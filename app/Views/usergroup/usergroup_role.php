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
    
    div.slider {
        display: none;
    }

    table.dataTable tbody td.no-padding {
        padding: 0;
    }
</style>
<script>
    var featureLength = '<?php echo count($featureList); ?>';
    var featureList = '<?php echo json_encode($featureList); ?>';
    
</script>
<div class="container">

    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title">Peran Grup Pengguna</h4>
                <ol class="breadcrumb p-0 m-0">
                    <li>Pengaturan</li>
                    <li class="active">Peran Grup Pengguna</li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <?php if ($this->session->flashdata('notif_usergroup_role_success') != ''): ?>
            <div class="col-sm-12">
                <div class="alert alert-success" role="alert">
                    <i class="mdi mdi-check-all"></i> <?php echo $this->session->flashdata('notif_usergroup_role_success') ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-sm-2">
            <label class="control-label" style="margin-top:10px">Grup Pengguna</label>
        </div>
        <div class="col-sm-4">
            <select class="select2" data-live-search="true" data-style="btn-default">
                <option value="1">Admin</option>
                <option value="2">Manajer</option>
                <option value="3">Pegawai</option>
            </select>
        </div>
        
        
    </div>
    
    <hr>
    
    <div class="row">
        
        <div class="col-sm-12">
            
            
<!--            <div class="card-box table-responsive">
                <table id="dtTable" class="table m-0 table-colored table-teal table-bordered"></table>
            </div>-->
            
            <div class="card-box table-responsive">
                
                <div id="content-table">
                    <table class="table tree-2 table-colored table-teal table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" width="250px">Modul</th>
                                <?php foreach($featureList as $row){ ?>
                                <th class="text-center feature_<?php echo $row->feature_id; ?>"><?php echo $row->feature_name; ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="<?php echo count($featureList)+1; ?>" class="text-center">Tidak ada data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            
            
            
            
<!--            <div class="card-box table-responsive">
                
                <div id="content-table">
                    <table class="table table-bordered table-hover table-colored table-teal">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">Modul</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
                
                
            </div>-->
        </div>
    </div>
    <!-- end row -->


</div>

<?= $this->endSection() ?>