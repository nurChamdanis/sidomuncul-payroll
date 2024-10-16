<!-- extends layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    #table {
        width: 2000px !important;
    }
</style>
<?= $this->endSection() ?>

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
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="panel panel-body">
        <!-- filter section -->
        <div class="row">
            <div class="col-md-4">
                <div class="row">

                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="filter-company" style="padding-top: 0.75rem;"><?= lang('Compensation.filter.search_company') ?></label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control select2" aria-label="company" id="company-options" name="company-options">
                            </select>
                        </div>
                    </div>


                    <div class="row" style="margin-top: 0.5rem;">
                        <div class="col-md-3">
                            <label class="control-label" for="filter-area" style="padding-top: 0.75rem;"><?= lang('Compensation.filter.search_area') ?></label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control select2" aria-label="area" id="area-options" name="area-options">
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="row">

                    <div class="row">
                        <div class="col-md-3" style="padding-left: 2rem;">
                            <label class="control-label" for="filter-company" style="padding-top: 0.75rem;"><?= lang('Compensation.filter.search_work_unit') ?></label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control select2" aria-label="company" id="role-options">
                            </select>
                        </div>
                    </div>


                    <div class="row" style="margin-top: 0.5rem;">
                        <div class="col-md-3" style="padding-left: 2rem;">
                            <label class="control-label" for="filter-area" style="padding-top: 0.75rem;"><?= lang('Compensation.filter.search_employee') ?></label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control select2" aria-label="area" id="employee-options">
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="row">

                    <div class="row">
                        <div class="col-md-3" style="padding-left: 2rem;">
                            <label class="control-label" for="filter-company" style="padding-top: 0.75rem;"><?= lang('Compensation.filter.search_period') ?></label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="valid_from" id="valid_from" class="text-center form-control dt_picker_compensation" style="font-size: smaller;" />
                        </div>
                        <div class="col-md-1" style="padding-right: 0rem; padding-left: 0.5rem; margin-top: 0.6rem;"><?= lang('Compensation.filter.to') ?></div>
                        <div class="col-md-4">
                            <input type="text" name="valid_to" id="valid_to" class="text-center form-control dt_picker_compensation" style="font-size: smaller;" />
                        </div>
                    </div>


                    <div class="row" style="margin-top: 0.5rem;">
                        <div class="col-md-3" style="padding-left: 2rem;">
                            <label class="control-label" for="filter-area" style="font-size: smaller;"><?= lang('Compensation.filter.search_compensation_type') ?></label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control select2" aria-label="area" id="compensationType-options">
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- filter + search button -->
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-8">
                    <?= create_button($button, "btn_add"); ?>
                    <?= create_button($button, "btn_edit_inquiry", "disabled"); ?>
                    <?= create_button($button, "btn_delete_inquiry", "disabled"); ?>
                    <?= create_button($button, "btn_upload") ?>
                    <?= create_button($button, "btn_download") ?>
                </div>

                <div class="col-md-4 text-right">
                    <div style="display:flex; justify-content:flex-end; gap:0.5rem">
                        <?= create_button($button, "btn_search"); ?>
                        <?= create_button($button, "btn_clear"); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- <hr> -->
        <!-- table section -->
        <div class="card-box">
            <div class="table-responsive">
                <table id="table" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="padding-right: 1rem;padding-bottom: 1.5rem;">
                                <div class="checkbox checkbox-custom d-flex justify-content-center align-items-center">
                                    <input ex-attr-edit="btn_edit_inquiry" ex-attr-delete="btn_delete_inquiry" ex-attr-checked="compensation" name="checkAll" class="checkAll" value="" id="checkAll" type="checkbox" onclick="handleCheckAll(this.id, 'compensation')">
                                    <label for="checkAll" class=""></label>
                                </div>
                            </th>
                            <th class="text-center"><?= lang('Compensation.inquiry.company_name') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.area_name') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.work_unit') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.employee_number') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.employee_name') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.period') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.compensation_type') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.total_compensation') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.created_by') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.created_dt') ?></th>
                            <th class="text-center"><?= lang('Compensation.inquiry.changed_by') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- Modal Delete -->
        <!-- Start Modal Delete -->
        <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <form id="formDelete" name="form_delete" action="" method="post">
                <input type="hidden" name="<?= csrf_token(); ?>" id="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" style="display: none">
                <input type="hidden" name="allowance_id" value="" />
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteModalLabel"><?= lang('Allowances.modal.delete_all.title') ?></h4>
                        </div>
                        <div class="modal-body">
                            <?= lang('Allowances.modal.delete_all.confirm') ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                                <?= lang('Allowances.modal.delete_all.back') ?>
                            </button>
                            <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleDeleteAll('master_kompensasi/removeSelected','compensation')" id="btn_modal_delete">
                                <?= lang('Allowances.modal.delete_all.submit') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Start Modal Delete -->

    </div>
    <div>
        <?= $this->endSection() ?>