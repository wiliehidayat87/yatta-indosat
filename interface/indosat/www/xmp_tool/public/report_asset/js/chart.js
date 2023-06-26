function load(chartName, data){		
	tmp = findSWF(chartName);
	window[chartName + '_data'] = data;
	x = tmp.load( JSON.stringify(data) );
}


function findSWF(movieName) {
	  if (navigator.appName.indexOf("Microsoft")!= -1) {
		  return window[movieName];
	  } 
	  else {
		  return document[movieName];
	  }
}


function ofc_ready()
{
//	for debuging chart started or not
//	alert('ofc_ready');
}

function addBar(chartName, chartData, value, label){	
	var num = chartData['elements'][0]['values'].length
	
	chartData['elements'][0]['values'][num] 	 = value;
	chartData['x_axis']['labels']['labels'][num] = label;
		
	load(chartName, chartData);
}

function addPie(chartName, chartData, value, label){
	var num = chartData['elements'][0]['values'].length
	
	chartData['elements'][0]['values'][num] = {"value":value,"label":label};	
		
	load(chartName, chartData);
}

function addLine(chartName, chartData, value, label, color){
	var num = chartData['elements'].length;
	var numLabel = chartData['x_axis']['labels']['labels'].length;
	
	var newLine	= {
			"type": "line",
			"dot-style": {
				"type": "solid-dot",
				"halo-size": 2,
				"dot-size": 2
			},
			"values": value,
			"width": 2,
			"colour": color
		}
	
	chartData['elements'][num] = newLine;
	//chartData['x_axis']['labels']['labels'][numLabel] = label;
	
	load(chartName, chartData);
}

function removeBarByLabel(chartName, chartData, label){
	$.each(chartData['x_axis']['labels']['labels'], function(key, val){
		if(val == label){
			chartData['x_axis']['labels']['labels'].splice(key, 1);
			chartData['elements'][0]['values'].splice(key, 1);
		}		
	});
	
	load(chartName, chartData);
}

function removePieByLabel(chartName, chartData, label){
	var index = null;
	
	$.each(chartData['elements'][0]['values'], function(key, val){		
		if(val['label'] != 'undefined' && val['label'] == label){			
			index = key;
		}		
	});
	
	if(index != null){
		chartData['elements'][0]['values'].splice(index, 1);
	}
	
	load(chartName, chartData);
}
