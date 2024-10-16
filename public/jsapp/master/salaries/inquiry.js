let filterCompany = $('#filterCompany');
let filterArea = $('#filterArea');
let filterRole = $('#filterRole');
let filterEmployee = $('#filterEmployee');
let filterCostCenter = $('#filterCostCenter');
let filterStatus = $('#filterStatus');
let period_from = $('#periodFrom');
let period_to = $('#periodTo');
let employee_group_id = $('#filterContractType');

let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadEmployeeOptions = {};
let payloadSystemOptions = {};
let payloadRoleOptions = {};
let payrollContractOptions = {};
let checkAll = $('#checkAll');

let today = new Date();
let formattedDate = today.getFullYear() + '-' + 
    ('0' + (today.getMonth() + 1)).slice(-2) + '-' + 
    ('0' + today.getDate()).slice(-2);

let tableFilters = () => {
    return {
        company_id : filterCompany.val(),
        work_unit_id : filterArea.val(),
        role_id : filterRole.val(),
        employee_id : filterEmployee.val(),
        history_flg : filterStatus.val(),
        period_from : period_from.val(),
        period_to : period_to.val(),
        employee_group_id : employee_group_id.val(),
		cpms_token : $('#cpms_token').val()
	}; 
}

$(function(){

     /** Company Options */
    OptionsSelect({id: 'filterCompany', url: 'master_salaries/options/company', payload: payloadCompanyOptions});
    payloadAreaOptions.company_id = $(this).val();
    $('#filterCompany').change(function(){
        payloadAreaOptions.company_id = $(this).val();
        payloadRoleOptions.company_id = $(this).val();
        payloadEmployeeOptions.company_id = $(this).val();
        $('#filterArea').select2('val','');
        $('#filterEmployee').select2('val','');
    });

    /** Area Options */
    OptionsSelect({id: 'filterArea', url: 'master_salaries/options/area', payload: payloadAreaOptions});
    $('#filterArea').change(function(){
        payloadEmployeeOptions.work_unit_id = $(this).val();
        $('#filterEmployee').select2('val','');
    });

     /** Employee Options */
    OptionsSelect({id: 'filterRole', url: 'master_salaries/options/role', payload: payloadRoleOptions});
    $('#filterRole').change(function(){
        payloadEmployeeOptions.role_id = $(this).val();
        $('#filterEmployee').select2('val','');
    });

    /** Employee Options */
    OptionsSelect({id: 'filterEmployee', url: 'master_salaries/options/employee', payload: payloadEmployeeOptions});

     /** Loan Duration Option */
    OptionsSelect({id: 'filterStatus', url: 'master_loan/options/system', payload: payloadSystemOptions = {
        system_type : 'basic_salary_status',
    }});

    /** Contract Type Options */
    OptionsSelect({id: 'filterContractType', url: 'master_salaries/options/contract_type', payload: payrollContractOptions});

    // Set the default date value
    $('#periodFrom').val(formattedDate);
    $('#periodTo').val(formattedDate);

    $('#periodFrom').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function(e) {
        var selectedDate = $('#periodFrom').datepicker('getDate');
        $('#periodTo').datepicker('setStartDate', selectedDate);
        if ($('#periodTo').datepicker('getDate') < selectedDate) {
            $('#periodTo').datepicker('setDate', selectedDate);
        }
    });

    $('#periodTo').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    $('#periodFrom').datepicker('update', $('#periodFrom').val()).trigger('changeDate');

    const additionalOptions = {
        ordering: true,
        order: [[4, 'DESC']],
        scrollY: "1000px",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 0
        },
    }

    /** Datatable Load */
    dataTableLoader = new DataTableLoader('table', 'master_salaries/datatable', {...tableFilters()}, additionalOptions);
    dataTableLoader.setColumnDefs([
        { targets: 0, className:'text-center', orderable: false},
        { targets: 1, className:'text-left', name: 'company_name'},
        { targets: 2, className:'text-center', name: 'work_unit_name'},
        { targets: 3, className:'text-center', name: 'role_name'},
        { targets: 4, className:'text-center', name: 'no_reg'},
        { 
            targets: 5, className:'text-center', name: 'employee_name',
            render: (data, type, row) => {
                return `<a style="display:block; width: 100%; text-align:center;" href="${SITE_URL}master_salaries/id/${row[15]}" class="author">
                    ${data}
                </a>`;
            },
        },
        { targets: 6, className:'text-center', name: 'loan_type_name'},
        { targets: 7, className:'text-center', name: 'loan_duration'},
        { targets: 8, className:'text-right', name: 'loan_total'},
        { targets: 9, className:'text-center', name: 'deduction_period_start'},
        { targets: 10, className:'text-center', name: 'deduction_period_end'},
        { targets: 11, render: (data) => `<div class="author">${data}</div>`, name: 'created_by'},
        { targets: 12, render: (data) => `<div class="timestamp">${data}</div>`, name: 'created_dt'},
        { targets: 13, render: (data) => `<div class="author">${data}</div>`, name: 'changed_by'},
        { targets: 14, render: (data) => `<div class="timestamp">${data}</div>`, name: 'changed_dt'},
    ]);
    dataTable = dataTableLoader.load();

});

function on_create(){
    window.location = SITE_URL + 'master_salaries/create';    
}

const handleSearch = () => dataTableLoader.update({...tableFilters()});
const handleReset = () => { 
    $('#filterCompany').select2('val','');
    $('#filterArea').select2('val','');
    $('#filterRole').select2('val','');
    $('#filterEmployee').select2('val','');
    $('#filterStatus').select2('val','');
    $('#periodFrom').val(formattedDate);
    $('#periodTo').val(formattedDate);
    $('#filterContractType').select2('val','');
    dataTableLoader.update({...tableFilters()});
}
