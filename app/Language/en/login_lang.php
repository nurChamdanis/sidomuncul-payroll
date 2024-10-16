<?php

// set initial values
$value = array();

// screen login
$value['title'] = 'Login';
$value['user_name'] = 'Employee ID';
$value['user_password'] = 'Password';

$value['btn_submit'] = 'Login';
$value['forgot_password'] = 'Forgot Password';
$value['reset_password'] = 'Reset Password';

$value['failed'] = '<strong>Login Failed.</strong> <br/>Incorrect ' . $value['user_name'] . ' or ' . $value['user_password'] . '.';
$value['misscode'] = '<strong>Failed Confirmation.</strong> <br/>User Confirmation Code not registered.';
$value['attempt'] = 'Experiment too much. Try after another $diff minutes.';
$value['redirect_uri'] = '<strong>Attention.</strong> <br/><span class="text-muted">You must login first to access the page</span>';

// screen forgot_password
$value['forgot_password_body'] = 'Enter your email address and we will send you a link to change your password';
$value['forgot_password_footer'] = 'Login if you have an account';
$value['btn_send'] = 'Send';
$value['email_not_registered'] = '<strong>Failed.</strong> <br/>Your email is not registered.';
$value['email_not_confirmed'] = '<strong>Failed.</strong> <br/>Your email is not confirmed.';
$value['email_not_sent'] = '<strong>Failed.</strong> <br/>There was an error sending the email, please try again later.';
$value['email_send_success'] = '<strong>Succeed. </strong> <br/>A link to change Password has been sent to the e-mail.';
$value['reset_password_code_not_found'] = '<strong>Failed. </strong> <br/>The code to change your password is no longer valid.';

// screen reset_password
$value['reset_password_failed'] = '<strong>Failed. </strong> <br/>Your password failed to change. <br/>The code to change your password is no longer valid. <br/>To return to the forgot password page, click <a href="$link">here</a>';
$value['reset_password_success'] = '<strong>Succeed. </strong> <br/>Your password has been successfully changed. <br/>Now you can use your account again. <br/>click <a href="$link">here</a> to go to the login page.';
$value['reset_password_body'] = 'Enter the new password for your Email Account, ';
$value['new_password'] = 'Password';
$value['confirm_password'] = 'Rewrite Password';
$value['password_length_char'] = 'Minimum 8 Characters';
$value['password_special_char'] = 'Character Special Combinations';
$value['password_uppercase_char'] = 'Upper & Lower case Combinations';
$value['btn_continue'] = 'Save';

/**
 * ----------------------------------------------------------------
 * Set root key for language
 * ----------------------------------------------------------------
 */
$lang['Login'] = $value;

return $lang;