{{-- C4: References, Background Questions, Government ID --}}

{{-- IX. References --}}
{{-- IX. References --}}
<div id="content-references" class="tab-content hidden">
    <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
        @csrf
        <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">IX. References (Person not related by consanguinity
            or affinity to applicant /appointee)</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border-none">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">Address
                        </th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">Tel. No.
                        </th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody id="references-tbody" class="bg-white divide-y divide-gray-200">
                    @php $references = $reference_records ?? collect([]); @endphp
                    @forelse($references as $index => $ref)
                        <tr>
                            <td class="px-3 py-2.5">
                                <input type="text" name="references[{{ $index }}][reference_name]"
                                    value="{{ $ref->reference_name ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                <input type="hidden" name="references[{{ $index }}][id]" value="{{ $ref->id ?? '' }}">
                            </td>
                            <td class="px-3 py-2.5">
                                <input type="text" name="references[{{ $index }}][reference_address]"
                                    value="{{ $ref->reference_address ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                            </td>
                            <td class="px-3 py-2.5">
                                <input type="text" name="references[{{ $index }}][reference_telno]"
                                    value="{{ $ref->reference_telno ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                            </td>
                            <td class="px-3 py-2.5">
                                <button type="button" onclick="removeRow(this)"
                                    class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                    title="Remove Entry">
                                    <x-icons.trash class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-3 py-2.5">
                                <input type="text" name="references[0][reference_name]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                            </td>
                            <td class="px-3 py-2.5">
                                <input type="text" name="references[0][reference_address]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                            </td>
                            <td class="px-3 py-2.5">
                                <input type="text" name="references[0][reference_telno]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                            </td>
                            <td class="px-3 py-2.5">
                                <button type="button" onclick="removeRow(this)"
                                    class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                    title="Remove Entry">
                                    <x-icons.trash class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-2 text-sm text-gray-500">Add up to 3 references. No entries yet? Click the button below to add.</p>
        <div class="mt-2">
            <button type="button" onclick="addRow('references')"
                class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">+
                Add Reference</button>
        </div>

        <template id="reference-template">
            <tr>
                <td class="px-3 py-2.5">
                    <input type="text" name="references[INDEX][reference_name]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                    <input type="hidden" name="references[INDEX][id]" value="">
                </td>
                <td class="px-3 py-2.5">
                    <input type="text" name="references[INDEX][reference_address]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                </td>
                <td class="px-3 py-2.5">
                    <input type="text" name="references[INDEX][reference_telno]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                </td>
                <td class="px-3 py-2.5">
                    <button type="button" onclick="removeRow(this)"
                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                        title="Remove Entry">
                        <x-icons.trash class="w-4 h-4" />
                    </button>
                </td>
            </tr>
        </template>

        <div class="flex justify-end pt-4 border-t border-[#e3e3e0]">
            <button type="submit"
                class="px-6 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">
                Save Changes
            </button>
        </div>
    </form>
</div>

