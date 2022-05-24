import { HttpRequest } from '@alangiacomin/js-utils';

const postLogin = (email, password) => HttpRequest.post(
  '/auth/login',
  { email, password },
);

const postLogout = () => HttpRequest.post(
  '/auth/logout',
  { },
);

export {
  postLogin,
  postLogout,
};
