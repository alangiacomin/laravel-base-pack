import { useMemo } from 'react';
import useLanguage from '../../../hooks/useLanguage';

const Home = () => {
  const { loadTranslation } = useLanguage();
  const t = useMemo(() => loadTranslation('app'), [loadTranslation]);
  return (
    <>
      <h1>Home Page</h1>
      <p>{t('welcome')}</p>
    </>
  );
};

export default Home;
