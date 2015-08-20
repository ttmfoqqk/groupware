var koDatePickerOpt = {language : 'kr',  format: 'yyyy-mm-dd',  todayHighlight:true}; 	//dataPicker option (korean)


//input-base
var $input_base = $('tbody');
//최초 html 클론 저장
var $new_row    = $('.tbRow').clone();


//row 추가/삭제
function row_controll(obj, mode){
	var len     = $('.tbRow').length;
	var parent  = obj.parent().parent();
	var new_row = $new_row.clone();
	
	if( mode == 'add' ){
		new_row.css('background-color','#82ffab').animate({
			'background-color':'#ffffff'
		},500);

		$(new_row).insertAfter(parent);	
		addEvent();
	}else if( mode == 'rm' ){
		if(len <= 1){
			alert('삭제할수 없습니다.');
			return false;
		}else{
			parent.css('background-color','#ff8282').animate({
				'opacity':'0',
				'background-color':'#ffffff'
			},500,function(){
				$(this).remove();
			});
		}
	}
}

function addEvent(){
	$(".input-daterange").datepicker(koDatePickerOpt);
}


function setInputVal(){
	$('.tdOrder').each(function(){
		$(this).val( $(this).attr('data-value') );
	});
	$('.tdInfo').each(function(){
		$(this).val( $(this).attr('data-value') );
	});
	$('.tdDate').each(function(){
		var date =  $(this).attr('data-value') ? $.datepicker.formatDate('yy-mm-dd', new Date( $(this).attr('data-value'))) : '';
		$(this).val(date);
	});
}
//리스트 생성
//number : 기본 리스트 갯수
function list(year){
	var number = 1;
	var year = year;
	$.ajax({
		type     : 'POST',
		url      : '/groupware/holiday/list/',
		dataType : 'json',
		data     : {
			year  : year
		},
		dataType : 'json',
		success: function(data){
			if(data.result){
				var json   = eval(data.data);
				var clones = '';
				for (var i in json){
					var new_row  = $new_row.clone();
					// value setting
					var infoCell  = new_row.find('.tdInfo');
					var dateCell     = new_row.find('.tdDate');
					
					infoCell.attr('data-value',json[i].name);
					dateCell.attr('data-value',json[i].date);
					
					clones += new_row.wrapAll('<div>').parent().html();
				}
				
				for (var i=json.length;i<number;i++){
					clones += $new_row.wrapAll('<div>').parent().html();
				}

				$input_base.html(clones);
				
				addEvent();
				setInputVal();
			}else{
				alert(data.result + ',' + data.msg);
			}
		},error:function(err){
			alert(err.responseText);
		}
	});
}

//입력
var save = function(){
	var data_array = new Array();
	var validate_fg = false;
	$('.tbRow').each(function(eq){
		var data_info  = new Object();
		var infoCell  = $(this).find('.tdInfo');
		var dateCell  = $(this).find('.tdDate');
		
		if(!infoCell.val()){
			alert('내용을 적어주세요.');
			infoCell.focus();
			validate_fg = false;
			return false;
		}
		if(!dateCell.val()){
			alert('날짜를 선택해 주세요');
			dateCell.focus();
			validate_fg = false;
			return false;
		}

		if(infoCell.val() && dateCell.val()){
			validate_fg = true;
			data_info.name = infoCell.val();
			data_info.date = dateCell.val();
			data_array.push(data_info);
		}
	});
	
	if( validate_fg == true ){
		$.ajax({
			type     : 'POST',
			url      : '/groupware/holiday/save/',
			data     : {
				data  : data_array
			},
			dataType : 'json',
			success: function(data){
				if(data.result){
					list();
					alert('저장됨');
				}else{
					alert(data.result + ',' + data.msg);
				}
			},error:function(err){
				alert(err.responseText);
			}
		});
	}
}

$(document).ready(function() {
	//date range
	$(".input-daterange").datepicker(koDatePickerOpt);
	list();
	
	$("#tfYear").on('change', function(){
		list($('select[name="year"]').val());
	});
	$("#btYear").click(function() {
		var thisYear = (new Date).getFullYear();
		list(thisYear);
		$('select[name="year"]').val(thisYear);
	});
	
	
})