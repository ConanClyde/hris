{{-- C1: Personal Information, Family Background, Educational Background --}}

<form action="{{ route('employee.pds.store') }}" method="POST" class="space-y-8">
    @csrf
    {{-- I. Personal Information --}}
    <div id="content-personal" class="tab-content">

        {{-- 1. Personal Information --}}
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">I. Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Surname</label>
                    <input type="text" name="surname" value="{{ $pds->surname ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ $pds->first_name ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ $pds->middle_name ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Name Extension (Jr., Sr.)</label>
                    <input type="text" name="name_extension" value="{{ $pds->name_extension ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Date of Birth</label>
                    <input type="date" name="dob"
                        value="{{ isset($pds->dob) ? \Carbon\Carbon::parse($pds->dob)->format('Y-m-d') : '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Place of Birth</label>
                    <input type="text" name="pob" value="{{ $pds->pob ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Sex</label>
                    <select name="sex"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                        <option value="">Select</option>
                        <option value="Male" {{ ($pds->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ ($pds->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Civil Status</label>
                    <select name="civil_status"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                        <option value="">Select</option>
                        <option value="Single" {{ ($pds->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single
                        </option>
                        <option value="Married" {{ ($pds->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married
                        </option>
                        <option value="Widowed" {{ ($pds->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed
                        </option>
                        <option value="Separated" {{ ($pds->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>
                            Separated</option>
                        <option value="Other/s" {{ ($pds->civil_status ?? '') == 'Other/s' ? 'selected' : '' }}>Other/s
                        </option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Height (m)</label>
                    <input type="text" name="height" value="{{ $pds->height ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Weight (kg)</label>
                    <input type="text" name="weight" value="{{ $pds->weight ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Blood Type</label>
                    <select name="blood_type"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                        <option value="">Select</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                            <option value="{{ $bt }}" {{ ($pds->blood_type ?? '') == $bt ? 'selected' : '' }}>{{ $bt }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Citizenship</label>
                    <div class="flex flex-col gap-2">
                        <select name="citizenship_type"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                            <option value="Filipino" {{ ($pds->citizenship_type ?? '') == 'Filipino' ? 'selected' : '' }}>
                                Filipino</option>
                            <option value="Dual Citizenship" {{ ($pds->citizenship_type ?? '') == 'Dual Citizenship' ? 'selected' : '' }}>Dual Citizenship</option>
                        </select>
                        <div class="flex gap-2 text-xs">
                            <label class="inline-flex items-center"><input type="radio" name="citizenship_nature"
                                    value="by birth" {{ ($pds->citizenship_nature ?? '') == 'by birth' ? 'checked' : '' }}> By Birth</label>
                            <label class="inline-flex items-center"><input type="radio" name="citizenship_nature"
                                    value="by naturalization" {{ ($pds->citizenship_nature ?? '') == 'by naturalization' ? 'checked' : '' }}> By Naturalization</label>
                        </div>
                        <input type="text" name="citizenship_country" value="{{ $pds->citizenship_country ?? '' }}"
                            placeholder="Country (if Dual)"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white text-xs">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Telephone No.</label>
                    <input type="text" name="phone" value="{{ $pds->phone ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Mobile No.</label>
                    <input type="text" name="mobile" value="{{ $pds->mobile ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ $pds->email ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white">
                </div>
            </div>
        </div>

        {{-- 2. Official Identification Numbers --}}
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">Official Identification Numbers</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">CS ID No. (to be filled up by
                        CSC)</label>
                    <input type="text" name="cs_id"
                        value="{{ ($pds->cs_id ?? '') == 'N/A' ? '' : ($pds->cs_id ?? '') }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium text-ink">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">Agency Employee No.</label>
                    <input type="text" name="agency_employee_no"
                        value="{{ ($pds->agency_employee_no ?? '') == 'N/A' ? '' : ($pds->agency_employee_no ?? '') }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium text-ink">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">GSIS ID No.</label>
                    <input type="text" name="gsis_id"
                        value="{{ ($pds->gsis_id ?? '') == 'N/A' ? '' : ($pds->gsis_id ?? '') }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium text-ink">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">PAG-IBIG ID No.</label>
                    <input type="text" name="pagibig_no"
                        value="{{ ($pds->pagibig_no ?? '') == 'N/A' ? '' : ($pds->pagibig_no ?? '') }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium text-ink">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">PhilHealth No.</label>
                    <input type="text" name="philhealth_no"
                        value="{{ ($pds->philhealth_no ?? '') == 'N/A' ? '' : ($pds->philhealth_no ?? '') }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium text-ink">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">SSS No.</label>
                    <input type="text" name="sss_no"
                        value="{{ ($pds->sss_no ?? '') == 'N/A' ? '' : ($pds->sss_no ?? '') }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium text-ink">
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted mb-1">TIN No.</label>
                    <input type="text" name="tin_no"
                        value="{{ ($pds->tin_no ?? '') == 'N/A' ? '' : ($pds->tin_no ?? '') }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium text-ink">
                </div>
            </div>
        </div>

        {{-- 3. Residential Address --}}
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">Residential Address</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Row 1: House/Block/Lot, Street, Subdivision/Village --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">House/Block/Lot No.</label>
                    <input type="text" name="res_house_block_lot" value="{{ $pds->res_house_block_lot ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Street</label>
                    <input type="text" name="res_street" value="{{ $pds->res_street ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Subdivision/Village</label>
                    <input type="text" name="res_subdivision_village" value="{{ $pds->res_subdivision_village ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                {{-- Row 2: Barangay, City/Municipality, Province --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Barangay</label>
                    <input type="text" name="res_barangay" value="{{ $pds->res_barangay ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">City/Municipality</label>
                    <input type="text" name="res_city_municipality" value="{{ $pds->res_city_municipality ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Province</label>
                    <input type="text" name="res_province" value="{{ $pds->res_province ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                {{-- Row 3: Zip Code --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Zip Code</label>
                    <input type="text" name="res_zip_code" value="{{ $pds->res_zip_code ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
            </div>
        </div>

        {{-- 4. Permanent Address --}}
        <div class="mt-8" id="perm-address-section">
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4 flex items-center justify-between">
                Permanent Address
                <label class="inline-flex items-center text-sm font-normal text-gray-600 gap-2 cursor-pointer">
                    <input type="checkbox" id="same_as_residential"
                        class="rounded border-[#e3e3e0] text-primary focus:ring-primary">
                    Same as Residential
                </label>
            </h3>
            <p id="perm-address-copied-msg" class="hidden mb-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2 transition-all" role="status">
                Copied from residential address.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 perm-address-fields">
                {{-- Row 1 --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">House/Block/Lot No.</label>
                    <input type="text" name="perm_house_block_lot" value="{{ $pds->perm_house_block_lot ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Street</label>
                    <input type="text" name="perm_street" value="{{ $pds->perm_street ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Subdivision/Village</label>
                    <input type="text" name="perm_subdivision_village"
                        value="{{ $pds->perm_subdivision_village ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                {{-- Row 2 --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Barangay</label>
                    <input type="text" name="perm_barangay" value="{{ $pds->perm_barangay ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">City/Municipality</label>
                    <input type="text" name="perm_city_municipality" value="{{ $pds->perm_city_municipality ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Province</label>
                    <input type="text" name="perm_province" value="{{ $pds->perm_province ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                {{-- Row 3 --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-muted mb-1">Zip Code</label>
                    <input type="text" name="perm_zip_code" value="{{ $pds->perm_zip_code ?? '' }}"
                        class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm focus:outline-none focus:ring-1 focus:ring-primary">
                </div>
            </div>
        </div>
    </div>

    {{-- II. Family Background --}}
    <div id="content-family" class="tab-content hidden">
        {{-- 5. Spouse Information --}}
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">II. Family Background</h3>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">Spouse's Information</h3>
            <div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Surname</label>
                        <input type="text" name="spouse_surname" value="{{ $family->spouse_surname ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">First Name</label>
                        <input type="text" name="spouse_firstname" value="{{ $family->spouse_firstname ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Middle Name</label>
                        <input type="text" name="spouse_middlename" value="{{ $family->spouse_middlename ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Name Extension</label>
                        <input type="text" name="spouse_suffix" value="{{ $family->spouse_suffix ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-muted mb-1">Occupation</label>
                        <input type="text" name="spouse_occupation" value="{{ $family->spouse_occupation ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-muted mb-1">Employer/Business Name</label>
                        <input type="text" name="spouse_employer_business"
                            value="{{ $family->spouse_employer_business ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-muted mb-1">Business Address</label>
                        <input type="text" name="spouse_employer_business_address"
                            value="{{ $family->spouse_employer_business_address ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-muted mb-1">Telephone No.</label>
                        <input type="text" name="spouse_telephone_no" value="{{ $family->spouse_telephone_no ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. Children --}}
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">Names of Children</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border-none">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">Name of
                                Child (Write full name)</th>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">Date of
                                Birth</th>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody id="children-tbody" class="bg-white divide-y divide-gray-200">
                        @php $childList = $children ?? collect([]); @endphp
                        @forelse($childList as $index => $child)
                            <tr>
                                <td class="px-2 py-2">
                                    <input type="text" name="children[{{ $index }}][child_name]"
                                        value="{{ $child->child_name ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                    <input type="hidden" name="children[{{ $index }}][id]" value="{{ $child->id ?? '' }}">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="date" name="children[{{ $index }}][date_of_birth]"
                                        value="{{ isset($child->date_of_birth) ? \Carbon\Carbon::parse($child->date_of_birth)->format('Y-m-d') : '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                </td>
                                <td class="px-2 py-2">
                                    <button type="button" onclick="removeRow(this)"
                                        class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                        title="Remove Entry">
                                        <x-icons.trash class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-2 py-2">
                                    <input type="text" name="children[0][child_name]"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="date" name="children[0][date_of_birth]"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50">
                                </td>
                                <td class="px-2 py-2">
                                    <button type="button" onclick="removeRow(this)"
                                        class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors cursor-pointer"
                                        title="Remove Entry">
                                        <x-icons.trash class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="mt-2 text-sm text-gray-500">No entries yet? Click the button below to add. Leave empty if not applicable.</p>
            <div class="mt-2">
                <button type="button" onclick="addRow('children')"
                    class="px-4 py-2 bg-primary text-white rounded-sm text-sm font-medium hover:bg-[#162d4a] transition-colors cursor-pointer">+
                    Add Child</button>
            </div>
            <template id="children-template">
                <tr>
                    <td class="px-2 py-2">
                        <input type="text" name="children[INDEX][child_name]"
                            class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50">
                        <input type="hidden" name="children[INDEX][id]" value="">
                    </td>
                    <td class="px-2 py-2">
                        <input type="date" name="children[INDEX][date_of_birth]"
                            class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50">
                    </td>
                    <td class="px-2 py-2">
                        <button type="button" onclick="removeRow(this)"
                            class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors cursor-pointer"
                            title="Remove Entry">
                            <x-icons.trash class="w-4 h-4" />
                        </button>
                    </td>
                </tr>
            </template>
        </div>

        {{-- 7. Father's Information --}}
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">Father's Information</h3>
            <div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Surname</label>
                        <input type="text" name="father_lastname" value="{{ $family->father_lastname ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">First Name</label>
                        <input type="text" name="father_firstname" value="{{ $family->father_firstname ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Middle Name</label>
                        <input type="text" name="father_middlename" value="{{ $family->father_middlename ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Name Extension (Jr., Sr.)</label>
                        <input type="text" name="father_suffix" value="{{ $family->father_suffix ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                </div>
            </div>
        </div>

        {{-- 8. Mother's Information --}}
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">Mother's Maiden Name</h3>
            <div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Surname</label>
                        <input type="text" name="mother_lastname" value="{{ $family->mother_lastname ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">First Name</label>
                        <input type="text" name="mother_firstname" value="{{ $family->mother_firstname ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">Middle Name</label>
                        <input type="text" name="mother_middlename" value="{{ $family->mother_middlename ?? '' }}"
                            class="w-full px-3 py-2 border border-[#e3e3e0] rounded-sm bg-gray-50 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white transition-all text-sm font-medium">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- III. Educational Background --}}
    <div id="content-education" class="tab-content hidden">
        <div>
            <h3 class="font-medium text-lg text-ink border-b pb-2 mb-4">III. Educational Background</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border-none">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">
                                Level</th>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Name
                                of School</th>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">
                                Basic Education/Degree/Course</th>
                            <th class="px-3 py-2 text-center text-sm font-medium text-gray-500 uppercase" colspan="2">
                                Period of Attendance</th>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">
                                Highest Level/Units Earned</th>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">Year
                                Graduated</th>
                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-500 uppercase" rowspan="2">
                                Scholarship/ Academic Honors Received</th>
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(['Elementary', 'Secondary', 'Vocational', 'College', 'Graduate Studies'] as $level)
                            @php
                                $edu = $education->where('educational_level', $level)->first();
                            @endphp
                            <tr>
                                <td class="px-3 py-2 text-sm font-medium">{{ $level }}</td>
                                <td class="px-2 py-2">
                                    <input type="text" name="education[{{ $level }}][school]"
                                        value="{{ $edu->school ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                    <input type="hidden" name="education[{{ $level }}][id]" value="{{ $edu->id ?? '' }}">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="education[{{ $level }}][degree]"
                                        value="{{ $edu->degree ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="education[{{ $level }}][year_from]"
                                        value="{{ $edu->year_from ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="education[{{ $level }}][year_to]"
                                        value="{{ $edu->year_to ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="education[{{ $level }}][units]" value="{{ $edu->units ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="education[{{ $level }}][year_graduated]"
                                        value="{{ $edu->year_graduated ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="education[{{ $level }}][awards]"
                                        value="{{ $edu->awards ?? '' }}"
                                        class="w-full px-2 py-1.5 border border-[#e3e3e0] rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white bg-gray-50 transition-all">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
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

