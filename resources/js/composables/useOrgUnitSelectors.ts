import { computed, onMounted, ref, watch } from 'vue';

type Division = {
    id: number;
    name: string;
};

type Subdivision = {
    id: number;
    name: string;
    division_id: number;
};

type Section = {
    id: number;
    name: string;
    division_id: number;
    subdivision_id: number | null;
};

export function useOrgUnitSelectors() {
    const divisions = ref<Division[]>([]);
    const subdivisions = ref<Subdivision[]>([]);
    const sections = ref<Section[]>([]);

    const divisionId = ref<number | null>(null);
    const subdivisionId = ref<number | null>(null);
    const sectionId = ref<number | null>(null);

    const subdivisionOptions = computed(() => {
        if (!divisionId.value) return [];

        return subdivisions.value.filter(
            (subdivision) => subdivision.division_id === divisionId.value,
        );
    });

    const sectionOptions = computed(() => {
        if (!divisionId.value) return [];

        if (subdivisionId.value) {
            return sections.value.filter(
                (section) => section.subdivision_id === subdivisionId.value,
            );
        }

        if (subdivisionOptions.value.length === 0) {
            return sections.value.filter(
                (section) =>
                    section.division_id === divisionId.value &&
                    section.subdivision_id === null,
            );
        }

        return [];
    });

    watch(divisionId, (newDivisionId, oldDivisionId) => {
        if (newDivisionId === oldDivisionId) {
            return;
        }

        subdivisionId.value = null;
        sectionId.value = null;
    });

    watch(subdivisionId, (newSubdivisionId, oldSubdivisionId) => {
        if (newSubdivisionId === oldSubdivisionId) {
            return;
        }

        sectionId.value = null;
    });

    onMounted(() => {
        divisions.value = [
            { id: 1, name: 'Chief of Hospital Offices Division' },
            { id: 2, name: 'Treatment and Rehabilitation Division' },
            { id: 3, name: 'Finance and Administrative Division' },
        ];

        subdivisions.value = [
            {
                id: 1,
                name: 'Non-Residential Treatment & Rehabilitation',
                division_id: 2,
            },
            {
                id: 2,
                name: 'Residential Treatment & Rehabilitation',
                division_id: 2,
            },
            { id: 3, name: 'Ancillary Services', division_id: 2 },
        ];

        sections.value = [
            { id: 1, name: 'Legal Unit', subdivision_id: null, division_id: 1 },
            {
                id: 2,
                name: 'Planning Unit',
                subdivision_id: null,
                division_id: 1,
            },
            {
                id: 3,
                name: 'Information and Communications Technology Unit',
                subdivision_id: null,
                division_id: 1,
            },
            {
                id: 4,
                name: 'Public Health Unit',
                subdivision_id: null,
                division_id: 1,
            },
            {
                id: 5,
                name: 'Quality Strategic Management Office',
                subdivision_id: null,
                division_id: 1,
            },
            {
                id: 6,
                name: 'Health Information Management Section/TRAIS',
                subdivision_id: null,
                division_id: 1,
            },

            {
                id: 7,
                name: 'Medical Section',
                subdivision_id: 1,
                division_id: 2,
            },
            {
                id: 8,
                name: 'Nursing Section',
                subdivision_id: 1,
                division_id: 2,
            },
            {
                id: 9,
                name: 'Medical Social Work Section',
                subdivision_id: 1,
                division_id: 2,
            },
            {
                id: 10,
                name: 'Psychological Section',
                subdivision_id: 1,
                division_id: 2,
            },
            {
                id: 11,
                name: 'Dormitory Management Section',
                subdivision_id: 1,
                division_id: 2,
            },

            {
                id: 12,
                name: 'Medical Section',
                subdivision_id: 2,
                division_id: 2,
            },
            {
                id: 13,
                name: 'Nursing Section',
                subdivision_id: 2,
                division_id: 2,
            },
            {
                id: 14,
                name: 'Medical Social Work Section',
                subdivision_id: 2,
                division_id: 2,
            },
            {
                id: 15,
                name: 'Psychological Section',
                subdivision_id: 2,
                division_id: 2,
            },
            {
                id: 16,
                name: 'Dormitory Management Section',
                subdivision_id: 2,
                division_id: 2,
            },

            {
                id: 17,
                name: 'Nutrition and Dietetics Section',
                subdivision_id: 3,
                division_id: 2,
            },
            {
                id: 18,
                name: 'Clinical Laboratory Section',
                subdivision_id: 3,
                division_id: 2,
            },

            {
                id: 19,
                name: 'Human Resource Management Section',
                subdivision_id: null,
                division_id: 3,
            },
            {
                id: 20,
                name: 'Procurement Section',
                subdivision_id: null,
                division_id: 3,
            },
            {
                id: 21,
                name: 'Materials Management Section',
                subdivision_id: null,
                division_id: 3,
            },
            {
                id: 22,
                name: 'General Services Section',
                subdivision_id: null,
                division_id: 3,
            },
            {
                id: 23,
                name: 'Accounting Section',
                subdivision_id: null,
                division_id: 3,
            },
            {
                id: 24,
                name: 'Budget Section',
                subdivision_id: null,
                division_id: 3,
            },
            {
                id: 25,
                name: 'Cash, Billing, & Claim Section',
                subdivision_id: null,
                division_id: 3,
            },
        ];
    });

    function resetOrgUnitSelectors(): void {
        divisionId.value = null;
        subdivisionId.value = null;
        sectionId.value = null;
    }

    return {
        divisions,
        subdivisions,
        sections,
        divisionId,
        subdivisionId,
        sectionId,
        subdivisionOptions,
        sectionOptions,
        resetOrgUnitSelectors,
    };
}
