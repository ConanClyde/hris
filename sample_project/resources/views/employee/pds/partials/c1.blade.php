{{-- C1: Personal & Family, Education (divided into separate cards like C2/C3) --}}
<div id="tab-c1" class="tab-panel hidden space-y-6">
    {{-- Card 1: Personal Information & Official IDs --}}
    <form method="POST" action="{{ route('employee.pds.store') }}">
        @csrf
        <input type="hidden" name="section" value="c1_personal">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c1-personal">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">I. Personal Information</h3>
                    <button type="button" onclick="toggleCard('c1-personal')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Surname</label><input type="text" name="surname" value="{{ old('surname', $pds->surname ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">First Name</label><input type="text" name="first_name" value="{{ old('first_name', $pds->first_name ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Middle Name</label><input type="text" name="middle_name" value="{{ old('middle_name', $pds->middle_name ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Name Extension</label><input type="text" name="name_extension" value="{{ old('name_extension', $pds->name_extension ?? '') }}" placeholder="Jr., Sr., III" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date of Birth</label><input type="date" name="dob" value="{{ old('dob', $pds->dob ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950 dark:[color-scheme:dark]"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Place of Birth</label><input type="text" name="place_of_birth" value="{{ old('place_of_birth', $pds->place_of_birth ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sex</label><select name="sex" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"><option value="">Select</option><option value="male" {{ old('sex', $pds->sex ?? '') == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ old('sex', $pds->sex ?? '') == 'female' ? 'selected' : '' }}>Female</option></select></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Civil Status</label><select name="civil_status" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"><option value="">Select</option><option value="single" {{ old('civil_status', $pds->civil_status ?? '') == 'single' ? 'selected' : '' }}>Single</option><option value="married" {{ old('civil_status', $pds->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option><option value="widowed" {{ old('civil_status', $pds->civil_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option><option value="separated" {{ old('civil_status', $pds->civil_status ?? '') == 'separated' ? 'selected' : '' }}>Separated</option><option value="other" {{ old('civil_status', $pds->civil_status ?? '') == 'other' ? 'selected' : '' }}>Other</option></select></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Height (m)</label><input type="number" name="height" value="{{ old('height', $pds->height ?? '') }}" min="0.1" max="3.0" step="0.01" inputmode="decimal" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Weight (kg)</label><input type="number" name="weight" value="{{ old('weight', $pds->weight ?? '') }}" min="1" step="0.1" inputmode="decimal" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Blood Type</label><select name="blood_type" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"><option value="">Select</option><option value="A+" {{ old('blood_type', $pds->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+</option><option value="A-" {{ old('blood_type', $pds->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A-</option><option value="B+" {{ old('blood_type', $pds->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+</option><option value="B-" {{ old('blood_type', $pds->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B-</option><option value="AB+" {{ old('blood_type', $pds->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option><option value="AB-" {{ old('blood_type', $pds->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option><option value="O+" {{ old('blood_type', $pds->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+</option><option value="O-" {{ old('blood_type', $pds->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O-</option></select></div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Citizenship</label>
                        <select name="citizenship_type" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                            <option value="">Select</option>
                            <option value="Filipino" {{ old('citizenship_type', $pds->citizenship_type ?? '') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                            <option value="Dual" {{ old('citizenship_type', $pds->citizenship_type ?? '') == 'Dual' ? 'selected' : '' }}>Dual Citizenship</option>
                        </select>
                        <div class="mt-1.5 flex flex-wrap gap-x-4 gap-y-1">
                            <label class="inline-flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300">
                                <input type="radio" name="citizenship_nature" value="by_birth" {{ old('citizenship_nature', $pds->citizenship_nature ?? '') == 'by_birth' ? 'checked' : '' }} class="text-[#013CFC] focus:ring-[#013CFC]">
                                By Birth
                            </label>
                            <label class="inline-flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300">
                                <input type="radio" name="citizenship_nature" value="by_naturalization" {{ old('citizenship_nature', $pds->citizenship_nature ?? '') == 'by_naturalization' ? 'checked' : '' }} class="text-[#013CFC] focus:ring-[#013CFC]">
                                By Naturalization
                            </label>
                        </div>
                        <div class="mt-1.5" data-dual-country-wrap>
                            <div class="relative">
                                <input type="text" id="citizenship_country_search" data-dual-country value="{{ old('citizenship_country', $pds->citizenship_country ?? '') }}" placeholder="Country (if Dual)" class="h-8 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-1.5 text-xs text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                <input type="hidden" name="citizenship_country" id="citizenship_country_hidden" value="{{ old('citizenship_country', $pds->citizenship_country ?? '') }}">
                                <div id="citizenship_country_list" class="hidden fixed z-50 max-h-56 overflow-auto rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 dark:ring-1 dark:ring-neutral-800 shadow-xl dark:shadow-2xl dark:shadow-black/40">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Telephone No.</label><input type="text" name="phone" value="{{ old('phone', $pds->phone ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mobile No.</label><input type="text" name="mobile" value="{{ old('mobile', $pds->mobile ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email Address</label><input type="email" name="email" value="{{ old('email', $pds->email ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Card 1B: Official Identification Numbers (under I. Personal Information) --}}
    <form method="POST" action="{{ route('employee.pds.store') }}">
        @csrf
        <input type="hidden" name="section" value="c1_ids">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c1-ids">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">I. Personal Information — Official Identification Numbers</h3>
                    <button type="button" onclick="toggleCard('c1-ids')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">CS ID</label><input type="text" name="cs_id" value="{{ old('cs_id', $pds->cs_id ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Agency Employee No.</label><input type="text" name="agency_employee_no" value="{{ old('agency_employee_no', $pds->agency_employee_no ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">GSIS</label><input type="text" name="gsis" value="{{ old('gsis', $pds->gsis ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">PAG-IBIG</label><input type="text" name="pag_ibig" value="{{ old('pag_ibig', $pds->pag_ibig ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">PhilHealth</label><input type="text" name="philhealth" value="{{ old('philhealth', $pds->philhealth ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SSS</label><input type="text" name="sss" value="{{ old('sss', $pds->sss ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">TIN</label><input type="text" name="tin" value="{{ old('tin', $pds->tin ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Card 2: Residential & Permanent Address --}}
    <form method="POST" action="{{ route('employee.pds.store') }}">
        @csrf
        <input type="hidden" name="section" value="c1_address">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c1-address">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Residential & Permanent Address</h3>
                    <button type="button" onclick="toggleCard('c1-address')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6 space-y-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Residential Address</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">House/Block/Lot No.</label><input type="text" name="residential_house_block_lot" value="{{ old('residential_house_block_lot', $pds->residential_house_block_lot ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Street</label><input type="text" name="residential_street" value="{{ old('residential_street', $pds->residential_street ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Subdivision</label><input type="text" name="residential_subdivision" value="{{ old('residential_subdivision', $pds->residential_subdivision ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Barangay</label><input type="text" name="residential_barangay" value="{{ old('residential_barangay', $pds->residential_barangay ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">City/Municipality</label><input type="text" name="residential_city_municipality" value="{{ old('residential_city_municipality', $pds->residential_city_municipality ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Province</label><input type="text" name="residential_province" value="{{ old('residential_province', $pds->residential_province ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Zip Code</label><input type="text" name="residential_zip" value="{{ old('residential_zip', $pds->residential_zip ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Permanent Address</h4>
                    <label class="flex items-center gap-2 mb-3 cursor-pointer">
                        <input type="checkbox" id="same-as-residential" onchange="if(this.checked){copyResidentialToPermanent()}else{clearPermanentAddress()}" class="rounded border-gray-300 text-[#013CFC] focus:ring-[#013CFC]">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Same as Residential</span>
                    </label>
                    <div id="permanent-same-banner" class="hidden mb-3 rounded-md border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-900/20 px-4 py-2 text-sm text-green-800 dark:text-green-300">Address copied from residential.</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">House/Block/Lot No.</label><input type="text" name="permanent_house_block_lot" data-permanent-field value="{{ old('permanent_house_block_lot', $pds->permanent_house_block_lot ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Street</label><input type="text" name="permanent_street" data-permanent-field value="{{ old('permanent_street', $pds->permanent_street ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Subdivision</label><input type="text" name="permanent_subdivision" data-permanent-field value="{{ old('permanent_subdivision', $pds->permanent_subdivision ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Barangay</label><input type="text" name="permanent_barangay" data-permanent-field value="{{ old('permanent_barangay', $pds->permanent_barangay ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">City/Municipality</label><input type="text" name="permanent_city_municipality" data-permanent-field value="{{ old('permanent_city_municipality', $pds->permanent_city_municipality ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Province</label><input type="text" name="permanent_province" data-permanent-field value="{{ old('permanent_province', $pds->permanent_province ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Zip Code</label><input type="text" name="permanent_zip" data-permanent-field value="{{ old('permanent_zip', $pds->permanent_zip ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Card 3: Family Background --}}
    <form method="POST" action="{{ route('employee.pds.store') }}">
        @csrf
        <input type="hidden" name="section" value="c1_family">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c1-family">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">II. Family Background</h3>
                    <button type="button" onclick="toggleCard('c1-family')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Spouse</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Name</label><input type="text" name="spouse_name" value="{{ old('spouse_name', $family->spouse_name ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Occupation</label><input type="text" name="spouse_occupation" value="{{ old('spouse_occupation', $family->spouse_occupation ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Employer/Business</label><input type="text" name="spouse_employer" value="{{ old('spouse_employer', $family->spouse_employer ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Business Address</label><input type="text" name="spouse_business_address" value="{{ old('spouse_business_address', $family->spouse_business_address ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Telephone</label><input type="text" name="spouse_telephone" value="{{ old('spouse_telephone', $family->spouse_telephone ?? '') }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                </div>
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Children</h4>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md border border-gray-200 dark:border-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800"><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date of Birth</th><th class="px-4 py-2 w-10"></th></tr></thead>
                    <tbody data-rows="children">
                        @forelse($children as $i => $c)
                        <tr><td class="px-4 py-2"><input type="text" name="children[{{ $i }}][name]" value="{{ $c->name ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="date" name="children[{{ $i }}][dob]" value="{{ $c->dob ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <template id="tpl-children">
                    <tr><td class="px-4 py-2"><input type="text" name="children[INDEX][name]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td><td class="px-4 py-2"><input type="date" name="children[INDEX][dob]" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm dark:[color-scheme:dark]"></td><td><button type="button" onclick="removeRow(this)" class="text-red-600 dark:text-red-400 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></td></tr>
                </template>
                <button type="button" onclick="addRow('children')" class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium rounded-md border border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-800">Add Child</button>
                <div class="mt-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Father's Name</label>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div><input type="text" name="father_surname" value="{{ old('father_surname', $family->father_surname ?? '') }}" placeholder="Surname" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                            <div><input type="text" name="father_first_name" value="{{ old('father_first_name', $family->father_first_name ?? '') }}" placeholder="First Name" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                            <div><input type="text" name="father_middle_name" value="{{ old('father_middle_name', $family->father_middle_name ?? '') }}" placeholder="Middle Name" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                            <div><input type="text" name="father_name_extension" value="{{ old('father_name_extension', $family->father_name_extension ?? '') }}" placeholder="Name Extension (Jr., Sr.)" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Mother's Maiden Name</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div><input type="text" name="mother_maiden_surname" value="{{ old('mother_maiden_surname', $family->mother_maiden_surname ?? '') }}" placeholder="Surname" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                            <div><input type="text" name="mother_maiden_first_name" value="{{ old('mother_maiden_first_name', $family->mother_maiden_first_name ?? '') }}" placeholder="First Name" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                            <div><input type="text" name="mother_maiden_middle_name" value="{{ old('mother_maiden_middle_name', $family->mother_maiden_middle_name ?? '') }}" placeholder="Middle Name" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950"></div>
                        </div>
                    </div>
                    <input type="hidden" name="father_name" value="{{ old('father_name', $family->father_name ?? '') }}">
                    <input type="hidden" name="mother_maiden_name" value="{{ old('mother_maiden_name', $family->mother_maiden_name ?? '') }}">
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Card 4: Educational Background --}}
    <form method="POST" action="{{ route('employee.pds.store') }}">
        @csrf
        <input type="hidden" name="section" value="c1_education">
        <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden" data-card="c1-education">
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">III. Educational Background</h3>
                    <button type="button" onclick="toggleCard('c1-education')" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Toggle card">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-down hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 chevron-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="card-content p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md border border-gray-200 dark:border-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800"><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Level</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">School</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Degree/Course</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Year From</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Year To</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Units</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Year Graduated</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Awards</th></tr></thead>
                        <tbody>
                            @php $levels = ['Elementary','Secondary','Vocational','College','Graduate Studies']; @endphp
                            @foreach($levels as $idx => $lvl)
                            <tr class="bg-white dark:bg-neutral-900"><td class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $lvl }}</td>
                                @php $ed = data_get($education, $idx); @endphp
                                <td class="px-4 py-2"><input type="text" name="education[{{ strtolower(str_replace(' ', '_', $lvl)) }}][school]" value="{{ optional($ed)->school ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td>
                                <td class="px-4 py-2"><input type="text" name="education[{{ strtolower(str_replace(' ', '_', $lvl)) }}][degree_course]" value="{{ optional($ed)->degree_course ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td>
                                <td class="px-4 py-2"><input type="text" name="education[{{ strtolower(str_replace(' ', '_', $lvl)) }}][year_from]" value="{{ optional($ed)->year_from ?? '' }}" class="h-9 w-20 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td>
                                <td class="px-4 py-2"><input type="text" name="education[{{ strtolower(str_replace(' ', '_', $lvl)) }}][year_to]" value="{{ optional($ed)->year_to ?? '' }}" class="h-9 w-20 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td>
                                <td class="px-4 py-2"><input type="text" name="education[{{ strtolower(str_replace(' ', '_', $lvl)) }}][units]" value="{{ optional($ed)->units ?? '' }}" class="h-9 w-16 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td>
                                <td class="px-4 py-2"><input type="text" name="education[{{ strtolower(str_replace(' ', '_', $lvl)) }}][year_graduated]" value="{{ optional($ed)->year_graduated ?? '' }}" class="h-9 w-20 rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td>
                                <td class="px-4 py-2"><input type="text" name="education[{{ strtolower(str_replace(' ', '_', $lvl)) }}][awards]" value="{{ optional($ed)->awards ?? '' }}" class="h-9 w-full rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#013CFC] rounded-md hover:bg-[#0031BC] focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 dark:focus:ring-offset-neutral-950">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
