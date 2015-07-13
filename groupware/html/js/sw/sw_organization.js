//------------- list.js -------------//
$(document).ready(function() {
	/* ------------------ Nestable lists --------------------*/
	var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
			var json_node = window.JSON.stringify(list.nestable('serialize'));
			action_move(json_node);
            //output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            //output.val('JSON browser support required for this demo.');
			alert('error');
        }
    };

    $('#nestable3').nestable({
		maxDepth : 20
	}).on('change', updateOutput);
	//updateOutput($('#nestable3').data('output', $('#nestable-output')));

	set_button();

	$('#category_add').click(function(){
		action_insert(0);
	});
});

/* 이동 업데이트 */
function action_move(obj){
	$.ajax({
		type     : 'POST',
		url      : '/organization/moves',
		dataType : 'json',
		data     : {
			json_data : obj
		},
		success: function(data){
			if(data.result!='ok'){
				alert(data.result + ',' + data.msg);
			}			
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}
/* 이름수정 */
function action_update(obj){
	$this = obj;
	$no   = $this.parent().parent().attr('data-id');
	$name = $this.text();
	bootbox.prompt({
		title: "이름변경",
		value : $name,
		callback: function(result) {
			$result = result;
			
			if($.trim(result) ){
				//alert(result);
				$.ajax({
					type     : 'POST',
					url      : '/organization/update',
					dataType : 'json',
					data     : {
						no : $no,
						rename : $result
					},
					success: function(data){
						if(data.result=='ok'){
							$this.text($result);
						}else{
							alert(data.result + ',' + data.msg);
						}
					},error:function(err){
						alert(err.responseText);
						//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
					}
				});
			}

		}
	});
}

/* 삭제 */
function action_delete(obj){
	var li = obj.parent().parent();
	var ol = li.parent();
	var c  = ol.children('li').length;

	bootbox.confirm({
		message: "삭제하시겠습니까?",
		title: "삭제하시겠습니까?",
		callback: function(result) {
			
			if(result){
				$.ajax({
					type     : 'POST',
					url      : '/organization/delete',
					dataType : 'json',
					data     : {
						no : li.attr('data-id')
					},
					success: function(data){
						if(data.result=='ok'){
							if( c <= 1 ){
								ol.remove();
							}else{
								li.remove();
							}
						}else{
							alert(data.result + ':' + data.msg);
						}
					},error:function(err){
						alert(err.responseText);
						//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
					}
				});
			}


		}
	});	
}

/* 입력 */
function action_insert(parent_no){
	bootbox.prompt({
		title: "추가",
		callback: function(result) {
			$name = result;
			
			if( $.trim(result) ){
				$.ajax({
					type     : 'POST',
					url      : '/organization/create',
					dataType : 'html',
					data     : {
						parent_no : parent_no,
						name      : $name
					},
					success: function(data){
						// 입력후 트리 다시 그리기
						$('#nestable3').html(data);
						set_button();
					},error:function(err){
						alert(err.responseText);
						//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
					}
				});
			}
			
		}
	});	
}

function set_button(){
	$('#nestable3 .update-item').click(function() {
		action_update($(this));
	});

	$('#nestable3 .delete').click(function(){
		action_delete($(this));
	});

	$('#nestable3 .add').click(function(){
		$this      = $(this);
		$parent_no = $this.parent().parent().attr('data-id');

		action_insert($parent_no);
	});
}