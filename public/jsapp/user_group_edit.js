var dtTable;
var relatedOptName = 'Related';
$(document).ready(function () {
//     dtTable = $('#datatable').DataTable(
//         {
//             serverSide: true,
//             processing: true,
//             ordering : false,
//             ajax: {
//                 url: SITE_URL + "usergroup/geFunctionFeature",
//                 type: "POST",
//                 data: function (d) {
//                     d.cpms_token = $('#cpms_token').val();
//                 }
//             },
//             columnDefs:
//                     [
//                         {targets: 0, name: 'function_name', sWidth : '80%'},
// //                            {targets: 1, name: 'is_admin', className :'text-center'
// //                                , render: function (data, type, row, meta) {
// //
// //                                    if (data == "1") {
// //                                        return '<i class="fa fa-check text-primary"></i>';
// //                                    } else {
// //                                        return '<i class="fa fa-close text-danger"></i>';
// //                                    }
// //
// //                                }
// //                            },
// //                            {targets: 1, name: 'default_user_lock', className :'text-center', sWidth : '250px'
// //                                , render: function (data, type, row, meta) {
// //
// //                                    if (data == "1") {
// //                                        return '<i class="fa fa-check text-primary"></i>';
// //                                    } else {
// //                                        return '<i class="fa fa-close text-danger"></i>';
// //                                    }
// //
// //                                }
// //                            }
//                         {targets: 1, name: 'default_landing', className :'text-right', sWidth : '20%' }
//                     ],
//         });
        
//         dtTable.on('draw', function(){
//             $('div#dtTable_length select').select2();
//             $('div#dtTable_filter input[type=search]').removeClass('input-sm');
//         });
    
    $('#dtTable tbody').on('click', 'td a.edit', function (e) {
        var node = $(this).parents('tr')[0];
        
        $('tr.active').removeClass('active');
        $(node).addClass("active");
        
        var function_id = $(node.cells[2]).text();
        var function_name = $(node.cells[0]).text();
        var usergroup = $('#user_group_id').val();
        
        
        $('.modal-title').text('Fitur ' +function_name);
        $('#modal_fitur').modal('show');
        
        $('#content-feature').html('<center><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i></center>');
        $('#function_id').val(function_id);
        showFeatureList(function_id, function_name, usergroup); 
        
        
//        $('html, body').animate({
//            scrollTop: $("#form_user_group").offset().top
//        }, 1000);
    });
    
    $('#content-feature').css({'height': '410px'});
    
    $('#form_user_group').validate({
        rules: {
            user_group_description: {
                required: true
            }
            , default_landing: {
                required: true
            }
        },
        messages: {
            user_group_description: {
                required: "Nama Grup harus diisi"
            }
            ,default_landing: {
                required: "Halaman awal harus dipilih"
            }
        }
        , errorPlacement: function (error, element) {
            if(element[0].name != 'default_landing'){
                error.insertAfter(element);
            }else{
                var _parent = element.parent();
                console.log($(_parent).last());
                error.insertAfter(element.parent());
                // $(_parent).append(error);
            }
        }
        , 
        highlight: function (element) {
            $(element).closest('.item').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            $(element).closest('.item').removeClass('has-error');
        }

    });
    
    
    $('#default_landing').select2().on('change', function(){
        $('#form_user_group').valid();
    });
    $('#company_id').select2({
        disabled: true
      }).on('change', function(){
        $('#form_user_group').valid();
    });
    $('#user_group_description').on('change', function(){
        $('#form_user_group').valid();
    });
    
    
    $('.tree-2').treegrid({
        expanderExpandedClass: 'glyphicon glyphicon-minus',
        expanderCollapsedClass: 'glyphicon glyphicon-plus'
    });
    //$('.tree-2').treegrid('collapseAll');
    
    $('.function_checkbox').on('change', function(){
        var cbId = this.id;
        if ( $('#'+cbId+':checked').length == 1 ){
            $('.func_' + cbId).prop('checked', true);
        }else{
            $('.func_' + cbId).prop('checked', false);
        }
    });

    setCompany(dataCompany);

    $('#p_child_data_access_type').select2().on('change', function(){
        setContent();
    });
    $('#p_child_company_id').select2().on('change', function(){
        setContent();
    });
    
    $('.dataAccessCompany').on('change', function(){
        var cbId = this.id;
        var id = cbId.replace('comp_', '');
        if ( $('#'+cbId+':checked').length == 1 ){
            dataAccessCompany.push(id);
        }else{
            const index = dataAccessCompany.indexOf(id);
            if (index > -1) { // only splice array when item is found
                dataAccessCompany.splice(index, 1); // 2nd parameter means remove one item only
            }
        }
        setDataAccessType();
    });

    setWorkUnit(dataCompany);
    setPosition(dataCompany);
    setRole(dataCompany);
    // setCompany(dataCompany);
    setDataAccessType();
});

