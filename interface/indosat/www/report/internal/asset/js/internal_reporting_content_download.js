var intervalId = 0;

function drawTable(parameter){
	if(parameter == undefined) var parameter = '';
	var element = 'contentDownloadTable';
	var url		= domain + 'content_download/getContentDownloadTable';
	
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
	else
	{
		loaded();
		clearInterval(intervalId);
	}
}

function drawGraph(){
	if( $("object#chart_revenue").size() != 0 ){
		$.ajax({
			'async' : false,
			'type'	: 	'post',
			'dataType': 'json',
			'url'	: domain + 'content_download/getChartData',
			'success': function(data){
				try{
					load('chart_revenue',data);
				}catch(e){
//					console.log(e.message);
					achtungCreate(e.message, true);
				}
			}
		});
	}
}

$(".warning").hide();
filterOption();
drawTable();

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
	var operator	= $("#operator option:selected").val();
	var contentOwner= $("#contentOwner option:selected").val();
	var contentType	= $("#contentType option:selected").val();
	var parameter	= '';
	
	if( $("#chartTable:hidden").size() == 1 ){
		$("#chartTable").show();
		$("#chartControl").text('Hide Chart');
	}
	
	pause(500);
	
	if($("#daily:checked").size() != 0){
		parameter = 'mode=daily';
		parameter+= '&year=' + year;
		parameter+= '&month=' + month;
		parameter+= '&operatorId=' + operator;
		parameter+= '&contentOwner=' + contentOwner;
		parameter+= '&contentType=' + contentType;
		var chartType = 'daily';
		var period 	  = year + '-' +month;
	}
	else{
		parameter = 'mode=monthly';
		parameter+= '&year=' + year;
		parameter+= '&operatorId=' + operator;
		parameter+= '&contentOwner=' + contentOwner;
		parameter+= '&contentType=' + contentType;
		var chartType = 'monthly';
		var period 	  = year;
	}
	
	$("#loadContainer").slideUp();
	filterOption();
	loadChartBox1(period, operator, contentOwner, contentType);
    loadChartBox2(period, operator, contentOwner, contentType);
    loadChartBox3(period, operator, contentOwner, contentType);
	drawTable(parameter);
});

function loadChartBox1(period, operatorId, contentOwner, contentType){
    var destination = domain + 'content_download/getDailyDownloadContentReportChart';
    var chartName = 'chart_box_1';
    
    $.ajax({
        type: 'post',
        data: 'period=' + period +'&operatorId=' + operatorId +'&contentOwner=' + contentOwner +'&contentType='+ contentType +'&ajaxCall=true',
        dataType: 'json',
        url: destination,
        success: function(result) {
    		if(result.status = "OK"){
    			load(chartName, result.data);
    		}
        }
    });
}

function loadChartBox2(period, operatorId, contentOwner, contentType){
    var destination = domain + 'content_download/getDownloadContentReportChart';
    var chartName = 'chart_box_2';
    
    $.ajax({
        type: 'post',
        data: 'period=' + period +'&operatorId=' + operatorId +'&contentOwner=' + contentOwner +'&contentType='+ contentType +'&ajaxCall=true',
        dataType: 'json',
        url: destination,
        success: function(result) {
    		if(result.status = "OK"){
    			load(chartName, result.data);
    		}
        }
    });
}

function loadChartBox3(period, operatorId, contentOwner, contentType){
    var destination = domain + 'content_download/getDailyDownloadContentPercentageReportChart';
    var chartName = 'chart_box_3';
    
    $.ajax({
        type: 'post',
        data: 'period=' + period +'&operatorId=' + operatorId +'&contentOwner=' + contentOwner +'&contentType='+ contentType +'&ajaxCall=true',
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
	var month 		= $("select[name='Date_Month'] option:selected").text();
	var year  		= $("select[name='Date_Year'] option:selected").text();
	var operator	= $("#operator option:selected").text();
	var contentOwner= $("#contentOwner option:selected").text();
	var contentType	= $("#contentType option:selected").text();
	var mode	= '';
	var period = year + '-' +month;
	
	if($("#daily:checked").size() != 0){
		mode = 'daily';
	}
	else{
		mode = 'monthly';
	}
    
    var htmlCode = '<b>Periode:</b> '+period +', <b>Content Owner:</b> '+contentOwner+', <b>Content Type:</b> '+contentType+',  <b>Operator:</b> '+operator+', <b>Mode:</b> '+mode;
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