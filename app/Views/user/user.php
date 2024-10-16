<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<!-- Content Section -->
<?= $this->section('content') ?>

<!-- Start Wrapper Content -->
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

    <div class="row">
        <!-- edited by arka.budi, 01/03/2019 -->
		<?php // if ($users_total >= $default_users): ?>
			<!--<div class="col-sm-12">
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-times"></i> <strong>Perhatian.</strong>  Anda telah mencapai Batas Maksimum Jumlah Pengguna. Perlu Tambahan? <a href="<?php // echo site_url('setting')?>">Klik disini</a>
                </div>
            </div>-->
		<?php // endif; ?>

        <div class="col-sm-12">
            <?php 
            
			// $disabled = ($users_total >= $default_users) ? 'disabled="disabled" title="Anda sudah mencapai Batas Maksimum jumlah pengguna"' : '';
            // create_button($this->button, "btnAdd", $disabled); 

            // edited by arka.budi, 01/03/2019
            // create_button($this->button, "btnAdd"); 
            ?>
            <input type="hidden" name="<?= csrf_token();?>" id="<?= csrf_token();?>" value="<?= csrf_hash();?>" style="display: none">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
        <form id="form_official_travel" class="form-horizontal" role="form" method="post" action="">
            <div class="row">
                <input type="hidden" name="<?= csrf_token();?>" id="<?= csrf_token();?>" value="<?= csrf_hash();?>" style="display: none">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="col-md-12 control-label">Perusahaan</label>
                        <div class="col-md-12">
                            <select class="form-control select2" id="company_id">
                                <option value="0">All Company</option>
                                <?php foreach ($listCompany as $e):?>
                                    <option value="<?php echo $e->company_id?>"><?php echo $e->company_name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="col-md-12 control-label">Grup Pengguna</label>
                        <div class="col-md-12">
                            <select class="form-control" id="user_group_id">
                                <option value="0">All Group</option>
                              
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 m-t-30">
                    <button id="btn_submit" type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5"
                    onclick="dtTable.ajax.reload();">Filter</button>
                 
                </div>
            </div>          
        </form>
        </div>

        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="datatable" class="table table-striped table-hover table-colored table-custom">
                    <thead>
                        <tr>
                            <th class="text-left">Nama Perusahaan</th>
                            <th class="text-left">Email</th>
                            <th class="text-left">Pegawai Yang Menggunakan</th>
                            <th class="text-left">Grup Pengguna</th>
                            <th class="text-left">Status Aktif</th>
                            <th class="text-left">Login Terakhir</th>
                            <th class="text-left">Konfirmasi Email</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<!-- End Wrapper Content -->

<?= $this->endSection() ?>