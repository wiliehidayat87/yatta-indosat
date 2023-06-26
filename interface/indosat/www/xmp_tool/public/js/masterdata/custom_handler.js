var gbl_id = 0,
gbl_name='';
gbl_description='';

$(document).ready(function() {
    getCustomHandlerList('');

    $("#search-form").submit(function() {
        getCustomHandlerList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getCustomHandlerList($(this).attr("href"));
        return false;
    });
     
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("custom-handler-form");
        $("#save").val("Save");
        $("#txt-name").focus();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("custom-handler-form");;
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getCustomHandlerList('');
    });          
     
    //- create new custom Handler -//
    $("#custom-handler-form").submit(function() {
        disabledForm("custom-handler-form");
        
        var datapost  = "txt-name=" + $("#txt-name").val();
            datapost += "&txt-description=" + $("#txt-description").val();
            datapost += "&name-compare=" + gbl_name ;
            datapost += "&description-compare=" + gbl_description ;
                                    
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "masterdata/custom_handler/ajaxAddCustomHandler";
            submitMessage="Add Data Success";
		}
        else{
            url = base_url + "masterdata/custom_handler/ajaxUpdateCustomHandler/" + gbl_id ;
            submitMessage="Update Data Success";
		}
				
        $.ajax({
            async: "false",
            data: datapost,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#save").val("Save");
                    resetForm("custom-handler-form");
                    achtungSuccess(submitMessage);
                    getCustomHandlerList('');
                }
                else {
                    //username
                    if (data.status_name == false) {
						achtungFailed(data.msg_name)
                        $("#txt-name").addClass("error-field");
					}
                    else {
                        $("#txt-name").removeClass("error-field");
                    }
				}

                enabledForm("custom-handler-form");
                $("#txt-name").focus();

                return false;
            }
        });

        return false;
    });
    
});

function getCustomHandlerList(url) {
    if (url == '')
        url = base_url + "masterdata/custom_handler/ajaxGetCustomHandlerList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#custom-handler-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            
            return false;
        }
    });
}

function editCustomHandler(id) {
    gbl_id = id;
   
    resetForm("custom-handler-form");
    $(".boxcontent").slideDown();
    disabledForm("custom-handler-form");
    $("#save").val("Update");
   
    url = base_url + "masterdata/custom_handler/ajaxEditCustomHandler";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-name").val(data.name);
            $("#txt-description").val(data.description);
            $("#txt-name").focus();
            gbl_name = data.name;
                
            enabledForm("custom-handler-form");
            
            $("#txt-name").focus();
        }
    });
}

function deleteCustomHandler(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "masterdata/custom_handler/ajaxDeleteCustomHandler/" + gbl_id;
 
    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					resetForm("custom-handler-form");
					achtungSuccess("Delete Success");
                    getCustomHandlerList('');
                }
                else {
                    achtungFailed("Delete Failed");
                }
            }
        });
    }
}

function achtungSuccess(message){
	$.achtung({
				timeout: 5, // Seconds
                className: 'achtungSuccess',
                icon: 'ui-icon-check',
                message: message
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


