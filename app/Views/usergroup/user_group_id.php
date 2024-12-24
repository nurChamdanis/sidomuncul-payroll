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
    table.tree-2 .last-col{
        display: none;
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
                        <a href="<?php echo site_url('user_group') ?>"><?=$function_name?></a>
                    </li>
                    <li class="active">Detail</li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">

        <div class="col-sm-12">
            <form id="form_usergroup" class="form-horizontal" role="form" method="post" action="">
                <input type="hidden" name="user_group_id" id="user_group_id" value="<?php echo $uri->setSilent()->getSegment(3) ?>" />
                <!-- add by nanin  -->
                <input type="hidden" name="<?=csrf_token();?>" id="<?=csrf_token();?>" value="<?=csrf_hash();?>" style="display: none">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-5 control-label">Nama Grup</label>
                                <div class="col-md-7 m-t-10">
                                    <?php echo $user_group->user_group_description; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label">Halaman Awal <small>(Setelah Login)</small></label>
                                <div class="col-md-7 m-t-10">
                                    <?php echo $user_group->function_name; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>				

					<div class="row">
                        <div class="col-md-6">
                            <!--h5> Daftar Fungsi yang bisa di akses :</h5-->
							<p> Klik tanda <span class="treegrid-expander glyphicon glyphicon-plus"></span> Untuk Melihat detil fungsi & Hak Akses.</p>
                            <div class="table-responsive">
                                <table id="dtTable" class="table tree-2 m-0 table-striped table-colored table-teal table-hover">
                                    <thead>
                                        <tr>
                                            <th width="400px" class="text-center">Nama</th>
                                            <th width="10px" class="text-center"> Aksi </th>
                                            <th class="last-col">Function id</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        <?php foreach ($function_list as $row) { ?>

                                            <?php if ($row->function_parent == "0" || $row->function_parent == "null") { ?>
                                                <tr class="treegrid-<?php echo $row->function_id ?>">
                                                    <td><?php echo $row->function_name ?></td>
                                                    <td class="text-center">

                                                    </td>
                                                    <td class="last-col"><?php echo $row->function_id ?></td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr class="treegrid-<?php echo $row->function_id; ?> treegrid-parent-<?php echo $row->function_parent; ?>">
                                                    <td><?php echo $row->function_name ?></td>
                                                    <td class="text-center">
                                                        <a class="btn btn-sm btn-icon waves-effect waves-light btn-success view"> <i class="fa fa-search"></i> Lihat Fitur </a>
                                                    </td>
                                                    <td class="last-col"><?php echo $row->function_id ?></td>
                                                </tr>
                                            <?php } ?>

                                            <?php $i++; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					
					<div class="row">
						<div class="col-md-12">
							<button type="button" class="btn btn-custom btn-bordered waves-light waves-effect w-md m-b-5" onclick="window.location = '<?php echo site_url('usergroup') ?>'">Kembali</button>
							<?php echo create_button($button, "btn_edit", "", $uri->setSilent()->getSegment(3)); ?>
							<?php
							
							if ($user_group->is_admin != 1) 
							{
								$attr = ($user_group->jmlh_pegawai > 0) ? 'disabled title="Ada ' . $user_group->jmlh_pegawai . ' pegawai menggunakan grup pengguna ini"' : '';
								echo create_button($button, "btn_delete", $attr);
							}
							?>
						</div>
					</div>
                </div>
            </form>
        </div>
    </div>


    <div id="modal_fitur" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:780px;">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div id="content-feature" class="" style="margin-top:10px"> 
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
</div>

<?= $this->endSection() ?>