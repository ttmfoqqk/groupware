//------------- login.js -------------//
$(document).ready(function() {

	//validate login form 
	$("#login-form").validate({
		ignore: null,
		ignore: 'input[type="hidden"]',
		errorPlacement: function( error, element ) {
			var place = element.closest('.input-group');
			if (!place.get(0)) {
				place = element;
			}
			if (place.get(0).type === 'checkbox') {
				place = element.parent();
			}
			if (error.text() !== '') {
				place.after(error);
			}
		},
		errorClass: 'help-block',
		rules: {
			userid: {
				required : true,
				minlength: 5,
				maxlength: 20,
			},
			password: {
				required : true,
				minlength: 5,
				maxlength: 20,
			}
		},
		messages: {
			password: {
				required: "비밀번호를 입력해주세요.",
				minlength: "비밀번호는 5~20자 입니다.",
				maxlength: "비밀번호는 5~20자 입니다."
			},
			userid: {
				required: "아이디를 입력해주세요.",
				minlength: "아이디는 5~20자 입니다.",
				maxlength: "아이디는 5~20자 입니다."
			}
		},
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			label.remove();
		}
	});

});