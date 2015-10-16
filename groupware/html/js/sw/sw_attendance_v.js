var koDatePickerOpt = {language : 'kr',  format: 'yyyy-mm-dd',  todayHighlight:true}; 	//dataPicker option (korean)
var enDatePickerOpt = {format: 'yyyy-mm-dd',  todayHighlight:true};						//dataPicker option (english)

$(document).ready(function() {
	var $menu_no = $('#menu_no');
	if($menu_no.length>0){
		$menu_no.create_menu({
			method : $menu_no.attr('data-method'),
			value : $menu_no.attr('data-value')
		});
	}
	
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

	
	$('.tb_num').change(function(){
		$('#qu').submit();
	});
	
	
})