<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

type PdsPersonal = {
    surname?: string | null;
    first_name?: string | null;
    middle_name?: string | null;
    name_extension?: string | null;
    dob?: string | null;
    place_of_birth?: string | null;
    sex?: string | null;
    civil_status?: string | null;
    height?: string | null;
    weight?: string | null;
    blood_type?: string | null;
    citizenship_type?: string | null;
    citizenship_country?: string | null;
    phone?: string | null;
    mobile?: string | null;
    email?: string | null;
    cs_id?: string | null;
    agency_employee_no?: string | null;
    gsis?: string | null;
    pag_ibig?: string | null;
    philhealth?: string | null;
    sss?: string | null;
    tin?: string | null;
    residential_address?: any;
    permanent_address?: any;
};

type PdsFamily = {
    spouse_surname?: string;
    spouse_first_name?: string;
    spouse_middle_name?: string;
    spouse_name_extension?: string;
    spouse_occupation?: string;
    spouse_employer?: string;
    spouse_business_address?: string;
    spouse_telephone?: string;
    father_surname?: string;
    father_first_name?: string;
    father_middle_name?: string;
    father_name_extension?: string;
    mother_maiden_surname?: string;
    mother_maiden_first_name?: string;
    mother_maiden_middle_name?: string;
};

type PdsChild = { name?: string; dob?: string };
type PdsEducation = { level?: string; school_name?: string; degree_course?: string; year_graduated?: string };
type PdsCscEligibility = { license_name?: string; rating?: string; date_of_examination?: string; place_of_examination?: string };
type PdsWorkExperience = { employed_from?: string; employed_to?: string; position_title?: string; department?: string; is_government?: boolean };
type PdsVoluntaryWork = { org_name_address?: string; volunteer_from?: string; volunteer_to?: string; number_of_hours?: string; nature_of_work?: string };
type PdsTraining = { title?: string; training_from?: string; training_to?: string; number_of_hours?: string };
type PdsOtherInfo = { skills?: string; recognition?: string; membership?: string };
type PdsReference = { reference_name?: string; reference_address?: string; reference_telno?: string };

type PdsRecord = {
    id?: number;
    status?: string;
    personal?: PdsPersonal | null;
    family?: PdsFamily | null;
    children?: PdsChild[];
    education?: PdsEducation[];
    csc_eligibility?: PdsCscEligibility[];
    work_experience?: PdsWorkExperience[];
    voluntary_work?: PdsVoluntaryWork[];
    training?: PdsTraining[];
    other_info?: PdsOtherInfo[];
    references?: PdsReference[];
};

defineProps<{ pds?: PdsRecord | null }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'PDS', href: employee.pds.index.url() },
    { title: 'Preview' },
];

function formatDate(value: string | null | undefined) {
    if (!value) return '—';
    try { return new Date(value).toLocaleDateString(); } catch { return value; }
}

function fullName(p?: PdsPersonal | null) {
    if (!p) return '—';
    const parts = [p.first_name, p.middle_name, p.surname].filter(Boolean);
    const name = parts.join(' ');
    return p.name_extension ? `${name} ${p.name_extension}` : name || '—';
}

function formatAddress(addr: any) {
    if (!addr) return '—';
    const parts = [addr.house_no, addr.street, addr.barangay, addr.city, addr.province, addr.zip_code].filter(Boolean);
    return parts.join(', ') || '—';
}
</script>

