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

function doPrint() {
    window.print();
}
</script>

<template>
    <Head title="PDS Preview" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="mb-4 flex flex-col gap-3 print:hidden sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">PDS Preview</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ pds?.personal ? fullName(pds.personal) : '—' }} — Personal Data Sheet
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <Button as-child variant="outline" size="sm">
                        <Link :href="employee.pds.index.url()">Edit PDS</Link>
                    </Button>
                    <Button variant="outline" size="sm" @click="doPrint">Print</Button>
                </div>
            </div>

            <div class="mx-auto w-full max-w-5xl">
                <div class="pds-paper bg-white text-black shadow print:shadow-none">
                    <!-- Page 1 -->
                    <div class="pds-page">
                        <div class="pds-header">
                            <div class="text-center">
                                <div class="text-xs font-semibold">CS FORM No. 212</div>
                                <div class="text-xs">Revised 2025</div>
                                <div class="mt-2 text-sm font-bold tracking-wide">PERSONAL DATA SHEET</div>
                            </div>
                            <div class="mt-3 text-[10px] leading-tight">
                                <div>
                                    <span class="font-semibold">WARNING:</span>
                                    Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.
                                </div>
                            </div>
                        </div>

                        <div v-if="pds?.personal" class="pds-section">
                            <div class="pds-section-title">I. PERSONAL INFORMATION</div>
                            <table class="pds-form-table">
                                <tbody>
                                    <tr>
                                        <td class="pds-form-label" rowspan="2">2. SURNAME</td>
                                        <td class="pds-form-value" colspan="4">{{ pds.personal?.surname || '' }}</td>
                                        <td class="pds-form-label">NAME EXTENSION (JR., SR.)</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.name_extension || '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-label">3. FIRST NAME</td>
                                        <td class="pds-form-value" colspan="3">{{ pds.personal?.first_name || '' }}</td>
                                        <td class="pds-form-label">MIDDLE NAME</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.middle_name || '' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="pds-form-label">4. DATE OF BIRTH (mm/dd/yyyy)</td>
                                        <td class="pds-form-value" colspan="2">{{ formatDate(pds.personal?.dob ?? null) }}</td>
                                        <td class="pds-form-label" colspan="2">5. PLACE OF BIRTH</td>
                                        <td class="pds-form-value" colspan="3">{{ pds.personal?.place_of_birth || '' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="pds-form-label">6. SEX</td>
                                        <td class="pds-form-value" colspan="2">
                                            <span class="pds-checkbox"></span> Male
                                            <span class="pds-checkbox ml-4"></span> Female
                                        </td>
                                        <td class="pds-form-label">7. CIVIL STATUS</td>
                                        <td class="pds-form-value" colspan="2">
                                            <div class="pds-checkbox-grid">
                                                <div><span class="pds-checkbox"></span> Single</div>
                                                <div><span class="pds-checkbox"></span> Widowed</div>
                                                <div><span class="pds-checkbox"></span> Married</div>
                                                <div><span class="pds-checkbox"></span> Separated</div>
                                            </div>
                                        </td>
                                        <td class="pds-form-label">8. CITIZENSHIP</td>
                                        <td class="pds-form-value">
                                            <div class="pds-checkbox-grid">
                                                <div><span class="pds-checkbox"></span> Filipino</div>
                                                <div><span class="pds-checkbox"></span> Dual Citizenship</div>
                                            </div>
                                            <div class="mt-1 text-[9px]">
                                                <span class="pds-checkbox"></span> by birth
                                                <span class="pds-checkbox ml-4"></span> by naturalization
                                            </div>
                                            <div class="mt-1 text-[9px]">Pls. indicate country: {{ pds.personal?.citizenship_country ?? '' }}</div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="pds-form-label">9. HEIGHT (m)</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.height ?? '' }}</td>
                                        <td class="pds-form-label">10. WEIGHT (kg)</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.weight ?? '' }}</td>
                                        <td class="pds-form-label">11. BLOOD TYPE</td>
                                        <td class="pds-form-value">{{ pds.personal?.blood_type ?? '' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="pds-form-label" rowspan="4">12. RESIDENTIAL ADDRESS</td>
                                        <td class="pds-form-sub" colspan="2">House/Block/Lot No.</td>
                                        <td class="pds-form-sub" colspan="2">Street</td>
                                        <td class="pds-form-sub" colspan="3">Subdivision/Village</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.residential_address?.house_no ?? '' }}</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.residential_address?.street ?? '' }}</td>
                                        <td class="pds-form-value" colspan="3">{{ pds.personal?.residential_address?.subdivision ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-sub" colspan="2">Barangay</td>
                                        <td class="pds-form-sub" colspan="2">City/Municipality</td>
                                        <td class="pds-form-sub" colspan="2">Province</td>
                                        <td class="pds-form-sub">Zip Code</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.residential_address?.barangay ?? '' }}</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.residential_address?.city ?? '' }}</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.residential_address?.province ?? '' }}</td>
                                        <td class="pds-form-value">{{ pds.personal?.residential_address?.zip_code ?? '' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="pds-form-label" rowspan="4">13. PERMANENT ADDRESS</td>
                                        <td class="pds-form-sub" colspan="2">House/Block/Lot No.</td>
                                        <td class="pds-form-sub" colspan="2">Street</td>
                                        <td class="pds-form-sub" colspan="3">Subdivision/Village</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.permanent_address?.house_no ?? '' }}</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.permanent_address?.street ?? '' }}</td>
                                        <td class="pds-form-value" colspan="3">{{ pds.personal?.permanent_address?.subdivision ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-sub" colspan="2">Barangay</td>
                                        <td class="pds-form-sub" colspan="2">City/Municipality</td>
                                        <td class="pds-form-sub" colspan="2">Province</td>
                                        <td class="pds-form-sub">Zip Code</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.permanent_address?.barangay ?? '' }}</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.permanent_address?.city ?? '' }}</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.permanent_address?.province ?? '' }}</td>
                                        <td class="pds-form-value">{{ pds.personal?.permanent_address?.zip_code ?? '' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="pds-form-label">14. TELEPHONE NO.</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.phone ?? '' }}</td>
                                        <td class="pds-form-label">15. MOBILE NO.</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.mobile ?? '' }}</td>
                                        <td class="pds-form-label">16. EMAIL ADDRESS</td>
                                        <td class="pds-form-value">{{ pds.personal?.email ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-label">17. GSIS ID NO.</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.gsis ?? '' }}</td>
                                        <td class="pds-form-label">18. PAG-IBIG ID NO.</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.pag_ibig ?? '' }}</td>
                                        <td class="pds-form-label">19. PHILHEALTH NO.</td>
                                        <td class="pds-form-value">{{ pds.personal?.philhealth ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pds-form-label">20. SSS NO.</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.sss ?? '' }}</td>
                                        <td class="pds-form-label">21. TIN NO.</td>
                                        <td class="pds-form-value" colspan="2">{{ pds.personal?.tin ?? '' }}</td>
                                        <td class="pds-form-label">22. AGENCY EMPLOYEE NO.</td>
                                        <td class="pds-form-value">{{ pds.personal?.agency_employee_no ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section">
                            <div class="pds-section-title">II. FAMILY BACKGROUND</div>
                            <div class="pds-grid">
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">SPOUSE (Full Name)</div>
                                    <div class="pds-value">
                                        {{ [pds?.family?.spouse_first_name, pds?.family?.spouse_middle_name, pds?.family?.spouse_surname, pds?.family?.spouse_name_extension].filter(Boolean).join(' ') }}
                                    </div>
                                </div>
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">SPOUSE OCCUPATION / EMPLOYER</div>
                                    <div class="pds-value">
                                        {{ [pds?.family?.spouse_occupation, pds?.family?.spouse_employer].filter(Boolean).join(' / ') }}
                                    </div>
                                </div>
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">FATHER (Full Name)</div>
                                    <div class="pds-value">
                                        {{ [pds?.family?.father_first_name, pds?.family?.father_middle_name, pds?.family?.father_surname, pds?.family?.father_name_extension].filter(Boolean).join(' ') }}
                                    </div>
                                </div>
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">MOTHER'S MAIDEN NAME</div>
                                    <div class="pds-value">
                                        {{ [pds?.family?.mother_maiden_first_name, pds?.family?.mother_maiden_middle_name, pds?.family?.mother_maiden_surname].filter(Boolean).join(' ') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pds-section" v-if="(pds?.children?.length ?? 0) > 0">
                            <div class="pds-section-title">III. CHILDREN (Full Name / Date of Birth)</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>NAME OF CHILD</th>
                                        <th>DATE OF BIRTH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(c, i) in (pds?.children ?? [])" :key="i">
                                        <td>{{ c.name || '' }}</td>
                                        <td>{{ formatDate(c.dob ?? null) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds?.education?.length ?? 0) > 0">
                            <div class="pds-section-title">IV. EDUCATIONAL BACKGROUND</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>LEVEL</th>
                                        <th>NAME OF SCHOOL</th>
                                        <th>BASIC EDUCATION / DEGREE / COURSE</th>
                                        <th>YEAR GRADUATED</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(e, i) in (pds?.education ?? [])" :key="i">
                                        <td>{{ e.level || '' }}</td>
                                        <td>{{ e.school_name || '' }}</td>
                                        <td>{{ e.degree_course || '' }}</td>
                                        <td>{{ e.year_graduated || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds?.csc_eligibility?.length ?? 0) > 0">
                            <div class="pds-section-title">V. CIVIL SERVICE ELIGIBILITY</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>CAREER SERVICE / RA 1080 (BOARD/BAR) / UNDER SPECIAL LAWS / CSC ELIGIBILITY</th>
                                        <th>RATING</th>
                                        <th>DATE OF EXAMINATION / CONFERMENT</th>
                                        <th>PLACE OF EXAMINATION / CONFERMENT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(r, i) in (pds?.csc_eligibility ?? [])" :key="i">
                                        <td>{{ r.license_name || '' }}</td>
                                        <td>{{ r.rating || '' }}</td>
                                        <td>{{ formatDate(r.date_of_examination ?? null) }}</td>
                                        <td>{{ r.place_of_examination || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-page-break"></div>

                        <div class="pds-section" v-if="(pds?.work_experience?.length ?? 0) > 0">
                            <div class="pds-section-title">VI. WORK EXPERIENCE</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>FROM</th>
                                        <th>TO</th>
                                        <th>POSITION TITLE</th>
                                        <th>DEPARTMENT / AGENCY / OFFICE / COMPANY</th>
                                        <th>GOV'T SERVICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(w, i) in (pds?.work_experience ?? [])" :key="i">
                                        <td>{{ formatDate(w.employed_from ?? null) }}</td>
                                        <td>{{ formatDate(w.employed_to ?? null) }}</td>
                                        <td>{{ w.position_title || '' }}</td>
                                        <td>{{ w.department || '' }}</td>
                                        <td>{{ w.is_government ? 'Y' : 'N' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds?.voluntary_work?.length ?? 0) > 0">
                            <div class="pds-section-title">VII. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>NAME & ADDRESS OF ORGANIZATION</th>
                                        <th>FROM</th>
                                        <th>TO</th>
                                        <th>NO. OF HOURS</th>
                                        <th>POSITION / NATURE OF WORK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(v, i) in (pds?.voluntary_work ?? [])" :key="i">
                                        <td>{{ v.org_name_address || '' }}</td>
                                        <td>{{ formatDate(v.volunteer_from ?? null) }}</td>
                                        <td>{{ formatDate(v.volunteer_to ?? null) }}</td>
                                        <td>{{ v.number_of_hours || '' }}</td>
                                        <td>{{ v.nature_of_work || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds?.training?.length ?? 0) > 0">
                            <div class="pds-section-title">VIII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS / TRAINING PROGRAMS ATTENDED</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS / TRAINING PROGRAMS</th>
                                        <th>FROM</th>
                                        <th>TO</th>
                                        <th>NO. OF HOURS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(t, i) in (pds?.training ?? [])" :key="i">
                                        <td>{{ t.title || '' }}</td>
                                        <td>{{ formatDate(t.training_from ?? null) }}</td>
                                        <td>{{ formatDate(t.training_to ?? null) }}</td>
                                        <td>{{ t.number_of_hours || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="!pds?.personal" class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-muted-foreground dark:border-neutral-700 dark:bg-neutral-800/50">
                            No PDS data yet. Fill out your PDS from the
                            <Link :href="employee.pds.index.url()" class="text-primary underline">PDS form</Link>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.pds-paper {
    border: 2px solid #111;
    font-family: "Arial Narrow", Arial, Helvetica, sans-serif;
}

.pds-page {
    width: 13in;
    min-height: 8.5in;
    padding: 0.25in;
    margin: 0 auto;
    box-sizing: border-box;
}

.pds-header {
    border-bottom: 2px solid #111;
    padding-bottom: 10px;
}

.pds-section {
    margin-top: 10px;
}

.pds-section-title {
    border: 1px solid #111;
    background: #bdbdbd;
    padding: 4px 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.02em;
}

.pds-grid {
    margin-top: 6px;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 6px;
}

.pds-field {
    border: 1px solid #111;
    padding: 4px 6px;
    min-height: 46px;
}

.pds-label {
    font-size: 9px;
    font-weight: 700;
}

.pds-value {
    margin-top: 4px;
    font-size: 11px;
    line-height: 1.2;
    word-break: break-word;
}

.pds-span-2 {
    grid-column: span 2 / span 2;
}

.pds-span-3 {
    grid-column: span 3 / span 3;
}

.pds-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
    font-size: 11px;
}

.pds-table th,
.pds-table td {
    border: 1px solid #111;
    padding: 4px;
    vertical-align: top;
}

.pds-table th {
    background: #efefef;
    font-size: 9px;
    text-align: left;
}

.pds-form-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
    table-layout: fixed;
}

.pds-form-table td {
    border: 1px solid #111;
    padding: 3px 4px;
    vertical-align: top;
}

.pds-form-label {
    font-size: 9px;
    font-weight: 700;
    background: #efefef;
}

.pds-form-sub {
    font-size: 9px;
    background: #ffffff;
    font-weight: 700;
}

.pds-form-value {
    font-size: 11px;
    line-height: 1.1;
    min-height: 18px;
}

.pds-checkbox {
    display: inline-block;
    width: 10px;
    height: 10px;
    border: 1px solid #111;
    vertical-align: -1px;
    margin-right: 4px;
}

.pds-checkbox-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2px 10px;
    font-size: 9px;
}

.pds-signature-row {
    margin-top: 8px;
    width: 100%;
    display: grid;
    grid-template-columns: 110px 1fr 70px;
    border: 1px solid #111;
}

.pds-signature-cell {
    padding: 6px;
    font-size: 10px;
    font-weight: 700;
    background: #efefef;
    border-right: 1px solid #111;
}

.pds-signature-mid {
    padding: 6px;
    font-size: 10px;
    text-align: center;
    border-right: 1px solid #111;
    color: #a11;
}

.pds-block {
    border: 1px solid #111;
    min-height: 180px;
    margin-top: 6px;
}

.pds-page-break {
    height: 0;
}

@media print {
    @page {
        size: 13in 8.5in;
        margin: 0;
    }

    .pds-paper {
        border: none;
    }

    .pds-page {
        padding: 0;
        width: auto;
        min-height: auto;
    }

    .pds-page-break {
        break-before: page;
        page-break-before: always;
    }
}
</style>
