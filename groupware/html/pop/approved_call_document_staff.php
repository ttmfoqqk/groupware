<table class="table table-bordered">
	<thead>
		<tr>
			<th class="per20">결재순</th>
			<th>부서선택</th>
			<th class="per20">담당자</th>
		</tr>
	</thead>
	<tbody id="table-list">
		<tr>
			<td>{eq}순위</td>
			<td>{menu}</td>
			<td>{user}</td>
		</tr>
	</tbody>
</table>


<script type="text/javascript">
var $approved_no = '<?echo $_POST["no"];?>';
var $input_base = $('#table-list');
var $new_row    = $input_base.html();
var $data;

function get_list(){
	$.ajax({
		type     : 'POST',
		url      : '/groupware/approved_archive/temp_doc_staff_lists/',
		data     : {
			approved_no : $approved_no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data);
			var html = '';
			var temp = '';

			$data = json;
			if(json.length>0){
				for (var i in json){
					template = $new_row;
					template = template.replace(/{eq}/gi   ,Number(i)+1);
					template = template.replace(/{menu}/gi ,json[i].menu_name);
					template = template.replace(/{user}/gi ,json[i].user_name);

					html += template;
				}
				$input_base.html(html);
			}else{
				$input_base.html('<tr><td colspan="3">등록된 내용이 없습니다.</td></tr>');
			}
			// base div show
			base_show('show');
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}

function application_approved(approved_no){

	base_show('hide');
	$.ajax({
		type     : 'POST',
		url      : '/groupware/approved_archive/staff_insert/',
		data     : {
			approved_no : approved_no,
			json_data   : JSON.stringify( $data )
		},
		dataType : 'json',
		success: function(data){
			if(data.result!='ok'){
				alert(data.result + ',' + data.msg);
			}else{
				base_show('show');
				alert('저장됨');
				location.reload();

			}
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}

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