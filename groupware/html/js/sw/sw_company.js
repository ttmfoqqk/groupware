var koDatePickerOpt = {language : 'ko',  format: 'yyyy-mm-dd',  todayHighlight:true}; 	//dataPicker option (korean)
var enDatePickerOpt = {format: 'yyyy-mm-dd',  todayHighlight:true};						//dataPicker option (english)


function cbManageUser(func, arg){
	return function(){
		if(arg != null)
			ret = func(arg);
		else
			ret = func();
		
		if(ret == null)	//when validate
			return false;
		
		asd = JSON.parse(ret);
		if(asd.success == true)
			loadPage(currentPage);
		else{
			alertModal(asd.data);
		}
	}
}

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


/**
 * 세로형태 폼 디자인
 */
function testTag2(){
	
}

/**
 * 가로 형태 폼 디자인
 * 칼럼이름 리스트, 체울 데이터 리스트, 각 칼럼 길이 세팅, 칼럼 인풋박스 스타일 (input, combobox, 달력)
 * @returns {String}
 */
function testTag(){
	//칼럼 이름.
	tt = '<form class="form-horizontal group-border stripped" id="validate"> \
		<div class="row"><div clas="form-group col-lg-12 col-md-12"> <label class="col-lg-1 col-md-1 col-sm-1 control-label">순서</label> \
		<label class="col-lg-1 col-md-1 col-sm-1 control-label">담당자</label> <label class="col-lg-1 col-md-1 col-sm-1 control-label">부서</label> \
		<label class="col-lg-1 col-md-1 col-sm-1 control-label">직급</label> \
		<label class="col-lg-1 col-md-1 col-sm-1 control-label">직통번호</label> \ <label class="col-lg-1 col-md-1 col-sm-1 control-label">내선번호</label>  \  <label class="col-lg-1 col-md-1 col-sm-1 control-label">이메일</label>';
	tt += '<label class="col-lg-1 col-md-1 col-sm-1 control-label"></label>';
	tt += '</div></div>';
	
	for (var i = 0; i < 5; i++) {	//row
		tt += '<div class="row"><div clas="form-group col-lg-12 col-md-12">'; //input list 시작
		tt += '<div class ="modal-inpt">' //윗 간격 주기
		for (var i2 = 0; i2 < 7; i2++) {	//colum
			tt += '<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>';
		}
		tt += '</div>';
		//공통 버튼 ( +, -)
		tt += '<div class="col-lg-1 col-md-1 col-sm-1 modal-mini-dv" ><div class="btn btn-success ">+</div></div><div class="col-lg-1 col-md-1 col-sm-1 modal-mini-dv" ><div class="btn btn-danger ">-</div></div>'
		tt += '</div></div>';	//input list 끝
	}
	tt += '</form>';
	return tt;
	/**
	 <form class="form-horizontal group-border stripped table-responsive" id="validate">
			<div class="row">
				<div clas="form-group col-lg-12 col-md-12">
					<label class="col-lg-1 col-md-1 col-sm-1 control-label">순서</label>
					<label class="col-lg-1 col-md-1 col-sm-1 control-label">담당자</label>
					<label class="col-lg-1 col-md-1 col-sm-1 control-label">부서</label>
					<label class="col-lg-1 col-md-1 col-sm-1 control-label">직급</label>
					<label class="col-lg-1 col-md-1 col-sm-1 control-label">직통번호</label>
					<label class="col-lg-1 col-md-1 col-sm-1 control-label">내선번호</label>
					<label class="col-lg-1 col-md-1 col-sm-1 control-label">이메일</label>
					<label class="col-lg-1 col-md-1 col-sm-1 control-label"></label>
				</div>
				</div>
				<div class="row">
				<div clas="form-group col-lg-12 col-md-12 ">
					<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>
					<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>
					<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>
					<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>
					<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>
					<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>
					<div class="col-lg-1 col-md-1 col-sm-1"><input class="form-control" type="text"></input></div>
					<div class="col-lg-1 col-md-1 col-sm-1 modal-mini-dv" ><div class="btn btn-success btn-xs">+</div></div>
					<div class="col-lg-1 col-md-1 col-sm-1 modal-mini-dv" ><div class="btn btn-danger btn-xs">-</div></div>
					<div class="col-lg-1 col-md-1 col-sm-1"></div>
				</div>
				</div>
			</form>
	 */
}

function test(){
	msg =  testTag();
	customModal(msg, "타이틀 ", "Call back", cbManageUser(createKeyword) );
	$('.modal-dialog').addClass('modal70');
	//createKeywordValidate();
}


function createKeywordValidate(){
	validateOpt.rules = {keyword:{required:true}, url:{required:true, url: true}, mt_modal_3:{required:true}};
	validateOpt.messages = {keyword: lang.um_required, url: {required: lang.um_required, url:lang.um_required_not_rule}, mt_modal_3: lang.um_required};
	$("#validate").validate(validateOpt);
}

function createKeyword(){
	
	alert("callbak func");
	return null;
}

$(document).ready(function() {
	//date range
	$(".input-daterange").datepicker(koDatePickerOpt);
	//START data 세팅 버튼 이벤트
	$(".init_date").on('click',function(){
		$("input[name$='start']").datepicker('setDate', ""); //$.datepicker.formatDate('yy-mm-dd', new Date())
		$("input[name$='end']").datepicker('setDate', "");
	});
	
	$(".init_today").on('click',function(){
		$("input[name$='start']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
		$("input[name$='end']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	$(".init_seven").on('click',function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 6);
		$("input[name$='start']").datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		$("input[name$='end']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	$(".init_thirty").on('click',function(){
		var myDate = new Date();
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 29);
		$("input[name$='start']").datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		$("input[name$='end']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	});
	//END data 세팅 버튼 이벤트
	
	// 회사관리 상세페이지 삭제
	$('#contents_setting_delete').on('click',function(){
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#company-form-write-setting').submit();
				}
		    }
		});
	});
	
	// 리스트 선택삭제
	$('#btn_list_delete').on('click',function(){
		//체크박스 체크
		bootbox.confirm({
			message: "삭제하시겠습니까?",
			title: "삭제하시겠습니까?",
			callback: function(result) {
		  		//callback result
				if(result){
					$('#action_type').val('delete');
					$('#company-form-list').submit();
				}		  		
		    }
		});
	});
	
	
})