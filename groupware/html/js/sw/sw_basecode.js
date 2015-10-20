/*
 * html 템플릿
 */
$tags = new Array();

$tags['list_nothing'] = '<tr><td colspan="6">등록된 내용이 없습니다.</td></tr>';
$tags['list_loading'] = '<tr><td colspan="6">로딩중 입니다.</td></tr>';
$tags['list'] = ''+ 
'<tr class="data-row" data-no="{no}" data-key="{key}" data-name="{name}" data-order="{order}" data-active="{is_active}">'+
	'<td>'+
		'<div class="checkbox-custom">'+
			'<input id="no" name="no[]" class="check" type="checkbox" value="{no}">'+
			'<label for="check"></label>'+
		'</div>'+
	'</td>'+
	'<td class="text-center">{order}</td>'+
	'<td class="text-center">{key}</td>'+
	'<td class="text-center">{name}</td>'+
	'<td class="text-center">{active}</td>'+
	'<td class="text-center"><button class="btn btn-danger btn-xs">수정</button></td>'+
'</tr>';


$tags['modal'] = ''+
'<form class="form-horizontal group-border stripped">'+
	'<input type="hidden" id="modal_no" name="modal_no" value="{no}">'+
	'<input type="hidden" id="modal_action" name="modal_action" value="{modal_action}">'+
	'<div class="form-group">'+
		'<label class="col-lg-2 col-md-3 control-label" for="modal_key"><font class="red">* </font>KEY</label>'+
		'<div class="col-lg-10 col-md-9"><input id="modal_key" name="modal_key" type="text" class="form-control" value="{key}" {disable}></div>'+
	'</div>'+
	'<div class="form-group">'+
		'<label class="col-lg-2 col-md-3 control-label" for="modal_name"><font class="red">* </font>내용</label>'+
		'<div class="col-lg-10 col-md-9"><input id="modal_name" name="modal_name" type="text" class="form-control" value="{name}"></div>'+
	'</div>'+
	'<div class="form-group">'+
		'<label class="col-lg-2 col-md-3 control-label" for="modal_order">순서</label>'+
		'<div class="col-lg-10 col-md-9"><input id="modal_order" name="modal_order" type="text" class="form-control input-mini" value="{order}"></div>'+
	'</div>'+
	'<div class="form-group">'+
		'<label class="col-lg-2 col-md-3 control-label" for="order">사용여부</label>'+
		'<div class="col-lg-10 col-md-9">'+
			'<div class="radio-custom radio-inline">'+
				'<input type="radio" name="modal_active" value="0" id="radio4" {active_true}>'+
				'<label for="radio4">사용</label>'+
			'</div>'+
			'<div class="radio-custom radio-inline">'+
				'<input type="radio" name="modal_active" value="1" id="radio5" {active_false}>'+
				'<label for="radio5">비사용</label>'+
			'</div>'+
		'</div>'+
	'</div>'+
'</form>';


/***
 * 리스트 출력
 * @param m : 키 타입
 * @returns : html 생성
 */
function codeList(m,key){
	var obj;
	var param = '';
	if( m == 'key'){
		obj = $keyBody;
	}else if( m == 'code'){
		if( !key ){
			alert('Key를 선택 해주세요.');
			return false;
		}
		obj = $codeBody;
		param = key;
	}else{
		alert('잘못된 요청입니다.');
		return false;
	}
	obj.html($tags['list_loading']);

	$.ajax({
		type : 'GET',
		url  : '/groupware/baseCode/lists',
		data : {
			key : param
		},
		dataType : 'json',
		success: function(data){
			var json = eval(data);
			if(json.length>0){
				var html = '';
				var temp = '';
				var template = $tags['list'];
				
				for (var i in json){
					var active = json[i].is_active == 0 ? '사용' : '미사용';
					
					temp = template;
					temp = temp.replace(/{no}/gi         ,json[i].no);
					temp = temp.replace(/{key}/gi        ,m == 'key'?json[i].key:json[i].no );
					temp = temp.replace(/{parent_key}/gi ,json[i].parent_key);
					temp = temp.replace(/{order}/gi      ,json[i].order);
					temp = temp.replace(/{name}/gi       ,json[i].name);
					temp = temp.replace(/{is_active}/gi  ,json[i].is_active);
					temp = temp.replace(/{active}/gi     ,active);
					html += temp;
				}
				
				obj.html( html );
				settingClick(obj,m);
			}else{
				var html = $tags['list_nothing'];
				obj.html( html );
			}
		},error:function(err){
			console.log(err);
		}
	});
}

/**
 * 코드 입력,수정
 * @param m
 */
function CodeSubmit(m){
	var modal_action = $('#modal_action').val();
	var modal_no     = $('#modal_no').val();
	var modal_key    = $('#modal_key').val();
	var modal_name   = $('#modal_name').val();
	var modal_order  = $('#modal_order').val();
	var modal_active = $('input[name="modal_active"]:checked').val();
	
	if( modal_action == 'update' && !modal_no ){
		alert('잘못된 요청입니다.');return false;
	}
	if( !modal_key ){
		alert('Key를 입력하세요.');return false;
	}
	if( !modal_name ){
		alert('내용을 입력하세요.');return false;
	}

	var params = {
		modal_action : modal_action,
		modal_no     : modal_no    ,
		modal_key    : modal_key   ,
		modal_name   : modal_name  ,
		modal_order  : modal_order ,
		modal_active : modal_active,
		code_type    : m
	};
	ajax_proc(params,function(){
		if(m == 'key'){
			$codeBody.attr('data-key','').html($tags['list_nothing']);
		}
		var key = $codeBody.attr('data-key');
		codeList(m,key);
		bootbox.hideAll();
	});
}
/***
 * 삭제
 * @param m
 * @returns {Boolean}
 */
