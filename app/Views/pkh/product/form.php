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
                <h4 class="page-title"><?= (!isset($allowance) ? lang('Shared.create') : lang('Shared.edit')); ?> <?= $function_name ?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li>
                        <a href="<?php echo site_url('master_tunjangan') ?>"><?= $function_name ?></a>
                    </li>
                    <li class="active"><?= (!isset($allowance) ? lang('Shared.create') : lang('Shared.edit')); ?> <?= $function_name ?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form id="formMasterTunjangan" class="form-horizontal" role="form" method="post" action="">
                <input type="hidden" id="segment" value="<?= service('uri')->getSegment(2) ?>" />
                <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                <input type="hidden" name="allowance_id" value="<?= isset($allowance) ? $allowance->allowance_id : null ?>" />
                <input type="hidden" id="list_area" name="list_area" value="<?= isset($allowance_area) ? implode(',', $allowance_area) : '' ?>" />
                <input type="hidden" id="list_area_grup" name="list_area_grup" value="<?= isset($allowance_area_group) ? implode(',', $allowance_area_group) : '' ?>" />
                <input type="hidden" id="list_payroll_rules" name="list_payroll_rules" value="<?= isset($allowance_payroll_rules) ? implode(',', $allowance_payroll_rules) : '' ?>" />

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">

                            <!-- Column 1 -->
                            <div class="col-md-6">
                                <div id="system_type_wrapper" class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('Shared.label.company') ?> <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <div id="system_type_wrapper">

                                            <?php if (isset($allowance)) : ?>
                                                <input type="hidden" class="form-control" value="<?= isset($allowance) ? $allowance->company_id : null ?>" name="company_id" />
                                                <select class="form-control select2" id="_company_id" name="_company_id" required placeholder="Pilih Company" <?= isset($allowance) ? 'disabled' : '' ?>>
                                                    <?php
                                                    if (isset($allowance)) :
                                                    ?>
                                                        <option value="<?= $allowance->company_id ?>" selected><?= $allowance->company_code ?></option>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </select>
                                            <?php else : ?>
                                                <select class="form-control select2" id="company_id" name="company_id" required placeholder="Pilih Company">
                                                    <?php
                                                    if (isset($allowance)) :
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
                                    <label class="col-md-4 control-label"><?= lang('PKHProduct.label.Product_Code') ?></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control input-pkh_product" name="product_code" placeholder="<?= lang('PKHProduct.placeholder.all.product_code') ?>" id="keyword" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('PKHProduct.label.Product_Name') ?></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="product_name" placeholder="<?= lang('PKHProduct.placeholder.all.product_name') ?>" id="product_name" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('PKHProduct.label.Pcs') ?></label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="pcs" id="PcsId" placeholder="<?= lang('PKHProduct.placeholder.all.area') ?>" required></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('PKHProduct.label.Price_Of_Product') ?></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="price" placeholder="<?= lang('PKHProduct.placeholder.all.product_price') ?>" id="product_price" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?= lang('PKHProduct.label.Daily_Target') ?></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="daily_target" placeholder="<?= lang('PKHProduct.placeholder.all.daily_target') ?>" id="daily_target" required />
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
                                <!-- Daftar Data Area -->
                                <div class="panel panel-border panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= lang('Allowances.form.list_of_data_area') ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <table id="areaTable" class="areaTable table table-list table-bordered table-sm table-hover table-colored table-custom" style="width:100%">
                                            <thead>
                                                <th><?= lang('Allowances.form.area_name') ?></th>
                                                <th width="20%" class="text-center">
                                                    <div>
                                                        <div class="checkbox checkbox-custom">
                                                            <input id="areaCheckAll" type="checkbox" onclick="handleCheckAll(this.id,'areaCheckbox')">
                                                            <label for="areaCheckAll"></label>
                                                        </div>
                                                    </div>
                                                </th>
                                            </thead>
                                            <tbody id="area_body">
                                                <tr>
                                                    <td colspan="2" class="text-center"><?= lang('Shared.no_data') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Daftar Data Area -->
                                <!-- Daftar Data Area Grup -->
                                <div class="panel panel-border panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= lang('Allowances.form.list_of_data_area_group') ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <table id="areaGrupTable" class="areaGrupTable table table-list table-bordered table-sm table-hover table-colored table-custom" style="width:100%">
                                            <thead>
                                                <th><?= lang('Allowances.form.area_group_name') ?></th>
                                                <th width="20%" class="text-center">
                                                    <div>
                                                        <div class="checkbox checkbox-custom">
                                                            <input id="areagrupCheckAll" type="checkbox" onclick="handleCheckAll(this.id,'areagrupCheckbox')">
                                                            <label for="areagrupCheckAll"></label>
                                                        </div>
                                                    </div>
                                                </th>
                                            </thead>
                                            <tbody id="areagroup_body">
                                                <tr>
                                                    <td colspan="2" class="text-center"><?= lang('Shared.no_data') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Daftar Data Area Grup -->
                            </div>
                        </div>
                        <br />
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