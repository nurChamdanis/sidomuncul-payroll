<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    .input-group input{
        height: 35px !important;
        top: 1px;
    }

    #tableImportExcel{width: 3500px !important;}
    #tableImportExcel tr.update td{
        background-color: #fff6ce;
    }
    #tableImportExcel tr.notvalid td{
        background-color: #ffcece;
    }

    ul.bgcolorDescription{list-style-type: none; margin: 0px; padding: 0px;}

    ul.bgcolorDescription li {
        display: flex;
        justify-content: flex-start;
        width: 200px;
        text-align: left;
        gap: 5px;
        padding: 2px 0px;
    }

    .box{
        border:1px solid #ccc;
        width: 20px;
        height: 20px;
    }

    .box.new{
        background-color: #fff;
    }
    .box.update{
        background-color: #fff6ce;
    }
    .box.invalid{
        background-color: #ffcece;
    }
</style>
<?= $this->endSection() ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
    <div class="container">
        <!-- Breadcrumb Section -->
        <div class="row">
            <div class="col-xs-12">
                <div class="page-title-box">
                    <h4 class="page-title">Upload <?=$function_name?></h4>
                    <ol class="breadcrumb p-0 m-0">
                        <li><?=$function_grp_name;?></li>
                        <li class="active">Upload <?=$function_name?></li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <!-- Filter Section -->
                <form id="formUploadTemplate" class="form-horizontal" role="form" method="post" action="">
                    <input type="hidden" id="process_id" value="<?= date('YmdHis').time() ?>" name="process_id" />
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-3 control-label"><?= lang('Shared.label.company') ?></label>
                                        <div class="col-md-9" >						
                                            <input type="text" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                                            <select class="form-control" name="company_id" id="company_id" placeholder="<?= lang('Shared.placeholder.all.company') ?>"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-3 control-label"><?= lang('Allowances.form.label_upload') ?></label>
                                        <div class="col-md-9" >		
                                            <div style="margin-bottom: 5px;">
                                                <input id="file_template" type="file" name="file_template" class="filestyle" data-buttontext="Select file" data-buttonbefore="true" disabled>
                                            </div>		
                                            <div>
                                                <?= create_button($button, "btn_upload_excel"); ?>
                                                <?= create_button($button, "btn_download_excel"); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Table Section -->
                <div id="wrapperImportExcel" class="card-box" style="display: none;">
                    <div class="table-responsive">
                        <table id="tableImportExcel" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Valid</th>
                                    <th>Update</th>
                                    <th><?= lang('Shared.label.company') ?></th>
                                    <th><?= lang('Allowances.form.allowance_code') ?></th>
                                    <th><?= lang('Allowances.form.allowance_name') ?></th>
                                    <th><?= lang('Allowances.form.default_value') ?></th>
                                    <th><?= lang('Allowances.form.calculation_type') ?></th>
                                    <th><?= lang('Allowances.form.calculation_mode') ?></th>
                                    <th><?= lang('Allowances.inquiry.gl_account') ?></th>
                                    <th><?= lang('Allowances.form.effective_date') ?></th>
                                    <th><?= lang('Allowances.form.effective_date_end') ?></th>
                                    <th><?= lang('Allowances.form.list_of_data_area') ?></th>
                                    <th><?= lang('Allowances.form.list_of_data_area_group') ?></th>
                                    <th><?= lang('Allowances.form.list_of_payroll_rules') ?></th>
                                    <th><?= lang('Shared.label.created_by') ?></th>
                                    <th><?= lang('Shared.label.changed_by') ?></th>
                                    <th><?= lang('Shared.label.created_at') ?></th>
                                    <th><?= lang('Shared.label.changed_at') ?></th>
                                    <th>Error Message</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="padding: 0px 10px;">
                        <div style="border-bottom: 1px solid #ccc; width: 200px; margin: 5px 0px;"><?= lang("Allowances.upload.information.informationrow") ?></div>
                        <ul class="bgcolorDescription">
                            <li><div class="box new"></div> <?= lang("Allowances.upload.information.new") ?></li>
                            <li><div class="box invalid"></div> <?= lang("Allowances.upload.information.invalid") ?></li>
                            <li><div class="box update"></div> <?= lang("Allowances.upload.information.update") ?></li>
                        </ul>
                    </div>
                    <div style="display: flex; justify-content: flex-end; padding-right: 10px;">
                        <?= create_button($button, "btn_submit_excel", "disabled"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Start Modal Delete -->
    <div id="uploadModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <form id="formUploadExcel" name="form_upload_excel" action="" method="post">
            <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
            <input type="hidden" name="allowance_id" value="" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="uploadModalLabel"><?= lang('Allowances.modal.import_excel.title') ?></h4>
                    </div>
                    <div class="modal-body">
                        <?= lang('Allowances.modal.import_excel.confirm') ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                            <?= lang('Allowances.modal.import_excel.back') ?>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleSubmitImport()" id="btn_modal_import">
                            <?= lang('Allowances.modal.import_excel.submit') ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Start Modal Delete -->

     <!-- Start Modal Delete -->
     <div id="invalidModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="invalidModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="invalidModalLabel">Error Message</h4>
                </div>
                <div class="modal-body" id="invalidModalBody">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" aria-hidden="true" id="btn_modal_delete">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Modal Delete -->
<?= $this->endSection() ?>
<!-- End Content Section -->