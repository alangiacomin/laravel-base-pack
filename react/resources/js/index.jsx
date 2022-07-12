import Application from './Application';
import { getProfile } from './apis/apiAuth';

import * as ReactDOM from 'react-dom/client';

getProfile()
  .then((result) => {
    const userData = result.success ? result.data ?? {} : {};
    ReactDOM
      .createRoot(document.getElementById('app'))
      .render(<Application userData={userData} />);
  })
  .catch((err) => {
    console.log(err);
  });
