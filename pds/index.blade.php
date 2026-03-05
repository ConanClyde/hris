<x-app-layout title="Personal Data Sheet (PDS)">
    <div class="space-y-6">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif

        {{-- Header (same as HR/Admin) --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Personal Data Sheet</h2>
                <p class="text-sm text-[#706f6c]">CS Form No. 212 (Revised 2017)</p>
            </div>
            <div class="flex gap-3">
                <button type="button" data-open-pds-preview onclick="openPdsPreviewModal()"
                    class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors inline-flex items-center gap-2 cursor-pointer shadow-sm">
                    <x-icons.eye class="w-4 h-4" />
                    Preview PDS
                </button>
            </div>
        </div>

        {{-- Tabs Navigation (sticky) --}}
        <div class="bg-white rounded-lg shadow-sm border border-[#e3e3e0] overflow-hidden">
            <div class="sticky top-0 z-10 bg-white border-b border-[#e3e3e0] overflow-x-auto shadow-[0_1px_0_0_#e3e3e0]">
                <nav class="flex min-w-max">
                    <button type="button" data-pds-tab="c1" onclick="switchTab('c1')" id="tab-c1"
                        class="tab-btn px-4 py-3.5 text-sm font-medium border-b-2 border-primary text-primary bg-primary/5 whitespace-nowrap cursor-pointer transition-colors">
                        <span class="font-semibold">C1</span>
                        <span class="hidden sm:inline ml-1 text-[#706f6c] font-normal">Personal &amp; Family</span>
                    </button>
                    <button type="button" data-pds-tab="c2" onclick="switchTab('c2')" id="tab-c2"
                        class="tab-btn px-4 py-3.5 text-sm font-medium border-b-2 border-transparent text-[#706f6c] hover:text-ink hover:border-[#e3e3e0] hover:bg-gray-50/80 whitespace-nowrap cursor-pointer transition-colors">
                        <span class="font-semibold">C2</span>
                        <span class="hidden sm:inline ml-1 font-normal">Eligibility &amp; Work</span>
                    </button>
                    <button type="button" data-pds-tab="c3" onclick="switchTab('c3')" id="tab-c3"
                        class="tab-btn px-4 py-3.5 text-sm font-medium border-b-2 border-transparent text-[#706f6c] hover:text-ink hover:border-[#e3e3e0] hover:bg-gray-50/80 whitespace-nowrap cursor-pointer transition-colors">
                        <span class="font-semibold">C3</span>
                        <span class="hidden sm:inline ml-1 font-normal">Voluntary &amp; Training</span>
                    </button>
                    <button type="button" data-pds-tab="c4" onclick="switchTab('c4')" id="tab-c4"
                        class="tab-btn px-4 py-3.5 text-sm font-medium border-b-2 border-transparent text-[#706f6c] hover:text-ink hover:border-[#e3e3e0] hover:bg-gray-50/80 whitespace-nowrap cursor-pointer transition-colors">
                        <span class="font-semibold">C4</span>
                        <span class="hidden sm:inline ml-1 font-normal">References &amp; ID</span>
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div id="pds-tab-content" class="p-6">
                {{-- Validation Errors --}}
                @if ($errors->any())
                    <x-alert type="error">
                        <p class="font-semibold">There were problems with your input.</p>
                        <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                {{-- C1: Personal Information, Family Background, Education --}}
                @include('employee.pds.partials.tabs.c1')

                {{-- C2: Civil Service Eligibility, Work Experience --}}
                @include('employee.pds.partials.tabs.c2')

                {{-- C3: Voluntary Work, Training, Other Information --}}
                @include('employee.pds.partials.tabs.c3')

                {{-- C4: References, Background Questions, Government ID --}}
                @include('employee.pds.partials.tabs.c4')
            </div>
        </div>
    </div>

    <div id="pds-preview-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity cursor-pointer" onclick="closePdsPreviewModal()">
            </div>

            <div
                class="relative bg-white rounded-lg shadow-xl w-full max-w-[95vw] h-[90vh] flex flex-col z-50 overflow-hidden border border-[#e3e3e0]">
                <div class="px-6 py-4 border-b border-[#e3e3e0] flex justify-between items-center bg-gray-50/50">
                    <div class="flex flex-col">
                        <h3 class="text-xl font-semibold text-ink" id="modal-title">
                            Personal Data Sheet Preview
                        </h3>
                        <p class="text-xs text-muted mt-0.5">CS Form No. 212 (Revised 2017)</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 bg-white border border-[#e3e3e0] rounded-sm p-1">
                            <button type="button" data-pds-zoom="out"
                                class="px-2 py-1 text-xs font-bold text-muted hover:text-ink hover:bg-gray-50 transition-colors rounded">
                                -
                            </button>
                            <span id="pds-zoom-label"
                                class="text-xs font-medium text-muted min-w-[40px] text-center">100%</span>
                            <button type="button" data-pds-zoom="in"
                                class="px-2 py-1 text-xs font-bold text-muted hover:text-ink hover:bg-gray-50 transition-colors rounded">
                                +
                            </button>
                            <div class="w-px h-4 bg-border mx-1"></div>
                            <button type="button" data-pds-zoom="reset"
                                class="px-2 py-1 text-xs font-medium text-muted hover:text-ink hover:bg-gray-50 transition-colors rounded">
                                Reset
                            </button>
                        </div>
                        <button type="button" onclick="closePdsPreviewModal()"
                            class="text-muted hover:text-ink transition-colors p-2 hover:bg-gray-100 rounded-lg min-w-11 min-h-11 flex items-center justify-center tap-target">
                            <x-icons.x class="w-6 h-6" />
                        </button>
                    </div>
                </div>
                <div class="flex-1 bg-gray-100/30 overflow-auto">
                    <iframe id="pds-preview-frame" class="w-full h-full border-0 origin-top-left"></iframe>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.PDS_PREVIEW_URL = "{{ route('employee.pds.preview') }}";
        window.PDS_ZOOM_DEFAULT = 1.15;
        (function () {
            function openModal() {
                var m = document.getElementById('pds-preview-modal');
                var f = document.getElementById('pds-preview-frame');
                if (!m || !f) return;
                f.src = window.PDS_PREVIEW_URL || '';
                m.classList.remove('hidden');
            }
            function closeModal() {
                var f = document.getElementById('pds-preview-frame');
                var m = document.getElementById('pds-preview-modal');
                if (f) f.src = '';
                if (m) m.classList.add('hidden');
            }
            window.openPdsPreviewModal = openModal;
            window.closePdsPreviewModal = closeModal;
        })();
    </script>
</x-app-layout>
