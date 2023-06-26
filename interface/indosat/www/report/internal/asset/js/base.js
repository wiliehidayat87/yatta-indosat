function in_array(needle, haystack, argStrict) {
    var key = '', strict = !!argStrict;
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {                return true;
            }
        }
    }
     return false;
}

function pause(millis){
    var date = new Date();
    var curDate = null;

    do { curDate = new Date(); }
    while(curDate-date < millis);
} 

function array_unique (inputArr) {
    var key = '', tmp_arr2 = {}, val = '';
    var __array_search = function (needle, haystack) {
        var fkey = '';
        for (fkey in haystack) {
            if (haystack.hasOwnProperty(fkey)) {                if ((haystack[fkey] + '') === (needle + '')) {
                    return fkey;
                }
            }
        }        return false;
    };

    for (key in inputArr) {
        if (inputArr.hasOwnProperty(key)) {            val = inputArr[key];
            if (false === __array_search(val, tmp_arr2)) {
                tmp_arr2[key] = val;
            }
        }    }

    return tmp_arr2;
}

function toggleForm() {
    $("#loadContainer").toggle();
}

function closeForm() {
    $("#loadContainer").slideUp();
}

function openForm() {
    $("#loadContainer").slideDown();
}

function buildTable(url, parameters, element) {
    loader();
    
    // create flag element
	if( $("#" + element + "_flag").size() == 0 ){
		$("body").append('<input id="' + element + '_flag" type="hidden" value="false"/>');
	}
	else{
		// set flag to false
		$("#" + element + "_flag").val("false");
	}

    $.ajax({
        async: false,
        type: 'post',
        data: parameters,
        dataType: 'json',
        url: url,
        success: function(result) {
            if ('OK' == result.status) {
                if (0 == result.data.length) {
                    achtungCreate(result.message, true);
                    $("#" + element).html('');
                }
                else {
                    $("#" + element).html(result.data);
                }

            }
            else {
                achtungCreate(result.message, true);
            }
        }
    });

    // set flag to true
	$("#" + element + "_flag").val("true");
	
    loaded();
}

var loop = 0;
function buildTableParted(url, parameters, element, part) {
	loop += 1;

    if(part != undefined){
    	if(parameters == ''){
    		parameters = 'part=' + part;
    	}
    	else{
    		parameters += '&part=' + part;
    	}
    }
    
    // create flag element
	if( $("#" + element + "_flag").size() == 0 ){
		$("body").append('<input id="' + element + '_flag" type="hidden" value="false"/>');
	}
	else{
		// set flag to false
		$("#" + element + "_flag").val("false");
	}

    $.ajax({
        type: 'post',
        data: parameters,
        dataType: 'json',
        url: url,
        success: function(result) {
            if ('OK' == result.status) {
                if (0 == result.data.length) {
                	// set flag to true
            		$("#" + element + "_flag").val("true");
            		
                    achtungCreate(result.message, true);
                }
                else if(result.data.part != undefined){
                	$("body").data('tmp_' + result.data.part, result.data.data);

                	if(result.data.part != 'EOF'){
                		buildTableParted(url,parameters,element,parseInt(result.data.part) + 1);
                	}
                	else{
                		$("body").data('final','');
                		for(i=1;i<=loop;i++){
                			if( i == loop ){
                				$("body").data('final', $("body").data('final') + $("body").data('tmp_EOF') );
                				$("body").removeData('tmp_EOF');
                			}
                			else{
                				$("body").data('final', $("body").data('final') + $("body").data('tmp_' + i) );
                				$("body").removeData('tmp_' + i);
                			}
                		};
                		
                		// appent to final element
                		$("#" + element).html( $("body").data('final') );
                		
                		// set flag to true
                		$("#" + element + "_flag").val("true");
                		
                		// remove temporary data
                		$("body").removeData('final');
                	}
                }
                else{
                	// set flag to true
            		$("#" + element + "_flag").val("true");
            		
            		// appent to final element
                	$("#" + element).html(result.data);
                }
            }
            else {
                achtungCreate(result.message, true);
            }
        }
    });
}

function explode (delimiter, string, limit) {
    var emptyArray = { 0: '' };

    if ( arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined' ) {
        return null;
    }

    if ( delimiter === '' || delimiter === false || delimiter === null ) {
        return false;
    }

    if ( typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object' ) {
        return emptyArray;
    }

    if ( delimiter === true ) {
        delimiter = '1';
    }

    if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    else {
        var splitted = string.toString().split(delimiter.toString());
        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());

        partA.push(partB);
        return partA;
    }
}

function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;

	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";

	if(typeof(arr) == 'object') { //Array/Hashes/Objects
		for(var item in arr) {
			var value = arr[item];

			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

function number_format (number, decimals, dec_point, thousands_sep) {
    var n = number, prec = decimals;

    var toFixedFix = function (n,prec) {
        var k = Math.pow(10,prec);        return (Math.round(n*k)/k).toString();
    };

    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);    var sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    var dec = (typeof dec_point === 'undefined') ? '.' : dec_point;

    var s = (prec > 0) ? toFixedFix(n, prec) : toFixedFix(Math.round(n), prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
     var abs = toFixedFix(Math.abs(n), prec);
    var _, i;

    if (abs >= 1000) {
        _ = abs.split(/\D/);        i = _[0].length % 3 || 3;

        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
        s = _.join(dec);    } else {
        s = s.replace('.', dec);
    }

    var decPos = s.indexOf(dec);    if (prec >= 1 && decPos !== -1 && (s.length-decPos-1) < prec) {
        s += new Array(prec-(s.length-decPos-1)).join(0)+'0';
    }
    else if (prec >= 1 && decPos === -1) {
        s += dec+new Array(prec).join(0)+'0';    }

    return s;
}

function tableSync() {
    $("#right").scroll(function() {
        $("#left").scrollTop($("#right").scrollTop());
    });
}

function tableResync() {
//    var currentTop = $("#right").scrollTop();
//    $("#right").scrollTop(currentTop + 1);
    $("#left").scrollTop($("#right").scrollTop());
}
