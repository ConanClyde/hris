import { ref } from 'vue';

export function useRequestFeedback() {
    const errorMessages = ref<string[]>([]);

    const setErrors = (errors: Record<string, string>) => {
        errorMessages.value = Object.values(errors).filter(Boolean);
    };

    const setErrorMessage = (message: string) => {
        errorMessages.value = message ? [message] : [];
    };

    const clearErrors = () => {
        errorMessages.value = [];
    };

    return {
        errorMessages,
        setErrors,
        setErrorMessage,
        clearErrors,
    };
}
