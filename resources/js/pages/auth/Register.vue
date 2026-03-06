<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import {
    AlertTriangle,
    Briefcase,
    Calendar,
    ChevronRight,
    FileText,
    GraduationCap,
    Shield,
    User,
    Users,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import AlertError from '@/components/AlertError.vue';
import PasswordInput from '@/components/auth/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';

type DivisionOption = {
    id: number;
    name: string;
    subdivisions: {
        id: number;
        name: string;
        sections: { id: number; name: string }[];
    }[];
    sections: { id: number; name: string }[];
};

const props = defineProps<{
    organizationalStructure: DivisionOption[];
    canRegister: boolean;
}>();

const currentStep = ref(1);
const formData = ref({
    role: '' as 'employee' | 'hr' | 'admin' | '',
    first_name: '',
    middle_name: '',
    surname: '',
    name_extension: '',
    sex: '' as 'male' | 'female' | '',
    date_of_birth: '',
    date_hired: '',
    division_id: null as number | string | null,
    subdivision_id: null as number | string | null,
    section_id: null as number | string | null,
    position: '',
    classification: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const isEmployee = computed(() => formData.value.role === 'employee');

const totalSteps = computed(() => (isEmployee.value ? 3 : 2));

const displayStep = computed(() => {
    if (currentStep.value <= 1) return 0;
    if (isEmployee.value) {
        return currentStep.value - 1;
    }
    if (currentStep.value === 2) return 1;
    return 2;
});

const roleTitle = computed(() => {
    const titles: Record<string, string> = {
        employee: 'Employee',
        hr: 'HR',
        admin: 'Admin',
    };
    return titles[formData.value.role] || '';
});

const layoutTitle = computed(() => {
    if (currentStep.value === 1) return 'Create an account';
    return `${roleTitle.value} Registration`;
});

const layoutDescription = computed(() => {
    if (currentStep.value === 1) return 'Select your role to get started';
    if (currentStep.value === 2) return 'Enter your personal details.';
    if (currentStep.value === 3 && isEmployee.value)
        return 'Enter employment and organizational details.';
    return 'Set up your login credentials.';
});

const isCredentialsStep = computed(() => {
    if (isEmployee.value) return currentStep.value === 4;
    return currentStep.value === 3;
});

const canProceedNext = computed(() => {
    if (currentStep.value === 1) return false;

    if (currentStep.value === 2) {
        const baseOk =
            Boolean(formData.value.first_name?.trim()) &&
            Boolean(formData.value.surname?.trim());
        if (!baseOk) return false;
        return (
            Boolean(formData.value.sex) && Boolean(formData.value.date_of_birth)
        );
    }

    if (currentStep.value === 3) {
        if (isEmployee.value) {
            const dateHiredOk = Boolean(formData.value.date_hired);
            if (!dateHiredOk) return false;

            const divisionOk = Boolean(formData.value.division_id);
            if (!divisionOk) return false;

            if (subdivisionOptions.value.length) {
                const subdivisionOk = Boolean(formData.value.subdivision_id);
                if (!subdivisionOk) return false;
            }

            if (sectionOptions.value.length) {
                const sectionOk = Boolean(formData.value.section_id);
                if (!sectionOk) return false;
            }

            const classificationOk = Boolean(formData.value.classification);
            if (!classificationOk) return false;

            return true;
        }
        return true;
    }

    return false;
});

const selectedDivision = computed(() => {
    const id = formData.value.division_id;
    if (id === null || id === '') return undefined;
    return props.organizationalStructure.find((d) => d.id === Number(id));
});

const subdivisionOptions = computed(
    () => selectedDivision.value?.subdivisions ?? [],
);

const sectionOptions = computed(() => {
    const div = selectedDivision.value;
    if (!div) return [];
    const subId = formData.value.subdivision_id;
    const fromSub = subId
        ? (div.subdivisions.find((s) => s.id === Number(subId))?.sections ?? [])
        : [];
    if (fromSub.length) return fromSub;
    if (subdivisionOptions.value.length) return [];
    return div.sections ?? [];
});

const registerFeatures = [
    {
        icon: Users,
        title: 'Management',
        description: 'People directory and org structure',
    },
    {
        icon: Calendar,
        title: 'Leave & time-off',
        description: 'Apply, approve, and track leave',
    },
    {
        icon: GraduationCap,
        title: 'Training & development',
        description: 'Training records and compliance',
    },
    {
        icon: FileText,
        title: 'PDS & documents',
        description: 'Personal Data Sheet and file management',
    },
];

const roleOptions = [
    {
        value: 'employee' as const,
        title: 'Employee',
        description: 'Standard employee access with leave and training',
        icon: User,
    },
    {
        value: 'hr' as const,
        title: 'HR',
        description: 'HR personnel with approval and management access',
        icon: Briefcase,
    },
    {
        value: 'admin' as const,
        title: 'Admin',
        description: 'Full system access with complete administrative control',
        icon: Shield,
    },
];

function selectRole(role: 'employee' | 'hr' | 'admin') {
    formData.value.role = role;
    currentStep.value = 2;
}

function nextStep() {
    const maxStep = isEmployee.value ? 4 : 3;
    if (currentStep.value < maxStep) currentStep.value++;
}

function prevStep() {
    if (currentStep.value > 1) currentStep.value--;
}

watch(
    () => formData.value.division_id,
    () => {
        formData.value.subdivision_id = null;
        formData.value.section_id = null;
    },
);

watch(
    () => formData.value.subdivision_id,
    () => {
        formData.value.section_id = null;
    },
);

const errorsList = (errors: Record<string, string>) => {
    return Object.values(errors);
};
</script>

<template>
    <AuthBase
        :title="layoutTitle"
        :description="layoutDescription"
        :header-link="
            currentStep === 1
                ? { href: login().url, label: 'Sign in' }
                : undefined
        "
        :show-progress="currentStep > 1"
        :progress-step="displayStep"
        :total-steps="totalSteps"
        :step-label="
            currentStep > 1 ? `Step ${displayStep} of ${totalSteps}` : undefined
        "
        :features="registerFeatures"
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <AlertError
                v-if="Object.keys(errors).length"
                :errors="errorsList(errors)"
                class="mb-4 border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20"
            />

            <!-- Step 1: Role Selection (auto-advance, no Next button) -->
            <div v-show="currentStep === 1" class="flex min-w-0 flex-col gap-6">
                <Alert
                    class="border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-900/20"
                >
                    <AlertTriangle
                        class="size-4 shrink-0 !text-orange-500 dark:!text-orange-400"
                    />
                    <AlertDescription
                        class="text-amber-800 dark:text-amber-200"
                    >
                        <span class="font-semibold">Approval Required.</span>
                        Your registration will be reviewed before your account
                        is activated.
                    </AlertDescription>
                </Alert>
                <div class="space-y-3">
                    <button
                        v-for="role in roleOptions"
                        :key="role.value"
                        type="button"
                        class="flex w-full cursor-pointer items-center gap-3 rounded-lg border p-4 text-left transition-colors hover:border-gray-300 hover:bg-gray-50 dark:hover:border-neutral-600 dark:hover:bg-neutral-800/50"
                        :class="
                            formData.role === role.value
                                ? 'border-brand bg-brand/5'
                                : 'border-gray-200 dark:border-neutral-700'
                        "
                        @click="selectRole(role.value)"
                    >
                        <div
                            class="flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800"
                            :class="
                                formData.role === role.value
                                    ? 'border-brand bg-brand/10'
                                    : ''
                            "
                        >
                            <component
                                :is="role.icon"
                                class="size-5 text-gray-600 dark:text-gray-400"
                                :class="
                                    formData.role === role.value
                                        ? 'text-brand'
                                        : ''
                                "
                            />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ role.title }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ role.description }}
                            </p>
                        </div>
                        <ChevronRight
                            class="size-5 shrink-0 text-gray-400"
                            :class="
                                formData.role === role.value ? 'text-brand' : ''
                            "
                        />
                    </button>
                </div>
                <input type="hidden" name="role" :value="formData.role" />
            </div>

            <!-- Step 2: Personal Information -->
            <div v-show="currentStep === 2" class="flex min-w-0 flex-col gap-6">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="first_name">First name</Label>
                        <Input
                            id="first_name"
                            v-model="formData.first_name"
                            type="text"
                            name="first_name"
                            autocomplete="given-name"
                            placeholder="Juan"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="middle_name">Middle name</Label>
                        <Input
                            id="middle_name"
                            v-model="formData.middle_name"
                            type="text"
                            name="middle_name"
                            autocomplete="additional-name"
                            placeholder="Andrade"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="surname">Surname</Label>
                        <Input
                            id="surname"
                            v-model="formData.surname"
                            type="text"
                            name="surname"
                            autocomplete="family-name"
                            placeholder="Dela Cruz"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name_extension">Name extension</Label>
                        <Input
                            id="name_extension"
                            v-model="formData.name_extension"
                            type="text"
                            name="name_extension"
                            placeholder="Jr., Sr., III"
                        />
                    </div>
                </div>

                <!-- Sex & Date of Birth for all roles -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label>Sex</Label>
                        <Select
                            :model-value="formData.sex"
                            @update:model-value="
                                (val) =>
                                    (formData.sex = val
                                        ? (val as 'male' | 'female')
                                        : '')
                            "
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select sex" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="male">Male</SelectItem>
                                <SelectItem value="female">Female</SelectItem>
                            </SelectContent>
                        </Select>
                        <input type="hidden" name="sex" :value="formData.sex" />
                    </div>
                    <div class="grid min-w-0 gap-2">
                        <Label for="date_of_birth">Date of birth</Label>
                        <Input
                            id="date_of_birth"
                            v-model="formData.date_of_birth"
                            type="date"
                            name="date_of_birth"
                            placeholder="mm/dd/yyyy"
                            class="w-full min-w-0"
                        />
                    </div>
                </div>
            </div>

            <!-- Step 3: Employment (Employee only) -->
            <div
                v-if="isEmployee"
                v-show="currentStep === 3"
                class="flex min-w-0 flex-col gap-6"
            >
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="date_hired">Date hired</Label>
                        <Input
                            id="date_hired"
                            v-model="formData.date_hired"
                            type="date"
                            name="date_hired"
                            placeholder="mm/dd/yyyy"
                            class="w-full"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>Division</Label>
                        <Select
                            :model-value="
                                formData.division_id?.toString() ?? ''
                            "
                            @update:model-value="
                                (val) =>
                                    (formData.division_id = val
                                        ? Number(val)
                                        : null)
                            "
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select division" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="d in organizationalStructure"
                                    :key="d.id"
                                    :value="d.id.toString()"
                                >
                                    {{ d.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <input
                            type="hidden"
                            name="division_id"
                            :value="formData.division_id ?? ''"
                        />
                    </div>
                    <div v-if="subdivisionOptions.length" class="grid gap-2">
                        <Label>Subdivision</Label>
                        <Select
                            :model-value="
                                formData.subdivision_id?.toString() ?? ''
                            "
                            @update:model-value="
                                (val) =>
                                    (formData.subdivision_id = val
                                        ? Number(val)
                                        : null)
                            "
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select subdivision" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="s in subdivisionOptions"
                                    :key="s.id"
                                    :value="s.id.toString()"
                                >
                                    {{ s.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <input
                            type="hidden"
                            name="subdivision_id"
                            :value="formData.subdivision_id ?? ''"
                        />
                    </div>
                    <div v-if="sectionOptions.length" class="grid gap-2">
                        <Label>Section</Label>
                        <Select
                            :model-value="formData.section_id?.toString() ?? ''"
                            @update:model-value="
                                (val) =>
                                    (formData.section_id = val
                                        ? Number(String(val))
                                        : null)
                            "
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select section" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="s in sectionOptions"
                                    :key="s.id"
                                    :value="s.id.toString()"
                                >
                                    {{ s.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <input
                            type="hidden"
                            name="section_id"
                            :value="formData.section_id ?? ''"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="position">Position</Label>
                        <Input
                            id="position"
                            v-model="formData.position"
                            type="text"
                            name="position"
                            placeholder="e.g. Administrative Assistant"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>Classification</Label>
                        <Select
                            :model-value="formData.classification"
                            @update:model-value="
                                (val) =>
                                    (formData.classification = val as string)
                            "
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue
                                    placeholder="Select classification"
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Regular">Regular</SelectItem>
                                <SelectItem value="Detailed"
                                    >Detailed</SelectItem
                                >
                                <SelectItem value="COS">COS</SelectItem>
                            </SelectContent>
                        </Select>
                        <input
                            type="hidden"
                            name="classification"
                            :value="formData.classification"
                        />
                    </div>
                </div>
            </div>

            <!-- Credentials step (step 4 for employee, step 3 for hr/admin) -->
            <div v-show="isCredentialsStep" class="flex min-w-0 flex-col gap-6">
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="username">Username</Label>
                        <Input
                            id="username"
                            v-model="formData.username"
                            type="text"
                            name="username"
                            required
                            autocomplete="username"
                            placeholder="juandelacruz"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="formData.email"
                            type="email"
                            name="email"
                            required
                            autocomplete="email"
                            placeholder="name@example.com"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="password">Password</Label>
                        <PasswordInput
                            id="password"
                            v-model="formData.password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Enter your password"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="password_confirmation"
                            >Confirm Password</Label
                        >
                        <PasswordInput
                            id="password_confirmation"
                            v-model="formData.password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                        />
                    </div>
                </div>
            </div>

            <!-- Navigation buttons (only after step 1) -->
            <div v-if="currentStep > 1" class="mt-4 flex justify-between gap-4">
                <Button type="button" variant="outline" @click="prevStep">
                    Back
                </Button>
                <Button
                    v-if="!isCredentialsStep"
                    type="button"
                    @click="nextStep"
                    :disabled="!canProceedNext"
                >
                    Next
                </Button>
                <Button
                    v-else
                    type="submit"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Create account
                </Button>
            </div>
        </Form>

        <div class="mt-6 text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink
                :href="login().url"
                class="font-medium text-brand hover:text-brand-dark"
            >
                Sign in
            </TextLink>
        </div>
    </AuthBase>
</template>
