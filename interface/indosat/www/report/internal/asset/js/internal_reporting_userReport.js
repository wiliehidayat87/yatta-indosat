$(function() {
    // Toggle form visibility
    $("a#add").live('click', function() {
        $("#loadContainer").toggle();
    });

    $("button#cancel").live('click', function() {
        toggleForm();
    });
    
    $("button#submit").live('click', function() {
        var adn = $("input#adn").val();
        var service = $("select#service").val();
        var operatorId = $("select#operatorId").val();
        var date = $("input#date").val();
        var channel = $("input#channel").val();
        var page = 1;

        var url = domain + 'user_report/getUserReportForTable';
        var parameters = 'adn=' + adn + '&service=' + service + '&operatorId=' + operatorId + '&date=' + date + '&channel=' + channel + '&page=' + page;
        var element = 'userTable';
        buildTable(url, parameters, element);
        closeForm();
    });

    $("span.pagination").live('click', function() {
        var adn = $("input#adn").val();
        var service = $("select#service").val();
        var operatorId = $("select#operatorId").val();
        var date = $("input#date").val();
        var channel = $("input#channel").val();
        var page = this.title;

        var url = domain + 'user_report/getUserReportForTable';
        var parameters = 'adn=' + adn + '&service=' + service + '&operatorId=' + operatorId + '&date=' + date + '&channel=' + channel + '&page=' + page;
        var element = 'userTable';
        buildTable(url, parameters, element);
        closeForm();
    });

    $("#date").datepicker({dateFormat: 'yy-mm-dd'});
});