function setCompany(dataCompany){
    htmlCompanySection = '';
    $.each(dataCompany, function(index, value) {
        var checked = '';
        if(dataAccessCompany.includes(value.company_id)){
            checked = 'checked';
        }
        if(value.company_id == $("#company_id").val()){
            checked = 'checked onclick="return false;"';
        }
        htmlCompanySection += `<div class="col-md-3">`;
        htmlCompanySection += `<div class="checkbox checkbox-custom m-t-0 m-b-0">`;
        htmlCompanySection += `<input name="comp_${value.company_id}" class="dataAccessCompany" id="comp_${value.company_id}" type="checkbox" ${checked}>`;
        htmlCompanySection += `<label for="comp_${value.company_id}"><div style="padding-top:2px!important"><span>${value.company_name}</span></div></label>`;
        htmlCompanySection += `</div>`;
        htmlCompanySection += `</div>`;
    }); 
    $("#companySection").html(htmlCompanySection);
}

function setCompanyOpt(dataCompany){
    htmlCompanySection = '<option value="-">Semua Perusahaaan</option>';
    var val_selected = '-';
    $.each(dataCompany, function(index, value) {
        if(dataAccessCompany.includes(value.company_id)){
            if($("#p_child_company_id").val() == value.company_id){
                val_selected = value.company_id;
            }
            htmlCompanySection += `<option value="${value.company_id}">${value.company_name}</option>`;
        }
    }); 
    $("#p_child_company_id").html(htmlCompanySection);
    $("#p_child_company_id").select2("val", val_selected);
}

