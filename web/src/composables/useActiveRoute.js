import { useRoute } from "vue-router";

export function useActiveRoute() {
  const route = useRoute();

  const isActive = (routeName) =>
    route.name === routeName || route.name?.startsWith(routeName + ".");

  return { isActive };
}
