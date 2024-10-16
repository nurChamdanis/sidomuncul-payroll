$(document).ready(function(){
	$(".numeric").on("keydown", function(event) {
		// Allow: backspace, delete, tab, escape, and enter
		if (
			event.keyCode === 46 ||
			event.keyCode === 8 ||
			event.keyCode === 9 ||
			event.keyCode === 27 ||
			event.keyCode === 13 ||
			// Allow: Ctrl+A
			(event.keyCode === 65 && event.ctrlKey === true) ||
			// Allow: home, end, left, right, down, up
			(event.keyCode >= 35 && event.keyCode <= 40)
		) {
			// Let it happen, don't do anything
			return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (
				event.shiftKey ||
				(event.keyCode < 48 || event.keyCode > 57) &&
				(event.keyCode < 96 || event.keyCode > 105)
			) {
				event.preventDefault();
			}
		}
	});

	$('#form_login').validate({
		rules : {
		}
        , errorPlacement: function (error, element) {
            if(['user_password'].includes((element[0].name))){
                var _parent = element.parent();
                error.insertAfter($(_parent).last());
            }else{
				var _parent = element.parent();
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
	$("#form_login").submit(function (event) {
		if ($('#form_login').valid()) {
			$('#btn_submit').html(`${lang.Shared.please_wait} <i class="fa fa-spinner fa-pulse fa-fw"></i>`);
			$('#btn_submit').attr('disabled', 'disabled');
		}
	});
	$("#btn_user_password").on('click', function(){
		if($("#user_password").attr('type') == 'password'){
			$("#btn_user_password").html('<i class="fa">&#xf06e;</i>');
			$("#user_password").attr('type','text');
		}else{
			$("#btn_user_password").html('<i class="fa">&#xf070;</i>');
			$("#user_password").attr('type','password');
		}
	});
});