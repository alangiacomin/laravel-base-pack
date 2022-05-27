import { useRoutes as useReactRoutes } from 'react-router';
import LayoutMain from './areas/website/LayoutMain';
import withLazy from './hocs/withLazy';
import withProtectedRoute from './hocs/withProtectedRoute';
import useRoutes from './hooks/useRoutes';
import HomeC from './areas/website/Home';
import PublicPageC from './areas/website/PublicPage';
import ProtectedPageC from './areas/website/ProtectedPage';
import LoginC from './areas/website/Login';

const Routes = () => {
  const { routes } = useRoutes();

  // WebSite
  const Home = withProtectedRoute(() => <HomeC />, routes.home);
  const PublicPage = withProtectedRoute(<PublicPageC />, routes.publicPage);
  const ProtectedPage = withProtectedRoute(<ProtectedPageC />, routes.protectedPage);
  const Login = withProtectedRoute(<LoginC />, routes.login);

  // Admin
  // const Admin = withProtectedRoute(withLazy(() => import('./areas/admin/Admin')), routes.admin);
  // const Perms = withProtectedRoute(withLazy(() => import('./areas/admin/Perms')), routes.admin_perms);
  // const Users = withProtectedRoute(withLazy(() => import('./areas/admin/Users')), routes.admin_users);

  return useReactRoutes([
    {
      path: routes.home.path,
      element: <LayoutMain />,
      children: [
        { path: routes.home.path, element: <Home /> },
        { path: routes.publicPage.path, element: <PublicPage /> },
        { path: routes.protectedPage.path, element: <ProtectedPage /> },
        { path: routes.login.path, element: <Login /> },
        { path: '*', element: <h1>NON TROVATO</h1> },
      ],
    },
    // {
    //   path: routes.admin.path,
    //   element: user.isAdmin ? <LayoutAdmin /> : <LayoutMain />,
    //   children: [
    //     { path: routes.admin.path, element: <Admin /> },
    //     ...(user.isAdmin
    //       ? [
    //         {
    //           path: routes.admin_users.path,
    //           element: <Users />,
    //           children: [
    //             { path: 'u1', element: <User /> },
    //           ],
    //         },
    //         { path: routes.admin_perms.path, element: <Perms /> },
    //       ]
    //       : [
    //         { path: `${routes.admin.path}/*`, element: <Admin /> },
    //       ]),
    //   ],
    //   children2: user.isAdmin
    //     ? [
    //       { path: routes.admin.path, element: <Admin /> },
    //       {
    //         path: routes.admin_users.path,
    //         element: <Users />,
    //         children: [
    //           { path: 'u1', element: <User /> },
    //         ],
    //       },
    //       { path: routes.admin_perms.path, element: <Perms /> },
    //     ]
    //     : [
    //       { path: routes.admin.path, element: <Admin /> },
    //       { path: `${routes.admin.path}/*`, element: <Admin /> },
    //     ],
    // },

  ]);
};

export default Routes;