function deleteCode(obj,m){
	var checkbox = obj.find('input[name="no[]"]:checked');
	var no = new Array();
	checkbox.each(function(){
		no.push($(this).val());
	});
	if( no.length <=0 ){
		alert('삭제할 아이템을 선택하세요.');
		return false;
	}
	
	var params = {
		modal_action : 'delete',
		modal_no     : no ,
		code_type    : m
	};
	ajax_proc(params,function(){
		if(m == 'key'){
			$codeBody.attr('data-key','').html($tags['list_nothing']);
		}
		var key = $codeBody.attr('data-key');
		codeList(m,key);
	});
}

/**
 * 
 * @param params : {key:value}
 * @param callback
 */
function ajax_proc(params,callback){
	$.ajax({
		type : 'POST',
		url  : '/groupware/baseCode/proc',
		data : params,
		dataType : 'json',
		success: function(data){
			if(data.result!='ok'){
				alert(data.result + ',' + data.msg);
			}else{
				callback();
			}
		},error:function(err){
			console.log(err);
		}
	});
}


/**
 * tr 클릭 모션 생성
 * @param obj : 부모 tbody
 * @param m : 키 타입
 */
function settingClick(obj,m){
	obj.find('tr').click(function(){
		obj.find('.success').removeClass('success');
		$(this).addClass('success');
		
		if(m == 'key'){
			var key = $(this).attr('data-key');
			$codeBody.attr('data-key',key);
			codeList('code',key);
		}
	});
	
	obj.find('button').click(function(){
		updateCode($(this),m);
	});	
	
	
	var table = obj.parents('table');
	$(table).checkAll({
		masterCheckbox: '.check-all',
		otherCheckboxes: '.check',
		highlightElement: {
            active: true,
            elementClass: 'tr',
            highlightClass: 'highlight'
        }
	});
}


/***
 * 모달창 html 템플릿 치환후 모달 호출
 * @param m
 * @returns {Boolean}
 */
function insertCode(m){
	var html = $tags['modal'];
	var title = '';
	if( m == 'key'){
		title = 'KEY 생성';
		html = html.replace(/{key}/gi     ,'');
		html = html.replace(/{disable}/gi ,'');
	}else if( m == 'code'){
		key = $codeBody.attr('data-key');
		if( !key ){
			alert('Key를 선택 해주세요.');
			return false;
		}
		title = 'CODE 생성';
		html = html.replace(/{key}/gi     ,key);
		html = html.replace(/{disable}/gi ,'disabled');
	}else{
		alert('잘못된 요청입니다.');
		return false;
	}
	html = html.replace(/{modal_action}/gi ,'create');
	html = html.replace(/{no}/gi           ,'');
	html = html.replace(/{name}/gi         ,'');
	html = html.replace(/{order}/gi        ,'0');
	html = html.replace(/{active_true}/gi  ,'checked');
	html = html.replace(/{active_false}/gi ,'');
	
	viewModal( html , title , function(){CodeSubmit(m);return false;} );
}

/***
 * 모달창 html 템플릿 치환후 모달 호출
 * @param m
 * @returns {Boolean}
 */
function updateCode(obj,m){
	var html   = $tags['modal'];
	var title  = '';
	var parent = obj.parents('tr');
	
	var is_active = parent.attr('data-active');
	html = html.replace(/{modal_action}/gi ,'update');
	html = html.replace(/{no}/gi           ,parent.attr('data-no') );
	html = html.replace(/{key}/gi          ,parent.attr('data-key'));
	html = html.replace(/{disable}/gi      ,'disabled');
	html = html.replace(/{name}/gi         ,parent.attr('data-name'));
	html = html.replace(/{order}/gi        ,parent.attr('data-order'));
	html = html.replace(/{active_true}/gi  ,is_active=='0'?'checked':'');
	html = html.replace(/{active_false}/gi ,is_active=='1'?'checked':'');
	
	viewModal( html , title , function(){CodeSubmit(m);return false;} );
}



/***
 * 모달창 생성
 * @param msg : html 템플릿
 * @param title
 * @param callback
 */
function viewModal( msg ,  title, callback){
	bootbox.dialog({
		message : msg,
		title   : title,
		buttons : {
			cancel: {
				label: '닫기',
				className: "btn-danger"
			},
			success: {
				label: '저장',
				className: "btn-success",
				callback: callback
			}			
		}
	});
	$('.modal-header').css("background-color","#51bf87 ");
	$('.modal-header').css("color","white");
}


$(document).ready(function() {
	$keyBody      = $('#keyBody');
	$codeBody     = $('#codeBody');
	
	$btKeyAdd     = $('#btKeyAdd');
	$btKeyRemove  = $('#btKeyRemove');
	$btCodeAdd    = $('#btCodeAdd');
	$btCodeRemove = $('#btCodeRemove');
	
	codeList('key');
	
	$btKeyAdd.click(function(){
		insertCode('key')
	});
	$btKeyRemove.click(function(){
		deleteCode($keyBody,'key')
	});
	$btCodeAdd.click(function(){
		insertCode('code')
	});
	$btCodeRemove.click(function(){
		deleteCode($codeBody,'code')
	});
});






