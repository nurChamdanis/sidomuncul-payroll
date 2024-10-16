<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);

defined('APP_NAME') OR define('APP_NAME', 'Personalia Sidomuncul');
defined('S_COMPANY_ID') OR define('S_COMPANY_ID', 's_company_id');
defined('S_COMPANY_NAME') OR define('S_COMPANY_NAME', 's_company_name');
defined('S_USER_NAME') OR define('S_USER_NAME', 's_user_name');
defined('S_USER_GROUP_ID') OR define('S_USER_GROUP_ID', 's_user_group_id');
defined('S_PHOTO') OR define('S_PHOTO', 's_photo');
defined('S_EMPLOYEE_ID') OR define('S_EMPLOYEE_ID', 's_employee_id');
defined('S_EMPLOYEE_NAME') OR define('S_EMPLOYEE_NAME', 's_employee_name');
defined('S_IS_ADMIN') OR define('S_IS_ADMIN', 's_is_admin');
defined('S_NO_REG') OR define('S_NO_REG', 's_no_reg');

// add by bagia 1 Des 2022
defined('S_POSITION_ID') OR define('S_POSITION_ID', 's_position_id');
defined('S_POSITION_NAME') OR define('S_POSITION_NAME', 's_position_name');

//add by uye 8 Mar 2017
defined('S_ADMIN_USER_NAME') OR define('S_ADMIN_USER_NAME', 's_admin_user_name');
defined('S_ADMIN_USER_GROUP_ID') OR define('S_ADMIN_USER_GROUP_ID', 's_admin_user_group_id');
defined('S_ADMIN_PHOTO') OR define('S_ADMIN_PHOTO', 's_admin_photo');
defined('S_ADMIN_EMPLOYEE_ID') OR define('S_ADMIN_EMPLOYEE_ID', 's_admin_employee_id');
defined('S_ADMIN_EMPLOYEE_NAME') OR define('S_ADMIN_EMPLOYEE_NAME', 's_admin_employee_name');
defined('S_ADMIN_IS_ADMIN') OR define('S_ADMIN_IS_ADMIN', 's_admin_is_admin');

defined('S_CAN_ACCESS_PAYROLL_APP') OR define('S_CAN_ACCESS_PAYROLL_APP', 'access_payroll_app');

defined('S_DEFAULT_LANDING') OR define('S_DEFAULT_LANDING', 's_default_landing');
defined('S_IS_EXPIRED') OR define('S_IS_EXPIRED', 's_is_expired');
defined('S_TRIAL_EXPIRED') OR define('S_TRIAL_EXPIRED', 's_trial_expired');

defined('REIMBURSE_PATH') OR define('REIMBURSE_PATH', 'uploads/reimburses/');

// SALES PATH
defined('SALES_ORDER_PATH') OR define('SALES_ORDER_PATH', 'uploads/sales_orders/');
defined('SALES_INVOICE_PATH') OR define('SALES_INVOICE_PATH', 'uploads/sales_invoice/');
defined('SALES_PAYMENT_PATH') OR define('SALES_PAYMENT_PATH', 'uploads/sales_payment/');

// EMAIL CONFIG -- Pilih Salah satu (Matikan salah satu)

// EMAIL PROD USING MAILGUN
//defined('SMTP_HOST') or define('SMTP_HOST', 'smtp.mailgun.org');
//defined('SMTP_PORT') or define('SMTP_PORT', '587');
//defined('SMTP_USER') or define('SMTP_USER', 'postmaster@client.arkamaya.net');
//defined('SMTP_PASS') or define('SMTP_PASS', '0abe2c108e283fcc109856dc0cc63710-ba042922-6bb48fb1');
//defined('EMAIL_ALIAS') or define('EMAIL_ALIAS', 'Personalia.id');

// EMAIL PROD USING MAILJET
// defined('SMTP_HOST') or define('SMTP_HOST', 'in-v3.mailjet.com');
//  defined('SMTP_PORT') or define('SMTP_PORT', '587');
//  defined('SMTP_USER') or define('SMTP_USER', 'b6ffdc0f74ff63ad7db4db62afeba10e');
//  defined('SMTP_PASS') or define('SMTP_PASS', '2dcec0bdd297dc402afc17c7c35cadb6');
//  defined('EMAIL_ALIAS') or define('EMAIL_ALIAS', 'Personalia.id');


