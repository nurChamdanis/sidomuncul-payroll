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
                <input type="hidden" name="loan_id" value="<?= isset($loan) ? $loan->loan_id : null ?>" />
                <div class="card-box" style="padding-left: 2%; padding-right: 2%;">
                    <div class="row m-b-20">
                        <div class="col-md-4">
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Shared.label.company') ?>  </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $loan->company_code ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Shared.label.area') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $loan->work_unit_name ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.filter.employee') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $loan->employee_name ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.inquiry.loan_type') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $loan->loan_type_name ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div id="system_type_wrapper m-t-20" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.inquiry.loan_term') ?> </label>
                                <div class="col-md-6">
                                    <div id="system_type_wrapper">
                                        <?php if (isset($loan)) : ?>
                                            <label class="m-t-5" style="font-weight: normal;"><?= $loan->loan_duration_name ?></label>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="startDate"  class="col-sm-3 m-t-10 col-form-label"><?= lang('Loan.filter.period') ?> </label>
                                <div class="col-sm-2">
                                    <?php if (isset($loan)) : ?>
                                        <label class="m-t-10" style="font-weight: normal;"><?= $loan->deduction_period_start ?></label>
                                    <?php endif ?>
                                </div>
                                <label for="endDate" class="col-sm-1 m-t-10 col-form-label text-center"><?= lang('Loan.filter.to') ?> </label>
                                <div class="col-sm-2">
                                    <?php if (isset($loan)) : ?>
                                        <label class="m-t-10" style="font-weight: normal;"><?= $loan->deduction_period_end ?></label>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.loan_amount') ?> </label>
                                <div class="col-md-9">
                                    <?php if (isset($loan)) : ?>
                                        <label class="m-t-10 nominal" style="font-weight: normal;"><?= $loan->loan_total ?></label>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.remaining_loan') ?> </label>
                                <div class="col-md-9">
                                    <?php if (isset($loan)) : ?>
                                        <label class="m-t-10 nominal" style="font-weight: normal;"><?= $loan->remaining_loan ?></label>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.monthly_deduction') ?> </label>
                                <div class="col-md-9">
                                    <?php if (isset($loan)) : ?>
                                        <label class="m-t-10 nominal" style="font-weight: normal;"><?= $loan->monthly_deduction ?></label>
                                    <?php endif ?>  
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.remark') ?> </label>
                                <div class="col-md-9">
                                    <?php if (isset($loan)) : ?>
                                        <label class="m-t-10" style="font-weight: normal;"><?= $loan->loan_description ?></label>
                                    <?php endif ?>
                                </div>
                            </div>            
                        </div>
                    </div>
                    <?= view('shared/payroll_logs_inquiry', ['function_id' => $function_id, ['refference_id' => $refference_id]]) ?>
                </div>
                <?php echo create_button($button, "btn_edit", null, "{$loan->loan_id}"); ?>
                <?php echo create_button($button, "btn_delete"); ?>
                <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_loan') ?>'">Kembali</button>
            </form>
        </div>
    </div>
</div>

<!-- Start Modal Delete -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <form id="formDelete" name="form_delete" action="" method="post">
        <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
        <input type="hidden" name="allowance_id" value="<?= isset($allowance) ? $allowance->allowance_id : null ?>" />
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