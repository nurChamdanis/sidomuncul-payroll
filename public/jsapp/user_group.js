var dtTable;

$(document).ready(function () {

    //initialize datatable
    //update by nanin
    dtTable = $('#datatable').DataTable(
            {
                serverSide: true,
                processing: true,
                ajax: {
                    url: SITE_URL + "usergroup/getData",
                    type: "POST",
                    data: function (d) {
                        d.cpms_token = $('#cpms_token').val();
                        d.company_id = $('#p_company_id').val();
                    }
                },
                columnDefs:
                        [
                            {targets: 0, name: 'usergroup_description', sWidth : '400px'},
//                            {targets: 1, name: 'is_admin', className :'text-center'
//                                , render: function (data, type, row, meta) {
//
//                                    if (data == "1") {
//                                        return '<i class="fa fa-check text-primary"></i>';
//                                    } else {
//                                        return '<i class="fa fa-close text-danger"></i>';
//                                    }
//
//                                }
//                            },
//                            {targets: 1, name: 'default_user_lock', className :'text-center', sWidth : '250px'
//                                , render: function (data, type, row, meta) {
//
//                                    if (data == "1") {
//                                        return '<i class="fa fa-check text-primary"></i>';
//                                    } else {
//                                        return '<i class="fa fa-close text-danger"></i>';
//                                    }
//
//                                }
//                            }
                            {targets: 1, name: 'default_landing', className :'text-left', sWidth : '250px' }
                        ],
            });
            
            dtTable.on('draw', function(){
                $('div#dtTable_length select').select2();
                $('div#dtTable_filter input[type=search]').removeClass('input-sm');
            });
            
            $('#p_company_id').select2({});
});

function on_create(){
   window.location = SITE_URL + 'usergroup/create';    
}


























