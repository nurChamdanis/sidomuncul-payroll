<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<!-- Content Section -->
<?= $this->section('content') ?>
    <!-- Start Wrapper Content -->
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="page-title-box">
                    <h4 class="page-title"><?=$function_name?></h4>
                    <ol class="breadcrumb p-0 m-0">
                        <li><?=$function_grp_name;?></li>
                        <li class="active"><?=$function_name?></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12">
                <p style="font-size:18px">Hi <span style="font-size: 20px;"><strong><?php echo ucwords($session->get(S_EMPLOYEE_NAME)) ?></strong></span>, Welcome To Dashboard Project! </p>
            </div>
        </div>
    </div>
    <!-- End Wrapper Content -->
<?= $this->endSection() ?>