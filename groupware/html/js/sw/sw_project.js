$(document).ready(function() {
	$(".input-daterange").datepicker();
	/* 리스트 페이지 */
	
	// 부서,분류 selectbox
	var $menu_part_no = $('#menu_part_no');
	var $menu_no      = $('#menu_no');
	$menu_part_no.create_menu({
		method : $menu_part_no.attr('data-method'),
		value : $menu_part_no.attr('data-value')
	});
	$menu_no.create_menu({
		method : $menu_no.attr('data-method'),
		value : $menu_no.attr('data-value')
	});
	var action_type = $('#action_type');
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
		set_btn_datepicker(sData,eData,-0);
	});
	btn_sWeek.click(function(){
		set_btn_datepicker(sData,eData,-7);
	});
	btn_sMonth.click(function(){
		set_btn_datepicker(sData,eData,-30);
	});
	btn_sReset.click(function(){
		set_btn_datepicker(sData,eData,null);
	});
	
	// 등록일자 datepicker
	btn_swToday.click(function(){
		set_btn_datepicker(swData,ewData,-0);
	});
	btn_swWeek.click(function(){
		set_btn_datepicker(swData,ewData,-7);
	});
	btn_swMonth.click(function(){
		set_btn_datepicker(swData,ewData,-30);
	});
	btn_swReset.click(function(){
		set_btn_datepicker(swData,ewData,null);
	});

	$('#btn_list_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					action_type.val('delete');
					$('#project-form-list').submit();
				}
		    }
		});
	});
	/* 리스트 페이지 */

	/* 쓰기 페이지 */
	// 진행기간 btn
	var btn_WsToday = $('#WsToday');
	var btn_WsWeek  = $('#WsWeek');
	var btn_WsMonth = $('#WsMonth');
	var btn_WsReset = $('#WsReset');

	btn_WsToday.click(function(){
		set_btn_datepicker(sData,eData,+0);
	});
	btn_WsWeek.click(function(){
		set_btn_datepicker(sData,eData,+7);
	});
	btn_WsMonth.click(function(){
		set_btn_datepicker(sData,eData,+30);
	});
	btn_WsReset.click(function(){
		set_btn_datepicker(sData,eData,null);
	});

	$('#contents_setting_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					action_type.val('delete');
					$('#project-form-write-setting').submit();
				}
		    }
		});
	});


	var date = new Date();
	var year  = pad(date.getFullYear());
	var month = pad(date.getMonth() + 1);
	var day   = pad(date.getDate());
	var yyyymmdd = year +'-'+ month +'-'+ day;
	
	var tmp_sData_value = sData.val();
	var tmp_eData_value = eData.val();

	var tmp_sData = '';
	var tmp_eData = '';

	sData.on('changeDate',function(){
		if( !sData.val() ){return false;}
		if( tmp_sData == $(this).val() ){return false;}
		
		if( $(this).val() < yyyymmdd  &&  action_type.val() == 'create'){
			alert( '현재날짜보다 전일은 등록하실 수 없습니다.' );
			sData.datepicker('setDate', "");
			tmp_sData = $(this).val();
			return false;
		}

		if( $(this).val() != tmp_sData_value  &&  action_type.val() == 'edit'){
			alert( '변경된 기간의 점수를 반영해주세요.' );
			tmp_sData = $(this).val();
			return false;
		}

	});

	
	eData.on('changeDate',function(){
		if( !eData.val() ){return false;}
		if( tmp_eData == $(this).val() ){return false;}
		
		if( $(this).val() < yyyymmdd  &&  action_type.val() == 'create'){
			alert( '현재날짜보다 전일은 등록하실 수 없습니다.' );
			eData.datepicker('setDate', "");
			tmp_eData = $(this).val();
			return false;
		}

		if( $(this).val() != tmp_eData_value  &&  action_type.val() == 'edit'){
			alert( '변경된 기간의 점수를 반영해주세요.' );
			tmp_eData = $(this).val();
			return false;
		}
	});

	

	
	/* 쓰기 페이지 */

	

});


/* 담당자 팝업 */
function staff_modal(no,callback){
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
	$('#modal-body').load('/groupware/html/pop/project_staff.php',{'no':no});
}

function call_staff(no){
	staff_modal(no,function(){
		// 팝업창 내부 함수
		modal_submit();
		return false;
	});
}


// 결재진행중인 업무 담당자 출력
function call_staff_view(no){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';

	bootbox.dialog({
		message: test_html,
		title: '담당자',
		buttons: {
			cancel: {
				label: '닫기',
				className: "btn-danger"
			}
		}
	});
	$('.modal-header').css("background-color","#51bf87 ");
	$('.modal-header').css("color","white");
	
	$('#modal-body').load('/groupware/html/pop/approved_call_project_staff.php',{'no':no});
}
/* 담당자 팝업 */


/* 규정 팝업 */
function call_document_modal(){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';

	bootbox.dialog({
		message: test_html,
		title: '서식선택',
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
	$('#modal-body').load('/groupware/html/pop/project_call_document.php',function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success"){
			$(this).find('.input-daterange').datepicker();
			
			var sData  = $(this).find('#pop_sData');
			var eData  = $(this).find('#pop_eData');
			
			var btn_sToday = $(this).find('#sToday');
			var btn_sWeek  = $(this).find('#sWeek');
			var btn_sMonth = $(this).find('#sMonth');
			var btn_sReset = $(this).find('#sReset');
			
			
			btn_sToday.click(function(){
				set_btn_datepicker(sData,eData,-0);
			});
			btn_sWeek.click(function(){
				set_btn_datepicker(sData,eData,-7);
			});
			btn_sMonth.click(function(){
				set_btn_datepicker(sData,eData,-30);
			});
			btn_sReset.click(function(){
				set_btn_datepicker(sData,eData,null);
			});
			
		}
	});
}