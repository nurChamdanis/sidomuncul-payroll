<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    #table {
        width: 1800px !important;
    }

    ul.group-list,
    ul.empty-list {
        margin: 0px;
        padding: 0px;
        list-style-type: none;
    }

    ul.group-list li {
        border: 1px solid #ddd;
        padding: 2px 10px;
        margin: 3px 0px;
        background-color: white;
        border-radius: 5px;
        font-weight: 600;
    }

    ul.empty-list li {
        padding: 2px 5px;
        margin: 5px 0px;
        text-align: center;
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
                <h4 class="page-title"><?= $function_name ?></h4>
                <ol class="breadcrumb p-0 m-0">
                    <li><?= $function_grp_name; ?></li>
                    <li class="active"><?= $function_name ?></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- Filter Section -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-md-3 control-label"><?= lang('Shared.label.company') ?></label>
                                <div class="col-md-9">
                                    <select class="form-control" name="filterCompany" id="filterCompany" placeholder="<?= lang('Shared.choose') . " " . lang('Shared.label.company') ?>"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-md-3 control-label"><?= lang('PKHProduct.inquiry.Area') ?></label>
                                <div class="col-md-9">
                                    <select class="form-control" name="filterArea" id="filterArea" placeholder="<?= lang('PKHProduct.placeholder.all.area') ?>"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-md-3 control-label"><?= lang('PKHProduct.inquiry.Org_Unit') ?></label>
                                <div class="col-md-9">
                                    <select class="form-control" name="filterOrgUnit" id="filterGrup" placeholder="<?= lang('Shared.placeholder.all.group') ?>"></select>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <br />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-md-3 control-label"><?= lang('PKHProduct.inquiry.Product_Name') ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="<?= lang('PKHProduct.placeholder.all.product_name') ?>" id="keyword" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-8">
                            <?= create_button($button, "btn_add"); ?>
                            <?= create_button($button, "btn_edit_inquiry", "disabled"); ?>
                            <?= create_button($button, "btn_delete_inquiry", "disabled"); ?>
                            <?= create_button($button, "btn_upload_inquiry"); ?>
                            <?= create_button($button, "btn_download"); ?>
                        </div>
                        <div class="col-md-4 text-right">
                            <?= create_button($button, "btn_search"); ?>
                            <?= create_button($button, "btn_reset"); ?>
                            <button id="toggleFilter" class="btn btn-default btn-sm btn-bordered waves-light waves-effect m-b-5" title="hide-filter" onclick="toggleFilter(this)">
                                <i id="filterIcon" class="mdi mdi-filter-remove"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card-box">
                <div class="table-responsive">
                    <table id="table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <div class="checkbox checkbox-custom">
                                        <input name="checkAll" class="checkAll" value="" id="checkAll" type="checkbox" ex-attr-edit="btn_edit_inquiry" ex-attr-delete="btn_delete_inquiry" ex-attr-checked="allowance" onclick="handleCheckAll(this.id, 'allowance')">
                                        <label for="checkAll" class=""></label>
                                    </div>
                                </th>
                                <th><?= lang('PKHProduct.label.Company') ?></th>
                                <th><?= lang('PKHProduct.label.Area') ?></th>
                                <th><?= lang('PKHProduct.label.Org_Unit') ?></th>
                                <th><?= lang('PKHProduct.label.Product_Code') ?></th>
                                <th><?= lang('PKHProduct.label.Product_Name') ?></th>
                                <th><?= lang('PKHProduct.label.Pcs') ?></th>
                                <th><?= lang('PKHProduct.label.Price_Of_Product') ?></th>
                                <th><?= lang('PKHProduct.label.Create_By') ?></th>
                                <th><?= lang('PKHProduct.label.Create_Date') ?></th>
                                <th><?= lang('PKHProduct.label.Changed_By') ?></th>
                                <th><?= lang('PKHProduct.label.Changed_Date') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start Modal Delete -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <form id="formDelete" name="form_delete" action="" method="post">
        <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
        <input type="hidden" name="allowance_id" value="" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="deleteModalLabel"><?= lang('PKHProduct.modal.delete_all.title') ?></h4>
                </div>
                <div class="modal-body">
                    <?= lang('PKHProduct.modal.delete_all.confirm') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                        <?= lang('PKHProduct.modal.delete_all.back') ?>
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleDeleteAll('master_tunjangan/removeSelected','allowance','btn_modal_delete')" id="btn_modal_delete">
                        <?= lang('PKHProduct.modal.delete_all.submit') ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Start Modal Delete -->
<?= $this->endSection() ?>
<!-- End Content Section -->