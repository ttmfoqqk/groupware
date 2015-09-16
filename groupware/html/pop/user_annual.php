<div class="row form-group ">
	<div class="col-xs-1 text-center">순서</div>
	<div class="col-xs-7 text-center">연차내용</div>
	<div class="col-xs-3 text-center">연차일자</div>
	<div class="col-xs-1 text-center"> </div>
</div>

<div id="input-base">

	<!-- input row -->
	<div class="row form-group pop-row" style="padding:5px 0px 5px 0px;">
		<div class="col-xs-1 text-center">
			<input type="text" name="pop_order" class="form-control" placeholder="순서" data-value="" value="">
		</div>
		<div class="col-xs-7 text-center">
			<input type="text" name="pop_name" class="form-control" placeholder="내용" data-value="" value="">
		</div>
		<div class="col-xs-3 text-center">
			<div class="input-daterange input-group">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" class="form-control tdDate datepick-min" name="pop_data" data-value="" value="">
			</div>
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
// 연차일수
var $annual = 0;
// 등록된 업무 일자 리스트 sData~eData
var $no_data = new Object();

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
			var no_data= json.no_data;
			var cnt    = json.cnt;
			var list   = json.list;
			var clones = '';

			$annual = json.cnt.annual;
			$no_data = no_data;

			for (var i in list){
				var new_row  = $new_row.clone();

				var name  = new_row.find('input[name="pop_name"]');
				var data  = new_row.find('input[name="pop_data"]');
				var order = new_row.find('input[name="pop_order"]');

				name.attr('data-value',list[i].name);
				data.attr('data-value',list[i].data.substr(0,10));
				order.attr('data-value',list[i].order);

				clones += new_row.wrapAll('<div>').parent().html();
			}
			
			for (var i=list.length;i<number;i++){
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
	var annual = $annual;
	var no_data = new Array();
	
	var date = new Date();
	var year = pad(date.getFullYear());

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

			var t_date = new Date( data.val() );
			var t_year = pad(date.getFullYear());

			if( year == t_year ){
				annual--;
			}

			for(var i in $no_data){
				if( $no_data[i].sData <= data.val() && $no_data[i].eData >= data.val() ){
					no_data.push(data.val());
				}
			}
		}

	});

	if( no_data.length > 0 ){
		alert('등록된 업무가 있습니다.\n업무설정 후 다시 등록해 주시기 바랍니다. \n\n' + no_data.join('\n') );
		validate_fg = false;
		return false;
	}

	if( annual <= 0 ){
		alert('연차일을 모두 사용하였습니다. \n'+year+'년도 사용가능한 연차일수는 '+$annual+'일 입니다.');
		validate_fg = false;
		return false;
	}	

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
get_list();
</script>