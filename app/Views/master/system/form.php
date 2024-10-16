<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
@media (min-width: 992px) {
    .left-space {
        margin-left: 25%;
    }
}
</style>
<?= $this->endSection() ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="page-title-box">
                    <h4 class="page-title"><?= (!isset($system) ? "Buat" : "Ubah"); ?> <?= $function_name ?></h4>
                    <ol class="breadcrumb p-0 m-0">
                        <li><?= $function_grp_name; ?></li>
                        <li>
                            <a href="<?php echo site_url('master_system') ?>"><?= $function_name ?></a>
                        </li>
                        <li class="active"><?= (!isset($system) ? "Buat" : "Ubah"); ?> <?= $function_name ?></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form id="formMasterSystem" class="form-horizontal" role="form" method="post" action="">
                    <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                    <input type="hidden" name="old_system_type" value="<?= isset($system) ? $system->system_type : null ?>" />
                    <input type="hidden" name="old_system_code" value="<?= isset($system) ? $system->system_code : null ?>" />
                    <input type="hidden" name="old_valid_from" value="<?= isset($system) ? $system->valid_from : null ?>" />
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Form <?= (!isset($system) ? "Buat" : "Ubah"); ?> Data</h3>
                            <p class="panel-sub-title font-13 text-muted">Mohon lengkapi data berikut.</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                
                                <!-- Column 1 -->
                                <div class="col-md-6">
                                    <div  id="system_type_wrapper" class="form-group">
                                        <label class="col-md-3 control-label">System Tipe <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <div id="system_type_wrapper">
                                                <select class="form-control select2" id="system_type" name="system_type" required placeholder="Pilih System Type">
                                                    <?php
                                                        if(isset($system)):
                                                    ?>
                                                        <option value="<?= $system->system_type ?>" selected><?= $system->system_type ?></option>
                                                    <?php
                                                        endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="system_type_free_text_wrapper" class="form-group hide">
                                        <label class="col-md-3 control-label">System Tipe <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input class="form-control required" id="system_type_free_text" name="system_type_free_text" class="form-control" onkeyup="replaceSpaces(event)" value="<?= isset($system) ? $system->system_type : '' ?>"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <div class="col-md-6 left-space">
                                            <div class="checkbox checkbox-primary">
                                                <input id="changeToFreeText" type="checkbox" name="change_to_free_text" onclick="handleChangeFreeText(this.id)">
                                                <label for="changeToFreeText">
                                                    Change To Free Text
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">System Code <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" value="<?= isset($system) ? $system->system_code : null ?>" name="system_code" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Deskripsi System Code</label>
                                        <div class="col-md-9 m-t-10">
                                            <textarea class="form-control" rows="3" name="system_code_desc"><?= isset($system) ? $system->system_code_desc : null ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Valid <span class="text-danger">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="valid_from" id="valid_from" class="text-center form-control dt_picker" value="<?= isset($system) ? date('d/m/Y', strtotime($system->valid_from)) : date('d/m/Y') ?>" />
                                        </div>
                                        <div class="col-md-1 m-t-5">sd</div>
                                        <div class="col-md-4">
                                            <input type="text" name="valid_to" id="valid_to" class="text-center form-control dt_picker" value="<?= isset($system) ? date('d/m/Y', strtotime($system->valid_to)) : date('d/m/Y') ?>" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 2 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">System Value</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" value="<?= isset($system) ? $system->system_value_txt : null ?>" name="system_value_txt" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">System Value Number</label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" value="<?= isset($system) ? $system->system_value_num : null ?>" name="system_value_num" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Deskripsi System</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" rows="3" name="system_description"><?= isset($system) ? $system->system_description : null ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_system') ?>'">Kembali</button>
                            <?php echo create_button($button, "btn_submit"); ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
<!-- End Content Section -->