@extends('layouts.pds')

@section('navbarTitle', 'Personal Data Sheet (PDS)')

@section('content')
@php
    $pds = $pds ?? null;
    $family = $family ?? null;
    $children = $children ?? collect();
    $education = $education ?? collect();
    $csc_eligibility = $csc_eligibility ?? collect();
    $work_experience = $work_experience ?? collect();
    $voluntary_work = $voluntary_work ?? collect();
    $training_records = $training_records ?? collect();
    $other_info = $other_info ?? collect();
    $reference_records = $reference_records ?? collect();
    $check = $check ?? null;
    $govid_records = $govid_records ?? null;
@endphp
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Personal Data Sheet</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">CS Form No. 212 (Revised 2025)</p>
        </div>
        <button type="button" onclick="openPdsPreview()"
            class="inline-flex items-center gap-2 px-4 py-2 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><circle cx="12" cy="12" r="3"/></svg>
            Preview PDS
        </button>
    </div>

    {{-- Success/Error messages --}}
    @if(session('success'))
        <div class="rounded-md border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-900/20 px-4 py-3 text-sm text-green-800 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rounded-md border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-300">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="-mx-4 px-4">
        <div class="inline-flex rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" role="tablist" aria-label="PDS Tabs">
            <button type="button" data-tab="c1" onclick="switchTab('c1')"
                class="tab-btn px-5 py-2.5 text-sm font-medium whitespace-nowrap transition-colors border-r border-gray-200 dark:border-neutral-800">
                C1: Personal & Family
            </button>
            <button type="button" data-tab="c2" onclick="switchTab('c2')"
                class="tab-btn px-5 py-2.5 text-sm font-medium whitespace-nowrap transition-colors border-r border-gray-200 dark:border-neutral-800">
                C2: Eligibility & Work
            </button>
            <button type="button" data-tab="c3" onclick="switchTab('c3')"
                class="tab-btn px-5 py-2.5 text-sm font-medium whitespace-nowrap transition-colors border-r border-gray-200 dark:border-neutral-800">
                C3: Voluntary & Training
            </button>
            <button type="button" data-tab="c4" onclick="switchTab('c4')"
                class="tab-btn px-5 py-2.5 text-sm font-medium whitespace-nowrap transition-colors">
                C4: References & ID
            </button>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="space-y-6">
        @include('employee.pds.partials.c1')
        @include('employee.pds.partials.c2')
        @include('employee.pds.partials.c3')
        @include('employee.pds.partials.c4')
    </div>
</div>

