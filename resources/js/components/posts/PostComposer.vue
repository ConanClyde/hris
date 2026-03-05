<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

const props = defineProps<{
    actorInitials: string;
    title: string;
    body: string;
    roleScope: string;
    processing?: boolean;
    roleScopeOptions: { value: string; label: string }[];
    image?: File | null;
    expiresAt?: string | null;
}>();

const emit = defineEmits<{
    (e: 'update:title', value: string): void;
    (e: 'update:body', value: string): void;
    (e: 'update:roleScope', value: string): void;
    (e: 'update:image', value: File | null): void;
    (e: 'update:expiresAt', value: string | null): void;
    (e: 'submit'): void;
}>();

const previewUrl = ref<string | null>(null);

watch(
    () => props.image,
    (file) => {
        if (previewUrl.value) {
            URL.revokeObjectURL(previewUrl.value);
            previewUrl.value = null;
        }
        if (file instanceof File) {
            previewUrl.value = URL.createObjectURL(file);
        }
    },
    { immediate: true }
);

function onFileChange(e: Event) {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0] ?? null;
    emit('update:image', file);
    input.value = '';
}

function removeImage() {
    emit('update:image', null);
}

function toDateString(d: Date) {
    const pad = (n: number) => String(n).padStart(2, '0');
    const yyyy = d.getFullYear();
    const mm = pad(d.getMonth() + 1);
    const dd = pad(d.getDate());
    return `${yyyy}-${mm}-${dd}`;
}

const expiresAtValue = computed(() => {
    if (props.expiresAt === undefined) {
        return toDateString(new Date(Date.now() + 24 * 60 * 60 * 1000));
    }
    return props.expiresAt ?? '';
});
</script>

<template>
    <form
        class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900/60"
        @submit.prevent="emit('submit')"
    >
        <div class="flex items-start gap-3">
            <Avatar class="mt-0.5 h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                <AvatarFallback class="rounded-xl bg-foreground text-background font-bold">
                    {{ actorInitials }}
                </AvatarFallback>
            </Avatar>

            <div class="flex-1 space-y-3">
                <Input
                    :model-value="title"
                    name="title"
                    placeholder="Title"
                    maxlength="200"
                    required
                    class="h-10 rounded-xl"
                    @update:model-value="(v) => emit('update:title', String(v))"
                />

                <textarea
                    :value="body"
                    name="body"
                    rows="4"
                    required
                    placeholder="What's new? Share an announcement..."
                    class="flex min-h-[120px] w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand dark:border-neutral-700 dark:bg-neutral-900 dark:placeholder:text-gray-500"
                    @input="emit('update:body', ($event.target as HTMLTextAreaElement).value)"
                />

                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <label
                            class="inline-flex h-10 cursor-pointer items-center justify-center rounded-full border border-gray-300 bg-white px-4 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-gray-200 dark:hover:bg-neutral-800"
                        >
                            <input
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @change="onFileChange"
                            />
                            Add photo
                        </label>

                        <Button
                            v-if="props.image"
                            type="button"
                            size="sm"
                            variant="outline"
                            class="h-10 rounded-full px-4"
                            @click="removeImage"
                        >
                            Remove photo
                        </Button>
                    </div>

                    <div v-if="previewUrl" class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-950">
                        <img :src="previewUrl" alt="" class="block max-h-[360px] w-full object-cover" />
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Expires at</label>
                    <input
                        type="date"
                        class="h-10 w-full rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-900 shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand dark:border-neutral-700 dark:bg-neutral-900 dark:text-gray-100"
                        :value="expiresAtValue"
                        @input="emit('update:expiresAt', ($event.target as HTMLInputElement).value || null)"
                    />
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Default: tomorrow. Clear to disable expiration.</p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <select
                            :value="roleScope"
                            name="role_scope"
                            class="h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs text-gray-900 shadow-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand dark:border-neutral-700 dark:bg-neutral-900 dark:text-gray-100"
                            @change="emit('update:roleScope', ($event.target as HTMLSelectElement).value)"
                        >
                            <option v-for="opt in roleScopeOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                        </select>
                    </div>

                    <Button type="submit" class="h-10 rounded-full px-5" :disabled="processing">
                        {{ processing ? 'Posting...' : 'Post' }}
                    </Button>
                </div>
            </div>
        </div>
    </form>
</template>