{{-- X. Background Information --}}
<div id="content-background" class="tab-content hidden">
    <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
        @csrf
        <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">X. Background Information</h3>

        <div class="overflow-x-auto border border-[#e3e3e0] rounded-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase w-3/5">Question</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase">Answer</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Q34 --}}
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="mb-2 font-medium">34. Are you related by consanguinity or affinity to the
                                appointing or recommending authority, or to the chief of bureau or office or to the
                                person who has immediate supervision over you in the Office, Bureau or Department where
                                you will be appointed,</p>
                            <p class="ml-4 mb-1">a. within the third degree?</p>
                            <p class="ml-4">b. within the fourth degree (for Local Government Unit - Career Employees)?
                            </p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="mb-3">
                                <div class="flex gap-4 mb-2">
                                    <label class="inline-flex items-center gap-2 text-sm"><input type="radio"
                                            name="q34a" value="yes" {{ ($check->cs_id_1 ?? '') == 'yes' ? 'checked' : '' }} class="text-primary focus:ring-primary"> Yes</label>
                                    <label class="inline-flex items-center gap-2 text-sm"><input type="radio"
                                            name="q34a" value="no" {{ ($check->cs_id_1 ?? '') == 'no' ? 'checked' : '' }}
                                            class="text-primary focus:ring-primary">
                                        No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="flex gap-4 mb-2">
                                    <label class="inline-flex items-center gap-2 text-sm"><input type="radio"
                                            name="q34b" value="yes" {{ ($check->cs_id_2 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="inline-flex items-center gap-2 text-sm"><input type="radio"
                                            name="q34b" value="no" {{ ($check->cs_id_2 ?? '') == 'no' ? 'checked' : '' }}>
                                        No</label>
                                </div>
                            </div>
                            <input type="text" name="q34_details" value="{{ $check->txtarea2 ?? '' }}"
                                placeholder="If YES to any of the above, give details"
                                class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                        </td>
                    </tr>

                    {{-- Q35 --}}
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="mb-2 font-medium">35. a. Have you ever been found guilty of any administrative
                                offense?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q35a"
                                        value="yes" {{ ($check->cs_id_3 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q35a"
                                        value="no" {{ ($check->cs_id_3 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q35a_details" value="{{ $check->txtarea3 ?? '' }}"
                                placeholder="If YES, give details"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="mb-2 font-medium">35. b. Have you been criminally charged before any court?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q35b"
                                        value="yes" {{ ($check->cs_id_4 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q35b"
                                        value="no" {{ ($check->cs_id_4 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q35b_details" value="{{ $check->txtarea4 ?? '' }}"
                                placeholder="If YES, give details"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white mb-2">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="q35b_date_filed"
                                    value="{{ isset($check) && $check->case_date ? date('Y-m-d', strtotime($check->case_date)) : '' }}"
                                    placeholder="Date Filed (YYYY-MM-DD)"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                <input type="text" name="q35b_status" value="{{ $check->case_status ?? '' }}"
                                    placeholder="Status of Case/s"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                            </div>
                        </td>
                    </tr>

                    {{-- Q36 --}}
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="font-medium">36. Have you ever been convicted of any crime or violation of any
                                law, decree, ordinance or regulation by any court or tribunal?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q36"
                                        value="yes" {{ ($check->cs_id_5 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q36"
                                        value="no" {{ ($check->cs_id_5 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q36_details" value="{{ $check->txtarea5 ?? '' }}"
                                placeholder="If YES, give details"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>

                    {{-- Q37 --}}
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="font-medium">37. Have you ever been separated from the service in any of the
                                following modes: resignation, retirement, dropped from the rolls, dismissal,
                                termination, end of term, finished contract or phased out in the public or private
                                sector?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q37"
                                        value="yes" {{ ($check->cs_id_6 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q37"
                                        value="no" {{ ($check->cs_id_6 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q37_details" value="{{ $check->txtarea6 ?? '' }}"
                                placeholder="If YES, give details"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>

                    {{-- Q38 --}}
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="mb-2 font-medium">38. a. Have you ever been a candidate in a national or local
                                election held within the last year (except Barangay election)?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q38a"
                                        value="yes" {{ ($check->cs_id_7 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q38a"
                                        value="no" {{ ($check->cs_id_7 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q38a_details" value="{{ $check->txtarea7 ?? '' }}"
                                placeholder="If YES, give details"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="mb-2 font-medium">38. b. Have you resigned from the government service during the
                                three (3)-month period before the last election to promote/actively campaign for a
                                national or local candidate?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q38b"
                                        value="yes" {{ ($check->cs_id_8 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q38b"
                                        value="no" {{ ($check->cs_id_8 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q38b_details" value="{{ $check->txtarea8 ?? '' }}"
                                placeholder="If YES, give details"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>

                    {{-- Q39 --}}
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <p class="font-medium">39. Have you acquired the status of an immigrant or permanent
                                resident of another country?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q39"
                                        value="yes" {{ ($check->cs_id_9 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q39"
                                        value="no" {{ ($check->cs_id_9 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q39_details" value="{{ $check->txtarea9 ?? '' }}"
                                placeholder="If YES, give details (Country)"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>

                    {{-- Q40 --}}
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700" colspan="2">
                            <p class="font-medium">40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna
                                Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA
                                8972), please answer the following items:</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700 pl-8">
                            <p class="mb-1">a. Are you a member of any indigenous group?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q40a"
                                        value="yes" {{ ($check->cs_id_10 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q40a"
                                        value="no" {{ ($check->cs_id_10 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q40a_details" value="{{ $check->txtarea10 ?? '' }}"
                                placeholder="If YES, please specify"
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700 pl-8">
                            <p class="mb-1">b. Are you a person with disability?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q40b"
                                        value="yes" {{ ($check->cs_id_11 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q40b"
                                        value="no" {{ ($check->cs_id_11 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q40b_details" value="{{ $check->txtarea11 ?? '' }}"
                                placeholder="If YES, please specify ID No."
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700 pl-8">
                            <p class="mb-1">c. Are you a solo parent?</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex gap-4 mb-2">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q40c"
                                        value="yes" {{ ($check->cs_id_12 ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="q40c"
                                        value="no" {{ ($check->cs_id_12 ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <input type="text" name="q40c_details" value="{{ $check->txtarea12 ?? '' }}"
                                placeholder="If YES, please specify ID No."
                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="flex justify-end pt-4 border-t border-[#e3e3e0]">
            <button type="submit"
                class="px-6 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">
                Save Changes
            </button>
        </div>
    </form>
</div>

{{-- XI. Government Issued ID --}}
<div id="content-govid" class="tab-content hidden">
    <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
        @csrf
        <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">XI. Government Issued ID (i.e. Passport, GSIS, SSS,
            PRC, Driver's License, etc.)</h3>
        <p class="text-sm text-gray-500 mb-4">PLEASE INDICATE ID Number and Date of Issuance</p>

        <div class="space-y-4">
            @php
                $govid = null;
                if (isset($govid_records) && count($govid_records) > 0) {
                    $govid = $govid_records[0];
                }
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Government Issued ID</label>
                    <input type="text" name="govid[0][govid_name]" value="{{ $govid->govid_name ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all"
                        placeholder="e.g. Passport, SSS, GSIS">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">ID/License No.</label>
                    <input type="text" name="govid[0][govid_no]" value="{{ $govid->govid_no ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Date/Place of Issuance</label>
                    <input type="text" name="govid[0][govid_dateplace]" value="{{ $govid->govid_dateplace ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-[#e3e3e0] mt-6">
            <button type="submit"
                class="px-6 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">
                Save Changes
            </button>
        </div>
    </form>
</div>

