var dataTableLoader;
var selectedEmployee = [];
var totalSelectedEmployee = 0;

const company_id = $(`#company_id`);
const work_unit_id = $(`#work_unit_id`);
const role_id = $(`#role_id`);
const payroll_period = $(`#payroll_period`);
const absence_period_start = $(`#absence_period_start`);
const absence_period_end = $(`#absence_period_end`);

var tableFilters = () => {
    return {
        company_id: company_id.val(),
        work_unit_id: work_unit_id.val(),
        role_id: role_id.val(),
        payroll_period: payroll_period.val(), 
        absence_period_start: absence_period_start.val(), 
        absence_period_end: absence_period_end.val(), 
		cpms_token : $('#cpms_token').val()
	}; 
}

$(function(){
    $(".payrollperiodpicker").datepicker({
        autoclose: true,
        todayHighlight: false,
        format: "mm/yyyy",
        startView: "year",
        minViewMode: "months",
    })

    const additionalOptions = {
        ordering: true,
        order: [[2, 'ASC']],
        scrollY: "1000px",
        scrollX: true,
        scrollCollapse: true,
    }

    /** Datatable Load */
    dataTableLoader = new DataTableLoader('table', 'generate_payroll/datatable_employeelist', {...tableFilters()}, additionalOptions);
    dataTableLoader.setColumnDefs([
        { targets: 0, orderable: false, className: 'text-center'},
        { targets: 1, name: 'no_reg'},
        { targets: 2, name: 'employee_name'},
        { targets: 3, name: 'work_unit_name'},
        { targets: 4, name: 'role_name'},
        { targets: 5, name: 'position_name'},
        { targets: 6, orderable: false, visible: false},
    ]);
    dataTable = dataTableLoader.load();
});

$('#table').on('draw.dt', function () {
    $('.employee:enabled').each(function(){
        const employeeList = $('#employee_list').val() ? JSON.parse($('#employee_list').val()) : [];
        const employeeValue = $(this).val();
        if (employeeList.some(obj => obj.employee_id == employeeValue)) {
            $(this).prop('checked', true);
        } else {
            $(this).prop('checked', false);
        }
    });
});

const handleSearch = () => {
    if($(`#checkAll`).is(':checked')){
        getAllEmployeeCheckAll();
    }
    dataTableLoader.update({...tableFilters()});
};

const onSelectChange = function(){
    $(`#checkAll`).prop('checked', false);
    handleSearch();
    selectedEmployee = [];
    $(`#employee_list`).val('');
    $(`#total_employee_list_checked`).val(selectedEmployee.length);
    $(`.employee`).each(function(){
        $(this).prop('checked', true);
    });
};
$("#company_id,#work_unit_id,#role_id").on("change", onSelectChange);

const onDateChange = () => { if(payroll_period.val() && absence_period_start.val() && absence_period_end.val()) handleSearch();};
$("#payroll_period,#absence_period_start,#absence_period_end").on("change", onDateChange);

async function handleCheckAllEmployeeList(id, checkedClass)
{
    const componentCheckBoxAll = $(`#${id}`);

    if(!payroll_period.val() && !absence_period_start.val() && !absence_period_end.val()){
        toastr['error']('Please choose salary period and absence period first.');
        componentCheckBoxAll.prop('checked',false);
        return;
    }
    
    if(componentCheckBoxAll.is(':checked'))
    {
        handleSearch();
    } 
    else 
    {
        selectedEmployee = [];
        $(`#employee_list`).val(JSON.stringify(selectedEmployee));
        $(`.${checkedClass}:enabled`).each(function() { $(this).prop('checked',false)});
    }
}

async function getAllEmployeeCheckAll()
{
    var data = new FormData();
    for (k in tableFilters()) data.append(k, ((tableFilters())[k] ? (tableFilters())[k] : ''));

    const config = {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
        },
        body: data
    };

    $('#checkAll').prop('disabled', true);
    $('.employee:enabled').each(function(){$(this).prop('disabled', true);});
    const response = await fetch(`${SITE_URL}generate_payroll/getallemployee_checkall`, config);
    const result = await response.json();
    const {employees, employees_total} = result;

    if(employees){
        if(employees.length > 0 && employees_total > 0) {
            selectedEmployee = employees;
            totalSelectedEmployee = employees_total;
            $(`#employee_list`).val(JSON.stringify(selectedEmployee));
            $(`#total_employee_list_checked`).val(totalSelectedEmployee);
        }
    }
    $('.employee:enabled').each(function(){$(this).prop('disabled', false);});
    $('#checkAll').prop('disabled', false);
}

function selfCheckedEmployeeList(id)
{
    const employeeId = $(`#${id}`).val();
    const totalWorkDay = $(`#${id}`).attr('workday');
    var filteredSelectedEmployee = [];

    if($(`#${id}`).is(':checked')){
        filteredSelectedEmployee = [{employee_id: employeeId, workDay: totalWorkDay, disabled: true}, ...selectedEmployee];
    } else {
        filteredSelectedEmployee = selectedEmployee.filter(item => item.employee_id != employeeId);
    }

    selectedEmployee = filteredSelectedEmployee;
    
    $(`#employee_list`).val(JSON.stringify(selectedEmployee));

    if(selectedEmployee.length == totalSelectedEmployee && selectedEmployee.length != 0)
    {
        $('#checkAll').prop('checked', true);
    } else {
        $('#checkAll').prop('checked', false);
    }
}