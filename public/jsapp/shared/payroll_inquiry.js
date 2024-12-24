var payrollDataTableLoader;
var payrollFunctionId = $('#function_id');
var payrollRefferenceId = $('#refference_id');

var tableFilters = () => {
    return {
        function_id : payrollFunctionId.val() ?? '',
        refference_id : payrollRefferenceId.val() ?? '',
		cpms_token : $('#cpms_token').val()
	}; 
}

$(function(){
    /** Datatable Load */
    payrollDataTableLoader = new DataTableLoader('payrollTable', `payroll_logs/datatable`, {...tableFilters()}, {
                                ordering: true,
                                order: [[1, 'DESC']]
                            });
    payrollDataTableLoader.setColumnDefs([
        { targets: 0, className:'text-center', name: 'created_by', orderable: false},
        { targets: 1, className:'text-center', name: 'created_dt', orderable: false},
        { targets: 2, className:'text-left', orderable: false},
    ]);
    dataTable = payrollDataTableLoader.load();
});
