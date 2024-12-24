var keyword = $('#keyword');
var dataTableFilters;
var dataTableLoader;

var filterCompany = $('#filterCompany');
let payloadCompanyOptions = {};

var filterArea = $('#filterArea');
let payloadAreaOptions = {};

var filterEmployee = $('#filterEmployee');
let payloadEmployeeOptions = {};

var filterRole = $('#filterRole');
let payloadRoleOptions = {};

var period_start = $('#period_start');
var period_end = $('#period_end');

let checkAll = $('#checkAll');

var tableFilters = () => {
    return {
        company_id : filterCompany.val(),
        work_unit_id : filterArea.val(),
        employee_id : filterEmployee.val(),
        role_id : filterRole.val(),
        period_start : period_start.val(),
        period_end : period_end.val(),
        keyword : keyword.val(),
		cpms_token : $('#cpms_token').val()
	}; 
}

$(function(){
    /** Company Options */
    OptionsSelect({id: 'filterCompany', url: 'generate_payroll/options/company', payload: payloadCompanyOptions});
    payloadAreaOptions.company_id = filterCompany.val();
    payloadRoleOptions.company_id = filterCompany.val();
    payloadEmployeeOptions.company_id = filterCompany.val();
    $('#filterCompany').change(function(){
        payloadAreaOptions.company_id = filterCompany.val();
        payloadRoleOptions.company_id = filterCompany.val();
        payloadEmployeeOptions.company_id = filterCompany.val();
        $('#filterArea').select2('val','');
        $('#filterRole').select2('val','');
        $('#filterEmployee').select2('val','');
    });

    /** Area Options */
    OptionsSelect({id: 'filterArea', url: 'generate_payroll/options/area', payload: payloadAreaOptions});
    payloadEmployeeOptions.work_unit_id = filterCompany.val();
    $('#filterArea').change(function(){
        payloadEmployeeOptions.work_unit_id = filterCompany.val();
        $('#filterEmployee').select2('val','');
    });

    /** Role Options */
    OptionsSelect({id: 'filterRole', url: 'generate_payroll/options/role', payload: payloadRoleOptions});
    payloadEmployeeOptions.role_id = filterRole.val();
    $('#filterRole').change(function(){
        payloadEmployeeOptions.role_id = filterRole.val();
        $('#filterEmployee').select2('val','');
    });

    /** Employee Options */
    OptionsSelect({id: 'filterEmployee', url: 'generate_payroll/options/employee', payload: payloadRoleOptions});

    const additionalOptions = {
        ordering: true,
        order: [[12, 'DESC']],
        scrollY: "1000px",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 0
        }
    }

    /** Datatable Load */
    dataTableLoader = new DataTableLoader('table', 'generate_payroll/datatable', {...tableFilters()}, additionalOptions);
    dataTableLoader.setColumnDefs([
        { targets: 0, orderable: false},
        { targets: 6, className: 'text-right'},
        { targets: 7, className: 'text-right'},
        { targets: 8, className: 'text-right'},
        { targets: 9, className: 'text-right'},
        { targets: 10, className: 'text-right'},
        { targets: 11, className: 'text-center', visible: false},
        { targets: 12, render: (data) => `<div class="author">${data}</div>`, name: 'created_by'},
        { targets: 13, render: (data) => `<div class="timestamp">${data}</div>`, name: 'created_dt'},
        { targets: 14, render: (data) => `<div class="author">${data}</div>`, name: 'changed_by'},
        { targets: 15, render: (data) => `<div class="timestamp">${data}</div>`, name: 'changed_dt'},
    ]);
    dataTable = dataTableLoader.load();
});

const handleSearch = () => dataTableLoader.update({...tableFilters()});
const handleReset = () => { dataTableLoader.update({...tableFilters()});}
