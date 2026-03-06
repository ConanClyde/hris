<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import {
    Bot,
    Send,
    Trash2,
    ThumbsUp,
    ThumbsDown,
    Lightbulb,
    FileText,
    Calendar,
    Users,
    Database,
    Clock,
    Briefcase,
    Square,
    Plus,
    MessageSquare,
    Settings,
    Download,
    BarChart3,
    Activity,
    Shield,
    AlertTriangle,
    Pencil,
} from 'lucide-vue-next';
import { ref, onMounted, onBeforeUnmount, nextTick, watch } from 'vue';
import { computed } from 'vue';
import AIChatbot from '@/actions/App/Features/AIChatbot/Http/Controllers';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'AI Helpdesk' }];

const page = usePage();
const user = computed(() => page.props.auth?.user as any);
const showUserAvatar = computed(
    () =>
        typeof user.value?.avatar === 'string' &&
        user.value.avatar.trim() !== '',
);
const userName = user.value?.first_name || 'User';
const fullName = user.value?.name || userName;
const isAdmin = computed(() => user.value?.role === 'admin');
const isHr = computed(() => user.value?.role === 'hr');
const isEmployee = computed(() => user.value?.role === 'employee');
const canViewInsights = computed(
    () => isAdmin.value || isHr.value || isEmployee.value,
);
const { getInitials, getInitialsFromName } = useInitials();

const userInitials = computed(() => {
    return (
        getInitialsFromName({
            first_name: user.value?.first_name,
            last_name: user.value?.last_name,
        }) || getInitials(fullName)
    );
});

interface ChatMessage {
    id: string;
    role: 'user' | 'assistant';
    content: string;
    timestamp: Date;
    sources?: Array<{
        source?: string;
        display_name?: string;
        url?: string;
        confidence?: number;
    }>;
    meta?: Record<string, unknown>;
}

interface IngestionLog {
    id: number;
    embed: boolean;
    documents_indexed: number;
    chunks_created: number;
    duration_ms: number;
    status: string;
    error_message: string | null;
    created_at: string | null;
}

const CURRENT_CONVERSATION_KEY = 'hris-chat-current-conversation';

const getConversationIdFromUrl = (): string | null => {
    try {
        const params = new URLSearchParams(window.location.search);
        const id = params.get('conversation');

        return id && id.trim() !== '' ? id : null;
    } catch {
        return null;
    }
};

const updateConversationInUrl = (id: string | null) => {
    try {
        const url = new URL(window.location.href);
        if (id) {
            url.searchParams.set('conversation', id);
        } else {
            url.searchParams.delete('conversation');
        }
        window.history.replaceState({}, '', url.toString());
    } catch {
        // Ignore URL update errors in environments without window or URL
    }
};

// Conversation state
const conversations = ref<Conversation[]>([]);
const currentConversationId = ref<string | null>(
    getConversationIdFromUrl() ||
        localStorage.getItem(CURRENT_CONVERSATION_KEY) ||
        null,
);
const conversationsLoading = ref(false);
const showConversationsSidebar = ref(false);

// Conversation interfaces
interface Conversation {
    id: string;
    title: string;
    status: 'active' | 'archived';
    last_message_at?: string | null;
    created_at: string;
    message_count: number;
}

interface ConversationMessage {
    id: string;
    role: 'user' | 'assistant';
    content: string;
    sources?: Array<{
        source?: string;
        display_name?: string;
        url?: string;
        confidence?: number;
    }>;
    tool_used?: string;
    tool_data?: unknown;
    sequence_number: number;
    created_at: string;
}

const inputMessage = ref('');
const isLoading = ref(false);
const messages = ref<ChatMessage[]>([]);
const isIngesting = ref(false);
const ingestStatus = ref('');
const ingestLogs = ref<IngestionLog[]>([]);
const ingestLoading = ref(false);
const feedbackSummary = ref<{
    total: number;
    helpful: number;
    not_helpful: number;
} | null>(null);
const feedbackTopFailing = ref<Array<{ prompt: string; total: number }>>([]);
const feedbackMostDownvotedSources = ref<
    Array<{ source: string; display_name: string; count: number }>
