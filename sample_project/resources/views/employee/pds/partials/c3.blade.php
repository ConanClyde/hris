{{-- C3: Voluntary Work, Training, Other --}}
<div id="tab-c3" class="tab-panel hidden">
    {{-- Section VI: Voluntary Work --}}
    <form method="POST" action="{{ route('employee.pds.store') }}" class="mb-6">
        @csrf
        <input type="hidden" name="section" value="voluntary">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c3-voluntary">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">VI. Voluntary Work</h3>
                    <button type="button" onclick="toggleCard('c3-voluntary')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md border border-gray-200 dark:border-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800"><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Organization Name/Address</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">From</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">To</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hours</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nature of Work</th><th class="px-4 py-2 w-10"></th></tr></thead>
                        <tbody data-rows="voluntary">
                            @forelse($voluntary_work as $i => $v)
                            <tr><td class="px-4 py-2"><input type="text" name="voluntary[{{ $i }}][org_name_address]" value="{{ $v->org_name_address ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="date" name="voluntary[{{ $i }}][volunteer_from]" value="{{ $v->volunteer_from ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="date" name="voluntary[{{ $i }}][volunteer_to]" value="{{ $v->volunteer_to ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="number" name="voluntary[{{ $i }}][number_of_hours]" value="{{ $v->number_of_hours ?? '' }}" min="0" class="h-9 w-20 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="voluntary[{{ $i }}][nature_of_work]" value="{{ $v->nature_of_work ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <template id="tpl-voluntary">
                    <tr><td class="px-4 py-2"><input type="text" name="voluntary[INDEX][org_name_address]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="date" name="voluntary[INDEX][volunteer_from]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="date" name="voluntary[INDEX][volunteer_to]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="number" name="voluntary[INDEX][number_of_hours]" min="0" class="h-9 w-20 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="voluntary[INDEX][nature_of_work]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                </template>
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" onclick="addRow('voluntary')" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium rounded-md border border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800">Add Entry</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Section VII: Learning & Development --}}
    <form method="POST" action="{{ route('employee.pds.store') }}" class="mb-6">
        @csrf
        <input type="hidden" name="section" value="training">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c3-training">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">VII. Learning & Development</h3>
                    <button type="button" onclick="toggleCard('c3-training')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md border border-gray-200 dark:border-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800"><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Title</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">From</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">To</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hours</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sponsor</th><th class="px-4 py-2 w-10"></th></tr></thead>
                        <tbody data-rows="training">
                            @forelse($training_records as $i => $t)
                            <tr><td class="px-4 py-2"><input type="text" name="training[{{ $i }}][title]" value="{{ $t->title ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="date" name="training[{{ $i }}][training_from]" value="{{ $t->training_from ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="date" name="training[{{ $i }}][training_to]" value="{{ $t->training_to ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="number" name="training[{{ $i }}][number_of_hours]" value="{{ $t->number_of_hours ?? '' }}" min="0" class="h-9 w-20 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="training[{{ $i }}][training_type]" value="{{ $t->training_type ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="training[{{ $i }}][sponsor]" value="{{ $t->sponsor ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <template id="tpl-training">
                    <tr><td class="px-4 py-2"><input type="text" name="training[INDEX][title]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="date" name="training[INDEX][training_from]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="date" name="training[INDEX][training_to]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td class="px-4 py-2"><input type="number" name="training[INDEX][number_of_hours]" min="0" class="h-9 w-20 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="training[INDEX][training_type]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="training[INDEX][sponsor]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                </template>
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" onclick="addRow('training')" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium rounded-md border border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800">Add Entry</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Section VIII: Other Information --}}
    <form method="POST" action="{{ route('employee.pds.store') }}">
        @csrf
        <input type="hidden" name="section" value="other">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c3-other">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">VIII. Other Information</h3>
                    <button type="button" onclick="toggleCard('c3-other')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md border border-gray-200 dark:border-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800"><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Skills</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Academic Distinctions</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Memberships</th><th class="px-4 py-2 w-10"></th></tr></thead>
                        <tbody data-rows="other">
                            @forelse($other_info as $i => $o)
                            <tr><td class="px-4 py-2"><input type="text" name="other[{{ $i }}][skills]" value="{{ $o->skills ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="other[{{ $i }}][academic_distinctions]" value="{{ $o->academic_distinctions ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="other[{{ $i }}][memberships]" value="{{ $o->memberships ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <template id="tpl-other">
                    <tr><td class="px-4 py-2"><input type="text" name="other[INDEX][skills]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="other[INDEX][academic_distinctions]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="other[INDEX][memberships]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                </template>
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" onclick="addRow('other')" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium rounded-md border border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800">Add Entry</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
