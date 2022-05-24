import { HttpRequest } from '@alangiacomin/js-utils';

const getProfile = () => HttpRequest.get(
  '/auth/profile',
  {},
);

export {
  // eslint-disable-next-line import/prefer-default-export
  getProfile,
};
