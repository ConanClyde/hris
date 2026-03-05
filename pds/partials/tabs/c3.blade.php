{{-- C3: Voluntary Work, Training, Other Information --}}

{{-- VI. Voluntary Work --}}
<div id="content-voluntary" class="tab-content hidden">
        <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
                @csrf
                <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">VI. Voluntary Work or Involvement in Civic /
                        Non-Government / People / Voluntary Organization/s</h3>

                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-none">
                                <thead class="bg-gray-50">
                                        <tr>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Name & Address of Organization (Write in full)</th>
                                                <th class="px-3 py-2 text-center text-sm font-medium text-gray-500 uppercase"
                                                        colspan="2">Inclusive Dates</th>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Number of Hours</th>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Position / Nature of Work</th>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Action</th>
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
                                <tbody id="voluntary-tbody" class="bg-white">
                                        @php $voluntaries = $voluntary_work ?? collect([]); @endphp
                                        @forelse($voluntaries as $index => $vol)
                                                <tr>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="voluntary[{{ $index }}][name_and_address_of_organization]"
                                                                        value="{{ $vol->name_and_address_of_organization ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                                <input type="hidden" name="voluntary[{{ $index }}][id]"
                                                                        value="{{ $vol->id ?? '' }}">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date"
                                                                        name="voluntary[{{ $index }}][volunteer_from]"
                                                                        value="{{ isset($vol->volunteer_from) ? \Carbon\Carbon::parse($vol->volunteer_from)->format('Y-m-d') : '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date"
                                                                        name="voluntary[{{ $index }}][volunteer_to]"
                                                                        value="{{ isset($vol->volunteer_to) ? \Carbon\Carbon::parse($vol->volunteer_to)->format('Y-m-d') : '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="voluntary[{{ $index }}][number_of_hours]"
                                                                        value="{{ $vol->number_of_hours ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="voluntary[{{ $index }}][nature_of_work]"
                                                                        value="{{ $vol->nature_of_work ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                                        title="Remove Entry">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                </button>
                                                        </td>
                                                </tr>
                                        @empty
                                                <tr>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="voluntary[0][name_and_address_of_organization]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date"
                                                                        name="voluntary[0][volunteer_from]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date"
                                                                        name="voluntary[0][volunteer_to]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="voluntary[0][number_of_hours]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="voluntary[0][nature_of_work]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                                        title="Remove Entry">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                </button>
                                                        </td>
                                                </tr>
                                        @endforelse
                                </tbody>
                        </table>
                </div>

                <p class="mt-2 text-sm text-gray-500">No entries yet? Click the button below to add. Leave empty if not applicable.</p>
                <div class="mt-2">
                        <button type="button" onclick="addRow('voluntary')"
                                class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">+
                                Add
                                Entry</button>
                </div>

                <template id="voluntary-template">
                        <tr>
                                <td class="px-3 py-2.5"><input type="text"
                                                name="voluntary[INDEX][name_and_address_of_organization]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                        <input type="hidden" name="voluntary[INDEX][id]" value="">
                                </td>
                                <td class="px-3 py-2.5"><input type="date" name="voluntary[INDEX][volunteer_from]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="date" name="voluntary[INDEX][volunteer_to]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="text" name="voluntary[INDEX][number_of_hours]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="text" name="voluntary[INDEX][nature_of_work]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                title="Remove Entry">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
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

