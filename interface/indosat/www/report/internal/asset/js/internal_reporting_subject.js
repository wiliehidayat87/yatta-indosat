var intervalId = 0;

function drawTable(parameter){
	if(parameter == undefined) var parameter = '';
	var element = 'subjectTable';
	var url		= domain + 'subject/getSubjectTable';

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
			'url'	: domain + 'subject/getChartData',
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
	var shortCode	= $("#shortCode option:selected").val();
	var operator	= $("#operator option:selected").val();
	var parameter	= 'period=' + year +'-'+ month + '&shortCode=' + shortCode + '&operator=' + operator;

	drawTable(parameter);
});

function getSubjectOperator(period,subject,shortCode,operatorId){
	var response = false;
	$.ajax({
		'async'		: false,
		'type'		: 'post',
		'url'		: domain + 'subject/getSubjectOperator',
		'data'		: 'period=' + period + '&subject=' + subject + '&shortCode=' + shortCode + '&operatorId=' + operatorId,
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
	var subject	= $(this).find("span").text();
	var obj		= this;

	if( $(this).find("span").hasClass("collapsed") == 1 ){
		$(this).find("span").removeClass('collapsed');
		$(this).find("span").addClass('expanded');

		if( $("." + subject.replace(/;/g,'-')).size() != 0 ){
			$("." + subject.replace(/;/g,'-')).show();
		}
		else{
			var month 		= $("select[name='Date_Month'] option:selected").val();
			var year  		= $("select[name='Date_Year'] option:selected").val();
			var shortCode	= $("#shortCode option:selected").val();
			var operatorId	= $("#operator option:selected").val();

			result = getSubjectOperator(year +'-'+ month, subject, shortCode, operatorId);

			//generate table
			if(result != false){
				var index = $(obj).parent("tr").index() + 1;
				$.each(result,function(){
					$(obj).parent("tr").after(
						$('<tr class="' + subject.replace(/;/g,'-') + '"></tr>').append('<td style="text-align:left;background:#ffffcc;"><img src="' + imagePath + 'branch.gif"/> '+this.operator+'</td>')
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
						$('<tr class="' + subject.replace(/;/g,'-') + '"></tr>').append(html)
					);
				});
			}
		}
		tableResync();
	}
	else{
		$(this).find("span").removeClass('expanded');
		$(this).find("span").addClass('collapsed');
		$("." + subject.replace(/;/g,'-')).hide();
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

       var month 	= $("select[name='Date_Month'] option:selected").val();
	   var year  	= $("select[name='Date_Year'] option:selected").val();
	   var shortCode= $("#shortCode option:selected").val();
	   var operator	= $("#operator option:selected").val();
       var parameter= 'searchPattern=' + searchPattern + '&period=' + year + '-' + month + '&shortCode=' + shortCode + '&operator=' + operator;

       drawTable(parameter);
    }
});
