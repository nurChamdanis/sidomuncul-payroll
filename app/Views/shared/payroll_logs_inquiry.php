<div class="row">
    <div class="panel panel-border panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= lang('Shared.payroll_log.title') ?></h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <input type="hidden" name="function_id" id="function_id" value="<?= isset($function_id) ? $function_id : '' ?>" />
                <input type="hidden" name="refference_id" id="refference_id" value="<?= isset($refference_id) ? $refference_id : '' ?>"/>
                <table id="payrollTable" class="payrollTable table table-bordered table-colored table-custom">
                    <thead>
                        <tr>
                            <th><?= lang('Shared.payroll_log.created_dt') ?></th>
                            <th><?= lang('Shared.payroll_log.created_by') ?></th>
                            <th><?= lang('Shared.payroll_log.activity') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>