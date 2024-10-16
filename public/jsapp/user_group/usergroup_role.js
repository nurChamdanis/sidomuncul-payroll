var dtTable;
var action = ["Tambah", "Ubah","Hapus","Setujui","Tolak","Ekspor","Impor","Unggah","Unduh"];
var dataColumn = [ 
    { title: " ", sWidth : "10px", className : "details-control text-center" }, 
    { title: "Function Id", sWidth : "100px", className : "text-left", visible : false} ,
    { title: "Modul", sWidth : "100px", className : "text-left"} ,
];
        
$(document).ready(function () {
    
    $('.tree-2').treegrid({
        expanderExpandedClass: 'glyphicon glyphicon-minus',
        expanderCollapsedClass: 'glyphicon glyphicon-plus'
    });
    $('.tree-2').treegrid('collapseAll');
    
    
    $.each(action, function( j, v2 ) {
        dataColumn.push({ 
            title: action[j], sWidth : "40px", className : "text-center" 
        });
    });
   
//    var dataObjColumn = eval('[{"COLUMNS":'+JSON.stringify(dataColumn)+'}]');
//    dtTable = $('#dtTable').dataTable({
//        serverSide: false,
//        processing: true,
//        info : false,
//        paging: false,
//        searching: false,
//        ordering : false,
//        columns: dataObjColumn[0].COLUMNS
//    });
//    $('#dtTable tbody').on('click', 'td.details-control', function () {
//        var tr = $(this).closest('tr');
//        var row = dtTable.api().row(tr);
//        
//        if ( row.child.isShown() ) {
//            $('div.slider', row.child()).slideUp( function () {
//                row.child.hide();
//                tr.removeClass('shown');
//                tr.find('td').eq(0).html('<i class="fa fa-plus"></i>');
//            } );
//        }
//        else {
//            row.child( detail(row.data()), 'no-padding' ).show();
//            tr.addClass('shown');
//            tr.find('td').eq(0).html('<i class="fa fa-minus"></i>');
//            $('div.slider', row.child()).slideDown();
//        }
//    });
    
    
    
    $('.select2').select2({
        placeholder : 'Pilih grup pengguna'
    }).on('change', function(){
        var v = $('.select2').select2('val');
        if (v){   
            loadTable();
            //var data = getDataParentFunction();
            //dtTable.api().clear().rows.add(data).draw();
        }
    });
    $('.select2').select2('val','');
    
    
    
    //dtTable.api().clear().rows.add([]).draw();
});


//function detail(d) {
//    var html = "";
//    
//    var data = getDataFunction(d[1]);
//    
//    html = '<div class="slider"><table class="table m-0 table-colored table-teal table-bordered">';
//    if (data.length>0){
//        $.each(data, function( i, value ) {
//            var row = data[i];
//            html += '<tr>'+
//                    '<td width="22px"> </td>'+
//                    '<td class="text-left" width="146px">'+row[1]+'</td>'+
//                    '<td class="text-center" width="76px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                    '<td class="text-center" width="63px">'+row[2]+'</td>'+
//                '</tr>';
//        });
//    }else{
//        html += '<tr> <td class="text-center"> Tidak ada data </td> </tr>';
//    }
//    html += '</table></div>';
//    
//    return html;
//}

function loadTable(){
    
    $('table.tree-2').find('tbody').remove()
    $('table.tree-2').append('<tbody><td class="text-center" colspan="'+(featureLength + 1 )+'"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i></td></tbody>');
    
    var usergroup = $('.select2').select2('val');
    var _featureName = $.map(JSON.parse(featureList), function(item) { return item.feature_name; });
    var _featureId = $.map(JSON.parse(featureList), function(item) { return item.feature_id; });
    
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: SITE_URL + 'usergroup_role/getDataFunction',
        data: {
            usergroup : usergroup
            , featureName : _featureName.join()
            , featureId : _featureId.join()
        },
        success : function(data){
            var html = generateTable(data);
            $('table.tree-2').find('tbody').remove()
            $('table.tree-2').append(html);
            $('.tree-2').treegrid({
                expanderExpandedClass: 'glyphicon glyphicon-minus',
                expanderCollapsedClass: 'glyphicon glyphicon-plus'
            });
            $('.tree-2').treegrid('collapseAll');
            $('input[type=checkbox]').on('click', function(){
                var id = $(this).attr('id');
                updateUsergroupRole(this.checked, id);
            });
        }
    });
}

function generateTable(data){
    //var action = ["Tambah", "Ubah","Hapus","Lihat","Cetak","Ekspor","Impor","Unggah","Unduh"];
    //html = '<table id="dtTable2" class="table tree-2 table-colored table-teal table-bordered">';
    //html += '<thead>'+
    //            '<tr>'+
    //                '<th class="text-center"></th>'+
    //                '<th class="text-center" width="250px">Modul</th>';
                
    //$.each(action, function( i, value ) {
    //    html += '<th class="text-center">'+action[i]+'</th>';
    //});
    var html ;
    html = '<tbody>';
    if (data.length>0){
        $.each(data, function( i, v1 ) {
            var row = data[i];
            if (row[1] == "0" || row[1] == "null"){
                html += '<tr class="treegrid-'+row[0]+'">';
            }else{
                html += '<tr class="treegrid-'+row[0]+' treegrid-parent-'+row[1]+' ">';
            }
            
            html += '<td class="text-left">'+row[2]+'</td>';
            $.each(JSON.parse(featureList), function( j, v2 ) {
                //html += '<td class="text-center"><div class="checkbox checkbox-default" style="margin-bottom:0px; margin-top:0px"><input id="chkfid'+row[0]+'aid'+j+'" type="checkbox" checked > <label for="checkbox3"></label></div></td>';
               
                html += '<td class="text-center">'+row[j+3] + '</td>';
            });
            html += '</tr>';
        });
    }else{
        html += '<tr> <td class="text-center"> Tidak ada data </td> </tr>';
    }
    html += '</tbody>';
    return html;
}



//function getDataParentFunction() {
//    var request = $.ajax({
//        type: 'POST',
//        dataType: 'json',
//        async: false,
//        url: SITE_URL + 'usergroup_role/getDataFunction',
//        data: {}
//        , success: function (data) {
//        }
//    });
//    var result = request.responseJSON;
//    return (result != null) ? result : [];
//}


//function getDataFunction(function_id) {
//    var request = $.ajax({
//        type: 'POST',
//        dataType: 'json',
//        async: false,
//        url: SITE_URL + 'user_group/getDataFunction',
//        data: {
//            function_id : function_id
//            , feature_length : featureLength
//        }
//    });
//    var result = request.responseJSON;
//    return (result.length > 0) ? result : [];
//}

function updateUsergroupRole(isChecked, id){
    
    var str = id.replace("chkid_","");
    var data = str.split("-");
    
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: SITE_URL + 'usergroup_role/updateUsergroupRole',
        data: {
            isChecked : isChecked
            , user_group_id : data[0]
            , function_id : data[1]
            , feature_id : data[2]
        },
        success : function(data){
            
        }
        
    });
}























