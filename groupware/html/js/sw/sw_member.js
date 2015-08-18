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
					$('#company-form-list').submit();
				}		  		
		    }
		});
	});
	
	$('.tb_num').change(function(){
		$('#qu').submit();
	});
	//<input type="file" id="userfile" name="userfile" class="filestyle" data-buttonText="Find file" data-buttonName="btn-danger" data-iconName="fa fa-plus">
	
	
	$(".input-daterange").datepicker(koDatePickerOpt);
	$('#component-colorpicker').colorpicker({
    	color: '#1fba5d'
    });
})