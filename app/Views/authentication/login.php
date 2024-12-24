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
    <meta name="description" content="Personalia - Software from PT. Arkamaya">
    <meta name="author" content="support@personalia.id">

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $template_url ?>/default/assets/images/favicon2.png">
    <!-- App title -->
    <title><?= lang('Login.title'); ?> - <?php echo APP_NAME ?></title>

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

    <script src="<?= $template_url ?>/default/assets/js/modernizr.min.js"></script>
    <script>
        var SITE_URL = '<?php echo base_url(); ?>';
    </script>
</head>


<body class="bg-transparent signwrapperx">
    <div class="sign-overlayx"></div>
    <div class="signpanel"></div>

    <!-- HOME -->
    <section>
        <div class="container-alt">
            <div class="row">
                <div class="col-sm-12">

                    <div class="wrapper-page">
                        <div class="account-pages">
                            <div class="text-center account-logo-box">
                                <a href="<?php echo base_url(); ?>" class="text-success">
                                    <span><img style="margin-top: 30px; margin-bottom: 10px" src="<?= $template_url ?>/default/assets/images/logo-text.png" alt="" height="40"></span>
                                </a>
                            </div>
                            <div class="account-content ac_fix">
                                <?php if ($uri->getSegment(2) == 'failed') : ?>
                                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <?= lang('Login.failed') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($uri->getSegment(2) == 'misscode') : ?>
                                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <?= lang('Login.misscode') ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($uri->getSegment(2) == 'attempt') : ?>
                                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <strong><?= $session->get('attempt'); ?> </strong> <br />
                                    </div>
                                <?php endif; ?>

                                <?php if ($session->getFlashData('redirect_uri') != '') : ?>
                                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <?= lang('Login.redirect_uri') ?>
                                    </div>
                                <?php endif; ?>

                                <form class="form-horizontal" action="<?php echo url_to('Authentication\LoginController::check') ?>" method="post" id="form_login">
                                    <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                                    <?php if ($session->getFlashData('redirect_uri')) : ?>
                                        <input type="hidden" id="redirect_uri" name="redirect_uri" value="<?php echo $session->getFlashData('redirect_uri'); ?>" />
                                    <?php endif; ?>

                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input name="user_name" class="form-control " type="text" placeholder="<?= lang('Login.user_name') ?>" value="" maxlength="100" autocomplete="Off" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <input name="user_password" id="user_password" class="form-control" type="password" placeholder="<?= lang('Login.user_password') ?>" value="" maxlength="100" required autocomplete="off" />
                                                <span class="input-group-addon"><a href="javascript:void(0);" id="btn_user_password"><i class="fa">&#xf070;</i></a></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button name="btn_submit" id="btn_submit" class="form-control btn btn-bordered btn-custom" type="submit"><?= lang('Login.btn_submit') ?></button>
                                        </div>
                                    </div>

                                    <div class="form-group text-center m-t-30">
                                        <div class="col-sm-12">
                                            <a href="<?php echo base_url(); ?>forgot_password"><i class="fa fa-lock m-r-5"></i> <?= lang('Login.forgot_password') ?></a>
                                        </div>
                                    </div>
                                </form>

                                <div class="clearfix"></div>

                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <p class="text-muted">&copy; 2017 - <?php echo date('Y') ?></p>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <a href="<?php echo $googleplay ?>"><img src="<?= $template_url ?>/frontend/images/logo/google_play.svg" alt="Play Store" style="width:100%;height:100px;"></a>
                                    </div>
                                    <div class="col-xs-6">
                                        <a href="<?php echo $appstore ?>"><img src="<?= $template_url ?>/frontend/images/logo/app_store.svg" alt="App Store" style="width:100%;height:100px;"></a>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="btn-group btn-toggle">
                                        <button id="btn_lang_id" class="btn btn-xs <?= (get_cookie('lang_code', true) == "ID") ? 'btn-custom' : 'btn-default' ?>">ID</button>
                                        <button id="btn_lang_en" class="btn btn-xs <?= (get_cookie('lang_code', true) == "ID") ? 'btn-default' : 'btn-custom' ?>">EN</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card-box-->
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

    <script src="<?= $template_url ?>/default/assets/js/jquery.core.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/jquery.app.js"></script>

    <script src="<?php echo base_url() ?>jsapp/login.js" defer></script>
    <script src="<?php echo base_url() ?>jsapp/lang.js"></script>
</body>
</html>