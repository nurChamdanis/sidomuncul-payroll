$(document).ready(function () {
      
    $('#dtTable tbody').on('click', 'td a.view', function (e) {

        var node = $(this).parents('tr')[0];
        $('tr.active').removeClass('active');
        $(node).addClass("active");
        var function_id = $(node.cells[2]).text();
        var function_name = $(node.cells[0]).text();
        var usergroup = $('#user_group_id').val();
        
        $('.modal-title').text('Fitur ' +function_name);
        $('#modal_fitur').modal('show');
        
        $('#content-feature').html('<center><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i></center>');
        showFeatureList(function_id, function_name, usergroup);

    });
    
    $('.tree-2').treegrid({
        expanderExpandedClass: 'glyphicon glyphicon-minus',
        expanderCollapsedClass: 'glyphicon glyphicon-plus'
    });
    $('.tree-2').treegrid('collapseAll');
    
    
});

function showFeatureList(function_id, name, usergroup){
    
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: SITE_URL + 'usergroup/getDataFeature',
        data: {
            function_id : function_id
            , usergroup : usergroup
            , type : 'view'
            , cpms_token: $('#cpms_token').val(),
        },
        success : function(data){
            var html = generateTableFeature(data, name);
            $('#content-feature').html(html);
        }
    });
    
}

function generateTableFeature(data, name){
    var html = "";
    //var html = '<h5> Fitur ' +name+'</h5>';
    html += '<div class="table-responsive">'+
            '<table class="table m-0 table-colored table-teal table-bordered">'+
                '<thead>'+
                    '<tr>'+
                        //'<th></th>'+
                        '<th class="text-center">Tipe Akses</th>'+
                        '<th class="text-left">Keterangan</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
    if(data.length > 0){
        $.each(data, function( i, value ) {
            var row = data[i];
            html += '<tr>'+
                    //'<td class="text-center">'+row[0]+'</td>'+
                    '<td class="text-center">'+row[1]+'</td>'+
                    '<td class="text-left">'+row[2]+'</td>'+
                '</tr>';
        });
    }else{
        html += '<tr> <td colspan="3" class="text-center"> Tidak ada data </td> </tr>';
    }

    html += '</tbody></table></div>';

    return html;
}

function getDataFunction(function_id) {
    var request = $.ajax({
        type: 'POST',
        dataType: 'json',
        async: false,
        url: SITE_URL + 'usergroup/getDataFunction',
        data: {
            function_id : function_id,
            cpms_token: $('#cpms_token').val(),
        }
    });
    var result = request.responseJSON;
    return (result.length > 0) ? result : [];
}

function on_delete_confirm()
{
    $('#btn_delete_confirm').html('Mohon Tunggu...');
    $('#btn_delete_confirm').attr('disabled', 'disabled');

    $('#form_usergroup').submit();
}


























