<form class="form-horizontal">
<div class="row form-group ">
	<label class="col-lg-2 col-md-2 control-label" for="">진행기간</label>
	<div class="col-lg-6 col-md-6">
		<div class="input-daterange input-group">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" class="form-control" name="sData" id="sData" value="" />
			<span class="input-group-addon">to</span>
			<input type="text" class="form-control" name="eData" id="eData" value=""/>
		</div>
	</div>
	<div class="col-lg-4 col-md-4">
		<button type="button" class="btn btn-sm btn-primary btn-alt" id="sToday">오늘</button>
		<button type="button" class="btn btn-sm btn-primary btn-alt" id="sWeek">7일</button>
		<button type="button" class="btn btn-sm btn-primary btn-alt" id="sMonth">30일</button>
		<button type="button" class="btn btn-sm btn-primary btn-alt" id="sReset">날짜초기화</button>
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

function get_list( pagination ){
	if( !pagination || pagination == undefined ){
		pagination = 1;
	}
	// parameters 생성 url 적용 보내기
	// 등록가능한 업무중 1차 결재요청자 의 업무 검색
	var list_url   = '/groupware/document/lists/';
	var parameters = '?sData=&eData=&menu_no=&userName=&title=&is_active=0';	
	
	list_url  += pagination;
	list_url  += parameters;	

	base_show('hide');

	$.ajax({
		type     : 'POST',
		url      : list_url,
		data     : {
			sData : ''
		},
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
	show_paper('document');

	$('#task_no').val(no);
	$('#d_title').val( title );
	$('#d_contents').val( contents );
	$('#document_name').text(title);

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