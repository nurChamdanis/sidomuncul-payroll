<?php
    $base_url = base_url();
    $template_url = $base_url . 'vendors/zrcs';
    $session = session();
    $uri = service('uri');
?>

<!DOCTYPE html>
<html class="account-pages-bg">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php APP_NAME ?> Of PT. Arkamaya">
    <meta name="author" content="admin@personalia.id">

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $template_url ?>/default/assets/images/favicon2.png">
    <!-- App title -->
    <title><?=lang('Login.forgot_password');?> - <?php echo APP_NAME ?></title>

    <!-- App css -->
    <link href="<?= $template_url ?>/default/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    <style>
    </style>

    <script>
        var SITE_URL = '<?php echo base_url(); ?>';
    </script>
</head>


<body class="bg-transparent">

    <!-- HOME -->
    <section>
        <div class="container-alt">
            <div class="row">
                <div class="col-sm-12">

                    <div class="wrapper-page">

                        <div class="account-pages">
                            <div class="text-center account-logo-box">
                                <a href="<?php echo base_url(); ?>" class="text-success" >
                                    <span><img style="margin-top: 30px; margin-bottom: 10px" src="<?= $template_url ?>/default/assets/images/logo-text.png" alt="" height="40"></span>
                                </a>
                            </div>		
                            <div class="account-content ac_fix">
                                <?php if ($session->getFlashdata('notif_forgot_pass') != '') : ?>
                                    <div class="alert alert-<?php echo ($session->getFlashdata('notif_status') == 'success') ? 'success' : 'danger' ?> alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <?php echo $session->getFlashdata('notif_forgot_pass'); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="text-center m-b-20">
                                    <p class="text-muted m-b-0 font-13"><?=lang('Login.forgot_password_body')?></p>
                                </div>
                                <form class="form-horizontal" action="" method="post" id="form_send_email">
                                    <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
                                    <div class="form-group item">
                                        <div class="col-xs-12">
                                            <input class="form-control" name="email" type="email" required="" placeholder="Email" maxlength="100">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button name="btn_submit" id="btn_submit" class="form-control btn btn-bordered btn-custom btnResend" type="button" onclick="on_resend()"><?=lang('Login.btn_send')?></button>
                                        </div>
                                    </div>

                                    <div class="form-group text-center m-t-30">
                                        <div class="col-sm-12">
                                            <a href="<?php echo base_url(); ?>login" class="text-mutedx"><i class="fa fa-lock m-r-5"></i> <?=lang('Login.forgot_password_footer')?></a>
                                        </div>
                                        <div class="col-sm-12">

                                        </div>
                                    </div>

                                </form>

                                <div class="clearfix"></div>

                                <div class="row text-center m-t-30">
                                    <div class="btn-group btn-toggle"> 
                                        <button id="btn_lang_id" class="btn btn-xs <?=(get_cookie('lang_code',true) == "ID") ? 'btn-custom' : 'btn-default'?>">ID</button>
                                        <button id="btn_lang_en" class="btn btn-xs <?=(get_cookie('lang_code',true) == "ID") ? 'btn-default' : 'btn-custom'?>">EN</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end wrapper -->

            </div>
        </div>
        </div>
    </section>
    <!-- END HOME -->

    <script>
        var resizefunc = [];
        var lang = <?= json_encode(get_all_lang()) ?>
    </script>

    <!-- jQuery  -->
    <script src="<?= $template_url ?>/default/assets/js/jquery.min.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/bootstrap.min.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/detect.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/fastclick.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/jquery.blockUI.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/waves.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/jquery.slimscroll.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/jquery.scrollTo.min.js"></script>
    <script src="<?= $template_url ?>/plugins/jquery-validate/jquery.validate.js"></script>
    <script src="<?php echo base_url() ?>jsapp/jquery.validate.message.js"></script>
    <!-- App js -->
    <script src="<?= $template_url ?>/default/assets/js/jquery.core.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/jquery.app.js"></script>
    <script src="<?php echo base_url() ?>jsapp/lang.js"></script>


    <script>
        $(document).ready(function() {

            $('#form_send_email').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    // email: {
                    //     required: "Email harus diisi",
                    //     email: "Format email salah"
                    // }
                },
                highlight: function(element) {
                    $(element).closest('.item').removeClass('has-success').addClass('has-error');
                },
                success: function(element) {
                    $(element).closest('.item').removeClass('has-error').addClass('has-success');
                }

            });


        });

        function on_resend() {
            var valid = $('#form_send_email').validate();
            if (valid.form()) {
                $('.btnResend').addClass('disabled').html(`${lang.please_wait} <i class="fa fa-spinner fa-pulse fa-fw"></i>`);
                $('#form_send_email').submit();
            }
        }
    </script>
</body>

<!-- page-register.html 13:25:29 GMT -->

</html>