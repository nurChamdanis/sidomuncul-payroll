var m_strUpperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
var m_strLowerCase = "abcdefghijklmnopqrstuvwxyz";
var m_strNumber = "0123456789";
var m_strCharacters = "!@#$%^&*?_~"
var is_loading = false;
let assignedEmployee = [];
$(document).ready(function () {
    $('#password').on('keyup', function(e){
        checkPassword($("#password").val());
    });
    

	$("#btn_password").on('click', function(){
		if($("#password").attr('type') == 'password'){
			$("#btn_password").html('<i class="fa">&#xf06e;</i>');
			$("#password").attr('type','text');
		}else{
			$("#btn_password").html('<i class="fa">&#xf070;</i>');
			$("#password").attr('type','password');
		}
	});
	$("#btn_confirm_password").on('click', function(){
		if($("#confirm_password").attr('type') == 'password'){
			$("#btn_confirm_password").html('<i class="fa">&#xf06e;</i>');
			$("#confirm_password").attr('type','text');
		}else{
			$("#btn_confirm_password").html('<i class="fa">&#xf070;</i>');
			$("#confirm_password").attr('type','password');
		}
	});
    initEmployeeAssigned();
    $('#form_user').validate({
        rules: {
            password: {
                // required: true,
                minlength: 8
            },
            confirm_password: {
                // required: true,
                minlength: 8,
                equalTo: "#password"
            }
        },
        messages: {
            full_name: {
                required: "Nama Lengkap harus diisi"
            },
            user_group_id: {
                required: "Grup Pengguna harus diisi"
            },
            is_active: {
                required: "Status Aktif harus diisi"
            }
            ,password: {
                minlength: "Kata Sandi harus lebih dari 8 karakter"
            },
            confirm_password: {
                minlength: "Kata Sandi harus lebih dari 8 karakter",
                equalTo: "Kata sandi tidak sama"
            }
        }, 
		errorPlacement: function (error, element) {
            if(element[0].name == 'password' || element[0].name == 'confirm_password'){
                error.insertAfter(element.parent());
            }else{
                if(element[0].input != undefined){
                    error.insertAfter(element);
                }else{
                    var _parent = element.parent();
                    $(_parent).append(error);
                }
            }
        }, 
        highlight: function (element) {
            $(element).closest('.item').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            $(element).closest('.item').removeClass('has-error');
        }

    });
    // $("input[id*=password]").rules("add", "required");
    
    $('#user_group_id, #is_active').select2();

     $('#company_id').select2()

    $('#company_id').on('change', function() {
        $.get( SITE_URL + 'user/get_user_groups_by_company_id',
        {
            company_id: $('#company_id').val()
        },
        function(data, status){
            $('#user_group_id').empty()
            $('#user_group_id').select2()

            // $row->user_group_id; ?>"><?php echo $row->user_group_description;
            // alert(data)
            var res = JSON.parse(data)
            res = res.items

            $.each(res, function(index, item) {
              var newOption = new Option(item.user_group_description, item.user_group_id, false, false);
              $('#user_group_id').append(newOption);
            });
        });

       $.get( SITE_URL + 'user/get_employee_by_company_id',
        {
            company_id: $('#company_id').val()
        },
        function(data, status){
            $('#employee_id').empty()
            $('#employee_id').select2()
            var res = JSON.parse(data)
            res = res.items

            $.each(res, function(index, item) {
              var newOption = new Option(item.employee_name, item.employee_id, false, false);
              $('#employee_id').append(newOption);
            });
        });
    });
    
});


function uniqueValue(){
    // make unique value between 0 - 30000000
    return Math.floor(Math.random() * 30000000);
}
select2options({
    id: '.approver',
    url: SITE_URL + 'user/getEmployeeByAccess',
    placeholder: 'Pilih Pegawai',
    searchText: 'Mencari',
    payload: {exclude_employee_id: function (e, el){ return $('.approver').map(function (_, el) { return ($(el).val() != "") ? $(el).val() : null; }).get().toString()}}
})

$('body').on('click', '#add_delegate',function(){
    $('#empty-delegate').hide();
    const body = `
        <tr>
            <td>
                <select name="employee_delegation[]" class="select2 approver form-control" data-unique="${uniqueValue()}" data-placeholder="Pilih Pegawai">
                </select>
            </td>
            <td class="col-delete text-center" style="display: flex; justify-content: center; align-content: center; align-items: center">
                <a href="javascript:void('')" class="remove_row"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
    `;
    $('tbody').append(body)
    select2options({
        id: '.approver',
        url: SITE_URL + 'user/getEmployeeByAccess',
        placeholder: 'Pilih Pegawai',
        searchText: 'Mencari',
        payload: {exclude_employee_id: function (e, el){ return $('.approver').map(function (_, el) { return ($(el).val() != "") ? $(el).val() : null; }).get().toString()}}
    })
})

$('body').on('change', '.approver', function(){
    const value = $(this).val();
    const unique = $(this).data('unique');
    const isValid = addOrUpdateAssignedEmployee({unique: unique , employee_id: value});
    if (!isValid) {
        $(this).val('').trigger('change');
    }
});

