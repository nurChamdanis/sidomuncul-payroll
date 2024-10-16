var dtTable;

$(document).ready(function () {
    //update by nanin
    //initialize datatable
    dtTable = $('#datatable').DataTable(
    {
        fixedHeader: true,
        ordering : false,
        lengthMenu : [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, 'All']],
        paging : true,
        serverSide : true,
        ajax :
        {
            url: SITE_URL + "user/getData",
            type: "POST",
            data : function (d)
            {
                d.cpms_token = $('#cpms_token').val();
                d.company_id = $('#company_id').val()
                d.user_group_id = $('#user_group_id').val()
            }
        },
        //dom : '<"top">rt<"bottom"lpi><"clear">',
        pagingType : 'full_numbers',
        processing : true,
        columnDefs :
        [
            // {targets: 0, name: 'user_email'},
            // {targets: 1, name: 'full_name', className :'text-left'},
            // {targets: 2, name: 'user_group_description', className :'text-left'},
            // {targets: 3, name: 'is_active_text', className :'text-left'},
            // {targets: 4, name: 'super_admin_text', className :'text-left'},
            // {targets: 5, name: 'email_confirmed_text', className :'text-left'},
        ],
        language: {
            "processing": lang.Shared.datatable_processing,
            "emptyTable": lang.Shared.datatable_emptyTable,
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
    });
            
    $('#company_id').select2().on('select2:select', function (e) {
		$('#user_group_id').val("0").change();
	});

    $('#user_group_id').select2({
		allowClear: false,
		width: "100%",
		ajax:
		{
			type: 'POST',
			url: SITE_URL + 'user/getUserGroups',
			dataType: 'json',
			delay: 500,
			async: false,
			data: function (params) {

				var aSearch = {
					company_id: $("#company_id").val()
					, keyword: params.term
					, cpms_token : $('#cpms_token').val()
				}
				return aSearch;
			},
			processResults: function (data, params) {

				// var d = [{id: "0", text: "All Org. Unit"}];
				// d = d.concat(data);
				
				return { results: data };
			},
			cache: true
		},
		minimumInputLength: 0,
		escapeMarkup: function (markup) {
			return markup;
		}
	}).on('select2:select', function (e) {
		
	});
           
});

function on_create(){
   var urel = SITE_URL + 'user/create';    
   window.open(urel, '_self');
}


























