var gbl_id = 0;

$(document).ready(function() {
    getMethodGroupList('');

    $("#search-form").submit(function() {
        getMethodGroupList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getMethodGroupList($(this).attr("href"));

        return false;
    });

    //- form--//
    $(".boxtoggle").click(function() {
        resetForm("scan-method-group-form");
       $("#scan").val("Scan");
        $("#txt-group-name").focus();
    });
    
     //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("scan-method-group-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getMethodGroupList('');
    });
            
    //- create new group -//
    $("#scan-method-group-form").submit(function() {        
        
            var controller = "";

                for (i = 1; i <= $(".controller-list").size(); i++) {
                    if ($("#controller-" + i).is(':checked')) {
                        if (controller != "")
                            controller += ",";

                        controller += $("#controller-" + i).val();
                    }
                }

            var getData  = "controller-list=" + controller;
                getData += "&id=" + cont_id;
                
            var url = "";

            if ($("#scan").val() == "Scan"){
                url = base_url + "acl/method_group/ajaxScanMethodGroup";
                submitMessage="Scan Data Success";
			}
            else{
                url = base_url + "acl/method_group/ajaxUpdateScanMethodGroup/" + gbl_id;
				submitMessage="Update Data Success";
			}
			
            $.ajax({
                async: "false",
                data: getData,
                dataType: "json",
                url: url,
                type: 'POST',
                success: function(data) {
                    if (data.status == true) {
                        resetForm("scan-method-group-form");
                        achtungSuccess(submitMessage);
                        $("#scan").val("Scan");
                        getMethodGroupList('');
                    }
                    else {
                        achtungFailed("Error");
                    }

                    enabledForm("scan-method-group-form");
                    $("#txt-group-name").focus();

                    return false;
                }
            });

        enabledForm("scan-method-group-form");
        $("#txt-group-name").focus();

        return false;
    });
});

function getMethodGroupList(url) {
    if (url == '')
        url = base_url + "acl/method_group/ajaxGetMethodGroupList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val() + "&id=" + cont_id + "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#method-group-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
           return false;
        }
    });
}

function backToGroup()
{
    window.location= base_url + "acl/group";
}

function activeMethodGroup(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "acl/method_group/ajaxActiveMethodGroup";

	if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					achtungSuccess("Activated Success");
                    getMethodGroupList('');
                }
                else {
                    achtungFailed("Activated Failed");
                }
            }
        });
	}
}

function inactiveMethodGroup(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "acl/method_group/ajaxInactiveMethodGroup";

	if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					resetForm("scan-method-group-form");
					achtungSuccess("Deactivated Success");
                    getMethodGroupList('');
                }
                else {
                    achtungFailed("Deactivated Failed");
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
