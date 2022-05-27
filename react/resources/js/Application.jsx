import { Provider } from 'react-redux';
import PropTypes from 'prop-types';
import configureStore from './configureStore';
import ApplicationContent from './ApplicationContent';

const Application = ({ userData }) => {
  const { store } = configureStore({
    user: userData || {},
  });

  return (
    <Provider store={store}>
      <ApplicationContent />
    </Provider>
  );
};

Application.propTypes = {
  userData: PropTypes.shape({
    id: PropTypes.number,
  }).isRequired,
};

export default Application;