$('body').on('click', '.remove_row', function () {
    const value = $(this).closest('tr').find('.approver').val();
    const unique = $(this).closest('tr').find('.approver').data('unique');
    deleteAssignedEmployee({unique: unique , employee_id: value});
    $(this).closest('tr').remove();
    if($('tbody tr').length === 1){
        $('#empty-delegate').show();
    }
});

function addOrUpdateAssignedEmployee(data) {
    const index = assignedEmployee.findIndex(item => item.unique === data.unique);

    if (index !== -1) {
        assignedEmployee[index].employee_id = data.employee_id;
        return true;
    } else if (!isEmailExists(data)) { // Check if email doesn't already exist
        assignedEmployee.push(data);
        return true;
    } else {
        toastr.error('Pegawai sudah dipilih');
        return false;
    }
}

const deleteAssignedEmployee = (data) => {
    const index = assignedEmployee.findIndex(item => item.unique === data.unique);

    if (index !== -1) {
        assignedEmployee.splice(index, 1);
    }
}

function isEmailExists(data) {
    return assignedEmployee.some(item => item.employee_id === data.employee_id);
}

$('body').on('change', '#checkAll', function(){
    if($(this).is(':checked')){
        $('.function_id_checkbox').each(function(){
            $(this).prop('checked', true);
        })
    }else{
        $('.function_id_checkbox').each(function(){
            $(this).prop('checked', false);
        })
    }
})

$('body').on('change', '.function_id_checkbox', function(){
    if($(this).is(':checked')){
        if($('.function_id_checkbox:checked').length == $('.function_id_checkbox').length){
            $('#checkAll').prop('checked', true);
        }
    }else{
        $('#checkAll').prop('checked', false);
    }
});

function initEmployeeAssigned(){
    $('tbody tr').each(function(){
        const value = $(this).find('.approver').val();
        const unique = $(this).find('.approver').data('unique');
        if (value !== undefined && unique !== undefined){
            addOrUpdateAssignedEmployee({unique: unique , employee_id: value});
        }
    });
}

function submit_user()
{
    if ($('#form_user').valid()) {
        // $('#btn_update').html('Mohon Tunggu...');
        // $('.sbmt_btn').attr('disabled', 'disabled');
        if(assignedEmployee.length > 0) {
            // get checbkox value
            var function_id = [];
            $('.function_id_checkbox:checked').each(function () {
                function_id.push($(this).val());
            });
            if(function_id.length <= 0){
                toastr.error('Pilih hak akses');
                $('#btn_update').html('Ubah Pengguna');
                $('.sbmt_btn').removeAttr('disabled');
                return ;
            }
        }
        
        $('#form_user').submit();
    }
}

function checkPassword(strPassword)
{
    // Reset combination count
    var nScore = 0;
    var html = "";

    // Password length
    // -- Less than 8 characters
    if (strPassword.length < 8)
    {
        html = `<span class="badge badge-danger"><i class="fa fa-times"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Minimal 8 Karakter</span> `;
        $("#password_char").html(html);
        nScore += 0;
    } else {
        html = `<span class="badge badge-success"><i class="fa fa-check"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Minimal 8 Karakter</span> `;
        $("#password_char").html(html);
        nScore += 33;
    }

    // Letters
    var nUpperCount = countContain(strPassword, m_strUpperCase);
    var nLowerCount = countContain(strPassword, m_strLowerCase);

    if (nUpperCount != 0 && nLowerCount != 0) 
    { 
        html = `<span class="badge badge-success"><i class="fa fa-check"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Kombinasi Huruf Besar Dan Kecil</span> `;
        $("#password_uppercase_char").html(html);
        nScore += 33;
    } else {
        html = `<span class="badge badge-danger"><i class="fa fa-times"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Kombinasi Huruf Besar Dan Kecil</span> `;
        $("#password_uppercase_char").html(html);
        nScore += 0; 
    }

    // Characters
    var nCharacterCount = countContain(strPassword, m_strCharacters);
    // -- 1 character
    if (nCharacterCount != 0)
    {
        html = `<span class="badge badge-success"><i class="fa fa-check"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Kombinasi Spesial Karakter</span> `;
        $("#password_special_char").html(html);
        nScore += 34;
    } else {
        html = `<span class="badge badge-danger"><i class="fa fa-times"></i></span><span class="badge badge-light" style="background-color: transparent; color:#333333;">Kombinasi Spesial Karakter</span> `;
        $("#password_special_char").html(html);
        nScore += 0; 
    }

    html = `<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="`+nScore+`" aria-valuemin="0" aria-valuemax="100" style="width: `+nScore+`%"></div>`

    $("#progress-bar").html(html);
    $("#total_score").val(nScore);

    if(nScore == 100) {
        $("#password_error").hide();
    } else {
        $("#password_error").show();
    }
    if(strPassword == ''){
        $("#div_pass_error").hide();
    }else{
        $("#div_pass_error").show();
    }
    return nScore;
}
function countContain(strPassword, strCheck)
{ 
    // Declare variables
    var nCount = 0;

    for (i = 0; i < strPassword.length; i++) 
    {
        if (strCheck.indexOf(strPassword.charAt(i)) > -1) 
        { 
                nCount++;
        } 
    } 

    return nCount; 
} 
