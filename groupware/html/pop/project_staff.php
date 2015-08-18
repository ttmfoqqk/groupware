<div class="row form-group ">
	<div class="col-xs-1 text-center">결재순</div>
	<div class="col-xs-3 text-center">부서선택</div>
	<div class="col-xs-3 text-center">담당자 선택</div>
	<div class="col-xs-4 text-center">비고</div>
	<div class="col-xs-1 text-center"> </div>
</div>


<form id="pop-form" name="pop-form" onsubmit="return false;">
<!-- input row -->
<div class="row form-group pop-row">
	<div class="col-xs-1 text-center" style="line-height:250%;">1순위</div>
	<div class="col-xs-3 text-center">
		<select name="pop_menu_no" data-method="department" data-value="" class="fancy-select form-control">
			<option value="">부서 선택</option>
		</select>
	</div>
	<div class="col-xs-3 text-center">
		<select name="pop_user_no" data-method="department" data-value="" class="fancy-select form-control">
			<option value="">담당자 선택</option>
			<option value="1">임시 데이터 관리자 no.1</option>
		</select>
	</div>
	<div class="col-xs-4 text-center">
		<input type="text" name="pop_bigo" class="form-control" placeholder="비고" value="<?echo $test?>">
	</div>
	<div class="col-xs-1 text-center" style="line-height:250%;padding:0%;">
		<button type="button" class="btn btn-ms btn-primary" onclick="row_controll($(this),'add')"><i class="glyphicon glyphicon-plus"></i></button>
		<button type="button" class="btn btn-ms btn-danger" onclick="row_controll($(this),'remove')"><i class="glyphicon glyphicon-minus"></i></button>
	</div>
</div>
<!-- input row -->

</form>

<script type="text/javascript">
// 리스트에서 받은 no
var $project_no = '<?echo $_POST["no"];?>';
// 최초 html 클론 저장
var $new_row    = $('.pop-row').clone();

// row 추가/삭제
function row_controll(obj,mode){
	var len     = $('.pop-row').length;
	var parent  = obj.parent().parent();
	var new_row = $new_row.clone();
	
	if( mode == 'add' ){
		$(new_row).insertAfter(parent);
	}else if( mode == 'remove' ){
		if(len <= 1){
			alert('1개 삭제금지');
			return false;
		}else{
			parent.remove();
		}
	}
	row_counting();
}

// 순위, select box 셋팅
function row_counting(){
	$('.pop-row').each(function(eq){
		$(this).find('div:first').text( (eq+1) + '순위');

		var pop_menu_no = $(this).find('select[name="pop_menu_no"]');
		pop_menu_no.create_menu({
			method : pop_menu_no.attr('data-method'),
			value  : pop_menu_no.attr('data-value')
		});
	});
}

// 리스트 생성
// number : 기본 리스트 갯수
function get_list(number){
	$.ajax({
		type     : 'POST',
		url      : '/groupware/project/staff_lists/',
		data     : {
			project_no : $project_no
		},
		dataType : 'json',
		success: function(data){
			$data = eval(data);
			for (var i in $data){
				var new_row = $new_row.clone();
				$(new_row).insertAfter($('.pop-row:last'));

				var pop_menu_no = new_row.find('select[name="pop_menu_no"]');
				pop_menu_no.attr('data-value',$data[i].menu_no);
			}
			for (var i=$data.length;i<number;i++){
				var new_row = $new_row.clone();
				$(new_row).insertAfter($('.pop-row:last'));
			}
			// 초기 셋팅된 html 삭제
			$('.pop-row:first').remove();
			row_counting();
			// base div show
			$('#modal-body').show();
			$('#modal-loading').hide();
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}



// 입력
function modal_submit(){
	var data_array = new Array();

	$('.pop-row').each(function(eq){
		var data_info  = new Object();
		var menu_no = $(this).find('select[name="pop_menu_no"]').val();
		var user_no = $(this).find('select[name="pop_user_no"]').val();
		var bigo    = $(this).find('input[name="pop_bigo"]').val();
		
		data_info.menu_no = menu_no;
		data_info.user_no = user_no;
		data_info.bigo    = bigo;
		data_array.push(data_info);

		// 폼 검증 추가 , 빈 블럭 넘기기
	});
	//console.log(JSON.stringify(data_array));return false;
	
	$.ajax({
		type     : 'POST',
		url      : '/groupware/project/staff_insert/',
		data     : {
			project_no : $project_no,
			json_data  : JSON.stringify(data_array)
		},
		dataType : 'json',
		success: function(data){
			if(data.result!='ok'){
				alert(data.result + ',' + data.msg);
			}else{
				alert('저장됨');
			}
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}

// 리스트 호출 기본 5개
get_list(5);
</script>