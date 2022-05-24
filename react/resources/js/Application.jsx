import { Provider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import PropTypes from 'prop-types';
import { useCallback, useEffect } from 'react';
import ErrorBoundary from './components/ErrorBoundary';
import configureStore from './configureStore';
import Routes from './Routes';
import { APP_ACTIONS } from './reducers/appReducer';

const Application = ({ userData }) => {
  const { store } = configureStore({ user: userData || {} });
  // const [userLoaded, setUserLoaded] = useState(false);

  const setAppLanguage = useCallback(
    () => {
      // devo farlo così senza useDispatch() perché ancora non sono dentro il <Provider />
      store.dispatch({ type: APP_ACTIONS.SET_LANGUAGE, payload: 'it' });
      // store.dispatch({ type: APP_ACTIONS.SET_LANGUAGE, payload: config.app.defaultLanguage });
    },
    [store],
  );

  // const setUserData = useCallback(() => {
  //   console.log('app setUserData');
  //   if (!userLoaded) {
  //     console.log('app setUserData setting');
  //     // devo farlo così senza useDispatch() perché ancora non sono dentro il <Provider />
  //     if (userData.id) {
  //       store.dispatch({ type: USER_ACTIONS.LOGGED_IN, payload: userData })
  //         .then(() => {
  //           setUserLoaded(true);
  //         });
  //     }
  //   }
  // }, [userData, userLoaded, store, setUserLoaded]);

  // const preloadAppLanguages = useCallback(() => {
  //   (config.app.preloadLanguages || []).forEach(({ lang, namespaces }) => {
  //     (namespaces || []).forEach((namespace) => {
  //       getTranslation(lang, namespace)
  //         .then((response) => {
  //           store.dispatch({
  //             type: APP_ACTIONS.SET_TRANSLATION,
  //             payload: { locale: lang, namespace, values: response },
  //           });
  //         });
  //     });
  //   });
  // }, [store]);

  useEffect(() => {
    // setAppLanguage();
    console.log('app use effect');
    // setUserData();
    // setTimeout(preloadAppLanguages, 1000);
  }, [setAppLanguage]);

  // console.log('app render', userLoaded);
  return (
    <Provider store={store}>
      <BrowserRouter>
        <ErrorBoundary>
          <Routes />
        </ErrorBoundary>
      </BrowserRouter>
    </Provider>
  );
};

Application.propTypes = {
  userData: PropTypes.shape({
    id: PropTypes.number,
  }).isRequired,
};

export default Application;
