<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="page-title-box">
                    <h4 class="page-title"><?= (!isset($data) ? "Buat" : "Ubah"); ?> <?= $function_name ?></h4>
                    <ol class="breadcrumb p-0 m-0">
                        <li><?= $function_grp_name; ?></li>
                        <li>
                            <a href="<?php echo site_url('master_system') ?>"><?= $function_name ?></a>
                        </li>
                        <li class="active"><?= (!isset($data) ? "Buat" : "Ubah"); ?> <?= $function_name ?></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Form Detail Data</h3>
                        <p class="panel-sub-title font-13 text-muted">Data Detail Master System</p>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">System Tipe</label>
                                            <div class="col-md-8">
                                                <span><?= $system->system_type ?? '-' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">System Code</label>
                                            <div class="col-md-8">
                                                <span><?= $system->system_code ?? '-' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Deskripsi System Code</label>
                                            <div class="col-md-8 m-t-10">
                                                <span><?= $system->system_code_desc ?? '-' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Valid</label>
                                            <div class="col-md-8 m-t-10">
                                                <div style="display:flex; justify-content: flex-start; gap: 15px;">
                                                    <span><?= !empty($system->valid_from) ?  std_date($system->valid_from, 'Y-m-d', 'd F Y') : '-' ?></span>
                                                    <span>sd</span>
                                                    <span><?= !empty($system->valid_to) ?  std_date($system->valid_to, 'Y-m-d', 'd F Y') : '-' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">System Value</label>
                                                <div class="col-md-8">
                                                    <span><?= $system->system_value_txt ?  $system->system_value_txt : '-' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">System Value Number</label>
                                                <div class="col-md-8">
                                                    <span><?= $system->system_value_num ?  $system->system_value_num : '-' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Deskripsi System</label>
                                                <div class="col-md-8">
                                                    <span><?= $system->system_description ?  $system->system_description : '-' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location='<?php echo site_url('master_system') ?>'">Kembali</button>
                        <?php echo create_button($button, "btn_edit", null, "{$system->system_type}/{$system->system_code}"); ?>
                        <?php echo create_button($button, "btn_delete"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Modal Delete -->
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <form id="formDelete" name="form_delete" action="" method="post">
            <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
            <input type="hidden" name="system_type" value="<?= isset($system) ? $system->system_type : null ?>" />
            <input type="hidden" name="system_code" value="<?= isset($system) ? $system->system_code : null ?>" />
            <input type="hidden" name="valid_from" value="<?= isset($system) ? $system->valid_from : null ?>" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="deleteModalLabel">Hapus Master System</h4>
                    </div>
                    <div class="modal-body">
                        Anda Yakin ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleDelete()" id="btn_submit">Hapus</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Start Modal Delete -->
<?= $this->endSection() ?>
<!-- End Content Section -->