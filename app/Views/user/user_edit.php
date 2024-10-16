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
                <h4 class="page-title">Ubah <?=$function_name?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?=$function_grp_name;?></li>
                    <li>
                        <a href="<?php echo site_url('user') ?>"><?=$function_name?></a>
                    </li>
                    <li class="active">Ubah <?=$function_name?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
            <form id="form_user" class="form-horizontal" method="post" action="">
				<input type="hidden" class="form-control" value="<?php echo $user->full_name; ?>" name="full_name" id="full_name" />
                <!-- add by nanin  -->
                <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label">Perusahaan <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <!-- <select class="form-control select2" id="company_id" required>
                                        <option value="">Pilih..</option>
                                        <?php foreach ($listCompany as $e):?>
                                            <option value="<?php echo $e->company_id?>"><?php echo $e->company_name?></option>
                                        <?php endforeach; ?>

                                    </select> -->
                                        <select id="company_id" class="select2" name="company_id" disabled>
                                            <option value="-">Tambahkan Perusahaan</option>
                                            <?php foreach ($listCompany as $e):?>
                                                <option value="<?php echo $e->company_id?>" <?php echo ($user->company_id == $e->company_id) ? 'selected' : ''?>><?php echo $e->company_name?></option>
                                            <?php endforeach; ?>
                                            </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Email Pengguna <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <input type="text" class="form-control" value="<?php echo $user->user_email; ?>" name="user_email" id="user_email" required readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Request Type
                                    <div id="boxCheckALl">
                                        <div class="checkbox checkbox-custom">
                                            <input name="checkAll" class="checkAll" id="checkAll" type="checkbox"  value="checkAll">
                                            <label for="checkAll"><span style="position:relative; top: 2px;">Pilih Semua</span></label>
                                        </div>
                                    </div>
                                </label>

                                <div class="col-md-7" style="grid-template-columns: 1fr; gap: 10px;">
                                    <?php
                                    $i =0;
                                    foreach($list_of_function_id as $f){
                                        $checked = '';
                                        if(!empty($selected_function_id)){
                                            $checked = (in_array($f->function_id, $selected_function_id) ? 'checked' : '');
                                        }
                                        ?>
                                    <div class="checkbox checkbox-custom" style="border:1px solid #ddd; padding: 5px 10px; border-radius: 4px;">
                                        <input name="function_id[]" class="function_id_checkbox" id="function_id_<?=$f->function_id ?>" type="checkbox" value="<?=$f->function_id ?>" style="position: relative !important; margin: 0px !important;" <?= $checked ?>>
                                        <label for="function_id_<?=$f->function_id ?>"><span style="display:inline-block; position:relative; top: 2px;"><?=$f->function_name ?></span></label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Yang diwakilkan </label>
                                <div class="col-md-7 item">
                                    <table id="reimburse-limit" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Pegawai
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            if (count($list_employee_represntative) > 0){ foreach ($list_employee_represntative as $v){ ?>
                                            <tr>
                                                <td>
                                                    <select name="employee_delegation[]" class="select2 approver form-control" data-unique="<?= $i ?>" data-placeholder="Pilih Pegawai">
                                                        <option value="<?=$v->employee_id ?>"><?=strtoupper($v->no_reg) . ' - ' . $v->employee_name ?></option>
                                                    </select>
                                                </td>
                                                <td class="col-delete text-center" style="display: flex; justify-content: center; align-content: center; align-items: center">
                                                    <a href="javascript:void('')" class="remove_row"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                                $i++; }
                                            }else{ ?>
                                            <tr id="empty-delegate">
                                                <td colspan="2" class="text-center">
                                                    belum ada delegasi
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">
                                                    <button name="add_delegate" type="button" id="add_delegate" class="btn btn-purple btn-sm btn-bordered waves-light waves-effect w-md m-b-5"><i class="mdi mdi-plus"> </i> Tambah Delegasi</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
							<div class="form-group">
                                <label class="col-md-5 control-label">Grup Pengguna <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" name="user_group_id" id="user_group_id" >
                                        <?php foreach ($user_group as $row) { ?>
											<option <?php echo $user->user_group_id == $row->user_group_id ? 'selected="selected"' : '';?> value="<?php echo $row->user_group_id; ?>"><?php echo $row->user_group_description; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Status Aktif <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" name="is_active" id="is_active" required>
                                        <option <?php echo $user->is_active == 1 ? 'selected="selected"' : '';?> value="1">Aktif</option>
                                        <option <?php echo $user->is_active == 0 ? 'selected="selected"' : '';?> value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Kata Sandi</label>
                                <div class="col-md-7 item">
                                    <div class="input-group">
                                        <input name="password" id="password" class="form-control" type="password" placeholder="" value="" maxlength="100"  autocomplete="off"/>
                                        <span class="input-group-addon"><a href="javascript:;" id="btn_password"><i class="fa">&#xf070;</i></a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Ulangi Kata Sandi</label>
                                <div class="col-md-7 item">
                                    <div class="input-group">
                                        <input name="confirm_password" id="confirm_password" class="form-control" type="password" placeholder="" value="" maxlength="100"  autocomplete="off"/>
                                        <span class="input-group-addon"><a href="javascript:;" id="btn_confirm_password"><i class="fa">&#xf070;</i></a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="div_pass_error" style="display:none">
                                <label class="col-md-5 control-label"></label>
                                <div class="col-md-7 item">
                                    <div>
                                        <p id="password_char"><span class="badge badge-danger"><i class="fa fa-times"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Minimal 8 Karakter</span> </p>
                                        <p id="password_special_char"><span class="badge badge-danger"><i class="fa fa-times"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Kombinasi Spesial Karakter</span> </p>
                                        <p id="password_uppercase_char"><span class="badge badge-danger"><i class="fa fa-times"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Kombinasi Huruf Besar Dan Kecil</span> </p>
                                    </div>
                                    <label id="password_error"  style="margin-bottom: 10px; display:none; color:red;">Kata Sandi terlalu lemah</label>
                                </div>
                            </div>
							<div class="form-group">
                                <label class="col-md-5 control-label"><mark>Pegawai Yang Menggunakan Akun ini</mark></label>
                                <div class="col-md-7 item">
                                    <!--<select class="form-control" id="employee_id" name="employee_id" disabled="disabled">
										<option value="0">Tidak Diset</option>
										<?php //if ($user->employee_id != ''): ?>
										<option value="<?php //echo $user->employee_id?>" selected="selected"><?php //echo $user->employee_name?></option>
										<?php //endif; ?>
										<?php //foreach ($employee_assignee as $e): ?>
										<option value="<?php //echo $e->employee_id?>"><?php //echo $e->employee_name?></option>
										<?php //endforeach; ?>
									</select>-->
                                    <input type="text" class="form-control" value="<?php echo $user->employee_name; ?>" name="employee_id_v" id="employee_id_v" required readonly />
                                    <input type="hidden" class="form-control" value="<?php echo $user->employee_id; ?>" name="employee_id" id="employee_id" />
									<input type="hidden" name="xemployee_id" id="xemployee_id" value="<?php echo $user->employee_id?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Reset Pegawai</label>
                                <div class="col-md-7 item">
                                    <div class="checkbox checkbox-custom">
                                            <input name="reset_flg" class="reset_flg" id="reset_flg" type="checkbox"  value="1">
                                            <label for="reset_flg"><span style="position:relative; top: 2px;">&nbsp;</span></label>
                                        </div>
                                </div>
                            </div>
						</div>
                    </div>
                    <hr/>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location = '<?php echo site_url('user/id/' . $uri->setSilent()->getSegment(3)) ?>'">Batal</button>
                        <?php echo create_button($button, "btn_update"); ?>						
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>