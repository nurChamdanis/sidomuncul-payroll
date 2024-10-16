<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    #table{width: 1500px !important;}
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

    p.label-optional{
        margin: 0px;
        padding: 5px 10px;
        border-bottom: 1px solid #ccc !important;
    }

    ul.payroll-generate li{
        margin-bottom: 3px;
    }

    table.dataTable thead > tr > th:first-child {padding-right: 0px !important; padding-left: 0px !important;}
    .checkboxtable{cursor: pointer;}
</style>
<?= $this->endSection() ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title"><?= $function_name ?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li>
                        <a href="<?php echo site_url('generate_payroll') ?>"><?= $function_name ?></a>
                    </li>
                    <li class="active"><?= $function_name ?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form id="formGeneratePayroll" class="form-horizontal" role="form" method="post" action="">
                <input type="hidden" id="segment" value="<?= service('uri')->getSegment(2)?>"/>
                <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                <input type="hidden" name="payroll_transaction_id" value="<?= isset($payroll_transaction) ? $payroll_transaction->payroll_transaction_id : null ?>" />
                <input type="hidden" id="employee_list" name="employee_list" value="" />
                <input type="hidden" id="total_employee_list_checked" name="total_employee_list_checked" value="0" />

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Form Generate Payroll Data</h3>
                        <p class="panel-sub-title font-13 text-muted"><?= lang('Shared.label.fillin') ?></p>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Shared.label.company') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8 mt-10">
                                        <select class="form-control select2" name="company_id" id="company_id" placeholder="<?= lang('Shared.choose') . ' '. lang('Shared.label.company') ?>" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Shared.label.area') ?></label>
                                    <div class="col-md-8 mt-10">
                                        <select class="form-control select2" name="work_unit_id" id="work_unit_id" placeholder="<?= lang('Shared.choose') . ' '. lang('Shared.label.area') ?>">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Shared.label.role') ?></label>
                                    <div class="col-md-8 mt-10">
                                        <select class="form-control select2" name="role_id" id="role_id" placeholder="<?= lang('Shared.choose').' '. lang('Shared.label.role') ?>">
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('GeneratePayroll.inquiry.salary_period') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8">						
                                        <input type="text" class="form-control payrollperiodpicker text-center" id="payroll_period" name="payroll_period" style="background: white !important;" required/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('GeneratePayroll.inquiry.cutoff') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8" style="display: flex; justify-content: space-between; gap: 15px; align-items: center;">						
                                        <div>
                                            <input type="text" class="form-control datepicker text-center" id="cut_off_start" name="cut_off_start" style="background: white !important;" required/>
                                        </div>
                                        <span>to</span>
                                        <div>
                                            <input type="text" class="form-control datepicker text-center" id="cut_off_end" name="cut_off_end" style="background: white !important;" required/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('GeneratePayroll.inquiry.period_absence') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8" style="display: flex; justify-content: space-between; gap: 15px; align-items: center;">						
                                        <div>
                                            <input type="text" class="form-control datepicker text-center" id="absence_period_start" name="absence_period_start" style="background: white !important;" required/>
                                        </div>
                                        <span>to</span>
                                        <div>
                                            <input type="text" class="form-control datepicker text-center" id="absence_period_end" name="absence_period_end" style="background: white !important;" required/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('GeneratePayroll.inquiry.description') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8">						
                                        <input type="text" class="form-control text-center" id="description" name="description" required/>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="col-md-6">
                                <p class="label-optional"><strong>OPTIONAL</strong></p>
                                <ul class="payroll-generate">
                                    <?php if(!empty($payrollGenerateOptions)): ?>
                                        <?php foreach($payrollGenerateOptions as $options):?>
                                            <li>
                                                <div class="checkbox checkbox-custom">
                                                    <input name="<?= $options->system_type ?>_<?= $options->system_code ?>" class="checkAll" value="<?= $options->system_code ?>" id="<?= $options->system_type ?>_<?= $options->system_code ?>" type="checkbox">
                                                    <label for="<?= $options->system_type ?>_<?= $options->system_code ?>" class=""><strong><?= $options->system_code_desc ?></strong></label>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>No Data</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="col-md-12">
                                <br/>
                                <br/>
                                <div>
                                    <p id="data_karyawan_label" class="label-optional"><strong>DATA KARYAWAN</strong></p>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-sm table-striped table-custom table-colored">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input name="checkAll" value="" class="checkboxtable" id="checkAll" type="checkbox" onclick="handleCheckAllEmployeeList('checkAll', 'employee')">
                                                </th>
                                                <th><?= lang('Shared.label.no_reg') ?></th>
                                                <th><?= lang('Shared.label.employee_name') ?></th>
                                                <th><?= lang('Shared.label.area') ?></th>
                                                <th><?= lang('Shared.label.role') ?></th>
                                                <th><?= lang('Shared.label.position') ?></th>
                                                <th>Work Day</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <?php echo create_button($button, "btn_submit"); ?>
                        <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('generate_payroll') ?>'">Kembali</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- End Content Section -->