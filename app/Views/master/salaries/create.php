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
                <h4 class="page-title"><?=  lang('Shared.create'); ?> <?= $function_name ?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li>
                        <a href="<?php echo site_url('master_salaries') ?>"><?= $function_name ?></a>
                    </li>
                    <li class="active"><?=  lang('Shared.create') ; ?> <?= $function_name ?></li>
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
                <div class="card-box m-t-1 m-b-1 p-20">
                    <div class="row">
                        <div class="col-md-6">
                            <div  id="system_type_wrapper" class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Shared.label.company') ?> <span class="text-danger">*</span></label>
                                <div  id="system_type_wrapper" class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="company_id" name="company_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.company') ?>">             </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Shared.label.area') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="work_unit_id" name="work_unit_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.area') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Loan.filter.employee') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="employee_id" name="employee_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.filter.employee') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.PTKP') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="ptkp" name="ptkp" required placeholder="<?= lang('Shared.choose') . " " . lang('Salaries.inquiry.PTKP') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.employee_group') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="employee_group" name="employee_group" required placeholder="<?= lang('Shared.choose') . " " . lang('Salaries.inquiry.employee_group') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-5 control-label"><?= lang('Salaries.inquiry.basic_salary') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="text" autocomplete="off" class="form-control nominal text-right" value=""  name="basic_salary" id="basic_salary" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row m-t-10">
                                <label for="startDate" class="col-sm-3 m-t-10 col-form-label"><?= lang('Salaries.inquiry.period') . " " . lang('Salaries.inquiry.attendance') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <input type="text" autocomplete="off" class="form-control nominal" id="attendance_date_start" name="attendance_date_start" required>
                                </div>
                                <label for="endDate" class="col-sm-1 m-t-10 col-form-label text-center"><?= lang('Loan.filter.to') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <input type="text" autocomplete="off" class="form-control nominal" id="attendance_date_end" name="attendance_date_end" required>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.count_pph21') ?></label>
                                <div class="col-md-7">
                                    <input type="checkbox" class="form-check-input m-t-10" id="pph21_flg" name="pph21_flg">
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.gross_up') ?></label>
                                <div class="col-md-7">
                                    <input type="checkbox" class="form-check-input m-t-10" id="grossup_flg" name="grossup_flg">
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.effective_date')?> <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="text" autocomplete="off" class="form-control" id="effective_date" name="effective_date" required>
                                </div>
                            </div> 
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Salaries.inquiry.effective_date') . " " .  lang('Salaries.inquiry.bpjs')?> <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="text" autocomplete="off" class="form-control" id="effective_date_bpjs" name="effective_date_bpjs" required>
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
                    <?php echo create_button($button, "btn_create"); ?>
                    <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_salaries') ?>'">Kembali</button>
                </div>

            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- End Content Section -->