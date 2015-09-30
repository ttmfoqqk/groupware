<form class="form-horizontal" id="pop_document_form">
	<div class="row form-group ">
		<label class="col-lg-2 col-md-2 control-label" for="">등록일자</label>
		<div class="col-lg-6 col-md-6">
			<div class="input-daterange input-group">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" class="form-control" name="pop_sData" id="pop_sData" value="" />
				<span class="input-group-addon">to</span>
				<input type="text" class="form-control" name="pop_eData" id="pop_eData" value=""/>
			</div>
		</div>
		<div class="col-lg-4 col-md-4">
			<button type="button" class="btn btn-sm btn-primary btn-alt" id="sToday">오늘</button>
			<button type="button" class="btn btn-sm btn-primary btn-alt" id="sWeek">7일</button>
			<button type="button" class="btn btn-sm btn-primary btn-alt" id="sMonth">30일</button>
			<button type="button" class="btn btn-sm btn-primary btn-alt" id="sReset">날짜초기화</button>
		</div>
	</div>
	<div class="form-group col-lg-12 col-md-12">
		<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
		<div class="col-lg-3 col-md-3">
			<select class="fancy-select form-control" id="pop_menu_no" name="pop_menu_no" data-method="document">
				<option value="">선택</option>
			</select>
		</div>
		<label class="col-lg-2 col-md-2 control-label" for="">등록자</label>
		<div class="col-lg-3 col-md-3">
			<input type="text" class="form-control" id="pop_name" name="pop_name" placeholder="등록자" value="">
		</div>
	</div>
	<div class="form-group col-lg-12 col-md-12">
		<label class="col-lg-2 col-md-2 control-label" for="">서식명</label>
		<div class="col-lg-8 col-md-8">
			<input type="text" class="form-control" id="pop_title" name="pop_title" placeholder="제목">
		</div>
		<div class="col-lg-2 col-md-2">
			<button type="submit" class="btn btn-primary btn-alt mr5 mb10">검 색</button>
		</div>
	</div>
</form>

<table class="table table-bordered">
	<thead>
		<tr>
			<th class="per10">분류</th>
			<th>서식명</th>
			<th class="per10">등록일자</th>
			<th class="per10">등록자</th>
		</tr>
	</thead>
	<tbody id="table-list">
		<tr>
			<td><a href="" data-eq="{eq}" class="text-normal">{menu}</a></td>
			<td><a href="" data-eq="{eq}" class="text-normal">{title}</a></td>
			<td><a href="" data-eq="{eq}" class="text-normal">{created}</a></td>
			<td><a href="" data-eq="{eq}" class="text-normal">{user}</a></td>
		</tr>
	</tbody>
</table>

<div class="panel-body" id="pagination" style="text-align:center;"></div>

<script type="text/javascript">
$pagination    = $('#pagination');
$table_list    = $('#table-list');
$list_template = $table_list.html();


$pop_menu_no = $('#pop_menu_no');
$pop_sData   = $('#pop_sData');
$pop_eData   = $('#pop_eData');
$pop_name    = $('#pop_name');
$pop_title   = $('#pop_title');

$pop_menu_no.create_menu({
	method : $pop_menu_no.attr('data-method'),
	value  : $pop_menu_no.attr('data-value')
});

$('#pop_document_form').submit(function(){
	get_list();
	return false;
});

function get_list( pagination ){
	if( !pagination || pagination == undefined ){
		pagination = 1;
	}
	// parameters 생성 url 적용 보내기
	// 등록가능한 업무중 1차 결재요청자 의 업무 검색
	var list_url   = '/groupware/document/lists/';
	var parameters = '?ft_start=' + $pop_sData.val() + '&ft_end=' + $pop_eData.val() + '&ft_document=' + $pop_menu_no.val() + '&ft_pop_name=' + $pop_name.val() + '&ft_title=' + $pop_title.val() + '&ft_active=0';
	
	list_url  += pagination;
	list_url  += parameters;	

	base_show('hide');

	$.ajax({
		type     : 'POST',
		url      : list_url,
		dataType : 'json',
		success: function(data){
			var json = eval(data);
			var list = json.list;
			var pagination = json.pagination;
			var html = '';
			var temp = '';
			
			if(list.length>0){
				for (var i in list){
					var no      = list[i].no;
					var menu    = list[i].menu_name;
					var title   = list[i].name;
					var active  = list[i].is_active;
					var created = list[i].created;
					var user    = list[i].user_name;

					template = $list_template;
					template = template.replace(/{eq}/gi      ,i);
					template = template.replace(/{menu}/gi    ,menu);
					template = template.replace(/{title}/gi   ,title);
					template = template.replace(/{created}/gi ,created.substring(0,10));
					template = template.replace(/{user}/gi    ,user);

					html += template;
				}
				$table_list.html(html);
				$table_list.find('a').bind('click',function(e){
					e.preventDefault();
					var eq = $(this).attr('data-eq');
					onlick(list[eq]);
				});

				$pagination.html(pagination);
				$pagination.find('a').bind('click',function(e){
					e.preventDefault();
					var p = $(this).attr('href').replace('/','');
					get_list(p);
				});
			}else{
				$table_list.html('<tr><td colspan="7">할당된 업무가 없습니다.</td></tr>');
				$pagination.html(pagination);
			}

			base_show('show');
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}

function onlick(data){
	for (var i in data){
		var no      = data.no;
		var menu    = data.menu_name;
		var title   = data.name;
		var contents= data.contents;
		var file    = data.file;
		var created = data.created;
	}

	$('#document_input').val( title );
	$('#contents').val( contents );

	bootbox.hideAll();
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

// 리스트 호출
get_list();
</script>