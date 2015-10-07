<div class="row form-group ">
	<div class="col-xs-1 text-center">순서</div>
	<div class="col-xs-4 text-center">URL</div>
	<div class="col-xs-2 text-center">아이디</div>
	<div class="col-xs-2 text-center">비밀번호</div>
	<div class="col-xs-2 text-center">비고</div>
	<div class="col-xs-1 text-center"> </div>
</div>

<div id="input-base">

	<!-- input row -->
	<div class="row form-group pop-row" style="padding:5px 0px 5px 0px;">
		<div class="col-xs-1 text-center">
			<input type="text" name="pop_order" class="form-control" value="{order}">
		</div>
		<div class="col-xs-4 text-center">
			<input type="text" name="pop_url" class="form-control" value="{url}">
		</div>
		<div class="col-xs-2 text-center">
			<input type="text" name="pop_id" class="form-control" value="{id}">
		</div>
		<div class="col-xs-2 text-center">
			<input type="text" name="pop_pwd" class="form-control" value="{pwd}">
		</div>
		<div class="col-xs-2 text-center">
			<input type="text" name="pop_bigo" class="form-control" value="{bigo}">
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
		url  : '/groupware/information/site_lists/',
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
				template = template.replace(/{order}/gi ,json[i].order);
				template = template.replace(/{url}/gi   ,json[i].url);
				template = template.replace(/{id}/gi    ,json[i].id);
				template = template.replace(/{pwd}/gi   ,json[i].pwd);
				template = template.replace(/{bigo}/gi  ,json[i].bigo);

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
		
		var order = $(this).find('input[name="pop_order"]');
		var url   = $(this).find('input[name="pop_url"]');
		var id    = $(this).find('input[name="pop_id"]');
		var pwd   = $(this).find('input[name="pop_pwd"]');
		var bigo  = $(this).find('input[name="pop_bigo"]');
		
		if( order.val() || url.val() || id.val() || pwd.val() || bigo.val() ){
			if(!order.val()){
				alert('순서를 입력해 주세요.');
				order.focus();
				validate_fg = false;
				return false;
			}
			if(!url.val()){
				alert('URL을 입력해 주세요.');
				url.focus();
				validate_fg = false;
				return false;
			}
			/*
			if(!id.val()){
				alert('아이디를 입력해 주세요.');
				id.focus();
				validate_fg = false;
				return false;
			}
			if(!pwd.val()){
				alert('비밀번호를 입력해 주세요.');
				pwd.focus();
				validate_fg = false;
				return false;
			}			
			if(!bigo.val()){
				alert('비고를 입력해 주세요.');
				bigo.focus();
				validate_fg = false;
				return false;
			}
			*/
		}
		validate_fg = true;

		if(order.val() && url.val() ){
			data_info.order = order.val();
			data_info.url   = url.val();
			data_info.id    = id.val();
			data_info.pwd   = pwd.val();
			data_info.bigo  = bigo.val();
			data_array.push(data_info);
		}

	});
	//console.log(JSON.stringify(data_array));return false;
	if( validate_fg == true ){
		$.ajax({
			type : 'POST',
			url  : '/groupware/information/site_insert/',
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