{{-- VII. Learning & Development --}}
<div id="content-training" class="tab-content hidden">
        <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
                @csrf
                <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">VII. Learning & Development (L&D)
                        Interventions/Training Programs Attended</h3>

                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-none">
                                <thead class="bg-gray-50">
                                        <tr>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Title of Learning and Development
                                                        Interventions/Training Programs</th>
                                                <th class="px-3 py-2 text-center text-sm font-medium text-gray-500 uppercase"
                                                        colspan="2">Inclusive Dates of Attendance</th>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Number of Hours</th>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Type of LD ( Managerial/ Supervisory/ Technical/etc)
                                                </th>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Conducted/ Sponsored By</th>
                                                <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase"
                                                        rowspan="2">Action</th>
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
                                <tbody id="training-tbody" class="bg-white">
                                        @php $trainings = $training_records ?? collect([]); @endphp
                                        @forelse($trainings as $index => $training)
                                                <tr>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="training[{{ $index }}][title]"
                                                                        value="{{ $training->title ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                                <input type="hidden" name="training[{{ $index }}][id]"
                                                                        value="{{ $training->id ?? '' }}">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date"
                                                                        name="training[{{ $index }}][training_from]"
                                                                        value="{{ isset($training->training_from) ? \Carbon\Carbon::parse($training->training_from)->format('Y-m-d') : '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date"
                                                                        name="training[{{ $index }}][training_to]"
                                                                        value="{{ isset($training->training_to) ? \Carbon\Carbon::parse($training->training_to)->format('Y-m-d') : '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="training[{{ $index }}][number_of_hours]"
                                                                        value="{{ $training->number_of_hours ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="training[{{ $index }}][training_type]"
                                                                        value="{{ $training->training_type ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="training[{{ $index }}][sponsor]"
                                                                        value="{{ $training->sponsor ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                                        title="Remove Entry">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                </button>
                                                        </td>
                                                </tr>
                                        @empty
                                                <tr>
                                                        <td class="px-3 py-2.5"><input type="text" name="training[0][title]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date"
                                                                        name="training[0][training_from]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="date" name="training[0][training_to]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="training[0][number_of_hours]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="training[0][training_type]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text" name="training[0][sponsor]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                                        title="Remove Entry">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                </button>
                                                        </td>
                                                </tr>
                                        @endforelse
                                </tbody>
                        </table>
                </div>

                <p class="mt-2 text-sm text-gray-500">No entries yet? Click the button below to add. Leave empty if not applicable.</p>
                <div class="mt-2">
                        <button type="button" onclick="addRow('training')"
                                class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">+
                                Add
                                Entry</button>
                </div>

                <template id="training-template">
                        <tr>
                                <td class="px-3 py-2.5"><input type="text" name="training[INDEX][title]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                        <input type="hidden" name="training[INDEX][id]" value="">
                                </td>
                                <td class="px-3 py-2.5"><input type="date" name="training[INDEX][training_from]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="date" name="training[INDEX][training_to]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="text" name="training[INDEX][number_of_hours]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="text" name="training[INDEX][training_type]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="text" name="training[INDEX][sponsor]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                title="Remove Entry">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
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

{{-- VIII. Other Information --}}
<div id="content-other" class="tab-content hidden">
        <form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-6">
                @csrf
                <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">VIII. Other Information</h3>

                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-none">
                                <thead class="bg-gray-50">
                                        <tr>
                                                <th
                                                        class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">
                                                        Special Skills and Hobbies</th>
                                                <th
                                                        class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">
                                                        Non-Academic Distinctions / Recognition</th>
                                                <th
                                                        class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">
                                                        Membership in Association/Organization</th>
                                                <th
                                                        class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">
                                                        Action</th>
                                        </tr>
                                </thead>
                                <tbody id="other-tbody" class="bg-white">
                                        @php $others = $other_info ?? collect([]); @endphp
                                        @forelse($others as $index => $other)
                                                <tr>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="other[{{ $index }}][skills]"
                                                                        value="{{ $other->skills ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                                <input type="hidden" name="other[{{ $index }}][id]"
                                                                        value="{{ $other->id ?? '' }}">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="other[{{ $index }}][academic]"
                                                                        value="{{ $other->academic ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text"
                                                                        name="other[{{ $index }}][membership]"
                                                                        value="{{ $other->membership ?? '' }}"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                                        title="Remove Entry">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                </button>
                                                        </td>
                                                </tr>
                                        @empty
                                                <tr>
                                                        <td class="px-3 py-2.5"><input type="text" name="other[0][skills]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text" name="other[0][academic]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><input type="text" name="other[0][membership]"
                                                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                                        </td>
                                                        <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                                        class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                                        title="Remove Entry">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                </button>
                                                        </td>
                                                </tr>
                                        @endforelse
                                </tbody>
                        </table>
                </div>

                <p class="mt-2 text-sm text-gray-500">No entries yet? Click the button below to add. Leave empty if not applicable.</p>
                <div class="mt-2">
                        <button type="button" onclick="addRow('other')"
                                class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">+
                                Add
                                Entry</button>
                </div>

                <template id="other-template">
                        <tr>
                                <td class="px-3 py-2.5"><input type="text" name="other[INDEX][skills]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                        <input type="hidden" name="other[INDEX][id]" value="">
                                </td>
                                <td class="px-3 py-2.5"><input type="text" name="other[INDEX][academic]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><input type="text" name="other[INDEX][membership]"
                                                class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all">
                                </td>
                                <td class="px-3 py-2.5"><button type="button" onclick="removeRow(this)"
                                                class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                                title="Remove Entry">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
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
