//------------- charts-other.js -------------//
$(document).ready(function() {
	//------------- Other charts -------------//
	//define chart colours first
	var chartColours = {
		gray: '#bac3d2',
		teal: '#43aea8',
		blue: '#60b1cc',
		red: '#df6a78',
		green: '#66c796',
		orange: '#cfa448',
		gray_lighter: '#e8ecf1',
		gray_light: '#777777',
	}

	//------------- Init Easy pie charts -------------//
    //pass the variables to pie chart init function
    //first is line width, size for pie, animated time , and colours object for theming.
	initPieChartPage(10,150,1500, chartColours);

	
});

//Setup easy pie charts in page
var initPieChartPage = function(lineWidth, size, animateTime, colours) {

	$(".easy-pie-chart").easyPieChart({
        barColor: colours.gray,
        borderColor: colours.gray,
        trackColor: colours.gray_lighter,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-red").easyPieChart({
        barColor: colours.red,
        borderColor: colours.red,
        trackColor: '#ebb7bd',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-green").easyPieChart({
        barColor: colours.green,
        borderColor: colours.green,
        trackColor: '#a9e2c6',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-blue").easyPieChart({
        barColor: colours.blue,
        borderColor: colours.blue,
        trackColor: '#b7e0ee',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-yellow").easyPieChart({
        barColor: colours.orange,
        borderColor: colours.orange,
        trackColor: '#e9d09a',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });

	
	$('#d_type').change(function(){
		var value = $(this).val();
		set_date_option(value);
	});
	set_date_option($('#d_type').val());

	var department = $('#department');
	if(department.length>0){
		department.create_menu({
			method : department.attr('data-method'),
			value  : department.attr('data-value')
		});
	}
}

function set_date_option(m){
	var obj = $('#d_option');
	var value = obj.attr('data-value');
	var h = '';
	if(m=='m'){
		var d = new Date();
		var n = d.getMonth();
		value = !value ? n : value;
		for(i=1;i<=12;i++){
			h += '<option value="'+(i<10 ? '0'+i : i )+'" '+ (value==i?'selected':'') +'>'+i+'월</option>';
		}
	}else if(m=='q'){
		for(i=1;i<=4;i++){
			if(i<=4){
				h += '<option value="'+i+'" '+ (value==i?'selected':'') +'>'+i+'기</option>';
			}
		}
		h += '<option value="5" '+ (value==5?'selected':'') +'>1기+2기</option><option value="6" '+ (value==6?'selected':'') +'>1기+2기+3기</option><option value="7" '+ (value==7?'selected':'') +'>1기+2기+3기+4기</option>';
	}	
	$('#d_option').html(h);
}