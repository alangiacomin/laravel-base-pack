import PERM from '../models/perms';

const routes = {

  // website

  home: {
    title: 'home',
    to: '/',
    component: 'Home',
  },
  publicPage: {
    title: 'publicPage',
    to: '/public-page',
    component: 'PublicPage',
  },
  protectedPage: {
    title: 'protectedPage',
    to: '/protected-page',
    component: 'ProtectedPage',
    perm: PERM.ProtectedPageView,
  },
  login: {
    title: 'login',
    to: '/login',
    component: 'Login',
    perm: PERM.GuestsOnly,
  },
  logout: {
    title: 'logout',
    to: '/logout',
    perm: PERM.RegisteredOnly,
  },

  // admin

  admin: {
    title: 'admin',
    to: '/admin',
    component: 'Admin',
    perm: 'admin',
    subRoutes: {
      users: {
        title: 'users',
        to: '/users',
        component: 'Admin',
        // perm: PERM.AdminUsers,
      },
      perms: {
        title: 'perms',
        to: '/perms',
        component: 'Admin',
        // perm: PERM.AdminPerms,
      },
    },
  },
};

export default routes;
