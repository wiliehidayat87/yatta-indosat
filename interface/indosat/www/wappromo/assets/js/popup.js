(function($) {
    $("a.btn_jad").live('click', function(){

	var hid_base_url = $('#hid_base_url').val();
	var post_url = hid_base_url+'wapreg_log/wapreglog_ua/get_ua';
	var dt = $(this).attr('rel');
//	alert(dt);
	var listdt = escape(dt);


	var dataString = 'dtsend='+listdt;
	
	$.ajax({
		url: post_url,
		type: "POST",
		data: dataString,
		 success: function(dataResult){
			if(dataResult.length > 0) {
//	                        alert(dataResult);
				jQuery('#dialog-modal').html(dataResult);
				//gowysiwyg();
				
			} else {
				alert('Failed to open data.');
			}	
		}
	
		});        


	$( "#dialog-modal" ).dialog({
                width: 450,
		height: 250,
                modal: true
            });
		});
    
    $("a.btn_jar").live('click', function(){

	var hid_base_url = $('#hid_base_url').val(); 
        var post_url = hid_base_url+'wapreg_log/wapreglog_ua/get_ua'; 
        var dt = $(this).attr('rel'); 
        var listdt = escape(dt); 
// 	alert(dt);
 
        var dataString = 'dtsend='+listdt; 
         
        $.ajax({ 
                url: post_url, 
                type: "POST", 
                data: dataString, 
                success: function(dataResult){ 
                        if(dataResult.length > 0) { 
//                                alert(dataResult); 
                                jQuery('#dialog-modal').html(dataResult); 
                                //gowysiwyg(); 
                                 
                        } else { 
                                alert('Failed to open data.'); 
                        }        
                } 
         
                }); 

        $( "#dialog-modal" ).dialog({
		width: 450,
                height: 250,
                modal: true
            });
		});


	$("input.popup_ua").live('click', function(){
        var hid_base_url = $('#hid_base_url').val();
        var post_url = hid_base_url+'wapreg_log/wapreglog_ua/get_ua';
	var v_service = $('input[name="tx_service"]').val();
        var v_begindate = $('input[name="tx_begindate"]').val();
        var v_enddate = $('input[name="tx_enddate"]').val();
        var v_ua = $('input[name="tx_ua"]').val();
        var v_jadr = $('input[name="tx_jadr"]').val();
        //alert(v_ua); 
	var listdt = escape(v_begindate+'|'+v_enddate+'|'+v_service+'|'+v_jadr+'|'+v_ua);
//        alert(listdt);

        var dataString = 'dtsend='+listdt;

        $.ajax({
                url: post_url,
                type: "POST",
                data: dataString,
                success: function(dataResult){
                        if(dataResult.length > 0) {
//                                alert( 'cek     '+dataResult); 
                                jQuery('#dialog-modal').html(dataResult);
                                //gowysiwyg(); 

                        } else {
                                alert('Failed to open data.');
                        }
                }

                });

           $( "#dialog-modal" ).dialog({
                width: 450,
                height: 250,
                modal: true
            });
        });

})(jQuery);
