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
                <h4 class="page-title"><?= (!isset($loan) ? lang('Shared.create') : lang('Shared.edit')); ?> <?= $function_name ?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li>
                        <a href="<?php echo site_url('master_tunjangan') ?>"><?= $function_name ?></a>
                    </li>
                    <li class="active"><?= (!isset($loan) ? lang('Shared.create') : lang('Shared.edit')); ?> <?= $function_name ?></li>
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
                <input type="hidden" name="loan_id" value="<?= isset($loan) ? $loan->loan_id : null ?>" />
                <input type="hidden" name="remaining_loan" id="remaining_loan" value="<?= isset($loan) ? $loan->remaining_loan : null ?>" />

                <div class="card-box m-t-1 m-b-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Shared.label.company') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <input type="hidden" class="form-control" value="<?= isset($loan) ? $loan->company_id : null ?>" name="company_id" />
                                            <select class="form-control select2" id="_company_id" name="_company_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.company') ?>" <?= isset($loan) ? 'disabled' : '' ?>>
                                                    <option value="<?= $loan->company_id ?>" selected><?= $loan->company_code ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Shared.label.area') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <input type="hidden" class="form-control" value="<?= isset($loan) ? $loan->work_unit_id : null ?>" name="work_unit_id" />
                                            <select class="form-control select2" id="_work_unit_id" name="_work_unit_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.area') ?>" <?= isset($loan) ? 'disabled' : '' ?>>
                                                <option value="<?= $loan->work_unit_id ?>" selected><?= $loan->work_unit_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.filter.employee') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <input type="hidden" class="form-control" value="<?= isset($loan) ? $loan->employee_id : null ?>" name="employee_id" />
                                            <select class="form-control select2" id="_employee_id" name="_employee_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.filter.employee') ?>" <?= isset($loan) ? 'disabled' : '' ?>>
                                                <option value="<?= $loan->employee_id ?>" selected><?= $loan->employee_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.inquiry.loan_type') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <select class="form-control select2" id="loan_type" name="loan_type" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.filter.employee') ?>">
                                                <option value="<?= $loan->loan_type ?>" selected><?= $loan->loan_type_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.inquiry.loan_term') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <select class="form-control select2" id="loan_duration" name="loan_duration" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.filter.employee') ?>">
                                                <option value="<?= $loan->loan_duration ?>" selected><?= $loan->loan_duration_name ?></option>
                                            </select>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row m-t-10">
                                <label for="startDate" class="col-sm-3 m-t-10 col-form-label"><?= lang('Loan.filter.period') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" name="periodFrom" value="<?= $loan->deduction_period_start ?>" class="form-control" id="periodFrom">
                                </div>
                                <label for="endDate" class="col-sm-1 m-t-10 col-form-label text-center"><?= lang('Loan.filter.to') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" readonly name="periodTo" value="<?= $loan->deduction_period_end ?>" class="form-control" id="periodTo">
                                </div>
                            </div>

                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.loan_amount') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <?php if (isset($loan)) : ?>
                                        <input type="text" class="form-control text-right nominal" value="<?=  $loan->loan_total ?>" name="loan_total" id="loan_total" required />
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.monthly_deduction') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <?php if (isset($loan)) : ?>
                                        <input type="text" class="form-control text-right nominal" value="<?= $loan->monthly_deduction ?>" id="monthly_deduction" name="monthly_deduction" required  readonly/>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.remark') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <?php if (isset($loan)) : ?>
                                        <textarea class="form-control" name="remark" id="remark"><?= $loan->loan_description ?></textarea>
                                    <?php endif ?>
                                </div>
                            </div>            
                        </div>
                    </div>
                </div>

                <div>
                    <?php echo create_button($button, "btn_edit_action"); ?>
                    <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_loan') ?>'">Kembali</button>
                </div>

            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- End Content Section -->