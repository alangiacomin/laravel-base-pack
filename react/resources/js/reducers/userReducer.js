const actionPrefix = 'USER';
const actions = {
  LOGIN_FAILED: `${actionPrefix}/LOGIN_FAILED`,
  LOGGED_IN: `${actionPrefix}/LOGGED_IN`,
  LOGGED_OUT: `${actionPrefix}/LOGGED_OUT`,
  SET_DATA: `${actionPrefix}/SET_DATA`,
};

const reducer = (state = {}, action = '') => {
  switch ((action || {}).type) {
    case actions.LOGGED_IN:
      return {
        ...state,
        ...action.payload,
      };
    case actions.LOGGED_OUT:
      return {
      };
    case actions.SET_DATA:
      return {
        ...state,
        ...action.payload,
      };
    default:
      return state;
  }
};

export default reducer;
export {
  actions as USER_ACTIONS,
};
