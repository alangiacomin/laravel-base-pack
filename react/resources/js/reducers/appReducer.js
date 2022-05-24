const actionPrefix = 'APP';
const actions = {
  SET_LANGUAGE: `${actionPrefix}/SET_LANGUAGE`,
  SET_TRANSLATION: `${actionPrefix}/SET_TRANSLATION`,
};

const initialState = {
  lang: '',
  translation: {},
};

const reducer = (state = initialState, action = {}) => {
  switch ((action || {}).type) {
    case actions.SET_LANGUAGE: return setLanguage(state, action);
    case actions.SET_TRANSLATION: return setTranslation(state, action);
    default: return state;
  }
};

const setLanguage = (state, action) => ({
  ...state,
  lang: action.payload,
});

const setTranslation = (state, action) => ({
  ...state,
  translation: {
    ...state.translation,
    [action.payload.locale]: {
      ...state.translation[action.payload.locale],
      [action.payload.namespace]: action.payload.values,
    },
  },
});

export default reducer;
export {
  actions as APP_ACTIONS,
};
