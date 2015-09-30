$(document).ready(function() {
	$(".input-daterange").datepicker({format: 'yyyy-mm-dd'});
	/* 리스트 페이지 */
	
	// 분류 selectbox
	var $menu_no = $('#menu_no');
	if($menu_no.length>0){
		$menu_no.create_menu({
			method : $menu_no.attr('data-method'),
			value : $menu_no.attr('data-value')
		});
	}
	// 등록일자
	var sData  = $('#sData');
	var eData  = $('#eData');
	// 등록일자 btn
	var btn_sToday = $('#sToday');
	var btn_sWeek  = $('#sWeek');
	var btn_sMonth = $('#sMonth');
	var btn_sReset = $('#sReset');

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
	
	$('#btn_list_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제 하시겠습니까?",
			title: "삭제 하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#meeting-form-list').submit();
				}
		    }
		});
	});
	/* 리스트 페이지 */

	/* 쓰기 페이지 */
	$('#contents_setting_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제 하시겠습니까?",
			title: "삭제 하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#meeting-form-write-setting').submit();
				}
		    }
		});
	});
	/* 쓰기 페이지 */
});



function call_document_modal(){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';

	bootbox.dialog({
		message: test_html,
		title: '서식선택',
		buttons: {
			cancel: {
				label: '닫기',
				className: "btn-danger"
			}
		}
	});
	$('.modal-header').css("background-color","#51bf87 ");
	$('.modal-header').css("color","white");
	$('.modal-dialog').addClass('modal70');

	// 팝업 호출
	$('#modal-body').load('/groupware/html/pop/project_call_document.php',function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success"){
			$(this).find('.input-daterange').datepicker();
			
			var sData  = $(this).find('#pop_sData');
			var eData  = $(this).find('#pop_eData');
			
			var btn_sToday = $(this).find('#sToday');
			var btn_sWeek  = $(this).find('#sWeek');
			var btn_sMonth = $(this).find('#sMonth');
			var btn_sReset = $(this).find('#sReset');
			
			
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
			
		}
	});
}