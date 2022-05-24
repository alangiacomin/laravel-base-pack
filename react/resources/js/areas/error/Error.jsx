import PropTypes from 'prop-types';

const Error = ({ errorCode }) => {
  const getDescription = (code) => {
    switch (code) {
      case 403:
        return 'unauthorized';
      case 404:
        return 'page_not_found';
      default:
        return 'undefined_error';
    }
  };

  return (
    <>
      <h1>ERRORE</h1>
      <div className="col-xl-9 mx-auto">
        <h2 className="mb-5">
          {getDescription(errorCode)}
        </h2>
      </div>
    </>
  );
};

Error.propTypes = {
  errorCode: PropTypes.number,
};

Error.defaultProps = {
  errorCode: 0,
};

export default Error;
