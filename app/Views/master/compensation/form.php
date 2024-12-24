<!-- extends layout -->
<?= $this->extend('layouts/default/index') ?>



<?= $this->section('content') ?>
<div class="container">
    <!-- Breadcrumb Section -->
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title"><?= $function_name ?></h4>
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
                    <input type="hidden" name="old_compensation_id" value="<?= isset($compensation) ? $compensation->compensation_id : null ?>">
                    <input type="hidden" name="old_company_id" value="<?= isset($compensation) ? $compensation->company_id : null ?>">
                    <input type="hidden" name="old_work_unit_id" value="<?= isset($compensation) ? $compensation->work_unit_id : null ?>">
                    <input type="hidden" name="old_employee_id" value="<?= isset($compensation) ? $compensation->employee_id : null ?>">
                    <input type="hidden" name="old_compensation_type" value="<?= isset($compensation) ? $compensation->compensation_type : null ?>">
                    <input type="hidden" name="old_period" value="<?= isset($compensation) ? $compensation->period : null ?>">
                    <input type="hidden" name="old_total_compensation" value="<?= isset($compensation) ? $compensation->total_compensation : null ?>">
                    <input type="hidden" name="old_description" value="<?= isset($compensation) ? $compensation->compensation_description : null ?>">
                    <!-- left side form -->
                    <div class="col-md-6">
                        <div class="row form-group">
                            <label class="col-md-3 control-label"><?= lang('Compensation.inquiry.company_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" id="company-options" name="company_id" required placeholder="-Select-">     
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <option value="<?= $compensation->company_id ?>" selected><?= $compensation->company_code ?></option>
                                    <?php
                                    endif;
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-3 control-label"> <?= lang('Compensation.inquiry.area_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" id="area-options" name="work_unit_id" required placeholder="-Select-">
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <option value="<?= $compensation->work_unit_id ?>" selected ><?= $compensation->name ?></option>
                                    <?php
                                    endif;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-3 control-label"> <?= lang('Compensation.create.employee_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" id="employee-options" name="employee_id" required placeholder="-Select-">
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <option value="<?= $compensation->employee_id ?>" selected ><?= $compensation->employee_name ?></option>
                                    <?php
                                    endif;
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-3 control-label"> <?= lang('Compensation.create.compensation_type') ?> <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" id="compensationType-options" name="compensation_type" required placeholder="-Select-">
                                    <?php
                                    if (isset($compensation)) :
                                    ?>
                                        <option value="<?= $compensation->compensation_type ?>" selected><?= $compensation->system_value_txt ?></option>
                                    <?php
                                    endif;
                                    ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <!-- right side form -->
                    <div class="col-md-6">
                        <div class="row form-group">
                            <label class="col-md-4 control-label"><?= lang('Compensation.inquiry.period') ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="period" id="period" class="text-center form-control dt_picker_compensation" value="<?= isset($compensation) ? $compensation->period : null ?>" required data-date-format="mm/yyyy" data-min-view-mode="months" />
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-4 control-label" style="padding-right: 2rem;"><?= lang('Compensation.inquiry.total_compensation') ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control nominal text-right" id="compensation-amount" name="total_compensation" required value="<?= isset($compensation) ? $compensation->total_compensation : null ?>">
                            </div>
                        </div>
                        <div class="row m-t-5 form-group">
                            <label class="col-md-4 control-label"><?= lang('Compensation.create.compensation_description') ?></label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="description" name="compensation_description" rows="3"><?= isset($compensation) ? $compensation->compensation_description : null ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <?= create_button($button, "btn_submit"); ?>
        <?= create_button($button, "btn_cancel"); ?>
    </div>
</div>

<?= $this->endSection() ?>