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
                        <a href="<?php echo site_url('master_potongan') ?>"><?= $function_name ?></a>
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
                <input type="hidden" name="deduction_id" value="<?= isset($deduction) ? $deduction->deduction_id : null ?>" />
                <input type="hidden" id="list_area" name="list_area" value="<?= isset($deduction_area) ? implode(',', $deduction_area) : '' ?>" />
                <input type="hidden" id="list_area_grup" name="list_area_grup" value="<?= isset($deduction_area_group) ? implode(',', $deduction_area_group) : '' ?>" />
                <input type="hidden" id="list_payroll_rules" name="list_payroll_rules" value="<?= isset($deduction_payroll_rules) ? implode(',', $deduction_payroll_rules) : '' ?>" />

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Form <?= lang('Shared.id') ?> Data</h3>
                        <p class="panel-sub-title font-13 text-muted"><?= lang('Shared.id') ?> Data <?= $function_name ?></p>
                    </div>
                    <div class="panel-body">
                        <div class="row">

                            <!-- Column 1 -->
                            <div class="col-md-6">
                                <div id="system_type_wrapper" class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Shared.label.company') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <div id="system_type_wrapper">
                                            <select class="form-control select2" id="company_id" name="company_id" required placeholder="Pilih Company" disabled>
                                                <?php
                                                if (isset($deduction)) :
                                                ?>
                                                    <option value="<?= $deduction->company_id ?>" selected><?= $deduction->company_code ?></option>
                                                <?php
                                                endif;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Deductions.form.deduction_code') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" value="<?= isset($deduction) ? $deduction->deduction_code : null ?>" name="deduction_code" required disabled />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Deductions.form.deduction_name') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" value="<?= isset($deduction) ? $deduction->deduction_name : null ?>" name="deduction_name" required disabled />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Deductions.form.default_value') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8 mt-10">
                                        <input type="text" id="default_value" class="form-control nominal text-right" value="<?= isset($deduction) ? $deduction->default_value : null ?>" name="default_value" required disabled />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Deductions.form.effective_date') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8 mt-10">
                                        <input type="text" name="effective_date" id="effective_date" class="text-center form-control dt_picker" value="<?= isset($deduction) ? date('d/m/Y', strtotime($deduction->effective_date)) : date('d/m/Y') ?>" disabled />
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label class="col-md-4 control-label">Is Active</label>
                                    <div class="col-md-8">
                                        <div class="checkbox checkbox-primary">
                                            <input id="is_active" type="checkbox" name="is_active" disabled <?= ($deduction->is_active == '1') ? 'checked' : '' ?>>
                                            <label for="is_active">
                                                Is Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Allowances.form.gl_account') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8 mt-10">
                                        <select class="form-control select2" name="gl_id" id="gl_id" placeholder="<?= lang('Shared.choose') . ' '. lang('Allowances.form.gl_account') ?>" required disabled>
                                            <?php if(isset($glaccount)) : ?>
                                                <?php if(!empty($glaccount)) : ?>
                                                <option value="<?= $glaccount->gl_id ?>"><?= $glaccount->gl_code . ' - ' .$glaccount->gl_name ?></option>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Deductions.form.calculation_mode') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8 mt-10">
                                        <?php if (!empty($calculationMode)) : ?>
                                            <?php foreach ($calculationMode as $calculation) : ?>
                                                <div class="radio radio-default">
                                                    <input type="radio" name="calculation_mode" id="calculation_mode_<?= $calculation->system_code ?>" value="<?= $calculation->system_code ?>" required <?= ($deduction->calculation_mode == $calculation->system_code) ? 'checked' : '' ?> disabled>
                                                    <label for="calculation_mode_<?= $calculation->system_code ?>">
                                                        <?= $calculation->system_value_txt ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <div class="radio radio-default">
                                                <input type="radio" name="calculation_mode" id="bulanan" value="bulanan" required disabled>
                                                <label for="bulanan">
                                                    Bulanan
                                                </label>
                                            </div>
                                            <div class="radio radio-default">
                                                <input type="radio" name="calculation_mode" id="harian" value="harian" required disabled>
                                                <label for="harian">
                                                    Harian
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Deductions.form.calculation_type') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8 mt-10">
                                        <?php if (!empty($calculationType)) : ?>
                                            <?php foreach ($calculationType as $calculation) : ?>
                                                <div class="radio radio-default">
                                                    <input type="radio" name="calculation_type" id="calculation_type_<?= $calculation->system_code ?>" value="<?= $calculation->system_code ?>" required <?= ($deduction->calculation_type == $calculation->system_code) ? 'checked' : '' ?> disabled>
                                                    <label for="calculation_type_<?= $calculation->system_code ?>">
                                                        <?= $calculation->system_value_txt ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <div class="radio radio-default">
                                                <input type="radio" name="calculation_type" id="kalkulasi_otomatis" value="otomatis" required disabled>
                                                <label for="kalkulasi_otomatis">
                                                    <?= lang('Shared.automatic_calculate') ?>
                                                </label>
                                            </div>
                                            <div class="radio radio-default">
                                                <input type="radio" name="calculation_type" id="kalkulasi_manual" value="manual" required disabled>
                                                <label for="kalkulasi_manual">
                                                    <?= lang('Shared.manual_calculate') ?>
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <ul class="nav nav-tabs tabs-bordered">
                                <li class="active">
                                    <a href="#areagroup" data-toggle="tab" aria-expanded="false">
                                        <span><?= lang('Deductions.form.area_and_group') ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#rules" data-toggle="tab" aria-expanded="true">
                                        <span><?= lang('Deductions.form.rules') ?></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="areagroup">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Daftar Data Area -->
                                            <div class="panel panel-border panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><?= lang('Deductions.form.list_of_data_area') ?></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <table id="areaTable" class="table table-list table-bordered table-sm table-hover table-colored table-custom" style="width:100%">
                                                        <thead>
                                                            <th><?= lang('Deductions.form.area_name') ?></th>
                                                            <th width="10%">Action</th>
                                                        </thead>
                                                        <tbody id="area_body">
                                                            <?php if (!empty($area)) : ?>
                                                                <?php foreach ($area as $key => $value) : ?>
                                                                    <tr>
                                                                        <td><?= $value->name ?></td>
                                                                        <td>
                                                                            <div class="checkbox checkbox-custom">
                                                                                <?php
                                                                                $checked = '';
                                                                                if (in_array($value->work_unit_id, $deduction_area)) {
                                                                                    $checked = 'checked';
                                                                                }
                                                                                ?>
                                                                                <input <?= $checked ?> class="areaCheckbox" id="area_<?= $value->work_unit_id ?>" type="checkbox" name="area[]" value="<?= $value->work_unit_id ?>" disabled />
                                                                                <label for="area_<?= $value->work_unit_id ?>"></label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td colspan="2"><?= lang('Shared.no_data') ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Daftar Data Area Grup -->
                                            <div class="panel panel-border panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><?= lang('Deductions.form.list_of_data_area_group') ?></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <table id="areaGrupTable" class="table table-bordered table-sm table-hover table-colored table-custom" style="width:100%">
                                                        <thead>
                                                            <th><?= lang('Deductions.form.area_group_name') ?></th>
                                                            <th width="10%">Action</th>
                                                        </thead>
                                                        <tbody id="areagroup_body">
                                                            <?php if (!empty($areaGrup)) : ?>
                                                                <?php foreach ($areaGrup as $key => $value) : ?>
                                                                    <tr>
                                                                        <td><?= $value->system_value_txt ?></td>
                                                                        <td>
                                                                            <?php
                                                                            $checked = '';
                                                                            if (in_array($value->system_code, $deduction_area_group)) {
                                                                                $checked = 'checked';
                                                                            }
                                                                            ?>
                                                                            <div class="checkbox checkbox-custom">
                                                                                <input <?= $checked ?> class="areagrupCheckbox" id="areagrup_<?= $value->system_code ?>" type="checkbox" name="areagrup[]" value="<?= $value->system_code ?>" disabled />
                                                                                <label for="areagrup_<?= $value->system_code ?>"></label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td colspan="2"><?= lang('Shared.no_data') ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="rules">
                                    <div class="panel panel-border panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?= lang('Deductions.form.list_of_payroll_rules') ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <div style="border:1px solid #efefef; padding: 5px 5px 13px 5px; width: 400px; border-radius: 5px">
                                                <ul>
                                                    <?php if (!empty($areaPayrollRules)) : ?>
                                                        <?php foreach ($areaPayrollRules as $key => $value) : ?>
                                                            <li>
                                                                <div class="checkbox checkbox-custom">
                                                                    <?php
                                                                    $checked = '';
                                                                    if (in_array($value['payroll_rules_id'], $deduction_payroll_rules)) {
                                                                        $checked = 'checked';
                                                                    }
                                                                    ?>
                                                                    <input <?= $checked ?> class="payrollrulesCheckbox" id="payrollrules_<?= $value['payroll_rules_id'] ?>" type="checkbox" name="payrollrules[]" value="<?= $value['payroll_rules_id'] ?>" disabled />
                                                                    <label for="payrollrules_<?= $value['payroll_rules_id'] ?>"><?= $value['rules_name'] ?></label>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <li><?= lang('Shared.no_data') ?></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?= view('shared/payroll_logs_inquiry', ['function_id' => $function_id, ['refference_id' => $refference_id]]) ?>
                    </div>
                    <div class="panel-footer">
                        <?php echo create_button($button, "btn_edit", null, "{$deduction->deduction_id}"); ?>
                        <?php echo create_button($button, "btn_delete"); ?>
                        <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_potongan') ?>'">Kembali</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Start Modal Delete -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <form id="formDelete" name="form_delete" action="" method="post">
        <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
        <input type="hidden" name="deduction_id" value="<?= isset($deduction) ? $deduction->deduction_id : null ?>" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="deleteModalLabel"><?= lang('Deductions.modal.delete.title') ?></h4>
                </div>
                <div class="modal-body">
                    <?= lang('Deductions.modal.delete.confirm') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                        <?= lang('Deductions.modal.delete.back') ?>
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleDelete()" id="btn_submit">
                        <?= lang('Deductions.modal.delete.submit') ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Start Modal Delete -->
<?= $this->endSection() ?>
<!-- End Content Section -->