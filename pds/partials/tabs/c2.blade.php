{{-- C2: Civil Service Eligibility, Work Experience --}}

{{-- IV. Civil Service Eligibility --}}
<div id="content-eligibility" class="tab-content hidden">
    <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
        @csrf
        <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">IV. Civil Service Eligibility</h3>

        <div class="overflow-x-auto">
            {{-- REMOVED: divide-y divide-gray-200 from table --}}
            <table class="min-w-full divide-y divide-gray-200 border-none">
                <thead class="bg-gray-50">
                    <tr>
                        {{-- REMOVED: border-r from all th --}}
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Career
                            Service/ RA 1080 (Board/ Bar) Under Special Laws/ CES/ CSEE Barangay
                            Eligibility / Driver's License</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Rating
                            (If Applicable)</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Date of
                            Examination / Conferment</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Place of
                            Examination / Conferment</th>
                        <th class="px-3 py-2 text-center text-sm font-medium text-gray-500 uppercase" colspan="2">
                            License (if applicable)</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Action
                        </th>
                    </tr>
                    <tr>
                        {{-- KEEP: border-t for header separation, REMOVE border-r --}}
                        <th
                            class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase border-t border-gray-100">
                            Number</th>
                        <th
                            class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase border-t border-gray-100">
                            Date of
                            Validity</th>
                    </tr>
                </thead>
                {{-- REMOVED: divide-y divide-gray-200 from tbody --}}
                <tbody id="eligibility-tbody" class="bg-white">
                    @php $eligibility = $csc_eligibility ?? collect([]); @endphp
                    @forelse($eligibility as $index => $item)
                        <tr>
                            {{-- REMOVED: border-r from all td --}}
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[{{ $index }}][license_name]"
                                    value="{{ $item->license_name ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                <input type="hidden" name="eligibility[{{ $index }}][id]" value="{{ $item->id ?? '' }}">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[{{ $index }}][rating]"
                                    value="{{ $item->rating ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="date" name="eligibility[{{ $index }}][date_of_examination]"
                                    value="{{ isset($item->date_of_examination) ? \Carbon\Carbon::parse($item->date_of_examination)->format('Y-m-d') : '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[{{ $index }}][place_of_examination]"
                                    value="{{ $item->place_of_examination ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[{{ $index }}][license_no]"
                                    value="{{ $item->license_no ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="date" name="eligibility[{{ $index }}][date_of_validity]"
                                    value="{{ isset($item->date_of_validity) ? \Carbon\Carbon::parse($item->date_of_validity)->format('Y-m-d') : '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                            </td>
                            <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                    class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                    title="Remove Entry">
                                    <x-icons.trash class="w-4 h-4" />
                                </button></td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[0][license_name]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[0][rating]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="date" name="eligibility[0][date_of_examination]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[0][place_of_examination]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="eligibility[0][license_no]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="date" name="eligibility[0][date_of_validity]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                    class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                    title="Remove Entry">
                                    <x-icons.trash class="w-4 h-4" />
                                </button></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-2 text-sm text-gray-500">No entries yet? Click the button below to add. Leave empty if not applicable.</p>
        <div class="mt-2">
            <button type="button" onclick="addRow('eligibility')"
                class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">+
                Add Entry</button>
        </div>

        <template id="eligibility-template">
            <tr>
                <td class="px-3 py-2.5"><input type="text" name="eligibility[INDEX][license_name]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                    <input type="hidden" name="eligibility[INDEX][id]" value="">
                </td>
                <td class="px-3 py-2.5"><input type="text" name="eligibility[INDEX][rating]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="date" name="eligibility[INDEX][date_of_examination]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="text" name="eligibility[INDEX][place_of_examination]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="text" name="eligibility[INDEX][license_no]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="date" name="eligibility[INDEX][date_of_validity]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                        class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors cursor-pointer"
                        title="Remove Entry">
                        <x-icons.trash class="w-4 h-4" />
                    </button></td>
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

