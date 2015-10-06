//------------- tables-data.js -------------//
$(document).ready(function() {
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
	
	if($('#contents').length > 0){
	
		var oEditors = [];
		
		nhn.husky.EZCreator.createInIFrame({
			oAppRef: oEditors,
			elPlaceHolder: "contents",
			sSkinURI: "/groupware/SE2.8.2.O12056/SmartEditor2Skin.html",	
			htParams : {
				bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
				bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
				bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
				//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
				fOnBeforeUnload : function(){
					//alert("완료!");
				}
			}, //boolean
			fOnAppLoad : function(){
				//예제 코드
				//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
			},
			fCreator: "createSEditor2"
		});
	
	}
	
	function submitContents() {
		oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		
		// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ir1").value를 이용해서 처리하면 됩니다.
		
		try {
			$("#board_form_write_board").submit();
		} catch(e) {}
	}
	
	$('#board-submit-button').click(function(){
		submitContents();
	});
	
});