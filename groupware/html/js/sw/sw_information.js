$(document).ready(function() {
	$(".input-daterange").datepicker();
	
	var sData  = $('#sData');
	var eData  = $('#eData');
	
	var btn_sToday = $('#sToday');
	var btn_sWeek  = $('#sWeek');
	var btn_sMonth = $('#sMonth');
	var btn_sReset = $('#sReset');
	
	btn_sToday.click(function(){
		set_btn_datepicker(sData,eData,-0);
	});
	btn_sWeek.click(function(){
		set_btn_datepicker(sData,eData,-7);
	});
	btn_sMonth.click(function(){
		set_btn_datepicker(sData,eData,-30);
	});
	btn_sReset.click(function(){
		set_btn_datepicker(sData,eData,null);
	});
	
	// 회사관리 상세페이지 삭제
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
					$('#company-form-list').submit();
				}		  		
		    }
		});
	});
	
	$('.tb_num').change(function(){
		$('#qu').submit();
	});
	
});

/* 담당자 팝업 */
function staff_modal(no,page,callback){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';
	var title = page=='staff'?'담당자':'사이트';
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
	if(page=='staff'){
		$('#modal-body').load('/groupware/html/pop/information_staff.php',{'no':no});
	}else{
		$('#modal-body').load('/groupware/html/pop/information_site.php',{'no':no});
	}
}

function call_staff(no,page){
	staff_modal(no,page,function(){
		// 팝업창 내부 함수
		modal_submit();
		return false;
	});
}