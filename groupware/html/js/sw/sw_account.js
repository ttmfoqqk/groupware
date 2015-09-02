
$(document).ready(function() {
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
					$('#account-form-list').submit();
				}		  		
		    }
		});
	});
	
	$("#ft_type").val($("#ft_type").attr('value')).attr("selected", "selected");	//분류필터 init
	$("#ft_grade").val($("#ft_grade").attr('value')).attr("selected", "selected");	//등급필터 init
	$("#ft_use").val($("#ft_use").attr('value')).attr("selected", "selected");		//용도필터 init
	
	$('.tb_num').change(function(){
		$('#qu').submit();
	});
	
})
