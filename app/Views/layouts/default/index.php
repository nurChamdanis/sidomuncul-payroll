<?php
$base_url = base_url();
$template_url = $base_url . 'vendors/zrcs';
$session = session();
$uri = service('uri');
?>
<!DOCTYPE html>
<html lang="<?= get_cookie('lang_code', true); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo APP_NAME ?> - Aplikasi Manajemen Pegawai Anda.">
    <meta name="author" content="support@personalia.id">

    <link rel="shortcut icon" href="<?= $template_url ?>/default/assets/images/favicon2.png">

    <title><?php
            echo (isset($stitle) != '') ? $stitle . ' - ' : '';
            echo $session->get(S_COMPANY_NAME) . ' - ' . APP_NAME;
            ?></title>
    <?php //if ($session->get(S_IS_EXPIRED) == 1) : ?>
        <!--<link href="<?php echo base_url(); ?>plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css">-->
        <!--  added by misbah 20200923 -->
        <!-- sweetalert2 -->
        <link href="<?= $template_url ?>/plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <style>
            .swal2-html-container {
                font-size: 1.5em !important;
            }
        </style>
    <?php //endif; ?>

    <link href="<?= $template_url ?>/plugins/fullcalendar/css/fullcalendar.min.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?= $template_url ?>/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?= $template_url ?>/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?= $template_url ?>/plugins/responsive-table/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="<?= $template_url ?>/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/morris/morris.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/chartist/css/chartist.min.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/select2/select2/select2.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/jquery.filer/css/jquery.filer.css" type="text/css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />

    <!-- ION Slider -->
    <link href="<?= $template_url ?>/plugins/ion-rangeslider/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/ion-rangeslider/ion.rangeSlider.skinModern.css" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="<?= $template_url ?>/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/datatables/keyTable.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/switchery/switchery.min.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/summernote/summernote.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/jquery-treegrid/css/jquery.treegrid.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="<?= $template_url ?>/plugins/raty/jquery.raty.css" rel="stylesheet">

    <!-- App css -->
    <link href="<?= $template_url ?>/default/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/default/assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <!-- add style multi select -->
    <link href="<?= $template_url ?>/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" rel="stylesheet" />

    <!--  added by moharifrifai 20210318 -->
    <link href="<?= $template_url ?>/plugins/intro-js/introjs.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/cropper/css/cropper.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/nestable/jquery.nestable.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/tooltipster/tooltipster.bundle.min.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
    <link href="<?= $template_url ?>/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet" />

    <!--  added by Taufik 20210427 -->
    <link href="<?= $template_url ?>/default/assets/css/announce.css" rel="stylesheet" type="text/css" />
    <!-- OwlCarousel -->
    <link href="<?= $template_url ?>/plugins/OwlCarousel2/assets/owl.carousel.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $template_url ?>/plugins/OwlCarousel2/assets/owl.theme.default.min.css" rel="stylesheet" type="text/css" />

    <link href="<?= $template_url ?>/plugins/jquery-orgchart/jquery.orgchart.css" rel="stylesheet" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    <script src="<?= $template_url ?>/default/assets/js/modernizr.min.js"></script>
    <script type="text/javascript">
        var SITE_URL = '<?php echo site_url() ?>';
    </script>

    <script type="text/javascript">
        // window.$crisp = [];
        // window.CRISP_WEBSITE_ID = "aacf04b4-f25f-467a-80a4-b5d7bbb45905";
        // (function() {
        //     d = document;
        //     s = d.createElement("script");
        //     s.src = "https://client.crisp.chat/l.js";
        //     s.async = 1;
        //     d.getElementsByTagName("head")[0].appendChild(s);
        // })();
    </script>

    <style>
        .bulk-edit {
            position: fixed;
            left: 0;
            width: 100%;
            background-color: #6b5fb5;
            color: white;
            text-align: center;
            z-index: 1000;
            display: none;
        }

        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: linear-gradient(45deg, #ffffff, #ffffff);
            z-index: 9999999;
        }

        #preloader #status {
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        #preloader #status .spinner {
            width: 40px;
            height: 40px;
            position: relative;
            margin: 100px auto;
        }

        #preloader #status .spinner .double-bounce1,
        #preloader #status .spinner .double-bounce2 {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #343a40;
            opacity: 0.6;
            position: absolute;
            top: 0;
            left: 0;
            -webkit-animation: sk-bounce 2.0s infinite ease-in-out;
            animation: sk-bounce 2.0s infinite ease-in-out;
        }

        #preloader #status .spinner .double-bounce2 {
            -webkit-animation-delay: -1.0s;
            animation-delay: -1.0s;
        }

        @-webkit-keyframes sk-bounce {

            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        @keyframes sk-bounce {

            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        .breadcrumb>.active {
            color: #777;
            font-weight: bold;
        }

        .table thead tr th{
            text-align: center;
            vertical-align: middle;
        }
        
        .table tbody tr td{
            vertical-align: middle !important;
        }

        .table-responsive{
            border: none !important;
            padding: 10px;
        }

        
        /* Style for the scrollbar */
        .dataTables_scrollBody::-webkit-scrollbar {
            width: 5px; /* Set the width of the scrollbar */
            height: 5px; /* Set the height of the scrollbar */
        }

        /* Style for the scrollbar track */
        .dataTables_scrollBody::-webkit-scrollbar-track {
            background: #f1f1f1; /* Set the background color of the track */
        }

        /* Style for the scrollbar thumb */
        .dataTables_scrollBody::-webkit-scrollbar-thumb {
            background: #cdcdcd; /* Set the color of the thumb */
            border-radius: 20px; /* Set the border radius of the thumb */
            height: 5px !important;
        }

        /* Style for the scrollbar thumb when hovering */
        .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
            background: #555; /* Set the color of the thumb on hover */
        }


        /* Style for the scrollbar */
        .table-responsive::-webkit-scrollbar {
            width: 5px; /* Set the width of the scrollbar */
            height: 5px; /* Set the height of the scrollbar */
        }

        /* Style for the scrollbar track */
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1; /* Set the background color of the track */
        }

        /* Style for the scrollbar thumb */
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cdcdcd; /* Set the color of the thumb */
            border-radius: 20px; /* Set the border radius of the thumb */
            height: 5px !important;
        }

        /* Style for the scrollbar thumb when hovering */
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555; /* Set the color of the thumb on hover */
        }

        .break-word{
            display:block; overflow-wrap: break-word; text-align:left;
        }

        .author{
            display:block; width: 100%; overflow-wrap: break-word; text-align:center;
        }
        
        .timestamp{
            display:block; width: 100%; overflow-wrap: break-word; text-align:center;
        }

        .panel-body {
            max-height: 2000px !important; /* Set a large enough value */
            overflow: hidden !important;
            transition: max-height 0.3s ease-in-out !important; /* Smooth transition */
        }

        .panel-body.hidden {
            max-height: 0 !important;
        }

        .has-error .select2-selection {
            /*border: 1px solid #a94442;
            border-radius: 4px;*/
            border-color:rgb(185, 74, 72) !important;
        }

        /* Style for the scrollbar */
        .table-list::-webkit-scrollbar {
            width: 5px; /* Set the width of the scrollbar */
            height: 5px; /* Set the height of the scrollbar */
        }

        /* Style for the scrollbar track */
        .table-list::-webkit-scrollbar-track {
            background: #f1f1f1; /* Set the background color of the track */
        }

        /* Style for the scrollbar thumb */
        .table-list::-webkit-scrollbar-thumb {
            background: #cdcdcd; /* Set the color of the thumb */
            border-radius: 20px; /* Set the border radius of the thumb */
            height: 5px !important;
        }

        /* Style for the scrollbar thumb when hovering */
        .table-list::-webkit-scrollbar-thumb:hover {
            background: #555; /* Set the color of the thumb on hover */
        }
        
        .flex-center{
            display: flex; justify-content:center; align-items:center;
        }
        .flex-end{
            display: flex; justify-content: flex-end
        }

        table.dataTable.table-bordered.DTFC_Cloned tbody tr:nth-of-type(odd) {
            background-color: #F3F3F3;
        }
        table.dataTable.table-bordered.DTFC_Cloned tbody tr:nth-of-type(even) {
            background-color: white;
        }
        ol.history_payroll_log {
            margin: 0px; padding: 0px 20px;
        }
        ol.history_payroll_log li{
            margin-bottom: 10px;
        }

        .label-gray{
            background-color: #dfdfdf;
        }
    </style>

    <?php if ($this->renderSection('styles')) : ?>
        <?php echo $this->renderSection('styles'); ?>
    <?php endif; ?>

