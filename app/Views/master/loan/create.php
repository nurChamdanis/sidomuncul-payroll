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
                        <a href="<?php echo site_url('master_loan') ?>"><?= $function_name ?></a>
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

                <div class="card-box m-t-1 m-b-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div  id="system_type_wrapper" class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Shared.label.company') ?> <span class="text-danger">*</span></label>
                                <div  id="system_type_wrapper" class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="company_id" name="company_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.company') ?>">             </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Shared.label.area') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="work_unit_id" name="work_unit_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.area') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.filter.employee') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="employee_id" name="employee_id" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.filter.employee') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.inquiry.loan_type') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="loan_type" name="loan_type" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.inquiry.loan_type') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-t-10">
                                <label class="col-md-6 control-label"><?= lang('Loan.inquiry.loan_term') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <div>
                                        <select class="form-control select2" id="loan_duration" name="loan_duration" required placeholder="<?= lang('Shared.choose') . " " . lang('Loan.inquiry.loan_term') ?>"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row m-t-10">
                                <label for="startDate" class="col-sm-3 m-t-10 col-form-label"><?= lang('Loan.filter.period') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" autocomplete="off" class="form-control" id="periodFrom" name="periodFrom" required>
                                </div>
                                <label for="endDate" class="col-sm-1 m-t-10 col-form-label text-center"><?= lang('Loan.filter.to') ?> <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" readonly autocomplete="off" class="form-control" id="periodTo" name="periodTo" required>
                                </div>
                            </div>

                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.loan_amount') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" autocomplete="off" class="form-control nominal text-right" value=""  name="loan_total" id="loan_total" required />
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.monthly_deduction') ?> <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" autocomplete="off" class="form-control nominal text-right" id="monthly_deduction" name="monthly_deduction" readonly required/>
                                </div>
                            </div>
                            <div class="form-group row m-t-10">
                                <label class="col-md-3 control-label"><?= lang('Loan.inquiry.remark') ?> </label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="remark" id="remark"></textarea>
                                </div>
                            </div>            
                        </div>
                        
                    </div>
                </div>

                <div>
                    <?php echo create_button($button, "btn_create"); ?>
                    <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_loan') ?>'">Kembali</button>
                </div>

            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- End Content Section -->