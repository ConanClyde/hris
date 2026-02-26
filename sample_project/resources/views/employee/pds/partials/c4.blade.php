{{-- C4: References, Background, Gov ID --}}
<div id="tab-c4" class="tab-panel hidden">
    {{-- Section IX: References --}}
    <form method="POST" action="{{ route('employee.pds.store') }}" class="mb-6">
        @csrf
        <input type="hidden" name="section" value="references">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c4-references">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">IX. References (max 3)</h3>
                    <button type="button" onclick="toggleCard('c4-references')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md border border-gray-200 dark:border-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800"><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Address</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telephone</th><th class="px-4 py-2 w-10"></th></tr></thead>
                        <tbody data-rows="references">
                            @forelse($reference_records as $i => $r)
                            <tr><td class="px-4 py-2"><input type="text" name="references[{{ $i }}][reference_name]" value="{{ $r->reference_name ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="references[{{ $i }}][reference_address]" value="{{ $r->reference_address ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="references[{{ $i }}][reference_telno]" value="{{ $r->reference_telno ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <template id="tpl-references">
                    <tr><td class="px-4 py-2"><input type="text" name="references[INDEX][reference_name]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="references[INDEX][reference_address]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="text" name="references[INDEX][reference_telno]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                </template>
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" onclick="addRow('references')" id="add-reference-btn" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium rounded-md border border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed">Add Reference</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Section X: Background Information --}}
    <form method="POST" action="{{ route('employee.pds.store') }}" class="mb-6">
        @csrf
        <input type="hidden" name="section" value="background">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c4-background">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">X. Background Information</h3>
                    <button type="button" onclick="toggleCard('c4-background')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6 space-y-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Answer the following questions (Questions 34–40). Provide details if Yes.</p>

                {{-- Question 34: Consanguinity/Affinity --}}
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row gap-2 mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">34. Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will be appointed,</label>
                    </div>
                    <div class="sm:pl-4 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="text-sm text-gray-700 dark:text-gray-300 sm:w-96 pt-1">a. within the third degree?</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q34a" value="yes" {{ old('q34a') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q34a" value="no" {{ old('q34a', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="text-sm text-gray-700 dark:text-gray-300 sm:w-96 pt-1">b. within the fourth degree (for Local Government Unit - Career Employees)?</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q34b" value="yes" {{ old('q34b') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q34b" value="no" {{ old('q34b', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                        </div>
                        <div class="sm:pl-0">
                            <textarea name="q34_details" data-bg-details="34" rows="2" placeholder="If YES to 34a or 34b, give details:" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q34_details') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Question 35: Administrative Offense & Criminal Charge --}}
                <div class="mb-6">
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">35. a. Have you ever been found guilty of any administrative offense?</span>
                            <div class="flex gap-4 shrink-0">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q35a" value="yes" {{ old('q35a') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q35a" value="no" {{ old('q35a', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                        </div>
                        <div class="sm:pl-0">
                            <textarea name="q35a_details" data-bg-details="35a" rows="2" placeholder="If YES, give details:" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q35a_details') }}</textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">&nbsp;&nbsp;&nbsp;b. Have you been criminally charged before any court?</span>
                            <div class="flex gap-4 shrink-0">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q35b" value="yes" {{ old('q35b') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q35b" value="no" {{ old('q35b', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                        </div>
                        <div class="sm:pl-0">
                            <textarea name="q35b_details" data-bg-details="35b" rows="2" placeholder="If YES, give details:" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q35b_details') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Question 36: Convicted of Crime --}}
                <div class="flex flex-col sm:flex-row sm:items-start gap-2 mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">36. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</label>
                    <div class="flex gap-4 shrink-0">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q36" value="yes" {{ old('q36') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q36" value="no" {{ old('q36', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                    </div>
                </div>
                <div class="mb-6">
                    <textarea name="q36_details" data-bg-details="36" rows="2" placeholder="If YES, give details:" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q36_details') }}</textarea>
                </div>

                {{-- Question 37: Separated from Service --}}
                <div class="flex flex-col sm:flex-row sm:items-start gap-2 mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">37. Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</label>
                    <div class="flex gap-4 shrink-0">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q37" value="yes" {{ old('q37') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q37" value="no" {{ old('q37', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                    </div>
                </div>
                <div class="mb-6">
                    <textarea name="q37_details" data-bg-details="37" rows="2" placeholder="If YES, give details:" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q37_details') }}</textarea>
                </div>

                {{-- Question 38: Election Candidate & Resignation --}}
                <div class="mb-6">
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">38. a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</span>
                            <div class="flex gap-4 shrink-0">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q38a" value="yes" {{ old('q38a') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q38a" value="no" {{ old('q38a', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                        </div>
                        <div class="sm:pl-0">
                            <textarea name="q38a_details" data-bg-details="38a" rows="2" placeholder="If YES, give details:" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q38a_details') }}</textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">&nbsp;&nbsp;&nbsp;b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</span>
                            <div class="flex gap-4 shrink-0">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q38b" value="yes" {{ old('q38b') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q38b" value="no" {{ old('q38b', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                        </div>
                        <div class="sm:pl-0">
                            <textarea name="q38b_details" data-bg-details="38b" rows="2" placeholder="If YES, give details:" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q38b_details') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Question 39: Immigrant/Permanent Resident --}}
                <div class="flex flex-col sm:flex-row sm:items-start gap-2 mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 sm:w-full">39. Have you acquired the status of an immigrant or permanent resident of another country?</label>
                    <div class="flex gap-4 shrink-0">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q39" value="yes" {{ old('q39') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q39" value="no" {{ old('q39', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                    </div>
                </div>
                <div class="mb-6">
                    <textarea name="q39_details" data-bg-details="39" rows="2" placeholder="If YES, give details (country):" class="w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">{{ old('q39_details') }}</textarea>
                </div>

                {{-- Question 40: Indigenous, PWD, Solo Parent --}}
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277, as amended); and (c) Expanded Solo Parents Welfare Act (RA 11861), please answer the following items:</p>
                    <div class="space-y-4 sm:pl-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 pt-2">a. Are you a member of any indigenous group?</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q40a" value="yes" {{ old('q40a') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q40a" value="no" {{ old('q40a', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                            <input type="text" name="q40a_specify" value="{{ old('q40a_specify') }}" placeholder="If YES, please specify:" data-bg-specify="40a" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 pt-2">b. Are you a person with disability?</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q40b" value="yes" {{ old('q40b') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q40b" value="no" {{ old('q40b', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                            <input type="text" name="q40b_idno" value="{{ old('q40b_idno') }}" placeholder="If YES, please specify ID No:" data-bg-specify="40b" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 pt-2">c. Are you a solo parent?</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q40c" value="yes" {{ old('q40c') === 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span></label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="q40c" value="no" {{ old('q40c', 'no') !== 'yes' ? 'checked' : '' }} class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]"> <span class="text-sm text-gray-700 dark:text-gray-300">No</span></label>
                            </div>
                            <input type="text" name="q40c_idno" value="{{ old('q40c_idno') }}" placeholder="If YES, please specify ID No:" data-bg-specify="40c" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                        </div>
                    </div>
                </div>
                <div class="pt-4 flex justify-end"><button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button></div>
            </div>
        </div>
    </form>

    {{-- Section XI: Government Issued ID --}}
    <form method="POST" action="{{ route('employee.pds.store') }}">
        @csrf
        <input type="hidden" name="section" value="govid">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c4-govid">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">XI. Government Issued ID</h3>
                    <button type="button" onclick="toggleCard('c4-govid')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">ID Type / Name</label><input type="text" name="govid_name" value="{{ old('govid_name', $govid_records->govid_name ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">ID Number</label><input type="text" name="govid_no" value="{{ old('govid_no', $govid_records->govid_no ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date/Place of Issue</label><input type="text" name="govid_dateplace" value="{{ old('govid_dateplace', $govid_records->govid_dateplace ?? '') }}" placeholder="e.g. Jan 15, 2020 / Manila" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                </div>
                <div class="mt-4 flex justify-end"><button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button></div>
            </div>
        </div>
    </form>
</div>
