import { configureStore as toolkitConfigureStore } from '@reduxjs/toolkit';
import appReducer from './reducers/appReducer';
import userReducer from './reducers/userReducer';

const configureStore = (preloadedState) => {
  const store = toolkitConfigureStore({
    reducer: {
      app: appReducer,
      user: userReducer,
    },
    preloadedState,
  });
  return {
    store,
  };
};

export default configureStore;
