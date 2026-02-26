<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { FileText, Save, Send, CheckCircle2, Clock, XCircle, ChevronDown, ChevronUp, Plus, Trash2 } from 'lucide-vue-next';
import { ref, reactive, computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

type ResidentialAddress = {
    house_no?: string;
    street?: string;
    subdivision?: string;
    barangay?: string;
    city?: string;
    province?: string;
    zip_code?: string;
};

type PdsPersonal = {
    surname?: string;
    first_name?: string;
    middle_name?: string;
    name_extension?: string;
    dob?: string;
    place_of_birth?: string;
    sex?: string;
    civil_status?: string;
    height?: string;
    weight?: string;
    blood_type?: string;
    citizenship_type?: string;
    citizenship_country?: string;
    phone?: string;
    mobile?: string;
    email?: string;
    cs_id?: string;
    agency_employee_no?: string;
    gsis?: string;
    pag_ibig?: string;
    philhealth?: string;
    sss?: string;
    tin?: string;
    residential_address?: ResidentialAddress | null;
    permanent_address?: ResidentialAddress | null;
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

type PdsChild = {
    name?: string;
    dob?: string;
};

type PdsEducation = {
    level?: string;
    school_name?: string;
    degree_course?: string;
    period_from?: string;
    period_to?: string;
    highest_level?: string;
    year_graduated?: string;
    scholarship_honors?: string;
    awards?: string;
};

type PdsCscEligibility = {
    license_name?: string;
    rating?: string;
    date_of_examination?: string;
    place_of_examination?: string;
    license_no?: string;
    date_of_validity?: string;
};

type PdsWorkExperience = {
    employed_from?: string;
    employed_to?: string;
    position_title?: string;
    department?: string;
    salary?: string;
    salary_grade?: string;
    appointment_status?: string;
    is_government?: boolean;
};

type PdsVoluntaryWork = {
    org_name_address?: string;
    volunteer_from?: string;
    volunteer_to?: string;
    number_of_hours?: string;
    nature_of_work?: string;
};

type PdsTraining = {
    title?: string;
    training_from?: string;
    training_to?: string;
    number_of_hours?: string;
    training_type?: string;
    sponsor?: string;
};

type PdsOtherInfo = {
    skills?: string;
    recognition?: string;
    membership?: string;
};

type PdsReference = {
    reference_name?: string;
    reference_address?: string;
    reference_telno?: string;
};

type PdsRecord = {
    id?: number;
    employee_id?: number | string;
    status?: string;
    submitted_at?: string | null;
    reviewed_at?: string | null;
    updated_at?: string | null;
    created_at?: string | null;
    personal?: PdsPersonal | null;
    family?: PdsFamily | null;
    children?: PdsChild[] | null;
    education?: PdsEducation[] | null;
    csc_eligibility?: PdsCscEligibility[] | null;
    work_experience?: PdsWorkExperience[] | null;
    voluntary_work?: PdsVoluntaryWork[] | null;
    training?: PdsTraining[] | null;
    other_info?: PdsOtherInfo[] | null;
    references?: PdsReference[] | null;
};

const props = defineProps<{
    pds: PdsRecord | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Personal Data Sheet' }];

const p = props.pds?.personal ?? {};
const f = (props.pds as any)?.family ?? {};
const addr = (a?: ResidentialAddress | null): ResidentialAddress => ({
    house_no: a?.house_no ?? '',
    street: a?.street ?? '',
    subdivision: a?.subdivision ?? '',
    barangay: a?.barangay ?? '',
    city: a?.city ?? '',
    province: a?.province ?? '',
    zip_code: a?.zip_code ?? '',
});

const form = reactive<PdsPersonal>({
    surname: p.surname ?? '',
    first_name: p.first_name ?? '',
    middle_name: p.middle_name ?? '',
    name_extension: p.name_extension ?? '',
    dob: p.dob ? String(p.dob).slice(0, 10) : '',
    place_of_birth: p.place_of_birth ?? '',
    sex: p.sex ?? '',
    civil_status: p.civil_status ?? '',
    height: p.height ?? '',
    weight: p.weight ?? '',
    blood_type: p.blood_type ?? '',
    citizenship_type: p.citizenship_type ?? 'Filipino',
    citizenship_country: p.citizenship_country ?? '',
    phone: p.phone ?? '',
    mobile: p.mobile ?? '',
    email: p.email ?? '',
    cs_id: p.cs_id ?? '',
    agency_employee_no: p.agency_employee_no ?? '',
    gsis: p.gsis ?? '',
    pag_ibig: p.pag_ibig ?? '',
    philhealth: p.philhealth ?? '',
    sss: p.sss ?? '',
    tin: p.tin ?? '',
    residential_address: addr(p.residential_address),
    permanent_address: addr(p.permanent_address),
});

const family = reactive<PdsFamily>({
    spouse_surname: f.spouse_surname ?? '',
    spouse_first_name: f.spouse_first_name ?? '',
    spouse_middle_name: f.spouse_middle_name ?? '',
    spouse_name_extension: f.spouse_name_extension ?? '',
    spouse_occupation: f.spouse_occupation ?? '',
    spouse_employer: f.spouse_employer ?? '',
    spouse_business_address: f.spouse_business_address ?? '',
    spouse_telephone: f.spouse_telephone ?? '',
    father_surname: f.father_surname ?? '',
    father_first_name: f.father_first_name ?? '',
    father_middle_name: f.father_middle_name ?? '',
    father_name_extension: f.father_name_extension ?? '',
    mother_maiden_surname: f.mother_maiden_surname ?? '',
    mother_maiden_first_name: f.mother_maiden_first_name ?? '',
    mother_maiden_middle_name: f.mother_maiden_middle_name ?? '',
});

const children = ref<PdsChild[]>(((props.pds as any)?.children ?? []).map((c: any) => ({
    name: c?.name ?? '',
    dob: c?.dob ? String(c.dob).slice(0, 10) : '',
})));

const education = ref<PdsEducation[]>(((props.pds as any)?.education ?? []).map((e: any) => ({
    level: e?.level ?? '',
    school_name: e?.school_name ?? '',
    degree_course: e?.degree_course ?? '',
    period_from: e?.period_from ?? '',
    period_to: e?.period_to ?? '',
    highest_level: e?.highest_level ?? '',
    year_graduated: e?.year_graduated ?? '',
    scholarship_honors: e?.scholarship_honors ?? '',
    awards: e?.awards ?? '',
})));

const cscEligibility = ref<PdsCscEligibility[]>(((props.pds as any)?.csc_eligibility ?? (props.pds as any)?.cscEligibility ?? []).map((r: any) => ({
    license_name: r?.license_name ?? '',
    rating: r?.rating ?? '',
    date_of_examination: r?.date_of_examination ? String(r.date_of_examination).slice(0, 10) : '',
    place_of_examination: r?.place_of_examination ?? '',
    license_no: r?.license_no ?? '',
    date_of_validity: r?.date_of_validity ? String(r.date_of_validity).slice(0, 10) : '',
})));

const workExperience = ref<PdsWorkExperience[]>(((props.pds as any)?.work_experience ?? (props.pds as any)?.workExperience ?? []).map((w: any) => ({
    employed_from: w?.employed_from ? String(w.employed_from).slice(0, 10) : '',
    employed_to: w?.employed_to ? String(w.employed_to).slice(0, 10) : '',
    position_title: w?.position_title ?? '',
    department: w?.department ?? '',
    salary: w?.salary ?? '',
    salary_grade: w?.salary_grade ?? '',
    appointment_status: w?.appointment_status ?? '',
    is_government: !!w?.is_government,
})));

const voluntaryWork = ref<PdsVoluntaryWork[]>(((props.pds as any)?.voluntary_work ?? (props.pds as any)?.voluntaryWork ?? []).map((v: any) => ({
    org_name_address: v?.org_name_address ?? '',
    volunteer_from: v?.volunteer_from ? String(v.volunteer_from).slice(0, 10) : '',
    volunteer_to: v?.volunteer_to ? String(v.volunteer_to).slice(0, 10) : '',
    number_of_hours: v?.number_of_hours ?? '',
    nature_of_work: v?.nature_of_work ?? '',
})));

const training = ref<PdsTraining[]>(((props.pds as any)?.training ?? []).map((t: any) => ({
    title: t?.title ?? '',
    training_from: t?.training_from ? String(t.training_from).slice(0, 10) : '',
    training_to: t?.training_to ? String(t.training_to).slice(0, 10) : '',
    number_of_hours: t?.number_of_hours ?? '',
    training_type: t?.training_type ?? '',
    sponsor: t?.sponsor ?? '',
})));

const otherInfo = ref<PdsOtherInfo[]>(((props.pds as any)?.other_info ?? (props.pds as any)?.otherInfo ?? []).map((o: any) => ({
    skills: o?.skills ?? '',
    recognition: o?.recognition ?? '',
    membership: o?.membership ?? '',
})));

const references = ref<PdsReference[]>(((props.pds as any)?.references ?? []).map((r: any) => ({
    reference_name: r?.reference_name ?? '',
    reference_address: r?.reference_address ?? '',
    reference_telno: r?.reference_telno ?? '',
})));

if (references.value.length === 0) {
    references.value = [
        { reference_name: '', reference_address: '', reference_telno: '' },
        { reference_name: '', reference_address: '', reference_telno: '' },
        { reference_name: '', reference_address: '', reference_telno: '' },
    ];
}

const activeTab = ref<'c1' | 'c2' | 'c3' | 'c4'>('c1');

const openSections = reactive({ personal: true, contact: true, govt: true });
function toggle(section: keyof typeof openSections) {
    openSections[section] = !openSections[section];
}

const status = computed(() => props.pds?.status ?? null);
const canEdit = computed(() => !status.value || status.value === 'draft' || status.value === 'rejected');

function statusVariant(s: string | null): 'default' | 'secondary' | 'destructive' | 'outline' {
    if (s === 'approved') return 'default';
    if (s === 'rejected') return 'destructive';
    if (s === 'submitted') return 'secondary';
    return 'outline';
}

function formatDate(v: string | null | undefined) {
    if (!v) return '—';
    try { return new Date(v).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }); } catch { return v; }
}

const sameAsResidential = ref(false);
function applySameAddress() {
    if (sameAsResidential.value) {
        Object.assign(form.permanent_address!, form.residential_address);
    }
}

const saving = ref(false);
const submitDialogOpen = ref(false);

function saveDraft() {
    saving.value = true;
    router.post(
        employee.pds.store.url(),
        {
            data: {
                status: 'draft',
                personal: form,
                family,
                children: children.value,
                education: education.value,
                csc_eligibility: cscEligibility.value,
                work_experience: workExperience.value,
                voluntary_work: voluntaryWork.value,
                training: training.value,
                other_info: otherInfo.value,
                references: references.value,
            },
        },
        { onFinish: () => { saving.value = false; } }
    );
}

function submitPds() {
    submitDialogOpen.value = false;
    router.post(
        employee.pds.store.url(),
        {
            data: {
                status: 'submitted',
                personal: form,
                family,
                children: children.value,
                education: education.value,
                csc_eligibility: cscEligibility.value,
                work_experience: workExperience.value,
                voluntary_work: voluntaryWork.value,
                training: training.value,
                other_info: otherInfo.value,
                references: references.value,
            },
        },
        {}
    );
}
</script>

<template>
    <Head title="Personal Data Sheet" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-5 p-4">

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">Personal Data Sheet</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Fill in your personal information and submit for HR review.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <Badge v-if="status" :variant="statusVariant(status)" class="capitalize gap-1.5 px-3 py-1 text-sm">
                        <CheckCircle2 v-if="status === 'approved'" class="size-3.5" />
                        <Clock v-else-if="status === 'submitted'" class="size-3.5" />
                        <XCircle v-else-if="status === 'rejected'" class="size-3.5" />
                        {{ status }}
                    </Badge>
                    <Link
                        v-if="pds?.id"
                        :href="employee.pds.preview.url()"
                        class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        <FileText class="size-4" /> Preview
                    </Link>
                </div>
            </div>

            <!-- Tabs -->
            <div class="-mx-4 px-4">
                <div class="inline-flex overflow-hidden rounded-md border border-gray-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900" role="tablist">
                    <button type="button" class="px-5 py-2.5 text-sm font-medium border-r border-gray-200 dark:border-neutral-800" :class="activeTab === 'c1' ? 'bg-gray-100 text-gray-900 dark:bg-neutral-800 dark:text-gray-100' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-neutral-800'" @click="activeTab = 'c1'">C1: Personal & Family</button>
                    <button type="button" class="px-5 py-2.5 text-sm font-medium border-r border-gray-200 dark:border-neutral-800" :class="activeTab === 'c2' ? 'bg-gray-100 text-gray-900 dark:bg-neutral-800 dark:text-gray-100' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-neutral-800'" @click="activeTab = 'c2'">C2: Eligibility & Work</button>
                    <button type="button" class="px-5 py-2.5 text-sm font-medium border-r border-gray-200 dark:border-neutral-800" :class="activeTab === 'c3' ? 'bg-gray-100 text-gray-900 dark:bg-neutral-800 dark:text-gray-100' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-neutral-800'" @click="activeTab = 'c3'">C3: Voluntary & Training</button>
                    <button type="button" class="px-5 py-2.5 text-sm font-medium" :class="activeTab === 'c4' ? 'bg-gray-100 text-gray-900 dark:bg-neutral-800 dark:text-gray-100' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-neutral-800'" @click="activeTab = 'c4'">C4: References</button>
                </div>
            </div>

            <!-- Status banner -->
            <div
                v-if="status === 'submitted'"
                class="flex items-start gap-3 rounded-lg border border-amber-300 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-700/50 dark:bg-amber-950/40 dark:text-amber-300"
            >
                <Clock class="mt-0.5 size-4 shrink-0" />
                <div>
                    <p class="font-medium">Pending HR review</p>
                    <p class="mt-0.5 text-amber-700 dark:text-amber-400">Your PDS was submitted {{ formatDate(pds?.submitted_at ?? null) }}. You cannot edit while under review.</p>
                </div>
            </div>
            <div
                v-else-if="status === 'approved'"
                class="flex items-start gap-3 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-700/50 dark:bg-emerald-950/40 dark:text-emerald-300"
            >
                <CheckCircle2 class="mt-0.5 size-4 shrink-0" />
                <div>
                    <p class="font-medium">Approved by HR</p>
                    <p class="mt-0.5 text-emerald-700 dark:text-emerald-400">Your PDS was approved {{ formatDate(pds?.reviewed_at ?? null) }}. Contact HR to make changes.</p>
                </div>
            </div>
            <div
                v-else-if="status === 'rejected'"
                class="flex items-start gap-3 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-800 dark:border-red-700/50 dark:bg-red-950/40 dark:text-red-300"
            >
                <XCircle class="mt-0.5 size-4 shrink-0" />
                <div>
                    <p class="font-medium">PDS requires correction</p>
                    <p class="mt-0.5 text-red-700 dark:text-red-400">HR returned your PDS. Please update and resubmit.</p>
                </div>
            </div>

            <!-- TAB C1 -->
            <div v-show="activeTab === 'c1'" class="space-y-5">
                <!-- Personal Information -->
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 text-left transition-colors hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800/60 dark:hover:bg-neutral-700/60"
                        @click="toggle('personal')"
                    >
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">I. Personal Information</span>
                        <ChevronUp v-if="openSections.personal" class="size-4 text-muted-foreground" />
                        <ChevronDown v-else class="size-4 text-muted-foreground" />
                    </button>
                    <div v-show="openSections.personal" class="bg-white p-5 dark:bg-neutral-900">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="space-y-1.5">
                                <Label for="surname">Surname</Label>
                                <Input id="surname" v-model="form.surname" :disabled="!canEdit" placeholder="Last name" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="first_name">First name</Label>
                                <Input id="first_name" v-model="form.first_name" :disabled="!canEdit" placeholder="First name" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="middle_name">Middle name</Label>
                                <Input id="middle_name" v-model="form.middle_name" :disabled="!canEdit" placeholder="Middle name" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="name_extension">Name ext.</Label>
                                <Input id="name_extension" v-model="form.name_extension" :disabled="!canEdit" placeholder="Jr., Sr., III" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="dob">Date of birth</Label>
                                <Input id="dob" v-model="form.dob" type="date" :disabled="!canEdit" />
                            </div>
                            <div class="space-y-1.5 sm:col-span-1">
                                <Label for="place_of_birth">Place of birth</Label>
                                <Input id="place_of_birth" v-model="form.place_of_birth" :disabled="!canEdit" placeholder="City / Province" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="sex">Sex</Label>
                                <Select v-model="form.sex" :disabled="!canEdit">
                                    <SelectTrigger id="sex"><SelectValue placeholder="Select" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Male">Male</SelectItem>
                                        <SelectItem value="Female">Female</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="space-y-1.5">
                                <Label for="civil_status">Civil status</Label>
                                <Select v-model="form.civil_status" :disabled="!canEdit">
                                    <SelectTrigger id="civil_status"><SelectValue placeholder="Select" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Single">Single</SelectItem>
                                        <SelectItem value="Married">Married</SelectItem>
                                        <SelectItem value="Widowed">Widowed</SelectItem>
                                        <SelectItem value="Separated">Separated</SelectItem>
                                        <SelectItem value="Annulled">Annulled</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="space-y-1.5">
                                <Label for="height">Height (m)</Label>
                                <Input id="height" v-model="form.height" type="number" step="0.01" min="0" :disabled="!canEdit" placeholder="e.g. 1.65" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="weight">Weight (kg)</Label>
                                <Input id="weight" v-model="form.weight" type="number" step="0.1" min="0" :disabled="!canEdit" placeholder="e.g. 60" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="blood_type">Blood type</Label>
                                <Select v-model="form.blood_type" :disabled="!canEdit">
                                    <SelectTrigger id="blood_type"><SelectValue placeholder="Select" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="t in ['A+','A-','B+','B-','AB+','AB-','O+','O-']" :key="t" :value="t">{{ t }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="space-y-1.5">
                                <Label for="citizenship_type">Citizenship</Label>
                                <Select v-model="form.citizenship_type" :disabled="!canEdit">
                                    <SelectTrigger id="citizenship_type"><SelectValue placeholder="Select" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Filipino">Filipino</SelectItem>
                                        <SelectItem value="Dual">Dual citizenship</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div v-if="form.citizenship_type === 'Dual'" class="space-y-1.5 sm:col-span-2">
                                <Label for="citizenship_country">Other country</Label>
                                <Input id="citizenship_country" v-model="form.citizenship_country" :disabled="!canEdit" placeholder="Country of dual citizenship" />
                            </div>
                        </div>

                        <!-- Residential address -->
                        <div class="mt-5 space-y-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Residential address</p>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="space-y-1.5">
                                    <Label for="res_house_no">House / Block / Lot No.</Label>
                                    <Input id="res_house_no" v-model="form.residential_address!.house_no" :disabled="!canEdit" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="res_street">Street</Label>
                                    <Input id="res_street" v-model="form.residential_address!.street" :disabled="!canEdit" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="res_subdivision">Subdivision / Village</Label>
                                    <Input id="res_subdivision" v-model="form.residential_address!.subdivision" :disabled="!canEdit" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="res_barangay">Barangay</Label>
                                    <Input id="res_barangay" v-model="form.residential_address!.barangay" :disabled="!canEdit" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="res_city">City / Municipality</Label>
                                    <Input id="res_city" v-model="form.residential_address!.city" :disabled="!canEdit" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="res_province">Province</Label>
                                    <Input id="res_province" v-model="form.residential_address!.province" :disabled="!canEdit" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="res_zip">ZIP code</Label>
                                    <Input id="res_zip" v-model="form.residential_address!.zip_code" :disabled="!canEdit" />
                                </div>
                            </div>
                        </div>

                        <!-- Permanent address -->
                        <div class="mt-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Permanent address</p>
                                <label v-if="canEdit" class="flex cursor-pointer items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <input v-model="sameAsResidential" type="checkbox" class="h-4 w-4 rounded border-gray-300" @change="applySameAddress" />
                                    Same as residential
                                </label>
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="space-y-1.5">
                                    <Label for="perm_house_no">House / Block / Lot No.</Label>
                                    <Input id="perm_house_no" v-model="form.permanent_address!.house_no" :disabled="!canEdit || sameAsResidential" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="perm_street">Street</Label>
                                    <Input id="perm_street" v-model="form.permanent_address!.street" :disabled="!canEdit || sameAsResidential" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="perm_subdivision">Subdivision / Village</Label>
                                    <Input id="perm_subdivision" v-model="form.permanent_address!.subdivision" :disabled="!canEdit || sameAsResidential" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="perm_barangay">Barangay</Label>
                                    <Input id="perm_barangay" v-model="form.permanent_address!.barangay" :disabled="!canEdit || sameAsResidential" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="perm_city">City / Municipality</Label>
                                    <Input id="perm_city" v-model="form.permanent_address!.city" :disabled="!canEdit || sameAsResidential" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="perm_province">Province</Label>
                                    <Input id="perm_province" v-model="form.permanent_address!.province" :disabled="!canEdit || sameAsResidential" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="perm_zip">ZIP code</Label>
                                    <Input id="perm_zip" v-model="form.permanent_address!.zip_code" :disabled="!canEdit || sameAsResidential" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 text-left transition-colors hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800/60 dark:hover:bg-neutral-700/60"
                        @click="toggle('contact')"
                    >
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">II. Contact Information</span>
                        <ChevronUp v-if="openSections.contact" class="size-4 text-muted-foreground" />
                        <ChevronDown v-else class="size-4 text-muted-foreground" />
                    </button>
                    <div v-show="openSections.contact" class="bg-white p-5 dark:bg-neutral-900">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="space-y-1.5">
                                <Label for="phone">Telephone number</Label>
                                <Input id="phone" v-model="form.phone" :disabled="!canEdit" placeholder="e.g. (02) 8123 4567" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="mobile">Mobile number</Label>
                                <Input id="mobile" v-model="form.mobile" :disabled="!canEdit" placeholder="e.g. 09XX XXX XXXX" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="email">Email address</Label>
                                <Input id="email" v-model="form.email" type="email" :disabled="!canEdit" placeholder="your@email.com" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Government IDs -->
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 text-left transition-colors hover:bg-gray-100 dark:border-neutral-700 dark:bg-neutral-800/60 dark:hover:bg-neutral-700/60"
                        @click="toggle('govt')"
                    >
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">III. Government IDs</span>
                        <ChevronUp v-if="openSections.govt" class="size-4 text-muted-foreground" />
                        <ChevronDown v-else class="size-4 text-muted-foreground" />
                    </button>
                    <div v-show="openSections.govt" class="bg-white p-5 dark:bg-neutral-900">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="space-y-1.5">
                                <Label for="cs_id">CS / Plantilla No.</Label>
                                <Input id="cs_id" v-model="form.cs_id" :disabled="!canEdit" placeholder="Civil Service / Plantilla No." />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="agency_employee_no">Agency Employee No.</Label>
                                <Input id="agency_employee_no" v-model="form.agency_employee_no" :disabled="!canEdit" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="gsis">GSIS ID No.</Label>
                                <Input id="gsis" v-model="form.gsis" :disabled="!canEdit" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="pag_ibig">Pag-IBIG ID No.</Label>
                                <Input id="pag_ibig" v-model="form.pag_ibig" :disabled="!canEdit" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="philhealth">PhilHealth No.</Label>
                                <Input id="philhealth" v-model="form.philhealth" :disabled="!canEdit" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="sss">SSS No.</Label>
                                <Input id="sss" v-model="form.sss" :disabled="!canEdit" />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="tin">TIN</Label>
                                <Input id="tin" v-model="form.tin" :disabled="!canEdit" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family -->
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Family Background</span>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="space-y-1.5"><Label>Spouse Surname</Label><Input v-model="family.spouse_surname" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Spouse First Name</Label><Input v-model="family.spouse_first_name" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Spouse Middle Name</Label><Input v-model="family.spouse_middle_name" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Spouse Name Ext.</Label><Input v-model="family.spouse_name_extension" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5 sm:col-span-2"><Label>Spouse Occupation</Label><Input v-model="family.spouse_occupation" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5 sm:col-span-2"><Label>Spouse Employer</Label><Input v-model="family.spouse_employer" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5 sm:col-span-2"><Label>Spouse Business Address</Label><Input v-model="family.spouse_business_address" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5 sm:col-span-2"><Label>Spouse Telephone</Label><Input v-model="family.spouse_telephone" :disabled="!canEdit" /></div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="space-y-1.5"><Label>Father Surname</Label><Input v-model="family.father_surname" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Father First Name</Label><Input v-model="family.father_first_name" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Father Middle Name</Label><Input v-model="family.father_middle_name" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Father Name Ext.</Label><Input v-model="family.father_name_extension" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Mother Maiden Surname</Label><Input v-model="family.mother_maiden_surname" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Mother Maiden First Name</Label><Input v-model="family.mother_maiden_first_name" :disabled="!canEdit" /></div>
                            <div class="space-y-1.5"><Label>Mother Maiden Middle Name</Label><Input v-model="family.mother_maiden_middle_name" :disabled="!canEdit" /></div>
                        </div>
                    </div>
                </div>

                <!-- Children -->
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Children</span>
                        <Button v-if="canEdit" type="button" size="sm" variant="outline" @click="children.push({ name: '', dob: '' })">
                            <Plus class="mr-1 size-4" /> Add
                        </Button>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Date of Birth</th>
                                        <th class="w-12 px-3 py-2" />
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(c, i) in children" :key="i">
                                        <td class="px-3 py-2"><Input v-model="c.name" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="c.dob" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2 text-right">
                                            <Button v-if="canEdit" type="button" variant="ghost" size="icon" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="children.splice(i, 1)">
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Education</span>
                        <Button v-if="canEdit" type="button" size="sm" variant="outline" @click="education.push({ level: '', school_name: '', degree_course: '', period_from: '', period_to: '', highest_level: '', year_graduated: '', scholarship_honors: '', awards: '' })">
                            <Plus class="mr-1 size-4" /> Add
                        </Button>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Level</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">School</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Degree</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Year</th>
                                        <th class="w-12 px-3 py-2" />
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(e, i) in education" :key="i">
                                        <td class="px-3 py-2"><Input v-model="e.level" :disabled="!canEdit" placeholder="Elementary/Secondary/etc" /></td>
                                        <td class="px-3 py-2"><Input v-model="e.school_name" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="e.degree_course" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="e.year_graduated" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2 text-right">
                                            <Button v-if="canEdit" type="button" variant="ghost" size="icon" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="education.splice(i, 1)">
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div v-if="canEdit" class="flex flex-wrap items-center justify-end gap-3 pt-1">
                    <p class="mr-auto text-xs text-muted-foreground">Last updated: {{ formatDate(pds?.updated_at ?? null) }}</p>
                    <Button type="button" variant="outline" class="gap-2" :disabled="saving" @click="saveDraft">
                        <Save class="size-4" />
                        {{ saving ? 'Saving…' : 'Save draft' }}
                    </Button>
                    <Button type="button" class="gap-2" @click="submitDialogOpen = true">
                        <Send class="size-4" />
                        Submit for review
                    </Button>
                </div>
            </div>

            <!-- TAB C2 -->
            <div v-show="activeTab === 'c2'" class="space-y-5">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Civil Service Eligibility</span>
                        <Button v-if="canEdit" type="button" size="sm" variant="outline" @click="cscEligibility.push({ license_name: '', rating: '', date_of_examination: '', place_of_examination: '', license_no: '', date_of_validity: '' })">
                            <Plus class="mr-1 size-4" /> Add
                        </Button>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">License/Career</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Rating</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Exam Date</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Place</th>
                                        <th class="w-12 px-3 py-2" />
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(r, i) in cscEligibility" :key="i">
                                        <td class="px-3 py-2"><Input v-model="r.license_name" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="r.rating" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="r.date_of_examination" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="r.place_of_examination" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2 text-right">
                                            <Button v-if="canEdit" type="button" variant="ghost" size="icon" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="cscEligibility.splice(i, 1)"><Trash2 class="size-4" /></Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Work Experience</span>
                        <Button v-if="canEdit" type="button" size="sm" variant="outline" @click="workExperience.push({ employed_from: '', employed_to: '', position_title: '', department: '', salary: '', salary_grade: '', appointment_status: '', is_government: false })">
                            <Plus class="mr-1 size-4" /> Add
                        </Button>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">From</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">To</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Position</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Department</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Gov't</th>
                                        <th class="w-12 px-3 py-2" />
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(w, i) in workExperience" :key="i">
                                        <td class="px-3 py-2"><Input v-model="w.employed_from" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="w.employed_to" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="w.position_title" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="w.department" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2">
                                            <select v-model="w.is_government" :disabled="!canEdit" class="h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                                <option :value="false">N</option>
                                                <option :value="true">Y</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2 text-right"><Button v-if="canEdit" type="button" variant="ghost" size="icon" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="workExperience.splice(i, 1)"><Trash2 class="size-4" /></Button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div v-if="canEdit" class="flex flex-wrap items-center justify-end gap-3 pt-1">
                    <Button type="button" variant="outline" class="gap-2" :disabled="saving" @click="saveDraft">
                        <Save class="size-4" />
                        {{ saving ? 'Saving…' : 'Save draft' }}
                    </Button>
                    <Button type="button" class="gap-2" @click="submitDialogOpen = true">
                        <Send class="size-4" />
                        Submit for review
                    </Button>
                </div>
            </div>

            <!-- TAB C3 -->
            <div v-show="activeTab === 'c3'" class="space-y-5">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Voluntary Work</span>
                        <Button v-if="canEdit" type="button" size="sm" variant="outline" @click="voluntaryWork.push({ org_name_address: '', volunteer_from: '', volunteer_to: '', number_of_hours: '', nature_of_work: '' })">
                            <Plus class="mr-1 size-4" /> Add
                        </Button>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Organization</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">From</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">To</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Hours</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Nature</th>
                                        <th class="w-12 px-3 py-2" />
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(v, i) in voluntaryWork" :key="i">
                                        <td class="px-3 py-2"><Input v-model="v.org_name_address" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="v.volunteer_from" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="v.volunteer_to" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="v.number_of_hours" type="number" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="v.nature_of_work" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2 text-right"><Button v-if="canEdit" type="button" variant="ghost" size="icon" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="voluntaryWork.splice(i, 1)"><Trash2 class="size-4" /></Button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Learning & Development</span>
                        <Button v-if="canEdit" type="button" size="sm" variant="outline" @click="training.push({ title: '', training_from: '', training_to: '', number_of_hours: '', training_type: '', sponsor: '' })">
                            <Plus class="mr-1 size-4" /> Add
                        </Button>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Title</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">From</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">To</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Hours</th>
                                        <th class="w-12 px-3 py-2" />
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(t, i) in training" :key="i">
                                        <td class="px-3 py-2"><Input v-model="t.title" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="t.training_from" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="t.training_to" type="date" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="t.number_of_hours" type="number" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2 text-right"><Button v-if="canEdit" type="button" variant="ghost" size="icon" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="training.splice(i, 1)"><Trash2 class="size-4" /></Button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div v-if="canEdit" class="flex flex-wrap items-center justify-end gap-3 pt-1">
                    <Button type="button" variant="outline" class="gap-2" :disabled="saving" @click="saveDraft">
                        <Save class="size-4" />
                        {{ saving ? 'Saving…' : 'Save draft' }}
                    </Button>
                    <Button type="button" class="gap-2" @click="submitDialogOpen = true">
                        <Send class="size-4" />
                        Submit for review
                    </Button>
                </div>
            </div>

            <!-- TAB C4 -->
            <div v-show="activeTab === 'c4'" class="space-y-5">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Other Information</span>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Skills</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Recognition</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Membership</th>
                                        <th class="w-12 px-3 py-2" />
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(o, i) in otherInfo" :key="i">
                                        <td class="px-3 py-2"><Input v-model="o.skills" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="o.recognition" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="o.membership" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2 text-right"><Button v-if="canEdit" type="button" variant="ghost" size="icon" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="otherInfo.splice(i, 1)"><Trash2 class="size-4" /></Button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <Button v-if="canEdit" type="button" size="sm" variant="outline" @click="otherInfo.push({ skills: '', recognition: '', membership: '' })">
                                <Plus class="mr-1 size-4" /> Add Row
                            </Button>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">References (max 3)</span>
                    </div>
                    <div class="bg-white p-5 dark:bg-neutral-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm dark:border-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Address</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Telephone</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="(r, i) in references.slice(0, 3)" :key="i">
                                        <td class="px-3 py-2"><Input v-model="r.reference_name" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="r.reference_address" :disabled="!canEdit" /></td>
                                        <td class="px-3 py-2"><Input v-model="r.reference_telno" :disabled="!canEdit" /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div v-if="canEdit" class="flex flex-wrap items-center justify-end gap-3 pt-1">
                    <Button type="button" variant="outline" class="gap-2" :disabled="saving" @click="saveDraft">
                        <Save class="size-4" />
                        {{ saving ? 'Saving…' : 'Save draft' }}
                    </Button>
                    <Button type="button" class="gap-2" @click="submitDialogOpen = true">
                        <Send class="size-4" />
                        Submit for review
                    </Button>
                </div>
            </div>

            <!-- Submit confirmation dialog -->
            <Dialog v-model:open="submitDialogOpen">
                <DialogContent :show-close-button="true" class="sm:max-w-md">
                    <DialogHeader>
                        <DialogTitle>Submit PDS for review?</DialogTitle>
                        <DialogDescription class="sr-only">
                            Confirm submission of your Personal Data Sheet for HR review.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                        <p class="text-sm text-muted-foreground">
                            Once submitted, you will not be able to edit your PDS until HR reviews it.
                            Make sure all information is correct before submitting.
                        </p>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="submitDialogOpen = false">Cancel</Button>
                        <Button type="button" @click="submitPds">Yes, submit</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
