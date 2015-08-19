$(document).ready(function() {
	$(".input-daterange").datepicker({format: 'yyyy-mm-dd'});
	/* 리스트 페이지 */
	
	// 부서,분류 selectbox
	var $menu_part_no = $('#menu_part_no');
	var $menu_no      = $('#menu_no');
	$menu_part_no.create_menu({
		method : $menu_part_no.attr('data-method'),
		value : $menu_part_no.attr('data-value')
	});
	$menu_no.create_menu({
		method : $menu_no.attr('data-method'),
		value : $menu_no.attr('data-value')
	});
	// 진행기간,등록일자 input #진행기간[sData,eData] 은 write 에서도 사용
	var sData  = $('#sData');
	var eData  = $('#eData');
	var swData = $('#swData');
	var ewData = $('#ewData');
	// 진행기간 btn
	var btn_sToday = $('#sToday');
	var btn_sWeek  = $('#sWeek');
	var btn_sMonth = $('#sMonth');
	var btn_sReset = $('#sReset');
	// 등록일자 btn
	var btn_swToday = $('#swToday');
	var btn_swWeek  = $('#swWeek');
	var btn_swMonth = $('#swMonth');
	var btn_swReset = $('#swReset');

	// 진행기간 datepicker
	btn_sToday.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_sWeek.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 6);
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_sMonth.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 29);
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_sReset.click(function(){
		sData.datepicker('setDate', "");
		eData.datepicker('setDate', "");
	});
	
	// 등록일자 datepicker
	btn_swToday.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		swData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		ewData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_swWeek.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 6);
		swData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		ewData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_swMonth.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 29);
		swData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		ewData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_swReset.click(function(){
		swData.datepicker('setDate', "");
		ewData.datepicker('setDate', "");
	});

	$('#btn_list_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#approved-form-list').submit();
				}
		    }
		});
	});
	/* 리스트 페이지 */

	/* 쓰기 페이지 */
	// 진행기간 btn
	var btn_WsToday = $('#WsToday');
	var btn_WsWeek  = $('#WsWeek');
	var btn_WsMonth = $('#WsMonth');
	var btn_WsReset = $('#WsReset');

	btn_WsToday.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', myDate));
	});
	btn_WsWeek.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth + 6);
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', myDate));
	});
	btn_WsMonth.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth + 29);
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', myDate));
	});
	btn_WsReset.click(function(){
		sData.datepicker('setDate', "");
		eData.datepicker('setDate', "");
	});

	$('#contents_setting_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#approved-form-write-setting').submit();
				}
		    }
		});
	});
	/* 쓰기 페이지 */

	

});


/* 담당자 팝업 */
function staff_modal(no,callback){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';

	bootbox.dialog({
		message: test_html,
		title: 'title',
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
	$('#modal-body').load('/groupware/html/pop/project_staff.php',{'no':no});
}

function call_staff(no){
	staff_modal(no,function(){
		// 팝업창 내부 함수
		modal_submit();
		return false;
	});
}
/* 담당자 팝업 */