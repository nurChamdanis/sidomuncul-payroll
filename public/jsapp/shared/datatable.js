function DataTableLoader(id, url, initialParams, options) {
    this.id = id;
    this.url = url;
    this.options = options || {};
    this.defaultOptions = {
        lengthMenu: [[25, 50, 100, 200, -1], [25, 50, 100, 200, 'All']],
        paging: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: SITE_URL + url,
            type: 'POST',
            data: function(d) {
                for (var prop in this.tableParams) {
                    if (this.tableParams.hasOwnProperty(prop)) {
                        d[prop] = this.tableParams[prop];
                    }
                }
                return d;
            }.bind(this)
        },
        autoWidth: false,
        pagingType: 'full_numbers',
        processing: true,
        columnDefs: [],
        language: {
            'processing': lang.Shared.datatable_processing,
            'emptyTable': lang.Shared.datatable_emptyTable,
            'info': lang.Shared.datatable_info,
            'infoEmpty': lang.Shared.datatable_infoEmpty,
            'paginate': {
                first: lang.Shared.datatable_paginate_first,
                last: lang.Shared.datatable_paginate_last,
                next: lang.Shared.datatable_paginate_next,
                previous: lang.Shared.datatable_paginate_previous
            },
            'search': lang.Shared.datatable_search,
            'lengthMenu': lang.Shared.datatable_lengthMenu,
        },
    };
    this.tableParams = initialParams || {}; // Initialize tableParams with initialParams
}

DataTableLoader.prototype.load = function() {
    this.dataTable = $('#' + this.id).DataTable(jQuery.extend(true, {}, this.defaultOptions, this.options));
    return this.dataTable;
};

DataTableLoader.prototype.refresh = function() {
    if (this.dataTable) {
        this.dataTable.ajax.reload();
    } else {
        console.error('DataTable is not initialized yet.');
    }
};

DataTableLoader.prototype.update = function(newParams) {
    this.tableParams = newParams;
    this.refresh();
};

DataTableLoader.prototype.setColumnDefs = function(columnDefs) {
    if (columnDefs !== null) {
        this.defaultOptions.columnDefs = columnDefs;
        // Apply changes to existing DataTable if initialized
        if (this.dataTable) {
            this.dataTable.destroy();
            this.load();
        }
    }
};