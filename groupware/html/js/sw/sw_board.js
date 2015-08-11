//------------- tables-data.js -------------//
$(document).ready(function() {
	/*
	if( $('#contents').length > 0){
		var config = {
			initializedId: "",
			wrapper: "tx_trex_container",
			form: 'board_form_write_board',
			txIconPath: "/daumeditor/images/icon/editor/",
			txDecoPath: "/daumeditor/images/deco/contents/",
			events: {
				preventUnload: false
			},
			sidebar: {
				attachbox: {
					show: true
				}
			}
		};

		EditorCreator.convert(document.getElementById("contents"), '/daumeditor/pages/template/simple.html', function () {
			EditorJSLoader.ready(function (Editor) {
				new Editor(config);
				Editor.modify({
					content: document.getElementById("contents").value
				});
			});
		});
	}
	*/

	//------------- Data tables -------------//
	//with tabletools
	$('.table').checkAll({
		masterCheckbox: '.check-all',
		otherCheckboxes: '.check',
		highlightElement: {
            active: true,
            elementClass: 'tr',
            highlightClass: 'highlight'
        }
	});
	
	// 리스트 선택삭제
	$('#btn_list_delete').on('click',function(){
		//체크박스 체크
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#board-form-list').submit();
				}		  		
		    }
		});
	});
	
	// 셋팅 상세페이지 삭제
	$('#contents_setting_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#board-form-write-setting').submit();
				}		  		
		    }
		});
	});

	// 게시판 상세페이지 삭제
	$('#contents_board_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#board_form_write_board').submit();
				}
		    }
		});
	});

	



	//게시판 셋팅 폼 검사
	$("#board-form-write-setting").validate({
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
			board_code: {
				required : true,
				maxlength: 20
			},
			board_type: {
				required : true
			},
			board_name: {
				required : true,
				maxlength: 20
			},
			board_order: {
				required : true,
				number: true
			}
		},
		messages: {
			board_code: {
				required: "게시판 코드를 입력해주세요.",
				maxlength: "게시판 코드는 영문 1~20자 입니다."
			},
			board_type: {
				required: "게시판 타입을 선택해주세요."
			},
			board_name: {
				required: "게시판 이름을 입력해주세요.",
				maxlength: "게시판 이름은 1~20자 입니다."
			},
			board_order: {
				required: "게시판 순서를 입력해주세요.",
				number: "숫자만 입력해주세요."
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


	//게시판 폼 검사
	$("#board_form_write_board").validate({
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
				required : true,
				maxlength: 200
			},
			contents: {
				required: false
			}
		},
		messages: {
			subject: {
				required: "제목을 입력해주세요.",
				maxlength: "제목은 200자 입니다."
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

	$('#test-submit-button').click(function(){
		//saveContent();
	});



	function saveContent() {
        Editor.save();
    }

    function validForm(editor) {
		alert('validForm');
        var validator = new Trex.Validator();
        var content = editor.getContent();
        if (!validator.exists(content)) {
            alert('Content is empty');
            return false;
        }

        return true;
    }

    function setForm(editor) {
		alert('setForm');
        var i, input;
        var form = editor.getForm();
        var content = editor.getContent();

        var field = document.getElementById("contents");
        field.value = content;

        var images = editor.getAttachments('image');
        for (i = 0; i < images.length; i++) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'attach_image';
            input.value = images[i].data.imageurl;
            form.createField(input);
        }

        var files = editor.getAttachments('file');
        for (i = 0; i < files.length; i++) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'attach_file';
            input.value = files[i].data.attachurl;
            form.createField(input);
        }
        return true;
    }

	
	
});