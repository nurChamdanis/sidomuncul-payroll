<?php

$value = array();

$value['form_validation_required']		= 'This field is required.';
$value['form_validation_valid_email']	= 'This field must contain a valid email address.';
$value['form_validation_valid_url']		= 'This field must contain a valid URL.';
$value['form_validation_valid_date']		= 'This field must contain a valid date.';
$value['form_validation_numeric']		= 'This field must contain only numbers.';
$value['form_validation_min_length']		= 'This field must be at least {0} characters in length.';
$value['form_validation_max_length']		= 'This field cannot exceed {0} characters in length.';
$value['form_validation_matches']		= 'This field does not match.';
$value['form_validation_less_than_equal_to']	= 'This field must contain a number less than or equal to {0}.';
$value['form_validation_greater_than_equal_to']	= 'This field must contain a number greater than or equal to {0}.';

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