<table class="table table-bordered">
	<thead>
		<tr>
			<th class="per15">분류</th>
			<th>권한</th>
		</tr>
	</thead>
	<tbody id="table-list">
		<tr class="pop-row">
			<td>{title}</td>
			<td style="padding:0px 0px 0px 20px;">
				<input type="hidden" name="category" value="{category}">
				<!--{for}-->
				<div class="toggle-custom toggle-inline">
					<label class="toggle" data-on="ON" data-off="OFF">
						<input type="checkbox" id="{for_id}" name="{for_name}" value="{for_value}" {for_checked}>
						<span class="button-checkbox"></span>
					</label>
					<label for="{for_id}">{for_label_title}</label>
				</div>
				<!--{/for}-->
			</td>
		</tr>
	</tbody>
</table>


<script type="text/javascript">
$no              = '<?echo $_POST["no"];?>';
$table_list      = $('#table-list');
$list_template   = $table_list.html();

$option_template = $list_template.split('<!--{for}-->')[1];
$option_template = $option_template.split('<!--{/for}-->')[0];

$list_template   = $list_template.split('<!--{for}-->')[0] + '{for_block}' + $list_template.split('<!--{/for}-->')[1];


function get_list(){
	base_show('hide');

	$.ajax({
		type     : 'POST',
		url      : '/groupware/member/permission_lists/',
		data     : {
			no : $no
		},
		dataType : 'json',
		success: function(data){
			var json = eval(data);
			var html = '';
			var template = '';
			
			if(json.length>0){
				for (var i in json){
					
					var category     = json[i].category;
					var title        = json[i].title;
					var permission   = json[i].permission.split('|');
					var u_permission = '';

					if(!json[i].u_permission || json[i].u_permission.toUpperCase()=='NULL'){
						u_permission = '';
					}else{
						u_permission = json[i].u_permission.split('|');
					}
					
					var for_html     = '';
					var for_template = '';
					for(i=0; i<permission.length; i++){
						var label_title = get_label_title( permission[i] );
						
						var is_checked  = '';
						for(k=0; k<u_permission.length; k++){
							if( u_permission[k]==permission[i] ){
								is_checked = 'checked';
							}
						}

						for_template = $option_template;
						for_template = for_template.replace(/{for_id}/gi    ,category+'_'+permission[i]);
						for_template = for_template.replace(/{for_name}/gi  ,category);
						for_template = for_template.replace(/{for_value}/gi ,permission[i]);
						for_template = for_template.replace(/{for_checked}/gi ,is_checked);
						for_template = for_template.replace(/{for_label_title}/gi ,label_title);

						for_html += for_template;
					}


					template = $list_template;
					template = template.replace(/{title}/gi     ,title);
					template = template.replace(/{category}/gi  ,category);
					template = template.replace(/{for_block}/gi ,for_html);
					html += template;
				}
				$table_list.html(html);
				$table_list.find('a').bind('click',function(e){
					e.preventDefault();
					var eq = $(this).attr('data-eq');
					onlick(list[eq]);
				});
			}else{
				$table_list.html('<tr><td colspan="2">등록된 내용이 없습니다.</td></tr>');
			}
			base_show('show');
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}

function modal_submit(){
	var data_array = new Array();

	$('.pop-row').each(function(eq){
		var data_info  = new Object();
		var category = $(this).find('input[name="category"]');
		var checkbox = $(this).find('input:checkbox:checked');

		var permission = '';
		if( checkbox.length>0 ){
			checkbox.each(function(eq){
				permission += $(this).val() + (eq+1 < checkbox.length ? '|' : '');
			});

			data_info.category   = category.val();
			data_info.permission = permission;
			data_array.push(data_info);
		}
	});
	
	$.ajax({
		type : 'POST',
		url  : '/groupware/member/permission_insert/',
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

function get_label_title(code){
	var label_title = '';
	switch(code) {
		case 'R':
			label_title = '조회';
			break;
		case 'W':
			label_title = '등록';
			break;
		case 'EX':
			label_title = '엑셀';
			break;
		case 'PR':
			label_title = '프린트';
			break;
		default:
			label_title = '-';
	}
	return label_title;
}

// 리스트 호출
get_list();
</script>