//------------- tables-data.js -------------//
$(document).ready(function() {

	$('#contents_member_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#validate').submit();
				}		  		
		    }
		});
	});

	//------------- Check all Checkboxes -------------//
	$('#checkAllExample').checkAll({
		masterCheckbox: '.check-all',
		otherCheckboxes: '.check'
	})
	//------------- Form validation -------------//
	$("#validate").validate({
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
			user_id: {
				required: true,
				minlength: 5,
	  			maxlength: 20
			},
			user_name: {
				required: true,
				minlength: 2,
	  			maxlength: 20
			},
			user_password: {
				required: true,
				minlength: 5,
				maxlength: 20
			},
			new_user_password: {
				minlength: 5,
				maxlength: 20
			},
			user_email: {
				required: true,
				email: true
			},
			user_organization :{
				required: true
			}
		},
		messages: {
			user_id: {
				required: "아이디를 입력해주세요.",
				minlength: "아이디는 5~20자 입니다.",
				maxlength: "아이디는 5~20자 입니다."
			},
			user_name: {
				required: "이름을 입력해주세요.",
				minlength: "이름은 2~20자 입니다.",
				maxlength: "이름은 2~20자 입니다."
			},
			user_password: {
				required: "비밀번호를 입력해주세요.",
				minlength: "비밀번호는 5~20자 입니다.",
				maxlength: "비밀번호는 5~20자 입니다."
			},
			new_user_password: {
				minlength: "비밀번호는 5~20자 입니다.",
				maxlength: "비밀번호는 5~20자 입니다."
			},
			user_email: {
				required: "이메일을 입력해주세요",
				email: "유효한 이메일 주소를 입력해주세요."
			},
			user_organization :{
				required: "부서를 선택해주세요",
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