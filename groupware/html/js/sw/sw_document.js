//------------- list.js -------------//
$(document).ready(function() {
	$(".input-daterange").datepicker();
	var $menu_no = $('#menu_no');
	
	$menu_no.create_menu({
		method : $menu_no.attr('data-method'),
		value : $menu_no.attr('data-value')
	});
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
					$('#document-form-list').submit();
				}		  		
		    }
		});
	});

	$('.tb_num').change(function(){
		$('#qu').submit();
	});

});