{{-- V. Work Experience --}}
<div id="content-work" class="tab-content hidden">
    <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
        @csrf
        <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">V. Work Experience</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border-none">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-center text-sm font-medium text-gray-500 uppercase" colspan="2">
                            Inclusive Dates</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Position
                            Title</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">
                            Department / Agency / Office / Company</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Monthly
                            Salary</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Salary/
                            Job/ Pay Grade (if applicable) & Step (Format "00-0")</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Status
                            of Appointment</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Gov't
                            Service (Y/N)</th>
                        <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Action
                        </th>
                    </tr>
                    <tr>
                        <th
                            class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase border-t border-gray-100">
                            From</th>
                        <th
                            class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase border-t border-gray-100">
                            To</th>
                    </tr>
                </thead>
                <tbody id="work-tbody" class="bg-white">
                    @php $works = $work_experience ?? collect([]); @endphp
                    @forelse($works as $index => $work)
                        <tr>
                            <td class="px-3 py-2.5"><input type="date" name="work[{{ $index }}][employed_from]"
                                    value="{{ isset($work->employed_from) ? \Carbon\Carbon::parse($work->employed_from)->format('Y-m-d') : '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                <input type="hidden" name="work[{{ $index }}][id]" value="{{ $work->id ?? '' }}">
                            </td>
                            <td class="px-3 py-2.5"><input type="date" name="work[{{ $index }}][employed_to]"
                                    value="{{ isset($work->employed_to) ? \Carbon\Carbon::parse($work->employed_to)->format('Y-m-d') : '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[{{ $index }}][position_title]"
                                    value="{{ $work->position_title ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[{{ $index }}][department]"
                                    value="{{ $work->department ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="number" step="0.01" name="work[{{ $index }}][salary]"
                                    value="{{ $work->salary ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[{{ $index }}][salary_grade]"
                                    value="{{ $work->salary_grade ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[{{ $index }}][appointment_status]"
                                    value="{{ $work->appointment_status ?? '' }}"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5">
                                <select name="work[{{ $index }}][is_government]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                    <option value="Y" {{ ($work->is_government ?? '') == 'Y' ? 'selected' : '' }}>Y</option>
                                    <option value="N" {{ ($work->is_government ?? '') == 'N' ? 'selected' : '' }}>N</option>
                                </select>
                            </td>
                            <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                    class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                    title="Remove Entry">
                                    <x-icons.trash class="w-4 h-4" />
                                </button></td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-3 py-2.5"><input type="date" name="work[0][employed_from]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="date" name="work[0][employed_to]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[0][position_title]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[0][department]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="number" step="0.01" name="work[0][salary]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[0][salary_grade]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5"><input type="text" name="work[0][appointment_status]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                            </td>
                            <td class="px-3 py-2.5">
                                <select name="work[0][is_government]"
                                    class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                    <option value="Y">Y</option>
                                    <option value="N">N</option>
                                </select>
                            </td>
                            <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                    class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                    title="Remove Entry">
                                    <x-icons.trash class="w-4 h-4" />
                                </button></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-2 text-sm text-gray-500">No entries yet? Click the button below to add. Leave empty if not applicable.</p>
        <div class="mt-2">
            <button type="button" onclick="addRow('work')"
                class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">+
                Add Entry</button>
        </div>

        <template id="work-template">
            <tr>
                <td class="px-3 py-2.5"><input type="date" name="work[INDEX][employed_from]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                    <input type="hidden" name="work[INDEX][id]" value="">
                </td>
                <td class="px-3 py-2.5"><input type="date" name="work[INDEX][employed_to]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="text" name="work[INDEX][position_title]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="text" name="work[INDEX][department]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="number" step="0.01" name="work[INDEX][salary]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="text" name="work[INDEX][salary_grade]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5"><input type="text" name="work[INDEX][appointment_status]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                </td>
                <td class="px-3 py-2.5">
                    <select name="work[INDEX][is_government]"
                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                </td>
                <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                        title="Remove Entry">
                        <x-icons.trash class="w-4 h-4" />
                    </button></td>
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

