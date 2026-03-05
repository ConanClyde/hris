<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

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

type PdsPersonal = {
    first_name?: string;
    middle_name?: string;
    surname?: string;
    name_extension?: string;
    dob?: string;
    place_of_birth?: string;
    sex?: string;
    civil_status?: string;
    email?: string;
    phone?: string;
    mobile?: string;
    [key: string]: unknown;
};

type PdsData = {
    id: number;
    employee_id: number;
    status: string;
    submitted_at: string | null;
    reviewed_at: string | null;
    created_at: string;
    employee?: { id: number; full_name?: string; first_name?: string; last_name?: string };
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

const props = defineProps<{ pds: PdsData }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'PDS Management', href: hr.pds.index.url() },
    { title: 'PDS Preview' },
];

function employeeName(): string {
    const e = props.pds.employee;
    if (!e) return `Employee #${props.pds.employee_id}`;
    return ((e as { full_name?: string }).full_name ?? [e.first_name, e.last_name].filter(Boolean).join(' ')) || `#${props.pds.employee_id}`;
}

function formatDate(value: string | null) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}

function formatAddress(addr: any) {
    if (!addr) return '—';
    const parts = [addr.house_no, addr.street, addr.barangay, addr.city, addr.province, addr.zip_code].filter(Boolean);
    return parts.join(', ') || '—';
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
                        {{ employeeName() }} — Personal Data Sheet
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <Button as-child variant="outline" size="sm">
                        <Link :href="hr.pds.index.url()">Back to list</Link>
                    </Button>
                    <Button variant="outline" size="sm" @click="doPrint">Print</Button>

                    <Form v-if="pds.status === 'submitted'" :action="hr.pds.status.url()" method="post" class="inline">
                        <input type="hidden" name="pds_id" :value="pds.id" />
                        <input type="hidden" name="status" value="approved" />
                        <Button type="submit" size="sm">Approve</Button>
                    </Form>
                    <Form v-if="pds.status === 'submitted'" :action="hr.pds.status.url()" method="post" class="inline">
                        <input type="hidden" name="pds_id" :value="pds.id" />
                        <input type="hidden" name="status" value="rejected" />
                        <Button type="submit" size="sm" variant="destructive">Reject</Button>
                    </Form>
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

                        <div class="pds-section">
                            <div class="pds-section-title">I. PERSONAL INFORMATION</div>

                            <div class="pds-grid">
                                <div class="pds-field">
                                    <div class="pds-label">SURNAME</div>
                                    <div class="pds-value">{{ pds.personal?.surname || '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">FIRST NAME</div>
                                    <div class="pds-value">{{ pds.personal?.first_name || '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">MIDDLE NAME</div>
                                    <div class="pds-value">{{ pds.personal?.middle_name || '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">NAME EXTENSION (JR., SR.)</div>
                                    <div class="pds-value">{{ pds.personal?.name_extension || '' }}</div>
                                </div>

                                <div class="pds-field">
                                    <div class="pds-label">DATE OF BIRTH</div>
                                    <div class="pds-value">{{ formatDate(pds.personal?.dob ?? null) }}</div>
                                </div>
                                <div class="pds-field pds-span-3">
                                    <div class="pds-label">PLACE OF BIRTH</div>
                                    <div class="pds-value">{{ pds.personal?.place_of_birth || '' }}</div>
                                </div>

                                <div class="pds-field">
                                    <div class="pds-label">SEX</div>
                                    <div class="pds-value">{{ pds.personal?.sex || '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">CIVIL STATUS</div>
                                    <div class="pds-value">{{ pds.personal?.civil_status || '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">HEIGHT (m)</div>
                                    <div class="pds-value">{{ pds.personal?.height ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">WEIGHT (kg)</div>
                                    <div class="pds-value">{{ pds.personal?.weight ?? '' }}</div>
                                </div>

                                <div class="pds-field">
                                    <div class="pds-label">BLOOD TYPE</div>
                                    <div class="pds-value">{{ pds.personal?.blood_type ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">CITIZENSHIP</div>
                                    <div class="pds-value">
                                        {{ [pds.personal?.citizenship_type, pds.personal?.citizenship_country].filter(Boolean).join(' ') }}
                                    </div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">MOBILE NO.</div>
                                    <div class="pds-value">{{ pds.personal?.mobile ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">EMAIL ADDRESS</div>
                                    <div class="pds-value">{{ pds.personal?.email ?? '' }}</div>
                                </div>
                            </div>

                            <div class="mt-3 pds-grid">
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">RESIDENTIAL ADDRESS</div>
                                    <div class="pds-value">{{ formatAddress(pds.personal?.residential_address) }}</div>
                                </div>
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">PERMANENT ADDRESS</div>
                                    <div class="pds-value">{{ formatAddress(pds.personal?.permanent_address) }}</div>
                                </div>
                            </div>

                            <div class="mt-3 pds-grid">
                                <div class="pds-field">
                                    <div class="pds-label">TELEPHONE NO.</div>
                                    <div class="pds-value">{{ pds.personal?.phone ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">GSIS ID NO.</div>
                                    <div class="pds-value">{{ pds.personal?.gsis ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">PAG-IBIG ID NO.</div>
                                    <div class="pds-value">{{ pds.personal?.pag_ibig ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">PHILHEALTH NO.</div>
                                    <div class="pds-value">{{ pds.personal?.philhealth ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">SSS NO.</div>
                                    <div class="pds-value">{{ pds.personal?.sss ?? '' }}</div>
                                </div>
                                <div class="pds-field">
                                    <div class="pds-label">TIN NO.</div>
                                    <div class="pds-value">{{ pds.personal?.tin ?? '' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="pds-section">
                            <div class="pds-section-title">II. FAMILY BACKGROUND</div>
                            <div class="pds-grid">
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">SPOUSE (Full Name)</div>
                                    <div class="pds-value">
                                        {{ [pds.family?.spouse_first_name, pds.family?.spouse_middle_name, pds.family?.spouse_surname, pds.family?.spouse_name_extension].filter(Boolean).join(' ') }}
                                    </div>
                                </div>
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">SPOUSE OCCUPATION / EMPLOYER</div>
                                    <div class="pds-value">
                                        {{ [pds.family?.spouse_occupation, pds.family?.spouse_employer].filter(Boolean).join(' / ') }}
                                    </div>
                                </div>
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">FATHER (Full Name)</div>
                                    <div class="pds-value">
                                        {{ [pds.family?.father_first_name, pds.family?.father_middle_name, pds.family?.father_surname, pds.family?.father_name_extension].filter(Boolean).join(' ') }}
                                    </div>
                                </div>
                                <div class="pds-field pds-span-2">
                                    <div class="pds-label">MOTHER'S MAIDEN NAME</div>
                                    <div class="pds-value">
                                        {{ [pds.family?.mother_maiden_first_name, pds.family?.mother_maiden_middle_name, pds.family?.mother_maiden_surname].filter(Boolean).join(' ') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pds-section" v-if="(pds.children?.length ?? 0) > 0">
                            <div class="pds-section-title">III. CHILDREN (Full Name / Date of Birth)</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>NAME OF CHILD</th>
                                        <th>DATE OF BIRTH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(c, i) in (pds.children ?? [])" :key="i">
                                        <td>{{ c.name || '' }}</td>
                                        <td>{{ formatDate(c.dob ?? null) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds.education?.length ?? 0) > 0">
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
                                    <tr v-for="(e, i) in (pds.education ?? [])" :key="i">
                                        <td>{{ e.level || '' }}</td>
                                        <td>{{ e.school_name || '' }}</td>
                                        <td>{{ e.degree_course || '' }}</td>
                                        <td>{{ e.year_graduated || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds.csc_eligibility?.length ?? 0) > 0">
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
                                    <tr v-for="(r, i) in (pds.csc_eligibility ?? [])" :key="i">
                                        <td>{{ r.license_name || '' }}</td>
                                        <td>{{ r.rating || '' }}</td>
                                        <td>{{ formatDate(r.date_of_examination ?? null) }}</td>
                                        <td>{{ r.place_of_examination || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-page-break"></div>

                        <div class="pds-section" v-if="(pds.work_experience?.length ?? 0) > 0">
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
                                    <tr v-for="(w, i) in (pds.work_experience ?? [])" :key="i">
                                        <td>{{ formatDate(w.employed_from ?? null) }}</td>
                                        <td>{{ formatDate(w.employed_to ?? null) }}</td>
                                        <td>{{ w.position_title || '' }}</td>
                                        <td>{{ w.department || '' }}</td>
                                        <td>{{ w.is_government ? 'Y' : 'N' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds.voluntary_work?.length ?? 0) > 0">
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
                                    <tr v-for="(v, i) in (pds.voluntary_work ?? [])" :key="i">
                                        <td>{{ v.org_name_address || '' }}</td>
                                        <td>{{ formatDate(v.volunteer_from ?? null) }}</td>
                                        <td>{{ formatDate(v.volunteer_to ?? null) }}</td>
                                        <td>{{ v.number_of_hours || '' }}</td>
                                        <td>{{ v.nature_of_work || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="(pds.training?.length ?? 0) > 0">
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
                                    <tr v-for="(t, i) in (pds.training ?? [])" :key="i">
                                        <td>{{ t.title || '' }}</td>
                                        <td>{{ formatDate(t.training_from ?? null) }}</td>
                                        <td>{{ formatDate(t.training_to ?? null) }}</td>
                                        <td>{{ t.number_of_hours || '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-signature-row">
                            <div class="pds-signature-cell">SIGNATURE</div>
                            <div class="pds-signature-mid">(wet signature/e-signature/digital certificate)</div>
                            <div class="pds-signature-cell">DATE</div>
                        </div>

                        <!-- Page 2 -->
                        <div class="pds-page-break"></div>
                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">IV. CIVIL SERVICE ELIGIBILITY</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>CAREER SERVICE / RA 1080 (BOARD / BAR) UNDER SPECIAL LAWS / CES / CSEE / BARANGAY ELIGIBILITY / DRIVER'S LICENSE</th>
                                        <th>RATING</th>
                                        <th>DATE OF EXAMINATION / CONFERMENT</th>
                                        <th>PLACE OF EXAMINATION / CONFERMENT</th>
                                        <th>LICENSE (if applicable)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(c, i) in (pds.csc_eligibility ?? [])" :key="`csc-${i}`">
                                        <td>{{ c.license_name || '' }}</td>
                                        <td>{{ c.rating || '' }}</td>
                                        <td>{{ formatDate(c.date_of_examination ?? null) }}</td>
                                        <td>{{ c.place_of_examination || '' }}</td>
                                        <td></td>
                                    </tr>
                                    <tr v-for="i in Math.max(0, 7 - (pds.csc_eligibility?.length ?? 0))" :key="`csc-empty-${i}`">
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">V. WORK EXPERIENCE</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">INCLUSIVE DATES</th>
                                        <th>POSITION TITLE</th>
                                        <th>DEPARTMENT / AGENCY / OFFICE / COMPANY</th>
                                        <th>STATUS OF APPOINTMENT</th>
                                        <th>GOV'T SERVICE</th>
                                    </tr>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th colspan="4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(w, i) in (pds.work_experience ?? [])" :key="`work-${i}`">
                                        <td>{{ formatDate(w.employed_from ?? null) }}</td>
                                        <td>{{ formatDate(w.employed_to ?? null) }}</td>
                                        <td>{{ w.position_title || '' }}</td>
                                        <td>{{ w.department || '' }}</td>
                                        <td></td>
                                        <td>{{ w.is_government ? 'Y' : 'N' }}</td>
                                    </tr>
                                    <tr v-for="i in Math.max(0, 24 - (pds.work_experience?.length ?? 0))" :key="`work-empty-${i}`">
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-signature-row">
                            <div class="pds-signature-cell">SIGNATURE</div>
                            <div class="pds-signature-mid">(wet signature/e-signature/digital certificate)</div>
                            <div class="pds-signature-cell">DATE</div>
                        </div>

                        <!-- Page 3 -->
                        <div class="pds-page-break"></div>
                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>NAME & ADDRESS OF ORGANIZATION</th>
                                        <th colspan="2">INCLUSIVE DATES</th>
                                        <th>NO. OF HOURS</th>
                                        <th>POSITION / NATURE OF WORK</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(v, i) in (pds.voluntary_work ?? [])" :key="`vol-${i}`">
                                        <td>{{ v.org_name_address || '' }}</td>
                                        <td>{{ formatDate(v.volunteer_from ?? null) }}</td>
                                        <td>{{ formatDate(v.volunteer_to ?? null) }}</td>
                                        <td>{{ v.number_of_hours || '' }}</td>
                                        <td>{{ v.nature_of_work || '' }}</td>
                                    </tr>
                                    <tr v-for="i in Math.max(0, 7 - (pds.voluntary_work?.length ?? 0))" :key="`vol-empty-${i}`">
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">VII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS / TRAINING PROGRAMS ATTENDED</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS / TRAINING PROGRAMS</th>
                                        <th colspan="2">INCLUSIVE DATES OF ATTENDANCE</th>
                                        <th>NUMBER OF HOURS</th>
                                        <th>TYPE OF L&D</th>
                                        <th>CONDUCTED / SPONSORED BY</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(t, i) in (pds.training ?? [])" :key="`train-${i}`">
                                        <td>{{ t.title || '' }}</td>
                                        <td>{{ formatDate(t.training_from ?? null) }}</td>
                                        <td>{{ formatDate(t.training_to ?? null) }}</td>
                                        <td>{{ t.number_of_hours || '' }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr v-for="i in Math.max(0, 19 - (pds.training?.length ?? 0))" :key="`train-empty-${i}`">
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">VIII. OTHER INFORMATION</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>SPECIAL SKILLS AND HOBBIES</th>
                                        <th>NON-ACADEMIC DISTINCTIONS / RECOGNITION</th>
                                        <th>MEMBERSHIP IN ASSOCIATION / ORGANIZATION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(o, i) in (pds.other_info ?? [])" :key="`other-${i}`">
                                        <td>{{ o.skills || '' }}</td>
                                        <td>{{ o.recognition || '' }}</td>
                                        <td>{{ o.membership || '' }}</td>
                                    </tr>
                                    <tr v-for="i in Math.max(0, 6 - (pds.other_info?.length ?? 0))" :key="`other-empty-${i}`">
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="pds-signature-row">
                            <div class="pds-signature-cell">SIGNATURE</div>
                            <div class="pds-signature-mid">(wet signature/e-signature/digital certificate)</div>
                            <div class="pds-signature-cell">DATE</div>
                        </div>

                        <!-- Page 4 placeholder -->
                        <div class="pds-page-break"></div>
                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">IX. QUESTIONS</div>
                            <div class="pds-block">&nbsp;</div>
                        </div>
                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">X. REFERENCES</div>
                            <table class="pds-table">
                                <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>OFFICE / RESIDENTIAL ADDRESS</th>
                                        <th>TELEPHONE NO.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(r, i) in (pds.references ?? [])" :key="`ref-${i}`">
                                        <td>{{ r.reference_name || '' }}</td>
                                        <td>{{ r.reference_address || '' }}</td>
                                        <td>{{ r.reference_telno || '' }}</td>
                                    </tr>
                                    <tr v-for="i in Math.max(0, 3 - (pds.references?.length ?? 0))" :key="`ref-empty-${i}`">
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="pds-section" v-if="pds">
                            <div class="pds-section-title">XI. GOVERNMENT ISSUED ID</div>
                            <div class="pds-block">&nbsp;</div>
                        </div>

                        <div class="pds-footer">
                            <div class="text-[10px]">
                                Generated by HRIS — {{ employeeName() }} (#{{ pds.employee_id }})
                            </div>
                            <div class="text-[10px]">Page 1</div>
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

.pds-footer {
    margin-top: 10px;
    border-top: 1px solid #111;
    padding-top: 6px;
    display: flex;
    justify-content: space-between;
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
