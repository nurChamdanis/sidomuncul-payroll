var form_validation_required = 'This field is required.';
var form_validation_valid_email = 'This field must contain a valid email address.';
var form_validation_valid_url = 'This field must contain a valid URL.';
var form_validation_valid_date = 'This field must contain a valid date.';
var form_validation_numeric = 'This field must contain only numbers.';
var form_validation_min_length = 'This field must be at least {0} characters in length.';
var form_validation_max_length = 'This field cannot exceed {0} characters in length.';
var form_validation_matches = 'This field does not match.';
var form_validation_less_than_equal_to = 'This field must contain a number less than or equal to {0}.';
var form_validation_greater_than_equal_to = 'This field must contain a number greater than or equal to {0}.';
if ( typeof lang !== 'undefined') {
    // console.log(lang)
    form_validation_required = (lang.form_validation_required) ? lang.form_validation_required : form_validation_required;
    form_validation_valid_email = (lang.form_validation_valid_email) ? lang.form_validation_valid_email : form_validation_valid_email;
    form_validation_valid_url = (lang.form_validation_valid_url) ? lang.form_validation_valid_url : form_validation_valid_url;
    form_validation_valid_date = (lang.form_validation_valid_date) ? lang.form_validation_valid_date : form_validation_valid_date;
    form_validation_numeric = (lang.form_validation_numeric) ? lang.form_validation_numeric : form_validation_numeric;
    form_validation_min_length = (lang.form_validation_min_length) ? lang.form_validation_min_length : form_validation_min_length;
    form_validation_max_length = (lang.form_validation_max_length) ? lang.form_validation_max_length : form_validation_max_length;
    form_validation_matches = (lang.form_validation_matches) ? lang.form_validation_matches : form_validation_matches;
    form_validation_less_than_equal_to = (lang.form_validation_less_than_equal_to) ? lang.form_validation_less_than_equal_to : form_validation_less_than_equal_to;
    form_validation_greater_than_equal_to = (lang.form_validation_greater_than_equal_to) ? lang.form_validation_greater_than_equal_to : form_validation_greater_than_equal_to;
}
jQuery.extend(jQuery.validator.messages, {
    required: form_validation_required,
    email: form_validation_valid_email,
    url: form_validation_valid_url,
    date: form_validation_valid_date,

    number: form_validation_numeric,
    minlength: jQuery.validator.format(form_validation_min_length),
    maxlength: jQuery.validator.format(form_validation_max_length),
    equalTo: form_validation_matches,
    min: jQuery.validator.format(form_validation_less_than_equal_to),
    max: jQuery.validator.format(form_validation_greater_than_equal_to),

    remote: "Please fix this field.",
    dateISO: "Please enter a valid date (ISO).",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    accept: "Please enter a value with a valid extension.",
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}.")
});