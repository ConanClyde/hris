# AI Chatbot Intelligence Delivery

## Architecture
- Context assembly combines role-scoped live data, cached aggregates, and retrieved policy snippets.
- Retrieval uses a document index persisted in `ai_chatbot_documents` with term vectors.
- Source linking uses `/ai-chatbot/policy/{filename}` for controlled access to policy text.
- Telemetry is stored in `ai_chatbot_metrics` for latency, cache hit, and retrieval confidence.

## Data Access And API
- Context endpoint: `GET /ai-chatbot/context` and `GET /api/ai/context`
- Data endpoints: `GET /api/ai/users`, `GET /api/ai/leave-applications`, `GET /api/ai/trainings`, `GET /api/ai/notices`
- Policy source: `GET /ai-chatbot/policy/{filename}`

## Retrieval And Indexing
- Reindex command: `php artisan ai:reindex-docs`
- Retrieval evaluation: `php artisan ai:evaluate`
- Golden set location: `storage/app/ai_eval/golden_set.json`

## Feature Flags
- `AI_CHATBOT_ENABLE_RETRIEVAL` toggles policy retrieval
- `AI_CHATBOT_ENABLE_DATA_API` toggles AI data endpoints
- `AI_CHATBOT_ENABLE_RESPONSE_CACHE` toggles cached responses
- `AI_CHATBOT_MAX_POLICY_CHARS` caps policy payload size
- `AI_CHATBOT_MAX_SOURCES` caps sources per response

## Benchmarks And Metrics
- Track `context_ms`, `llm_ms`, `total_ms`, `policy_sources_count`, and `max_confidence` in `ai_chatbot_metrics`.
- Evaluate retrieval precision with the golden set command.

## Training Pipeline Evaluation
- Fine-tuning requires GPU resources and a curated QA set of at least 5k examples.
- RAG is the default path using indexed policy docs plus live HR data.
- Prompt engineering is supported via prompt files in `storage/app/prompts`.

## Accuracy Metrics And Testing
- Retrieval precision is computed with `ai:evaluate`.
- API regression coverage is in `tests/Feature/AIChatbot`.
- Role-based access is enforced via session auth and role checks.

## Deployment And Rollback
- Run migrations for `ai_chatbot_documents` and `ai_chatbot_metrics`.
- Reindex policy docs after deployment.
- Toggle `AI_CHATBOT_ENABLE_RETRIEVAL=false` and `AI_CHATBOT_ENABLE_DATA_API=false` to roll back.
- Restore previous prompt templates or policy files if needed.