</head>

<body class="fixed-left">
    <!-- Begin page -->
    <div id="wrapper">
        <div class="bulk-edit">
            <div class="row">
                <div class="col-sm-3" style="padding: 10px 5px;">
                    <span id="txt_cnt_bulk"></span>
                </div>
                <div class="col-sm-6">
                    <div class="thumb-md member-thumb center-block tooltipster" data-placement="bottom" title="Set Milestone" style="max-width:24px;max-height:24px;display: inline;">
                        <a href="javascript:;" id="milestone_bulk" style="cursor: pointer" class="popover-button" data-selected-id="null" onclick="onShowMilestoneBulk(event)" onmouseover="onHoverBulk('icon_milestone_bulk')" onmouseout="onUnhoverBulk('icon_milestone_bulk')">
                            <i class="mdi mdi-flag" style="cursor: pointer;font-size:24px;color: white; margin: 10px 5px;" id="icon_milestone_bulk"></i>
                        </a>
                    </div>
                    <div class="thumb-md member-thumb center-block tooltipster" data-placement="bottom" title="Set Assignee" style="max-width:24px;max-height:24px;display: inline;">
                        <a href="javascript:;" id="assignee_bulk" style="cursor: pointer" class="popover-button" data-selected-id="null" onclick="onShowAssigneeBulk(event)" onmouseover="onHoverBulk('icon_assignee_bulk')" onmouseout="onUnhoverBulk('icon_assignee_bulk')">
                            <i class="mdi mdi-account-box" style="cursor: pointer;font-size:24px;color: white; margin: 10px 5px;" id="icon_assignee_bulk"></i>
                        </a>
                    </div>

                    <div class="thumb-md member-thumb center-block  input-daterange-datepicker" id="input_date_bulk" style="max-height:24px;max-width:24px;display: inline;">
                        <a href="javascript:;" id="" data-date="" data-time="" id="" title="Set Date" data-placement="bottom" style="cursor: pointer;padding:0px" class="tooltipster" onclick="onShowDateBulk()" onmouseover="onHoverBulk('icon_date_bulk')" onmouseout="onUnhoverBulk('icon_date_bulk')">
                            <i class="mdi mdi-calendar" style="cursor: pointer;font-size:24px;color: white;margin: 10px 5px;" id="icon_date_bulk"></i>
                        </a>
                    </div>
                    <div class="thumb-md member-thumb center-block tooltipster" title="Set Status" id="" data-placement="bottom" style="max-height:24px;max-width:24px;display: inline;">
                        <a href="javascript:;" id="status_bulk" style="cursor: pointer;padding:0px" class="popover-button" data-status-task="" onclick="onShowStatusBulk()" onmouseover="onHoverBulk('icon_status_bulk')" onmouseout="onUnhoverBulk('icon_status_bulk')">
                            <i class="mdi mdi-playlist-check" style="cursor: pointer;font-size:24px;color: white;margin: 10px 5px;" id="icon_status_bulk"></i>
                        </a>
                    </div>
                    <div class="thumb-md member-thumb center-block tooltipster" title="Delete" id="" data-placement="bottom" style="max-height:24px;max-width:24px;display: inline;">
                        <a href="javascript:;" id="" data-date="" data-time="" style="cursor: pointer;padding:0px" class="" onclick="onShowDeleteBulk()" onmouseover="onHoverBulk('icon_delete_bulk')" onmouseout="onUnhoverBulk('icon_delete_bulk')">
                            <i class="mdi mdi-delete delete-icon" style="cursor: pointer;font-size:24px;color: white;margin: 10px 5px;" id="icon_delete_bulk"></i>
                        </a>
                    </div>
                    <div style="position:fixed; top: 40px;left: 47.5%;background:#5c4fab ;padding:0px 10px;border: 1px solid white; border-radius: 25px;" id="icon_dismiss_bulk">
                        <a href="javascript:;" data-date="" data-time="" style="cursor: pointer;padding:0px;color: white;" class="" onclick="onDismiss()" onmouseover="onHoverBulk('icon_dismiss_bulk')" onmouseout="onUnhoverBulk('icon_dismiss_bulk')">
                            <span>
                                <i class=" mdi mdi-window-close" style="cursor: pointer;font-size:12px;"></i>
                                Dismiss
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Bar Start -->
        <div class="topbar">
            <!-- LOGO -->
            <div class="topbar-left">
                <?php
                // $logo = base_url()."default/assets/images/logo_app_white.png";
                $logo = $template_url ."/default/assets/images/logo-text-white.png";
                // $logo_app_icon = base_url()."default/assets/images/logo_app_icon.png";
                $logo_app_icon = $template_url ."/default/assets/images/logo-white.png";
                ?>
                <a href="<?php echo site_url('dashboard') ?>" class="logo">
                    <span> <img src="<?php echo $logo; ?>" alt="" style="height: 35px; max-height: 70px; max-width: 160px"></span>
                    <i> <img src="<?php echo $logo_app_icon; ?>" alt="" style="height: 30px; max-width: 50px"></i>
                </a>
            </div>

            <!-- Button mobile view to collapse sidebar menu -->
            <div class="navbar navbar-default" role="navigation" style="display:flex; justify-content: flex-start; align-items:center;">

                <!-- Navbar-left -->
                <ul class="nav navbar-nav" style="padding-left: auto; flex: 2; display:flex; justify-content: flex-start;">
                    <li>
                        <button class="button-menu-mobile open-left waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                    <li class="hidden-xs">
                        <a href="<?php echo site_url() ?>" class="menu-item" style="font-weight: bolder; font-size: 18px; padding-top: 24px;"><?php echo $session->get(S_COMPANY_NAME) ?></a>
                    </li>
                </ul>
                <style>
                    .dropdown-item {
                        display: block;
                        width: 100%;
                        padding: 0.35rem 1.5rem;
                        clear: both;
                        font-weight: 400;
                        color: #575a65;
                        text-align: inherit;
                        white-space: nowrap;
                        background-color: transparent;
                        border: 0;
                    }

                    .notify-item {
                        padding: 12px 20px;
                    }

                    .notify-item:hover {
                        color: #16181b;
                        text-decoration: none;
                        background-color: #f9f9f9;
                    }

                    .noti-icon {
                        font-size: 22px
                    }

                    .noti-icon-badge {
                        display: inline-block;
                        position: absolute;
                        top: 12px;
                        right: 12px
                    }

                    .notify-item.notify-all {
                        background-color: #fdfdfd;
                        margin-bottom: -7px
                    }

                    .notify-item .notify-icon {
                        float: left;
                        height: 36px;
                        width: 36px;
                        font-size: 18px;
                        line-height: 36px;
                        text-align: center;
                        margin-top: 4px;
                        margin-right: 10px;
                        border-radius: 50%;
                        color: #fff
                    }

                    .notify-item .notify-details {
                        margin-bottom: 5px;
                        overflow: hidden;
                        margin-left: 45px;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        color: #575a65;
                        font-weight: 600;
                        text-wrap: wrap;
                    }

                    .notify-item .notify-details b {
                        font-weight: 500
                    }

                    .notify-item .notify-details small {
                        display: block
                    }

                    .notify-item .notify-details span {
                        display: block;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        font-size: 13px;
                        text-wrap: wrap;
                    }

                    .notify-item.unread {
                        background: #e8ebff !important;
                    }
                </style>
                <!-- Right(Notification) -->
                <ul class="nav navbar-nav" style="padding-left: auto; flex: 2; display:flex; justify-content: flex-end;">
                    <li class="dropdown user-box">
                        <a href="javascript:;" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown" aria-expanded="true">
                            <i class="mdi mdi-bell noti-icon" style="font-size:27px"></i>
                            <?php if ($notifcount > 0) : ?>
                                <span class="badge badge-danger rounded-circle noti-icon-badge" style="position: absolute; top: 12px; right: 8px; font-size: 9px"><?= $notifcount ?></span>
                            <?php endif; ?>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list" style="width: 35rem; height: 300px;">
                            <div style="display:flex; flex-direction:column; height:100%">
                                <div style="background-color: #f3f3f3; padding: 15px 20px; border-radius: 0.25rem 0.25rem 0 0; margin-top: -8px;">
                                    <h5 class="font-16 m-0" style="padding:0px !important; text-align:left;">
                                        Notification
                                        <span style="float:right">
                                            <a href="javascript:void(0);" onclick="readAll()" class="text-dark">
                                                <small>Read All</small>
                                            </a>
                                        </span>
                                    </h5>
                                </div>
                                <div id="notification-container" style="flex:1; overflow-y: auto">
                                    <?php
                                    foreach ($notifications as $index => $value) : ?>
                                        <a href="javascript:void(0);" onclick="readAndGo('<?= $value->link_location ?>',<?= $value->notification_id ?>)" class="dropdown-item notify-item <?php if ($value->read_flg == "0") echo "unread"; ?>">
                                            <div class="notify-icon bg-info">
                                                <i class="mdi mdi-bell-outline"></i>
                                            </div>
                                            <p class="notify-details"><?= $value->notification_title ?>
                                                <small class="text-muted" style="font-size: 0.75em;"><?= $value->notification_content ?></small>
                                            </p>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                                <div style="width: 100%;height: 40px;text-align: center;padding: 10px;">
                                    <a href="javascript:void(0);" onclick="showMore()">
                                        Show More
                                        <i class="fi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="hidden-xs">
                        <a class="" href="javascript:;"><span style="font-weight: bold"><?php echo $session->get(S_EMPLOYEE_NAME) ?></span><br /><sup><?php echo $session->get(S_POSITION_NAME) ?></sup></a>
                    </li>

                    <li class="dropdown user-box">
                        <a href="javascript:;" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown" aria-expanded="true">
                            <?php
                            $photo = '/default/assets/images/users/thumbs/user_photo.png';
                            if ($session->get(S_PHOTO) != '') {
                                $filep = COMPANY_ASSETS_PATH . 'cid' . $session->get(S_COMPANY_ID) . '/profile/thumbs/' . $session->get(S_PHOTO);

                                if (file_exists($filep)) {
                                    $photo = $filep;
                                }
                            }
                            ?>

                            <img src="<?= $template_url ?><?php echo $photo ?>" alt="profile-image" class="img-circle user-img" />
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                            <?php if ($session->get(S_EMPLOYEE_ID) != '') : ?>
                                <li><a href="<?php echo site_url('profile/id/' . $session->get(S_EMPLOYEE_ID)) ?>"><i class="ti-user m-r-5"></i> <?= !empty(lang('Shared.my_profile')) ? lang('Shared.my_profile') : 'Profil Saya' ?></a></li>
                            <?php endif; ?>
                            <?php if ($session->get(S_IS_ADMIN) == '1') : ?>
                                <li><a href="<?php echo site_url('setting') ?>"><i class="ti-settings m-r-5"></i> <?= !empty(lang('Shared.my_setting')) ? lang('Shared.my_setting') : 'Pengaturan' ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo site_url('login/out') ?>"><i class="ti-power-off m-r-5"></i> <?= !empty(lang('Shared.my_logout')) ? lang('Shared.my_logout') : 'Keluar' ?></a></li>
                        </ul>
                    </li>

                </ul> <!-- end navbar-right -->

            </div><!-- end navbar -->
        </div>
        <!-- Top Bar End -->


        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu" id="left-side-menu">
            <div class="sidebar-inner slimscrollleft">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <ul>
                        <li class="menu-title">Navigasi</li>
                        <?= create_menu($menu); ?>
                    </ul>
                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>
            </div>
            <!-- Sidebar -left -->
        </div>
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <input type="hidden" name="<?= csrf_token() ?>" id="<?= csrf_token()?>" value="<?= csrf_hash() ?>"/>
            <!-- Start content -->
            <div class="content">
                <?= $this->renderSection('content') ?>        
            </div>
            <!-- End content -->
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->

        <footer class="footer text-right">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2023 <a href="<?= MAIN_WEB ?>" class="text-primary m-l-5"><b><?= APP_NAME ?></b></a>
                </div>
                <div class="col-sm-6">
                    <div class="btn-group btn-toggle">
                        <button id="btn_lang_id" class="btn btn-xs <?= (get_cookie('lang_code', true) == "ID") ? 'btn-custom' : 'btn-default' ?>">ID</button>
                        <button id="btn_lang_en" class="btn btn-xs <?= (get_cookie('lang_code', true) == "ID") ? 'btn-default' : 'btn-custom' ?>">EN</button>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- END wrapper -->

    <script>
        var resizefunc = [];
        var lang = <?= json_encode(get_all_lang()); ?>;
        var lang_code = '<?= get_cookie('lang_code', true); ?>';
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
    <script src="<?= $template_url ?>/plugins/switchery/switchery.min.js"></script>
    <!-- add jquery multiple select -->
    <script src="<?= $template_url ?>/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>

    <!-- added by taufik 20210427 -->
    <!-- OwlCarousel -->
    <script src="<?= $template_url ?>/plugins/OwlCarousel2/owl.carousel.min.js"></script>

    <!-- Counter js  -->
    <script src="<?= $template_url ?>/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="<?= $template_url ?>/plugins/counterup/jquery.counterup.min.js"></script>

    <script src="<?= $template_url ?>/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/dataTables.bootstrap.js"></script>

    <script src="<?= $template_url ?>/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/buttons.bootstrap.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/pdfmake.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/vfs_fonts.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/buttons.html5.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/buttons.print.min.js"></script>
    <?php if (strtolower($uri->setSilent()->getSegment(1)) == 'employee' && strtolower($uri->setSilent()->getSegment(2)) != 'upload_employee' || strtolower($uri->setSilent()->getSegment(1)) != 'employee') { ?>
        <script src="<?= $template_url ?>/plugins/datatables/dataTables.fixedHeader.min.js"></script>
    <?php } ?>
    <script src="<?= $template_url ?>/plugins/datatables/dataTables.keyTable.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/responsive.bootstrap.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/dataTables.scroller.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/dataTables.colVis.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/dataTables.fixedColumns.min.js"></script>
    <script src="<?= $template_url ?>/plugins/datatables/jquery.dataTables.rowGrouping.js"></script>

    <!-- added by yoga 20170323 -->
    <script src="<?= $template_url ?>/default/assets/js/jquery.fileDownload.js" type="text/javascript"></script>
    <script src="<?= $template_url ?>/plugins/tooltipster/tooltipster.bundle.min.js"></script>
    <script src="<?= $template_url ?>/default/assets/pages/jquery.tooltipster.js"></script>
    <script src="<?= $template_url ?>/plugins/ion-rangeslider/ion.rangeSlider.min.js"></script>
    <!-- end -->

    <!-- added by nanin mulyani 20170323 -->
    <script src="<?= $template_url ?>/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
    <!-- end -->

    <!-- added by nanin mulyani 20170308 -->
    <!--Morris Chart-->
    <script src="<?= $template_url ?>/plugins/morris/morris.min.js"></script>
    <script src="<?= $template_url ?>/plugins/raphael/raphael-min.js"></script>
    <!--  end add -->

    <!-- added by nanin mulyani 20170309 -->
    <!--Chartist Chart-->
    <script src="<?= $template_url ?>/plugins/chartist/js/chartist.min.js"></script>
    <script src="<?= $template_url ?>/plugins/chartist/js/chartist-plugin-tooltip.min.js"></script>
    <!--  end add -->

    <!-- added by achmad@arkamaya.co.id 20170407 -->
    <!--Chartist Chart-->
    <script src="<?= $template_url ?>/plugins/clockpicker/js/bootstrap-clockpicker.min.js"></script>
    <!--  end add -->

    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.min.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.time.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.tooltip.min.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.resize.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.pie.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.selection.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.stack.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.orderBars.min.js"></script>
    <script src="<?= $template_url ?>/plugins/flot-chart/jquery.flot.crosshair.js"></script>

    <!--<script src="<?= $template_url ?>/plugins/select2/select2/select2.js" type="text/javascript"></script> -->
    <script src="<?= $template_url ?>/plugins/select2/select2/select2.full.js" type="text/javascript"></script>

    <script src="<?= $template_url ?>/plugins/moment/moment.js"></script>
    <script src="<?= $template_url ?>/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?= $template_url ?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <!-- time picker -->
    <script src="<?= $template_url ?>/plugins/timepicker/bootstrap-timepicker.js"></script>

    <script src="<?= $template_url ?>/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

    <!-- responsive-table-->
    <script src="<?= $template_url ?>/plugins/responsive-table/js/rwd-table.min.js" type="text/javascript"></script>

    <!-- Toastr js -->
    <script src="<?= $template_url ?>/plugins/toastr/toastr.min.js"></script>

    <!-- CountUp js -->
    <script src="<?= $template_url ?>/plugins/countUp/countup.min.js"></script>

    <!-- Chart js -->
    <script src="<?= $template_url ?>/plugins/chart.js/chart.min.js"></script>
    <script src="<?= $template_url ?>/plugins/chart.js/chartjs-plugin-datalabels.min.js"></script>

    <script src="<?= $template_url ?>/plugins/jquery-validate/jquery.validate.js"></script>
    <script src="<?= $base_url ?>jsapp/jquery.validate.message.js"></script>
    <script src="<?= $template_url ?>/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
    <script src="<?= $template_url ?>/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script>

    <script src="<?= $template_url ?>/plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>

    <!-- KNOB JS -->
    <!--[if IE]>
