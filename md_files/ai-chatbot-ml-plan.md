# AI Chatbot ML Plan (Ollama + RAG)

## Objectives
- Provide grounded answers using internal HRIS policies and records
- Improve answer consistency across roles (admin, HR, employee)
- Keep data access scoped and auditable

## Current State (From Codebase)
- Qwen via Ollama chat endpoint is already integrated
- Role-based system prompts exist
- Context service injects HRIS stats and policy snippets
- Response caching and metrics are available

## Architecture Plan
- **Ingestion pipeline**
  - Collect HR documents (policies, manuals, memos)
  - Chunk documents into small passages
  - Create embeddings for each chunk
  - Store embeddings + metadata
- **Retrieval**
  - Compute embedding for user query
  - Retrieve top-K relevant chunks
  - Feed snippets into system prompt
- **Chat**
- Use Ollama chat
  - Provide retrieved snippets + structured HRIS stats

## Data & Storage
- **Tables**
  - ai_chatbot_documents: original documents and metadata
  - ai_chatbot_chunks: chunk text, source, offsets
  - ai_chatbot_embeddings: vector storage and retrieval metadata
- **Metadata**
  - source type (policy, memo, handbook)
  - role visibility (admin/hr/employee/all)
  - version and effective date

## Implementation Phases
1. **Schema & Storage**
   - Create document, chunk, and embedding tables
   - Add role visibility and source metadata
2. **Ingestion**
   - CLI command to ingest files
   - Chunking logic with fixed size and overlap
   - Embedding generation via Ollama embeddings endpoint
3. **Retrieval**
   - Query embedding + similarity search
   - Top-K filtering with role visibility
4. **Prompt Integration**
   - Inject top snippets into system prompt
   - Add citations in response metadata
5. **Admin Tools**
   - Upload/replace policies
   - Rebuild embeddings
6. **Evaluation**
   - Add thumbs-up/down
   - Track answer quality metrics

## Risks & Mitigations
- **Hallucinations** → Always include retrieved snippets and citations
- **Data leakage** → Role-based visibility on chunks
- **Latency** → Cache query embeddings and responses
- **Staleness** → Scheduled re-ingestion on document updates

## Optional Enhancements
- Fine-tune with internal Q&A dataset
- Hybrid search (keyword + vector)
- Role-specific embedding indexes

## Next Actions
- Confirm documents to ingest and their source locations
- Pick vector storage (DB + cosine similarity or external vector DB)
- Implement ingestion command and retrieval service
