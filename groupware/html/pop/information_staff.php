<div class="row form-group ">
	<div class="col-xs-1 text-center">순서</div>
	<div class="col-xs-1 text-center">담당자</div>
	<div class="col-xs-2 text-center">부서</div>
	<div class="col-xs-2 text-center">직급</div>
	<div class="col-xs-2 text-center">직통번호</div>
	<div class="col-xs-1 text-center">내선번호</div>
	<div class="col-xs-2 text-center">이메일</div>
	<div class="col-xs-1 text-center"> </div>
</div>

<div id="input-base">

	<!-- input row -->
	<div class="row form-group pop-row" style="padding:5px 0px 5px 0px;">
		<div class="col-xs-1 text-center">
			<input type="text" name="pop_order" class="form-control" value="{order}">
		</div>
		<div class="col-xs-1 text-center">
			<input type="text" name="pop_name" class="form-control" value="{name}">
		</div>
		<div class="col-xs-2 text-center">
			<input type="text" name="pop_part" class="form-control" value="{part}">
		</div>
		<div class="col-xs-2 text-center">
			<input type="text" name="pop_position" class="form-control" value="{position}">
		</div>
		<div class="col-xs-2 text-center">
			<input type="text" name="pop_phone" class="form-control" value="{phone}">
		</div>
		<div class="col-xs-1 text-center">
			<input type="text" name="pop_ext" class="form-control" value="{ext}">
		</div>
		<div class="col-xs-2 text-center">
			<input type="text" name="pop_email" class="form-control" value="{email}">
		</div>
		<div class="col-xs-1 text-center" style="line-height:250%;padding:0%;">
			<button type="button" class="btn btn-ms btn-primary" onclick="row_controll($(this),'add')"><i class="glyphicon glyphicon-plus"></i></button>
			<button type="button" class="btn btn-ms btn-danger" onclick="row_controll($(this),'remove')"><i class="glyphicon glyphicon-minus"></i></button>
		</div>
	</div>
	<!-- input row -->

</div>


<script type="text/javascript">
var $no = '<?echo $_POST["no"];?>';
var $input_base = $('#input-base');
var $new_row    = $input_base.html();

// row 추가/삭제
function row_controll(obj,mode){
	var len     = $('.pop-row').length;
	var parent  = obj.parent().parent();
	var new_row = $new_row.replace(/\{(.*?)\}/gi ,''); //check;
	
	if( mode == 'add' ){
		$(new_row).insertAfter(parent).css('background-color','#82ffab').animate({
			'background-color':'#ffffff'
		},500);		
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

// 리스트 생성
// number : 기본 리스트 갯수
function get_list(){
	var number = 5;
	$.ajax({
		type : 'POST',
		url  : '/groupware/company/staff_lists/',
		data : {
			no : $no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data);
			var html = '';
			var temp = '';

			for (var i in json){
				template = $new_row;
				template = template.replace(/{order}/gi    ,json[i].order);
				template = template.replace(/{name}/gi     ,json[i].name);
				template = template.replace(/{part}/gi     ,json[i].part);
				template = template.replace(/{position}/gi ,json[i].position);
				template = template.replace(/{phone}/gi    ,json[i].phone);
				template = template.replace(/{ext}/gi      ,json[i].ext);
				template = template.replace(/{email}/gi    ,json[i].email);

				html += template;
			}			
			for (var i=json.length;i<number;i++){
				template = $new_row.replace(/\{(.*?)\}/gi ,'');
				html += template;
			}

			$input_base.html(html);
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
		
		var order    = $(this).find('input[name="pop_order"]');
		var name     = $(this).find('input[name="pop_name"]');
		var part     = $(this).find('input[name="pop_part"]');
		var position = $(this).find('input[name="pop_position"]');
		var phone    = $(this).find('input[name="pop_phone"]');
		var ext      = $(this).find('input[name="pop_ext"]');
		var email    = $(this).find('input[name="pop_email"]');
		
		if( order.val() || name.val() || part.val() || position.val() || phone.val() || ext.val() || email.val() ){
			if(!order.val()){
				alert('순서를 입력해 주세요.');
				order.focus();
				validate_fg = false;
				return false;
			}
			if(!name.val()){
				alert('담당자를 입력해 주세요.');
				name.focus();
				validate_fg = false;
				return false;
			}
			if(!part.val()){
				alert('부서를 입력해 주세요.');
				part.focus();
				validate_fg = false;
				return false;
			}
			if(!position.val()){
				alert('직급를 입력해 주세요.');
				position.focus();
				validate_fg = false;
				return false;
			}
			/*
			if(!phone.val()){
				alert('연락처를 입력해 주세요.');
				phone.focus();
				validate_fg = false;
				return false;
			}
			if(!ext.val()){
				alert('내선번호를 입력해 주세요.');
				ext.focus();
				validate_fg = false;
				return false;
			}
			if(!email.val()){
				alert('이메일을 입력해 주세요.');
				email.focus();
				validate_fg = false;
				return false;
			}
			*/
		}
		validate_fg = true;

		if(order.val() && name.val() && part.val() && position.val() ){
			data_info.order    = order.val();
			data_info.name     = name.val();
			data_info.part     = part.val();
			data_info.position = position.val();
			data_info.phone    = phone.val();
			data_info.ext      = ext.val();
			data_info.email    = email.val();
			data_array.push(data_info);
		}

	});
	//console.log(JSON.stringify(data_array));return false;
	if( validate_fg == true ){
		$.ajax({
			type : 'POST',
			url  : '/groupware/company/staff_insert/',
			data : {
				no : $no,
				json_data : JSON.stringify(data_array)
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