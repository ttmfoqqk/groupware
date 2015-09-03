/***************************************************************************************************
 * 입력 수정창 모델
 * 
 ***************************************************************************************************/

function customModal(msg, title, label, callback){
	bootbox.dialog({
        message: msg,
        title: title,
        buttons: {
          success: {
            label: label,
            className: "btn-success",
            callback: callback
          },
        }
      });
	$('.modal-header').css("background-color","#51bf87 ");
	$('.modal-header').css("color","white");
}

function cbModel(func, arg){
	return function(){
		if(arg != null)
			ret = func(arg);
		else
			ret = func();
		if(ret == null)	//when validate
			return false;
		
		asd = JSON.parse(ret);
		if(asd.result == true){
			alert(asd.data);
			keyList();
			codeList($("#idx").val());
			
			$('.table').checkAll({
				masterCheckbox: '.check-all',
				otherCheckboxes: '.check',
				highlightElement: {
		            active: true,
		            elementClass: 'tr',
		            highlightClass: 'highlight'
		        }
			});
			
			//location.replace("baseCode");
			
		}
		else{
			alert(asd.data);
		}
	}
}

function getCreateKeyTag(values){
	tt = '<form class="form-horizontal group-border stripped" id="validate">';
	tt += getInputTag("<font class='red'>* </font>Key", 'text', 'k_key', values['key'], false, 'k_key');
	tt += getInputTag("<font class='red'>* </font>내용", 'text', 'k_info', values['info'], false, 'k_info');
	tt += getInputTag("순서", 'text', 'k_order', values['order'], false, 'k_order');
	tt += '</form>';
	return tt;
}

function getCreateCodeTag(values){
	tt = '<form class="form-horizontal group-border stripped" id="validate">';
	tt += getInputTag("<font class='red'>* </font>내용", 'text', 'c_info', values['info'], false, 'c_info');
	tt += getInputTag("순서", 'text', 'c_order', values['order'], false, 'c_order');
	tt += '<div class="form-group">	\
			<label class="col-lg-2 col-md-3 control-label" for="order">사용여부</label>\
			<div class="col-lg-10 col-md-9"> \
				<div class="radio-custom radio-inline">	\
		        	<input type="radio" name="use" value="1" id="radio4" checked="checked">	\
		        	<label for="radio4">사용</label>	\
		        </div>	\
		        <div class="radio-custom radio-inline">	\
		        	<input type="radio" name="use" value="2" id="radio5">	\
		        	<label for="radio5">비사용</label>	\
		        </div>	\
			</div></div>';
	tt += '</form>';
	//$(tt).find('input:radio[name="use"][value=' + values['is_active'] + ']').attr('checked', 'checked');
	
	return tt;
}

function getInputTag(label, type, id, value, disable, name, hint){
	if(hint == null)
		hint = '';
	tt = '<div class="form-group"><label class="col-lg-2 col-md-3 control-label" for="' + id + '">'+ label +
	'</label><div class="col-lg-10 col-md-9"><input type="'+ type + '" name="' + name + '" class="form-control" id="'+ id
	+ '" VALUE="' +value+ '" placeholder="' + hint + '"';
	if(disable == true){
		tt += ' disabled';
	}
	tt +='>	</div> </div>';
	
	return tt;
}

/***************************************************************************************************
 * table list
 * 
 ***************************************************************************************************/


function getKeyRow(){
	var tag;
	tag = '<tr class="keyRow" onclick="clickKeyEvt($(this));">	\
		<input type="hidden" class="kNo">	\
		<td class="kOrder text-center"></td>	\
		<td class="kKey text-center"></td>	\
		<td class="kInfo text-center"></td>	\
		<td class="text-center"><div class="btn btn-primary btn-alt btn-xs  kCmd" id="btKeyModify" onclick="createKey($(this));">수정</div></td>\
	</tr>';
	return tag;
}

