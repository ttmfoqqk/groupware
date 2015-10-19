
//------------- list.js -------------//
$(document).ready(function() {
	if($('#action_type').val() == "create")
		$('#contents_setting_delete').hide();
	else
		$('#contents_setting_delete').show();
	
	var $menu_no = $('#menu_no');
	$menu_no.create_menu({
		method : $menu_no.attr('data-method'),
		value : $menu_no.attr('data-value')
	});
	
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
					$('#document-form-write-setting').submit();
				}
		    }
		});
	});
	

});
