var closeReasonRequestedSubject = [];
var closeReasonOpenedSubject = [];

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

    $("select[name=Date_Month], select[name=Date_Year]").live('focus', function() {
        $("#periodDate").attr('checked', 'checked');
    });

    $("select#numberOfLastDays").live('focus', function() {
        $("#periodLast").attr('checked', 'checked');
    });

    $("button#submit").live('click', function() {
        var shortCode = $("select#shortCode").val();
        var operatorId = $("select#operatorId").val();
        var serviceId = $("select#serviceId").val();
        var display = $("select#display").val();
        var periodType = $("input[name=period]:checked").val();
        var period = (periodType == 'periodDate') ? $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val() : $("select#numberOfLastDays").val();
        var limit = $("select#limit").val();
        var sorting = $("input[name=sorting]:checked").val();
        
        if( $("#chartTable:hidden").size() == 1 ){
    		$("#chartTable").show();
    		$("#chartControl").text('Hide Chart');
    	}
        
        pause(500);

        var parameters = 'shortCode=' + shortCode + '&operatorId=' + operatorId + '&serviceId=' + serviceId + '&display=' + display + '&period=' + period + '&limit=' + limit + '&sorting=' + sorting;
        $("#loadContainer").slideUp();
    	filterOption();
    	loadChartBox1(parameters);
        loadChartBox2(parameters);
        loadChartBox3(parameters);
        drawTable(parameters);
        closeForm();
        tableSync();
    });

    $("span.getService").live('click', function() {
        var shortCode = $("select#shortCode").val();
        var serviceId = $("select#serviceId").val();
        var display = $("select#display").val();
        var periodType = $("input[name=period]").val();
        var period = (periodType == 'periodDate') ? $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val() : $("select#numberOfLastDays").val();
        var limit = $("select#limit").val();
        var sorting = $("input[name=sorting]").val();

        var spanId = this.id;
        var ocr = explode(closeReasonDelimiter, this.id);
        var operatorId = ocr[0];
        var closeReason = ocr[1];

        if (true == in_array(spanId, closeReasonRequestedSubject)) {
            // opened before, hide it.
            if (true == in_array(spanId, closeReasonOpenedSubject)) {
                var current = spanId;
                var temp = [];

                // remove from opened
                for (var i = 0; i < closeReasonOpenedSubject.length; i++) {
                    if (current != closeReasonOpenedSubject[i]) {
                        temp.push(current);
                    }
                }

                closeReasonOpenedSubject = temp;
                $("tr." + spanId).hide();
            }
            else {
                closeReasonOpenedSubject.push(spanId);
                $("tr." + spanId).show();
            }

            loaded();
            tableResync();
            return false;
        }

        var thisParent = $(this).parent().parent();
        var thisParentIndex = $(this).parent().parent().index();
        var thatParent = $('table.dg2-right tr:eq(' + thisParentIndex + ')');

        var destination = domain + 'close_reason/getClosereasonServiceReportForTable';
        var parameters = 'shortCode=' + shortCode + '&operatorId=' + operatorId + '&serviceId=' + serviceId + '&display=' + display + '&period=' + period + '&limit=' + limit + '&sorting=' + sorting + '&closeReason=' + closeReason;

        loader();
        $.ajax({
            async: false,
            type: 'post',
            data: parameters,
            dataType: 'json',
            url: destination,
            success: function(result) {
                if ('OK' == result.status) {
                    closeReasonRequestedSubject.push(spanId);
                    closeReasonOpenedSubject.push(spanId);

                    $(result.data.left).insertAfter(thisParent);
                    $(result.data.right).insertAfter(thatParent);
                }
                else {
                    achtungCreate(result.message);
                }
            }
        });
        loaded();
        tableResync();
    });

    var intervalId = 0;

    function drawTable(parameter){
    	if(parameter == undefined) var parameter = '';
    	var url 	= domain + 'close_reason/getCloseReasonReportForTable';
        var element = 'closeReasonTable';
    	
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
    			'url'	: domain + 'close_reason/getChartData',
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

    function loadChartBox1(parameters){
        var destination = domain + 'close_reason/getDailyCloseReasonReportChart';
        var chartName = 'chart_box_1';
        
        $.ajax({
            type: 'post',
            data: parameters +'&ajaxCall=true',
            dataType: 'json',
            url: destination,
            success: function(result) {
        		if(result.status = "OK"){
        			load(chartName, result.data);
        		}
            }
        });
    }

    function loadChartBox2(parameters){
        var destination = domain + 'close_reason/getCloseReasonReportChart';
        var chartName = 'chart_box_2';
        
        $.ajax({
            type: 'post',
            data: parameters +'&ajaxCall=true',
            dataType: 'json',
            url: destination,
            success: function(result) {
        		if(result.status = "OK"){
        			load(chartName, result.data);
        		}
            }
        });
    }

    function loadChartBox3(parameters){
        var destination = domain + 'close_reason/getDailyCloseReasonPercentageReportChart';
        var chartName = 'chart_box_3';
        
        $.ajax({
            type: 'post',
            data: parameters +'&ajaxCall=true',
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
    	var shortCode = $("select#shortCode option:selected").text();
        var operatorId = $("select#operatorId option:selected").text();
        var serviceId = $("select#serviceId option:selected").text();
        var display = $("select#display option:selected").text();
        var periodType = $("input[name=period]:checked").val();
        var period = (periodType == 'periodDate') ? $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val() : $("select#numberOfLastDays").val();
        var limit = $("select#limit option:selected").text();
        var sorting = $("input[name=sorting]").val();
        
        var htmlCode = '<b>Periode:</b> '+period +
        				', <b>Shortcode:</b> '+shortCode+
        				', <b>Service:</b> '+serviceId+
        				', <b>Operator:</b> '+operatorId+
        				', <b>Display</b>:'+display+
        				', <b>Period Type</b>:'+periodType+
        				', <b>Limit</b>:'+limit+
        				', <b>Sorting</b>:'+sorting;
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
    
$(".warning").hide();
filterOption();
 drawTable();

