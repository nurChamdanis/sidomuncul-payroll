var search = $('#search');
var dataTableFilters;
var dataTableLoader;
var tableFilters = () => {
    return {
        search : search.val(),
		cpms_token : $('#cpms_token').val()
	}; 
}

$(function(){
    dataTableLoader = new DataTableLoader('table', 'master_system/datatable', {...tableFilters()});
    dataTableLoader.setColumnDefs([
        { 
            targets: 0, 
            width: '200px', 
            render: (data, type, row) => `
                <a style="display:block; width: 100%; text-align:left;" href="${SITE_URL}master_system/id/${row[0]}/${row[1]}" class="author">
                    ${data}
                </a>
            `},
        { targets: 3, render: (data) => `<div class="author">${data}</div>`},
        { targets: 4, render: (data) => `<div class="author">${data}</div>`},
        { targets: 5, render: (data) => `<div class="timestamp">${data}</div>`},
        { targets: 6, render: (data) => `<div class="timestamp">${data}</div>`},
    ]);
    dataTable = dataTableLoader.load();
});

const handleSearch = () => dataTableLoader.update({...tableFilters()});
const handleReset = () => { search.val(''); dataTableLoader.update({...tableFilters()});}
