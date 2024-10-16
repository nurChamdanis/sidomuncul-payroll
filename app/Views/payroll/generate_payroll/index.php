<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

<?= $this->section('styles') ?>
<style type="text/css">
    #table{width: 2300px !important;}
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
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Shared.label.company') ?></label>
                                    <div class="col-md-9" >						
                                        <select class="form-control" name="filterCompany" id="filterCompany" placeholder="<?= lang('Shared.placeholder.all.company') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Shared.label.role') ?></label>
                                    <div class="col-md-9" >						
                                        <select class="form-control" name="filterArea" id="filterArea" placeholder="<?= lang('Shared.placeholder.all.area') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Shared.label.role') ?></label>
                                    <div class="col-md-9" >						
                                        <select class="form-control" name="filterRole" id="filterRole" placeholder="<?= lang('Shared.placeholder.all.role') ?>"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('GeneratePayroll.inquiry.period') ?></label>
                                    <div class="col-md-9" style="display: flex; justify-content: space-between; gap: 15px; align-items: center;">						
                                        <input type="text" class="form-control date" id="period_start" readonly style="background: white !important;"/>
                                        <span>to</span>
                                        <input type="text" class="form-control date" id="period_end" readonly style="background: white !important;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Shared.label.employee') ?></label>
                                    <div class="col-md-9" >						
                                        <select class="form-control" name="filterEmployee" id="filterEmployee" placeholder="<?= lang('Shared.placeholder.all.employee') ?>"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-3 control-label"><?= lang('Shared.label.keyword') ?></label>
                                    <div class="col-md-9" >						
                                        <input type="text" class="form-control" id="keyword"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-8">
                                <?= create_button($button, "btn_generate"); ?>
                                <?= create_button($button, "btn_posting"); ?>
                                <?= create_button($button, "btn_lock"); ?>
                                <?= create_button($button, "btn_download"); ?>
                            </div>
                            <div class="col-md-4 text-right">
                                <?= create_button($button, "btn_search"); ?>
                                <?= create_button($button, "btn_reset"); ?>
                                <button 
                                    id="toggleFilter" 
                                    class="btn btn-default btn-bordered waves-light waves-effect m-b-5" 
                                    title="hide-filter" 
                                    onclick="toggleFilter(this)">
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
                                            <input name="checkAll" class="checkAll" value="" id="checkAll" type="checkbox" ex-attr-edit="btn_edit_inquiry" ex-attr-delete="btn_delete_inquiry"  ex-attr-checked="payroll_transaction" onclick="handleCheckAll(this.id, 'payroll_transaction')">
                                            <label for="checkAll" class=""></label>
                                        </div>
                                    </th>
                                    <th><?= lang('Shared.label.company') ?></th>
                                    <th><?= lang('Shared.label.area') ?></th>
                                    <th><?= lang('Shared.label.role') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.period') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.description') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.total_employee') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.total_deductions') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.total_allowances') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.total_bruto') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.total_netto') ?></th>
                                    <th><?= lang('GeneratePayroll.inquiry.effective_date') ?></th>
                                    <th><?= lang('Shared.label.created_by') ?></th>
                                    <th><?= lang('Shared.label.created_at') ?></th>
                                    <th><?= lang('Shared.label.changed_by') ?></th>
                                    <th><?= lang('Shared.label.changed_at') ?></th>
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
                        <h4 class="modal-title" id="deleteModalLabel"><?= lang('Allowances.modal.delete_all.title') ?></h4>
                    </div>
                    <div class="modal-body">
                        <?= lang('Allowances.modal.delete_all.confirm') ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                            <?= lang('Allowances.modal.delete_all.back') ?>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" onclick="handleDeleteAll('generate_payroll/removeSelected','payroll_transaction','btn_modal_delete')" id="btn_modal_delete">
                            <?= lang('Allowances.modal.delete_all.submit') ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Start Modal Delete -->
<?= $this->endSection() ?>
<!-- End Content Section -->