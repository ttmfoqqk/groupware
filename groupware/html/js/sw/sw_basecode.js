
function getKeyRow(){
	var tag;
	tag = '<tr class="keyRow">	\
		<input type="hidden" class="kNo">	\
		<td class="kOrder text-center"></td>	\
		<td class="kKey text-center"></td>	\
		<td class="kInfo text-center"></td>	\
		<td class="text-center"><div class="btn btn-primary btn-alt btn-xs  kCmd">수정</div></td>\
	</tr>';
	return tag;
}

function getCodeRow(){
	var tag;
	tag = '<tr class="codeRow">	\
		<td>	\
		<div class="checkbox-custom">	\
			<input id="check" name="no[]" class="check" type="checkbox" value="1"> <label for="check"></label> <input type="hidden" class="cNo">	\
		</div>	\
		</td>	\
		<td class="cOrder text-center"></td>	\
		<td class="cIdx text-center"></td>	\
		<td class="cInfo text-center"></td>	\
		<td class="cActive text-center"></td>\
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


function codeList(){
	$.ajax({
		type     : 'POST',
		url      : '/groupware/baseCode/codeList/',
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
					var active = new_row.find('.kActive');
					var no = new_row.find('.cNo');
					
					no.attr('data-value',json[i].no);
					order.text(json[i].order);
					idx.text(json[i].parent_key);
					info.text(json[i].name);
					active.text(json[i].is_active);
					
					clones += new_row.wrapAll('<div>').parent().html();
				}
				$code_base.html(clones);
			}else{
				nodata = '<tr><td colspan="5">등록된 내용이 없습니다.</td></tr>'
				$code_base.html(nodata);
				alert(data.result + ',' + data.data);
			}
		},error:function(err){
			alert(err.responseText);
		}
	});
}


keyList();
codeList();