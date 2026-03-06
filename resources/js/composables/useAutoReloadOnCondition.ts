import { router } from '@inertiajs/vue3';
import { onBeforeUnmount, type Ref, watch } from 'vue';

export function useAutoReloadOnCondition(
    condition: Ref<boolean>,
    intervalMs = 3000,
) {
    let interval: ReturnType<typeof setInterval> | null = null;

    const stop = () => {
        if (interval) {
            clearInterval(interval);
            interval = null;
        }
    };

    watch(
        condition,
        (value) => {
            if (!value) {
                stop();
                return;
            }

            if (interval) return;

            interval = setInterval(() => {
                router.reload();
            }, intervalMs);
        },
        { immediate: true },
    );

    onBeforeUnmount(stop);

    return { stop };
}