function setDataAccessType(){
    setCompanyOpt(dataCompany);
    setContent();
}
function setContent(){
    $("#divAreaSection").addClass("hidden");
    $("#divPositionSection").addClass("hidden");
    $("#divRoleSection").addClass("hidden");
    if($("#p_child_data_access_type").val() == 'area'){
        $("#divAreaSection").removeClass("hidden");
        setWorkUnit(dataCompany);
    }else if($("#p_child_data_access_type").val() == 'position'){
        $("#divPositionSection").removeClass("hidden");
        setPosition(dataCompany);
    }else if($("#p_child_data_access_type").val() == 'role'){
        $("#divRoleSection").removeClass("hidden");
        setRole(dataCompany);
    }
}
function setWorkUnit(dataCompany){
    htmlWorkUnitSection = '';
    var hidden = ($("#p_child_company_id").val() == '-' || $("#p_child_company_id").val() == ''  || $("#p_child_company_id").val() == null) ? '' : 'hidden';
    $.each(dataCompany, function(index, value) {
        if(dataAccessCompany.includes(value.company_id)){
            htmlWorkUnitSection += `<tr ${($("#p_child_company_id").val() == value.company_id) ? '' : hidden}>`;
            htmlWorkUnitSection += `<td>`;
            
            // htmlWorkUnitSection += `${value.company_name}`;
            
            htmlWorkUnitSection += `<div class="checkbox checkbox-custom m-t-0 m-b-0">`;
            htmlWorkUnitSection += `<input name="cid_area_${value.company_id}" class="cidArea" id="cid_area_${value.company_id}" type="checkbox" >`;
            htmlWorkUnitSection += `<label for="cid_area_${value.company_id}"><div style="padding-top:2px!important">${value.company_name}</div></label>`;
            htmlWorkUnitSection += `</div>`;

            htmlWorkUnitSection += `</td>`;
            htmlWorkUnitSection += `<td>`;

            htmlWorkUnitSection += `<div class="row">`;

            $.each(value.work_unit, function(index, valueWorkUnit) {
                var checked = '';
                if(dataAccessArea.includes(valueWorkUnit.work_unit_id)){
                    checked = 'checked';
                }
                htmlWorkUnitSection += `<div class="col-md-4">`;
                htmlWorkUnitSection += `<div class="checkbox checkbox-custom m-t-0 m-b-0">`;
                htmlWorkUnitSection += `<input name="area_${valueWorkUnit.work_unit_id}" class="dataAccessArea cid_area_${value.company_id}" id="area_${valueWorkUnit.work_unit_id}" type="checkbox" ${checked}>`;
                htmlWorkUnitSection += `<label for="area_${valueWorkUnit.work_unit_id}"><div style="padding-top:2px!important"><span>${valueWorkUnit.name}</span></div></label>`;
                htmlWorkUnitSection += `</div>`;
                htmlWorkUnitSection += `</div>`;
            });

            htmlWorkUnitSection += `</div>`;

            htmlWorkUnitSection += `</td>`;
            htmlWorkUnitSection += `</tr>`;
        }
    }); 
    htmlWorkUnitSection = (htmlWorkUnitSection == '') ? '<tr class="text-center"><td colspan="2">Tidak Ada Data</td></tr>' : htmlWorkUnitSection;
    $("#workUnitSection").html(htmlWorkUnitSection);
    
    $('.dataAccessArea').on('change', function(){
        var cbId = this.id;
        var id = cbId.replace('area_', '');
        if ( $('#'+cbId+':checked').length == 1 ){
            dataAccessArea.push(id);
        }else{
            const index = dataAccessArea.indexOf(id);
            if (index > -1) { // only splice array when item is found
                dataAccessArea.splice(index, 1); // 2nd parameter means remove one item only
            }
        }
        setDataAccessType();
    });
    
    $(".cidArea").on('change', function(){
        var cbId = this.id;

        $('.' + cbId).each(function() {
            const index = dataAccessArea.indexOf(this.id.replace('area_', ''));
            if (index > -1) { // only splice array when item is found
                dataAccessArea.splice(index, 1); // 2nd parameter means remove one item only
            }
        });
        if ( $('#'+cbId+':checked').length == 1 ){
            $('.' + cbId).prop('checked', true);
            $('.' + cbId).each(function() {
                dataAccessArea.push(this.id.replace('area_', ''));
            });
        }else{
            $('.' + cbId).prop('checked', false);
        }
    });
}
function setPosition(dataCompany){
    htmlPositionSection = '';
    var hidden = ($("#p_child_company_id").val() == '-' || $("#p_child_company_id").val() == ''  || $("#p_child_company_id").val() == null) ? '' : 'hidden';
    $.each(dataCompany, function(index, value) {
        if(dataAccessCompany.includes(value.company_id)){
            htmlPositionSection += `<tr ${($("#p_child_company_id").val() == value.company_id) ? '' : hidden}>`;
            htmlPositionSection += `<td>`;
            
            // htmlPositionSection += `${value.company_name}`;

            htmlPositionSection += `<div class="checkbox checkbox-custom m-t-0 m-b-0">`;
            htmlPositionSection += `<input name="cid_position_${value.company_id}" class="cidPosition" id="cid_position_${value.company_id}" type="checkbox" >`;
            htmlPositionSection += `<label for="cid_position_${value.company_id}"><div style="padding-top:2px!important">${value.company_name}</div></label>`;
            htmlPositionSection += `</div>`;

            htmlPositionSection += `</td>`;
            htmlPositionSection += `<td>`;
    
            htmlPositionSection += `<div class="row">`;
    
            $.each(value.position, function(index, position) {
                var checked = '';
                if(dataAccessPosition.includes(position.position_id)){
                    checked = 'checked';
                }
                htmlPositionSection += `<div class="col-md-4">`;
                htmlPositionSection += `<div class="checkbox checkbox-custom m-t-0 m-b-0">`;
                htmlPositionSection += `<input name="position_${position.position_id}" class="dataAccessPosition cid_position_${value.company_id}" id="position_${position.position_id}" type="checkbox" ${checked}>`;
                htmlPositionSection += `<label for="position_${position.position_id}"><div style="padding-top:2px!important"><span>${position.position_name}</span></div></label>`;
                htmlPositionSection += `</div>`;
                htmlPositionSection += `</div>`;
            });
    
            htmlPositionSection += `</div>`;
    
            htmlPositionSection += `</td>`;
            htmlPositionSection += `</tr>`;
        }
    }); 
    htmlPositionSection = (htmlPositionSection == '') ? '<tr class="text-center"><td colspan="2">Tidak Ada Data</td></tr>' : htmlPositionSection;
    $("#positionSection").html(htmlPositionSection);
    
    $('.dataAccessPosition').on('change', function(){
        var cbId = this.id;
        var id = cbId.replace('position_', '');
        if ( $('#'+cbId+':checked').length == 1 ){
            dataAccessPosition.push(id);
        }else{
            const index = dataAccessPosition.indexOf(id);
            if (index > -1) { // only splice array when item is found
                dataAccessPosition.splice(index, 1); // 2nd parameter means remove one item only
            }
        }
        setDataAccessType();
    });
    $(".cidPosition").on('change', function(){
        var cbId = this.id;

        $('.' + cbId).each(function() {
            const index = dataAccessPosition.indexOf(this.id.replace('position_', ''));
            if (index > -1) { // only splice array when item is found
                dataAccessPosition.splice(index, 1); // 2nd parameter means remove one item only
            }
        });
        if ( $('#'+cbId+':checked').length == 1 ){
            $('.' + cbId).prop('checked', true);
            $('.' + cbId).each(function() {
                dataAccessPosition.push(this.id.replace('position_', ''));
            });
        }else{
            $('.' + cbId).prop('checked', false);
        }
    });
}
function setRole(dataCompany){
    htmlRoleSection = '';
    var hidden = ($("#p_child_company_id").val() == '-' || $("#p_child_company_id").val() == ''  || $("#p_child_company_id").val() == null) ? '' : 'hidden';
    $.each(dataCompany, function(index, value) {
        if(dataAccessCompany.includes(value.company_id)){
            htmlRoleSection += `<tr ${($("#p_child_company_id").val() == value.company_id) ? '' : hidden}>`;
            htmlRoleSection += `<td>`;

            // htmlRoleSection += `${value.company_name}`;
            
            htmlRoleSection += `<div class="checkbox checkbox-custom m-t-0 m-b-0">`;
            htmlRoleSection += `<input name="cid_role_${value.company_id}" class="cidRole" id="cid_role_${value.company_id}" type="checkbox" >`;
            htmlRoleSection += `<label for="cid_role_${value.company_id}"><div style="padding-top:2px!important">${value.company_name}</div></label>`;
            htmlRoleSection += `</div>`;
            
            htmlRoleSection += `</td>`;
            htmlRoleSection += `<td>`;

            htmlRoleSection += `<div class="row">`;

            $.each(value.role, function(index, role) {
                var checked = '';
                if(dataAccessRole.includes(role.role_id)){
                    checked = 'checked';
                }
                htmlRoleSection += `<div class="col-md-4">`;
                htmlRoleSection += `<div class="checkbox checkbox-custom m-t-0 m-b-0">`;
                htmlRoleSection += `<input name="role_${role.role_id}" class="dataAccessRole cid_role_${value.company_id}" id="role_${role.role_id}" type="checkbox" ${checked}>`;
                htmlRoleSection += `<label for="role_${role.role_id}"><div style="padding-top:2px!important"><span>${role.role_name}</span></div></label>`;
                htmlRoleSection += `</div>`;
                htmlRoleSection += `</div>`;
            });

            htmlRoleSection += `</div>`;

            htmlRoleSection += `</td>`;
            htmlRoleSection += `</tr>`;
        }
    }); 
    htmlRoleSection = (htmlRoleSection == '') ? '<tr class="text-center"><td colspan="2">Tidak Ada Data</td></tr>' : htmlRoleSection;
    $("#roleSection").html(htmlRoleSection);
    
    $('.dataAccessRole').on('change', function(){
        var cbId = this.id;
        var id = cbId.replace('role_', '');
        if ( $('#'+cbId+':checked').length == 1 ){
            dataAccessRole.push(id);
        }else{
            const index = dataAccessRole.indexOf(id);
            if (index > -1) { // only splice array when item is found
                dataAccessRole.splice(index, 1); // 2nd parameter means remove one item only
            }
        }
        setDataAccessType();
        console.log(dataAccessRole)
    });
    $(".cidRole").on('change', function(){
        var cbId = this.id;

        $('.' + cbId).each(function() {
            const index = dataAccessRole.indexOf(this.id.replace('role_', ''));
            if (index > -1) { // only splice array when item is found
                dataAccessRole.splice(index, 1); // 2nd parameter means remove one item only
            }
        });
        if ( $('#'+cbId+':checked').length == 1 ){
            $('.' + cbId).prop('checked', true);
            $('.' + cbId).each(function() {
                dataAccessRole.push(this.id.replace('role_', ''));
            });
        }else{
            $('.' + cbId).prop('checked', false);
        }
        console.log(dataAccessRole)
    });
}
function setFeature(function_id, function_name) {
    
    $('.modal-title').text('Fitur ' +function_name);
    $('#modal_fitur').modal('show');

    var usergroup = $('#user_group_id').val();
    
    $('#content-feature').html('<center><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i></center>');
    $('#function_id').val(function_id);
    showFeatureList(function_id, function_name, usergroup); 
}