>([]);
const feedbackSummaryLoading = ref(false);
const policyCoverage = ref<{
    missing: string[];
    outdated: Array<{ file: string; age_days: number; updated_at: string }>;
    extras: string[];
} | null>(null);
const policyCoverageLoading = ref(false);
const healthLoading = ref(false);
const healthStatus = ref<{
    status: string;
    tables?: Record<string, boolean>;
    features?: Record<string, boolean>;
    limits?: Record<string, number>;
    ollama?: {
        reachable: boolean;
        base_url: string;
        model: string;
        latency_ms: number | null;
        error: string | null;
    };
} | null>(null);
const activeTab = ref<'chat' | 'admin' | 'insights'>('chat');
const messagesContainer = ref<HTMLElement | null>(null);
const scrollAnchor = ref<HTMLElement | null>(null);
const textareaRef = ref<HTMLTextAreaElement | null>(null);
const abortController = ref<AbortController | null>(null);
const formattedCache = ref<Record<string, string>>({});
const feedbackState = ref<Record<string, 'up' | 'down'>>({});
const sourcesOpen = ref<Record<string, boolean>>({});
const lastErrorMessage = ref('');
const lastFailedInput = ref('');
const isSending = ref(false);
const lastSendAtMs = ref<number>(0);
const waitingForAiSlot = ref(false);
const aiWaitingStartedAt = ref<number | null>(null);
const analyticsLoading = ref(false);
const analyticsData = ref<{
    range: { days: number; start: string; end: string };
    daily: Array<{
        day: string;
        total: number;
        avg_total_ms: number;
        cache_hits: number;
        errors: number;
    }>;
    by_role: Array<{
        role: string;
        total: number;
        avg_total_ms: number;
        errors: number;
    }>;
    error_types: Array<{ error_type: string; total: number }>;
} | null>(null);
const sessionId = ref('');
if (!sessionId.value) {
    sessionId.value = `${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
}
const modelOptions = [
    {
        id: 'gemini-3.1-flash-lite-preview',
        label: 'Ultra',
        description: 'Fast, high-quality responses',
    },
    { id: 'qwen2.5:1.5b', label: 'Flash', description: 'Answers quickly' },
    {
        id: 'llama3.2:3b',
        label: 'Thinking',
        description: 'Solves complex problems',
    },
    {
        id: 'qwen3.5:2b',
        label: 'Pro',
        description: 'Advanced analysis and reasoning',
    },
];
const storedModel = localStorage.getItem('AI_CHATBOT_MODEL') || '';
const defaultModel = 'gemini-3.1-flash-lite-preview';
const selectedModel = ref(
    modelOptions.some((model) => model.id === storedModel)
        ? storedModel
        : defaultModel,
);
const selectedModelLabel = computed(() => {
    return (
        modelOptions.find((model) => model.id === selectedModel.value)?.label ??
        'Model'
    );
});
watch(selectedModel, (value) => {
    localStorage.setItem('AI_CHATBOT_MODEL', value);
});

const adjustTextareaHeight = () => {
    if (textareaRef.value) {
        textareaRef.value.style.height = 'auto';
        textareaRef.value.style.height = `${Math.min(textareaRef.value.scrollHeight, 128)}px`;
    }
};

const suggestionIcons: Record<string, any> = {
    database: Database,
    clock: Clock,
    users: Users,
    calendar: Calendar,
    briefcase: Briefcase,
    lightbulb: Lightbulb,
    file: FileText,
};

const emptySuggestions = ref<
    Array<{ id: string; title: string; icon: string }>
>([]);

let previousBodyOverflow = '';
let previousHtmlOverflow = '';

const scrollToBottom = async (): Promise<void> => {
    await nextTick();
    // Some UI changes (streaming, textarea resize) change layout after nextTick.
    await new Promise<void>((resolve) =>
        requestAnimationFrame(() => resolve()),
    );
    await new Promise<void>((resolve) =>
        requestAnimationFrame(() => resolve()),
    );

    if (scrollAnchor.value) {
        scrollAnchor.value.scrollIntoView({ block: 'end' });
        return;
    }

    const el = messagesContainer.value;
    if (!el) return;
    el.scrollTop = Math.max(0, el.scrollHeight - el.clientHeight);
};

watch(
    () => messages.value,
    () => {},
    { deep: true },
);

const recentIntents = ref<string[]>([]);

const loadSuggestions = async () => {
    try {
        const response = await axios.get('/api/ai/suggestions');
        const suggestions = Array.isArray(response.data?.suggestions)
            ? response.data.suggestions
            : [];
        recentIntents.value = Array.isArray(response.data?.recent_intents)
            ? response.data.recent_intents
            : [];
        emptySuggestions.value = reorderSuggestionsByRecent(
            suggestions,
            recentIntents.value,
        );
    } catch {
        emptySuggestions.value = [];
        recentIntents.value = [];
    }
};

function reorderSuggestionsByRecent(
    suggestions: Array<{ id: string; title: string; icon: string }>,
    intents: string[],
): Array<{ id: string; title: string; icon: string }> {
    if (intents.length === 0) return suggestions;
    const prefixes = [
        ...new Set(intents.map((id) => id.split('_')[0]).filter(Boolean)),
    ];
    return [...suggestions].sort((a, b) => {
        const aPrefix = a.id.split('_')[0] ?? '';
        const bPrefix = b.id.split('_')[0] ?? '';
        const aRank = prefixes.includes(aPrefix) ? 0 : 1;
        const bRank = prefixes.includes(bPrefix) ? 0 : 1;
        return aRank - bRank;
    });
}

const adminDataLoaded = ref(false);
const personalInsightsLoading = ref(false);
const personalFeedbackLoading = ref(false);
const personalInsightsData = ref<{
    conversation_count: number;
    message_count: number;
    recent_conversations: Array<{
        id: string;
        title: string;
        message_count: number;
        last_message_at: string;
        created_at: string;
    }>;
    model_usage: Array<{ model: string; count: number }>;
} | null>(null);
const personalFeedbackData = ref<{
    feedback: Array<{
        id: number;
        prompt: string;
        rating: number;
        response: string;
        created_at: string;
    }>;
    summary: { total: number; helpful: number; not_helpful: number };
} | null>(null);
const insightsDataLoaded = ref(false);
const lastChatScrollTop = ref<number>(0);
const loadAdminData = async () => {
    if (!isAdmin.value || adminDataLoaded.value) return;
    await Promise.all([
        loadIngestionLogs(),
        loadFeedbackSummary(),
        loadPolicyCoverage(),
        loadHealthStatus(),
        loadAnalytics(),
    ]);
    adminDataLoaded.value = true;
};

const loadPersonalInsights = async () => {
    if (personalInsightsLoading.value || personalInsightsData.value) return;
    personalInsightsLoading.value = true;
    try {
        const response = await axios.get('/api/ai/insights/personal');
        personalInsightsData.value = response.data ?? null;
    } catch {
        personalInsightsData.value = null;
    } finally {
        personalInsightsLoading.value = false;
    }
};

const loadPersonalFeedback = async () => {
    if (personalFeedbackLoading.value || personalFeedbackData.value) return;
    personalFeedbackLoading.value = true;
    try {
        const response = await axios.get('/api/ai/insights/feedback');
        personalFeedbackData.value = response.data ?? null;
    } catch {
        personalFeedbackData.value = null;
    } finally {
        personalFeedbackLoading.value = false;
    }
};

const loadInsightsData = async () => {
    if (insightsDataLoaded.value) return;
    await Promise.all([loadPersonalInsights(), loadPersonalFeedback()]);
    insightsDataLoaded.value = true;
};

watch(
    () => activeTab.value,
    async (tab, prevTab) => {
        if (prevTab === 'chat' && messagesContainer.value) {
            lastChatScrollTop.value = messagesContainer.value.scrollTop;
        }

        if (tab === 'admin') {
            void loadAdminData();
            return;
        }

        if (tab === 'insights') {
            void loadInsightsData();
            return;
        }

        if (tab === 'chat') {
            // If user was already at/near bottom, keep them at bottom.
            // Otherwise restore their previous scroll position.
            await nextTick();
            if (!messagesContainer.value) {
                await scrollToBottom();
                return;
            }

            const el = messagesContainer.value;
            const isNearBottom =
                el.scrollHeight - (el.scrollTop + el.clientHeight) < 96;
            if (isNearBottom || lastChatScrollTop.value === 0) {
                await scrollToBottom();
            } else {
                el.scrollTop = lastChatScrollTop.value;
            }
        }
    },
    { immediate: true },
);

onMounted(() => {
    previousBodyOverflow = document.body.style.overflow;
    previousHtmlOverflow = document.documentElement.style.overflow;
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
    void loadSuggestions();
    scrollToBottom();
});

onBeforeUnmount(() => {
    document.body.style.overflow = previousBodyOverflow;
    document.documentElement.style.overflow = previousHtmlOverflow;
});

const sendMessage = async () => {
    const nowMs = Date.now();
    if (!inputMessage.value.trim() || isLoading.value || isSending.value)
        return;
    if (nowMs - lastSendAtMs.value < 400) return;
    isSending.value = true;
    lastSendAtMs.value = nowMs;

    if (!currentConversationId.value) {
        await createNewConversation();
    }

    const userMessage: ChatMessage = {
        id: Date.now().toString(),
        role: 'user',
        content: inputMessage.value,
        timestamp: new Date(),
    };

    messages.value.push(userMessage);
    const currentInput = inputMessage.value;
    inputMessage.value = '';

    // Reset textarea height after sending
    adjustTextareaHeight();

    isLoading.value = true;
    lastErrorMessage.value = '';
    lastFailedInput.value = '';
    waitingForAiSlot.value = true;
    aiWaitingStartedAt.value = Date.now();
    await scrollToBottom();

    const history = messages.value
        .filter((m) => m.id !== userMessage.id)
        .slice(-30)
        .map((m) => ({
            role: m.role,
            content: m.content.slice(0, 2000),
        }));

    // Cancel any existing request
    if (abortController.value) {
        abortController.value.abort();
    }

    // Create a new controller for this request
    abortController.value = new AbortController();

    const assistantMessage: ChatMessage = {
        id: (Date.now() + 1).toString(),
        role: 'assistant',
        content: '',
        timestamp: new Date(),
        sources: [],
        meta: {},
    };
    messages.value.push(assistantMessage);
    formattedCache.value[assistantMessage.id] = '';

    const finalizeAssistantContent = (text: string) => {
        assistantMessage.content = text;
        formattedCache.value[assistantMessage.id] = formatMessage(
            assistantMessage.content,
        );
    };

    try {
        const response = await fetch(
            AIChatbot.AIChatbotController.chatStream.url(),
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'text/event-stream',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN':
                        (
                            document.querySelector(
                                'meta[name="csrf-token"]',
                            ) as HTMLMetaElement | null
                        )?.content || '',
                },
                body: JSON.stringify({
                    message: currentInput,
                    history,
                    session_id: sessionId.value,
                    model: selectedModel.value,
                    conversation_id: currentConversationId.value,
                }),
                signal: abortController.value.signal,
            },
        );

        if (!response.ok || !response.body) {
            throw new Error('Streaming request failed');
        }

        const reader = response.body.getReader();
        const decoder = new TextDecoder('utf-8');
        let buffer = '';
        let currentEvent = 'message';
        let currentData = '';

        const flushEvent = () => {
            const eventName = currentEvent;
            const dataText = currentData.trim();
            currentEvent = 'message';
            currentData = '';
            if (!dataText) return;

            let payload: any = null;
            try {
                payload = JSON.parse(dataText);
            } catch {
                payload = null;
            }

            if (eventName === 'delta') {
                const delta = payload?.delta;
                if (typeof delta === 'string' && delta.length > 0) {
                    waitingForAiSlot.value = false;
                    aiWaitingStartedAt.value = null;
                    assistantMessage.content += delta;
                    formattedCache.value[assistantMessage.id] = formatMessage(
                        assistantMessage.content,
                    );
                }
                return;
            }

            if (eventName === 'meta') {
                return;
            }

            if (eventName === 'end') {
                waitingForAiSlot.value = false;
                aiWaitingStartedAt.value = null;
                const finalResponse =
                    typeof payload?.response === 'string'
                        ? payload.response
                        : assistantMessage.content;
                const meta = payload?.meta ?? {};
                assistantMessage.meta = meta;
                assistantMessage.sources = Array.isArray(meta?.sources)
                    ? meta.sources
                    : [];
                finalizeAssistantContent(finalResponse);
                return;
            }

            if (eventName === 'error') {
                waitingForAiSlot.value = false;
                aiWaitingStartedAt.value = null;
                const errorMsg =
                    typeof payload?.error === 'string'
                        ? payload.error
                        : 'Failed to get response from AI. Please try again.';
                throw new Error(errorMsg);
            }
        };

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;
            buffer += decoder.decode(value, { stream: true });

            while (true) {
                const sepIndex = buffer.indexOf('\n\n');
                if (sepIndex === -1) break;
                const rawEvent = buffer.slice(0, sepIndex);
                buffer = buffer.slice(sepIndex + 2);

                const lines = rawEvent.split('\n');
                currentEvent = 'message';
                currentData = '';
                for (const line of lines) {
                    const trimmed = line.trim();
                    if (trimmed.startsWith('event:')) {
                        currentEvent =
                            trimmed.slice('event:'.length).trim() || 'message';
                    } else if (trimmed.startsWith('data:')) {
                        currentData += trimmed.slice('data:'.length).trim();
                    }
                }
                flushEvent();
            }
        }

        if (!assistantMessage.content) {
            finalizeAssistantContent(
                'Sorry, I could not process your request.',
            );
        }
    } catch (error) {
        if (
            axios.isCancel(error) ||
            (error as any)?.name === 'CanceledError' ||
            (error as any)?.code === 'ERR_CANCELED'
        )
            return;
        if ((error as any)?.name === 'AbortError') return;

        waitingForAiSlot.value = false;
        aiWaitingStartedAt.value = null;

        try {
            const response = await postChat({
                message: currentInput,
                history,
                session_id: sessionId.value,
                model: selectedModel.value,
            });

            assistantMessage.content =
                response.data.response ||
                'Sorry, I could not process your request.';
            assistantMessage.sources = Array.isArray(
                response.data?.meta?.sources,
            )
                ? response.data.meta.sources
                : [];
            assistantMessage.meta = response.data?.meta ?? {};
            formattedCache.value[assistantMessage.id] = formatMessage(
                assistantMessage.content,
            );
            return;
        } catch (fallbackError) {
            if (
                axios.isCancel(fallbackError) ||
                (fallbackError as any)?.name === 'CanceledError' ||
                (fallbackError as any)?.code === 'ERR_CANCELED'
            )
                return;

            const errorMessage = axios.isAxiosError(fallbackError)
                ? fallbackError.response?.data?.error ||
                  'Failed to get response from AI. Please try again.'
                : (error as any)?.message || 'An unexpected error occurred.';
            lastErrorMessage.value = errorMessage;
            lastFailedInput.value = currentInput;
            assistantMessage.content = errorMessage;
            assistantMessage.sources = [];
            assistantMessage.meta = {};
            formattedCache.value[assistantMessage.id] = formatMessage(
                assistantMessage.content,
            );
        }
    } finally {
        isLoading.value = false;
        abortController.value = null;
        isSending.value = false;
        waitingForAiSlot.value = false;
        aiWaitingStartedAt.value = null;
        await scrollToBottom();
    }
};

const stopGenerating = () => {
    if (abortController.value) {
        abortController.value.abort();
        abortController.value = null;
    }
    isLoading.value = false;
};

const retryLastMessage = async () => {
    if (!lastFailedInput.value || isLoading.value) return;
    inputMessage.value = lastFailedInput.value;
    lastFailedInput.value = '';
    await sendMessage();
};

const clearChat = () => {
    if (abortController.value) {
        abortController.value.abort();
        abortController.value = null;
    }

    messages.value = [];
    formattedCache.value = {};
    isLoading.value = false;
    void loadSuggestions();
};

const sendSuggestion = async (suggestion: { id: string; title: string }) => {
    if (isLoading.value) return;

    if (!currentConversationId.value) {
        await createNewConversation();
    }

    const conversationId = currentConversationId.value;
    if (!conversationId) return;

    const userMessage: ChatMessage = {
        id: Date.now().toString(),
        role: 'user',
        content: suggestion.title,
        timestamp: new Date(),
    };

    messages.value.push(userMessage);
    isLoading.value = true;
    await scrollToBottom();

    try {
        // Persist the user message
        await axios.post(`/api/ai/conversations/${conversationId}/messages`, {
            role: 'user',
            content: suggestion.title,
        });

        const response = await axios.post('/api/ai/suggestions/answer', {
            id: suggestion.id,
            question: suggestion.title,
        });

        const assistantMessage: ChatMessage = {
            id: (Date.now() + 1).toString(),
            role: 'assistant',
            content:
                response.data.answer ||
                'Sorry, I could not process your request.',
            timestamp: new Date(),
            sources: Array.isArray(response.data?.meta?.sources)
                ? response.data.meta.sources
                : [],
            meta: response.data?.meta ?? {},
        };

        messages.value.push(assistantMessage);
        formattedCache.value[assistantMessage.id] = formatMessage(
            assistantMessage.content,
        );

        // Persist the assistant message, including sources/meta where useful
        await axios.post(`/api/ai/conversations/${conversationId}/messages`, {
            role: 'assistant',
            content: assistantMessage.content,
            sources: assistantMessage.sources ?? [],
            tool_used: undefined,
            tool_data: assistantMessage.meta ?? {},
        });
    } catch (error) {
        const errorMessage = axios.isAxiosError(error)
            ? error.response?.data?.error ||
              'Failed to get response from AI. Please try again.'
            : 'An unexpected error occurred.';

        const assistantMessage: ChatMessage = {
            id: (Date.now() + 1).toString(),
            role: 'assistant',
            content: errorMessage,
            timestamp: new Date(),
            sources: [],
            meta: {},
        };

        messages.value.push(assistantMessage);
        formattedCache.value[assistantMessage.id] = formatMessage(
            assistantMessage.content,
        );

        try {
            await axios.post(
                `/api/ai/conversations/${conversationId}/messages`,
                {
                    role: 'assistant',
                    content: assistantMessage.content,
                },
            );
        } catch {
            // ignore logging errors for fallback
        }
    } finally {
        isLoading.value = false;
        await scrollToBottom();
    }
};

const triggerIngestion = async () => {
    if (isIngesting.value) return;
    isIngesting.value = true;
    ingestStatus.value = '';
    try {
        const response = await axios.post('/api/ai/ingest', { embed: true });
        const documents = response.data?.documents_indexed ?? 0;
        const chunks = response.data?.chunks_created ?? 0;
        ingestStatus.value = `Ingestion complete: ${documents} documents, ${chunks} chunks.`;
        await loadIngestionLogs();
    } catch (error) {
        ingestStatus.value = axios.isAxiosError(error)
            ? error.response?.data?.error || 'Ingestion failed.'
            : 'Ingestion failed.';
    } finally {
        isIngesting.value = false;
    }
};

const loadIngestionLogs = async () => {
    if (ingestLoading.value) return;
    ingestLoading.value = true;
    try {
        const response = await axios.get('/api/ai/ingest-logs');
        ingestLogs.value = Array.isArray(response.data?.logs)
            ? response.data.logs
            : [];
    } catch {
        ingestLogs.value = [];
    } finally {
        ingestLoading.value = false;
    }
};

const loadFeedbackSummary = async () => {
    if (feedbackSummaryLoading.value) return;
    feedbackSummaryLoading.value = true;
    try {
        const response = await axios.get('/api/ai/feedback/summary');
        feedbackSummary.value = response.data?.summary ?? null;
        feedbackTopFailing.value = Array.isArray(response.data?.top_failing)
            ? response.data.top_failing
            : [];
        feedbackMostDownvotedSources.value = Array.isArray(
            response.data?.most_downvoted_sources,
        )
            ? response.data.most_downvoted_sources
            : [];
    } catch {
        feedbackSummary.value = null;
        feedbackTopFailing.value = [];
        feedbackMostDownvotedSources.value = [];
    } finally {
        feedbackSummaryLoading.value = false;
    }
};

const loadPolicyCoverage = async () => {
    if (policyCoverageLoading.value) return;
    policyCoverageLoading.value = true;
    try {
        const response = await axios.get('/api/ai/policy-coverage');
        policyCoverage.value = response.data ?? null;
    } catch {
        policyCoverage.value = null;
    } finally {
        policyCoverageLoading.value = false;
    }
};

const loadHealthStatus = async () => {
    if (healthLoading.value) return;
    healthLoading.value = true;
    try {
        const response = await axios.get('/api/ai/health');
        healthStatus.value = response.data ?? null;
    } catch {
        healthStatus.value = null;
    } finally {
        healthLoading.value = false;
    }
};

const loadAnalytics = async () => {
    if (analyticsLoading.value) return;
    analyticsLoading.value = true;
    try {
        const response = await axios.get('/api/ai/analytics', {
            params: { days: 14 },
        });
        analyticsData.value = response.data ?? null;
    } catch {
        analyticsData.value = null;
    } finally {
        analyticsLoading.value = false;
    }
};

const sendFeedback = async (message: ChatMessage, rating: 'up' | 'down') => {
    if (message.role !== 'assistant') return;
    if (feedbackState.value[message.id]) return;
    feedbackState.value[message.id] = rating;
    const prompt = getPromptForMessage(message);
    try {
        await axios.post('/api/ai/feedback', {
            message_id: message.id,
            query_hash: message.meta?.query_hash ?? null,
            prompt,
            rating: rating === 'up' ? 1 : -1,
            response: message.content,
            sources: message.sources ?? [],
        });
    } catch {
        delete feedbackState.value[message.id];
    }
};

const getPromptForMessage = (message: ChatMessage) => {
    const index = messages.value.findIndex((item) => item.id === message.id);
    if (index <= 0) return '';
    for (let i = index - 1; i >= 0; i -= 1) {
        const candidate = messages.value[i];
        if (candidate.role === 'user') {
            return candidate.content;
        }
    }
    return '';
};

const feedbackExportUrl = (rating?: 'up' | 'down') => {
    if (rating === 'up') return '/api/ai/feedback/export?rating=1';
    if (rating === 'down') return '/api/ai/feedback/export?rating=-1';
    return '/api/ai/feedback/export';
};

const exportChatHistory = () => {
    const payload = messages.value.map((message) => ({
        role: message.role,
        content: message.content,
        timestamp: message.timestamp,
        sources: message.sources ?? [],
    }));
    const blob = new Blob([JSON.stringify(payload, null, 2)], {
        type: 'application/json',
    });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `ai-chat-history-${new Date().toISOString().slice(0, 10)}.json`;
    link.click();
    URL.revokeObjectURL(url);
};

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        if (isLoading.value) {
            stopGenerating();
            return;
        }
        sendMessage();
    }
};

const formatMessage = (content: string): string => {
    let html = content
        // Escape HTML first
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        // Bold: **text** or __text__
        .replace(/\*\*([\s\S]*?)\*\*/g, '<strong>$1</strong>')
        .replace(/__([\s\S]*?)__/g, '<strong>$1</strong>')
        // Italic: *text* or _text_
        .replace(/\*([\s\S]*?)\*/g, '<em>$1</em>')
        .replace(/_([\s\S]*?)_/g, '<em>$1</em>')
        // Code blocks: ```code```
        .replace(
            /```([\s\S]*?)```/g,
            '<pre class="bg-gray-100 dark:bg-neutral-800 p-2 rounded my-2 overflow-x-auto text-xs"><code>$1</code></pre>',
        )
        // Inline code: `code`
        .replace(
            /`([^`]+)`/g,
            '<code class="bg-gray-100 dark:bg-neutral-800 px-1 py-0.5 rounded text-xs">$1</code>',
        )
        // Lists: - item or * item
        .replace(/^[\-\*]\s+(.+)$/gm, '<li class="ml-4">$1</li>')
        // Numbered lists: 1. item
        .replace(/^\d+\.\s+(.+)$/gm, '<li class="ml-4 list-decimal">$1</li>')
        // Line breaks
        .replace(/\n/g, '<br>');

    // Wrap consecutive <li> elements in <ul>
    html = html.replace(
        /(<br>)?(<li class="ml-4">[\s\S]*?<\/li>)(<br>)?/g,
        '<ul class="my-1">$2</ul>',
    );

    return html;
};

const formattedMessage = (message: ChatMessage) => {
    if (message.role !== 'assistant') return '';
    return (
        formattedCache.value[message.id] ??
        (formattedCache.value[message.id] = formatMessage(message.content))
    );
};

// Conversation management methods
const loadConversations = async () => {
    if (conversationsLoading.value) return;
    conversationsLoading.value = true;
    try {
        const response = await axios.get('/api/ai/conversations');
        conversations.value = response.data?.data || [];
    } catch {
        conversations.value = [];
    } finally {
        conversationsLoading.value = false;
    }
};

const createNewConversation = async () => {
    try {
        const response = await axios.post('/api/ai/conversations');
        const newConversation = response.data?.data;
        if (newConversation) {
            currentConversationId.value = newConversation.id;
            conversations.value.unshift(newConversation);
            messages.value = [];
            formattedCache.value = {};
        }
    } catch (error) {
        console.error('Failed to create conversation:', error);
    }
};

const renamingConversationId = ref<string | null>(null);
const renameTitleDraft = ref('');

const startRenameConversation = (conversation: Conversation) => {
    renamingConversationId.value = conversation.id;
    renameTitleDraft.value = conversation.title;
};

const cancelRenameConversation = () => {
    renamingConversationId.value = null;
    renameTitleDraft.value = '';
};

const saveRenameConversation = async (conversationId: string) => {
    const nextTitle = renameTitleDraft.value.trim();
    if (!nextTitle) {
        cancelRenameConversation();
        return;
    }
    try {
        const response = await axios.put(
            `/api/ai/conversations/${conversationId}`,
            { title: nextTitle },
        );
        const updated = response.data?.data;
        const idx = conversations.value.findIndex(
            (c) => c.id === conversationId,
        );
        if (idx !== -1) {
            conversations.value[idx].title = updated?.title ?? nextTitle;
        }
        cancelRenameConversation();
    } catch (error) {
        console.error('Failed to rename conversation:', error);
    }
};

const loadConversation = async (conversationId: string) => {
    try {
        const response = await axios.get(
            `/api/ai/conversations/${conversationId}`,
        );
        const conversation = response.data?.data;
        if (conversation) {
            currentConversationId.value = conversationId;
            // Convert conversation messages to ChatMessage format
            messages.value = (conversation.messages || []).map(
                (msg: ConversationMessage) => ({
                    id: msg.id,
                    role: msg.role,
                    content: msg.content,
                    timestamp: new Date(msg.created_at),
                    sources: msg.sources,
                    meta: msg.tool_data
                        ? { data: msg.tool_data, tool_used: msg.tool_used }
                        : {},
                }),
            );
            formattedCache.value = {};
            await scrollToBottom();
        }
    } catch (error: any) {
        console.error('Failed to load conversation:', error);
        if (error.response?.status === 404) {
            // Conversation was likely deleted on backend or logged in as different user
            currentConversationId.value = null;
            localStorage.removeItem(CURRENT_CONVERSATION_KEY);
            messages.value = [];
            formattedCache.value = {};
        }
    }
};

const deleteConversation = async (conversationId: string) => {
    try {
        await axios.delete(`/api/ai/conversations/${conversationId}`);
        const index = conversations.value.findIndex(
            (c) => c.id === conversationId,
        );
        if (index !== -1) {
            conversations.value.splice(index, 1);
        }
        if (currentConversationId.value === conversationId) {
            currentConversationId.value = null;
            messages.value = [];
            formattedCache.value = {};
        }
    } catch (error) {
        console.error('Failed to delete conversation:', error);
    }
};

const toggleConversationsSidebar = () => {
    showConversationsSidebar.value = !showConversationsSidebar.value;
    if (showConversationsSidebar.value) {
        void loadConversations();
    }
};

defineExpose({
    createNewConversation,
    deleteConversation,
    toggleConversationsSidebar,
    Plus,
    MessageSquare,
});

// Update postChat to include conversation_id
const postChat = async (payload: {
    message: string;
    history: Array<{ role: string; content: string }>;
    session_id: string;
    model: string;
    conversation_id?: string | null;
}) => {
    let lastError: unknown = null;
    for (let attempt = 0; attempt < 2; attempt += 1) {
        try {
            return await axios.post(
                AIChatbot.AIChatbotController.chat.url(),
                { ...payload, conversation_id: currentConversationId.value },
                {
                    signal: abortController.value?.signal,
                },
            );
        } catch (error) {
            lastError = error;
            if (attempt === 0) {
                await new Promise((resolve) => setTimeout(resolve, 400));
                continue;
            }
        }
    }
    throw lastError;
};

watch(
    () => currentConversationId.value,
    (id) => {
        if (id) {
            localStorage.setItem(CURRENT_CONVERSATION_KEY, id);
        } else {
            localStorage.removeItem(CURRENT_CONVERSATION_KEY);
        }
        updateConversationInUrl(id);
    },
);

// Load conversations on mount
onMounted(() => {
    void loadConversations();
    const fromUrl = getConversationIdFromUrl();
    const initialId = fromUrl || currentConversationId.value;
    if (initialId) {
        currentConversationId.value = initialId;
        void loadConversation(initialId);
    }
});
</script>

<template>
    <Head title="AI Helpdesk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="relative flex h-[calc(100vh-4rem)] min-h-0 overflow-hidden bg-white dark:bg-neutral-900"
        >
            <!-- Conversations Sidebar -->
            <div
                :class="[
                    'flex flex-col border-r border-gray-200 bg-gray-50 transition-all duration-300 dark:border-neutral-800 dark:bg-neutral-900',
                    showConversationsSidebar ? 'w-80' : 'w-0 overflow-hidden',
                ]"
            >
                <!-- Sidebar Header -->
                <div
                    class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-neutral-800"
                >
                    <h2
                        class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Chats
                    </h2>
                    <div class="flex items-center gap-1">
                        <Button
                            size="icon"
                            variant="ghost"
                            class="size-8"
                            @click="createNewConversation"
                            title="New chat"
                        >
                            <Plus class="size-4" />
                        </Button>
                        <Button
                            size="icon"
                            variant="ghost"
                            class="size-8"
                            @click="toggleConversationsSidebar"
                            title="Close sidebar"
                        >
                            <MessageSquare class="size-4" />
                        </Button>
                    </div>
                </div>

                <!-- New Chat Button -->
                <div class="p-3">
                    <Button
                        variant="outline"
                        class="w-full justify-start gap-2"
                        @click="createNewConversation"
                    >
                        <Plus class="size-4" />
                        New chat
                    </Button>
                </div>

                <!-- Conversations List -->
                <div class="flex-1 overflow-y-auto">
                    <div
                        v-if="conversationsLoading"
                        class="p-4 text-center text-sm text-gray-500"
                    >
                        Loading...
                    </div>
                    <div
                        v-else-if="conversations.length === 0"
                        class="p-4 text-center text-sm text-gray-500"
                    >
                        No conversations yet
                    </div>
                    <div v-else class="space-y-1 p-2">
                        <div
                            v-for="conversation in conversations"
                            :key="conversation.id"
                            :class="[
                                'group flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 transition-colors',
                                currentConversationId === conversation.id
                                    ? 'bg-brand/10 text-brand dark:bg-brand/20'
                                    : 'hover:bg-gray-100 dark:hover:bg-neutral-800',
                            ]"
                            @click="loadConversation(conversation.id)"
                        >
                            <MessageSquare
                                class="size-4 shrink-0 text-gray-400"
                            />
                            <div class="min-w-0 flex-1">
                                <input
                                    v-if="
                                        renamingConversationId ===
                                        conversation.id
                                    "
                                    v-model="renameTitleDraft"
                                    class="w-full border-b border-dashed border-gray-300 bg-transparent text-sm font-medium text-gray-900 focus:outline-none dark:border-neutral-700 dark:text-gray-100"
                                    @click.stop
                                    @keydown.enter.prevent="
                                        saveRenameConversation(conversation.id)
                                    "
                                    @keydown.esc.prevent="
                                        cancelRenameConversation
                                    "
                                />
                                <p
                                    v-else
                                    class="truncate text-sm font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ conversation.title }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ conversation.message_count }} messages ·
                                    {{
                                        conversation.last_message_at
                                            ? new Date(
                                                  conversation.last_message_at,
                                              ).toLocaleDateString()
                                            : '—'
                                    }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                <template
                                    v-if="
                                        renamingConversationId ===
                                        conversation.id
                                    "
                                >
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        class="h-6 px-2 text-[11px]"
                                        @click.stop="
                                            saveRenameConversation(
                                                conversation.id,
                                            )
                                        "
                                    >
                                        Save
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        class="h-6 px-2 text-[11px]"
                                        @click.stop="cancelRenameConversation"
                                    >
                                        Cancel
                                    </Button>
                                </template>
                                <template v-else>
                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        class="size-6"
                                        @click.stop="
                                            startRenameConversation(
                                                conversation,
                                            )
                                        "
                                        title="Rename"
                                    >
                                        <Pencil class="size-3" />
                                    </Button>
                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        class="size-6 text-red-500 hover:text-red-600"
                                        @click.stop="
                                            deleteConversation(conversation.id)
                                        "
                                        title="Delete"
                                    >
                                        <Trash2 class="size-3" />
                                    </Button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden">
                <!-- Header -->
                <div
                    class="z-10 flex h-12 shrink-0 items-center justify-between bg-white px-4 dark:bg-neutral-900"
                >
                    <div class="flex items-center">
                        <Button
                            v-if="!showConversationsSidebar"
                            size="sm"
                            variant="ghost"
                            class="h-9 gap-2 px-3"
                            @click="toggleConversationsSidebar"
                        >
                            <MessageSquare class="size-4" />
                            Chats
                        </Button>
                    </div>

                    <DropdownMenu v-if="canViewInsights">
                        <DropdownMenuTrigger as-child>
                            <Button
                                size="sm"
                                variant="ghost"
                                class="h-9 gap-2 px-3"
                            >
                                <Settings class="size-4" />
                                Menu
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-48">
                            <DropdownMenuItem
                                @click="activeTab = 'chat'"
                                :class="{ 'bg-accent': activeTab === 'chat' }"
                            >
                                <MessageSquare class="mr-2 size-4" />
                                Helpdesk
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                v-if="isAdmin"
                                @click="activeTab = 'admin'"
                                :class="{ 'bg-accent': activeTab === 'admin' }"
                            >
                                <Settings class="mr-2 size-4" />
                                Admin Tools
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                @click="activeTab = 'insights'"
                                :class="{
                                    'bg-accent': activeTab === 'insights',
                                }"
                            >
                                <BarChart3 class="mr-2 size-4" />
                                My Insights
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <div v-else></div>
                </div>

                <!-- Main Content Area (properly nested) -->
                <div
                    class="flex min-h-0 flex-1 flex-col overflow-y-auto scroll-smooth"
                    :class="[
                        messages.length === 0 &&
                        (!isAdmin || activeTab === 'chat')
                            ? 'justify-center p-4 md:p-8'
                            : 'p-4 md:p-8',
                    ]"
                    ref="messagesContainer"
                >
                    <!-- Admin Tools Dashboard -->
                    <div
                        v-if="isAdmin && activeTab === 'admin'"
                        class="mx-auto w-full max-w-5xl space-y-6 pt-2 pb-8"
                    >
                        <!-- Page Title -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h2
                                    class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    Admin Tools
                                </h2>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Manage AI chatbot settings and monitor
                                    performance
                                </p>
                            </div>
                        </div>

                        <!-- Quick Actions Grid -->
                        <div
                            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
                        >
                            <!-- AI Policy Ingestion Card -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                                        >
                                            <Database class="size-5" />
                                        </div>
                                        <div>
                                            <h3
                                                class="font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                Policy Ingestion
                                            </h3>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                Rebuild embeddings
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="mt-3 text-xs text-gray-600 dark:text-gray-400"
                                >
                                    Rebuild document chunks and embeddings for
                                    retrieval.
                                </p>
                                <div class="mt-4 flex items-center gap-2">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        :disabled="isIngesting"
                                        @click="triggerIngestion"
                                        class="w-full"
                                    >
                                        <Database class="mr-2 size-4" />
                                        {{
                                            isIngesting
                                                ? 'Ingesting…'
                                                : 'Run Ingestion'
                                        }}
                                    </Button>
                                </div>
                                <p
                                    v-if="ingestStatus"
                                    class="mt-2 text-xs text-emerald-600 dark:text-emerald-400"
                                >
                                    {{ ingestStatus }}
                                </p>
                            </div>

                            <!-- Feedback Export Card -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400"
                                        >
                                            <Download class="size-5" />
                                        </div>
                                        <div>
                                            <h3
                                                class="font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                Feedback Export
                                            </h3>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                Download data
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="mt-3 text-xs text-gray-600 dark:text-gray-400"
                                >
                                    Download feedback data for training and
                                    evaluation.
                                </p>
                                <div class="mt-4 flex items-center gap-2">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        as-child
                                        class="flex-1"
                                    >
                                        <a :href="feedbackExportUrl()">All</a>
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        as-child
                                        class="flex-1"
                                    >
                                        <a :href="feedbackExportUrl('up')"
                                            >Helpful</a
                                        >
                                    </Button>
                                </div>
                            </div>

                            <!-- Analytics Export Card -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400"
                                        >
                                            <BarChart3 class="size-5" />
                                        </div>
                                        <div>
                                            <h3
                                                class="font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                Analytics Export
                                            </h3>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                Metrics data
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="mt-3 text-xs text-gray-600 dark:text-gray-400"
                                >
                                    Export chatbot analytics and usage metrics.
                                </p>
                                <div class="mt-4">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        as-child
                                        class="w-full"
                                    >
                                        <a href="/api/ai/metrics/export">
                                            <Download class="mr-2 size-4" />
                                            Export Metrics
                                        </a>
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Overview -->
                        <div
                            v-if="feedbackSummary"
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400"
                                    >
                                        <ThumbsUp class="size-5" />
                                    </div>
                                    <div>
                                        <h3
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            Feedback Overview
                                        </h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            User satisfaction stats
                                        </p>
                                    </div>
                                </div>
                                <Button
                                    size="sm"
                                    variant="ghost"
                                    :disabled="feedbackSummaryLoading"
                                    @click="loadFeedbackSummary"
                                >
                                    {{
                                        feedbackSummaryLoading
                                            ? 'Loading…'
                                            : 'Refresh'
                                    }}
                                </Button>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div
                                    class="rounded-lg bg-gray-50 p-3 text-center dark:bg-neutral-800"
                                >
                                    <p
                                        class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                                    >
                                        {{ feedbackSummary.total }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Total Feedback
                                    </p>
                                </div>
                                <div
                                    class="rounded-lg bg-emerald-50 p-3 text-center dark:bg-emerald-900/20"
                                >
                                    <p
                                        class="text-2xl font-bold text-emerald-600 dark:text-emerald-400"
                                    >
                                        {{ feedbackSummary.helpful }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Helpful
                                    </p>
                                </div>
                                <div
                                    class="rounded-lg bg-rose-50 p-3 text-center dark:bg-rose-900/20"
                                >
                                    <p
                                        class="text-2xl font-bold text-rose-600 dark:text-rose-400"
                                    >
                                        {{ feedbackSummary.not_helpful }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Not Helpful
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- System Status Grid -->
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <!-- Service Health -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div
                                    class="mb-4 flex items-center justify-between"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-100 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400"
                                        >
                                            <Activity class="size-5" />
                                        </div>
                                        <div>
                                            <h3
                                                class="font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                Service Health
                                            </h3>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                AI system status
                                            </p>
                                        </div>
                                    </div>
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        :disabled="healthLoading"
                                        @click="loadHealthStatus"
                                    >
                                        {{
                                            healthLoading
                                                ? 'Loading…'
                                                : 'Refresh'
                                        }}
                                    </Button>
                                </div>
                                <div
                                    v-if="!healthStatus"
                                    class="py-4 text-center text-sm text-gray-500 dark:text-gray-400"
                                >
                                    No health data available
                                </div>
                                <div v-else class="space-y-3">
                                    <div
                                        class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-neutral-800"
                                    >
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-medium text-gray-700 dark:text-gray-200"
                                                >Ollama</span
                                            >
                                            <span
                                                :class="
                                                    healthStatus.ollama
                                                        ?.reachable
                                                        ? 'text-emerald-600 dark:text-emerald-400'
                                                        : 'text-rose-600 dark:text-rose-400'
                                                "
                                            >
                                                {{
                                                    healthStatus.ollama
                                                        ?.reachable
                                                        ? '● Online'
                                                        : '● Offline'
                                                }}
                                            </span>
                                        </div>
                                        <span
                                            v-if="
                                                healthStatus.ollama
                                                    ?.latency_ms !== null
                                            "
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                healthStatus.ollama?.latency_ms
                                            }}ms
                                        </span>
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{
                                            healthStatus.ollama?.base_url || '—'
                                        }}
                                    </div>
                                    <div
                                        v-if="healthStatus.ollama?.error"
                                        class="rounded bg-rose-50 p-2 text-xs text-rose-500 dark:bg-rose-900/20"
                                    >
                                        {{ healthStatus.ollama?.error }}
                                    </div>
                                </div>
                            </div>

                            <!-- Policy Coverage -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div
                                    class="mb-4 flex items-center justify-between"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400"
                                        >
                                            <Shield class="size-5" />
                                        </div>
                                        <div>
                                            <h3
                                                class="font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                Policy Coverage
                                            </h3>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                Document status
                                            </p>
                                        </div>
                                    </div>
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        :disabled="policyCoverageLoading"
                                        @click="loadPolicyCoverage"
                                    >
                                        {{
                                            policyCoverageLoading
                                                ? 'Loading…'
                                                : 'Refresh'
                                        }}
                                    </Button>
                                </div>
                                <div
                                    v-if="!policyCoverage"
                                    class="py-4 text-center text-sm text-gray-500 dark:text-gray-400"
                                >
                                    No coverage data available
                                </div>
                                <div v-else class="space-y-3">
                                    <div
                                        class="flex items-center justify-between rounded-lg bg-gray-50 p-2 dark:bg-neutral-800"
                                    >
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                            >Missing Files</span
                                        >
                                        <span
                                            :class="
                                                policyCoverage.missing
                                                    .length === 0
                                                    ? 'text-emerald-600 dark:text-emerald-400'
                                                    : 'text-amber-600 dark:text-amber-400'
                                            "
                                            class="font-medium"
                                        >
                                            {{
                                                policyCoverage.missing
                                                    .length === 0
                                                    ? 'None'
                                                    : policyCoverage.missing
                                                          .length
                                            }}
                                        </span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between rounded-lg bg-gray-50 p-2 dark:bg-neutral-800"
                                    >
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                            >Outdated Files</span
                                        >
                                        <span
                                            :class="
                                                policyCoverage.outdated
                                                    .length === 0
                                                    ? 'text-emerald-600 dark:text-emerald-400'
                                                    : 'text-amber-600 dark:text-amber-400'
                                            "
                                            class="font-medium"
                                        >
                                            {{
                                                policyCoverage.outdated
                                                    .length === 0
                                                    ? 'None'
                                                    : policyCoverage.outdated
                                                          .length
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ingestion Logs -->
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-900/30 dark:text-slate-400"
                                    >
                                        <Clock class="size-5" />
                                    </div>
                                    <div>
                                        <h3
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            Recent Ingestion Runs
                                        </h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            Last 10 runs
                                        </p>
                                    </div>
                                </div>
                                <Button
                                    size="sm"
                                    variant="ghost"
                                    :disabled="ingestLoading"
                                    @click="loadIngestionLogs"
                                >
                                    {{ ingestLoading ? 'Loading…' : 'Refresh' }}
                                </Button>
                            </div>
                            <div
                                v-if="ingestLogs.length === 0"
                                class="py-8 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                No ingestion history yet
                            </div>
                            <div v-else class="space-y-2">
                                <div
                                    v-for="log in ingestLogs.slice(0, 5)"
                                    :key="log.id"
                                    class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-neutral-800"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            :class="
                                                log.status === 'success'
                                                    ? 'text-emerald-600 dark:text-emerald-400'
                                                    : 'text-rose-600 dark:text-rose-400'
                                            "
                                        >
                                            {{
                                                log.status === 'success'
                                                    ? '✓'
                                                    : '✗'
                                            }}
                                        </span>
                                        <span
                                            class="text-sm text-gray-700 dark:text-gray-200"
                                            >{{ log.documents_indexed }} docs,
                                            {{ log.chunks_created }}
                                            chunks</span
                                        >
                                    </div>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                        >{{ log.duration_ms }}ms</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Top Failing Prompts -->
                        <div
                            v-if="feedbackTopFailing.length"
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                        >
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400"
                                >
                                    <AlertTriangle class="size-5" />
                                </div>
                                <div>
                                    <h3
                                        class="font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Top Failing Prompts
                                    </h3>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Needs attention
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div
                                    v-for="item in feedbackTopFailing.slice(
                                        0,
                                        5,
                                    )"
                                    :key="item.prompt"
                                    class="flex items-center justify-between rounded-lg bg-rose-50 p-3 dark:bg-rose-900/10"
                                >
                                    <span
                                        class="max-w-md truncate text-sm text-gray-700 dark:text-gray-200"
                                        >{{ item.prompt }}</span
                                    >
                                    <span
                                        class="text-sm font-medium text-rose-600 dark:text-rose-400"
                                        >{{ item.total }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Most Downvoted Policy Sources -->
                        <div
                            v-if="feedbackMostDownvotedSources.length"
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                        >
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400"
                                >
                                    <FileText class="size-5" />
                                </div>
                                <div>
                                    <h3
                                        class="font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Most Downvoted Policy Sources
                                    </h3>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Consider updating these documents
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div
                                    v-for="item in feedbackMostDownvotedSources"
                                    :key="item.source"
                                    class="flex items-center justify-between rounded-lg bg-amber-50 p-3 dark:bg-amber-900/10"
                                >
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-200"
                                        >{{
                                            item.display_name || item.source
                                        }}</span
                                    >
                                    <span
                                        class="text-sm font-medium text-amber-600 dark:text-amber-400"
                                        >{{ item.count }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Insights Dashboard (For HR, Employee, and Admin) -->
                    <div
                        v-if="activeTab === 'insights'"
                        class="mx-auto w-full max-w-5xl space-y-6 pt-2 pb-8"
                    >
                        <!-- Page Title -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h2
                                    class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    My Insights
                                </h2>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Your personal AI Helpdesk activity and
                                    feedback
                                </p>
                            </div>
                        </div>

                        <!-- Personal Stats Grid -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Total Conversations -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                                    >
                                        <MessageSquare class="size-5" />
                                    </div>
                                    <div>
                                        <h3
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            Conversations
                                        </h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            Total chats
                                        </p>
                                    </div>
                                </div>
                                <p
                                    class="mt-3 text-2xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    {{ conversations.length }}
                                </p>
                            </div>

                            <!-- Total Messages -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400"
                                    >
                                        <BarChart3 class="size-5" />
                                    </div>
                                    <div>
                                        <h3
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            Messages
                                        </h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            Total exchanged
                                        </p>
                                    </div>
                                </div>
                                <p
                                    class="mt-3 text-2xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    {{
                                        conversations.reduce(
                                            (sum, c) =>
                                                sum + (c.message_count || 0),
                                            0,
                                        )
                                    }}
                                </p>
                            </div>

                            <!-- Feedback Given -->
                            <div
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400"
                                    >
                                        <ThumbsUp class="size-5" />
                                    </div>
                                    <div>
                                        <h3
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            Feedback
                                        </h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            Ratings given
                                        </p>
                                    </div>
                                </div>
                                <p
                                    class="mt-3 text-2xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    {{ Object.keys(feedbackState).length }}
                                </p>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-100 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400"
                                    >
                                        <Clock class="size-5" />
                                    </div>
                                    <div>
                                        <h3
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            Recent Conversations
                                        </h3>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            Your chat history
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="conversations.length === 0"
                                class="py-8 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                No conversations yet. Start chatting to see your
                                activity!
                            </div>
                            <div v-else class="space-y-2">
                                <div
                                    v-for="conversation in conversations.slice(
                                        0,
                                        5,
                                    )"
                                    :key="conversation.id"
                                    class="flex cursor-pointer items-center justify-between rounded-lg bg-gray-50 p-3 hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700"
                                    @click="
                                        loadConversation(conversation.id);
                                        activeTab = 'chat';
                                    "
                                >
                                    <div class="flex items-center gap-3">
                                        <MessageSquare
                                            class="size-4 text-gray-400"
                                        />
                                        <div>
                                            <p
                                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                {{ conversation.title }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ conversation.message_count }}
                                                messages ·
                                                {{
                                                    conversation.last_message_at
                                                        ? new Date(
                                                              conversation.last_message_at,
                                                          ).toLocaleDateString()
                                                        : '—'
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <Button size="sm" variant="ghost"
                                        >Open</Button
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Export Data -->
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-900/30 dark:text-slate-400"
                                >
                                    <Download class="size-5" />
                                </div>
                                <div>
                                    <h3
                                        class="font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Export Your Data
                                    </h3>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Download your chat history
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <Button
                                    size="sm"
                                    variant="outline"
                                    @click="exportChatHistory"
                                >
                                    <Download class="mr-2 size-4" />
                                    Export Chat History
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- EMPTY STATE -->
                    <div
                        v-if="
                            messages.length === 0 &&
                            (activeTab === 'chat' || activeTab === 'insights')
                        "
                        class="mx-auto w-full max-w-2xl space-y-8 pb-12"
                    >
                        <h1
                            class="mb-8 text-center text-3xl font-medium text-gray-900 dark:text-gray-100"
                        >
                            What can I help with?
                        </h1>

                        <div
                            class="relative flex flex-col gap-2 rounded-2xl border border-gray-300 bg-white p-3 shadow-sm focus-within:border-brand focus-within:ring-1 focus-within:ring-brand dark:border-neutral-700 dark:bg-neutral-800 dark:focus-within:border-brand"
                        >
                            <textarea
                                ref="textareaRef"
                                v-model="inputMessage"
                                rows="1"
                                class="max-h-40 min-h-[44px] w-full resize-none border-0 bg-transparent px-2 py-1.5 text-base placeholder:text-gray-500 focus:ring-0 focus:outline-none dark:text-gray-100"
                                placeholder="Message AI Assistant..."
                                :disabled="isLoading"
                                @keydown="handleKeydown"
                                @input="adjustTextareaHeight"
                            ></textarea>
                            <div class="flex items-center justify-end gap-2">
                                <Select v-model="selectedModel">
                                    <SelectTrigger
                                        class="h-8 w-auto justify-end px-2 text-right"
                                    >
                                        <SelectValue
                                            class="sr-only"
                                            placeholder="Model"
                                        />
                                        <span
                                            class="text-right text-xs font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            {{ selectedModelLabel }}
                                        </span>
                                    </SelectTrigger>
                                    <SelectContent class="w-[260px]">
                                        <SelectItem
                                            v-for="model in modelOptions"
                                            :key="model.id"
                                            :value="model.id"
                                            class="py-2"
                                        >
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                                    >{{ model.label }}</span
                                                >
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                    >{{
                                                        model.description
                                                    }}</span
                                                >
                                            </div>
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <Button
                                    size="icon"
                                    @click="
                                        isLoading
                                            ? stopGenerating()
                                            : sendMessage()
                                    "
                                    :disabled="
                                        !isLoading && !inputMessage.trim()
                                    "
                                    class="size-8 rounded-full bg-brand text-white transition-colors hover:bg-brand-dark dark:bg-brand dark:hover:bg-brand-dark"
                                >
                                    <Square v-if="isLoading" class="size-4" />
                                    <Send v-else class="size-4" />
                                </Button>
                            </div>
                        </div>

                        <div
                            v-if="lastErrorMessage"
                            class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-300"
                        >
                            <span class="flex-1">{{ lastErrorMessage }}</span>
                            <Button
                                size="sm"
                                variant="ghost"
                                class="h-7 px-2 text-xs"
                                @click="retryLastMessage"
                            >
                                Retry
                            </Button>
                        </div>

                        <div
                            v-if="isLoading && waitingForAiSlot"
                            class="mt-3 text-xs text-gray-500 dark:text-gray-400"
                        >
                            Waiting for an available AI slot…
                        </div>

                        <div class="mt-8 grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div
                                v-for="(suggestion, idx) in emptySuggestions"
                                :key="suggestion.title"
                                class="cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 text-sm text-gray-600 transition-colors hover:bg-gray-50 dark:border-neutral-800 dark:text-gray-300 dark:hover:bg-neutral-800/50"
                                :class="
                                    idx >= 6
                                        ? 'hidden'
                                        : idx >= 3
                                          ? 'hidden md:flex'
                                          : 'flex'
                                "
                                @click="sendSuggestion(suggestion)"
                            >
                                <component
                                    :is="
                                        suggestionIcons[suggestion.icon] ||
                                        Lightbulb
                                    "
                                    class="mt-0.5 size-5 shrink-0 text-gray-400"
                                />
                                <span class="leading-relaxed">{{
                                    suggestion.title
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- CHAT STATE -->
                    <div
                        v-else-if="activeTab === 'chat'"
                        class="mx-auto w-full max-w-3xl space-y-8 pb-4"
                    >
                        <!-- Message Item -->
                        <div
                            v-for="message in messages"
                            :key="message.id"
                            :class="[
                                'flex w-full gap-5',
                                message.role === 'user'
                                    ? 'flex-row-reverse'
                                    : 'flex-row',
                            ]"
                        >
                            <!-- Avatar -->
                            <div
                                v-if="message.role === 'user'"
                                class="shrink-0 pt-0.5"
                            >
                                <Avatar
                                    class="size-8 shrink-0 overflow-hidden rounded-lg"
                                >
                                    <AvatarImage
                                        v-if="showUserAvatar"
                                        :src="user.avatar"
                                        :alt="fullName"
                                    />
                                    <AvatarFallback
                                        class="rounded-lg bg-foreground text-xs font-bold text-background"
                                    >
                                        {{ userInitials }}
                                    </AvatarFallback>
                                </Avatar>
                            </div>
                            <div
                                v-else
                                class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 shadow-sm dark:bg-amber-900/30 dark:text-amber-500"
                            >
                                <Bot class="size-5" />
                            </div>

                            <!-- Content -->
                            <div
                                :class="[
                                    'flex max-w-[85%] flex-col space-y-1',
                                    message.role === 'user'
                                        ? 'items-end'
                                        : 'items-start',
                                ]"
                            >
                                <div
                                    class="mb-0.5 text-xs font-semibold text-gray-900 text-muted-foreground dark:text-gray-100"
                                >
                                    {{
                                        message.role === 'user'
                                            ? 'You'
                                            : 'AI Assistant'
                                    }}
                                </div>
                                <div
                                    :class="[
                                        'rounded-xl px-4 py-2.5 text-sm leading-relaxed',
                                        message.role === 'user'
                                            ? 'rounded-tr-sm bg-brand text-left text-primary-foreground dark:bg-brand'
                                            : 'rounded-tl-sm bg-muted/50 text-left text-gray-800 dark:text-gray-200',
                                    ]"
                                >
                                    <span
                                        v-if="message.role === 'user'"
                                        class="whitespace-pre-wrap"
                                        >{{ message.content }}</span
                                    >
                                    <template v-else>
                                        <div
                                            v-show="
                                                isLoading &&
                                                message.id ===
                                                    messages[
                                                        messages.length - 1
                                                    ].id
                                            "
                                            class="mb-2 flex h-5 items-center gap-1"
                                        >
                                            <div
                                                class="size-1.5 animate-bounce rounded-full bg-brand/60"
                                                style="animation-delay: 0ms"
                                            />
                                            <div
                                                class="size-1.5 animate-bounce rounded-full bg-brand/60"
                                                style="animation-delay: 150ms"
                                            />
                                            <div
                                                class="size-1.5 animate-bounce rounded-full bg-brand/60"
                                                style="animation-delay: 300ms"
                                            />
                                        </div>
                                        <span
                                            v-if="message.content"
                                            v-html="formattedMessage(message)"
                                            class="chatbot-prose"
                                        ></span>
                                    </template>
                                </div>
                                <button
                                    v-if="
                                        message.role === 'assistant' &&
                                        message.sources &&
                                        message.sources.length
                                    "
                                    type="button"
                                    class="text-xs text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80"
                                    @click="
                                        sourcesOpen[message.id] =
                                            !sourcesOpen[message.id]
                                    "
                                >
                                    {{
                                        sourcesOpen[message.id]
                                            ? 'Hide context'
                                            : 'Context used'
                                    }}
                                </button>
                                <div
                                    v-if="
                                        message.role === 'assistant' &&
                                        message.sources &&
                                        message.sources.length &&
                                        sourcesOpen[message.id]
                                    "
                                    class="rounded-lg border border-gray-200 bg-white/70 px-3 py-2 text-xs text-gray-600 dark:border-neutral-800 dark:bg-neutral-900/70 dark:text-gray-300"
                                >
                                    <div
                                        class="font-medium text-gray-800 dark:text-gray-100"
                                    >
                                        Context used
                                    </div>
                                    <ul class="mt-1 space-y-1">
                                        <li
                                            v-for="source in message.sources"
                                            :key="source.source ?? source.url"
                                        >
                                            <a
                                                v-if="source.url"
                                                class="text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80"
                                                :href="source.url"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                {{
                                                    source.display_name ??
                                                    source.source ??
                                                    source.url
                                                }}
                                            </a>
                                            <span v-else>{{
                                                source.display_name ??
                                                source.source
                                            }}</span>
                                            <span
                                                v-if="
                                                    typeof source.confidence ===
                                                    'number'
                                                "
                                                class="text-gray-400 dark:text-gray-500"
                                            >
                                                ·
                                                {{
                                                    source.confidence.toFixed(2)
                                                }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div
                                    v-if="
                                        message.role === 'assistant' &&
                                        (!isLoading ||
                                            message.id !==
                                                messages[messages.length - 1]
                                                    .id)
                                    "
                                    class="flex w-full flex-col gap-2"
                                >
                                    <div
                                        class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-full border border-gray-200 px-2 py-1 text-xs hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-neutral-800 dark:hover:bg-neutral-800/60"
                                            :class="
                                                feedbackState[message.id] ===
                                                'up'
                                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-300'
                                                    : ''
                                            "
                                            :disabled="
                                                Boolean(
                                                    feedbackState[message.id],
                                                )
                                            "
                                            @click="sendFeedback(message, 'up')"
                                        >
                                            <ThumbsUp class="size-3" />
                                            Helpful
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-full border border-gray-200 px-2 py-1 text-xs hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-neutral-800 dark:hover:bg-neutral-800/60"
                                            :class="
                                                feedbackState[message.id] ===
                                                'down'
                                                    ? 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-300'
                                                    : ''
                                            "
                                            :disabled="
                                                Boolean(
                                                    feedbackState[message.id],
                                                )
                                            "
                                            @click="
                                                sendFeedback(message, 'down')
                                            "
                                        >
                                            <ThumbsDown class="size-3" />
                                            Not helpful
                                        </button>
                                    </div>
                                    <div
                                        v-if="feedbackState[message.id]"
                                        class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Thanks for the feedback.
                                    </div>
                                    <div
                                        v-if="
                                            Array.isArray(
                                                message.meta?.followups,
                                            ) && message.meta?.followups?.length
                                        "
                                        class="mt-2 flex flex-wrap gap-2"
                                    >
                                        <button
                                            v-for="followup in message.meta
                                                ?.followups"
                                            :key="followup.id"
                                            type="button"
                                            class="rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-600 transition-colors hover:bg-gray-50 dark:border-neutral-800 dark:text-gray-300 dark:hover:bg-neutral-800/60"
                                            @click="
                                                sendSuggestion({
                                                    id: followup.id,
                                                    title: followup.title,
                                                })
                                            "
                                        >
                                            {{ followup.title }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div ref="scrollAnchor" class="h-px w-full" />
                </div>

                <!-- Fixed Bottom Input Area (Only when messages exist) -->
                <div
                    v-if="messages.length > 0 && activeTab === 'chat'"
                    class="shrink-0 border-t border-gray-200 bg-white p-4 dark:border-neutral-800 dark:bg-neutral-900"
                >
                    <div class="mx-auto max-w-3xl">
                        <div
                            class="relative flex flex-col gap-2 rounded-xl border border-gray-300 bg-white p-2 shadow-sm focus-within:border-brand focus-within:ring-1 focus-within:ring-brand dark:border-neutral-700 dark:bg-neutral-800 dark:focus-within:border-brand"
                        >
                            <textarea
                                ref="textareaRef"
                                v-model="inputMessage"
                                rows="1"
                                class="max-h-32 min-h-[40px] w-full resize-none border-0 bg-transparent px-3 py-2 text-sm focus:ring-0 focus:outline-none dark:text-gray-100"
                                placeholder="Message AI Assistant..."
                                :disabled="isLoading"
                                @keydown="handleKeydown"
                                @input="adjustTextareaHeight"
                            ></textarea>
                            <div
                                class="flex items-center justify-end gap-2 px-1 pb-1"
                            >
                                <Select v-model="selectedModel">
                                    <SelectTrigger
                                        class="h-8 w-auto justify-end px-2 text-right"
                                    >
                                        <SelectValue
                                            class="sr-only"
                                            placeholder="Model"
                                        />
                                        <span
                                            class="text-right text-xs font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            {{ selectedModelLabel }}
                                        </span>
                                    </SelectTrigger>
                                    <SelectContent class="w-[260px]">
                                        <SelectItem
                                            v-for="model in modelOptions"
                                            :key="model.id"
                                            :value="model.id"
                                            class="py-2"
                                        >
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                                    >{{ model.label }}</span
                                                >
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                    >{{
                                                        model.description
                                                    }}</span
                                                >
                                            </div>
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <Button
                                    size="icon"
                                    @click="
                                        isLoading
                                            ? stopGenerating()
                                            : sendMessage()
                                    "
                                    :disabled="
                                        !isLoading && !inputMessage.trim()
                                    "
                                    class="size-8 rounded-lg bg-brand text-white hover:bg-brand-dark dark:bg-brand dark:hover:bg-brand-dark"
                                >
                                    <Square v-if="isLoading" class="size-4" />
                                    <Send v-else class="size-4" />
                                </Button>
                            </div>
                        </div>
                        <div
                            v-if="lastErrorMessage"
                            class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-300"
                        >
                            <span class="flex-1">{{ lastErrorMessage }}</span>
                            <Button
                                size="sm"
                                variant="ghost"
                                class="h-7 px-2 text-xs"
                                @click="retryLastMessage"
                            >
                                Retry
                            </Button>
                        </div>
                        <!-- Quick action suggestion badges removed to simplify the input area -->
                        <div
                            class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500 dark:text-gray-400"
                        >
                            <span
                                >AI Assistant can make mistakes. Verify
                                important information.</span
                            >
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="clearChat"
                                class="h-auto p-0 text-xs text-gray-400 hover:bg-transparent hover:text-red-500"
                            >
                                <Trash2 class="mr-1 size-3" />
                                Clear chat
                            </Button>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="exportChatHistory"
                                class="h-auto p-0 text-xs text-gray-400 hover:bg-transparent hover:text-brand"
                            >
                                Export chat
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
/* Optional styling to make the injected HTML look better */
.chatbot-prose p {
    margin-bottom: 0.75em;
}
.chatbot-prose p:last-child {
    margin-bottom: 0;
}
.chatbot-prose ul {
    list-style-type: disc;
    padding-left: 1.5em;
    margin-bottom: 0.75em;
}
.chatbot-prose ol {
    list-style-type: decimal;
    padding-left: 1.5em;
    margin-bottom: 0.75em;
}
.chatbot-prose pre {
    background-color: #f3f4f6;
    padding: 0.75rem;
    border-radius: 0.375rem;
    overflow-x: auto;
    margin-bottom: 0.75em;
    font-size: 0.875em;
}
.dark .chatbot-prose pre {
    background-color: #262626;
}
.chatbot-prose code {
    background-color: #f3f4f6;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}
.dark .chatbot-prose code {
    background-color: #262626;
}
.chatbot-prose pre code {
    background-color: transparent;
    padding: 0;
}
.chatbot-prose em {
    font-style: italic;
}
.chatbot-prose strong {
    font-weight: 600;
}
</style>
