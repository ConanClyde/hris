export type UseInitialsReturn = {
    getInitials: (fullName?: string) => string;
    getInitialsFromName: (params: {
        first_name?: string | null;
        last_name?: string | null;
    }) => string;
};

export function getInitials(fullName?: string): string {
    if (!fullName) return '';

    const suffixes = new Set([
        'jr',
        'jr.',
        'sr',
        'sr.',
        'ii',
        'iii',
        'iv',
        'v',
    ]);

    const names = fullName.trim().split(/\s+/).filter(Boolean);

    if (names.length === 0) return '';
    if (names.length === 1) return names[0].charAt(0).toUpperCase();

    let last = names[names.length - 1];
    while (names.length > 1 && suffixes.has(last.toLowerCase())) {
        names.pop();
        last = names[names.length - 1];
    }

    return `${names[0].charAt(0)}${last.charAt(0)}`.toUpperCase();
}

export function getInitialsFromName(params: {
    first_name?: string | null;
    last_name?: string | null;
}): string {
    const first = params.first_name?.[0] || '';
    const last = params.last_name?.[0] || '';
    const initials = (first + last).toUpperCase();
    return initials || getInitials(params.first_name || '');
}

export function useInitials(): UseInitialsReturn {
    return { getInitials, getInitialsFromName };
}
