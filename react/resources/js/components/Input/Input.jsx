import PropTypes from 'prop-types';
import { Input as InputComponent, useFormContext } from '@alangiacomin/ui-components-react';

const Input = ({
                 id, name, autoFocus, label, type,
               }) => {
  const { formErrors } = useFormContext();

  return (
    <div>
      <label className="form" htmlFor={id}>{label}</label>
      <InputComponent type={type} id={id} className="form-control" name={name} autoFocus={autoFocus} />
      {formErrors[name] && (
        <ul>
          {formErrors[name].map((err) => (<li key={err}>{err}</li>))}
        </ul>
      )}
    </div>
  );
};

Input.propTypes = {
  id: PropTypes.string.isRequired,
  type: PropTypes.string,
  name: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  autoFocus: PropTypes.bool,
};

Input.defaultProps = {
  autoFocus: false,
  type: null,
};

export default Input;
