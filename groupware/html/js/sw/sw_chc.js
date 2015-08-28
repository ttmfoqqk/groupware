var koDatePickerOpt = {language : 'kr',  format: 'yyyy-mm-dd',  todayHighlight:true}; 	//dataPicker option (korean)

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
					$('#rule-form-list').submit();
				}		  		
		    }
		});
	});
	
	/*
	//분류필터 리스트 init
	var $menu_part_no = $('#ft_rule');
	$menu_part_no.create_menu({
		method : $menu_part_no.attr('data-method'),
		value : $menu_part_no.attr('data-value')
	});
	*/
	
	$('.tb_num').change(function(){
		$('#qu').submit();
	});
	
})

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

function getExpGraphTag(){
	tt = '  <div class="row"><div class="col-lg-3">	\
		<div class="form-group">	\
	<label class="col-lg-3 col-md-3 control-label" for="">' + "날짜" + '</label>	\
	<div class="col-lg-9 col-md-9">	\
		<div class="input-daterange input-group">	\
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>	\
			<input type="text" class="form-control" name="start2" />	\
		</div>	\
	</div>	\
</div>	\
</div>  <button  type="button" id="expGraphSearch_btn" class="btn btn-primary btn-alt">' + "검색" + '</button> \
<button  type="button" id="expGraphSearch_before" class="btn btn-primary btn-alt">' + "이전" + '</button>\
<button  type="button" id="expGraphSearch_next" class="btn btn-primary btn-alt">' + "다음" + '</button>\
</div> \
<div class="row"> <div id="exp_graph" style="width: 100%; height:250px;"></div></div>';
	return tt;
}

function expInfoKeyword(jobId){
	msg = getExpGraphTag();
	customModal(msg, "1" , "취소", null );
	$('.modal-dialog').addClass('modal70');
	
	$(".input-daterange").datepicker(koDatePickerOpt);
	
	$("input[name$='start2']").datepicker('setDate', $.datepicker.formatDate('yy-mm-dd', new Date()));
	$("input[name$='start2']").datepicker('update');
	
	date = $("input[name$='start2']").val();
	drawGraphKwdInfo(jobId, date);
	
	$("#expGraphSearch_btn").click(function(){
		drawGraphKwdInfo(jobId, $("input[name$='start2']").val());
	});
	$("#expGraphSearch_before").click(function(){
		var myDate = new Date($("input[name$='start2']").val());
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth - 1);
		$("input[name$='start2']").datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		drawGraphKwdInfo(jobId, $("input[name$='start2']").val());
	});
	$("#expGraphSearch_next").click(function(){
		var myDate = new Date($("input[name$='start2']").val());
		var dayOfMonth = myDate.getDate();
		myDate.setDate(dayOfMonth + 1);
		$("input[name$='start2']").datepicker('setDate',  $.datepicker.formatDate('yy-mm-dd', myDate));
		drawGraphKwdInfo(jobId, $("input[name$='start2']").val());
	});
	
	
}

function drawGraphKwdInfo(jobId, date){
	//define chart colours first
	var chartColours = {
		gray: '#bac3d2',
		teal: '#43aea8',
		blue: '#60b1cc',
		red: '#df6a78',
		orange: '#cfa448',
		gray_lighter: '#e8ecf1',
		gray_light: '#777777',
		gridColor: '#bfbfbf'
	}
	//convert the object to array for flot use
	var chartColoursArr = Object.keys(chartColours).map(function (key) {return chartColours[key]});

	data = getExpData(jobId, date);
	data = JSON.parse(data);
		//some data
		var d1 = data.data;
		var options = {
			grid: {
				show: true,
			    aboveData: true,
			    color: chartColours.gridColor,
			    labelMargin: 15,
			    axisMargin: 0, 
			    borderWidth: 0,
			    borderColor:null,
			    minBorderMargin: 5 ,
			    clickable: true, 
			    hoverable: true,
			    autoHighlight: true,
			    mouseActiveRadius: 20
			},
	        series: {
	        	grow: {active:false},
	            lines: {
            		show: true,
            		fill: true,
            		lineWidth: 2,
            		steps: false
	            	},
	            points: {show:true}
	        },
	        legend: { 
	        	position: "ne", 
	        	margin: [0,-25], 
	        	noColumns: 0,
	        	labelBoxBorderColor: null,
	        	labelFormatter: function(label, series) {
				    // just add some space to labes
				    return '&nbsp;&nbsp;' + label + ' &nbsp;&nbsp;';
				},
				width: 30,
				height: 2
	    	},
	        yaxis: { min: -1, tickSize: 2,   tickDecimals: 0},
		    //xaxis: {ticks:11, tickDecimals: 0, tickLength: 0},
	        xaxis: {mode: "time", timeformat: "%H:%M"},
	        //xaxis: { ticks: [[0,'00:00'],[1,'00:20'],[8,'01:10']]},
	        colors: chartColoursArr,
	        shadowSize:1,
	        tooltip: true, //activate tooltip
			tooltipOpts: {
				//content: "%s : (%x)%y.0" + " $",
				content: "시간" + ": %x  " + "랭킹" + ": %y.0",
				shifts: {
					x: -30,
					y: -50
				}
			}
	    };   

    	$.plot($("#exp_graph"), [ 
    		{
    			label: "랭킹", 
    			data: d1,
    			lines: {fillColor: chartColours.gray_lighter},
    			points: {fillColor: chartColours.red}
    		}

    	], options);

}

function getExpData(no, date){
	
	var postData = {"date": date, 'no': no};
	return $.ajax({
		url: '/groupware/chc/expData/'
		,async: false
		,data: postData
		,type: "POST"
		,dataType: 'json'
		,success: function(data, status, jqxhr){
			console.log(data);
			if(data.success == true)
			{
				return true;
			}
			else
			{
				return false
			}
		}
		,error: function(jqxhr, status, errorMsg)
		{
			console.log(jqxhr);
			return false;
		}
	}).responseText;
}




