var gbl_id = 0,
gbl_name='',
gbl_description='',
gbl_handler='';


$(document).ready(function() {
    getModuleList('');

    $("#search-form").submit(function() {
        getModuleList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getModuleList($(this).attr("href"));
        return false;
    });
     
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("module-form");
        $("#save").val("Save");
        $("#txt-name").focus();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("module-form");;
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getModuleList('');
    });          
     
    //- create new custom Handler -//
    $("#module-form").submit(function() {
        disabledForm("module-form");
        
        var datapost  = "txt-name=" + $("#txt-name").val();
            datapost += "&txt-description=" + $("#txt-description").val();
            datapost += "&txt-handler=" + $("#txt-handler").val();
            datapost += "&name-compare=" + gbl_name ;
            datapost += "&description-compare=" + gbl_description ;
            datapost += "&handler-compare=" + gbl_handler ;
                                      
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "masterdata/module/ajaxAddModule";
            submitMessage="Add Data Success";
		}
        else{
            url = base_url + "masterdata/module/ajaxUpdateModule/" + gbl_id ;
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
                    resetForm("module-form");
                    achtungSuccess(submitMessage);
                    getModuleList('');
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
                    if (data.status_handler == false) {
						achtungFailed(data.msg_handler)
                        $("#txt-handler").addClass("error-field");
                    }
                    else {
                        $("#txt-handler").removeClass("error-field");
                    }
				}

                enabledForm("module-form");
                $("#txt-name").focus();

                return false;
            }
        });

        return false;
    });
   
});

function getModuleList(url) {
    if (url == '')
        url = base_url + "masterdata/module/ajaxGetModuleList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#module-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            
            return false;
        }
    });
}

function editModule(id) {
    gbl_id = id;
   
    resetForm("module-form");
    $(".boxcontent").slideDown();
    disabledForm("module-form");
    $("#save").val("Update");
   
    url = base_url + "masterdata/module/ajaxEditModule";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-name").val(data.name);
            $("#txt-description").val(data.description);
            $("#txt-handler").val(data.handler);
            $("#txt-name").focus();
            gbl_name 		= data.name;
            gbl_description	= data.description;
			gbl_handler		= data.handler;
                
            enabledForm("module-form");
            
            $("#txt-name").focus();
        }
    });
}

function deleteModule(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "masterdata/module/ajaxDeleteModule/" + gbl_id;
 
    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					resetForm("module-form");
					achtungSuccess("Delete Success");
                    getModuleList('');
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