function getCodeRow(){
	var tag;
	tag = '<tr class="codeRow">	\
				<td>	\
				<div class="checkbox-custom">	\
					<input id="check" name="no[]" class="check" type="checkbox"> <label for="check"></label> <input type="hidden" class="cNo">	\
				</div>	\
				</td>	\
				<td class="cOrder text-center"></td>	\
				<td class="cIdx text-center"></td>	\
				<td class="cInfo text-center"></td>	\
				<td class="cActive text-center"></td>\
				<td class="text-center"><div class="btn btn-primary btn-alt btn-xs  kCmd" id="btCodeModify" onclick="createCode($(this));">수정</div></td>	\
			</tr>';
	return tag;
}

//input-base
var $key_base = $('#keyBody');
var $code_base = $('#codeBody');

//리스트 생성
function keyList(){
	$.ajax({
		type     : 'POST',
		url      : '/groupware/baseCode/keyList/',
		dataType : 'json',
		success: function(data){
			if(data.result){
				var json   = eval(data.data);
				var clones = '';
				for (var i in json){
					var new_row  = $(getKeyRow());
					// value setting
					var order = new_row.find('.kOrder');
					var key = new_row.find('.kKey');
					var info = new_row.find('.kInfo');
					var btModi = new_row.find('.kCmd');
					var no = new_row.find('.kNo');
					
					no.attr('data-value',json[i].no);
					order.text(json[i].order);
					key.text(json[i].key);
					info.text(json[i].name);
					
					clones += new_row.wrapAll('<div>').parent().html();
				}
				$key_base.html(clones);
			}else{
				nodata = '<tr><td colspan="4">등록된 내용이 없습니다.</td></tr>'
				$key_base.html(nodata);
				alert(data.result + ',' + data.data);
			}
		},error:function(err){
			alert(err.responseText);
		}
	});
}

function clickKeyEvt(keyRowObj){
	var no = keyRowObj.find(".kNo").attr('data-value');
	keyRowObj.animate({backgroundColor:'#fffed5'}, {duration: 200 })
	keyRowObj.animate({backgroundColor:'white'}, {duration: 200 })
	
	$("#idx").val(no);
	codeList(no);
}

function codeList(keyNo){
	var no = keyNo;
	
	$.ajax({
		type     : 'POST',
		url      : '/groupware/baseCode/codeList/',
		data     : {
			no  : no
		}, 
		dataType : 'json',
		success: function(data){
			if(data.result){
				var json   = eval(data.data);
				var clones = '';
				for (var i in json){
					var new_row  = $(getCodeRow());
					// value setting
					var order = new_row.find('.cOrder');
					var idx = new_row.find('.cIdx');
					var info = new_row.find('.cInfo');
					var active = new_row.find('.cActive');
					var no = new_row.find('.cNo');
					
					no.attr('data-value',json[i].no);
					order.text(json[i].order);
					idx.text(json[i].parent_key);
					info.text(json[i].name);
					active.text(json[i].is_active);
					
					new_row.find('input[name="no[]"]').val(json[i].no);
					
					clones += new_row.wrapAll('<div>').parent().html();
				}
				$code_base.html(clones);
				
				//jquery.checkAll.js 플러그인 사용. 동적생성 시 처리 못하여서 플러그인 init 부분 수정 후, 사용 중.
				$('.table').checkAll({
					masterCheckbox: '.check-all',
					otherCheckboxes: '.check',
					highlightElement: {
			            active: true,
			            elementClass: 'tr',
			            highlightClass: 'highlight'
			        }
				});
				
				
			}else{
				nodata = '<tr><td colspan="6">등록된 내용이 없습니다.</td></tr>'
				$code_base.html(nodata);
				//alert(data.result + ',' + data.data);
			}
			
		},error:function(err){
			alert(err.responseText);
		}
	});
}


/***************************************************************************************************
 * ready
 * 
 ***************************************************************************************************/
