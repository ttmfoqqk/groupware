/* 문서 팝업 */
function call_data_modal(mode){
	var load_url   = '';
	var load_title = '';
	if( mode=='project' ){
		load_url   = '/groupware/html/pop/approved_call_project_by_chc_write.php';
		load_title = '업무선택';
	}else{
		alert('mode 누락');return false;
	}
	// base div hide
	var base_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';
	
	bootbox.dialog({
		message: base_html,
		title: load_title,
		buttons: {
			cancel: {
				label: '닫기',
				className: "btn-danger"
			}
		}
	});

	$('.modal-header').css("background-color","#51bf87 ");
	$('.modal-header').css("color","white");
	$('.modal-dialog').addClass('modal70');

	// 팝업 호출
	$('#modal-body').load(load_url);
}


//------------- list.js -------------//
$(document).ready(function() {
	if($('#action_type').val() == "create")
		$('#contents_setting_delete').hide();
	else
		$('#contents_setting_delete').show();
	
	//고객사 필터 리스트 init
	var $menu_part_no = $('#ft_commpany');
	$menu_part_no.create_mcompany({
		method : 'marketingList',
		value : $menu_part_no.attr('data-value')
	});
	
	//id 필터 리스트 init
	//addEvent();
	addEvent($(".tbRow"));
	
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
					$('#chc-form-write-setting').submit();
				}
		    }
		});
	});
	
	//업무불러오기
	$('#call_approved_kind').click(function(){
		var kind = $('#approved_kind').val();
		call_data_modal('project');
	});
	
	if($('#action_type').val() != 'create' ){
		$("#call_approved_kind").hide();
		$("#keyword").attr("disabled",true); //readonly
		$("#ft_commpany").attr("disabled",true);
	}
	

});

//input-base
var $idTable_base = $('.tbId tbody');
//최초 html 클론 저장
var $new_row    = $('.tbRow').clone();

//row 추가/삭제
function row_controll(obj, mode){
	var len     = $('.tbRow').length;
	var parent  = obj.parent().parent();
	var new_row = $new_row.clone();
	
	if( mode == 'add' ){
		new_row.css('background-color','#82ffab').animate({
			'background-color':'#ffffff'
		},500);

		addEvent(new_row);
		$(new_row).insertAfter(parent);	
		
	}else if( mode == 'rm' ){
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

function addEvent(obj){
	//id 필터 리스트 init
	var menu_obj = obj.find('select[name="sel_id"]');
	menu_obj.each(function(eq){
		$this = $(this);
		$this.create_idList({
			method : 'lists',
			value : $this.attr('data-value')
		});
		$this.change(function(){
			var no = $(this).val();
			createIdRow(no, obj);
			menu_obj.attr("data-value", no);
		});
	});
}

//ID select box 선택 시 테이블 변경
function createIdRow(no,obj){
	no = !no ? null : no;
	$.ajax({
		type     : 'POST',
		url      : '/groupware/account/lists/',
		data     : {
			accountNo : no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data.data);
			obj.find('.tdPass').text(json[0].pwd);
			obj.find('.tdName').text(json[0].name);
			obj.find('.tdGrade').text(json[0].grade);
			obj.find('.tdBirth').text(json[0].birth);
			obj.find('.tdUsed').text(json[0].used);
			obj.find('.tdEmail').text(json[0].email);
			if( json[0].is_using_anser == 1)
				obj.find('.tdUsed').text("답변");
			else if(json[0].is_using_question == 1)
				obj.find('.tdUsed').text("질문");
			
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}


(function($){
	//마케팅 고객사 셀렉트 생성
	$.fn.create_mcompany = function(options){
		var $method = options.method;
		var $value  = options.value;
		var element = $(this);
		
		if(element.length<=0){
			alert('잘못된 객체');
			return false;
		}
		if( !$method || $method=='undefined' ){
			alert('method 누락');return false;
		}

		var url     = '/groupware/company/' + $method;
		var tagName = element.get(0).tagName;
		
		if(tagName != 'SELECT'){
			alert('select 만 있음;;');return false;
		}

		$.ajax({
			type     : 'POST',
			url      : url,
			dataType : 'json',
			success: function(data){
				$data = eval(data.data);
				
				if(tagName=='SELECT'){
					var html = create_option($data);
					element.append(html);
				}
			},error:function(err){
				alert(err.responseText);
				//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
			}
		});

		function create_option(json_obj){
			var output = '';
			for (var i in json_obj){
				console.log(json_obj[i]);
				console.log($value);
                output+='<option value="'+json_obj[i].no+'" '+ ($value==json_obj[i].no?'selected':'') +'>' + json_obj[i].bizName + '</option>';
            }
			return output;
		}
	}
	
	//계정목록 셀렉트 박스 생성
	$.fn.create_idList = function(options){
		var $method = options.method;
		var $value  = options.value;
		var element = $(this);
		
		if(element.length<=0){
			alert('잘못된 객체');
			return false;
		}
		if( !$method || $method=='undefined' ){
			alert('method 누락');return false;
		}

		var url     = '/groupware/account/' + $method;
		var tagName = element.get(0).tagName;
		
		if(tagName != 'SELECT'){
			alert('select 만 있음;;');return false;
		}

		$.ajax({
			type     : 'POST',
			url      : url,
			dataType : 'json',
			success: function(data){
				$data = eval(data.data);
				
				if(tagName=='SELECT'){
					var html = create_option($data);
					element.append(html);
				}
			},error:function(err){
				alert(err.responseText);
				//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
			}
		});

		function create_option(json_obj){
			var output = '';
			for (var i in json_obj){
				console.log(json_obj[i]);
				console.log($value);
                output+='<option value="'+json_obj[i].no+'" '+ ($value==json_obj[i].no?'selected':'') +'>' + json_obj[i].id + '</option>';
            }
			return output;
		}
	}
	
})(jQuery);
