import api from './api.js';

export const botService = {
    getBots:       ()        => api.get('/bots'),
    getBot:        (id)      => api.get(`/bots/${id}`),
    createBot:     (data)    => api.post('/bots', data),
    updateBot:     (id, data)=> api.put(`/bots/${id}`, data),
    deleteBot:     (id)      => api.delete(`/bots/${id}`),

    // Training documents
    getDocuments:    (botId)       => api.get(`/bots/${botId}/documents`),
    uploadDocument:  (botId, data) => api.post(`/bots/${botId}/documents`, data),
    deleteDocument:  (botId, docId)=> api.delete(`/bots/${botId}/documents/${docId}`),
};
