    // Toggle form visibility
    $("a#add").live('click', function() {
        $("#loadContainer").toggle();
    });

    $("button#cancel").live('click', function() {
        toggleForm();
    });

    $("select#shortCode").live('change', function() {
        var shortCode = $(this).val();
        var destination = domain + 'api/getOperator';

        loader();
        $.ajax({
            async: false,
            type: 'post',
            data: 'shortCode=' + shortCode,
            dataType: 'json',
            url: destination,
            success: function(result) {
                if ('OK' == result.status) {
                    var operatorList = result.data[1];
                    var htmlCode = '<option value="">-- OPERATOR --</option>';

                    for (var i = 0; i < operatorList.length; i++) {
                        htmlCode += '<option value="' + operatorList[i].id + '">' + operatorList[i].operator + '</option>';
                    }

                    $("select#operator").html(htmlCode);
                }
                else {
                    achtungCreate(result.message, true);
                }
            }
        });
        loaded();
        $("select#operator").focus();
    });

    $("button#submit").live('click', function() {
        var period = $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val();
        var shortCode = $("select#shortCode").val();
        var operatorId = $("select#operatorId").val();
        var serviceId = $("select#serviceId").val();
        
        if( $("#chartTable:hidden").size() == 1 ){
    		$("#chartTable").show();
    		$("#chartControl").text('Hide Chart');
    	}
        
        pause(500);

        var parameters = 'period=' + period + '&shortCode=' + shortCode + '&operatorId=' + operatorId + '&serviceId=' + serviceId;
        
        drawTable(parameters);
    	filterOption();
    	loadChartBox1(period, operatorId, shortCode);
        loadChartBox2(period, operatorId, shortCode);
        loadChartBox3(period, operatorId, shortCode);
        closeForm();
    });

    var intervalId = 0;

    function drawTable(parameter){
    	if(parameter == undefined) var parameter = '';
    	var url = domain + 'user/getUserReportForTable';
        var element = 'userTable';
    	
    	buildTable(url, parameter, element);
    	tableSync();
    	
    	intervalId = setInterval("postDrawTable('" + element + "')",500);
    }

    function postDrawTable(element){
    	var elementFlag = element + '_flag';
    	if( $("#" + elementFlag).size() != 0 && $("#" + elementFlag).val() == 'true'){
//    		drawGraph();
    		clearInterval(intervalId);
    	}
    }

    function drawGraph(){
    	if( $("object#chart_traffic").size() != 0 ){
    		$.ajax({
    			'async' : false,
    			'type'	: 	'post',
    			'dataType': 'json',
    			'url'	: domain + 'user/getChartData',
    			'success': function(data){
    				try{
    					load('chart_traffic',data);
    				}catch(e){
//    					console.log(e.message);
    				}
    			}
    		});
    	}
    }
    
    $(".warning").hide();
    filterOption();
    drawTable();
    
    function loadChartBox1(period, operatorId, shortcode){
        var destination = domain + 'user/getDailyUserReportChart';
        var chartName = 'chart_box_1';
        
        $.ajax({
            type: 'post',
            data: 'period=' + period +'&operatorId=' + operatorId +'&shortcode=' + shortcode +'&ajaxCall=true',
            dataType: 'json',
            url: destination,
            success: function(result) {
        		if(result.status = "OK"){
        			load(chartName, result.data);
        		}
            }
        });
    }

    function loadChartBox2(period, operatorId, shortcode){
        var destination = domain + 'user/getUserReportChart';
        var chartName = 'chart_box_2';
        
        $.ajax({
            type: 'post',
            data: 'period=' + period +'&operatorId=' + operatorId +'&shortcode=' + shortcode +'&ajaxCall=true',
            dataType: 'json',
            url: destination,
            success: function(result) {
        		if(result.status = "OK"){
        			load(chartName, result.data);
        		}
            }
        });
    }

    function loadChartBox3(period, operatorId, shortcode){
        var destination = domain + 'user/getDailyUserPercentageReportChart';
        var chartName = 'chart_box_3';
        
        $.ajax({
            type: 'post',
            data: 'period=' + period +'&operatorId=' + operatorId +'&shortcode=' + shortcode +'&ajaxCall=true',
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
        var service	= $("#serviceId option:selected").text();
        var operator = $("#operatorId option:selected").text();
        
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