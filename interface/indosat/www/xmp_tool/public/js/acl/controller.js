var gbl_id  = "0",
gbl_menu_compare = "",
gbl_sort = "";

$(document).ready(function() {
    getControllerList('');

    $("#search-form").submit(function() {
        getControllerList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getControllerList($(this).attr("href"));
        return false;
    });
    
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("controller-form");
        $("#save").val("Save");
        $("#txt-menu-name").focus();
        $("#sort").hide();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("controller-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getControllerList('');
    });        
       
    //- create new controller -//
    $("#controller-form").submit(function() {
        disabledForm("controller-form");
        //  showFormLoader();
        
        var getData  = "txt-menu-name=" + $("#txt-menu-name").val();
        getData += "&txt-parent=" + $("#txt-parent").val();
        getData += "&txt-controller-link=" + $("#txt-controller-link").val();
        getData += "&txt-status=" + $("#txt-status").val();
        getData += "&txt-sort=" + $("#txt-sort").val();
        getData += "&txt-menu-name-compare=" + gbl_menu_compare;
        getData += "&txt-sort-old=" + gbl_sort;
                
        var url = "";

        if ($("#save").val() == "Save"){
            url = base_url + "acl/controller/ajaxSaveController";
            submitMessage="Add Data Success";
        }
        else{
            url = base_url + "acl/controller/ajaxUpdateController/" + gbl_id;
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
                    resetForm("controller-form");
                    achtungSuccess(submitMessage);
                    $("#save").val("Save");
                    getControllerList('');
                }
                else {
                    if (data.status_menu_name == false) {
                        achtungFailed(data.msg_menu_name);  
					    $("#txt-menu-name").addClass("error-field");
                    }
                    else {
                        $("#txt-menu-name").removeClass("error-field");
                    }

                    if (data.status_controller_link == false) {
						achtungFailed(data.msg_controller_link);
						$("#txt-controller-link").addClass("error-field");
                    }
                    else {
                        $("#txt-controller-link").removeClass("error-field");
					}
                    if (data.status_sort == false) {
                        achtungFailed(data.msg_sort); 
						$("#txt-sort").addClass("error-field");
                    }
                    else {
                        $("#txt-sort").removeClass("error-field");
                    }
                }

                enabledForm("controller-form");
                $("#txt-menu-name").focus();

                return false;
            }
        });
        enabledForm("controller-form");
        $("#txt-menu-name").focus();

        return false;
    });
});

function getControllerList(url) {
    if (url == '')
        url = base_url + "acl/controller/ajaxGetControllerList";

    $.ajax({
        async: "false",
		data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#controller-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
           
            return false;
        }
    });
}

function editController(id) {
    gbl_id = id;
    $("#sort").show();
    resetForm("controller-form");
    $(".boxcontent").slideDown();
    disabledForm("controller-form");
    $("#save").val("Update");
    
    url = base_url + "acl/controller/ajaxEditController";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-menu-name").val(data.menu_name);
            $("#txt-parent").val(data.parent);
            $("#txt-controller-link").val(data.controller_link);
            $("#txt-sort").val(data.sort);
            $("#txt-status").val(data.status);
            gbl_menu_compare=data.menu_name;
            gbl_sort=data.sort;
            
            enabledForm("controller-form");
            $("#txt-menu-name").focus();
                        
            return false;
        }
    });
}

function deleteController(id) {
    gbl_id = id;    

    var answer = confirm("Are you sure?");

    url = base_url + "acl/controller/ajaxDeleteController";

    if (answer) {
                
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					resetForm("controller-form");
					achtungSuccess("Delete Success");
                    getControllerList('');
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
