import { Outlet } from 'react-router';
import { Link } from 'react-router-dom';
import { useCallback } from 'react';
import useRoutes from '../../hooks/useRoutes';
import useUser from '../../hooks/useUser';
import { postLogout } from '../../apis/apiUser';
import useLanguage from '../../hooks/useLanguage';

const LayoutMain = () => {
  const { routes } = useRoutes();
  const user = useUser();
  const { lang, setLanguage } = useLanguage();

  const onLogout = useCallback((event) => {
    event.preventDefault();
    event.stopPropagation();
    postLogout()
      .then((resp) => {
        if (resp.success) {
          user.logout();
        }
      });
  }, [user]);

  const onChangeLanguage = useCallback((event, l) => {
    event.preventDefault();
    event.stopPropagation();
    setLanguage(l);
  }, [setLanguage]);

  const Languages = () => ['it', 'en', 'fr', 'de'].map((l) => (lang === l
    ? (<span key={l} className="demo-navlink">{l}</span>)
    : (<Link key={l} className="demo-navlink" to={routes.home.to} onClick={(event) => onChangeLanguage(event, l)}>{l}</Link>)));

  return (
    <div className="container">
      <div className="clearfix">
        <div className="float-start">
          <Link className="demo-navlink" to={routes.home.to}>Home</Link>
          <Link className="demo-navlink" to={routes.publicPage.to}>Public Page</Link>
          <Link className="demo-navlink" to={routes.protectedPage.to}>Protected Page</Link>
          (all links)
        </div>
        <div className="float-end">
          <Languages />
          {user.isGuest
            ? (<Link className="demo-navlink" to={routes.login.to}>Login</Link>)
            : (<Link className="demo-navlink" to={routes.home.to} onClick={onLogout}>Logout</Link>)}
        </div>
      </div>
      <div>
        {user.hasPerm(routes.home.perm) && (
          <Link className="demo-navlink" to={routes.home.to}>Home</Link>)}
        {user.hasPerm(routes.publicPage.perm) && (
          <Link className="demo-navlink" to={routes.publicPage.to}>Public Page</Link>)}
        {user.hasPerm(routes.protectedPage.perm) && (
          <Link className="demo-navlink" to={routes.protectedPage.to}>Protected Page</Link>)}
        (with perms)
      </div>
      <Outlet />
    </div>
  );
};

LayoutMain.propTypes = {
};

export default LayoutMain;
