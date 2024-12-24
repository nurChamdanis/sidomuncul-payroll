<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<!-- Content Section -->
<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title"><?=$function_name?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?=$function_grp_name;?></li>
                    <li class="active"><?=$function_name?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- end row -->
    <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
    <?php if ($session->getFlashdata('notif_user_group_success') != ''): ?>
    <div class="col-sm-12">
        <div class="alert alert-success" role="alert">
            <i class="mdi mdi-check-all"></i> <?php echo $session->getFlashdata('notif_user_group_success')?>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        
        <div class="col-sm-12">
            <?php create_button($button, "btnAdd"); ?>
            <!-- add by nanin  -->
            <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
            <?php //create_button_group($this->button, "btnGroup"); ?>
            <?php //create_button_group($this->button, "btnDropdown2"); ?>
<!--            <button type="button" id="btnAdd" class="btn btn-teal btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location = '<?php echo site_url('user_group/create') ?>'"><i class="mdi mdi-plus"></i> Buat Grup Pengguna</button>-->
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-md-12 control-label">Perusahaan</label>
                            <div class="col-md-12">
                                <select class="form-control select2" id="p_company_id">
                                        <option value="-" selected>Semua Perusahaan</option>
                                        <?php foreach ($company as $row) { ?>
                                            <option value="<?=$row->company_id?>"><?=$row->company_name?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-top: 25px;">
                        <button id="btn_submit" type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5"
                        onclick="dtTable.ajax.reload();">Filter</button>
                    </div>
                </div>
		        <hr/>
                <table id="datatable" class="table table-striped table-hover table-colored table-custom">
                    <thead>
                        <tr>
                            <th width="40%">Perusahaan</th>
                            <th>Deskripsi</th>
<!--                            <th class="text-center">Admin</th>-->
<!--                            <th class="text-center">Kunci Pengguna</th>-->
                            <th>Halaman Awal <small>(Setelah Login)</small></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>

<?= $this->endSection() ?>