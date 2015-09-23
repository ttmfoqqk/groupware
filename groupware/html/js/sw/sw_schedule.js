$(document).ready(function() {
	$(".input-daterange").datepicker({format:'yyyy-mm',minViewMode:1,startView:1});
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
	var action_type = $('#action_type');
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


	// 진행기간 datepicker
	btn_sToday.click(function(){
		var myDate = new Date();
		sData.datepicker('setDate', new Date() );
		eData.datepicker('setDate', new Date() );
	});
	btn_sWeek.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getMonth();
		myDate.setMonth(dayOfMonth + 3);
		sData.datepicker('setDate', new Date());
		eData.datepicker('setDate', myDate );
	});
	btn_sMonth.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getMonth();
		myDate.setMonth(dayOfMonth + 6);
		sData.datepicker('setDate', new Date());
		eData.datepicker('setDate', myDate );
	});
	btn_sReset.click(function(){
		sData.datepicker('setDate', '');
		eData.datepicker('setDate', '');
	});
});