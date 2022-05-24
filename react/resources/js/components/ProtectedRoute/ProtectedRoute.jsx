import PropTypes from 'prop-types';
import { Navigate, useLocation } from 'react-router';
import Login from '../../areas/website/Login';
import useUser from '../../hooks/useUser';
import PERM from '../../models/perms';
import useRoutes from '../../hooks/useRoutes';
import Error from '../../areas/error';

const ProtectedRoute = (props) => {
  const {
    perm, group, path, children,
  } = props;

  const location = useLocation();
  const user = useUser();
  const { routes } = useRoutes();

  const isLoginPath = location.pathname.startsWith(routes.login.path);
  const isAdminPath = false; // location.pathname.startsWith(routes.admin.path);

  // Checking groups

  if (group) {
    if (!user.isInGroup(group)) {
      if (user.isGuest) {
        return (<Login refer={isLoginPath ? routes.home.path : location.pathname} />);
      }
      if (isAdminPath && !user.isAdmin) {
        return <Navigate to={routes.home.path} replace />;
      }
      return (<Error errorCode={403} />);
    }
  }

  // Checking perms

  if (perm) {
    if (perm === PERM.GuestsOnly && !user.isGuest) {
      return <Navigate to={routes.home.path} replace />;
    }

    if (perm === PERM.RegisteredOnly && user.isGuest) {
      if (path === routes.logout.path) {
        return <Navigate to={routes.home.path} replace />;
      }
      return (<Login refer={isLoginPath || isAdminPath ? routes.home.path : location.pathname} />);
    }

    if (!user.hasPerm(perm)) {
      if (user.isGuest) {
        return (<Login refer={isLoginPath || isAdminPath ? routes.home.path : location.pathname} />);
      }
      if (isAdminPath && !user.isAdmin) {
        return <Navigate to={routes.home.path} />;
      }
      return (<Error errorCode={403} />);
    }
  }

  // If allowed, view content

  return (children);
};

ProtectedRoute.propTypes = {
  children: PropTypes.oneOfType([
    PropTypes.node,
    PropTypes.arrayOf(PropTypes.node),
  ]).isRequired,
  group: PropTypes.string,
  path: PropTypes.string.isRequired,
  perm: PropTypes.string,
};

ProtectedRoute.defaultProps = {
  group: '',
  perm: '',
};

export default ProtectedRoute;
