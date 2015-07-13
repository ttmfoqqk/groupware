$(document).ready(function() {
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
			subject: {
				required: true,
				minlength: 2,
	  			maxlength: 200
			},
			contents: {
				required: true,
				minlength: 2
			}
		},
		messages: {
			subject: {
				required: "제목을 입력해주세요.",
				minlength: "제목은 2~200자 입니다.",
				maxlength: "제목은 2~200자 입니다."
			},
			contents: {
				required: "내용을 입력해주세요.",
				minlength: "내용은 2자 이상 입니다."
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