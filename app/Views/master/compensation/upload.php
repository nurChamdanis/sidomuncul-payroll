<!-- extends layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    #tableImportExcel {
        width: 150rem !important;
    }

    #tableImportExcel tr.update td {
        background-color: #fff6ce;
    }

    #tableImportExcel tr.notvalid td {
        background-color: #ffcece;
    }

    ul.bgcolorDescription {
        list-style-type: none;
        margin: 0px;
        padding: 0px;
    }

    ul.bgcolorDescription li {
        display: flex;
        justify-content: flex-start;
        width: 200px;
        text-align: left;
        gap: 5px;
        padding: 2px 0px;
    }

    .box {
        border: 1px solid #ccc;
        width: 20px;
        height: 20px;
    }

    .box.new {
        background-color: #fff;
    }

    .box.update {
        background-color: #fff6ce;
    }

    .box.invalid {
        background-color: #ffcece;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container">
    <!-- Breadcrumb Section -->
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title"><?= $function_name ?> Upload</h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li class="active"><?= $function_name ?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <!-- upload form -->

    <div class="panel panel-body" style="padding-right: 0.5rem;padding-left: 0.5rem;">
        <div class="container">
            <div class="row">
                <form action="" id="formUpload" class="form-horizontal" role="form" method="post">
                    <input type="hidden" id="process_id" value="<?= date('YmdHis') . time() ?>" name="process_id" />
                    <div class="col-md-8">
                        <!-- company -->
                        <div class="form-group row">
                            <label class="col-md-4 m-t-5" for="company-options"><?= lang('Compensation.inquiry.company_name') ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control select2" id="company-options" name="company_id" required placeholder="-Select-">

                                </select>
                            </div>
                        </div>
                        <!-- file -->
                        <div class="form-group row m-t-5">
                            <label for="file_upload" class="col-md-4 m-t-5">File <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input id="file_upload" type="file" name="file_upload" class="filestyle" data-buttontext="Select file" required>
                            </div>
                        </div>
                        <!-- period -->
                        <!-- <div class="form-group row m-t-5">
                            <label for="period" class="col-md-4 m-t-5">Efective Date <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="period" id="period" class="text-center form-control dt_picker_compensation" required data-date-format="mm/yyyy" data-min-view-mode="months" />
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
        </div>
        <div style="margin: 1rem;">
            <?= create_button($button, "btn_download_template") ?>
            <?= create_button($button, "btn_action_upload") ?>
            <?= create_button($button, "btn_cancel") ?>
        </div>

        <!-- table section -->
        <div id="wrapperImportExcel" class="card-box" style="display: none;">
            <div class="table-responsive">
                <table id="tableImportExcel" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Valid</th>
                            <th>Update</th>
                            <th>perusahaan</th>
                            <th>area</th>
                            <th>org.unit</th>
                            <th>Nomor Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Periode</th>
                            <th>Jenis</th>
                            <th>Jumlah Kompensasi</th>
                            <th>Error Message</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div style="padding: 0px 10px;">
                <div style="border-bottom: 1px solid #ccc; width: 200px; margin: 5px 0px;"><?= lang("Allowances.upload.information.informationrow") ?></div>
                <ul class="bgcolorDescription">
                    <li>
                        <div class="box new"></div> <?= lang("Allowances.upload.information.new") ?>
                    </li>
                    <li>
                        <div class="box invalid"></div> <?= lang("Allowances.upload.information.invalid") ?>
                    </li>
                    <li>
                        <div class="box update"></div> <?= lang("Allowances.upload.information.update") ?>
                    </li>
                </ul>
            </div>
            <div style="display: flex; justify-content: flex-end; padding-right: 10px;">
                <?= create_button($button, "btn_submit_excel", "disabled"); ?>
            </div>
        </div>


        <!-- invalid modal -->
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
        <!-- submit modal -->
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
    </div>

    <?= $this->endSection() ?>