function submit_user_group()
{
    if ($('#form_user_group').valid()) {

        var items = new Array();
        //$('#is_admin').val($('input[name=_is_admin]:checked').length);
        //$('#default_user_lock').val($('input[name=_default_user_lock]:checked').length);
        $('#items').val(JSON.stringify(items));
        $('#btn_update').html('Mohon Tunggu...');
        $('.sbmt_btn').attr('disabled', 'disabled');

        var feature_id = [];
        $('.feature_checkbox').each(function() {
            var cbId = this.id;
            if ( $('#'+cbId+':checked').length == 1 ){
                feature_id.push(cbId);
            }
        });
        $('#feature_list').val(feature_id.join(','));
        
        $('#related_area_flg').val($('#v_related_area_flg:checked').length);
        $('#related_position_flg').val($('#v_related_position_flg:checked').length);
        $('#related_role_flg').val($('#v_related_role_flg:checked').length);
        $('#subordinate_flg').val($('#v_subordinate_flg:checked').length);

        var data_access_company_list = [];
        $('.dataAccessCompany').each(function() {
            var cbId = this.id;
            if ( $('#'+cbId+':checked').length == 1 ){
                var id = cbId.replace('comp_', '');
                data_access_company_list.push(id);
            }
        });
        $('#data_access_company_list').val(data_access_company_list.join(','));

        var data_access_area_list = [];
        $('.dataAccessArea').each(function() {
            var cbId = this.id;
            if ( $('#'+cbId+':checked').length == 1 ){
                var id = cbId.replace('area_', '');
                data_access_area_list.push(id);
            }
        });
        $('#data_access_area_list').val(data_access_area_list.join(','));

        var data_access_position_list = [];
        $('.dataAccessPosition').each(function() {
            var cbId = this.id;
            if ( $('#'+cbId+':checked').length == 1 ){
                var id = cbId.replace('position_', '');
                data_access_position_list.push(id);
            }
        });
        $('#data_access_position_list').val(data_access_position_list.join(','));

        var data_access_role_list = [];
        $('.dataAccessRole').each(function() {
            var cbId = this.id;
            if ( $('#'+cbId+':checked').length == 1 ){
                var id = cbId.replace('role_', '');
                data_access_role_list.push(id);
            }
        });
        $('#data_access_role_list').val(data_access_role_list.join(','));

        $('#form_user_group').submit();
    }
}


