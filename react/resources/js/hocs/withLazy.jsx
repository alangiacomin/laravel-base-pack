import { lazy, Suspense } from 'react';
import ErrorBoundary from '../components/ErrorBoundary';

const withLazy = (component, fallback) => function wrapped(props) {
  const Component = lazy(component);
  return (
    <ErrorBoundary>
      <Suspense fallback={fallback || null}>
        <Component {...props} />
      </Suspense>
    </ErrorBoundary>
  );
};

export default withLazy;
