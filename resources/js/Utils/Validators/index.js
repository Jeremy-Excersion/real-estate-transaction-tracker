export const validateEmail = (email) => {
  return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email);
};

export const validateZipcode = (zip) => {
  return /^\d{5}(-\d{4})?$/.test(zip);
};

export const validatePrice = (price) => {
  // Allows an optional dollar sign at the start, commas every 3 digits, and two decimal values
  return /^\$?(\d{1,3}(,\d{3})*|\d+)(\.\d{1,2})?$/.test(price.replace(/,/g, ''));
};

export const validatePhoneNumber = (phoneNumber) => {
  return /^\+?(\d{1,3})?[-. ]?(\d{1,4})[-. ]?(\d{1,4})[-. ]?(\d{1,4})[-. ]?(\d{1,9})$/.test(
    phoneNumber
  );
};