function showFeatureList(function_id, name, usergroup){
    
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: SITE_URL + 'usergroup/getDataFeature',
        data: {
            function_id : function_id
            , usergroup : usergroup
            , type : 'edit'
            , cpms_token: $('#cpms_token').val()
        },
        success : function(data){
            var html = generateTableFeature(data, name);
            $('#content-feature').html(html);
            $('#chkAll').on('click', function(){
                $('.chkFeature').prop('checked', this.checked);
            });
            $('.chkFeature').on('click', function(){
                var ln = $('.chkFeature').length;
                var lnChecked = $('.chkFeature:checked').length;
                if (ln == lnChecked){
                    $('#chkAll').prop('checked', true);
                }else{
                    $('#chkAll').prop('checked', false);
                }
                
            });
        }
    });
    
}

function generateTableFeature(data, name){
    
    //var html = '<h5> Fitur ' +name+'</h5>';
    var html = "";
        html += ' <p>Beri tanda centang &#10004; untuk Hak Akses</p><div class="table-responsive">'+
            
            '<table class="table table-fitur m-0 table-colored table-custom table-bordered">'+
                '<thead>'+
                    '<tr>'+
                        '<th width="40px"><div class="checkbox checkbox-default"><input id="chkAll" type="checkbox"> <label for="chkAll"></label></div></th>'+
                        '<th class="text-center">Tipe Akses</th>'+
                        '<th class="text-center">Keterangan</th>'+
                        '<th class="text-center last-col">feature_id</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>';
    if(data.length > 0){
        
        $.each(data, function( i, value ) {
            var row = data[i];
            html += '<tr>'+
                    '<td class="text-center">'+row[0]+'</td>'+
                    '<td class="text-left">'+row[1]+'</td>'+
                    '<td class="text-left">'+row[2]+'</td>'+
                    '<td class="text-right last-col">'+row[3]+'</td>'+
                '</tr>';
        });
        
        html += '</tbody></table></div>';
//        html+=  '<div class="row">'+
//                    '<div class="col-md-12">'+
//                        '<button type="button" class="btn btn-teal btn-bordered waves-light waves-effect w-md m-b-5 sbmt_btn" id="btn_submit_fitur" onclick="submit_fitur()">Simpan Fitur</button>'+
//                    '</div>'+
//                '</div>';
        $('#btn_submit_fitur').show();
    }else{
        html += '<tr> <td colspan="3" class="text-center"> Tidak ada data </td> </tr>';
        html += '</tbody></table></div>';
        $('#btn_submit_fitur').hide();
    }
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

    $('#usergroup_action').val('delete');
	$('#form_user_group').submit();

}
// function submit_fitur(){

