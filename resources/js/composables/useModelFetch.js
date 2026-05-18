/**
 * useModelFetch — composable for fetching AI model lists via the backend proxy.
 *
 * Usage:
 *   const { models, fetchingModels, fetchError, fetchModels, clearModels } = useModelFetch();
 *   await fetchModels(provider, apiKey);
 */

import { ref } from 'vue';
import api from '../services/api';

export function useModelFetch() {
    const models        = ref([]);
    const fetchingModels = ref(false);
    const fetchError    = ref('');

    /**
     * Fetch models for the given provider + api_key from the backend proxy.
     * @param {string} provider  - e.g. 'openai', 'anthropic', 'gemini', 'openrouter'
     * @param {string} apiKey    - the raw API key typed by the user
     */
    const fetchModels = async (provider, apiKey) => {
        if (!apiKey || !provider || provider === 'custom') {
            models.value   = [];
            fetchError.value = provider === 'custom'
                ? 'Custom providers do not support automatic model discovery.'
                : '';
            return;
        }

        fetchingModels.value = true;
        fetchError.value     = '';
        models.value         = [];

        try {
            const { data } = await api.post('/models/fetch', {
                provider,
                api_key: apiKey,
            });
            models.value = data.models ?? [];

            if (models.value.length === 0) {
                fetchError.value = 'No models returned. Check your API key and provider.';
            }
        } catch (err) {
            let msg = 'Failed to fetch models. Please verify your API key.';
            if (err.response?.data?.details) {
                msg = err.response.data.details;
            } else if (err.response?.data?.message) {
                msg = err.response.data.message;
            } else if (err.response?.data?.error) {
                msg = err.response.data.error;
            }
            fetchError.value = msg;
            models.value     = [];
        } finally {
            fetchingModels.value = false;
        }
    };

    const clearModels = () => {
        models.value     = [];
        fetchError.value = '';
    };

    return {
        models,
        fetchingModels,
        fetchError,
        fetchModels,
        clearModels,
    };
}
