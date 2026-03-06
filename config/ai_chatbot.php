<?php

return [
    'enable_analysis_pipeline' => env('AI_CHATBOT_ENABLE_ANALYSIS_PIPELINE', true),
    'enable_retrieval' => env('AI_CHATBOT_ENABLE_RETRIEVAL', true),
    'enable_data_api' => env('AI_CHATBOT_ENABLE_DATA_API', true),
    'enable_response_cache' => env('AI_CHATBOT_ENABLE_RESPONSE_CACHE', true),
    'enable_embeddings' => env('AI_CHATBOT_ENABLE_EMBEDDINGS', true),
    'slow_total_ms_warn' => env('AI_CHATBOT_SLOW_TOTAL_MS_WARN', 8000),
    'slow_ttfb_ms_warn' => env('AI_CHATBOT_SLOW_TTFB_MS_WARN', 1500),
    'max_policy_chars' => env('AI_CHATBOT_MAX_POLICY_CHARS', 20000),
    'max_sources' => env('AI_CHATBOT_MAX_SOURCES', 3),
    'min_confidence' => env('AI_CHATBOT_MIN_CONFIDENCE', 0.35),
    'embedding_cache_seconds' => env('AI_CHATBOT_EMBEDDING_CACHE_SECONDS', 600),
    'embedding_weight' => env('AI_CHATBOT_EMBEDDING_WEIGHT', 0.7),
    'keyword_weight' => env('AI_CHATBOT_KEYWORD_WEIGHT', 0.3),
    'policy_stale_days' => env('AI_CHATBOT_POLICY_STALE_DAYS', 180),
    'policy_required_files' => [
        'labor_code_leaves.txt',
        'csc_leave_policies.txt',
        'csc_leave_rules.txt',
        'dtr_policies.txt',
        'pds_policies.txt',
        'code_of_conduct.txt',
        'ssl_vi_policies.txt',
        'gsis_policies.txt',
        'spms_policies.txt',
        'paternity_leave_policies.txt',
        'solo_parent_leave_policies.txt',
        'special_leave_women_policies.txt',
        'year_end_bonus_policies.txt',
        'mid_year_bonus_policies.txt',
        'pbb_policies.txt',
        'policy_access.txt',
    ],
    // Retrieval chunking defaults (tuned for latency vs. coverage)
    'chunk_size' => env('AI_CHATBOT_CHUNK_SIZE', 800),
    'chunk_overlap' => env('AI_CHATBOT_CHUNK_OVERLAP', 150),
    'max_chunks' => env('AI_CHATBOT_MAX_CHUNKS', 400),
    'ingest_paths' => [
        storage_path('app/prompts'),
    ],
    'enhancement' => [
        'monitoring' => [
            'default_window_hours' => env('AI_CHATBOT_ENHANCEMENT_WINDOW_HOURS', 168),
            'report_path' => env('AI_CHATBOT_ENHANCEMENT_REPORT_PATH', 'app/ai_eval/enhancement_audit_latest.json'),
            'snapshot_retention_days' => env('AI_CHATBOT_ENHANCEMENT_RETENTION_DAYS', 30),
        ],
        'targets' => [
            'intelligence' => [
                'analysis_pipeline_uptime' => 0.99,
                'domain_context_coverage' => 0.85,
                'continuous_tuning_readiness' => 0.9,
            ],
            'accuracy' => [
                'avg_confidence' => 0.78,
                'reliability_rate' => 0.98,
                'consensus_stability' => 0.85,
                'real_time_correction_rate' => 0.8,
            ],
            'efficiency' => [
                'p95_total_ms' => 3500,
                'cache_hit_rate' => 0.35,
                'throughput_queries_per_hour' => 30,
            ],
            'hallucination_mitigation' => [
                'low_confidence_rate' => 0.15,
                'negative_feedback_rate' => 0.12,
                'grounded_response_rate' => 0.9,
            ],
            'source_validation' => [
                'citation_coverage_rate' => 0.9,
                'feedback_source_coverage_rate' => 0.85,
                'document_freshness_rate' => 0.95,
            ],
            'additional_improvements' => [
                'adversarial_resilience_rate' => 0.97,
                'explainability_rate' => 0.9,
                'ethics_compliance_rate' => 0.95,
            ],
        ],
        'industry_benchmarks' => [
            'intelligence' => [
                'analysis_pipeline_uptime' => 0.995,
                'domain_context_coverage' => 0.88,
                'continuous_tuning_readiness' => 0.92,
            ],
            'accuracy' => [
                'avg_confidence' => 0.82,
                'reliability_rate' => 0.985,
                'consensus_stability' => 0.9,
                'real_time_correction_rate' => 0.85,
            ],
            'efficiency' => [
                'p95_total_ms' => 2500,
                'cache_hit_rate' => 0.4,
                'throughput_queries_per_hour' => 50,
            ],
            'hallucination_mitigation' => [
                'low_confidence_rate' => 0.1,
                'negative_feedback_rate' => 0.08,
                'grounded_response_rate' => 0.95,
            ],
            'source_validation' => [
                'citation_coverage_rate' => 0.95,
                'feedback_source_coverage_rate' => 0.9,
                'document_freshness_rate' => 0.98,
            ],
            'additional_improvements' => [
                'adversarial_resilience_rate' => 0.985,
                'explainability_rate' => 0.95,
                'ethics_compliance_rate' => 0.98,
            ],
        ],
        'roadmap' => [
            'intelligence' => [
                ['phase' => 'phase_1', 'title' => 'Architecture optimization baseline', 'timeline' => '0-30 days'],
                ['phase' => 'phase_2', 'title' => 'Multi-task routing and domain adapters', 'timeline' => '31-60 days'],
                ['phase' => 'phase_3', 'title' => 'Continuous fine-tuning release cadence', 'timeline' => '61-90 days'],
            ],
            'accuracy' => [
                ['phase' => 'phase_1', 'title' => 'Validation and scoring pipeline', 'timeline' => '0-30 days'],
                ['phase' => 'phase_2', 'title' => 'Multi-model consensus rollouts', 'timeline' => '31-60 days'],
                ['phase' => 'phase_3', 'title' => 'Real-time correction loops', 'timeline' => '61-90 days'],
            ],
            'efficiency' => [
                ['phase' => 'phase_1', 'title' => 'Latency and resource profiling', 'timeline' => '0-30 days'],
                ['phase' => 'phase_2', 'title' => 'Compression and quantization deployment', 'timeline' => '31-60 days'],
                ['phase' => 'phase_3', 'title' => 'Edge routing and dynamic scaling', 'timeline' => '61-90 days'],
            ],
            'hallucination_mitigation' => [
                ['phase' => 'phase_1', 'title' => 'Fact-checking and uncertainty controls', 'timeline' => '0-30 days'],
                ['phase' => 'phase_2', 'title' => 'Grounded generation enforcement', 'timeline' => '31-60 days'],
                ['phase' => 'phase_3', 'title' => 'Automated hallucination regression gates', 'timeline' => '61-90 days'],
            ],
            'source_validation' => [
                ['phase' => 'phase_1', 'title' => 'Citation tracking and source linkage', 'timeline' => '0-30 days'],
                ['phase' => 'phase_2', 'title' => 'Reference verification and weighting', 'timeline' => '31-60 days'],
                ['phase' => 'phase_3', 'title' => 'Transparent attribution dashboards', 'timeline' => '61-90 days'],
            ],
            'additional_improvements' => [
                ['phase' => 'phase_1', 'title' => 'Bias and ethics policy automation', 'timeline' => '0-30 days'],
                ['phase' => 'phase_2', 'title' => 'Explainability and traceability outputs', 'timeline' => '31-60 days'],
                ['phase' => 'phase_3', 'title' => 'Adversarial red-team test cycle', 'timeline' => '61-90 days'],
            ],
        ],
        'testing_protocols' => [
            'intelligence' => [
                'Run weekly domain coverage checks against golden prompts by role.',
                'Run monthly offline fine-tuning evaluation with holdout datasets.',
            ],
            'accuracy' => [
                'Run pre-deploy regression tests for confidence and error-rate thresholds.',
                'Run consensus disagreement audits on sampled production prompts.',
            ],
            'efficiency' => [
                'Run load tests at p50 and peak throughput scenarios each release.',
                'Run latency benchmark suite after model or retrieval changes.',
            ],
            'hallucination_mitigation' => [
                'Run groundedness checks that fail when source coverage drops below target.',
                'Run uncertainty trigger tests with ambiguous and adversarial prompts.',
            ],
            'source_validation' => [
                'Run citation integrity checks ensuring URLs and source IDs resolve.',
                'Run source freshness checks against policy staleness thresholds.',
            ],
            'additional_improvements' => [
                'Run fairness scorecard evaluation across protected groups.',
                'Run adversarial test pack for prompt injection and jailbreak vectors.',
            ],
        ],
    ],
];
