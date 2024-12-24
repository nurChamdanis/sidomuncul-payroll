<?php

$value = array();

$value['form_validation_required']		= 'Field ini harus diisi.';
$value['form_validation_valid_email']	= 'Field ini harus berisi alamat email yang valid';
$value['form_validation_valid_url']		= 'Field ini harus berisi URL yang valid.';
$value['form_validation_valid_date']		= 'Field ini harus berisi tanggal yang valid.';
$value['form_validation_numeric']		= 'Field ini harus berisi angka saja.';
$value['form_validation_min_length']		= 'Field ini minimal harus {0} karakter.';
$value['form_validation_max_length']		= 'Field ini tidak boleh melebihi {0} karakter.';
$value['form_validation_matches']		= 'Field ini tidak sama.';
$value['form_validation_less_than_equal_to']	= 'Field ini harus berisi angka yang kurang dari atau sama dengan {0}.';
$value['form_validation_greater_than_equal_to']	= 'Field ini harus berisi angka yang lebih besar dari atau sama dengan {0}.';

$value['form_validation_isset']			= 'The {field} field must have a value.';
$value['form_validation_valid_emails']		= 'The {field} field must contain all valid email addresses.';
$value['form_validation_valid_ip']		= 'The {field} field must contain a valid IP.';
$value['form_validation_valid_base64']		= 'The {field} field must contain a valid Base64 string.';
$value['form_validation_exact_length']		= 'The {field} field must be exactly {param} characters in length.';
$value['form_validation_alpha']			= 'The {field} field may only contain alphabetical characters.';
$value['form_validation_alpha_numeric']		= 'The {field} field may only contain alpha-numeric characters.';
$value['form_validation_alpha_numeric_spaces']	= 'The {field} field may only contain alpha-numeric characters and spaces.';
$value['form_validation_alpha_dash']		= 'The {field} field may only contain alpha-numeric characters, underscores, and dashes.';
$value['form_validation_is_numeric']		= 'The {field} field must contain only numeric characters.';
$value['form_validation_integer']		= 'The {field} field must contain an integer.';
$value['form_validation_regex_match']		= 'The {field} field is not in the correct format.';
$value['form_validation_differs']		= 'The {field} field must differ from the {param} field.';
$value['form_validation_is_unique'] 		= 'The {field} field must contain a unique value.';
$value['form_validation_is_natural']		= 'The {field} field must only contain digits.';
$value['form_validation_is_natural_no_zero']	= 'The {field} field must only contain digits and must be greater than zero.';
$value['form_validation_decimal']		= 'The {field} field must contain a decimal number.';
$value['form_validation_less_than']		= 'The {field} field must contain a number less than {param}.';
$value['form_validation_greater_than']		= 'The {field} field must contain a number greater than {param}.';
$value['form_validation_error_message_not_set']	= 'Unable to access an error message corresponding to your field name {field}.';
$value['form_validation_in_list']		= 'The {field} field must be one of: {param}.';

/**
 * ----------------------------------------------------------------
 * Set root key for language
 * ----------------------------------------------------------------
 */
$lang['Validation'] = $value;

return $lang;