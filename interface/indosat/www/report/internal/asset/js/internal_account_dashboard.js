$(function(){
	var def = '';
	function generateChartOption(topic){
		if(def == ''){
			def = $("#chart_type option");
		}
		
		$("#chart_type option").show();
		$("#chart_type").attr('value','');
		
		if(topic == 'traffic'){
			$("#chart_type option[value!='stacked'][value!='']").hide();
		}	
		else{
			$("#chart_type option[value='stacked'][value!='']").hide();	
		}
	}
	
	function addForm(){
		$('button#chart-submit').show();
		$('button#chart-update').hide();
	}
	
	function editForm(){
		$('button#chart-submit').hide();
		$('button#chart-update').show();
		$("#loadContainer").slideDown();
	}
	
	function clearForm(){
		$("#name").attr('value', '');
		$("#topic").attr('value', '');
		$("#group").attr('value', '');
		$("#chart_type").attr('value', '');
		$("#chart_type").attr('value', '');
		$("#data").attr('value', '');
	}
	
	// Toggle form visibility
    $("a#add").live('click', function() {
    	addForm();
    	clearForm();
        $("#loadContainer").toggle();
    });
    
	//
	// edit config
	//
	var def = '';
	$(".chart-config").live('click', function(){
		chartId 	= $(this).attr('rel');
		chartIndex 	= $(this).attr('boxid');
		
		loader();
		
		$.ajax({
			type:	  'post',
			url:	  domain + 'account/addChart',
			data:	  'index=' + chartIndex,
			dataType: 'json',
			success:  function(result){
				loaded();
				if(result.status == 'OK'){
					if(result.status == 'OK'){
						$("#name").attr('value', result.data.name);
						$("#topic option[value='" + result.data.title + "']").attr('selected', 'selected');
						$("#group option[value='" + result.data.group + "']").attr('selected', 'selected');
						$("#data option[value='" + result.data.data + "']").attr('selected', 'selected');
						$("#date").attr('value', result.data.rangedate);
						$("#chart-update").attr('rel', result.data.id);
						$("#chart-update").attr('title', chartIndex);
						generateChartOption(result.data.title);
						$("#chart_type option[value='" + result.data.chart_type + "']").attr('selected', 'selected');
						editForm();
					}
					else{
						alert("Error!! Could't connect pages!");
					}
				}
				else{
					alert("Error!! Could't connect pages!");
				}
			}
		});
	});
	
	
	//
	// close chart form
	//
	$("#close").live('click', function(){
		$("#loadContainer").slideUp();
	});
	
	
	$("#date").live('mouseover click', function(){
		$("table.boxy-wrapper").css('z-index', 2);
		$(".boxy-modal-blackout").css('z-index', 1);
		$(this).daterangepicker().focus();
	});
	
	$("#chart-submit").live('click', function(){
		var name		= $("#name").val();
		var topic 		= $("#topic option:selected").val();
		var group 		= $("#group option:selected").val();
		var chart_type  = $("#chart_type option:selected").val();
		var data		= $("#data option:selected").val();
		var date		= $("#date").val();
		
		var postdata 	= 'name=' + name;
			postdata   += '&topic=' + topic;
			postdata   += '&group=' + group;
			postdata   += '&type=' + chart_type;
			postdata   += '&data=' + data;
			postdata   += '&rangedate=' + date;
		
		// save chart configuration	
		loader();

		$.ajax({
			type:	  'post',
			url:	  domain + 'account/saveChartSetting',
			data:	  postdata,
			dataType: 'json',
			success:  function(result){
				if(result.status == 'OK'){					
					$("#loadContainer").slideUp();
					
					var dashboardId = result.data; 
						
					$.ajax({
						'type':	  	'post',
						'url':	  	domain + 'account/getChartDataWrapperAjax',
						'data':	  	postdata,
						'dataType': 'json',
						'success':  function(result){
							if(result.status == 'OK'){
								if(chart_type == 'table_summary'){
									showTable(topic,date, result.data);
								}
								else if(chart_type == 'number'){
									showTotal(topic,date, result.data);
								}
								else{
									showChart(topic,date, result.data, name, dashboardId);
								}
								clearForm();
							}
							else{
								alert('error!');
							}
						}
					});
				}
				else{
					achtungCreate(result.message, false);
				}
				loaded();
			}
		});						
	});
	
	
	$("#chart-update").live('click', function(){
		var chartId		= $(this).attr('rel');
		var name		= $("#name").val();
		var topic 		= $("#topic option:selected").val();
		var group 		= $("#group option:selected").val();
		var chart_type  = $("#chart_type option:selected").val();
		var data		= $("#data option:selected").val();
		var date		= $("#date").val();
		var chartIndex  = $(this).attr('title');
		var boxId		= 'box_' + chartIndex;
		var postdata 	= 'name=' + name;
			postdata   += '&topic=' + topic;
			postdata   += '&group=' + group;
			postdata   += '&type=' + chart_type;
			postdata   += '&data=' + data;
			postdata   += '&rangedate=' + date;
			postdata   += '&id=' + chartId;
		
		// save chart configuration	
		loader();

		$.ajax({
			type:	  'post',
			url:	  domain + 'account/updateChartSetting',
			data:	  postdata,
			dataType: 'json',
			success:  function(result){
				if(result.status == 'OK'){					
					$.ajax({
						'type':	  	'post',
						'url':	  	domain + 'account/getChartDataWrapperAjax',
						'data':	  	postdata,
						'dataType': 'json',
						'success':  function(result){								
							if(result.status == 'OK'){
								if(chart_type == 'table_summary'){
									reloadTable(boxId,topic,date,result.data);
								}
								else if(chart_type == 'number'){
									reloadTotal(boxId,topic,date,result.data);
								}
								else{
									reloadChart(boxId,topic,date,result.data,name);
								}
							}
							else{
								alert('error!');
							}
						}
					});	
				}
				else{
					achtungCreate(result.message, false);
				}
				loaded();
				$("#loadContainer").slideUp();
			}
		});						
	});
	
	$("#chart-cancel").live('click',function(){
		$("#loadContainer").hide();
	});
	
	function ucwords(str) {
	    return (str + '').replace(/^(.)|\s(.)/g, function ($1) {
	        return $1.toUpperCase();    });
	}
	
	
	function reloadChart(boxId,topic,rangedate,data,name){
		var topic = ucwords( topic.replace('_',' ') );					
		var nameChart = boxId.replace('box_', 'chart_box_'); 
		var container = 'content_' + nameChart;
		var chartName = topic;
	    // load chart
		try {
			load(nameChart, data);
		} catch (e) {
	       	// catch and forget :)
//	       	alert("An exception occurred in the script. Error name: " + e.name 	+ ". Error message: " + e.message); 
		}
	    
		if( $("#" + container).css('display') == 'none' ){
			$("#" + container).css('display', '');
        	$("#" + container.replace('chart','table')).css('display', 'none');
        }
		
		if(name != ''){
			chartName = name;
		}
		
	    $("#"+boxId).children().find("h3").html(chartName);
	    
	    $.ajax({
			'type':	  	'post',
			'url':	  	domain + 'account/dateToString',
			'data':	  	'date=' + rangedate,
			'dataType': 'json',
			'success':  function(result){								
				if(result.status == 'OK'){
					$("#"+boxId).children().find("span").html(result.data);
				}
				else{
					$("#"+boxId).children().find("span").html(rangedate);
				}
			}
		});
	            	            
	    // show
	    $("#"+boxId).fadeIn("slow");	            
	            
	    return false;				
	}

	function reloadTable(boxId,topic,rangedate,data){
		var topic = ucwords( topic.replace('_',' ') );					
		var name  = boxId.replace('box_', 'content_table_box_');
		
		$("#"+boxId).children().find("h3").html(topic);
		
		if( $("#"+name).css('display') == 'none' ){
        	$("#"+name).css('display', '');
        	$("#"+name).html(data);
        	$("table.rptRevenue tbody tr:odd td").css({ background:'#EFEFEF' });
        	$("#"+name.replace('table','chart')).css('display', 'none');	            	
        }
	            
	    $.ajax({
			'type':	  	'post',
			'url':	  	domain + 'account/dateToString',
			'data':	  	'date=' + rangedate,
			'dataType': 'json',
			'success':  function(result){								
				if(result.status == 'OK'){
					$("#"+boxId).children().find("span").html(result.data);
				}
				else{
					$("#"+boxId).children().find("span").html(rangedate);
				}
			}
		});
	            	            
	    // show
	    $("#"+boxId).fadeIn("slow");	            
	            
	    return false;				
	}
	
	function reloadTotal(boxId,topic,rangedate,data){
		var topic = ucwords( topic.replace('_',' ') );					
		var name  = boxId.replace('box_', 'content_table_box_');
		
		$("#"+boxId).children().find("h3").html(topic);
		
		if( $("#"+name).css('display') != 'none' ){
        	$("#"+name).css('display', '');
        	$("#"+name).html(data);
        	$("table.rptRevenue tbody tr:odd td").css({ background:'#EFEFEF' });
        	$("#"+name.replace('table','chart')).css('display', 'none');	            	
        }
	            
	    $.ajax({
			'type':	  	'post',
			'url':	  	domain + 'account/dateToString',
			'data':	  	'date=' + rangedate,
			'dataType': 'json',
			'success':  function(result){								
				if(result.status == 'OK'){
					$("#"+boxId).children().find("span").html(result.data);
				}
				else{
					$("#"+boxId).children().find("span").html(rangedate);
				}
			}
		});
	            	            
	    // show
	    $("#"+boxId).fadeIn("slow");	            
	            
	    return false;				
	}
	
	function showChart(topic, rangedate, data, name, chartId){
		var topic = ucwords( topic.replace('_',' ') );
		var chartName = topic;
		
		$.each($("li[id^='box_']"), function(key, value){
			if($(value).css('display') == 'none'){
				var boxId     = $(value).attr('id');
				var nameChart = boxId.replace('box_', 'chart_box_');
				var container = 'content_' + nameChart;
	            
				if( $("#" + container).css('display') == 'none' ){
					$("#" + container).css('display', '');
	            	$("#" + container.replace('chart','table')).css('display', 'none');
	            }
				
	            // load chart
	            try {
	            	load(nameChart, data);
	            } catch (e) {
	            	// catch and forget :)
	            	// alert("An exception occurred in the script. Error name: " + e.name 	+ ". Error message: " + e.message); 
	            }
	            
	            if(name != ''){
	            	chartName = name;
	            }
	            
	            $(value).children().find("h3").html(chartName);
	            
	            if(chartId != undefined){
	            	$(value).find(".chart-config,.chart-remove").attr('rel',chartId);
	            }
	            
	            $.ajax({
					'type':	  	'post',
					'url':	  	domain + 'account/dateToString',
					'data':	  	'date=' + rangedate,
					'dataType': 'json',
					'success':  function(result){								
						if(result.status == 'OK'){
							$(value).children().find("span").html(result.data);
						}
						else{
							$(value).children().find("span").html(rangedate);
						}
					}
				});
	            	            
	            // show
	            $(value).fadeIn("slow");	            
	            
	            return false;
			}
		});
	}
	
	
	function showTable(topic, rangedate, data){
		var topic = ucwords( topic.replace('_',' ') );
					
		$.each($("li[id^='box_']"), function(key, value){
			if($(value).css('display') == 'none'){
				var boxId   = $(value).attr('id');
				var name 	= boxId.replace('box_', 'content_table_box_');

				$(value).children().find("h3").html(topic);
				
	            if( $("#"+name).css('display') == 'none' ){
	            	$("#"+name).css('display', '');
	            	$("#"+name).html(data);
	            	$("table.rptRevenue tbody tr:odd td").css({ background:'#EFEFEF' });
	            	$("#"+name.replace('table','chart')).css('display', 'none');	            	
	            }
	            
	            $.ajax({
					'type':	  	'post',
					'url':	  	domain + 'account/dateToString',
					'data':	  	'date=' + rangedate,
					'dataType': 'json',
					'success':  function(result){								
						if(result.status == 'OK'){
							$(value).children().find("span").html(result.data);
						}
						else{
							$(value).children().find("span").html(rangedate);
						}
					}
				});
	            	            
	            // show
	            $(value).fadeIn("slow");	            
	            
	            return false;
			}
		});
	}
	
	function showTotal(topic, rangedate, data){
		var topic = ucwords( topic.replace('_',' ') );
					
		$.each($("li[id^='box_']"), function(key, value){
			if($(value).css('display') == 'none'){
				var boxId   = $(value).attr('id');
				var name 	= boxId.replace('box_', 'content_table_box_');

				$(value).children().find("h3").html(topic);
				
	            if( $("#"+name).css('display') == 'none' ){
	            	$("#"+name).css('display', '');
	            	$("#"+name).html(data);
	            	$("table.rptRevenue tbody tr:odd td").css({ background:'#EFEFEF' });
	            	$("#"+name.replace('table','chart')).css('display', 'none');	            	
	            }
	            
	            $.ajax({
					'type':	  	'post',
					'url':	  	domain + 'account/dateToString',
					'data':	  	'date=' + rangedate,
					'dataType': 'json',
					'success':  function(result){								
						if(result.status == 'OK'){
							$(value).children().find("span").html(result.data);
						}
						else{
							$(value).children().find("span").html(rangedate);
						}
					}
				});
	            	            
	            // show
	            $(value).fadeIn("slow");	            
	            
	            return false;
			}
		});
	}
	
	$(".chart-remove").live('click', function(){
		var ask = confirm("Are you sure want to remove this chart?");
		var tmp = '';
		
		if(ask == false){
			return;
		}
		
		var instance = this;
		var id 	  = this.rel;
		var boxId = $(this).attr('boxid');
		var index = parseInt( boxId.replace('box_','') );
		loader();
		
		$.ajax({
			type:	  'post',
			url:	  domain + 'account/removeChart',
			data:	  'index=' + boxId + '&id=' + id,
			dataType: 'json',
			success:  function(result){
				loaded();
				if(result.status == 'OK'){
					achtungCreate(result.message, false);
//					$("#box_"+boxId).fadeIn("slow");
//					tmp = $("#box_"+boxId).html();
					$(instance).parent().parent().parent().parent().hide();
					
//					for(i=index+1;i<=maxDashboardChart;i++){
//						boxMove(i, i-1);
//					}
					
					//$("#box_" + maxDashboardChart).css('display', 'none');
				}
				else{
					alert('Failed removing chart from dashboard. Please try again.');
				}
			}
		});		
	});
	
	
	function boxMove(fromIndex, toIndex) {
		var isDisplay = $("#box_" + fromIndex).css('display');
		$("#box_" + toIndex).html( $("#box_" + fromIndex).html() );
		$("#box_" + toIndex).css('display', isDisplay);
		$("#chart_box_" + fromIndex).attr('id', 'chart_box_' + toIndex);
		$("#chart_box_" + toIndex + " param[name='flashvars']").attr('value','get-data=chart_box_'+toIndex+'_func');
		$("#content_chart_box_" + fromIndex).attr('id', 'content_chart_box_' + toIndex);
		$("#content_table_box_" + fromIndex).attr('id', 'content_table_box_' + toIndex);
		$("#box_" + toIndex).find(".chart-config").attr('boxid', toIndex);
		$("#box_" + toIndex).find(".chart-remove").attr('boxid', toIndex);
	}
	
	var indexBefore;
	var indexAfter;
		
	$("#chart-table").sortable({
		cursor: 'pointer',
		revert: true,
		tolerance: 'pointer',
		placeholder: 'ui-state-highlight',		
		start: function(event, ui){
			indexBefore = ui.item.index()+1;
		},
		update: function(event, ui){
			indexAfter = ui.item.index()+1;
			loader();
			$.ajax({
				type:	  'post',
				url:	  domain + 'account/swapChart',
				data:	  'indexBefore=' + indexBefore + '&indexAfter=' + indexAfter,
				dataType: 'json',			
				success:  function(result){
					loaded();
					updateIndex();
				}
			});
		}   
	});
	
	function updateIndex(){
		$.each($("#chart-table > li"),function(i,v){
			$(this).find(".chart-config,.chart-remove").attr('boxid', (i + 1) );
		});
	}
	
	$("#chart-table").disableSelection();
	
	$("#topic").live('change', function(){
		var topic = $("#topic option:selected").val(); 
		var chart_type  = $("#chart_type option:selected").val();
		generateChartOption(topic);
	});
});
