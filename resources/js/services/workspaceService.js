import api from './api.js';

export const workspaceService = {
    getWorkspaces:     ()              => api.get('/workspaces'),
    getWorkspace:      (id)            => api.get(`/workspaces/${id}`),
    createWorkspace:   (data)          => api.post('/workspaces', data),
    updateWorkspace:   (id, data)      => api.put(`/workspaces/${id}`, data),
    deleteWorkspace:   (id)            => api.delete(`/workspaces/${id}`),

    // Members
    getMembers:    (workspaceId)            => api.get(`/workspaces/${workspaceId}/members`),
    inviteMember:  (workspaceId, data)      => api.post(`/workspaces/${workspaceId}/members`, data),
    removeMember:  (workspaceId, userId)    => api.delete(`/workspaces/${workspaceId}/members/${userId}`),
};
