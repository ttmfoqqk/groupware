$(document).ready(function() {
	$(".input-daterange").datepicker({format: 'yyyy-mm-dd'});
	/* 리스트 페이지 */
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

	var sData  = $('#sData');
	var eData  = $('#eData');
	var swData = $('#swData');
	var ewData = $('#ewData');

	var btn_sToday = $('#sToday');
	var btn_sWeek  = $('#sWeek');
	var btn_sMonth = $('#sMonth');
	var btn_sReset = $('#sReset');

	var btn_swToday = $('#swToday');
	var btn_swWeek  = $('#swWeek');
	var btn_swMonth = $('#swMonth');
	var btn_swReset = $('#swReset');


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
		//체크박스 체크
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#project-form-list').submit();
				}
		    }
		});
	});
	/* 리스트 페이지 */

	/* 쓰기 페이지 */
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
		//체크박스 체크
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#project-form-write-setting').submit();
				}
		    }
		});
	});
	/* 쓰기 페이지 */

});