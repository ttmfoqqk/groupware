<div class="row form-group ">
	<div class="col-xs-4 text-center">명칭</div>
	<div class="col-xs-2 text-center">권한1</div>
	<div class="col-xs-2 text-center">권한2</div>
	<div class="col-xs-2 text-center">권한2</div>
	<div class="col-xs-2 text-center">권한2</div>
</div>

<div id="input-base">

	<!-- input row -->
	<div class="row form-group pop-row" style="padding:5px 0px 5px 0px;">
		<div class="col-xs-4 text-center">
			<input type="text" name="pop_order" class="form-control" placeholder="순서" data-value="" value="" readonly>
		</div>
		<div class="col-xs-2 text-center">
			<select class="fancy-select form-control tb_num" name="tb_num">
				<option value="Y">Y</option>
				<option value="M">N</option>
			</select>
		</div>
		<div class="col-xs-2 text-center">
			<select class="fancy-select form-control tb_num" name="tb_num">
				<option value="Y">Y</option>
				<option value="M">N</option>
			</select>
		</div>
		<div class="col-xs-2 text-center">
			<select class="fancy-select form-control tb_num" name="tb_num">
				<option value="Y">Y</option>
				<option value="M">N</option>
			</select>
		</div>
		<div class="col-xs-2 text-center">
			<select class="fancy-select form-control tb_num" name="tb_num">
				<option value="Y">Y</option>
				<option value="M">N</option>
			</select>
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
	var name  = obj.find('input[name="pop_name"]');
	var data  = obj.find('input[name="pop_data"]');
	var order = obj.find('input[name="pop_order"]');

	name.each(function(){
		$(this).val( $(this).attr('data-value') );
	});
	data.each(function(){
		$(this).val( $(this).attr('data-value') );
		$(this).datepicker({language : 'kr',  format: 'yyyy-mm-dd',  autoclose:true});
	});
	order.each(function(){
		$(this).val( $(this).attr('data-value') );
	});
}

// 리스트 생성
// number : 기본 리스트 갯수
function get_list(){
	var number = 5;
	$.ajax({
		type : 'POST',
		url  : '/groupware/member/annual_lists/',
		data : {
			no : $no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data);
			var clones = '';
			for (var i in json){
				var new_row  = $new_row.clone();

				var name  = new_row.find('input[name="pop_name"]');
				var data  = new_row.find('input[name="pop_data"]');
				var order = new_row.find('input[name="pop_order"]');

				name.attr('data-value',json[i].name);
				data.attr('data-value',json[i].data.substr(0,10));
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
	alert('준비중'); return false;
	var data_array = new Array();
	var validate_fg = false;
	$('.pop-row').each(function(eq){
		var data_info  = new Object();

		var name  = $(this).find('input[name="pop_name"]');
		var data  = $(this).find('input[name="pop_data"]');
		var order = $(this).find('input[name="pop_order"]');
		
		if( name.val() || data.val() || order.val() ){
			if(!order.val()){
				alert('순서를 입력해주세요.');
				order.focus();
				validate_fg = false;
				return false;
			}
			if(!name.val()){
				alert('내용을 입력해주세요.');
				name.focus();
				validate_fg = false;
				return false;
			}
			if(!data.val()){
				alert('일자를 입력해주세요.');
				data.focus();
				validate_fg = false;
				return false;
			}
		}
		validate_fg = true;

		if(name.val() && data.val() && order.val() ){
			data_info.name  = name.val();
			data_info.data  = data.val();
			data_info.order = order.val();
			data_array.push(data_info);
		}

	});
	//console.log(JSON.stringify(data_array));return false;
	if( validate_fg == true ){
		$.ajax({
			type : 'POST',
			url  : '/groupware/member/annual_insert/',
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
//get_list();
base_show('show');
</script>