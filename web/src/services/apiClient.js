const BASE_URL = import.meta.env.VITE_BACKEND_URL;

async function getCsrfCookie() {
  await fetch(`${BASE_URL}/sanctum/csrf-cookie`, {
    credentials: "include",
  });
}

function getXsrfToken() {
  const cookies = document.cookie.split(";");
  for (let cookie of cookies) {
    const [name, value] = cookie.trim().split("=");
    if (name === "XSRF-TOKEN") {
      return decodeURIComponent(value);
    }
  }
  return null;
}

export async function apiFetch(url, options = {}) {
  const { method = "GET", body, ...fetchOptions } = options;

  if (["POST", "PUT", "PATCH", "DELETE"].includes(method.toUpperCase())) {
    await getCsrfCookie();
  }

  const token = getXsrfToken();
  const isFormData = body instanceof FormData;

  try {
    const response = await fetch(`${BASE_URL}${url}`, {
      method,
      credentials: "include",
      headers: {
        ...(isFormData ? {} : { "Content-Type": "application/json" }),
        Accept: "application/json",
        ...(token ? { "X-XSRF-TOKEN": token } : {}),
        ...fetchOptions.headers,
      },
      ...(body ? { body: isFormData ? body : JSON.stringify(body) } : {}),
      ...fetchOptions,
    });

    if (response.status === 401 && url !== "/api/user" && window.location.pathname !== "/login") {
      window.location.href = "/login";
      return response;
    }

    return response;
  } catch {
    throw new Error("Impossible de contacter le serveur. Vérifiez votre connexion.");
  }
}
