import { BrowserRouter } from 'react-router-dom';
import { useCallback, useEffect } from 'react';
import ErrorBoundary from './components/ErrorBoundary';
import Routes from './Routes';
import { languageConfig } from './config';
import useLanguage from './hooks/useLanguage';
import { getTranslation } from './apis/apiLanguage';

const ApplicationContent = () => {
  const { lang, setLanguage, setTranslation } = useLanguage();

  const preloadAppLanguages = useCallback(() => {
    (languageConfig.preload || []).forEach(({ lang: l, namespaces }) => {
      (namespaces || []).forEach((namespace) => {
        getTranslation(namespace, l)
          .then((response) => {
            setTranslation(l, namespace, response);
          });
      });
    });
  }, [setTranslation]);

  useEffect(() => {
    if (!lang) {
      setLanguage(languageConfig.default);
    }
  }, [lang, setLanguage]);

  useEffect(() => {
    setTimeout(() => {
      preloadAppLanguages();
    }, 2000);
  }, [preloadAppLanguages]);

  return (
    <BrowserRouter>
      <ErrorBoundary>
        <Routes />
      </ErrorBoundary>
    </BrowserRouter>
  );
};

export default ApplicationContent;