// EMAIL DEV (Berjalan di localhost)
// defined('SMTP_HOST') or define('SMTP_HOST', 'tls://smtp.gmail.com');
// defined('SMTP_PORT') or define('SMTP_PORT', '587');
// defined('SMTP_USER') or define('SMTP_USER', 'postman@arkamaya.co.id');
// defined('SMTP_PASS') or define('SMTP_PASS', '*!satu2tiga!*');
// defined('EMAIL_ALIAS') or define('EMAIL_ALIAS', 'Personalia.id');

// SMTP Google Workspace
defined('SMTP_HOST') or define('SMTP_HOST', 'sandbox.smtp.mailtrap.io');
defined('SMTP_PORT') or define('SMTP_PORT', '465');
defined('SMTP_USER') or define('SMTP_USER', '#####');
defined('SMTP_PASS') or define('SMTP_PASS', '#####');
//  defined('SMTP_PASS') or define('SMTP_PASS', '');
defined('EMAIL_ALIAS') or define('EMAIL_ALIAS', 'Personalia.id');

defined('MAIN_WEB') or define('MAIN_WEB', 'https://hris.sidomuncul.co.id');
defined('COMPANY_ASSETS_PATH') or define('COMPANY_ASSETS_PATH', './assets/');

// defined('PWDTENCRYPT') or define('PWDTENCRYPT', 'Th15t1m3..9o04L4uncH');
// defined('PWDTENCRYPTVL') or define('PWDTENCRYPTVL', 'Th15t1m3..9o04L4uncH-->Encrypted');
defined('ENCRYPTKEY') or define('ENCRYPTKEY', '&^Xznj-XTJAHv-kk}Hfq6%;(3F(gnj');

//add by ark.eri
//add date 02-06-2020
//description :

defined('S_AFFILIATE_ID') OR define('S_AFFILIATE_ID', 's_affiliate_id');
defined('S_AFFILIATE_NO') OR define('S_AFFILIATE_NO', 's_affiliate_no');
defined('S_AFFILIATE_NAME') OR define('S_AFFILIATE_NAME', 's_affiliate_name');
defined('S_AFFILIATE_PHONE') OR define('S_AFFILIATE_PHONE', 's_affiliate_phone');
defined('S_AFFILIATE_EMAIL') OR define('S_AFFILIATE_EMAIL', 's_affiliate_email');
defined('S_REFERRAL_CODE') OR define('S_REFERRAL_CODE', 's_referral_code');

defined('S_ACCESS_COMPANY_ID') OR define('S_ACCESS_COMPANY_ID', 's_access_company_id');
defined('S_ACCESS_AREA_ID') OR define('S_ACCESS_AREA_ID', 's_access_area_id');
defined('S_ACCESS_POSITION_ID') OR define('S_ACCESS_POSITION_ID', 's_access_position_id');
defined('S_ACCESS_ROLE_ID') OR define('S_ACCESS_ROLE_ID', 's_access_role_id');
defined('S_ACCESS_SUBORDINATE_ID') OR define('S_ACCESS_SUBORDINATE_ID', 's_access_subordinate_id');
defined('S_ACCESS_APPROVAL_ID') OR define('S_ACCESS_APPROVAL_ID', 's_access_approval_id');

defined('S_LANG_CODE') OR define('S_LANG_CODE', 's_lang_code');
defined('S_LANGUAGE_DEFAULT') OR define('S_LANGUAGE_DEFAULT', 's_language_default');

defined('BASE_URL') OR define('BASE_URL', 'http://localhost:8080');

defined('SECRET_KEY') OR define('SECRET_KEY', '$2a$12$F14Rwtdzdg7ZugGG7y4Cau9e0ZC443i0hkj97BxmQmtWt59nEsgtS');
defined('ISSUER') OR define('ISSUER', 'PT. Arkamaya');
defined('EXPIRATION_TIME') OR define('EXPIRATION_TIME', '+1 days');

defined('HRISSIDO2024') OR define('HRISSIDO2024', '_*!)2024_hr1Ss1d0mUnCuL!(*_');
defined('SIDOKEY') OR define('SIDOKEY', 'encryption_key');
