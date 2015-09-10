$(document).ready(function() {
	$(".input-daterange").datepicker({format: 'yyyy-mm-dd'});
	/* 리스트 페이지 */
	
	// 부서,분류 selectbox
	var $part_sender   = $('#part_sender');
	var $part_receiver = $('#part_receiver');
	var $menu_no       = $('#menu_no');
	var $d_department  = $('#d_department');
	
	
	if($part_sender.length>0){
		$part_sender.create_menu({
			method : $part_sender.attr('data-method'),
			value  : $part_sender.attr('data-value')
		});
	}

	if($part_receiver.length>0){
		$part_receiver.create_menu({
			method : $part_receiver.attr('data-method'),
			value  : $part_receiver.attr('data-value')
		});
	}
	if($d_department.length>0){
		$d_department.create_menu({
			method : $d_department.attr('data-method'),
			value  : $d_department.attr('data-value')
		});
	}
	
	if($part_receiver.length>0 && $menu_no.length>0){
		var $menu_no_method = $menu_no.attr('data-method').split(',');
		
		$menu_no.create_menu({
			method : $menu_no_method[0],
			value  : $menu_no.attr('data-value'),
			callback : function(){
				if($menu_no_method.length>1){
					// 서식 자료 추가 , 구분자 추가
					$menu_no.append('<option value="">========================</option>');
					$menu_no.create_menu({
						method : $menu_no_method[1],
						value  : $menu_no.attr('data-value')
					});
				}
			}
		});
	}
	

	// 진행기간,등록일자 input #진행기간[sData,eData] 은 write 에서도 사용
	var sData  = $('#sData');
	var eData  = $('#eData');
	var swData = $('#swData');
	var ewData = $('#ewData');

	
	// 진행기간 btn
	var btn_sToday = $('#sToday');
	var btn_sWeek  = $('#sWeek');
	var btn_sMonth = $('#sMonth');
	var btn_sReset = $('#sReset');
	// 등록일자 btn
	var btn_swToday = $('#swToday');
	var btn_swWeek  = $('#swWeek');
	var btn_swMonth = $('#swMonth');
	var btn_swReset = $('#swReset');

	// 진행기간 datepicker
	btn_sToday.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_sWeek.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 6);
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_sMonth.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 29);
		sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_sReset.click(function(){
		sData.datepicker('setDate', "");
		eData.datepicker('setDate', "");
	});
	
	// 등록일자 datepicker
	btn_swToday.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		swData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		ewData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_swWeek.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 6);
		swData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		ewData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_swMonth.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 29);
		swData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		ewData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	btn_swReset.click(function(){
		swData.datepicker('setDate', "");
		ewData.datepicker('setDate', "");
	});

	$('#btn_list_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#approved-form-list').submit();
				}
		    }
		});
	});
	/* 리스트 페이지 */

	/* 쓰기 페이지 */
	var d_sData     = $('#d_sData');
	var d_eData     = $('#d_eData');
	var d_swData    = $('#d_swData');
	var d_ewData    = $('#d_ewData');
	// 진행기간 btn
	var btn_WsToday = $('#WsToday');
	var btn_WsWeek  = $('#WsWeek');
	var btn_WsMonth = $('#WsMonth');
	var btn_WsReset = $('#WsReset');

	btn_WsToday.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		d_sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		d_eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', myDate));
	});
	btn_WsWeek.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth + 6);
		d_sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		d_eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', myDate));
	});
	btn_WsMonth.click(function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth + 29);
		d_sData.datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', new Date()));
		d_eData.datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', myDate));
	});
	btn_WsReset.click(function(){
		d_sData.datepicker('setDate', "");
		d_eData.datepicker('setDate', "");
	});

	$('#approved_write_send').on('click',function(){
		bootbox.confirm({
			message: "결재 요청하시겠습니까?",
			title: "결재 요청하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('send');
					$('#approved-form-send').submit();
				}
		    }
		});
	});

	$('#approved_write_receive').on('click',function(){
		bootbox.confirm({
			message: "결재 하시겠습니까?",
			title: "결재 하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					if( !$('#status').val() ){
						alert('결재 상태를 선택하세요.');
					}else{
						$('#action_type').val('receive');
						$('#approved-form-send').submit();
					}
				}
		    }
		});
	});

	$('#call_approved_kind').click(function(){
		var kind = $('#approved_kind').val();
		if(kind == '0'){
			call_data_modal('project');
		}else{
			call_data_modal('document');
		}
	});
	/* 쓰기 페이지 */

});


function show_paper(mode){
	if(mode=='project'){
		$('#project-layout').show();
		$('#document-layout').hide();
	}else if(mode=='document'){
		$('#project-layout').hide();
		$('#document-layout').show();
	}
}

/* 문서 팝업 */
function call_data_modal(mode){
	var load_url   = '';
	var load_title = '';
	if( mode=='project' ){
		load_url   = '/groupware/html/pop/approved_call_project.php';
		load_title = '업무선택';
	}else if( mode=='document' ){
		load_url = '/groupware/html/pop/approved_call_document.php';
		load_title = '서식선택';
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


/* 결재요청 팝업 */
function call_project_staff_modal(no,kind,callback){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';

	bootbox.dialog({
		message: test_html,
		title: '결재자',
		buttons: {
			cancel: {
				label: '닫기',
				className: "btn-danger"
			},
			success: {
				label: '결재 등록',
				className: "btn-success",
				callback: callback
			}
		}
	});
	$('.modal-header').css("background-color","#51bf87 ");
	$('.modal-header').css("color","white");
	
	// 팝업 호출
	if(kind==1){
		$('#modal-body').load('/groupware/html/pop/approved_call_document_staff.php',{'no':no});
	}else{
		$('#modal-body').load('/groupware/html/pop/approved_call_project_staff.php',{'no':no});
	}
}

function call_project_staff(project_no,approved_no,kind){
	var no = kind=='0' ? project_no : approved_no;
	call_project_staff_modal(no,kind,function(){
		if(confirm('결재를 등록하시겠습니까?')){
			application_approved(approved_no);
		}else{
			return false;
		}
	});
}


/* 일반업무 담당자등록 팝업 */
function document_staff_modal(no,callback){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';

	bootbox.dialog({
		message: test_html,
		title: '담당자',
		buttons: {
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
	$('.modal-dialog').addClass('modal70');

	// 팝업 호출
	$('#modal-body').load('/groupware/html/pop/approved_document_staff.php',{'no':no});
}

function document_staff(no){
	document_staff_modal(no,function(){
		// 팝업창 내부 함수
		modal_submit();
		return false;
	});
}