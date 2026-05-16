import api from './api.js';

export const chatService = {
    // Conversations
    getConversations:    ()           => api.get('/conversations'),
    getConversation:     (id)         => api.get(`/conversations/${id}`),
    createConversation:  (data)       => api.post('/conversations', data),
    deleteConversation:  (id)         => api.delete(`/conversations/${id}`),

    // Messages
    getMessages:  (conversationId)        => api.get(`/conversations/${conversationId}/messages`),
    sendMessage:  (conversationId, data)  => api.post(`/conversations/${conversationId}/messages`, data),
};
