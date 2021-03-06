var koDatePickerOpt = {language : 'kr',  format: 'yyyy-mm-dd',  todayHighlight:true}; 	//dataPicker option (korean)

$(document).ready(function() {
	//상세페이지 삭제
	$('#contents_setting_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#company-form-write-setting').submit();
				}
		    }
		});
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
					$('#member-form-list').submit();
				}		  		
		    }
		});
	});
	
	$('.tb_num').change(function(){
		$('#qu').submit();
	});
	
	
	$(".input-daterange").datepicker(koDatePickerOpt);
});



/* 팝업 */
function staff_modal(no,page,callback){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';
	var title = '';
	if(page == 'department'){
		title = '부서/직급';
	}else if(page == 'annual'){
		title = '연차등록';
	}else if(page == 'permission'){
		title = '권한설정';
	}
	bootbox.dialog({
		message: test_html,
		title: title,
		buttons: {
			cancel: {
				label: '닫기',
				className: "btn-danger"
			},
			success: {
				label: '저장',
				className: "btn-success",
				callback: callback
			}
		}
	});
	$('.modal-header').css("background-color","#51bf87 ");
	$('.modal-header').css("color","white");
	$('.modal-dialog').addClass('modal70');

	// 팝업 호출
	$('#modal-body').load('/groupware/html/pop/user_'+page+'.php',{'no':no});
}

function call_staff(no,page){
	staff_modal(no,page,function(){
		// 팝업창 내부 함수
		modal_submit();
		return false;
	});
}