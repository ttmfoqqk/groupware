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
					$('#object-form-write-setting').submit();
				}
		    }
		});
	});
	
	
	//분류필터 리스트 init
	var $menu_part_no = $('#menu_no');
	$menu_part_no.create_menu({
		method : $menu_part_no.attr('data-method'),
		value : $menu_part_no.attr('data-value')
	});
	//관리자필터 리스트 init
	
	var $user_part_no = $('#user_no');
	$user_part_no.create_user({
		method : $user_part_no.attr('data-method'),
		value : $user_part_no.attr('data-value')
	});
	
	
})