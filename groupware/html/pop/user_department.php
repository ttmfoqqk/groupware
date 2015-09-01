<div class="row form-group ">
	<div class="col-xs-1 text-center">순서</div>
	<div class="col-xs-3 text-center">부서선택</div>
	<div class="col-xs-3 text-center">직급</div>
	<div class="col-xs-4 text-center">비고</div>
	<div class="col-xs-1 text-center"> </div>
</div>

<div id="input-base">

	<!-- input row -->
	<div class="row form-group pop-row" style="padding:5px 0px 5px 0px;">
		<div class="col-xs-1 text-center">
			<input type="text" name="pop_order" class="form-control" placeholder="순서" data-value="" value="">
		</div>
		<div class="col-xs-3 text-center">
			<select name="pop_menu_no" data-method="department" data-value="" class="fancy-select form-control">
				<option value="">선택</option>
			</select>
		</div>
		<div class="col-xs-3 text-center">
			<input type="text" name="pop_position" class="form-control" placeholder="직급" data-value="" value="">
		</div>
		<div class="col-xs-4 text-center">
			<input type="text" name="pop_bigo" class="form-control" placeholder="비고" data-value="" value="">
		</div>
		<div class="col-xs-1 text-center" style="line-height:250%;padding:0%;">
			<button type="button" class="btn btn-ms btn-primary" onclick="row_controll($(this),'add')"><i class="glyphicon glyphicon-plus"></i></button>
			<button type="button" class="btn btn-ms btn-danger" onclick="row_controll($(this),'remove')"><i class="glyphicon glyphicon-minus"></i></button>
		</div>
	</div>
	<!-- input row -->

</div>


<script type="text/javascript">
// 리스트에서 받은 no
var $no = '<?echo $_POST["no"];?>';
// input-base
var $input_base = $('#input-base');
// 최초 html 클론 저장
var $new_row    = $('.pop-row').clone();

// row 추가/삭제
function row_controll(obj,mode){
	var len     = $('.pop-row').length;
	var parent  = obj.parent().parent();
	var new_row = $new_row.clone();
	
	if( mode == 'add' ){
		new_row.css('background-color','#82ffab').animate({
			'background-color':'#ffffff'
		},500);

		set_selectbox(new_row);
		$(new_row).insertAfter(parent);		
	}else if( mode == 'remove' ){
		if(len <= 1){
			alert('삭제할수 없습니다.');
			return false;
		}else{
			parent.css('background-color','#ff8282').animate({
				'opacity':'0',
				'background-color':'#ffffff'
			},500,function(){
				$(this).remove();
			});
		}
	}
}


function set_selectbox(obj){
	var menu_no  = obj.find('select[name="pop_menu_no"]');
	var position = obj.find('input[name="pop_position"]');
	var bigo     = obj.find('input[name="pop_bigo"]');
	var order    = obj.find('input[name="pop_order"]');

	menu_no.each(function(eq){
		$this = $(this);
		$this.create_menu({
			method : $this.attr('data-method'),
			value  : $this.attr('data-value')
		});
	});

	order.each(function(){
		$(this).val( $(this).attr('data-value') );
	});
	position.each(function(){
		$(this).val( $(this).attr('data-value') );
	});
	bigo.each(function(){
		$(this).val( $(this).attr('data-value') );
	});
}

// 리스트 생성
// number : 기본 리스트 갯수
function get_list(){
	var number = 5;
	$.ajax({
		type : 'POST',
		url  : '/groupware/member/department_lists/',
		data : {
			no : $no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data);
			var clones = '';
			for (var i in json){
				var new_row  = $new_row.clone();
				// value setting
				var menu_no  = new_row.find('select[name="pop_menu_no"]');
				var position = new_row.find('input[name="pop_position"]');
				var bigo     = new_row.find('input[name="pop_bigo"]');
				var order    = new_row.find('input[name="pop_order"]');

				menu_no.attr('data-value',json[i].menu_no);
				position.attr('data-value',json[i].position);
				bigo.attr('data-value',json[i].bigo);
				order.attr('data-value',json[i].order);

				clones += new_row.wrapAll('<div>').parent().html();
			}
			
			for (var i=json.length;i<number;i++){
				clones += $new_row.wrapAll('<div>').parent().html();
			}

			$input_base.html(clones);
			set_selectbox($input_base);
			// base div show
			base_show('show');
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}



// 입력
function modal_submit(){

	var data_array = new Array();
	var validate_fg = false;
	$('.pop-row').each(function(eq){
		var data_info  = new Object();

		var menu_no  = $(this).find('select[name="pop_menu_no"]');
		var position = $(this).find('input[name="pop_position"]');
		var bigo     = $(this).find('input[name="pop_bigo"]');
		var order    = $(this).find('input[name="pop_order"]');
		
		if( menu_no.val() || position.val() || bigo.val() || order.val() ){
			if(!order.val()){
				alert('순서를 입력해주세요.');
				order.focus();
				validate_fg = false;
				return false;
			}
			if(!menu_no.val()){
				alert('부서를 선택해주세요.');
				menu_no.focus();
				validate_fg = false;
				return false;
			}
			if(!position.val()){
				alert('직급을 입력해주세요.');
				position.focus();
				validate_fg = false;
				return false;
			}
		}
		validate_fg = true;

		if(menu_no.val() && position.val() && order.val() ){
			data_info.menu_no  = menu_no.val();
			data_info.position = position.val();
			data_info.bigo     = bigo.val();
			data_info.order    = order.val();
			data_array.push(data_info);
		}

	});
	//console.log(JSON.stringify(data_array));return false;
	if( validate_fg == true ){
		$.ajax({
			type : 'POST',
			url  : '/groupware/member/department_insert/',
			data : {
				no : $no,
				json_data  : JSON.stringify(data_array)
			},
			dataType : 'json',
			success: function(data){
				if(data.result!='ok'){
					alert(data.result + ',' + data.msg);
				}else{
					// 리스트 다시 로딩
					base_show('hide');
					get_list();
					alert('저장됨');
				}
			},error:function(err){
				alert(err.responseText);
				//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
			}
		});
	}
}
// 베이스,로딩 div
function base_show(m){
	var base_div    = $('#modal-body');
	var loading_div = $('#modal-loading');
	
	if(m=='show'){
		base_div.show();
		loading_div.hide();
	}else{
		base_div.hide();
		loading_div.show();
	}
}
get_list();
</script>