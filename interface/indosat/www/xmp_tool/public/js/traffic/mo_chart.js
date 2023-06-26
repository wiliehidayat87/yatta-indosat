var targetUrl = base_url + "traffic/mo_traffic/getChartData";

swfobject.embedSWF(
    base_url + "public/flash/open-flash-chart.swf", "mo-chart", "278", "150",
    "9.0.0", "expressInstall.swf",
    {"data-file": targetUrl },
	{"wmode": "transparent" }
);

function load_mo_data(data) {
    tmp = findSWF("mo-chart");
    x = tmp.load(data);
}

function findSWF(movieName) {
  if (navigator.appName.indexOf("Microsoft")!= -1) {
    return window[movieName];
  } else {
    return document[movieName];
  }
}

$(document).ready(function() {
    $('#chart-button').click(function() {
        interval = $('#chart-timespan').val();

        if (interval < 3) interval = 3;

        $.post(targetUrl, {chart_span: interval}, function(data) {
            load_mo_data(data);
        });
        return false;
    });


})