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
                <h4 class="page-title">Buat <?=$function_name?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?=$function_grp_name;?></li>
                    <li>
                        <a href="<?php echo site_url('usergroup') ?>"><?=$function_name?></a>
                    </li>
                    <li class="active">Buat <?=$function_name?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">

        <div class="col-sm-12">
            <form id="form_user_group" class="form-horizontal" role="form" method="post" action="">
                <input type="hidden" name="user_group_id" id="user_group_id" value="" />
<!--                <input type="hidden" name="items" id="items" />-->
<!--                <input type="hidden" name="is_admin" id="is_admin" />-->
                <input type="hidden" name="default_user_lock" id="default_user_lock" />
                <!-- add by nanin  -->
                <input type="hidden" name="<?= csrf_token();?>" id="<?= csrf_token();?>" value="<?= csrf_hash();?>" style="display: none">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label">Perusahaan <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" name="company_id" id="company_id" required>
                                        <option></option>
                                        <?php foreach ($company_list as $row) { ?>
                                        <?php 
                                            $selected = ($row->company_id == $session->get(S_COMPANY_ID)) ? 'selected="selected"' : ""; 
                                        ?>
                                        <option value="<?php echo $row->company_id; ?>" <?php echo $selected; ?>><?php echo $row->company_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Nama Grup <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <input type="text" class="form-control" value="" name="user_group_description" id="user_group_description" required />
                                </div>
                            </div>
<!--                            <div class="form-group">
                                <label class="col-md-4 control-label">Atur Sebagai Admin</label>
                                <div class="col-md-8">
                                    <input type="checkbox" name="_is_admin" data-plugin="switchery" data-color="#1bb99a" data-size="small"/>
                                </div>
                            </div>-->
<!--                            <div class="form-group">
                                <label class="col-md-4 control-label">Kunci Pengguna</label>
                                <div class="col-md-8">
                                    <input type="checkbox" name="_default_user_lock" data-plugin="switchery" data-color="#1bb99a" data-size="small"/>
                                </div>
                            </div>-->
                            
                            <div class="form-group">
                                <label class="col-md-5 control-label">Is Admin</label>
                                <div class="col-md-7 item">
                                    <div class="checkbox checkbox-custom m-t-0 m-b-0">
                                        <input name="is_admin" class="" id="is_admin" type="checkbox">
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
                                <label class="col-md-5 control-label">Halaman Awal <small>(Setelah Login)</small> <span class="text-danger">*</span></label>
                                <div class="col-md-7 item">
                                    <select class="form-control" name="default_landing" id="default_landing" required>
                                        <option></option>
                                        <?php foreach ($function_list as $row) { ?>
                                        <?php 
                                            //$selected = ($row->function_controller == $user_group->default_landing) ? 'selected=""' : ""; 
                                            $disabled = ($row->function_controller == "") ? 'disabled="disabled"' : "";
                                        ?>
                                        <option value="<?php echo $row->function_controller; ?>" <?php echo $disabled; ?>><?php echo $row->function_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>

<!--                    <h5> Otentikasi Grup Pengguna</h5>-->

<!--                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="my_table" class="table m-0 table-colored table-teal table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th class="text-right">Fitur</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="details">
                                            <td>
                                                <select class="form-control menu menu_1" /></select>
                                            </td>
                                            <td style="width: 40%">
                                                <select class="form-control menu function_1" /></select>
                                            </td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>-->

<!--                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-purple btn-bordered waves-light waves-effect w-md m-b-5" id="btn_add_new_row" onclick="add_new_row()"><i class="mdi mdi-plus"></i> Tambah Data</button>
                        </div>
                    </div>-->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location = '<?php echo site_url('usergroup') ?>'">Kembali</button>
                        <button type="button" class="btn btn-primary btn-bordered waves-light waves-effect w-md m-b-5 sbmt_btn" id="btn_submit" onclick="submit_user_group()">Simpan Grup</button>						
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>