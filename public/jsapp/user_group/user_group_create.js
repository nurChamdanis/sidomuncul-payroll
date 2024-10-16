$(document).ready(function () {

    $('#form_user_group').validate({
        rules: {
            user_group_description: {
                required: true
            }
            , company_id: {
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
            ,company_id: {
                required: "Perusahaan harus dipilih"
            }
            ,default_landing: {
                required: "Halaman awal harus dipilih"
            }
        }
        , errorPlacement: function (error, element) {
            if(element[0].name != 'default_landing' || element[0].name != 'company_id'){
                // error.insertAfter(element);
                var _parent = element.parent();
                //console.log($(_parent).last());
                //error.insertAfter(element.parent());
                $(_parent).append(error);
            }else{
                var _parent = element.parent();
                //console.log($(_parent).last());
                //error.insertAfter(element.parent());
                $(_parent).append(error);
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
    
    $('#company_id').select2().on('change', function(){
        $('#form_user_group').valid();
    });
    $('#user_group_description').on('change', function(){
        $('#form_user_group').valid();
    });
});


function submit_user_group()
{
    if ($('#form_user_group').valid()) {
        //$('#is_admin').val($('input[name=_is_admin]:checked').length);
        //$('#default_user_lock').val($('input[name=_default_user_lock]:checked').length);
        //$('#items').val(JSON.stringify(items));
        $('#btn_submit').html('Mohon Tunggu...');
        $('.sbmt_btn').attr('disabled', 'disabled');

        $('#form_user_group').submit();
    }
}