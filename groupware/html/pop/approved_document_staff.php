<div class="row form-group ">
	<div class="col-xs-1 text-center">결재순</div>
	<div class="col-xs-3 text-center">부서선택</div>
	<div class="col-xs-3 text-center">담당자 선택</div>
	<div class="col-xs-4 text-center">비고</div>
	<div class="col-xs-1 text-center"> </div>
</div>

<div id="input-base">

	<!-- input row -->
	<div class="row form-group pop-row" style="padding:5px 0px 5px 0px;">
		<div class="col-xs-1 text-center" style="line-height:250%;">1순위</div>
		<div class="col-xs-3 text-center">
			<select name="pop_menu_no" data-method="department" data-value="" class="fancy-select form-control">
				<option value="">선택</option>
			</select>
		</div>
		<div class="col-xs-3 text-center">
			<select name="pop_user_no" data-value="" class="fancy-select form-control">
				<option value="">선택</option>
			</select>
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
var $approved_no = '<?echo $_POST["no"];?>';
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
	row_counting();
}

// 순위, select box 셋팅
function row_counting(){
	$('.pop-row').each(function(eq){
		$(this).find('div:first').text( (eq+1) + '순위');
	});
}

function set_selectbox(obj){
	var menu_obj = obj.find('select[name="pop_menu_no"]');
	var user_obj = obj.find('select[name="pop_user_no"]');
	var bigo     = obj.find('input[name="pop_bigo"]');

	menu_obj.each(function(eq){
		$this = $(this);
		$this.create_menu({
			method : $this.attr('data-method'),
			value  : $this.attr('data-value')
		});
		$this.change(function(){
			var no = $(this).val();
			create_user(no,user_obj.filter(':eq('+eq+')'));
		});

		create_user( $this.attr('data-value') ,user_obj.filter(':eq('+eq+')'));
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
		type     : 'POST',
		url      : '/groupware/approved_archive/temp_doc_staff_lists/',
		data     : {
			approved_no : $approved_no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data);
			var clones = '';
			for (var i in json){
				var new_row  = $new_row.clone();
				// value setting
				var menu_obj = new_row.find('select[name="pop_menu_no"]');
				var user_no  = new_row.find('select[name="pop_user_no"]');
				var bigo     = new_row.find('input[name="pop_bigo"]');

				menu_obj.attr('data-value',json[i].menu_no);
				user_no.attr('data-value',json[i].user_no);
				bigo.attr('data-value',json[i].bigo);

				clones += new_row.wrapAll('<div>').parent().html();
			}
			
			for (var i=json.length;i<number;i++){
				clones += $new_row.wrapAll('<div>').parent().html();
			}

			$input_base.html(clones);
			set_selectbox($input_base);
			row_counting();
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
		var menu_no = $(this).find('select[name="pop_menu_no"]');
		var user_no = $(this).find('select[name="pop_user_no"]');
		var bigo    = $(this).find('input[name="pop_bigo"]');
		
		if( menu_no.val() || user_no.val() || bigo.val() ){
			if(!menu_no.val()){
				alert('부서를 선택해 주세요.');
				menu_no.focus();
				validate_fg = false;
				return false;
			}
			if(!user_no.val()){
				alert('담당자를 선택해 주세요.');
				user_no.focus();
				validate_fg = false;
				return false;
			}
		}
		validate_fg = true;

		if(menu_no.val() && user_no.val()){
			data_info.menu_no = menu_no.val();
			data_info.user_no = user_no.val();
			data_info.bigo    = bigo.val();
			data_array.push(data_info);
		}

	});
	//console.log(JSON.stringify(data_array));return false;
	if( validate_fg == true ){
		$.ajax({
			type     : 'POST',
			url      : '/groupware/approved_archive/temp_doc_staff_insert/',
			data     : {
				approved_no : $approved_no,
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

// 유저 select box option 생성
function create_user(no,obj){
	no = !no ? 0 : no;
	obj.html('<option value="">로딩중..</option>');

	var setVal = obj.attr('data-value');
	$.ajax({
		type     : 'POST',
		url      : '/groupware/member/lists/',
		data     : {
			menu_no : no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data);
			var html = '';
			for (var i in json){
				html += '<option value="' + json[i].no + '"'+ (setVal==json[i].no?' selected':'') +'>' + json[i].name + '</option>';
			}
			html = '<option value="">선택</option>' + html;
			obj.html(html);
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}


get_list();
</script>