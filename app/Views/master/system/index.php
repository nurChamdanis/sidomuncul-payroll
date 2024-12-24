<!-- Extend Layout -->
<?= $this->extend('layouts/default/index') ?>

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
                        <div class="form-group row">
                            <label class="col-md-12 control-label"><?= lang('System.filter.search_label') ?></label>
                            <div class="col-md-12" >						
                                <input type="text" class="form-control" placeholder="<?= lang('System.filter.search_placeholder') ?>" id="search"/>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-8">
                                <?= create_button($button, "btn_add"); ?>
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
                        <table id="table" class="table table-bordered table-hover table-colored table-custom">
                            <thead>
                                <tr>
                                    <th><?= lang('System.inquiry.system_type') ?></th>
                                    <th><?= lang('System.inquiry.system_code') ?></th>
                                    <th><?= lang('System.inquiry.system_value_id') ?></th>
                                    <th><?= lang('System.inquiry.created_by') ?></th>
                                    <th><?= lang('System.inquiry.changed_by') ?></th>
                                    <th><?= lang('System.inquiry.created_at') ?></th>
                                    <th><?= lang('System.inquiry.updated_at') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
<!-- End Content Section -->