<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
@media (min-width: 992px) {
    .left-space {
        margin-left: 25%;
    }
}

ul{list-style-type: none; padding: 0px 5px; margin: 0px;}
</style>
<?= $this->endSection() ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="page-title-box">
                    <h4 class="page-title"><?= (!isset($allowance) ? "Buat" : "Ubah"); ?> <?= $function_name ?></h4>
                    <ol class="breadcrumb p-0 m-0">
                        <li><?= $function_grp_name; ?></li>
                        <li>
                            <a href="<?php echo site_url('master_system') ?>"><?= $function_name ?></a>
                        </li>
                        <li class="active"><?= (!isset($allowance) ? "Buat" : "Ubah"); ?> <?= $function_name ?></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form id="formMasterTunjangan" class="form-horizontal" role="form" method="post" action="">
                    <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                    <input type="hidden" name="allowance_id" value="<?= isset($allowance) ? $allowance->allowance_id : null ?>" />
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Form <?= (!isset($allowance) ? "Buat" : "Ubah"); ?> Data</h3>
                            <p class="panel-sub-title font-13 text-muted">Mohon lengkapi data berikut.</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                
                                <!-- Column 1 -->
                                <div class="col-md-6">
                                    <div  id="system_type_wrapper" class="form-group">
                                        <label class="col-md-4 control-label">Company <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <div id="system_type_wrapper">
                                                
                                                <?php if(isset($allowance)): ?>
                                                    <input type="hidden" class="form-control" value="<?= isset($allowance) ? $allowance->company_id : null ?>" name="company_id" />
                                                    <select class="form-control select2" id="_company_id" name="_company_id" required placeholder="Pilih Company" <?= isset($allowance) ? 'disabled' : '' ?>>
                                                        <?php
                                                            if(isset($allowance)):
                                                        ?>
                                                            <option value="<?= $allowance->company_id ?>" selected><?= $allowance->company_code ?></option>
                                                        <?php
                                                            endif;
                                                        ?>
                                                    </select>
                                                <?php else: ?>
                                                    <select class="form-control select2" id="company_id" name="company_id" required placeholder="Pilih Company">
                                                        <?php
                                                            if(isset($allowance)):
                                                        ?>
                                                            <option value="<?= $allowance->company_id ?>" selected><?= $allowance->company_code ?></option>
                                                        <?php
                                                            endif;
                                                        ?>
                                                    </select>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Kode Tunjangan <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <?php if(isset($allowance)): ?>
                                                <input type="hidden" class="form-control" value="<?= isset($allowance) ? $allowance->allowance_code : null ?>" name="allowance_code" />
                                                <input type="text" class="form-control" value="<?= isset($allowance) ? $allowance->allowance_code : null ?>" name="_allowance_code" required disabled />
                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= isset($allowance) ? $allowance->allowance_code : null ?>" name="allowance_code" required />
                                            <?php endif ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Nama Tunjangan <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" value="<?= isset($allowance) ? $allowance->allowance_name : null ?>" name="allowance_name" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Default Value <span class="text-danger">*</span></label>
                                        <div class="col-md-8 mt-10">
                                            <input type="text" id="default_value" class="form-control nominal text-right" value="<?= isset($allowance) ? $allowance->default_value : null ?>" name="default_value" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Effective Date <span class="text-danger">*</span></label>
                                        <div class="col-md-8 mt-10">
                                            <input type="text" name="effective_date" id="effective_date" class="text-center form-control dt_picker" value="<?= isset($allowance) ? date('d/m/Y', strtotime($allowance->effective_date)) : date('d/m/Y') ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="col-md-4 control-label">Is Active</label>
                                        <div class="col-md-8">
                                            <div class="checkbox checkbox-primary">
                                                <input id="is_active" type="checkbox" name="is_active" checked>
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
                                        <label class="col-md-4 control-label">Minimal Masa Kerja <span class="text-danger">*</span></label>
                                        <div class="col-md-3 mt-10" style="display: flex; justify-content:center; align-items:center; gap: 10px;">
                                            <input type="text" class="form-control numericOnly text-center" maxlength="3" value="<?= isset($allowance) ? $allowance->minimum_working_period : null ?>" name="minimum_working_period" required /> 
                                            <span>Tahun</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Jenis Perhitungan <span class="text-danger">*</span></label>
                                        <div class="col-md-8 mt-10">
                                            <?php if(!empty($calculationMode)): ?>
                                                <?php foreach($calculationMode as $calculation):?>
                                                    <div class="radio radio-default">
                                                        <input type="radio" name="calculation_mode" id="calculation_mode_<?= $calculation->system_code ?>" value="<?= $calculation->system_code ?>" <?= isset($allowance) ? (($allowance->calculation_mode == $calculation->system_code) ? 'checked' : '') : ''?> required>
                                                        <label for="calculation_mode_<?= $calculation->system_code ?>">
                                                            <?= $calculation->system_value_txt ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="radio radio-default">
                                                    <input type="radio" name="calculation_mode" id="bulanan" value="bulanan" required>
                                                    <label for="bulanan">
                                                        Bulanan
                                                    </label>
                                                </div>
                                                <div class="radio radio-default">
                                                    <input type="radio" name="calculation_mode" id="harian" value="harian" required>
                                                    <label for="harian">
                                                        Harian
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tipe Kalkulasi <span class="text-danger">*</span></label>
                                        <div class="col-md-8 mt-10">
                                            <?php if(!empty($calculationType)): ?>
                                                <?php foreach($calculationType as $calculation):?>
                                                    <div class="radio radio-default">
                                                        <input type="radio" name="calculation_type" id="calculation_type_<?= $calculation->system_code ?>" value="<?= $calculation->system_code ?>" <?= isset($allowance) ? (($allowance->calculation_type == $calculation->system_code) ? 'checked' : '') : '' ?> required>
                                                        <label for="calculation_type_<?= $calculation->system_code ?>">
                                                            <?= $calculation->system_value_txt ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="radio radio-default">
                                                    <input type="radio" name="calculation_type" id="kalkulasi_otomatis" value="otomatis" required>
                                                    <label for="kalkulasi_otomatis">
                                                        Hitung Otomatis
                                                    </label>
                                                </div>
                                                <div class="radio radio-default">
                                                    <input type="radio" name="calculation_type" id="kalkulasi_manual" value="manual" required>
                                                    <label for="kalkulasi_manual">
                                                        Hitung Manual
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <ul class="nav nav-tabs tabs-bordered">
                                    <li class="active">
                                        <a href="#areagroup" data-toggle="tab" aria-expanded="false">
                                            <span>AREA DAN GRUP</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#rules" data-toggle="tab" aria-expanded="true">
                                            <span>RULES</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="areagroup">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- Daftar Data Area -->
                                                <h4><strong class="label label-primary">Daftar Data Area</strong></h4>
                                                <table id="areaTable" class="table table-list table-bordered table-sm table-hover table-colored table-custom" style="width:100%">
                                                    <thead>
                                                        <th>Nama Area</th>
                                                        <th width="10%">Action</th>
                                                    </thead>
                                                    <tbody id="area_body">
                                                        <?php if(!empty($area)) : ?>
                                                            <?php foreach ($area as $key => $value): ?>
                                                                <tr>
                                                                    <td><?= $value->name ?></td>
                                                                    <td>
                                                                        <div class="checkbox checkbox-custom">
                                                                            <?php
                                                                                $checked = '';
                                                                                if(isset($allowance_area)):
                                                                                    if(in_array($value->work_unit_id, $allowance_area)){
                                                                                        $checked = 'checked';
                                                                                    }
                                                                                endif;
                                                                            ?>
                                                                            <input <?= $checked ?> class="areaCheckbox" id="area_<?= $value->work_unit_id ?>" type="checkbox" name="area[]" value="<?= $value->work_unit_id ?>">
                                                                            <label for="area_<?= $value->work_unit_id ?>"></label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr><td colspan="2">Tidak Ada Data Area</td></tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                <div class="flex-end">
                                                    <div class="checkbox checkbox-custom">
                                                        <input id="areaCheckAll" type="checkbox" onclick="handleCheckAll(this.id,'areaCheckbox')">
                                                        <label for="areaCheckAll">Check All</label>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <!-- Daftar Data Area -->
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- Daftar Data Area Grup -->
                                                <h4><strong class="label label-primary">Daftar Data Area Grup</strong></h4>
                                                <table id="areaGrupTable" class="table table-bordered table-sm table-hover table-colored table-custom" style="width:100%">
                                                    <thead>
                                                        <th>Grup Area</th>
                                                        <th width="10%">Action</th>
                                                    </thead>
                                                    <tbody id="areagroup_body">
                                                        
                                                    </tbody>
                                                </table>
                                                <div class="flex-end">
                                                    <div class="checkbox checkbox-custom">
                                                        <input id="areagrupCheckAll" type="checkbox" onclick="handleCheckAll(this.id,'areagrupCheckbox')">
                                                        <label for="areagrupCheckAll">Check All</label>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <!-- Daftar Data Area Grup -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="rules">
                                        <h4><strong class="label label-primary">Daftar Data Payroll Rules</strong></h4>
                                        <br/>
                                        <div style="border:1px solid #efefef; padding: 5px 5px 13px 5px; width: 400px; border-radius: 5px">
                                            <ul>
                                                <?php if(!empty($areaPayrollRules)) : ?>
                                                    <?php foreach ($areaPayrollRules as $key => $value): ?>
                                                        <li>
                                                            <div class="checkbox checkbox-custom">
                                                                <?php
                                                                    $checked = '';
                                                                    if(isset($allowance_payroll_rules)):
                                                                        if(in_array($value['payroll_rules_id'], $allowance_payroll_rules)){
                                                                            $checked = 'checked';
                                                                        }
                                                                    endif;
                                                                ?>
                                                                <input <?= $checked ?> class="payrollrulesCheckbox" id="payrollrules_<?= $value['payroll_rules_id'] ?>" type="checkbox" name="payrollrules[]" value="<?= $value['payroll_rules_id'] ?>">
                                                                <label for="payrollrules_<?= $value['payroll_rules_id'] ?>"><?= $value['rules_name'] ?></label>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <li>Tidak Ada Data Area</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <div class="flex-start">
                                            <div class="checkbox checkbox-custom">
                                                <input id="payrollrulesCheckAll" type="checkbox" onclick="handleCheckAll(this.id,'payrollrulesCheckbox')">
                                                <label for="payrollrulesCheckAll">Check All</label>
                                            </div>
                                        </div>
                                        <hr/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <?php echo create_button($button, "btn_submit"); ?>
                            <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_tunjangan') ?>'">Kembali</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
<!-- End Content Section -->