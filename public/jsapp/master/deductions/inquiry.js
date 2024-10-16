var keyword = $('#keyword');
var filterCompany = $('#filterCompany');
var filterArea = $('#filterArea');
var filterGrup = $('#filterGrup');
var dataTableFilters;
var dataTableLoader;
let payloadCompanyOptions = {};
let payloadAreaOptions = {};
let payloadAreaGroupOptions = {};
let checkAll = $('#checkAll');

var tableFilters = () => {
    return {
        company_id : filterCompany.val(),
        work_unit_id : filterArea.val(),
        area_grup_id : filterGrup.val(),
        keyword : keyword.val(),
		cpms_token : $('#cpms_token').val()
	}; 
}

$(function(){
    /** Company Options */
    OptionsSelect({id: 'filterCompany', url: 'master_potongan/options/company', payload: payloadCompanyOptions});
    payloadAreaOptions.company_id = $(this).val();
    $('#filterCompany').change(function(){
        payloadAreaOptions.company_id = $(this).val();
        $('#filterArea').select2('val','');
    });

    /** Area Options */
    OptionsSelect({id: 'filterArea', url: 'master_potongan/options/area', payload: payloadAreaOptions});
    
    /** Area Group Options */
    OptionsSelect({id: 'filterGrup', url: 'master_potongan/options/areagroup', payload: payloadAreaGroupOptions});

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
    dataTableLoader = new DataTableLoader('table', 'master_potongan/datatable', {...tableFilters()}, additionalOptions);
    dataTableLoader.setColumnDefs([
        { targets: 0, className:'text-center', orderable: false},
        { 
            targets: 1, 
            width: '200px', 
            render: (data, type, row) => {
                console.log(row[12]);
                return `<a style="display:block; width: 100%; text-align:left;" href="${SITE_URL}master_potongan/id/${row[12]}" class="author">
                            ${data}
                        </a>`;
            },
            name: 'company_id'
        },
        { 
            targets: 2, render: (data) => {
                const area = data.split(",");
                return `<ul class="group-list">${area.map((item) =>`<li>${item}</li>`).join('')}</ul>`;
            },
            orderable: false
        },
        { 
            targets: 3, render: (data) => {
                const group = data.split(",");
                const className = data != "-" ? 'group-list' : 'empty-list';
                return `<ul class="${className}">${group.map((item) =>`<li>${item}</li>`).join('')}</ul>`;
            },
            orderable: false
        },
        { targets: 4, className:'text-center', name: 'effective_date'},
        { targets: 5, className:'text-left', name: 'deduction_name'},
        { targets: 6, className:'text-right', orderable: false},
        { targets: 7, className:'text-center', name: 'is_active'},
        { targets: 8, render: (data) => `<div class="author">${data}</div>`, name: 'created_by'},
        { targets: 9, render: (data) => `<div class="timestamp">${data}</div>`, name: 'created_dt'},
        { targets: 10, render: (data) => `<div class="author">${data}</div>`, name: 'changed_by'},
        { targets: 11, render: (data) => `<div class="timestamp">${data}</div>`, name: 'changed_dt'},
    ]);
    dataTable = dataTableLoader.load();
});

const handleSearch = () => dataTableLoader.update({...tableFilters()});
const handleReset = () => { search.val(''); dataTableLoader.update({...tableFilters()});}
