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
	list();
	
	//고객사 필터 리스트 init
	var $menu_part_no = $('#ft_commpany');
	$menu_part_no.create_mcompany({
		method : 'marketingList',
		value : $menu_part_no.attr('data-value')
	});
	
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
		$('#contents_setting_delete').show();
		$("#call_approved_kind").hide();
		$("#keyword").attr("disabled",true); //readonly
		$("#ft_commpany").attr("disabled",true);
	}else
		$('#contents_setting_delete').hide();
});


//input-base
var $idTable_base = $('#tbId tbody');
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
	var menu_obj = obj.find('select[name="sel_id[]"]');
	menu_obj.each(function(eq){
		$this = $(this);
		$this.create_idList({
			method : 'selectList',
			value : $this.attr('data-value')
		});
		$this.change(function(){
			var no = $(this).val();
			createIdRow(no, obj);
			menu_obj.attr("data-value", no);
		});
	});
	
	//sel_request
	var menu_obj = obj.find('select[name="sel_request[]"]');
	menu_obj.each(function(eq){
		$this = $(this);
		$this.change(function(){
			var no = $(this).val();
			$(this).attr("data-value", no);
			$(this).prev().attr("value", no);
		});
	});
	
}

//ID select box 선택 시 테이블 변경
function createIdRow(no,obj){
	no = !no ? null : no;
	if(no==null){
		obj.find('.tdPass').text("");
		obj.find('.tdName').text("");
		obj.find('.tdGrade').text("");
		obj.find('.tdBirth').text("");
		//obj.find('.tdUsed').text("");
		obj.find('.tdEmail').text("");
		obj.find('#selId').val("");
	}
	else
		$.ajax({
			type     : 'POST',
			url      : '/groupware/account/selectList/',
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
				obj.find('.tdEmail').text(json[0].email);
				obj.find('select[name="sel_request[]"]').val(json[0].is_using_question).attr("selected", "selected");
				//$('input:radio[name="is_request"][value=' + json[0].is_using_question + ']').attr('checked', 'checked');
				obj.find('#selId').val(json[0].no);
			},error:function(err){
				alert(err.responseText);
				//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
			}
		});	
}

function list(){
	if($("#isActive").val()){
		$('input:radio[name="is_active"][value=' + $("#isActive").val() + ']').attr('checked', 'checked');
	}
	
	chcNo = $("#docNo").text()=="" ? null : $("#docNo").text();
	number = 1;
	$.ajax({
		type     : 'POST',
		data     : {
			chcNo : chcNo
		},
		url      : '/groupware/account/usedList' ,
		dataType : 'json',
		success: function(data){
			console.log(data);
			if(data.result){
				var json   = eval(data.data);
				var clones = '';
				var itemNum = 0;
				for (var i in json){
					var new_row  = $new_row.clone();
					// value setting
					var passCell  = new_row.find('.tdPass');
					var gradeCell     = new_row.find('.tdGrade');
					
					
					passCell.attr('data-value',json[i].pwd);
					gradeCell.attr('data-value',json[i].grade);
					new_row.find('.selId').attr('data-value', json[i].no);
					if(json[i].chc_no > 0){
						new_row.find('.selId').attr('disabled', 'disabled');
					}
					new_row.find('.tdName').attr('data-value', json[i].name);
					new_row.find('.tdBirth').attr('data-value', json[i].birth);
					new_row.find('.tdEmail').attr('data-value', json[i].email);
					new_row.find('.tdDate').attr('data-value', json[i].used);
					new_row.find('.tdUsed').attr('data-value', json[i].is_using_question);
					new_row.find('input[name="is_request[]"]').val(json[i].is_using_question);
					new_row.find('#selId').val(json[i].no);
					new_row.find('.btRm').css('display', 'none');
					clones += new_row.wrapAll('<div>').parent().html();
					itemNum = itemNum + 1;
				}
				
				$idTable_base.html(clones);
				
				setInputVal();
			}else{
				for (var i=0;i<number;i++){
					clones += $new_row.wrapAll('<div>').parent().html();
				}

				$idTable_base.html(clones);
			}
			
			var menu_obj = $idTable_base.find('select[name="sel_id[]"]');
			
			menu_obj.each(function(eq){
				$this = $(this);
				var opt = $this.attr("disabled") ? $this.attr("disabled") : null;
				$this.create_idList({
					method : 'selectList',
					value : $this.attr('data-value'),
					opt : opt
				});
				$this.change(function(){
					var no = $(this).val();
					createIdRow(no, $(this).parent().parent());
					menu_obj.attr("data-value", no);
				});
			});
			//sel_request
			var menu_obj = $idTable_base.find('select[name="sel_request[]"]');
			menu_obj.each(function(eq){
				$this = $(this);
				$this.change(function(){
					var no = $(this).val();
					$(this).attr("data-value", no);
					$(this).prev().attr("value", no);
				});
			});
			
			
			
			
		},error:function(err){
			console.log(err);
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}


function setInputVal(){
	$('.tdPass').each(function(){
		$(this).text( $(this).attr('data-value') );
	});
	$('.tdGrade').each(function(){
		$(this).text( $(this).attr('data-value') );
	});
	$('.tdName').each(function(){
		$(this).text( $(this).attr('data-value') );
	});
	$('.tdBirth').each(function(){
		var date =  $(this).attr('data-value') ? $.datepicker.formatDate('yy-mm-dd', new Date( $(this).attr('data-value'))) : '';
		$(this).text(date);
	});
	$('.tdEmail').each(function(){
		$(this).text( $(this).attr('data-value') );
	});
	$('.tdDate').each(function(){
		var date =  $(this).attr('data-value') ? $(this).attr('data-value') : '';
		$(this).text(date);
	});
	
	$('.tdUsed').each(function(){
		var va = $(this).attr('data-value');
		$(this).find('select[name="sel_request[]"]').find('option[value=' + va + ']').attr('selected', 'selected');
		//$(this).find('input:radio[name="is_request[]"][value=' + va + ']').attr('checked', 'checked');
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
		var $actionType = $("#action_type").val();
		var $opt = options.opt;
		console.log($value);
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
		if($opt == "disabled"){
			$.ajax({
				type     : 'POST',
				url      : url,
				data     : {
					chcNo : $("#no").val()
				},
				dataType : 'json',
				success: function(data){
					console.log(data);
					$data = eval(data.data);
					
					if(tagName=='SELECT'){
						var html = create_option($data);
						element.append(html);
					}
				},error:function(err){
					console.log(err);
					alert(err.responseText);
					//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
				}
			});
		}else{
			$.ajax({
				type     : 'POST',
				url      : url,
				dataType : 'json',
				success: function(data){
					console.log(data);
					if(data.result){
						$data = eval(data.data);
						
						if(tagName=='SELECT'){
							var html = create_option($data);
							element.append(html);
						}
					}else{
						alert(data.data);
					}
				},error:function(err){
					console.log(err);
					alert(err.responseText);
					//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
				}
			});
		}

		function create_option(json_obj){
			var output = '';
			for (var i in json_obj){
                output+='<option value="'+json_obj[i].no+'" '+ ($value==json_obj[i].no?'selected':'') +'>' + json_obj[i].id + '</option>';
            }
			return output;
		}
	}
	
})(jQuery);
