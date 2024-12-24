<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>


<?= $this->section('styles') ?>
<style type="text/css">
    #table{width: 2400px !important;}
    ul.group-list,ul.empty-list{margin: 0px; padding: 0px; list-style-type: none;}
    ul.group-list li{ border: 1px solid #ddd; padding: 2px 10px; margin: 3px 0px; background-color: white; border-radius: 5px; font-weight: 600;}
    ul.empty-list li{ padding: 2px 5px; margin: 5px 0px; text-align: center;}
</style>
<?= $this->endSection() ?>

<!-- Start Content Section -->
<?= $this->section('content') ?>
    <div class="container">
        <!-- Breadcrumb Section -->
        <div class="row">
            <div class="col-xs-12">
                <div class="page-title-box">
                    <h4 class="page-title"><?=$function_name?></h4>
                    <ol class="breadcrumb p-0 m-0">
                        <li><?=$function_grp_name;?></li>
                        <li class="active"><?=$function_name?></li>
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
                    <form id="form_official_travel" class="form-horizontal" role="form" method="post" action="">
                        <div class="row">
                            <input type="hidden" name="<?= csrf_token();?>" id="<?= csrf_token();?>" value="<?= csrf_hash();?>" style="display: none">
                            <div class="col-md-4 m-t-10">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Shared.label.company') ?></label>
                                    <div class="col-md-9" >						
                                        <select class="form-control select2" name="filterCompany" id="filterCompany" placeholder="<?= lang('Shared.placeholder.all.company') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-t-10">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Shared.label.area') ?></label>
                                    <div class="col-md-9" >						
                                        <select class="form-control select2" name="filterArea" id="filterArea" placeholder="<?= lang('Shared.placeholder.all.area') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-t-10">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Loan.filter.role') ?></label>
                                    <div class="col-md-9">
                                        <select class="form-control select2" name="filterRole" id="filterRole" placeholder="<?= lang('Loan.all.role') ?>">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-t-10">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Loan.filter.employee') ?></label>
                                    <div class="col-md-9">
                                        <select class="form-control select2" name="filterEmployee" id="filterEmployee"  placeholder="<?= lang('Loan.all.employee') ?>">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-t-10">
                                <div class="form-group row">
                                    <label for="startDate" class="col-sm-3 col-form-label"><?= lang('Loan.filter.period') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="periodFrom">
                                    </div>
                                    <label for="endDate" class="col-sm-1 col-form-label text-center m-t-5"><?= lang('Loan.filter.to') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="periodTo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-t-10">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Salaries.filter.status') ?></label>
                                    <div class="col-md-9">
                                        <select class="form-control select2" name="filterStatus" id="filterStatus" placeholder="<?= lang('Salaries.all.status') ?>">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-t-10">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Salaries.filter.contract_type') ?></label>
                                    <div class="col-md-9">
                                        <select class="form-control select2" name="filterContractType" id="filterContractType" placeholder="<?= lang('Salaries.all.contract_type') ?>">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>          
                    </form>
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
                                <button 
                                    id="toggleFilter" 
                                    class="btn btn-default btn-sm btn-bordered waves-light waves-effect m-b-5" 
                                    title="hide-filter" 
                                    onclick="toggleFilter(this)">
                                    <i id="filterIcon" class="mdi mdi-filter-remove"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table id="table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <div class="checkbox checkbox-custom">
                                            <input name="checkAll" class="checkAll" value="" id="checkAll" type="checkbox" ex-attr-edit="btn_edit_inquiry" ex-attr-delete="btn_delete_inquiry"  ex-attr-checked="salary" onclick="handleCheckAll(this.id, 'salary')">
                                            <label for="checkAll" class=""></label>
                                        </div>
                                    </th>
                                    <th><?= lang('Shared.label.company') ?></th>
                                    <th><?= lang('Shared.label.area') ?></th>
                                    <th><?= lang('Loan.filter.role') ?></th>
                                    <th><?= lang('Loan.inquiry.employee_id') ?></th>
                                    <th><?= lang('Loan.inquiry.employee_name') ?></th>
                                    <th><?= lang('Salaries.inquiry.basic_salary') ?></th>
                                    <th><?= lang('Salaries.inquiry.dedduction') ?></th>
                                    <th><?= lang('Salaries.inquiry.allowance') ?></th>
                                    <th><?= lang('Salaries.inquiry.THP') ?></th>
                                    <th><?= lang('Salaries.inquiry.effective_date') ?></th>
                                    <th><?= lang('Shared.label.created_by') ?></th>
                                    <th><?= lang('Shared.label.created_at') ?></th>
                                    <th><?= lang('Shared.label.changed_by') ?></th>
                                    <th><?= lang('Shared.label.changed_at') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada data</td>
                                </tr>
                            </tbody>
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
            <input type="hidden" name="basic_salary_id_hidden" value="" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="deleteModalLabel"><?= lang('Loan.modal.delete_all.title') ?></h4>
                    </div>
                    <div class="modal-body">
                        <?= lang('Loan.modal.delete_all.confirm') ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                            <?= lang('Loan.modal.delete_all.back') ?>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleDeleteAll('master_salaries/removeSelected','salary')" id="btn_modal_delete">
                            <?= lang('Loan.modal.delete_all.submit') ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Start Modal Delete -->

<?= $this->endSection() ?>
<!-- End Content Section -->