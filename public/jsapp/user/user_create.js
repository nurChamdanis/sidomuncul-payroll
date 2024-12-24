$(document).ready(function () {
    $('#form_user').validate({
        rules: {
            user_email: {
                email: true,
				remote: {
					url: SITE_URL + 'user/check_email',
					type: "post",
					data: {
						email: function () { return $('input[name=user_email]').val(); },
                        cpms_token: $('#cpms_token').val(),
					}
				}
            },
			user_password: {
				minlength: 8
			},
            re_user_password: {
                minlength: 8,
                equalTo: "#user_password"
            }
        },
        messages: {
            user_email: {
                required: "Email Pengguna harus diisi",
                email: "Email tidak valid",
				remote: "Email telah digunakan"
            },
            user_password: {
                required: "Password harus diisi"
            },
			re_user_password: {
                required: "Ketik ulang Password",
                equalTo: "Password tidak sama"
            },
            full_name: {
                required: "Nama Lengkap harus diisi"
            },
            user_group_id: {
                required: "Grup Pengguna harus diisi"
            },
            is_active: {
                required: "Status Aktif harus diisi"
            },
        }, 
		errorPlacement: function (error, element) {
			if(element[0].input != undefined){
                error.insertAfter(element);
            }else{
                var _parent = element.parent();
                $(_parent).append(error);
            }
        }, 
        highlight: function (element) {
            $(element).closest('.item').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            $(element).closest('.item').removeClass('has-error');
        }

    });
    
    $('#user_group_id, #is_active, #employee_id').select2();
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


function submit_user()
{
    if ($('#form_user').valid()) {
        $('#btn_submit').html('Mohon Tunggu...');
        $('.sbmt_btn').attr('disabled', 'disabled');

        $('#form_user').submit();
    }
}