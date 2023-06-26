var intervalId = 0;

function drawTable(parameter){
	if(parameter == undefined) var parameter = '';
	var element = 'subscriberTable';
	var url		= domain + 'subscriber/getSubscriberTable';
	
	loader();
	buildTableParted(url, parameter, element);
	
	intervalId = setInterval("postDrawTable('" + element + "')",500);
}

function postDrawTable(element){
	var elementFlag = element + '_flag';
	if( $("#" + elementFlag).size() != 0 && $("#" + elementFlag).val() == 'true'){
		loaded();
//		drawGraph();
		clearInterval(intervalId);
	}
}

function drawGraph(){
	if( $("object#chart_traffic").size() != 0 ){
		$.ajax({
			'async' : false,
			'type'	: 	'post',
			'dataType': 'json',
			'url'	: domain + 'subscriber/getChartData',
			'success': function(data){
				try{
					load('chart_traffic',data);
				}catch(e){
//					console.log(e.message);
				}
			}
		});
	}
}

$(".warning").hide();
filterOption();
drawTable();

//$("#chartTable").hide();
//$("#custom").live('click', function() {
//	var container = 'chartTable';
//	
//	if( $("#" + container).css('display') == 'none' ){
//		$("#" + container).css('display', '');
//  	$(this).html('<span>hide chart</span>');
//  }
//	else{
//		$("#" + container).css('display', 'none');
//  	$(this).html('<span>show chart</span>');
//	}
//});

$("#custom").live('click',function(){
	if( $("#loadContainer:hidden").size() == 1 ){
		$("#loadContainer").slideDown();
	}
	else{
		$("#loadContainer").slideUp();
	}
});

$("#cancel").live('click',function(){
	$("#loadContainer").slideUp();
});

$("#submit").live('click',function(){
	var month 		= $("select[name='Date_Month'] option:selected").val();
	var year  		= $("select[name='Date_Year'] option:selected").val();
	var shortCode	= $("#shortCode option:selected").val();
	var operator	= $("#operator option:selected").val();
	var service		= $("#service option:selected").val();
	var parameter	= 'period=' + year +'-'+ month + '&shortCode=' + shortCode + '&operator=' + operator + '&service=' + service; 
	var period = year + '-' + month;
	
	if( $("#chartTable:hidden").size() == 1 ){
		$("#chartTable").show();
		$("#chartControl").text('Hide Chart');
	}
	
	pause(500);
	
	$("#loadContainer").slideUp();
	filterOption();
	
	drawTable(parameter);
	loadChartBox1(parameter);
    loadChartBox2(parameter);
    loadChartBox3(parameter);
});

$("#shortCode").live('change',function(){
	var shortCode = $("#shortCode option:selected").val();
	$.ajax({
		'type'	: 'post',
		'url'	: domain + 'subscriber/getOperator',
		'data'	: 'shortCode=' + shortCode,
		'dataType': 'json',
		'beforeSend': function(){
			loader();
		},
		'success' : function(result){
			loaded();
			if(result.status == 'OK'){
				var operator = result.data[1];
				$("#operator").children().remove();
				$("#operator").append('<option value="">All</option>')
				$.each(operator,function(){
					$("#operator").append('<option value="' + this.operator_code + '">' + this.operator + '_' + this.shortCode + '</option>')
				});
				
			}
			else{
				alert(result.message);
			}
		}
	});
});

function loadChartBox1(parameter){
    var destination = domain + 'subscriber/getDailySubcriberSubtotalReportChart';
    var chartName = 'chart_box_1';
    
    $.ajax({
        type: 'post',
        data: parameter +'&ajaxCall=true',
        dataType: 'json',
        url: destination,
        success: function(result) {
    		if(result.status = "OK"){
    			load(chartName, result.data);
    		}
        }
    });
}

function loadChartBox2(parameter){
    var destination = domain + 'subscriber/getDailySubcriberRegUnregReportChart';
    var chartName = 'chart_box_2';
    
    $.ajax({
        type: 'post',
        data: parameter +'&ajaxCall=true',
        dataType: 'json',
        url: destination,
        success: function(result) {
    		if(result.status = "OK"){
    			load(chartName, result.data);
    		}
        }
    });
}

function loadChartBox3(parameter){
    var destination = domain + 'subscriber/getDailySubcriberSubtotalPercentageReportChart';
    var chartName = 'chart_box_3';
    
    $.ajax({
        type: 'post',
        data: parameter +'&ajaxCall=true',
        dataType: 'json',
        url: destination,
        success: function(result) {
    		if(result.status = "OK"){
    			load(chartName, result.data);
    		}
        }
    });
}

function filterOption(){
	var period = $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val();
    var shortCode = $("select#shortCode option:selected").text();
    var service	= $("#service option:selected").text();
    var operator = $("#operator option:selected").text();
    
    var htmlCode = '<b>Periode:</b> '+period +', <b>Shortcode:</b> '+shortCode+',  <b>Operator:</b> '+operator+',  <b>Service:</b> '+service;
    $(".warning").html(htmlCode);
    $(".warning").show();
}

$("#chartControl").live('click',function(){
	if( $("#chartTable:hidden").size() == 0 ){
		$("#chartTable").slideUp();
		$(this).text('Show Chart');
	}
	else{
		$("#chartTable").slideDown();
		$(this).text('Hide Chart');
	}
});