<script type="text/javascript" src="<?= $template_url ?>/plugins/jquery-knob/excanvas.js"></script>
<![endif]-->
    <script src="<?= $template_url ?>/plugins/jquery-knob/jquery.knob.js"></script>

    <!-- added by misbah 20170221 -->
    <script type="text/javascript" src="<?= $template_url ?>/plugins/jquery-treegrid/js/jquery.treegrid.js"></script>

    <script src="<?= $template_url ?>/plugins/jquery.filer/js/jquery.filer.min.js"></script>

    <!--fullcalendar-->
    <script src='<?= $template_url ?>/plugins/fullcalendar/js/fullcalendar.min.js'></script>
    <!-- App js -->
    <script src="<?= $template_url ?>/default/assets/js/jquery.core.js"></script>
    <script src="<?= $template_url ?>/default/assets/js/jquery.app.js"></script>

    <script type="text/javascript" src="<?= $base_url ?>jsapp/default.js"></script>

    <!--  added by nanin 20170228 -->
    <!-- summernote -->
    <script src='<?= $template_url ?>/plugins/summernote/summernote.min.js'></script>


    <!-- page specific js -->
    <!--<script src="<?= $template_url ?>/default/assets/pages/jquery.fileuploads.init.js"></script>-->

    <!-- end -->
    <script src="<?= $template_url ?>/plugins/morris/morris.min.js"></script>
    <script src="<?= $template_url ?>/plugins/raphael/raphael-min.js"></script>

    <!-- organization structure chart  -->
    <script src="<?= $template_url ?>/plugins/autocomplete/jquery.mockjax.js" type="text/javascript"></script>
    <script src="<?= $template_url ?>/plugins/jquery-orgchart/jquery.orgchart.js" type="text/javascript"></script>
    <!-- organization structure chart  -->

    <script>
        $(document).ready(function() {
            if (lang_code == 'EN') {
                $.fn.select2.defaults.set('language', 'en');
            }

            $('.dt_picker').datepicker({
                autoclose: true,
                todayHighlight: false,
                format: 'dd/mm/yyyy',
            });
        });
    </script>
    <?php if (isset($pluginjs)) {
        foreach ($pluginjs as $js) { ?>
            <script type="text/javascript" src="<?php echo $js ?>"></script>
    <?php }
    } ?>
    <?php if ($uri->setSilent()->getSegment(1) == 'dashboard') : ?>
        <script type="text/javascript">
            $('#reportrange span').html('<?php echo $start_dt_span ?> - <?php echo $end_dt_span ?>');
            $('#reportrange').daterangepicker({
                startDate: '<?php echo $start_dt ?>',
                endDate: '<?php echo $end_dt ?>',
                minDate: '01/01/2014',
                maxDate: '31/12/2044',
                dateLimit: {
                    days: 365
                },
                showDropdowns: true,
                ranges: {
                    'Periode Kerja': ['<?php echo $start_dt ?>', '<?php echo $end_dt ?>'],
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-success',
                cancelClass: 'btn-default',
                locale: {
                    applyLabel: 'Simpan',
                    cancelLabel: 'Batal',
                    format: 'DD/MM/YYYY'
                }
            }, function(start, end, label) {
                $.ajax({
                    type: 'POST',
                    data: {
                        sd: start.format('YYYY-MM-DD'),
                        ed: end.format('YYYY-MM-DD')
                    },
                    url: SITE_URL + 'dashboard/get_infograph',
                    dataType: 'json',
                    success: function(res) {
                        $('#ot_hour_claim').html(res.ot_hour_claim);
                        $('#ot_hour_approved').html(res.ot_hour_approved);
                        $('#reimburse_claim').html(res.reimburse_claim);
                        $('#reimburse_approved').html(res.reimburse_approved);
                        $('#task_total').html(res.task_total);
                        $('#task_pending').html(res.task_pending);
                    }
                });

                $('#reportrange span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
            });
        </script>
    <?php elseif ($uri->setSilent()->getSegment(1) == 'dashboard2') : ?>
        <script type="text/javascript">
            var dt_from = "";
            var dt_to = "";
            var par = 5;
            var color = ["#ff9900", "#3399cc", "#ffcc00", "#00ff00", "#336699", "#ff0000", "#b21b21"];

            function formatMoney(n) {
                n = (Number(n).toFixed(2) + '').split('.');
                //console.log(n);
                return 'Rp. ' + n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',' + (n[1] || '00');
            }
            var MorrisCharts = function() {};
            MorrisCharts.prototype.createBarChart = function(element, data, xkey, ykeys, labels, lineColors) {
                $('#' + element).empty();
                Morris.Bar({
                    element: element,
                    data: data,
                    xkey: xkey,
                    ykeys: ykeys,
                    labels: labels,
                    hideHover: 'auto',
                    resize: true, //defaulted to true
                    gridLineColor: '#eeeeee',
                    barSizeRatio: 0.5,
                    xLabelAngle: 35,
                    barColors: lineColors
                });
            }

            MorrisCharts.prototype.createDonutChart = function(element, data, colors) {
                Morris.Donut({
                    element: element,
                    data: data,
                    resize: false, //defaulted to true
                    colors: colors
                });
            }

            MorrisCharts.prototype.init = function(param, date_from, date_to) {

                if (param == "") {
                    param = 5;
                }
                var $series = Array();
                var $series_bs = Array();
                var $series_realisasi = Array();
                var pl_series_name = Array();
                var pl_series_name_bs = Array();
                var that = this;

                $.ajax({
                    type: 'POST',
                    data: {
                        dt_from: date_from,
                        dt_to: date_to,
                        param: param
                    },
                    url: SITE_URL + 'dashboard2/get_infograph',
                    dataType: 'json',
                    success: function(res) {
                        $('#tbloperasional').empty();
                        var series = new Array();
                        $('#pie-chart').empty();
                        that.createDonutChart('pie-chart', res.morris, color);
                        $.each(res.data, function(i, item) {
                            /*var series_data = Array();
                             series_data.push(item.name);
                             series_data.push(item.y);
                             series.push(series_data);*/

                            $('#tbloperasional').append('<tr style="font-size:90%;"><td>' + item.name + '</td><td align="right">' + formatMoney(item.y) + '</td></tr>');
                        });

                    }
                });


                $.ajax({
                    type: 'POST',
                    data: {
                        dt_from: date_from,
                        dt_to: date_to,
                        param: param
                    },
                    url: SITE_URL + 'dashboard2/getSalesPurchases',
                    dataType: 'json',
                    success: function(res) {
                        $('.open_invoices').html(formatMoney(res.open_invoices));
                        $('.payment_received').html(formatMoney(res.payment_received));
                        $('.overdue_invoices').html(formatMoney(res.overdue_invoices));

                        $('.open_purchases').html(formatMoney(res.open_purchases));
                        $('.payment_sent').html(formatMoney(res.payment_sent));
                        $('.overdue_purchases').html(formatMoney(res.overdue_purchases));
                    }
                });

                $.ajax({
                    type: 'POST',
                    data: {
                        dt_from: date_from,
                        dt_to: date_to,
                        param: param
                    },
                    url: SITE_URL + 'dashboard2/get_ranged_profit_loss',
                    dataType: 'json',
                    success: function(res) {
                        $('#tblDataList').empty();
                        $('#tblDataList').append('');

                        $.each(res.data[0].datas, function(i, item) {
                            var series_data = {};
                            $('#tblDataList').append('<tr style="border-bottom:solid #ccc 1px;"><td style="padding:5px 5px 5px 5px;">' + item.key + '</td><td  style="padding:5px 5px 5px 5px;" align="right">' + formatMoney(item.laba) + '</td></tr>')

                            series_data['y'] = item.key;
                            for (var inc = 0; inc < res.series.length; inc++) {
                                series_data[res.series[inc].name] = res.series[inc].data[i];
                            }
                            $series.push(series_data);
                        });

                        for (var inc = 0; inc < res.series.length; inc++) {
                            pl_series_name.push(res.series[inc].name);
                        }
                        that.createBarChart('chart', $series, 'y', pl_series_name, pl_series_name, color);
                    }

                });

                $.ajax({
                    type: 'POST',
                    data: {
                        dt_from: date_from,
                        dt_to: date_to,
                        param: param
                    },
                    url: SITE_URL + 'dashboard2/get_ranged_balance_sheet',
                    dataType: 'json',
                    success: function(res) {
                        var series = new Array();
                        $.each(res.series, function(i, item) {
                            var series_data = {};
                            series_data['y'] = item.name;
                            series_data['a'] = item.y;
                            $series_bs.push(series_data);
                        });
                        that.createBarChart('chart_bs', $series_bs, 'y', 'a', 'Total', color);
                    }
                });
            }
            $.MorrisCharts = new MorrisCharts, $.MorrisCharts.Constructor = MorrisCharts;



            $('#reportrange span').html('Tahunan');
            $('#reportrange').daterangepicker({
                startDate: '<?php echo $start_dt ?>',
                endDate: '<?php echo $end_dt ?>',
                minDate: '01/01/2014',
                maxDate: '31/12/2044',
                dateLimit: {
                    days: 365
                },
                showDropdowns: true,
                ranges: {
                    //'Periode Kerja': ['<?php echo $start_dt ?>', '<?php echo $end_dt ?>'],
                    'Harian': [moment(), moment()],
                    'Mingguan': [moment().subtract(6, 'days'), moment()],
                    'Bulanan': [moment().subtract(1, 'months'), moment()],
                    'Triwulan': [moment().startOf('month'), moment().endOf('month')],
                    'Tahunan': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-success',
                cancelClass: 'btn-default',
                locale: {
                    applyLabel: 'Simpan',
                    cancelLabel: 'Batal',
                    format: 'DD/MM/YYYY'
                }
            }, function(start, end, label) {
                //$('#reportrange span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
                var param = null;

                switch (label) {
                    case "Harian":
                        param = 1;
                        break;
                    case "Mingguan":
                        param = 2;
                        break;
                    case "Bulanan":
                        param = 3;
                        break;
                    case "Triwulan":
                        param = 4;
                        break;
                    case "Tahunan":
                        param = 5;
                        break;
                    case "Custom Range":
                        param = 6;
                        break;
                    default:
                        param = 5;
                        label = "Tahunan";
                }

                if (label == "Custom Range") {
                    $('#reportrange span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
                } else {
                    $('#reportrange span').html(label);
                }

                $('.pl-header').html('Profit & Loss (' + label + ')');
                $('.bs-header').html('Balance Sheet (' + label + ')');
                $('.header-top').html('Biaya Operasional (' + label + ')');
                $('.header-top5').html('Top 5 Biaya Operasional (' + label + ')');
                $('.header-sales').html('Penjualan (' + label + ')');
                $('.header-purchase').html('Pembelian (' + label + ')');
                //load_graph(param, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
                dt_from = start.format('YYYY-MM-DD');
                dt_to = end.format('YYYY-MM-DD');
                par = param;
                $.MorrisCharts.init(param, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

            });

            // load_graph(1, '', '');
            $.MorrisCharts.init(5, '', '');
            $('.pl-header').html('Profit & Loss (Tahunan)');
            $('.bs-header').html('Balance Sheet (Tahunan)');
            $('.header-top').html('Biaya Operasional (Tahunan)');
            $('.header-top5').html('Top 5 Biaya Operasional (Tahunan)');
            $('.header-sales').html('Penjualan (Tahunan)');
            $('.header-purchase').html('Pembelian (Tahunan)');

            function reload() {
                $.MorrisCharts.init(par, dt_from, dt_to);
            }
        </script>
    <?php endif; ?>

    <?php if ($uri->setSilent()->getSegment(1) == 'project') : ?>
        <script src="<?= $template_url ?>/default/assets/js/readmore.min.js"></script>
    <?php endif; ?>

    <!-- js/app -->
    <script type="text/javascript" src="<?= $template_url ?>/plugins/tinymce/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="<?= $template_url ?>/plugins/tinymce/js/tinymce/plugins/jbimages/plugin.min.js"></script>
    <script src="<?= $template_url ?>/plugins/jquery.timeago.js" type="text/javascript"></script>
    <script src="<?= $template_url ?>/plugins/jquery.events.input.js" type="text/javascript"></script>
    <script src="<?= $template_url ?>/plugins/jquery.elastic.js" type="text/javascript"></script>
    <script src="<?= $template_url ?>/plugins/jquery.mentionsInput.js" type="text/javascript"></script>
    <script src="<?= $template_url ?>/plugins/raty/jquery.raty.js" type="text/javascript"></script>

    <!--  added by moharifrifai 20210318 -->
    <script src="<?= $template_url ?>/plugins/intro-js/intro.js"></script>
    <script src="<?= $template_url ?>/plugins/cropper/js/cropper.js"></script>
    <script src="<?= $template_url ?>/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?= $template_url ?>/plugins/tooltipster/tooltipster.bundle.min.js"></script>
    <script src="<?= $template_url ?>/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="<?= $template_url ?>/plugins/jquery.filer/js/jquery.filer.js"></script>
    <script src="<?= $template_url ?>/plugins/websocket/fancywebsocket.js"></script>
    <script src="<?= $template_url ?>/plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <script src="<?= $base_url ?>jsapp/lang.js"></script>
    <script src="<?= $template_url ?>/plugins/html2canvas/html2canvas.min.js"></script>
    <?php if (isset($jsapp)) : foreach ($jsapp as $js) : ?>
            <script type="text/javascript" src="<?= $base_url ?>jsapp/<?php echo $js ?>.js?key=<?php echo date("YmdHis"); ?>"></script>
    <?php
        endforeach;
    endif;
    ?>

    <script>
        $.ajaxSetup({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-Security-Policy', "default-src 'self' *; font-src 'self' *; img-src 'self' *; style-src 'self' 'unsafe-inline' *; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;");
            }
        });
        // $('#modalExpired').modal('show');
    </script>
    <!-- edit -->
    <?php

    // update 13-Juni-2019
    // --- akun akan aktif selamanya kecuali belum bayar.

    // if ($session->get(S_IS_EXPIRED) == 1 && $this->router->fetch_class() != 'billing') { 
    ?>
        <!-- <script src="<?php //echo base_url() 
                            ?>plugins/bootstrap-sweetalert/sweet-alert.min.js"></script>-->
        <script src="<?= $template_url ?>/plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script>
            //alert 1
            //toastr["info"]("Akun anda sudah <b><i>expired</i></b> harap lakukan pembayaran, untuk mengaktifkan kembali.", "Informasi");

            //alert 2
            // setTimeout(function() {
            //     Swal.fire({
            //         title: 'Informasi',
            //         icon: 'info',
            //         width: 400,
            //         customClass: {
            //             confirmButton: 'btn-info btn-md waves-effect waves-light',
            //             cancelButton: 'btn btn-danger'
            //         },
            //         buttonsStyling: false,
            //         html: 'Akun anda sudah tidak aktif,\nharap lakukan pembayaran untuk mengaktifkan kembali.' +
            //             '<br>Hubungi <b><a href="https://wa.me/+6281802152157" target="_blank"> Farid (Whatsapp)</a></b> ',
            //         showCancelButton: false,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Sembunyikan!'
            //     });


            // }, 200);
        </script>
    <?php //} ?>
    <script>
        let page = 2;

        function readAll() {
            $.ajax({
                type: 'GET',
                data: {
                    cpms_token: $('#cpms_token').val()
                },
                url: SITE_URL + 'home/readAll',
                dataType: 'json',
                success: function(res) {
                    location.reload();
                }
            });
        }

        function readAndGo(url, id) {
            $.ajax({
                type: 'POST',
                data: {
                    notification_id: id,
                    cpms_token: $('#cpms_token').val()
                },
                url: SITE_URL + 'home/readAndGo',
                dataType: 'json',
                success: function(res) {
                    console.log(SITE_URL + url);
                    window.location.replace(SITE_URL + url)

                }
            });
        }

        function readAndGo(url, id) {
            $.ajax({
                type: 'POST',
                data: {
                    notification_id: id,
                    cpms_token: $('#cpms_token').val()
                },
                url: SITE_URL + 'home/readAndGo',
                dataType: 'json',
                success: function(res) {
                    console.log(SITE_URL + url);
                    window.location.replace(SITE_URL + url)

                }
            });
        }

        function showMore() {
            $.ajax({
                type: 'POST',
                data: {
                    page: page,
                    cpms_token: $('#cpms_token').val()
                },
                url: SITE_URL + 'home/showMore',
                dataType: 'json',
                success: function(res) {
                    let container = $('#notification-container');

                    $(".empty-notif").remove();
                    if (res.status === true) {
                        let data = res.data;
                        let newRow = '';

                        for (var i = 0; i < data.length; i++) {
                            newRow += '<a href="javascript:void(0);" onclick="readAndGo("' + data[i].link_location + '",' + data[i].notification_id + ')" class="dropdown-item notify-item">';
                            newRow += '<div class="notify-icon bg-info">';
                            newRow += '<i class="mdi mdi-bell-outline"></i>';
                            newRow += '</div>';
                            newRow += '<p class="notify-details">' + data[i].notification_title;
                            newRow += '<small class="text-muted">' + data[i].notification_content + '</small>'
                            newRow += '</p>'
                            newRow += '</a>';
                        }
                        container.append(newRow);
                        page++;
                    } else {
                        let newRow = '';
                        newRow += '<div class="empty-notif text-center">';
                        newRow += '<small class="text-muted">Data notification not available.</small>'
                        newRow += '</div>';
                        container.append(newRow);
                    }
                }
            });
            event.stopPropagation();
        }

        function toggleFilter(button) {
            var panelBody = document.querySelector('.panel-body');
            panelBody.classList.toggle('hidden');

            var btnSearch = document.getElementById('btn_search');
            btnSearch.classList.toggle('hidden');
            
            var btnReset = document.getElementById('btn_reset');
            btnReset.classList.toggle('hidden');
            
            var filterIcon = document.getElementById('filterIcon');
            filterIcon.classList.toggle('mdi-filter-remove');
            filterIcon.classList.toggle('mdi-filter');
            
            // Toggle title attribute
            if (button.title === "hide-filter") {
                button.title = "Show Filters";
            } else {
                button.title = "Hide Filters";
            }
        }


        function redirectTo(title, type, redirectTo){
            const defaultOptions = {
                title: title,
                icon: type,
                width: 400,
                customClass: {
                    confirmButton: 'btn-custom btn-md waves-effect waves-light',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
            };

            Swal.fire(defaultOptions).then((result) => {
                if (result.isConfirmed) {
                    if(redirectTo){
                        window.location.href = SITE_URL + redirectTo;
                    }
                } 
            });
        }

    </script>
</body>

</html>