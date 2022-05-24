import configRoutes from '../config/routes';

const useRoutes = () => {
  const getRoutesFromConfig = (routesConfig, parentRoute) => {
    let routes = {};
    Object.entries(routesConfig ?? {}).forEach((value) => {
      const [key, route] = value;
      const routeKey = (parentRoute ? `${parentRoute.id}_` : '') + key;

      const defaultRoute = {
        exact: true,
        isActive: (match) => match !== null,
        path: (parentRoute ? parentRoute.to : '') + route.to,
      };

      const newRoute = {
        id: routeKey,
        ...defaultRoute,
        ...route,
        to: (parentRoute ? parentRoute.to : '') + route.to,
      };
      routes = {
        ...routes,
        ...getRoutesFromConfig(newRoute.subRoutes, newRoute),
      };
      delete newRoute.subRoutes;
      routes[routeKey] = newRoute;
    });
    return routes;
  };

  const routes = getRoutesFromConfig(configRoutes);

  return {
    configRoutes,
    routes,
  };
};

export default useRoutes;
