<!-- extends layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    .not-bold {
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
        padding-top: 0.6rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <!-- Breadcrumb Section -->
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title"><?= $function_name ?> Detail</h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li class="active"><?= $function_name ?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="panel panel-body" style="padding-right: 0.5rem;padding-left: 0.5rem;">
        <div class="container">
            <!-- Breadcrumb Section -->
            <div class="row">
                <form id="formCompensation" class="form-horizontal" action="" role="form" method="post">
                    <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                    <!-- left side form -->
                    <div class="col-md-6">
                        <div class="row form-group">
                            <label class="col-md-3 control-label"><?= lang('Compensation.inquiry.company_name') ?></label>
                            <div class="col-md-9">
                                <label class="not-bold" id="company-options">
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <?= $compensation->company_name ?>
                                    <?php
                                    endif;
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-3 control-label"> <?= lang('Compensation.inquiry.area_name') ?> </label>
                            <div class="col-md-9">
                                <label class="not-bold" id="area-options">
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <?= $compensation->name ?>
                                    <?php
                                    endif;
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-3 control-label"> <?= lang('Compensation.create.employee_name') ?> </label>
                            <div class="col-md-9">

                                <label class="not-bold" id="employee-options">
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <?= $compensation->employee_name ?>
                                    <?php
                                    endif;
                                    ?>
                                </label>

                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-3 control-label"> <?= lang('Compensation.create.compensation_type') ?> </label>
                            <div class="col-md-9">
                                <label class="not-bold" id="compensationType-options">
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <?= $compensation->system_value_txt ?>
                                    <?php
                                    endif;
                                    ?>
                                </label>

                            </div>
                        </div>
                    </div>
                    <!-- right side form -->
                    <div class="col-md-6">
                        <div class="row form-group">
                            <label class="col-md-4 control-label"><?= lang('Compensation.inquiry.period') ?> </label>
                            <div class="col-md-8">
                                <label class="not-bold" id="period"><?= isset($compensation) ? (new DateTime($compensation->period))->format('F Y') : null ?></label>
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-4 control-label"><?= lang('Compensation.inquiry.total_compensation') ?> </label>
                            <div class="col-md-8">
                                <label class="not-bold nominal" id="compensation-amount"><?= isset($compensation) ? $compensation->total_compensation : null ?></label>
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-4 control-label"><?= lang('Compensation.create.compensation_description') ?> </label>
                            <div class="col-md-8">
                                <label class="not-bold" id="description"><?= isset($compensation) ? $compensation->compensation_description : null ?></label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- tabel section -->
            <!-- <div class="container">
                <h4 class="m-t-5 m-r-5">LOG ACTIVITY</h4>
                <hr>
                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-hover table-colored table-custom">
                        <thead>
                            <tr>
                                <th>Tanggal dibuat</th>
                                <th>Dibuat Oleh</th>
                                <th class="c ol-md-6">Aktivitas</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div> -->
            <?= view('shared/payroll_logs_inquiry', ['function_id' => $function_id, ['refference_id' => $refference_id]]) ?>
        </div>
    </div>

    <div class="container">
        <button type="button" class="btn btn-primary m-r-5" onclick="window.location.href = window.location = SITE_URL + 'master_kompensasi/edit/' + <?= $compensation->compensation_id ?>">Edit</button>
        <?= create_button($button, "btn_cancel"); ?>
    </div>
</div>

</div>
<?= $this->endSection() ?>