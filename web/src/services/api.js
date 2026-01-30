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

  async getDocuments() {
    const response = await apiFetch("/api/documents");
    return handleResponse(response);
  },

  async getDocument(id) {
    const response = await apiFetch(`/api/documents/${id}`);
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

  async getShares(documentId) {
    const response = await apiFetch(`/api/documents/${documentId}/shares`);
    return handleResponse(response);
  },

  async deleteShare(documentId, shareId) {
    const response = await apiFetch(
      `/api/documents/${documentId}/shares/${shareId}`,
      {
        method: "DELETE",
      },
    );
    return handleResponse(response);
  },

  async createFileGeneration(templateId, data, format = "pdf") {
    const response = await apiFetch("/api/file-generations", {
      method: "POST",
      body: { template_id: templateId, data, format },
    });
    return handleResponse(response);
  },
};