//     var feature_id = [];
//     $('.table-fitur tbody tr').each(function(i,element){
//         var node = element;
//         var el1 = $(node.cells[0]).html();
//         var cbId = $(el1).find('input[type="checkbox"]').attr('id');
//         if ( $('#'+cbId+':checked').length == 1 ){
//             feature_id.push($(node.cells[3]).text());
//         }
//     });
    
//     $('#items').val(feature_id.join(','));
//     $('#btn_submit_fitur').html('Mohon Tunggu...');
//     $('#btn_submit_fitur').attr('disabled', 'disabled');
//     $('#fitur_function').submit();
    
// }

$('body').on('click', '#checkAllUserGroup', function () {
    if ($(this).is(':checked')) {
        $('.apply_function').prop('checked', true);
        $('.apply_data_access').prop('checked', true);
        $('#checkAllFunction').prop('checked', true);
        $('#checkAllDataAccess').prop('checked', true);
    } else {
        $('.apply_function').prop('checked', false);
        $('.apply_data_access').prop('checked', false);
        $('#checkAllFunction').prop('checked', false);
        $('#checkAllDataAccess').prop('checked', false);
    }
});

$('body').on('click', '#checkAllFunction', function () {
    if ($(this).is(':checked')) {
        $('.apply_function').prop('checked', true);
    } else {
        $('.apply_function').prop('checked', false);
    }

    // checkAll is check if is apply function is checked and apply data access is checked
    if($('.apply_function:checked').length == $('.apply_function').length && $('.apply_data_access:checked').length == $('.apply_data_access').length){
        $('#checkAllUserGroup').prop('checked', true);
    }else{
        $('#checkAllUserGroup').prop('checked', false);
    }
})

$('body').on('click', '#checkAllDataAccess', function () {
    if ($(this).is(':checked')) {
        $('.apply_data_access').prop('checked', true);
    } else {
        $('.apply_data_access').prop('checked', false);
    }

    // checkAll is check if is apply function is checked and apply data access is checked
    if($('.apply_function:checked').length == $('.apply_function').length && $('.apply_data_access:checked').length == $('.apply_data_access').length){
        $('#checkAllUserGroup').prop('checked', true);
    }else{
        $('#checkAllUserGroup').prop('checked', false);
    }
})

$('body').on('click', '.apply_function', function () {
    if($('.apply_function:checked').length == $('.apply_function').length){
        $('#checkAllFunction').prop('checked', true);
    }else{
        $('#checkAllFunction').prop('checked', false);
    }

    // checkAll is check if is apply function is checked and apply data access is checked
    if($('.apply_function:checked').length == $('.apply_function').length && $('.apply_data_access:checked').length == $('.apply_data_access').length){
        $('#checkAllUserGroup').prop('checked', true);
    }else{
        $('#checkAllUserGroup').prop('checked', false);
    }
});

$('body').on('click', '.apply_data_access', function () {
    if($('.apply_data_access:checked').length == $('.apply_data_access').length){
        $('#checkAllDataAccess').prop('checked', true);
    }else{
        $('#checkAllDataAccess').prop('checked', false);
    }

    // checkAll is check if is apply function is checked and apply data access is checked
    if($('.apply_function:checked').length == $('.apply_function').length && $('.apply_data_access:checked').length == $('.apply_data_access').length){
        $('#checkAllUserGroup').prop('checked', true);
    }else{
        $('#checkAllUserGroup').prop('checked', false);
    }
});


