var koDatePickerOpt = {language : 'kr',  format: 'yyyy-mm-dd',  todayHighlight:true}; 	//dataPicker option (korean)
var enDatePickerOpt = {format: 'yyyy-mm-dd',  todayHighlight:true};						//dataPicker option (english)

$(document).ready(function() {
	//date range
	$(".input-daterange").datepicker(koDatePickerOpt);
	//START data 세팅 버튼 이벤트
	$(".init_date").on('click',function(){
		$("input[name$='start']").datepicker('setDate', ""); //$.datepicker.formatDate('yy-mm-dd', new Date())
		$("input[name$='end']").datepicker('setDate', "");
	});
	
	$(".init_today").on('click',function(){
		$("input[name$='start']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
		$("input[name$='end']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	$(".init_seven").on('click',function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 6);
		$("input[name$='start']").datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		$("input[name$='end']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	$(".init_thirty").on('click',function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 29);
		$("input[name$='start']").datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		$("input[name$='end']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	//END data 세팅 버튼 이벤트
	
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