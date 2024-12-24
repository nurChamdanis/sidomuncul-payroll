<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<!-- Content Section -->
<?= $this->section('content') ?>

<style>
    .table > tbody > tr > td, 
    .table > tbody > tr > th, 
    .table > tfoot > tr > td, 
    .table > tfoot > tr > th, 
    .table > thead > tr > td, 
    .table > thead > tr > th {
        vertical-align: middle;
    }
    td.details-control {
        cursor: pointer;
    }

    table.tree-2 .last-col, table.table-fitur .last-col{
        display: none;
    }
    /*    tr.shown td.details-control {
            background: url('../resources/details_close.png') no-repeat center center;
        }*/
    .has-error .select2-container .select2-selection--single {
        border: 1px solid #f5707a !important;
        height: 38px !important;
    }
</style>
<script>
    var dataFunction = <?php echo json_encode($function_list); ?>;
    var dataCompany = <?php echo json_encode($company_list); ?>;
    var dataAuth = <?php echo json_encode($data_auth); ?>;
    var dataAccessCompany = <?php echo json_encode($data_access_company); ?>;
    var dataAccessArea = <?php echo json_encode($data_access_area); ?>;
    var dataAccessPosition = <?php echo json_encode($data_access_position); ?>;
    var dataAccessRole = <?php echo json_encode($data_access_role); ?>;
