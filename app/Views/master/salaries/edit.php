<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    @media (min-width: 992px) {
        .left-space {
            margin-left: 25%;
        }
    }

    ul {
        list-style-type: none;
        padding: 0px 5px;
        margin: 0px;
    }

    table.table-list thead>tr>th {
        padding-right: 0px !important;
    }

    table.table-list .checkbox {
        padding-left: 0px !important;
    }
</style>
<?= $this->endSection() ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
            <h4 class="page-title"><?= (!isset($salary) ? lang('Shared.create') : lang('Shared.edit')); ?> <?= $function_name ?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li>
                        <a href="<?php echo site_url('master_salaries') ?>"><?= $function_name ?></a>
                    </li>
                    <li class="active"><?= (!isset($salary) ? lang('Shared.create') : lang('Shared.edit')); ?> <?= $function_name ?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form id="formMasterLoan" class="form-horizontal" role="form" method="post" action="">
                <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                <input type="hidden" id="allowances" name="allowances">
                <input type="hidden" id="deductions" name="deductions">
                <input type="hidden" id="total_allowance_with_tax" name="total_allowance_with_tax">
                <input type="hidden" id="total_allowance_no_tax" name="total_allowance_no_tax">
                <input type="hidden" id="total_deduction_with_tax" name="total_deduction_with_tax">
                <input type="hidden" id="total_deduction_no_tax" name="total_deduction_no_tax">
                <input type="hidden" id="calc_pph21_flg" name="calc_pph21_flg">
                <input type="hidden" id="calc_grossup_flg" name="calc_grossup_flg">
                <input type="hidden" name="basic_salary_id" id="basic_salary_id" value="<?= $salary->basic_salary_id ?>" />
                <div class="card-box m-t-1 m-b-1 p-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div  id="system_type_wrapper" class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Shared.label.company') ?> <span class="text-danger">*</span></label>
                                <div  id="system_type_wrapper" class="col-md-6">
                                    <div>
                                        <?php if (isset($salary)) : ?>
                                            <input type="hidden" name="company_id" id="company_id" value="<?= $salary->company_id ?>">
                                            <select disabled class="form-control select2" id="_company_id" name="_company_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.company') ?>">
                                                <option value="<?= $salary->company_id ?>" selected><?= $salary->company_code ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Shared.label.area') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <?php if (isset($salary)) : ?>
                                            <input type="hidden" name="work_unit_id" id="work_unit_id" value="<?= $salary->work_unit_id ?>">
                                            <select disabled class="form-control select2" id="_work_unit_id" name="_work_unit_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.area') ?>">
                                                <option value="<?= $salary->work_unit_id ?>" selected><?= $salary->work_unit_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Loan.filter.employee') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <?php if (isset($salary)) : ?>
                                            <input type="hidden" name="employee_id" id="employee_id" value="<?= $salary->employee_id ?>">
                                            <select disabled class="form-control select2" id="_employee_id" name="_employee_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.filter.employee') ?>">
                                                <option value="<?= $salary->employee_id ?>" selected><?= $salary->employee_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.PTKP') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <?php if (isset($salary)) : ?>
                                            <input type="hidden" name="ptkp" id="ptkp" value="<?= $salary->status_ptkp ?>">
                                            <select disabled class="form-control select2" id="_ptkp" name="_ptkp" required placeholder="<?= lang('Shared.choose') . " " . lang('Salaries.inquiry.PTKP') ?>">
                                                <option value="<?= $salary->status_ptkp ?>" selected><?= $salary->status_ptkp_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.employee_group') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <?php if (isset($salary)) : ?>
                                            <input type="hidden" name="employee_group" id="employee_group" value="<?= $salary->employee_category ?>">
                                            <select disabled class="form-control select2" id="_employee_group" name="_employee_group" required placeholder="<?= lang('Shared.choose') . " " . lang('Salaries.inquiry.employee_group') ?>">
                                                <option value="<?= $salary->employee_category ?>" selected><?= $salary->employee_category_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.basic_salary') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <?php if (isset($salary)) : ?>
                                        <input type="text" autocomplete="off" class="form-control nominal text-right" value="<?= $salary->basic_salary ?>"  name="basic_salary" id="basic_salary" required />
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row m-t-10">
                                <label for="startDate" class="col-sm-3 m-t-10 col-form-label"><?= lang('Salaries.inquiry.period') . " " . lang('Salaries.inquiry.attendance') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <?php if (isset($salary)) : ?>
                                        <input type="text" autocomplete="off" class="form-control nominal" id="attendance_date_start" name="attendance_date_start" value="<?= $salary->attendance_date_start ?>" required>
                                    <?php endif ?>
                                </div>
                                <label for="endDate" class="col-sm-1 m-t-10 col-form-label text-center"><?= lang('Loan.filter.to') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <?php if (isset($salary)) : ?>
                                        <input type="text" autocomplete="off" value="<?= $salary->attendance_date_end ?>" class="form-control nominal" id="attendance_date_end" name="attendance_date_end" required>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.count_pph21') ?></label>
                                <div class="col-md-7">
                                    <?php 
                                    if (isset($salary)): 
                                        $checked = ($salary->calc_pph21_flg == '1') ? "checked" : "";    
                                    ?>
                                        <input type="checkbox" class="form-check-input m-t-10" <?= $checked; ?> id="pph21_flg" name="pph21_flg">
                                    <?php 
                                    endif 
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.gross_up') ?></label>
                                <div class="col-md-7">
                                    <?php 
                                    if (isset($salary)): 
                                        $checked = ($salary->calc_grossup_flg == '1') ? "checked" : "";    
                                    ?>
                                        <input type="checkbox" class="form-check-input m-t-10" <?= $checked; ?> id="grossup_flg" name="grossup_flg">
                                    <?php 
                                    endif 
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.effective_date')?> <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <?php if (isset($salary)) : ?>
                                        <input type="text" autocomplete="off" class="form-control" value="<?= $salary->effective_date_start ?>" id="effective_date" name="effective_date" required>
                                    <?php 
                                    endif 
                                    ?>
                                </div>
                            </div> 
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.effective_date') . " " .  lang('Salaries.inquiry.bpjs')?> <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <?php if (isset($salary)) : ?>
                                        <input type="text" autocomplete="off" class="form-control" value="<?= $salary->effective_date_bpjs ?>" id="effective_date_bpjs" name="effective_date_bpjs" required>
                                    <?php 
                                    endif 
                                    ?>
                                </div>
                            </div>          
                        </div>
                    </div>
                    <div class="component-section" style="display:none;">
                        <hr>
                        <div class="row m-t-40">
                            <div class="col-md-6 addition-card">
                                <div class="card-box m-t-1 m-b-1 p-20">
                                    <h5 class="text-uppercase"><?= lang('Salaries.inquiry.addition_components') ?></h5>
                                    <hr class="m-t-10">
                                    <div class="row list-allowance" style="padding-left: 2%;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 deduction-card">
                                <div class="card-box m-t-1 m-b-1 p-20">
                                    <h5 class="text-uppercase"><?= lang('Salaries.inquiry.deduction_components') ?></h5>
                                    <hr class="m-t-10">
                                    <div class="row list-deduction" style="padding-left: 2%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="total-section">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.total_deduction') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <div>
                                        <input type="text" autocomplete="off" class="form-control text-right nominal" id="total_deduction" name="total_deduction" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-t-20">
                                    <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.total_addition') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <div>
                                        <input type="text" autocomplete="off" class="form-control text-right nominal" id="total_allowance" name="total_allowance" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-t-20">
                                    <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.THP') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <div>
                                        <input type="text" autocomplete="off" class="form-control text-right nominal" id="THP" name="THP" readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="m-t-20">
                    <?php echo create_button($button, "btn_edit_action"); ?>
                    <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_salaries') ?>'">Kembali</button>
                </div>

            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- End Content Section -->