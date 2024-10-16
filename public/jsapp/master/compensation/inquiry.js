var dataTableFilters;
var dataTableLoader;
var tableFilters = () => {
  return {
    valid_from: $("#valid_from").val(),
    valid_to: $("#valid_to").val(),
    cpms_token: $("#cpms_token").val(),
    company_id: $("#company-options").val(),
    area_id: $("#area-options").val(),
    unit_id: $("#unit-options").val(),
    employee_id: $("#employee-options").val(),
    compensationType: $("#compensationType-options").val(),
  };
};

var filterClear = () => {
  $("#valid_from").val("").trigger('change');
  $("#valid_to").val("").trigger('change');
  $("#company-options").val("").trigger('change');
  $("#company-options").val("").trigger('change');
  $("#unit-options").val("").trigger('change');
  $("#employee-options").val("").trigger('change');
  $("#compensationType-options").val("").trigger('change');
};

let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadRoleOptions = {};
let payloadEmployeeOptions = {};
let payloadCompensationOptions = {};

$(function () {
  // OptionsSelect({
  //   id: "company-options",
  //   url: "master_kompensasi/options_company",
  //   payload: payloadCompanyOptions,
  // });

  OptionsSelect({id: 'company-options', url: 'master_kompensasi/options_company', payload: payloadCompanyOptions});
  payloadAreaOptions.company_id = $(this).val();
  payloadRoleOptions.company_id = $(this).val();
  payloadEmployeeOptions.company_id = $(this).val();
  $('#company-options').change(function(){
      payloadAreaOptions.company_id = $(this).val();
      payloadRoleOptions.company_id = $(this).val();
      payloadEmployeeOptions.company_id = $(this).val();
      $('#area-options').select2('val','');
      $('#role-options').select2('val','');
      $('#employee-options').select2('val','');
  });


  OptionsSelect({id: 'area-options', url: 'master_kompensasi/options_area', payload: payloadAreaOptions});
  payloadEmployeeOptions .work_unit_id = $(this).val();
  $('#area-options').change(function(){
    payloadEmployeeOptions .work_unit_id = $(this).val();
      $('#employee-options').select2('val','');
  });


  // OptionsSelect({
  //   id: "area-options",
  //   url: "master_kompensasi/options_area",
  //   payload: payloadAreaOptions,
  // });

  OptionsSelect({id: 'role-options', url: "master_kompensasi/options_role", payload: payloadAreaOptions});
  payloadEmployeeOptions .role_id = $(this).val();
  $('#role-options').change(function(){
    payloadEmployeeOptions .role_id = $(this).val();
      $('#employee-options').select2('val','');
  });

  // OptionsSelect({
  //   id: "role-options",
  //   url: "master_kompensasi/options_role",
  //   payload: payloadRoleOptions,
  // });

   // 'company_id', 'work_unit_id', 'role_id'

  OptionsSelect({
    id: "employee-options",
    url: "master_kompensasi/options_employee",
    payload: payloadEmployeeOptions,
  });

  OptionsSelect({
    id: "compensationType-options",
    url: "master_kompensasi/options_compensationType",
    payload: payloadCompensationOptions,
  });

  const additionalOptions = {
    ordering: true,
    order: [[10, 'DESC']],
    scrollY: "1000px",
    scrollX: true,
    scrollCollapse: true,
    fixedColumns: {
        leftColumns: 0,
        rightColumns: 0
    },
}

  dataTableLoader = new DataTableLoader(
    "table",
    "master_kompensasi/datatable",
    { ...tableFilters() },
    additionalOptions
  );
  dataTableLoader.setColumnDefs([
    {
      targets: 0, className: 'text-center', orderable: false
    },
    { targets: 1, render: (data) => `<div>${data}</div>`, name: 'company_name' },
    { targets: 2, render: (data) => `<div class="author">${data}</div>`, name: 'name' },
    { targets: 3, render: (data) => `<div>${data}</div>`, name: 'role_id'},
    { targets: 4, render: (data) => `<div>${data}</div>`, name: 'no_reg' },
    { targets: 5, render: (data) => `<div class="author">${data}</div>`, name: 'employee_name' },
    { targets: 6, render: (data) => `<div>${data}</div>`, name: 'period' },
    { targets: 7, render: (data) => `<div>${data}</div>`, name: 'system_value_txt' },
    { targets: 8, render: (data) => `<div>${data}</div>`, name: 'total_compensation' },
    { targets: 9, render: (data) => `<div>${data}</div>`, name: 'created_by' },
    { targets: 10, render: (data) => `<div class="timestamp">${data}</div>`,name:'created_dt' },
    { targets: 11, render: (data) => `<div>${data}</div>`, name: 'changed_by'},
  ]);
  dataTable = dataTableLoader.load();
});


const handleSearch = () => dataTableLoader.update({ ...tableFilters() });

const handleReset = () => { filterClear(); dataTableLoader.update({ ...tableFilters() }) }

