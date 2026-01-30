import { ref, onUnmounted } from "vue";
import { api } from "@/services/api";
import { echo } from "@/services/echo";
import { useAuthStore } from "@/stores/auth";

export function useFileGeneration() {
  const isGenerating = ref(false);
  const status = ref(null);
  const error = ref(null);
  const downloadUrl = ref(null);

  let currentChannel = null;
  let currentGenerationId = null;
  let onCompleteCallback = null;

  function listenForCompletion(id) {
    const authStore = useAuthStore();

    currentGenerationId = id;
    currentChannel = authStore.isAuthenticated
      ? echo.private(`user.${authStore.user.id}`)
      : echo.channel(`file-generation.${id}`);

    currentChannel.listen(".file-generation.completed", (event) => {
      if (event.id === id) {
        status.value = event.status;
        isGenerating.value = false;

        if (event.status === "completed" && event.download_url) {
          downloadUrl.value = event.download_url;
          onCompleteCallback?.(event.download_url);
        } else if (event.status === "failed") {
          error.value = event.error || "Échec de la génération";
        }

        cleanup();
      }
    });
  }

  function cleanup() {
    if (currentChannel && currentGenerationId) {
      const authStore = useAuthStore();
      const channelName = authStore.isAuthenticated
        ? `user.${authStore.user.id}`
        : `file-generation.${currentGenerationId}`;
      echo.leave(channelName);
      currentChannel = null;
      currentGenerationId = null;
    }
  }

  async function generateFile(templateId, data, format = "pdf", onComplete = null) {
    isGenerating.value = true;
    status.value = "pending";
    error.value = null;
    downloadUrl.value = null;
    onCompleteCallback = onComplete;

    try {
      const result = await api.createFileGeneration(templateId, data, format);
      listenForCompletion(result.id);
      return result.id;
    } catch (err) {
      isGenerating.value = false;
      status.value = "failed";
      error.value = err.message || "Erreur lors de la création";
      return null;
    }
  }

  function download() {
    if (downloadUrl.value) {
      window.open(downloadUrl.value, "_blank");
    }
  }

  onUnmounted(() => {
    cleanup();
  });

  return {
    isGenerating,
    status,
    error,
    downloadUrl,
    generateFile,
    download,
  };
}