function createKey(btObj){
	btn = btObj.attr("id");
	
	if(btn == "btKeyModify"){
		var parent = btObj.parent().parent();
		var value = new Array();
		value['no'] = parent.find(".kNo").attr('data-value');
		value['order'] = parent.find(".kOrder").text();
		value['key'] = parent.find(".kKey").text();
		value['info'] = parent.find(".kInfo").text();
		
		msg = getCreateKeyTag(value);
		
		var args = new Array();
		args['mode'] = "modify";
		args['no'] = value['no'];
		customModal(msg, "Key 등록", "저장", cbModel(mdCreateKey, args) );
	}else if(btn == "btKeyAdd"){
		var value = new Array();
		value['no'] = value['order'] = value['key'] = value['info'] = "";
		
		msg = getCreateKeyTag(value);
		
		var args = new Array();
		args['mode'] = "create";
		customModal(msg, "Key 등록", "저장", cbModel(mdCreateKey, args) );
	}
}

function mdCreateKey(args){
	var data_info  = new Object();
	data_info.order = $("#k_order").val();
	data_info.key = $("#k_key").val();
	data_info.name = $("#k_info").val();
	if(args.no == null){
		//create
		data_info.method = "create";
	}else{
		//modify
		data_info.method = "modify";
		data_info.no = args.no;
	}
		
	return $.ajax({
		type     : 'POST',
		url      : '/groupware/baseCode/createKey/',
		data     : {
			data  : data_info
		},
		async: false,
		dataType : 'json',
		success: function(data){
		},error:function(err){
		}
	}).responseText;
	
}

function createCode(btObj){
	btn = btObj.attr("id");
	
	var idx = $("#idx").val();
	if(idx == ''){
		alert("Key를 선택 해주세요.");
		return;
	}
	
	if(btn == "btCodeModify"){
		var parent = btObj.parent().parent();
		var value = new Array();
		
		value['no'] = parent.find(".cNo").attr("data-value");
		value['order'] = parent.find(".cOrder").text();
		value['parent_key'] = idx;
		value['info'] = parent.find(".cInfo").text();
		value['is_active'] = parent.find(".cActive").text();
		
		msg = getCreateCodeTag(value);
		
		var args = new Array();
		args['mode'] = "modify";
		args['no'] = value['no'];
		customModal(msg, "코드 등록", "저장", cbModel(mdCreateCode, args) );
		$('input:radio[name="use"][value=' + value['is_active'] + ']').attr('checked', 'checked');
		
	}else if(btn == "btCodeAdd"){
		var value = new Array();
		value['no'] = value['order']  = value['info'] = "";
		value['is_active'] = 1;
		value['parent_key'] = idx;
		
		msg = getCreateCodeTag(value);
		
		var args = new Array();
		args['mode'] = "create";
		args['parent_key'] = idx;
		customModal(msg, "코드 등록", "저장", cbModel(mdCreateCode, args) );
	}else if(btn == "btCodeDel"){
		var nos = new Array();
		$("input[name='no[]']").each(function(){
			if($(this).is(":checked"))
				nos.push($(this).val());
		});
		
		var args = new Object();
		args.method = "remove";
		args.ids = nos;
		
		
		$.ajax({
			type     : 'POST',
			url      : '/groupware/baseCode/createCode/',
			data     : {
				data  : args
			},
			dataType : 'json',
			success: function(data){
				if(data.result){
					alert(data.data);
					keyList();
					codeList($("#idx").val());
					
				}else{
					alert(data.data);
				}
			},error:function(err){
				console.log(err);
			}
		})
		
	}
	
}

function mdCreateCode(args){
	var data_info  = new Object();
	data_info.order = $("#c_order").val();
	data_info.is_active = $("input[name=use]:checked").val();
	data_info.name = $("#c_info").val();
	if(args['mode'] == 'create'){
		//create
		data_info.method = args['mode'];
		data_info.parent_key = args.parent_key;
	}else if(args['mode'] == 'modify'){
		//modify
		data_info.method = args['mode'];
		data_info.no = args.no;
	}
		
	return $.ajax({
		type     : 'POST',
		url      : '/groupware/baseCode/createCode/',
		data     : {
			data  : data_info
		},
		async: false,
		dataType : 'json',
		success: function(data){
		},error:function(err){
		}
	}).responseText;
}


$(document).ready(function() {
	keyList();
});