<template>
    <Head title="PDS Preview" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">PDS Preview</h1>
                <Button as-child variant="outline" size="sm">
                    <Link :href="employee.pds.index.url()">Edit PDS</Link>
                </Button>
            </div>

            <!-- Personal Information -->
            <div v-if="pds?.personal" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Personal Information</h2>
                <dl class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div><dt class="text-xs text-muted-foreground">Full name</dt><dd class="mt-0.5 font-medium">{{ fullName(pds.personal) }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Date of birth</dt><dd class="mt-0.5">{{ formatDate(pds.personal.dob) }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Place of birth</dt><dd class="mt-0.5">{{ pds.personal.place_of_birth || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Sex</dt><dd class="mt-0.5">{{ pds.personal.sex || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Civil status</dt><dd class="mt-0.5">{{ pds.personal.civil_status || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Height</dt><dd class="mt-0.5">{{ pds.personal.height || '—' }} m</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Weight</dt><dd class="mt-0.5">{{ pds.personal.weight || '—' }} kg</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Blood type</dt><dd class="mt-0.5">{{ pds.personal.blood_type || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Citizenship</dt><dd class="mt-0.5">{{ pds.personal.citizenship_type || '—' }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs text-muted-foreground">Residential Address</dt><dd class="mt-0.5">{{ formatAddress(pds.personal.residential_address) }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs text-muted-foreground">Permanent Address</dt><dd class="mt-0.5">{{ formatAddress(pds.personal.permanent_address) }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Phone</dt><dd class="mt-0.5">{{ pds.personal.phone || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Mobile</dt><dd class="mt-0.5">{{ pds.personal.mobile || '—' }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs text-muted-foreground">Email</dt><dd class="mt-0.5">{{ pds.personal.email || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">GSIS</dt><dd class="mt-0.5">{{ pds.personal.gsis || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">Pag-IBIG</dt><dd class="mt-0.5">{{ pds.personal.pag_ibig || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">PhilHealth</dt><dd class="mt-0.5">{{ pds.personal.philhealth || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">SSS</dt><dd class="mt-0.5">{{ pds.personal.sss || '—' }}</dd></div>
                    <div><dt class="text-xs text-muted-foreground">TIN</dt><dd class="mt-0.5">{{ pds.personal.tin || '—' }}</dd></div>
                </dl>
            </div>

            <!-- Family Background -->
            <div v-if="pds?.family" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Family Background</h2>
                <div class="space-y-4">
                    <div v-if="pds.family.spouse_surname || pds.family.spouse_first_name">
                        <h3 class="text-xs font-medium text-muted-foreground mb-2">Spouse</h3>
                        <p class="text-sm">{{ [pds.family.spouse_first_name, pds.family.spouse_middle_name, pds.family.spouse_surname].filter(Boolean).join(' ') }} {{ pds.family.spouse_name_extension }}</p>
                        <p v-if="pds.family.spouse_occupation" class="text-xs text-muted-foreground">Occupation: {{ pds.family.spouse_occupation }}</p>
                    </div>
                    <div v-if="pds.family.father_surname || pds.family.father_first_name">
                        <h3 class="text-xs font-medium text-muted-foreground mb-2">Father</h3>
                        <p class="text-sm">{{ [pds.family.father_first_name, pds.family.father_middle_name, pds.family.father_surname].filter(Boolean).join(' ') }} {{ pds.family.father_name_extension }}</p>
                    </div>
                    <div v-if="pds.family.mother_maiden_surname || pds.family.mother_maiden_first_name">
                        <h3 class="text-xs font-medium text-muted-foreground mb-2">Mother (Maiden)</h3>
                        <p class="text-sm">{{ [pds.family.mother_maiden_first_name, pds.family.mother_maiden_middle_name, pds.family.mother_maiden_surname].filter(Boolean).join(' ') }}</p>
                    </div>
                </div>
            </div>

            <!-- Children -->
            <div v-if="pds?.children && pds.children.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Children</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">Name</th><th class="py-2 text-left text-xs">Date of Birth</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(c, i) in pds.children" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ c.name || '—' }}</td>
                            <td class="py-2">{{ formatDate(c.dob) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Education -->
            <div v-if="pds?.education && pds.education.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Education</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">Level</th><th class="py-2 text-left text-xs">School</th><th class="py-2 text-left text-xs">Degree</th><th class="py-2 text-left text-xs">Year</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(e, i) in pds.education" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ e.level || '—' }}</td>
                            <td class="py-2">{{ e.school_name || '—' }}</td>
                            <td class="py-2">{{ e.degree_course || '—' }}</td>
                            <td class="py-2">{{ e.year_graduated || '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- CSC Eligibility -->
            <div v-if="pds?.csc_eligibility && pds.csc_eligibility.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Civil Service Eligibility</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">License/Career</th><th class="py-2 text-left text-xs">Rating</th><th class="py-2 text-left text-xs">Exam Date</th><th class="py-2 text-left text-xs">Place</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, i) in pds.csc_eligibility" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ r.license_name || '—' }}</td>
                            <td class="py-2">{{ r.rating || '—' }}</td>
                            <td class="py-2">{{ formatDate(r.date_of_examination) }}</td>
                            <td class="py-2">{{ r.place_of_examination || '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Work Experience -->
            <div v-if="pds?.work_experience && pds.work_experience.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Work Experience</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">Period</th><th class="py-2 text-left text-xs">Position</th><th class="py-2 text-left text-xs">Department</th><th class="py-2 text-left text-xs">Gov't</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(w, i) in pds.work_experience" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ formatDate(w.employed_from) }} - {{ formatDate(w.employed_to) }}</td>
                            <td class="py-2">{{ w.position_title || '—' }}</td>
                            <td class="py-2">{{ w.department || '—' }}</td>
                            <td class="py-2">{{ w.is_government ? 'Yes' : 'No' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Voluntary Work -->
            <div v-if="pds?.voluntary_work && pds.voluntary_work.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Voluntary Work</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">Organization</th><th class="py-2 text-left text-xs">Period</th><th class="py-2 text-left text-xs">Hours</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, i) in pds.voluntary_work" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ v.org_name_address || '—' }}</td>
                            <td class="py-2">{{ formatDate(v.volunteer_from) }} - {{ formatDate(v.volunteer_to) }}</td>
                            <td class="py-2">{{ v.number_of_hours || '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Training -->
            <div v-if="pds?.training && pds.training.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Learning & Development</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">Title</th><th class="py-2 text-left text-xs">Period</th><th class="py-2 text-left text-xs">Hours</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(t, i) in pds.training" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ t.title || '—' }}</td>
                            <td class="py-2">{{ formatDate(t.training_from) }} - {{ formatDate(t.training_to) }}</td>
                            <td class="py-2">{{ t.number_of_hours || '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Other Information -->
            <div v-if="pds?.other_info && pds.other_info.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">Other Information</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">Skills</th><th class="py-2 text-left text-xs">Recognition</th><th class="py-2 text-left text-xs">Membership</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(o, i) in pds.other_info" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ o.skills || '—' }}</td>
                            <td class="py-2">{{ o.recognition || '—' }}</td>
                            <td class="py-2">{{ o.membership || '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- References -->
            <div v-if="pds?.references && pds.references.length > 0" class="rounded-lg border border-gray-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800/50">
                <h2 class="mb-4 text-sm font-medium uppercase tracking-wider text-muted-foreground">References</h2>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                        <tr><th class="py-2 text-left text-xs">Name</th><th class="py-2 text-left text-xs">Address</th><th class="py-2 text-left text-xs">Telephone</th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, i) in pds.references.slice(0, 3)" :key="i" class="border-b border-gray-100 dark:border-neutral-800">
                            <td class="py-2">{{ r.reference_name || '—' }}</td>
                            <td class="py-2">{{ r.reference_address || '—' }}</td>
                            <td class="py-2">{{ r.reference_telno || '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="!pds?.personal" class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center text-sm text-muted-foreground dark:border-neutral-700 dark:bg-neutral-800/50">
                No PDS data yet. Fill out your PDS from the
                <Link :href="employee.pds.index.url()" class="text-primary underline">PDS form</Link>.
            </div>
        </div>
    </AppLayout>
</template>
