import { HttpRequest } from '@alangiacomin/js-utils';

const postLocale = (locale) => HttpRequest.post(
  '/translation/setLocale',
  { locale },
);

const getTranslation = (namespace, locale = null) => HttpRequest.get(
  locale
    ? `/translation/${locale}/${namespace}`
    : `/translation/${namespace}`,
  {},
);

export {
  postLocale,
  getTranslation,
};
