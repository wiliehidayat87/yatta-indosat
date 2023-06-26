var gbl_pageURI		= "";
var adn 			= "";
var msisdn 			= "";
var operator 		= "";
var service 		= "";

$(function() {
    // Toggle form visibility
    $("a#add").live('click', function() {
        $("#loadContainer").toggle();
    });
	
	$("div#btnCheck").hide();
	
	$(".pagination ul li a").live("click", function() {
		tableListPagination($(this).attr("href"));
        return false;
    });
	
	$("input[name='CheckAll']").live('click', function(){
		$("form[name='table'] #choices").attr("checked",true);
	});
	
	$("input[name='UnCheckAll']").live('click', function(){
		$("form[name='table'] #choices").attr("checked",false);
	});
	
	$("input[name='inactiveChecked']").live('click', function(){
		inactiveChecked();
	});
		
    $("button#cancel").live('click', function() {
        $("#loadContainer").toggle();
    });
    
    $(".boxtoggle").click(function() {
        resetForm("subscription");
    });
    
    $("button#submit").live('click', function() {
        adn 		= $("select#adn").val();
        msisdn 		= $("input#msisdn").val();
        operator 	= $("select#operator").val();
        service 	= $("select#service").val();
        gbl_pageURI	= "";
        
        if(adn=="" && msisdn=="" && operator=="" && service==""){
			achtungFailed("All field Can not be empty");
			return false;
		}
		
		if(isNaN(msisdn)){
			achtungFailed("MSISDN must be number");
			return false;
		}
				        
        var url 	= base_url + 'cs/subscription/getUserSubscriptionTable';
        var pageUrl	= base_url + 'cs/subscription/pagination';
        var parameters = 'adn=' + adn + '&msisdn=' + msisdn + '&operator=' + operator + '&service=' + service;
             
        $.ajax({
                type: "POST",
                url: url,
                data: parameters,
                success: function(result){					
					$("#userSubscriptionTable").html(result);
					
					$.ajax({
						async: "false",
						data: parameters,
						dataType: "json",
						url: pageUrl,
						type: 'POST',
						success: function(data) {
							if(data.status!="nodata"){
								$(".pagination ul").html(data.paging);
								$(".pagination ul").show();
								$("div#btnCheck").show();
							}
							else{
								achtungFailed("Data Not Found");
								$(".pagination ul").hide();
								$("div#btnCheck").hide();
							}
						}
					});
				}
		});
        
//        closeForm();
    });
});

function closeForm() {
    $(".boxcontent").slideUp();
}
    
function inactiveChecked(){
	var k=0;
	var size= "";	
	if(gbl_pageURI==""){
		size= parseInt($("input#choices").size());
		k=1;
	}
	else{
		size= parseInt(gbl_pageURI)+parseInt($("input#choices").size());
		k=parseInt(gbl_pageURI)+1;
	}
	
	var choice="";		 
	var parameters = '';
	for (k; k <= size; k++) {
		if ($(".choices-" + k).is(':checked')) {
			if (choice != "")
				choice += ",";
        		
        		choice += $(".choices-" + k).val();
        }
	}

	if(choice != ""){
		var answer = confirm ("Are you sure?");
		if (answer)
		{
		parameters = parameters + '&choice=' + choice;
				
			var url = base_url + 'cs/subscription/inactiveCheck';
				  
			$.ajax({
					type: "POST",
					url: url,
					data: parameters,
					success: function(result){
						var url = base_url + 'cs/subscription/getUserSubscriptionTable/'+gbl_pageURI;
						var parameters = 'adn=' + adn + '&msisdn=' + msisdn + '&operator=' + operator + '&service=' + service;
							 
						$.ajax({
								type: "POST",
								url: url,
								data: parameters,
								success: function(result){
									$("#userSubscriptionTable").html(result);
									tableListPagination(url);
								}
							});
					}
				});
		}
	}
}   

function inactiveBut($id)
{	
	var answer = confirm ("Are you sure?");
	if (answer)	{
			parameters = 'choice=' + $id;
			
			var url = base_url + 'cs/subscription/inactiveCheck';
			
			$.ajax({
					type: "POST",
					url: url,
					data: parameters,
					success: function(result){
						var url = base_url + 'cs/subscription/getUserSubscriptionTable/'+gbl_pageURI;
						var parameters = 'adn=' + adn + '&msisdn=' + msisdn + '&operator=' + operator + '&service=' + service ;
							 
						$.ajax({
								type: "POST",
								url: url,
								data: parameters,
								success: function(result){
									$("#userSubscriptionTable").html(result);
								}
							});
					}
				});
	}
}

function tableListPagination(url){
		var pageURI		= url.split('/');
			gbl_pageURI	= pageURI[7];
				        
        var pageUrl	= base_url + 'cs/subscription/pagination/'+ gbl_pageURI;
        var parameters = 'adn=' + adn + '&msisdn=' + msisdn + '&operator=' + operator + '&service=' + service;
             
        $.ajax({
                type: "POST",
                url: url,
                data: parameters,
                success: function(result){					
					$("#userSubscriptionTable").html(result);

					$.ajax({
						async: "false",
						data: parameters,
						dataType: "json",
						url: pageUrl,
						type: 'POST',
						success: function(data) {
							if(data.status!="nodata"){
								$(".pagination ul").html(data.paging);
								$(".pagination ul").show();
								$("div#btnCheck").show();
							}
							else{
								achtungFailed("Data Not Found");
								$(".pagination ul").hide();
								$("div#btnCheck").hide();
							}
						}
					});
				}
		});
}

function achtungFailed(message){
	$.achtung({
				timeout: 5, // Seconds
                className: 'achtungFail',
                icon: 'ui-icon-check',
                message: message
			});
}
   
    


