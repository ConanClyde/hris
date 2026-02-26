@extends('layouts.auth')



@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left: Branding --}}
    <aside class="relative flex-1 bg-gradient-to-br from-[#013CFC] via-[#0031BC] to-[#60C8FC] border-r border-gray-200 p-6 lg:p-10 flex flex-col justify-between">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-white/90 hover:text-white transition-colors w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to login
        </a>

        <div class="flex-1 flex flex-col justify-center mt-8 lg:mt-0">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-md bg-white/20 flex items-center justify-center">
                    <span class="text-white font-semibold text-lg">{{ substr(config('app.name', 'HRIS'), 0, 1) }}</span>
                </div>
                <span class="text-xl font-semibold text-white">{{ config('app.name', 'HRIS') }}</span>
            </div>
            <p class="text-white/80 text-sm leading-relaxed max-w-sm">
                {{ config('app.name', 'HRIS') }} is your complete people platform designed to simplify HR and enhance productivity.
            </p>
        </div>

        {{-- Progress indicator --}}
        <div class="flex gap-2 mt-8 lg:mt-0">
            @for ($i = 1; $i <= 4; $i++)
                <div class="progress-step flex-1 h-1 rounded-sm bg-white/30 transition-colors" data-step="{{ $i }}"></div>
            @endfor
        </div>

        <div class="flex gap-6 text-sm text-white/80 mt-4">
            <a href="#" class="hover:text-white transition-colors">About</a>
            <a href="#" class="hover:text-white transition-colors">FAQ</a>
            <a href="#" class="hover:text-white transition-colors">Support</a>
        </div>
    </aside>

    {{-- Right: Form --}}
    <main class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-[#FDFDFD]">
        <div class="w-full max-w-md">
            <form class="space-y-6" id="register-form" method="POST" action="{{ route('register.store') }}">
                @csrf
                {{-- Progress 1: Role --}}
                <div class="register-step" data-step="1">
                    <h1 class="text-xl font-semibold text-gray-900 mb-1">Choose your role</h1>
                    <p class="text-sm text-gray-600 mb-6">Select your user role for this account.</p>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-md cursor-pointer hover:border-[#013CFC] hover:bg-[#013CFC]/5 transition-colors has-[:checked]:border-[#013CFC] has-[:checked]:bg-[#013CFC]/10">
                            <input type="radio" name="role" value="Employee" class="w-4 h-4 text-[#013CFC] focus:ring-[#013CFC]" required>
                            <span class="text-sm font-medium text-gray-900">Employee</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-md cursor-pointer hover:border-[#013CFC] hover:bg-[#013CFC]/5 transition-colors has-[:checked]:border-[#013CFC] has-[:checked]:bg-[#013CFC]/10">
                            <input type="radio" name="role" value="HR" class="w-4 h-4 text-[#013CFC] focus:ring-[#013CFC]">
                            <span class="text-sm font-medium text-gray-900">HR</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-md cursor-pointer hover:border-[#013CFC] hover:bg-[#013CFC]/5 transition-colors has-[:checked]:border-[#013CFC] has-[:checked]:bg-[#013CFC]/10">
                            <input type="radio" name="role" value="Admin" class="w-4 h-4 text-[#013CFC] focus:ring-[#013CFC]">
                            <span class="text-sm font-medium text-gray-900">Admin</span>
                        </label>
                    </div>
                </div>

                {{-- Progress 2: Personal --}}
                <div class="register-step hidden" data-step="2">
                    <h1 class="text-xl font-semibold text-gray-900 mb-1">Personal information</h1>
                    <p class="text-sm text-gray-600 mb-6">Enter your personal details.</p>
                    <div class="space-y-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-900 mb-1.5">First Name</label>
                            <input type="text" id="first_name" name="first_name" placeholder="Enter first name" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-900 mb-1.5">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" placeholder="Enter middle name"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-900 mb-1.5">Surname</label>
                            <input type="text" id="surname" name="surname" placeholder="Enter surname" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label for="name_extension" class="block text-sm font-medium text-gray-900 mb-1.5">Name Extension</label>
                            <input type="text" id="name_extension" name="name_extension" placeholder="e.g. Jr., Sr., III"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1.5">Sex</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sex" value="male" class="w-4 h-4 text-[#013CFC] focus:ring-[#013CFC]" required>
                                    <span class="text-sm text-gray-900">Male</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sex" value="female" class="w-4 h-4 text-[#013CFC] focus:ring-[#013CFC]">
                                    <span class="text-sm text-gray-900">Female</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-900 mb-1.5">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                    </div>
                </div>

                {{-- Progress 3: Employment --}}
                <div class="register-step hidden" data-step="3">
                    <h1 class="text-xl font-semibold text-gray-900 mb-1">Employment information</h1>
                    <p class="text-sm text-gray-600 mb-6">Enter your employment details.</p>
                    <div class="space-y-4">
                        <div>
                            <label for="date_hired" class="block text-sm font-medium text-gray-900 mb-1.5">Date Hired</label>
                            <input type="date" id="date_hired" name="date_hired" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label for="division" class="block text-sm font-medium text-gray-900 mb-1.5">Division</label>
                            <select id="division" name="division" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                                <option value="">Select division</option>
                                <!-- Options populated via JS -->
                            </select>
                        </div>
                        <div id="subdivision-wrap" class="hidden">
                            <label for="subdivision" class="block text-sm font-medium text-gray-900 mb-1.5">Subdivision</label>
                            <select id="subdivision" name="subdivision"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                                <option value="">Select subdivision</option>
                            </select>
                        </div>
                        <div>
                            <label for="unit_section" class="block text-sm font-medium text-gray-900 mb-1.5">Unit / Section</label>
                            <select id="unit_section" name="unit_section" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                                <option value="">Select division first</option>
                            </select>
                        </div>
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-900 mb-1.5">Position</label>
                            <input type="text" id="position" name="position" placeholder="Enter position" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label for="classification" class="block text-sm font-medium text-gray-900 mb-1.5">Classification</label>
                            <select id="classification" name="classification" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                                <option value="">Select classification</option>
                                <option value="Regular">Regular</option>
                                <option value="Detailed">Detailed</option>
                                <option value="COS">COS</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Progress 4: Credentials --}}
                <div class="register-step hidden" data-step="4">
                    <h1 class="text-xl font-semibold text-gray-900 mb-1">Account credentials</h1>
                    <p class="text-sm text-gray-600 mb-6">Create your login credentials.</p>
                    <div class="space-y-4">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-900 mb-1.5">User ID</label>
                            <input type="text" id="user_id" name="user_id" placeholder="Enter user ID" autocomplete="username" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900 mb-1.5">Email</label>
                            <input type="email" id="email" name="email" placeholder="name@company.com" autocomplete="email" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-900 mb-1.5">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" placeholder="••••••••" minlength="8" autocomplete="new-password" required
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent pr-10">
                                <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" aria-label="Toggle password">
                                    <svg class="w-4 h-4 eye-open" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <svg class="w-4 h-4 eye-closed hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-1.5">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" minlength="8" autocomplete="new-password" required
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#013CFC] focus:ring-offset-2 focus:border-transparent pr-10">
                                <button type="button" id="toggle-password-confirmation" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1" aria-label="Toggle password">
                                    <svg class="w-4 h-4 eye-open-confirm" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <svg class="w-4 h-4 eye-closed-confirm hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Navigation buttons --}}
            <div class="flex gap-3 mt-6">
                <button type="button" id="btn-prev" class="hidden py-3 px-6 border border-gray-300 dark:border-neutral-700 rounded-md text-sm font-medium text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-900 transition-colors">
                    Back
                </button>
                <button type="button" id="btn-next" class="flex-1 py-3 px-6 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors">
                    Next
                </button>
                <button type="submit" form="register-form" id="btn-submit" class="hidden flex-1 py-3 px-6 bg-[#013CFC] text-white text-sm font-medium rounded-md hover:bg-[#0031BC] transition-colors">
                    Register
                </button>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-300 mt-6 text-center">
                Already have an account? <a href="{{ route('login') }}" class="text-[#013CFC] font-medium hover:text-[#0031BC]">Sign in</a>
            </p>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-8 text-center">
                {{ config('app.name', 'HRIS') }} — All rights reserved.
            </p>
        </div>
    </main>
</div>

<script>

let currentStep = 1;
const totalSteps = 4;

function showStep(step) {
    currentStep = step;
    document.querySelectorAll('.register-step').forEach(el => el.classList.add('hidden'));
    document.querySelector(`.register-step[data-step="${step}"]`)?.classList.remove('hidden');

    document.querySelectorAll('.progress-step').forEach((el, i) => {
        el.classList.toggle('bg-white', i + 1 <= step);
        el.classList.toggle('bg-white/30', i + 1 > step);
    });

    document.getElementById('btn-prev').classList.toggle('hidden', step === 1);
    document.getElementById('btn-next').classList.toggle('hidden', step === totalSteps);
    document.getElementById('btn-submit').classList.toggle('hidden', step !== totalSteps);
}

function validateStep(step) {
    const stepEl = document.querySelector(`.register-step[data-step="${step}"]`);
    if (!stepEl) return true;

    if (step === 1) {
        return !!stepEl.querySelector('input[name="role"]:checked');
    }
    if (step === 2) {
        const fn = stepEl.querySelector('#first_name')?.value?.trim();
        const sn = stepEl.querySelector('#surname')?.value?.trim();
        const sex = stepEl.querySelector('input[name="sex"]:checked');
        const dob = stepEl.querySelector('#date_of_birth')?.value;
        return !!(fn && sn && sex && dob);
    }

    const inputs = stepEl.querySelectorAll('input[required]:not([type="radio"]), select[required]');
    let valid = true;
    inputs?.forEach(inp => {
        const parentHidden = inp.closest('.hidden') || (inp.id === 'subdivision' && subdivisionWrap?.classList.contains('hidden'));
        if (!parentHidden && !inp.value?.trim()) valid = false;
    });
    return valid;
}

document.getElementById('btn-next')?.addEventListener('click', function() {
    if (!validateStep(currentStep)) {
        document.querySelector(`.register-step[data-step="${currentStep}"]`)?.querySelector('input[required], select[required]')?.focus();
        return;
    }
    showStep(currentStep + 1);
});

document.getElementById('btn-prev')?.addEventListener('click', function() {
    showStep(currentStep - 1);
});

document.getElementById('register-form')?.addEventListener('submit', function(e) {
    if (document.getElementById('password').value !== document.getElementById('password_confirmation').value) {
        e.preventDefault();
        alert('Passwords do not match.');
        return;
    }
});

// Division / Subdivision / Section logic
const divisionSelect = document.getElementById('division');
const subdivisionWrap = document.getElementById('subdivision-wrap');
const subdivisionSelect = document.getElementById('subdivision');
const unitSectionSelect = document.getElementById('unit_section');

let structureData = [];

// Fetch structure from API
fetch('/api/v1/organizational-structure')
    .then(response => response.json())
    .then(data => {
        structureData = data;
        populateDivisions();
    })
    .catch(error => console.error('Error fetching structure:', error));

function populateDivisions() {
    if (!divisionSelect) return;
    divisionSelect.innerHTML = '<option value="">Select division</option>';
    structureData.forEach(div => {
        divisionSelect.innerHTML += `<option value="${div.id}">${div.name}</option>`;
    });
}

divisionSelect?.addEventListener('change', function() {
    const divId = parseInt(this.value);
    subdivisionSelect.innerHTML = '<option value="">Select subdivision</option>';
    unitSectionSelect.innerHTML = '<option value="">Select unit/section</option>';
    subdivisionWrap.classList.add('hidden');
    subdivisionSelect.required = false;
    unitSectionSelect.required = true;

    if (!divId) return;

    const divData = structureData.find(d => d.id === divId);
    if (!divData) return;

    if (divData.subdivisions && divData.subdivisions.length > 0) {
        subdivisionWrap.classList.remove('hidden');
        subdivisionSelect.required = true;
        unitSectionSelect.required = true;
        unitSectionSelect.innerHTML = '<option value="">Select subdivision first</option>';
        divData.subdivisions.forEach(sub => {
            subdivisionSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
        });
    } else if (divData.sections && divData.sections.length > 0) {
        divData.sections.forEach(sec => {
            unitSectionSelect.innerHTML += `<option value="${sec.id}">${sec.name}</option>`;
        });
    }
});

subdivisionSelect?.addEventListener('change', function() {
    const divId = parseInt(divisionSelect?.value);
    const subId = parseInt(this.value);
    unitSectionSelect.innerHTML = '<option value="">Select unit/section</option>';
    unitSectionSelect.required = true;

    if (!divId || !subId) return;

    const divData = structureData.find(d => d.id === divId);
    if (!divData) return;

    const subData = divData.subdivisions.find(s => s.id === subId);
    if (!subData) return;

    if (subData.sections) {
        subData.sections.forEach(sec => {
            unitSectionSelect.innerHTML += `<option value="${sec.id}">${sec.name}</option>`;
        });
    }
});

// Password toggles
document.getElementById('toggle-password')?.addEventListener('click', function() {
    const input = document.getElementById('password');
    const open = document.querySelector('.eye-open');
    const closed = document.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        open?.classList.add('hidden');
        closed?.classList.remove('hidden');
    } else {
        input.type = 'password';
        open?.classList.remove('hidden');
        closed?.classList.add('hidden');
    }
});
document.getElementById('toggle-password-confirmation')?.addEventListener('click', function() {
    const input = document.getElementById('password_confirmation');
    const open = document.querySelector('.eye-open-confirm');
    const closed = document.querySelector('.eye-closed-confirm');
    if (input.type === 'password') {
        input.type = 'text';
        open?.classList.add('hidden');
        closed?.classList.remove('hidden');
    } else {
        input.type = 'password';
        open?.classList.remove('hidden');
        closed?.classList.add('hidden');
    }
});

showStep(1);
</script>
@endsection
