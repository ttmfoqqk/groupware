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
	
	// 상세페이지 삭제
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
					$('#object-form-list').submit();
				}		  		
		    }
		});
	});
	
	//테이블 행 수 설정
	$('.tb_num').change(function(){
		$('#qu').submit();
	});
	
	//분류필터 리스트 init
	var $menu_part_no = $('#ft_kind');
	$menu_part_no.create_menu({
		method : $menu_part_no.attr('data-method'),
		value : $menu_part_no.attr('data-value')
	});
	
})