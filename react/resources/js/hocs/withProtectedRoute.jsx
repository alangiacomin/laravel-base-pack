import ErrorBoundary from '../components/ErrorBoundary';
import ProtectedRoute from '../components/ProtectedRoute';

const withProtectedRoute = (Component, route) => function wrapped(props) {
  return (
    <ErrorBoundary>
      <ProtectedRoute {...route}>
        <Component {...props} />
      </ProtectedRoute>
    </ErrorBoundary>
  );
};

export default withProtectedRoute;
