$(document).ready(function(){
	$(".input-daterange").datepicker({language : 'kr',format: 'yyyy-mm-dd',autoclose:true,todayHighlight:true});

	var sData  = $('#sData');
	var eData  = $('#eData');

	var btn_sToday = $('#sToday');
	var btn_sWeek  = $('#sWeek');
	var btn_sMonth = $('#sMonth');
	var btn_sReset = $('#sReset');

	

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
	
	var department    = $('#department');
	var in_department = $('#in_department');
	var in_user       = $('#in_user');
	
	if(department.length>0){
		department.create_menu({
			method : department.attr('data-method'),
			value  : department.attr('data-value')
		});
	}

	if(in_department.length>0){
		in_department.create_menu({
			method : in_department.attr('data-method'),
			value  : in_department.attr('data-value')
		});
	}

	in_department.change(function(){
		var no = $(this).val();
		create_user(no,in_user);
	});
});


/* 규정 팝업 */
function call_rule_modal(){
	// base div hide
	var test_html ='<div id="modal-body" style="display:none;"></div><div id="modal-loading">로딩중..</div>';

	bootbox.dialog({
		message: test_html,
		title: '규정선택',
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
	$('#modal-body').load('/groupware/html/pop/purpose_call_rule.php');
}

function create_user(no,obj){
	no = !no ? 0 : no;
	obj.html('<option value="">로딩중..</option>');

	var setVal = obj.attr('data-value');
	$.ajax({
		type     : 'POST',
		url      : '/groupware/member/lists/',
		data     : {
			menu_no : no
		},
		dataType : 'json',
		success: function(data){
			var json   = eval(data);
			var html = '';
			for (var i in json){
				html += '<option value="' + json[i].no + '"'+ (setVal==json[i].no?' selected':'') +'>' + json[i].name + '</option>';
			}
			html = '<option value="">선택</option>' + html;
			obj.html(html);
		},error:function(err){
			alert(err.responseText);
			//alert('일시적인 에러입니다. 잠시 후 다시 시도해 주세요.');
		}
	});
}