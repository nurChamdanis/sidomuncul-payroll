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
</style>
<?= $this->endSection() ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title"><?= lang('Shared.id') ?> <?= $function_name ?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li>
                        <a href="<?php echo site_url('master_loan') ?>"><?= $function_name ?></a>
                    </li>
                    <li class="active"><?= lang('Shared.id') ?> <?= $function_name ?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form id="formMasterTunjangan" class="form-horizontal" role="form" method="post" action="">
                <input type="hidden" id="segment" value="<?= service('uri')->getSegment(2)?>"/>
                <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                <input type="hidden" name="basic_salary_id" id="basic_salary_id" value="<?= isset($salary) ? $salary->basic_salary_id : null ?>" />
                <input type="hidden"  class="nominal" value="<?= isset($salary) ? $salary->basic_salary : 0 ?>" name="basic_salary" id="basic_salary" required />
                <div class="card-box" style="padding-left: 2%; padding-right: 2%;">
                    <div class="row m-b-20">
                        <div class="col-md-5">
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Shared.label.company') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($salary)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $salary->company_code ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper" class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Shared.label.area') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($salary)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $salary->work_unit_name ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Loan.filter.employee') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($salary)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $salary->employee_name ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.PTKP') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($salary)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $salary->status_ptkp_name ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.employee_group') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($salary)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $salary->employee_category_name ?></label>
                                        <?php endif ?>  
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.basic_salary') ?> </label>
                                <div class="col-md-9">
                                    <?php if (isset($salary)) : ?>
                                        <label class="m-t-10 nominal" style="font-weight: normal;"><?= $salary->basic_salary ?></label>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="startDate"  class="col-sm-3 m-t-10 col-form-label"><?= lang('Loan.filter.period') ?> </label>
                                <div class="col-sm-2">
                                    <?php if (isset($salary)) : ?>
                                        <label class="m-t-10" style="font-weight: normal;"><?= $salary->attendance_date_start ?></label>
                                    <?php endif ?>
                                </div>
                                <label for="endDate" class="col-sm-1 m-t-10 col-form-label text-center"><?= lang('Loan.filter.to') ?> </label>
                                <div class="col-sm-2">
                                    <?php if (isset($salary)) : ?>
                                        <label class="m-t-10" style="font-weight: normal;"><?= $salary->attendance_date_end ?></label>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.count_pph21') ?> </label>
                                <div class="col-md-9">
                                    <?php 
                                    if (isset($salary)): 
                                        $checked = ($salary->calc_pph21_flg == '1') ? "checked" : "";    
                                    ?>
                                        <input type="checkbox" class="form-check-input m-t-10" <?= $checked; ?> disabled id="pph21_flg" name="pph21_flg">
                                    <?php 
                                    endif 
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.gross_up') ?> </label>
                                <div class="col-md-9">
                                    <?php 
                                    if (isset($salary)) : 
                                        $checked = ($salary->calc_grossup_flg == '1') ? "checked" : ""; 
                                    ?>
                                        <input type="checkbox" class="form-check-input m-t-10" <?= $checked; ?> disabled id="grossup_flg" name="grossup_flg">
                                    <?php 
                                    endif 
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.effective_date') ?> </label>
                                <div class="col-md-9">
                                    <?php if (isset($salary)) : ?>
                                        <label class="m-t-10" style="font-weight: normal;"><?= $salary->effective_date_start ?></label>
                                    <?php endif ?>
                                </div>
                            </div>  
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.effective_date') . " " .  lang('Salaries.inquiry.bpjs')?></label>
                                <div class="col-md-9">
                                    <?php if (isset($salary)) : ?>
                                        <label class="m-t-10" style="font-weight: normal;"><?= $salary->effective_date_bpjs ?></label>
                                    <?php endif ?>
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
                    <div class="total-section" style="margin-bottom: 4%;">
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
                    <?= view('shared/payroll_logs_inquiry', ['function_id' => $function_id, ['refference_id' => $refference_id]]) ?>
                </div>
                <?php echo create_button($button, "btn_edit", null, "{$salary->basic_salary_id}"); ?>
                <?php echo create_button($button, "btn_delete"); ?>
                <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_salaries') ?>'">Kembali</button>
            </form>
        </div>
    </div>
</div>

<!-- Start Modal Delete -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <form id="formDelete" name="form_delete" action="" method="post">
        <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
        <input type="hidden" name="basic_salary_id_hidden" value="<?= isset($salary) ? $salary->basic_salary_id : null ?>" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="deleteModalLabel"><?= lang('Allowances.modal.delete.title') ?></h4>
                </div>
                <div class="modal-body">
                    <?= lang('Allowances.modal.delete.confirm') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                        <?= lang('Allowances.modal.delete.back') ?>
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleSingleDelete()" id="btn_submit">
                        <?= lang('Allowances.modal.delete.submit') ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Start Modal Delete -->
<?= $this->endSection() ?>
<!-- End Content Section -->