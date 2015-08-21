
$(document).ready(function() {
	for(i=1; i<4 ; i++){
		$('#start-time' + i).timepicker({
	    	upArrowStyle: 'fa fa-angle-up',
	    	downArrowStyle: 'fa fa-angle-down',
	    	showMeridian: false, 
	    	defaultTime : false
	    });
		$('#end-time' + i).timepicker({
	    	upArrowStyle: 'fa fa-angle-up',
	    	downArrowStyle: 'fa fa-angle-down',
	    	showMeridian: false,
	    	defaultTime : false,
	    });
	//	$('#start-time' + i).timepicker('setTime', '09:00');
	//	$('#end-time' + i).timepicker('setTime', '18:00');
	}
})