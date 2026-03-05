# Better AI Helpdesk Chatbot: Smarter, Reliable, Fast

This document outlines how to improve the HRIS AI Helpdesk chatbot so it is **smarter**, **more reliable**, and **faster**. It assumes a RAG + Laravel backend setup similar to the current AIChatbot feature.

---

## 1. Upgrade Knowledge & Retrieval

### Central, curated knowledge base

- Consolidate policies, FAQs, SOPs, and HR workflows into a single document store (docs, PDFs, markdown, DB tables).
- Remove duplicates and obsolete content; ambiguous or conflicting sources lead to ambiguous answers.

### Better chunking and metadata

- Chunk documents by **semantic sections** (headings, bullets, Q&A pairs), not fixed character lengths.
- Attach rich metadata to each chunk: `category` (e.g. leave, training, PDS), `effective_date`, `role` (employee | hr | admin).
- Use this metadata in retrieval filters so the bot only pulls context relevant to the user’s role and question type.

### Retrieval tuning

- Prefer **hybrid search** (vector + keyword) when available.
- Retrieve a larger top-k, then **re-rank** the top candidates (e.g. by similarity or a small cross-encoder).
- Always pass **only the relevant excerpts** to the LLM, not full documents, to reduce hallucination and token usage.

---

## 2. Make It Tools-First, Not Text-Only

### Live data tools (Laravel APIs)

- Let the bot call internal endpoints for: leave balance, leave credits, status of a leave application, upcoming trainings, user profile, recent notices.
- Answers become **factual and personalized** (“Your current VL balance is 5 days”) instead of generic policy text.

### Workflow tools

- Expose safe, idempotent actions: “file leave request”, “cancel leave”, “enroll in training”, “show my last 3 notices”.
- The bot decides whether to **call a tool** or **answer from retrieved docs**; the system prompt should define when to use which.

---

## 3. Tighten Prompting for Reliability

### Strong system prompt

- Define the domain (HRIS helpdesk), tone, and that the bot must **cite sources** (document title or link, or “from your HRIS data” when using a tool).
- Instruct: if the answer is not in the provided context or tool result, say “I don’t know” and suggest who to contact or where to look.

### Few-shot examples

- Add 5–10 high-quality Q&A examples (leave edge cases, overlapping policies, training rules).
- Include **good refusals** as examples (e.g. “I can’t see other employees’ data”).

### Response constraints

- Enforce a short answer + bullets + “Sources” section.
- For tool-augmented answers: “Think → decide tool → call → answer with data + citation.”

---

## 4. Latency: Design for “Fast by Default”

### Use a fast model tier for most queries

- Route simple FAQs and policy lookups to a **fast, cheap model** (e.g. Flash-Lite tier).
- Reserve heavier models only for complex, multi-step or highly nuanced questions.

### Streaming

- Enable **streaming** to the frontend so the user sees the answer as it’s generated; perceived latency drops even if total time is similar.

### Cache aggressively

- Cache **embeddings** for the knowledge base (already typical).
- Cache **retrieved chunks** for very common queries (e.g. “overtime policy”, “sick leave requirements”) with a short TTL.
- Optionally cache **final answers** for identical normalized queries.

### Optimize the retrieval path

- Keep the RAG pipeline lean (single service, minimal network hops).
- Reuse DB connections and HTTP clients; avoid N+1 in repositories.

---

## 5. Guardrails and Evaluation (Reliability)

### Hallucination control

- Instruct: “Answer only from the provided context or tool results. If the information isn’t there, say so and suggest next steps.”
- Require at least one **citation** per answer; if the model can’t cite, it should respond with “I couldn’t find that” rather than guessing.

### Red lines

- For sensitive topics (legal, payroll disputes, medical), the bot should **decline to give definitive advice** and point users to official channels (HR, compliance).

### Continuous evaluation loop

- Log: user question, retrieved docs, tools called, final answer, and **thumbs up/down** (or explicit feedback).
- Periodically review low-rated answers and repeated re-asks.
- Maintain a small **golden set** of HR questions and run them after prompt or model changes to catch regressions.

---

## 6. UX Tweaks That Make It Feel “Smart”

### Clarifying questions

- Allow the bot to ask one or two short clarifying questions instead of guessing (e.g. “Is this for sick leave or vacation leave?”).

### Show its work (lightly)

- A small “Sources” section with policy names and links builds trust.
- For tool-based answers: “Based on your current leave balance in the HRIS…”

### Multi-turn memory

- Keep minimal **conversation state** per session (e.g. last user, topic, selected year) so follow-ups like “what about next month?” work without re-stating context.

---

## 7. Implementation Checklist (Current Codebase)

- [ ] Review and expand `app/Features/AIChatbot/` (context service, retrieval, document indexer).
- [ ] Add or refine system prompt and few-shot examples in the chat controller/service.
- [ ] Expose Laravel APIs as tools (leave balance, applications, trainings, notices) and add tool-calling logic.
- [ ] Enable streaming in the chat endpoint and frontend.
- [ ] Add response caching for common queries and optional answer caching.
- [ ] Add logging (query, context, tools, answer) and feedback (thumbs up/down).
- [ ] Define a golden Q&A set and run it in CI or a manual eval script after changes.

---

## References

- Existing AI Chatbot: `app/Features/AIChatbot/`, `config/ai_chatbot.php`
- Realtime/notifications: `md_files/reverb-and-email-function.md`, `app/Notifications/SystemNotification.php`
