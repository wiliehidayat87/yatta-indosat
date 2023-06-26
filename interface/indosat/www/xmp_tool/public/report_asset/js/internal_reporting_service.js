var intervalId = 0;

function drawTable(parameter){
	if(parameter == undefined) var parameter = '';
	var element = 'serviceTable';
	var url		= domain + 'service/getServiceTable';

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
	if( $("object#chart_revenue").size() != 0 ){
		$.ajax({
			'async' : false,
			'type'	: 	'post',
			'dataType': 'json',
			'url'	: domain + 'service/getChartData',
			'success': function(data){
				try{
					load('chart_revenue',data);
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
//$("a#showChart").live('click', function() {
//	var container = 'chartTable';
//	
//	if( $("#" + container).css('display') == 'none' ){
//		$("#" + container).css('display', '');
//    	$(this).html('<span>hide chart</span>');
//    }
//	else{
//		$("#" + container).css('display', 'none');
//    	$(this).html('<span>show chart</span>');
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
	var operatorId	= $("#operator option:selected").val();
	var parameter 	= 'period=' + year +'-'+ month + '&shortCode=' + shortCode + '&operatorId=' + operatorId;
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

function getServiceOperator(period,service,shortCode,operatorId){
	var response = false;
	$.ajax({
		'async'		: false,
		'type'		: 'post',
		'url'		: domain + 'service/getServiceOperator',
		'data'		: 'period=' + period + '&service=' + service + '&shortCode=' + shortCode + '&operatorId=' + operatorId,
		'dataType'	: 'json',
		'beforeSend': function(){
			loader();
		},
		'success'	: function(result){
			if(result.status == 'OK'){
				loaded();
				response = result.data;
			}
			else{
				alert(result.message);
			}
		}
	});
	return response;
}

function getServiceOperatorSubject(period,service,shortCode,operatorId){
	var response = false;
	$.ajax({
		'async'		: false,
		'type'		: 'post',
		'url'		: domain + 'service/getServiceOperatorSubject',
		'data'		: 'period=' + period + '&service=' + service + '&shortCode=' + shortCode + '&operatorId=' + operatorId,
		'dataType'	: 'json',
		'beforeSend': function(){
			loader();
		},
		'success'	: function(result){
			if(result.status == 'OK'){
				loaded();
				response = result.data;
			}
			else{
				alert(result.message);
			}
		}
	});
	return response;
}

$(".pointerBD").live('click',function(){
	var service	= $(this).find("span").text();
	var obj		= this;

	if( $(this).find("span").hasClass("collapsed") == 1 ){
		$(this).find("span").removeClass('collapsed');
		$(this).find("span").addClass('expanded');

		if( $("." + service.replace(/;/g,'-')).size() != 0 ){
			$("." + service.replace(/;/g,'-')).show();
		}
		else{
			var month 		= $("select[name='Date_Month'] option:selected").val();
			var year  		= $("select[name='Date_Year'] option:selected").val();
			var shortCode	= $("#shortCode option:selected").val();
			var operatorId	= $("#operator option:selected").val();

			result = getServiceOperator(year +'-'+ month, service, shortCode, operatorId);

			//generate table
			if(result != false){
				var index = $(obj).parent("tr").index() + 1;
				$.each(result,function(){
					$(obj).parent("tr").after(
						$('<tr class="' + service.replace(/;/g,'-') + '"></tr>').append('<td style="text-align:left;background:#ffffcc;cursor:pointer;" class="pointerBDOperator" rel="' + this.operatorId + ';' + this.service + '"><img src="' + imagePath + 'branch.gif"/> <span class="collapsed">'+this.operator+'</span></td>')
					);
					var html = '<td style="background:#ffffcc;">' + number_format(this.totalSent,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.totalDelivered,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.totalFailed,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.totalUnknown,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.totalRevenue,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.averageSent,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.averageDelivered,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.averageFailed,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.averageUnknown,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.averageRevenue,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.monthEndSent,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.monthEndDelivered,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.monthEndFailed,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.monthEndUnknown,0,',','.') + '</td>';
						html+= '<td style="background:#ffffcc;">' + number_format(this.monthEndRevenue,0,',','.') + '</td>';

						var daily = [];
						$.each(this.daily,function(){
							daily.push({
								'sent' 		: number_format(this.sent,0,',','.'),
								'delivered'	: number_format(this.delivered,0,',','.'),
								'failed'	: number_format(this.failed,0,',','.'),
								'unknown'	: number_format(this.unknown,0,',','.'),
								'revenue'	: number_format(this.revenue,0,',','.'),
								'color'	    : this.color
							});
						});

						$.each(daily.reverse(),function(){
							html+= '<td style="' + this.color + '">' + this.sent + '</td>';
							html+= '<td style="' + this.color + '">' + this.delivered + '</td>';
							html+= '<td style="' + this.color + '">' + this.failed + '</td>';
							html+= '<td style="' + this.color + '">' + this.unknown + '</td>';
							html+= '<td style="' + this.color + '">' + this.revenue + '</td>';
						});
					$("table.dg2-right tr:eq(" + index + ")").after(
						$('<tr class="' + service.replace(/;/g,'-') + '"></tr>').append(html)
					);
				});
			}
		}
		tableResync();
	}
	else{
		$(this).find("span").removeClass('expanded');
		$(this).find("span").addClass('collapsed');
		$("." + service.replace(/;/g,'-')).hide();
		// hide all expanded child
		$("tr[class*='" + service + "']").find("span").removeClass('expanded');
		$("tr[class*='" + service + "']").find("span").addClass('collapsed');
		$("tr[class*='" + service + "']").hide();
		tableResync();
	}
});

$(".pointerBDOperator").live('click',function(){
	var tmp 		= explode(';',$(this).attr('rel'));
	var operatorId	= tmp[0];
	var service		= tmp[1];
	var obj			= this;

	if( $(this).find("span").hasClass("collapsed") == 1 ){
		$(this).find("span").removeClass('collapsed');
		$(this).find("span").addClass('expanded');

		if( $("." + service + operatorId).size() != 0 ){
			$("." + service + operatorId).show();
		}
		else{
			var month 		= $("select[name='Date_Month'] option:selected").val();
			var year  		= $("select[name='Date_Year'] option:selected").val();
			var shortCode	= $("#shortCode option:selected").val();

			result = getServiceOperatorSubject(year +'-'+ month, service, shortCode, operatorId);

			//generate table
			if(result != false){
				var index = $(obj).parent("tr").index() + 1;
				$.each(result,function(){
					if(this.subject.length > 25){
						var subject = this.subject.substring(0,25) + '...';
					}
					else{
						var subject = this.subject;
					}
					$(obj).parent("tr").after(
						$('<tr class="' + service + operatorId + '"></tr>').append('<td style="text-align:left;background:#FDF3F2;padding-left:14px;" title="'+this.subject+'"><img src="' + imagePath + 'branch.gif"/> ' + subject + '</td>')
					);
					var html = '<td style="background:#FDF3F2;">' + number_format(this.totalSent,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.totalDelivered,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.totalFailed,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.totalUnknown,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.totalRevenue,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.averageSent,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.averageDelivered,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.averageFailed,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.averageUnknown,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.averageRevenue,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.monthEndSent,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.monthEndDelivered,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.monthEndFailed,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.monthEndUnknown,0,',','.') + '</td>';
						html+= '<td style="background:#FDF3F2;">' + number_format(this.monthEndRevenue,0,',','.') + '</td>';

						var daily = [];
						$.each(this.daily,function(){
							daily.push({
								'sent' 		: number_format(this.sent,0,',','.'),
								'delivered'	: number_format(this.delivered,0,',','.'),
								'failed'	: number_format(this.failed,0,',','.'),
								'unknown'	: number_format(this.unknown,0,',','.'),
								'revenue'	: number_format(this.revenue,0,',','.'),
								'color'	    : this.color
							});
						});

						$.each(daily.reverse(),function(){
							html+= '<td style="' + this.color + '">' + this.sent + '</td>';
							html+= '<td style="' + this.color + '">' + this.delivered + '</td>';
							html+= '<td style="' + this.color + '">' + this.failed + '</td>';
							html+= '<td style="' + this.color + '">' + this.unknown + '</td>';
							html+= '<td style="' + this.color + '">' + this.revenue + '</td>';
						});
					$("table.dg2-right tr:eq(" + index + ")").after(
						$('<tr class="' + service + operatorId + '"></tr>').append(html)
					);
				});
			}
		}
		tableResync();
	}
	else{
		$(this).find("span").removeClass('expanded');
		$(this).find("span").addClass('collapsed');
		$("." + service + operatorId).hide();
		tableResync();
	}
});

$(".search").live('click', function(){
    if( $(this).val() == 'search' ){
            $(this).val('');
    }
});


$(".search").live('focusout', function(){
    if( $(this).val() == '' ){
            $(this).val('search');
    }
});

$(".search").live('keypress', function(e){
    var code = (e.keyCode ? e.keyCode : e.which);
    if(code == 13) {
       var searchPattern = $(this).val();

       if( $(this).val() == '' ){
           $(this).val('search');
       }

       var month 		= $("select[name='Date_Month'] option:selected").val();
	   var year  		= $("select[name='Date_Year'] option:selected").val();
	   var shortCode	= $("#shortCode option:selected").val();
	   var operatorId	= $("#operator option:selected").val();
	   var parameter	= 'searchPattern=' + searchPattern + '&period=' + year + '-' + month + '&shortCode=' + shortCode + '&operatorId=' + operatorId;

	   drawTable(parameter);
    }
});

function loadChartBox1(parameter){
    var destination = domain + 'service/getDailyRevenueReportChart';
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
    var destination = domain + 'service/getTopRevenueChart';
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
    var destination = domain + 'service/getDailyRevenuePercentageReportChart';
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
    var operator = $("#operator option:selected").text();
    
    var htmlCode = '<b>Periode:</b> '+period +', <b>Shortcode:</b> '+shortCode+',  <b>Operator:</b> '+operator;
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