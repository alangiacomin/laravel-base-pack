import { useCallback } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { APP_ACTIONS } from '../reducers/appReducer';
import { getTranslation, postLocale } from '../apis/apiLanguage';

const useLanguage = () => {
  const lang = useSelector((state) => state.app.lang);
  const dispatch = useDispatch();
  const translation = useSelector((state) => state.app.translation);

  const setLanguage = useCallback(
    (l) => {
      postLocale(l).then(() => {
        dispatch({ type: APP_ACTIONS.SET_LANGUAGE, payload: l });
      });
    },
    [dispatch],
  );

  const setTranslation = useCallback(
    (locale, namespace, values) => {
      dispatch({
        type: APP_ACTIONS.SET_TRANSLATION,
        payload: { locale, namespace, values },
      });
    },
    [dispatch],
  );

  const loadTranslation = useCallback((namespace) => {
    if (lang && (!translation || !translation[lang] || !translation[lang][namespace])) {
      getTranslation(namespace, lang)
        .then((response) => {
          setTranslation(lang, namespace, response);
        });
    }

    return (key) => {
      if (!translation || !translation[lang] || !translation[lang][namespace]) {
        return '';
      }
      return translation[lang][namespace][key] || `{${key}}`;
    };
  }, [lang, setTranslation, translation]);

  return {
    lang,
    setLanguage,
    setTranslation,
    loadTranslation,
  };
};

export default useLanguage;