{{-- PDS Preview Modal --}}
<div id="pds-preview-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/30 dark:bg-black/60" onclick="closePdsPreview()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="relative w-full max-w-4xl max-h-[90vh] flex flex-col rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40 pointer-events-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center shrink-0">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">PDS Preview</h3>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="zoomOut()" class="p-1.5 rounded-md border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-neutral-800" title="Zoom out">−</button>
                        <span id="zoom-label" class="text-xs text-gray-500 dark:text-gray-400 w-12 text-center">100%</span>
                        <button type="button" onclick="zoomIn()" class="p-1.5 rounded-md border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-neutral-800" title="Zoom in">+</button>
                        <button type="button" onclick="zoomReset()" class="px-2 py-1 text-xs rounded-md border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-neutral-800">Reset</button>
                    </div>
                    <button type="button" onclick="closePdsPreview()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="flex-1 min-h-0 overflow-auto p-6">
                <div id="preview-zoom-wrapper" class="origin-top-left" style="transform: scale(1);">
                    <iframe id="pds-preview-iframe" src="" class="w-full min-h-[600px] border border-gray-200 dark:border-neutral-700 rounded-md bg-white" title="PDS Preview"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-content {
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out, padding 0.3s ease-out;
        max-height: 10000px;
        opacity: 1;
        overflow: hidden;
    }
    .card-content.collapsed {
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
    }
    button[onclick^="toggleCard"] svg {
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Define global variables and functions on window to ensure availability
    window.currentZoom = 1;

    window.switchTab = function(group) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-gray-100', 'text-gray-900', 'dark:bg-neutral-800', 'dark:text-gray-100');
            btn.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50', 'hover:text-gray-900', 'dark:bg-neutral-900', 'dark:text-gray-300', 'dark:hover:bg-neutral-800', 'dark:hover:text-gray-100');
            if (btn.dataset.tab === group) {
                btn.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50', 'hover:text-gray-900', 'dark:bg-neutral-900', 'dark:text-gray-300', 'dark:hover:bg-neutral-800', 'dark:hover:text-gray-100');
                btn.classList.add('bg-gray-100', 'text-gray-900', 'dark:bg-neutral-800', 'dark:text-gray-100');
            }
        });
        const panel = document.getElementById('tab-' + group);
        if (panel) panel.classList.remove('hidden');
    }

    window.openPdsPreview = function() {
        const previewUrl = {!! json_encode(route('employee.pds.preview')) !!};
        const iframe = document.getElementById('pds-preview-iframe');
        if (iframe) iframe.src = previewUrl;

        const modal = document.getElementById('pds-preview-modal');
        if (modal) modal.classList.remove('hidden');

        document.body.style.overflow = 'hidden';
        window.currentZoom = 1;
        const zoomLabel = document.getElementById('zoom-label');
        if (zoomLabel) zoomLabel.textContent = '100%';
    }

    window.closePdsPreview = function() {
        const iframe = document.getElementById('pds-preview-iframe');
        if (iframe) iframe.src = '';

        const modal = document.getElementById('pds-preview-modal');
        if (modal) modal.classList.add('hidden');

        document.body.style.overflow = '';
    }

    window.zoomIn = function() {
        window.currentZoom = Math.min(2, window.currentZoom + 0.1);
        window.applyZoom();
    }

    window.zoomOut = function() {
        window.currentZoom = Math.max(0.5, window.currentZoom - 0.1);
        window.applyZoom();
    }

    window.zoomReset = function() {
        window.currentZoom = 1;
        window.applyZoom();
    }

    window.applyZoom = function() {
        const wrapper = document.getElementById('preview-zoom-wrapper');
        if (wrapper) wrapper.style.transform = 'scale(' + window.currentZoom + ')';

        const zoomLabel = document.getElementById('zoom-label');
        if (zoomLabel) zoomLabel.textContent = Math.round(window.currentZoom * 100) + '%';
    }

    window.addRow = function(section) {
        const tpl = document.getElementById('tpl-' + section);
        const tbody = document.querySelector('[data-rows="' + section + '"]');
        if (!tpl || !tbody) return;
        if (section === 'references' && tbody.querySelectorAll('tr').length >= 3) return;

        const idx = tbody.querySelectorAll('tr').length;
        const html = tpl.innerHTML.replace(/INDEX/g, idx);
        tbody.insertAdjacentHTML('beforeend', html);

        if (section === 'references') {
            const btn = document.getElementById('add-reference-btn');
            if (btn && tbody.querySelectorAll('tr').length >= 3) btn.disabled = true;
        }
    }

    window.removeRow = function(btn) {
        const tr = btn.closest('tr');
        const tbody = tr ? tr.closest('[data-rows]') : null;
        if (tr) tr.remove();

        if (tbody && tbody.dataset.rows === 'references') {
            const addBtn = document.getElementById('add-reference-btn');
            if (addBtn && tbody.querySelectorAll('tr').length < 3) addBtn.disabled = false;
        }
    }

    window.copyResidentialToPermanent = function() {
        const fields = ['house_block_lot', 'street', 'subdivision', 'barangay', 'city_municipality', 'province', 'zip'];
        fields.forEach(f => {
            const src = document.querySelector('[name="residential_' + f + '"]');
            const dst = document.querySelector('[name="permanent_' + f + '"]');
            if (src && dst) dst.value = src.value;
        });
        const banner = document.getElementById('permanent-same-banner');
        if (banner) banner.classList.remove('hidden');

        document.querySelectorAll('[data-permanent-field]').forEach(el => {
            el.disabled = true;
            el.classList.add('bg-gray-50', 'dark:bg-neutral-800/50', 'cursor-not-allowed');
        });
    }

    window.clearPermanentAddress = function() {
        const permanent = ['house_block_lot', 'street', 'subdivision', 'barangay', 'city_municipality', 'province', 'zip'];
        permanent.forEach(f => {
            const dst = document.querySelector('[name="permanent_' + f + '"]');
            if (dst) dst.value = '';
        });
        const banner = document.getElementById('permanent-same-banner');
        if (banner) banner.classList.add('hidden');

        const checkbox = document.getElementById('same-as-residential');
        if (checkbox) checkbox.checked = false;

        document.querySelectorAll('[data-permanent-field]').forEach(el => {
            el.disabled = false;
            el.classList.remove('bg-gray-50', 'dark:bg-neutral-800/50', 'cursor-not-allowed');
        });
    }

    window.togglePermanentAddress = function(checkbox) {
        if (checkbox.checked) {
            window.copyResidentialToPermanent();
        } else {
            window.clearPermanentAddress();
        }
    }

    window.toggleCard = function(cardId) {
        console.log('toggleCard called with:', cardId);
        const card = document.querySelector('[data-card="' + cardId + '"]');
        console.log('Found card:', card);
        if (!card) return;

        const content = card.querySelector('.card-content');
        const downChevron = card.querySelector('.chevron-down');
        const upChevron = card.querySelector('.chevron-up');

        if (content) {
            content.classList.toggle('collapsed');
        }
        if (downChevron && upChevron) {
            downChevron.classList.toggle('hidden');
            upChevron.classList.toggle('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        window.switchTab('c1');

        function syncBackgroundDetails() {
            document.querySelectorAll('textarea[data-bg-details]').forEach(function (textarea) {
                const q = textarea.getAttribute('data-bg-details');
                // Handle sub-questions like q34, q35a, q35b, q38a, q38b
                let yesRadio;
                if (q === '34') {
                    // For q34, enable if either q34a OR q34b is yes
                    const yes34a = document.querySelector('input[type="radio"][name="q34a"][value="yes"]');
                    const yes34b = document.querySelector('input[type="radio"][name="q34b"][value="yes"]');
                    const isYes = (yes34a && yes34a.checked) || (yes34b && yes34b.checked);
                    textarea.disabled = !isYes;
                    textarea.required = !!isYes;
                    if (!isYes) textarea.value = '';
                    textarea.classList.toggle('bg-gray-50', !isYes);
                    textarea.classList.toggle('dark:bg-neutral-800/50', !isYes);
                    textarea.classList.toggle('cursor-not-allowed', !isYes);
                    return;
                } else {
                    yesRadio = document.querySelector('input[type="radio"][name="q' + q + '"][value="yes"]');
                }
                const isYes = yesRadio && yesRadio.checked;

                textarea.disabled = !isYes;
                textarea.required = !!isYes;

                if (!isYes) {
                    textarea.value = '';
                }

                textarea.classList.toggle('bg-gray-50', !isYes);
                textarea.classList.toggle('dark:bg-neutral-800/50', !isYes);
                textarea.classList.toggle('cursor-not-allowed', !isYes);
            });

            // Handle text inputs for question 40 (IP, PWD, Solo Parent specify fields)
            document.querySelectorAll('input[data-bg-specify]').forEach(function (input) {
                const q = input.getAttribute('data-bg-specify');
                const yesRadio = document.querySelector('input[type="radio"][name="q' + q + '"][value="yes"]');
                const isYes = yesRadio && yesRadio.checked;

                input.disabled = !isYes;
                input.required = !!isYes;

                if (!isYes) {
                    input.value = '';
                }

                input.classList.toggle('bg-gray-50', !isYes);
                input.classList.toggle('dark:bg-neutral-800/50', !isYes);
                input.classList.toggle('cursor-not-allowed', !isYes);
            });
        }

        document.querySelectorAll('input[type="radio"][name^="q"]').forEach(function (radio) {
            radio.addEventListener('change', syncBackgroundDetails);
        });

        syncBackgroundDetails();

        function syncDualCitizenshipCountry() {
            const type = document.querySelector('select[name="citizenship_type"]');
            const search = document.getElementById('citizenship_country_search');
            const hidden = document.getElementById('citizenship_country_hidden');
            if (!type || !search || !hidden) return;

            const isDual = type.value === 'Dual';
            search.disabled = !isDual;

            const wrap = search.closest('[data-dual-country-wrap]');
            if (wrap) {
                wrap.classList.toggle('hidden', !isDual);
            }

            if (!isDual) {
                search.value = '';
                hidden.value = '';
            }

            search.classList.toggle('bg-gray-50', !isDual);
            search.classList.toggle('dark:bg-neutral-800/50', !isDual);
            search.classList.toggle('cursor-not-allowed', !isDual);
        }

        document.querySelector('select[name="citizenship_type"]')?.addEventListener('change', syncDualCitizenshipCountry);
        syncDualCitizenshipCountry();

        // Country combobox for dual citizenship
        const countries = [
            'Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahamas','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Central African Republic','Chad','Chile','China','Colombia','Comoros','Congo (DRC)','Congo (Republic)','Costa Rica','Croatia','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominica','Dominican Republic','East Timor','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Eswatini','Ethiopia','Fiji','Finland','France','Gabon','Gambia','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Korea (North)','Korea (South)','Kosovo','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Macedonia','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen','Zambia','Zimbabwe'
        ].map(name => ({ id: name, name }));

        function initCountryCombobox(options) {
            const items = Array.isArray(options.items) ? options.items : [];
            const input = document.getElementById(options.inputId);
            const hidden = document.getElementById(options.hiddenId);
            const list = document.getElementById(options.listId);
            if (!input || !hidden || !list) return;

            function normalize(v) {
                return String(v || '').toLowerCase().trim();
            }

            function render(query) {
                const q = normalize(query);
                list.innerHTML = '';

                const results = q
                    ? items.filter(e => normalize(e.name).includes(q))
                    : items;

                if (results.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'px-3 py-1.5 text-xs text-gray-500 dark:text-gray-400';
                    empty.textContent = 'No matches';
                    list.appendChild(empty);
                    return;
                }

                results.slice(0, 200).forEach(function (e) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'w-full text-left px-3 py-1.5 text-xs text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-neutral-800';
                    btn.textContent = e.name;
                    btn.addEventListener('click', function () {
                        hidden.value = e.id;
                        input.value = e.id;
                        list.classList.add('hidden');
                    });
                    list.appendChild(btn);
                });
            }

            function openList() {
                render(input.value);

                // Position fixed dropdown under input
                const rect = input.getBoundingClientRect();
                list.style.top = rect.bottom + window.scrollY + 'px';
                list.style.left = rect.left + window.scrollX + 'px';
                list.style.width = rect.width + 'px';

                list.classList.remove('hidden');
            }

            input.addEventListener('focus', openList);
            input.addEventListener('input', function () {
                // Don't clear hidden value on typing - allow custom input
                openList();
            });
            input.addEventListener('blur', function () {
                setTimeout(function () {
                    list.classList.add('hidden');
                    // Don't clear input if user typed custom value
                    if (!hidden.value && input.value.trim()) {
                        hidden.value = input.value.trim();
                    }
                }, 200);
            });
        }

        initCountryCombobox({
            items: countries,
            inputId: 'citizenship_country_search',
            hiddenId: 'citizenship_country_hidden',
            listId: 'citizenship_country_list',
        });
    });
</script>
@endpush
@endsection