</script>
<div class="container">
<input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title">Ubah <?=$function_name?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?=$function_grp_name;?></li>
                    <li>
                        <a href="<?php echo site_url('usergroup') ?>"><?=$function_name?></a>
                    </li>
                    <li class="active">Ubah <?=$function_name?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
	
		<?php if ($session->getFlashdata('notif_user_group_success') != ''): ?>
            <div class="col-sm-12">
                <div class="alert alert-success" role="alert">
                    <i class="mdi mdi-check-all"></i> <?php echo $session->getFlashdata('notif_user_group_success') ?>
                </div>
            </div>
        <?php endif; ?>
		
		<?php if ($session->getFlashdata('notif_update_usergroup') != ''): ?>
			<div class="col-sm-12">
				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert"aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="mdi mdi-check-all"></i> <?php echo $session->getFlashdata('notif_update_usergroup'); ?>
				</div>
			</div>
		<?php endif; ?>

        <div class="col-sm-12">
            
            <div class="card-box">
                
                <div class="row">
                    <form id="form_user_group" class="form-horizontal" role="form" method="post" action="">
                        <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
                        <input type="hidden" name="user_group_id" id="user_group_id" value="<?php echo $uri->setSilent()->getSegment(3) ?>" />
                        <input type="hidden" name="functionlist" id="functionlist" />
                        <input type="hidden" name="default_user_lock" id="default_user_lock" />
                        <input type="hidden" id="feature_list" name="feature_list" class="form-control">
                        <input type="hidden" id="related_area_flg" name="related_area_flg" class="form-control">
                        <input type="hidden" id="related_position_flg" name="related_position_flg" class="form-control">
                        <input type="hidden" id="related_role_flg" name="related_role_flg" class="form-control">
                        <input type="hidden" id="subordinate_flg" name="subordinate_flg" class="form-control">
                        <input type="hidden" id="data_access_company_list" name="data_access_company_list" class="form-control">
                        <input type="hidden" id="data_access_area_list" name="data_access_area_list" class="form-control">
                        <input type="hidden" id="data_access_position_list" name="data_access_position_list" class="form-control">
                        <input type="hidden" id="data_access_role_list" name="data_access_role_list" class="form-control">
                        <input type="hidden" id="usergroup_action" name="usergroup_action" class="form-control">

                        <!-- add by nanin  -->

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label">Perusahaan <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" name="company_id" id="company_id" required>
                                        <option></option>
                                        <?php foreach ($company_list as $row) { ?>
                                        <?php 
                                            $selected = ($row->company_id == $user_group->company_id) ? 'selected="selected"' : ""; 
                                        ?>
                                        <option value="<?php echo $row->company_id; ?>" <?php echo $selected; ?>><?php echo $row->company_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Nama Grup</label>
                                <div class="col-md-7 item">
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user_group->user_group_description); ?>" name="user_group_description" required />
                                </div>
                            </div>
                            <?php 
                                $checked = ($user_group->is_admin == "1") ? 'checked="checked"' : ""; 
                            ?>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Is Admin</label>
                                <div class="col-md-7 item">
                                    <div class="checkbox checkbox-custom m-t-0 m-b-0">
                                        <input name="is_admin" class="" id="is_admin" type="checkbox" <?= $checked;?>>
                                        <label for="is_admin">
                                            <div style="padding-top:2px!important">
                                               &nbsp;
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php //var_dump($function_list); ?>
                                <label class="col-md-5 control-label">Halaman Awal <small>(Setelah Login)</small></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" name="default_landing" id="default_landing" required >
                                        <option></option>
                                        <?php foreach ($function_list as $row) { ?>
                                        <?php 
                                            $selected = ($row->function_controller == $user_group->default_landing) ? 'selected=""' : ""; 
                                            $disabled = ($row->function_controller == "") ? 'disabled="disabled"' : "";
                                        ?>
                                        <option value="<?php echo $row->function_controller; ?>" <?php echo $selected." ".$disabled; ?>><?php echo $row->function_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!--
                        updated by : septian
                        date : 2023-09-08
                        desc : can apply to other group by company access
                        -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="" class="control-label">Terapkan ke group lain</label>
                                    <div class="checkbox checkbox-custom">
                                        <input name="checkAllUserGroup" class="checkAllUserGroup" id="checkAllUserGroup" type="checkbox"
                                               value="checkAllUserGroup">
                                        <label for="checkAllUserGroup"><span style="position:relative; top: 2px;">Pilih Semua</span></label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="table-responsive">
                                        <table id="other_group" class="table table-striped table-hover table-responsive-sm">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Grup Lain</th>
                                                <th class="">
                                                    <div class="checkbox checkbox-custom">
                                                        <input name="checkAllFunction" class="checkAllFunction" value="" id="checkAllFunction" type="checkbox" >
                                                        <label for="checkAllFunction" class="">
                                                                Daftar Fungsi
                                                        </label>
                                                    </div>
                                                </th>
                                                <th class="">
                                                    <div class="checkbox checkbox-custom">
                                                        <input name="checkAllDataAccess" class="checkAllDataAccess" value="" id="checkAllDataAccess" type="checkbox" >
                                                        <label for="checkAllDataAccess" class="">
                                                            Akses Data
                                                        </label>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($user_groups as $user_group): ?>
                                                <tr>
                                                    <td>
                                                        <?= $user_group->company_code . ' - ' . $user_group->user_group_description ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="checkbox checkbox-custom m-t-0 m-b-0">
                                                            <input name="apply_function[]" class="apply_function" value="<?= $user_group->user_group_id ?>" id="<?= $user_group->user_group_id ?>" type="checkbox" >
                                                            <label for="apply_function_<?= $user_group->user_group_id ?>">
                                                                <div style="padding-top:2px!important">
                                                                    &nbsp;
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="checkbox checkbox-custom m-t-0 m-b-0">
                                                            <input name="apply_data_access[]" class="apply_data_access" value="<?= $user_group->user_group_id ?>" id="apply_data_access_<?= $user_group->user_group_id ?>" type="checkbox" >
                                                            <label for="apply_data_access_<?= $user_group->user_group_id ?>">
                                                                <div style="padding-top:2px!important">
                                                                    &nbsp;
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <hr/>


                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li id="" class="active">
                                <a href="#funcList-b1" data-toggle="tab">
                                    <span class="hidden-xs">Daftar Fungsi</span>
                                    <span class="visible-xs"><i class="mdi mdi-settings"></i></span>
                                </a>
                            </li>
                            <li id="" class="">
                                <a href="#dataAccess-b1" data-toggle="tab">
                                    <span class="hidden-xs">Akses Data</span>
                                    <span class="visible-xs"><i class="mdi mdi-settings"></i></span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- start info -->
                            <div class="tab-pane active" id="funcList-b1">	
                                            
                                <div class="col-md-12">
                                    
                                    <div class="table-responsive" style="padding: 10px;">
                                        <table id="datatable" class="table table-striped table-hover table-colored table-custom">
                                            <thead>
                                                <tr>
                                                    <th>Menu</th>
                                                    <th>Otoritas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($function_list as $row) { ?>
                                                    <tr>
                                                        <td style="width: 35% !important;">
                                                            <div class="checkbox checkbox-custom m-t-0 m-b-0">
                                                                <input name="<?=$row->function_id?>" class="function_checkbox" id="<?=$row->function_id?>" type="checkbox">
                                                                <label for="<?=$row->function_id?>" style="display: flex; gap: 10px;">
                                                                    <div><?=$row->function_id?></div>
                                                                    <div>-</div>
                                                                    <div><?=$row->function_name?></div>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <?php foreach ($row->feature_list as $rowFeature) { ?>
                                                                    <?php
                                                                        $checked = (htmlspecialchars($rowFeature->is_used) == "1") ? "checked" : "";
                                                                        $arr_eltype = array(
                                                                            'page' => 'Halaman'
                                                                            , 'button' => 'Tombol'
                                                                            , 'button_group' => 'Grup Tombol'
                                                                            , 'button_group_list' => 'Daftar Grup Tombol'
                                                                            , 'submit' => 'Tombol Submit'
                                                                            , '' => 'Umum'
                                                                        );
                                                                        
                                                                    ?>
                                                                    <div class="col-md-4" style="margin-bottom: 25px;">
                                                                        <!-- <input id="cb_<?=$rowFeature->feature_id?>" class="chkFeature" type="checkbox" <?=$checked?>> <label for="cb_<?=$rowFeature->feature_id?>"><?=(isset($arr_eltype[htmlspecialchars($rowFeature->feature_element_type)])) ? $arr_eltype[htmlspecialchars($rowFeature->feature_element_type)] : htmlspecialchars($rowFeature->feature_element_type)?> <?=$rowFeature->feature_description?></label> -->
                                                                        <div class="checkbox checkbox-custom m-t-0 m-b-0" style="display:flex;">
                                                                            <input name="<?=$rowFeature->feature_id?>" class="feature_checkbox func_<?=$row->function_id?>" id="<?=$rowFeature->feature_id?>" type="checkbox" <?=$checked?>>
                                                                            <label for="<?=$rowFeature->feature_id?>">
                                                                                <div style="padding-top:2px!important">
                                                                                    <p style="margin: 0px; padding: 0px;">
                                                                                        <span style="font-size: 10px;">Feature Name</span><br/>
                                                                                        <span style="font-size: 14px;"><strong><?=(isset($rowFeature->feature_name)) ? htmlspecialchars($rowFeature->feature_name) : "-"?></strong></span>
                                                                                    </p>
                                                                                    <p style="margin: 0px; padding: 0px;">
                                                                                        <span style="font-size: 9px;"><i>( <?=$rowFeature->feature_description?> )</i></span>
                                                                                    </p>
                                                                                    <hr style="margin: 4px; padding: 0px;"/>
                                                                                    <p style="margin: 0px; padding: 0px;">
                                                                                        <span style="font-size: 10px;">Feature Type </span><br/>
                                                                                        <span style="font-size: 12px;"><?=(isset($arr_eltype[htmlspecialchars($rowFeature->feature_element_type)])) ? $arr_eltype[htmlspecialchars($rowFeature->feature_element_type)] : htmlspecialchars($rowFeature->feature_element_type)?></span>
                                                                                    </p>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane " id="dataAccess-b1">	
                                <div class="table-responsive" style="padding: 10px;">
                                    
                                    <h5>Pengaturan Umum</h5>
                                    <div class="row">
                                        <?php 
                                            $related_area_nm = '';
                                            $related_position_nm = '';
                                            $related_role_nm = '';
                                        ?>
                                        <?php foreach ($data_access_type as $row) { ?>
                                            <?php
                                                $checked = '';
                                                $id_element = '';
                                                if($row->system_code == 'area'){
                                                    $id_element = 'v_related_area_flg';
                                                    $checked = ($data_auth->related_area_flg == '1') ? 'checked' : '';
                                                    $related_area_nm = $row->system_value_txt;
                                                }else if($row->system_code == 'position'){
                                                    $id_element = 'v_related_position_flg';
                                                    $checked = ($data_auth->related_position_flg == '1') ? 'checked' : '';
                                                    $related_position_nm = $row->system_value_txt;
                                                }else if($row->system_code == 'role'){
                                                    $id_element = 'v_related_role_flg';
                                                    $checked = ($data_auth->related_role_flg == '1') ? 'checked' : '';
                                                    $related_role_nm = $row->system_value_txt;
                                                }
                                                ?>
                                            <div class="col-md-3">
                                                <div class="checkbox checkbox-custom m-t-0 m-b-0">
                                                    <input name="<?=$id_element?>" class="" id="<?=$id_element?>" type="checkbox" <?=$checked?>>
                                                    <label for="<?=$id_element?>">
                                                        <div style="padding-top:2px!important">
                                                            <?=$row->system_value_txt?> Pengguna
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="checkbox checkbox-custom m-t-0 m-b-0">
                                                <input name="v_subordinate_flg" class="" id="v_subordinate_flg" type="checkbox" <?=($data_auth->subordinate_flg == '1') ? 'checked' : ''?>>
                                                <label for="v_subordinate_flg">
                                                    <div style="padding-top:2px!important">
                                                        Bawahan Pengguna 
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <h5>Perusahaan</h5>
                                    <div id="companySection" class="row"></div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Tipe</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2" id="p_child_data_access_type">
                                                        <!-- <option value="-" selected>Tidak Ada</option> -->
                                                        <?php foreach ($data_access_type as $row) { ?>
                                                            <option value="<?=$row->system_code?>"><?=$row->system_value_txt?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Perusahaan</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2" id="p_child_company_id">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3" style="margin-top: 25px;">
                                            <button id="btn_submit" type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5"
                                            onclick="oTable.ajax.reload();">Filter</button>
                                        </div> -->
                                    </div>
                                    <hr>
                                    <div class="table-responsive hidden" style="padding: 10px; border:0px!important" id="divAreaSection">
                                        <table id="" class="table table-striped table-hover table-colored table-custom">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Perusahaan</th>
                                                    <th><?=$related_area_nm?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="workUnitSection">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive hidden" style="padding: 10px; border:0px!important" id="divPositionSection">
                                        <table id="" class="table table-striped table-hover table-colored table-custom">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Perusahaan</th>
                                                    <th><?=$related_position_nm?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="positionSection">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive hidden" style="padding: 10px; border:0px!important" id="divRoleSection">
                                        <table id="" class="table table-striped table-hover table-colored table-custom">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Perusahaan</th>
                                                    <th><?=$related_role_nm?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="roleSection">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location = '<?php echo site_url('usergroup') ?>'">Kembali</button>
                    <?php echo create_button($button, "btn_update"); ?>
                    <?php echo ($count == 0) ? create_button($button, "btn_delete") : ""; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" leave="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Hapus Grup Pengguna</h4>
                </div>
                <div class="modal-body">
                    Anda Yakin ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="on_delete_confirm()" id="btn_delete_confirm">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    
<?= $this->endSection() ?>