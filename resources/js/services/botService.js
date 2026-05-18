import api from './api.js';

export const botService = {
    getBots:       ()        => api.get('/bots'),
    getBot:        (id)      => api.get(`/bots/${id}`),
    createBot:     (data)    => api.post('/bots', data),
    updateBot:     (id, data)=> api.put(`/bots/${id}`, data),
    deleteBot:     (id)      => api.delete(`/bots/${id}`),

    // Training data (Knowledge Base)
    getTrainingData:   (botId)       => api.get(`/bots/${botId}/training`),
    uploadTrainingFile: (botId, formData) => api.post(`/bots/${botId}/training`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    }),
    scrapeWebsite:     (botId, url)  => api.post(`/bots/${botId}/training`, { url }),
    deleteTrainingData: (id)         => api.delete(`/bots/training/${id}`),
};
