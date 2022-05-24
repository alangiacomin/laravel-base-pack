import { useCallback, useState } from 'react';
import { postLogin } from '../../../apis/apiUser';
import { Button, Form } from '../../../../../node_modules/@alangiacomin/ui-components-react';
import Input from '../../../components/Input/Input';
import useUser from '../../../hooks/useUser';

const Login = () => {
  const initialValues = {};
  const [values, setValues] = useState(initialValues);
  const [inputErrors, setInputErrors] = useState({});
  const [error, setError] = useState();
  const [loading, setLoading] = useState(false);
  const user = useUser();

  const onChangeData = useCallback(
    (data) => {
      setValues({ ...values, ...data });
    },
    [values],
  );

  const onLogin = useCallback(
    () => {
      const { email, password } = values;
      setLoading(true);
      postLogin(email, password)
        .then((resp) => {
          if (resp.success) {
            user.login(resp.data);
          } else if (resp.errors.errors) {
            setError(null);
            setInputErrors({
              ...inputErrors,
              email: resp.errors.errors.email,
              password: resp.errors.errors.password,
            });
          } else {
            setError(resp.errors);
            setInputErrors({});
          }
        })
        .finally(() => {
          setLoading(false);
        });
    },
    [inputErrors, user, values],
  );

  /* TEST DATA */
  const accounts = {
    admin: { email: 'admin@admin.com', password: 'password' },
    user: { email: 'user@user.com', password: 'password' },
    invalid: { email: 'wrong mail', password: '' },
    notfound: { email: 'notfound@notfound.com', password: 'password' },
  };
  const loginAccount = useCallback((account) => {
    setValues({ ...values, ...account });
  }, [values]);
  /* END */

  return (
    <>
      <p>
        <Button onClick={() => { loginAccount(accounts.admin); }}>Login as Admin</Button>
        <Button onClick={() => { loginAccount(accounts.user); }}>Login as User</Button>
        <Button onClick={() => { loginAccount(accounts.invalid); }}>Login with invalid data</Button>
        <Button onClick={() => { loginAccount(accounts.notfound); }}>Login as not found user</Button>
      </p>
      <Form
        data={values}
        onChange={onChangeData}
        onSubmit={onLogin}
        errors={inputErrors}
      >
        <Input id="input-email" name="email" label="Email" autoFocus />
        <Input type="password" id="input-password" name="password" label="Password" />
        <p><Button className="btn btn-primary" isSubmit loading={loading}>Login</Button></p>
        {error && (
          <span>
            {error}
          </span>
        )}
      </Form>
    </>
  );
};

export default Login;
