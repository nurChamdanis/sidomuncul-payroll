<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<!-- Content Section -->
<?= $this->section('content') ?>

<style>
    .has-error .select2-container .select2-selection--single {
        border: 1px solid #f5707a !important;
        height: 38px !important;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title">Buat Pengguna</h4>
                <ol class="breadcrumb p-0 m-0">
                    <li>Pengaturan</li>
                    <li>
                        <a href="<?php echo site_url('user') ?>">Pengguna</a>
                    </li>
                    <li class="active">Buat Pengguna</li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
			<?php if (count($employee_assignee) > 0): ?>
            <form id="form_user" class="form-horizontal" role="form" method="post" action="">
				<input type="hidden" class="form-control" value="" name="full_name" id="full_name" />
                <!-- add by nanin  -->
                <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label">Perusahaan <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-control select2" id="company_id" required>
                                        <option value="">Pilih..</option>
                                        <?php foreach ($listCompany as $e):?>
                                            <option value="<?php echo $e->company_id?>"><?php echo $e->company_name?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-5 control-label">Email Pengguna <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <input type="text" class="form-control" value="" name="user_email" id="user_email" required />
                                </div>
                            </div>
							<div class="form-group">
                                <label class="col-md-5 control-label">Grup Pengguna <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" name="user_group_id" id="user_group_id" required>
                                        <?php foreach ($user_group as $row) { ?>
											<option value="<?php echo $row->user_group_id; ?>"><?php echo $row->user_group_description; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Status Aktif <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
									<div class="radio radio-info">
										<input type="radio" name="is_active" id="radio1" value="1" checked="checked">
										<label for="radio1">
											Aktif
										</label>
									</div>
									<div class="radio radio-danger">
										<input type="radio" name="is_active" id="radio0" value="0">
										<label for="radio0">
											Tidak
										</label>
									</div>
                                </div>
                            </div>
							<div class="form-group">
                                <label class="col-md-5 control-label"><mark>Pegawai Yang Menggunakan Akun ini</mark></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" id="employee_id" name="employee_id">
										<!--option value="0">Tidak Diset</option-->
										<?php foreach ($employee_assignee as $e): 
											$sel_after_create_employe = ($uri->setSilent()->getSegment(3) == $e->employee_id) ? 'selected="selected"' : '';
										?>
										<option value="<?php echo $e->employee_id?>" <?php echo $sel_after_create_employe?>><?php echo $e->employee_name?></option>
										<?php endforeach; ?>
									</select>									
                                </div>
                            </div>                            
						</div>
						<div class="col-md-6">						
						</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location = '<?php echo site_url('user') ?>'">Kembali</button>
                        <button type="button" class="btn btn-primary btn-bordered waves-light waves-effect w-md m-b-5 sbmt_btn" id="btn_submit" onclick="submit_user()">Kirim Undangan</button>						
                    </div>
                </div>
            </form>
			<?php else:?>
			<div class="alert alert-warning alert-dismissible fade in" role="alert">
				<strong class="text-danger">Tidak ada Pegawai untuk dibuatkan Pengguna baru.</strong> <br/><a href="<?php echo site_url('employee/create')?>">Buat Pegawai Baru terlebih dahulu disini.</a>
			</div>
			<?php endif;?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>