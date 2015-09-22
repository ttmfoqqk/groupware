var koDatePickerOpt = {language : 'kr',  format: 'yyyy-mm-dd',  todayHighlight:true}; 	//dataPicker option (korean)

$(document).ready(function() {
	if($('#action_type').val() == "create")
		$('#contents_setting_delete').hide();
	else
		$('#contents_setting_delete').show();
	
	//상세페이지 삭제
	$('#contents_setting_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
				console.log(result);
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#member-form-write-setting').submit();
				}
		    }
		});
	});
	
	$(".input-daterange").datepicker(koDatePickerOpt);
	
	var color_value = $('#color').val() ? $('#color').val() : '#1fba5d';
	$('#component-colorpicker').colorpicker({
    	color: color_value
    });
    
})