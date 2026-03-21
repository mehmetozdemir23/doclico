import { apiFetch } from "./apiClient";

async function handleResponse(response) {
  if (!response.ok) {
    const data = await response.json().catch(() => ({}));
    throw new Error(data.message || "Une erreur est survenue");
  }
  return response.json();
}

export const api = {
  async getTemplates() {
    const response = await apiFetch("/api/templates");
    return handleResponse(response);
  },

  async getTemplate(type) {
    const response = await apiFetch(`/api/templates/${type}`);
    return handleResponse(response);
  },

  async createDocument(templateId, name, data, clientId = null) {
    const response = await apiFetch("/api/documents", {
      method: "POST",
      body: { template_id: templateId, name, data, client_id: clientId || null },
    });
    return handleResponse(response);
  },

  async getDocuments({ page = 1, sortBy = 'createdAt', sortDir = 'desc', templateTypes = [], clientId = null } = {}) {
    const params = new URLSearchParams({ page, sort_by: sortBy, sort_dir: sortDir });
    templateTypes.forEach(t => params.append('type[]', t));
    if (clientId) params.set('client_id', clientId);
    const response = await apiFetch(`/api/documents?${params}`);
    return handleResponse(response);
  },

  async deleteDocument(id) {
    const response = await apiFetch(`/api/documents/${id}`, {
      method: "DELETE",
    });
    return handleResponse(response);
  },

  async shareDocument(documentId, expiresIn) {
    const response = await apiFetch(`/api/documents/${documentId}/shares`, {
      method: "POST",
      body: { expires_in: expiresIn },
    });
    return handleResponse(response);
  },

  async notifyShareRecipient(shareId) {
    const response = await apiFetch(`/api/shares/${shareId}/notify`, {
      method: "POST",
    });
    return handleResponse(response);
  },

  async getShares(documentId) {
    const response = await apiFetch(`/api/documents/${documentId}/shares`);
    return handleResponse(response);
  },

  async deleteShare(shareId) {
    const response = await apiFetch(`/api/shares/${shareId}`, {
      method: "DELETE",
    });
    return handleResponse(response);
  },

  async getClients() {
    const response = await apiFetch("/api/clients");
    return handleResponse(response);
  },

  async createClient(data) {
    const response = await apiFetch("/api/clients", {
      method: "POST",
      body: data,
    });
    return handleResponse(response);
  },

  async updateClient(id, data) {
    const response = await apiFetch(`/api/clients/${id}`, {
      method: "PUT",
      body: data,
    });
    return handleResponse(response);
  },

  async deleteClient(id) {
    const response = await apiFetch(`/api/clients/${id}`, {
      method: "DELETE",
    });
    return handleResponse(response);
  },

  async exportUserData() {
    const response = await apiFetch("/api/profile/export");
    if (!response.ok) throw new Error("Erreur lors de l'export des données");
    const blob = await response.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `doclico-export-${new Date().toISOString().slice(0, 10)}.json`;
    a.click();
    URL.revokeObjectURL(url);
  },

  async getShareInfo(token) {
    const response = await apiFetch(`/api/share/${token}/info`);
    if (response.status === 404) throw Object.assign(new Error("Lien introuvable"), { status: 404 });
    if (response.status === 410) throw Object.assign(new Error("Lien expiré"), { status: 410 });
    if (!response.ok) throw new Error("Une erreur est survenue");
    return response.json();
  },

  async downloadSharedDocument(token) {
    return `${import.meta.env.VITE_BACKEND_URL}/api/share/${token}`;
  },

  async deleteAccount() {
    const response = await apiFetch("/api/user", { method: "DELETE" });
    return handleResponse(response);
  },

  getDocumentDownloadUrl(documentId, format = "pdf") {
    return `${import.meta.env.VITE_BACKEND_URL}/api/documents/${documentId}/download?format=${format}`;
  },
};
