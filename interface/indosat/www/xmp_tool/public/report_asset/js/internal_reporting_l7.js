var operatorRequestedSubject = [];
var operatorOpenedSubject = [];
var operatorHiddenSubject = [];


var intervalId = 0;
function drawTable(parameter){
    if(parameter == undefined) var parameter = '';
    var url 	= domain + 'l7/getOperatorReportForTableL7';
    var element = 'revenueTable';

    buildTable(url, parameter, element);
    tableSync();

    intervalId = setInterval("postDrawTable('" + element + "')",500);
}

function postDrawTable(element){
    var elementFlag = element + '_flag';
    if( $("#" + elementFlag).size() != 0 && $("#" + elementFlag).val() == 'true'){
        //		drawGraph();
        clearInterval(intervalId);
    }
}

function drawGraph(){
    //	if( $("object#chart_revenue").size() != 0 ){
    $.ajax({
        'async' : false,
        'type'	: 	'post',
        'dataType': 'json',
        'url'	: domain + 'l7/getChartData',
        'success': function(data){
            try{
                load('chart_revenue',data);
            }catch(e){
            //					console.log(e.message);
            }
        }
    });
//	}
}

$(function() {
    $(".warning").hide();
    filterOption();

    // Toggle form visibility
    $("a#add").live('click', function() {
        $("#loadContainer").toggle();
    });

    $("button#cancel").live('click', function() {
        toggleForm();
    });

    $("a#all").live('click', function() {
        $(".operatorId").attr('checked', 'checked');
    });

    $("a#clear").live('click', function() {
        $(".operatorId").attr('checked', '');
    });

    //    $("#chartTable").hide();
    $("a#showChart").live('click', function() {
        var container = 'chartTable';

        if( $("#" + container).css('display') == 'none' ){
            $("#" + container).css('display', '');
            $(this).html('<span>hide chart</span>');
        }
        else{
            $("#" + container).css('display', 'none');
            $(this).html('<span>show chart</span>');
        }
    });

    //    $("select#shortCode").live('change', function() {
    //        var shortCode = $(this).val();
    //        var destination = domain + 'api/getOperator';
    //
    //        loader();
    //        $.ajax({
    //            async: false,
    //            type: 'post',
    //            data: 'shortCode=' + shortCode,
    //            dataType: 'json',
    //            url: destination,
    //            success: function(result) {
    //                if ('OK' == result.status) {
    //                    var operatorList = result.data[1];
    //                    var htmlCode = '<ul>';
    //
    //                    for (var i = 0; i < operatorList.length; i++) {
    //                        htmlCode += '<li><input class="operatorId" type="checkbox" value="' + operatorList[i].operator_code + '"> ' + operatorList[i].operator + '</li>';
    //                    }
    //
    //                    htmlCode += '</ul>';
    //                    $("div#optionList").html(htmlCode);
    //                }
    //                else {
    //                    achtungCreate(result.message, true);
    //                }
    //            }
    //        });
    //        loaded();
    //        $("select#operator").focus();
    //    });

    $("button#submit").live('click', function() {
        var period = $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val();
        var shortCode = $("select#shortCode option:selected").val();
        var option = $("div#optionList").find('.operatorId');
        var optionSelected = $("div#optionList").find('.operatorId:checked');

        if( $("#chartTable:hidden").size() == 1 ){
            $("#chartTable").show();
            $("#chartControl").text('Hide Chart');
        }

        pause(500);

        if( option.size() <= optionSelected.size() ){
            var operatorIds = [];
            $.each(optionSelected, function(){
                var operatorId = $(this).val();
                operatorIds.push(operatorId)
            });
        }
        else{
            var operatorIds = '';
        }

        var url = domain + 'l7/getOperatorReportForTableL7';
        var parameters = 'period=' + period + '&shortCode=' + shortCode + '&operatorId=' + operatorIds;
        var element = 'revenueTable';
        drawTable(parameters);
        closeForm();
        filterOption();

        loadChartBox1(shortCode, period);
        loadChartBox2(shortCode, period);
        loadChartBox3(shortCode, period);
    });

    $("span.getCharging").live('click', function() {
        var period = $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val();
        var operatorId = this.id;
        var type = this.title;
        var shortCode = $("#shortCode option:selected").val();

        if (true == in_array(type + '-' + operatorId, operatorRequestedSubject)) {
            // opened before, hide it.
            if (true == in_array(type + '-' + operatorId, operatorOpenedSubject)) {
                var current = type + '-' + operatorId;
                var temp = [];

                // remove from opened
                for (var i = 0; i < operatorOpenedSubject.length; i++) {
                    if (current != operatorOpenedSubject[i]) {
                        temp.push(current);
                    }
                }

                operatorOpenedSubject = temp;
                $("tr." + type + '-' + operatorId).hide();
            }
            else {
                operatorOpenedSubject.push(type + '-' + operatorId);
                $("tr." + type + '-' + operatorId).show();
            }

            loaded();
            tableResync();
        //            return false;
        }

        loader();

        var thisParent = $(this).parent().parent();
        var thisParentIndex = $(this).parent().parent().index();
        var thatParent = $('table.dg2-right tr:eq(' + thisParentIndex + ')');

        var destination = domain + 'l7/getOperatorChargingReportForTable';
        var parameters = 'period=' + period + '&operatorId=' + operatorId + '&type=' + type + '&shortCode=' + shortCode;

        $.ajax({
            async: false,
            type: 'post',
            data: parameters,
            dataType: 'json',
            url: destination,
            success: function(result) {
                if ('OK' == result.status) {
                    operatorRequestedSubject.push(type + '-' + operatorId);
                    operatorOpenedSubject.push(type + '-' + operatorId);

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

    var url = domain + 'l7/getOperatorReportForTableL7';
    var parameters = '';
    var element = 'revenueTable';
    buildTable(url, parameters, element);
    tableSync();

    function filterOption(){
        var period = $("select[name=Date_Year]").val() + '-' + $("select[name=Date_Month]").val();
        var shortCode = $("select#shortCode").val();
        var option = $("div#optionList").find('.operatorId');
        var optionSelected = $("div#optionList").find('.operatorId:checked');
        var operator = 'All';

        if( ( optionSelected.size() != 0 ) && (optionSelected.size() != option.size()) ){
            operator = '';
            $.each(optionSelected, function(){
                operator += $(this).attr('title') + ', ';

            });
        }
        if(shortCode === '' || shortCode === '0'){
            shortCode='All';
        }
        var htmlCode = '<b>Periode:</b> '+period +', <b>Shortcode:</b> '+shortCode+',  <b>Operator:</b> ' + operator;
        $(".warning").html(htmlCode);
        $(".warning").show();
    }

    function loadChartBox1(shortCode, period){
        var destination = domain + 'l7/getDailyRevenueReportChartl7';
        var chartName = 'chart_box_1';

        $.ajax({
            type: 'post',
            data: 'shortcode='+ shortCode +'&period=' + period +'&ajaxCall=true',
            dataType: 'json',
            url: destination,
            success: function(result) {
                if(result.status = "OK"){
                    load(chartName, result.data);
                }
            }
        });
    }

    function loadChartBox2(shortCode, period){
        var destination = domain + 'l7/getTopRevenueChartl7';
        var chartName = 'chart_box_2';
        //        console.log(shortCode);
        //        console.log(period);
        $.ajax({
            type: 'post',
            data: 'shortcode='+ shortCode +'&period=' + period +'&ajaxCall=true',
            dataType: 'json',
            url: destination,
            success: function(result) {
                if(result.status = "OK"){
                    load(chartName, result.data);
                }
            }
        });
    }

    function loadChartBox3(shortCode, period){
        var destination = domain + 'l7/getDailyTrafficReportChartl7';
        var chartName = 'chart_box_3';
        var operator  = [];

        if($(".operatorId:checked").size() != $(".operatorId").size()){
            $.each($(".operatorId:checked"),function(){
                operator.push( $(this).val() );
            });
        }
        else{
            operator = '';
        }

        $.ajax({
            type: 'post',
            data: 'shortcode='+ shortCode +'&period=' + period +'&ajaxCall=true' + '&operatorId=' + operator,
            dataType: 'json',
            url: destination,
            success: function(result) {
                if(result.status = "OK"){
                    load(chartName, result.data);
                }
            }
        });
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
});

