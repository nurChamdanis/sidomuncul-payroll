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
                <h4 class="page-title">Detail <?=$function_name?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?=$function_grp_name;?></li>
                    <li>
                        <a href="<?php echo site_url('user') ?>"><?=$function_name?></a>
                    </li>
                    <li class="active">Detail <?=$function_name?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
	
	<div class="row">
	<?php if ($session->getFlashdata('notif_user_success') != ''): ?>
		<div class="col-sm-12">
			<div class="alert alert-success" role="alert">
				<i class="mdi mdi-check-all"></i> <?php echo $session->getFlashdata('notif_user_success') ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($session->getFlashdata('notif_user_failed') != ''): ?>
		<div class="col-sm-12">
			<div class="alert alert-danger" role="alert">
				<i class="fa fa-times"></i> <?php echo $session->getFlashdata('notif_user_failed') ?>
			</div>
		</div>
	<?php endif; ?>
	</div>

    <div class="row">

        <div class="col-sm-12">
            <form id="form_user" class="form-horizontal" role="form" method="post" action="">
                <!-- add by nanin  -->
            <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label">Email Pengguna</label>
                                <div class="col-md-7 item">
									<input type="hidden" name="deleted" id="deleted" value="<?php echo htmlspecialchars($user->user_email); ?>" />
                                    <?php echo htmlspecialchars($user->user_email); ?>
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-md-5 control-label">Nama Alias Pengguna</label>
                                <div class="col-md-7 item">
									<?php echo htmlspecialchars($user->full_name); ?>
                                </div>
                            </div-->
                            <!-- <div class="form-group">
                                <label class="col-md-5 control-label">Super Admin</label>
                                <div class="col-md-7 item">
                                    <?php echo htmlspecialchars($user->super_admin_text); ?>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-md-5 control-label">Konfirmasi Email</label>
                                <div class="col-md-7 item">
                                    <?php echo htmlspecialchars($user->email_confirmed_text); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Request Type</label>
                                <div class="col-md-7 item">
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <?php foreach($request_types as $wu): ?>
                                            <div style=" border:1px solid #ddd; padding: 5px 10px; border-radius: 4px;">
                                                <?php echo htmlspecialchars($wu->function_name)?>
                                            </div>
                                        <?php endforeach; ?>
                                        <?= count($request_types) == 0  ? '-' : '' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Yang diwakilkan </label>
                                <div class="col-md-7 item">
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <?php foreach($list_employee_represntative as $wu): ?>
                                            <div style=" border:1px solid #ddd; padding: 5px 10px; border-radius: 4px;">
                                                <?php echo htmlspecialchars($wu->user_email . ' - ' . $wu->employee_name)?>
                                            </div>
                                        <?php endforeach; ?>
                                        <?= count($list_employee_represntative) == 0  ? '-' : '' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
							<div class="form-group">
                                <label class="col-md-5 control-label">Grup Pengguna</label>
                                <div class="col-md-7 item">
									<?php echo htmlspecialchars($user->user_group_description); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Status Aktif</label>
                                <div class="col-md-7 item">
                                    <?php echo htmlspecialchars($user->is_active_text); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label"><mark>Pegawai Yang Menggunakan Akun ini</mark></label>
                                <div class="col-md-7 item m-t-5">									
                                    <?php 
										if (htmlspecialchars($user->employee_id) == '')
										{
											echo 'Tidak Diset';
										}
										else
										{
											echo '<a href="'.site_url('employee/id/' . htmlspecialchars($user->employee_id)).'">'.htmlspecialchars($user->employee_name).'</a>';
										}
										
									?>
                                </div>
                            </div>
						</div>
                    </div>
                    <hr/>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location = '<?php echo site_url('user') ?>'">Kembali</button>
                        <?php 
                            echo create_button($button, "btn_edit", "", $uri->setSilent()->getSegment(3));
                            
                            // edited by arka.budi, 01/03/2019
							// if ($user->super_admin == 0 && $user->user_email != $this->session->userdata(S_USER_NAME)) {
							// 	echo create_button($this->button, "btn_delete"); 
							// }
						?>
                    </div>
                </div>
            </form>
        </div>
    </div>
	
	<?php if ($user->super_admin == 0) { ?>
	<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Hapus Pengguna</h4>
                </div>
                <div class="modal-body">
                    Anda Yakin ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" onclick="on_delete_confirm()" id="btn_delete_confirm">Hapus</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	<?php } ?>
</div>

<?php if ($user->super_admin == 0) { ?>
<script>
function on_delete_confirm()
{
    $('#btn_delete_confirm').html('Mohon Tunggu...');
    $('#btn_delete_confirm').attr('disabled', 'disabled');

    $('#form_user').submit();
}
</script>
<?php } ?>

<?= $this->endSection() ?>