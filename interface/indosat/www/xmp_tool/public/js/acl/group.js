var gbl_id = 0,
method='',
path='';

$(document).ready(function() {
    getGroupList('');

    $("#search-form").submit(function() {
        getGroupList('');
        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getGroupList($(this).attr("href"));
        return false;
    });
    
    //- form--//
    $(".boxtoggle").click(function() {
        resetForm("group-form");
        $("#save").val("Save");
        $("#txt-group-name").focus();
    });
    
    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("group-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getGroupList('');
    });
       
    $('.check-menu').click(function(){
        var_id = $(this).attr("id"); 
        id=var_id.replace(/[a-zA-Z_-]/g,"");
        var_name = $(this).attr("name"); 
        name_id=var_name.replace(/[a-zA-Z]/g,"");
        //alert(var_name);
        if(this.checked==true)
        {
            $("#menu-"+name_id).attr('checked', true);
            $("input[name='child"+id+"']").attr('checked', true);
        }else{
            $("input[name='child"+id+"']").attr('checked', false);
            
        }
    });
       
    //- create new group -//
    $("#group-form").submit(function() {
        disabledForm("group-form");

        var getData  = "group-name=" + $("#txt-group-name").val();
        getData += "&group-desc=" + $("#txt-group-description").val();

        var menu       = "";
        $(".check-menu").each(function() {

            if ($("#" + this.id).is(':checked')) {
                if (menu != "")
                    menu += ",";

                menu += $("#" + this.id).val();
            }
        });

        getData += "&group-menu=" + menu;

        var url = "";

        if ($("#save").val() == "Save"){
            url = base_url + "acl/group/ajaxSaveGroup";
            submitMessage="Add Data Success";
        }
        else{
            url = base_url + "acl/group/ajaxUpdateGroup/" + gbl_id;
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
                    resetForm("group-form");
                    achtungSuccess(submitMessage);
                    $("#save").val("Save");
                    getGroupList('');
                }
                else {
                    if (data.status_group_name == false) {
                        achtungFailed(data.msg_group_name);
                        $("#txt-name").addClass("error-field");
                    }
                    else {
                        $("#txt-name").removeClass("error-field");
                    }
                }

                enabledForm("group-form");
                $("#txt-group-name").focus();

                return false;
            }
        });
		
        enabledForm("group-form");
        $("#txt-group-name").focus();

        return false;
    });
});

function getGroupList(url) {
    if (url == '')
        url = base_url + "acl/group/ajaxGetGroupList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val() + "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#group-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            
            return false;
        }
    });
}

function editGroup(id) {
    gbl_id = id;
    
    resetForm("group-form");
    $(".boxcontent").slideDown();
    disabledForm("group-form");
    $("#save").val("Update");
    
    url = base_url + "acl/group/ajaxEditGroup";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#loadContainer").slideDown();
            $("#txt-group-name").val(data.group_name);
            $("#txt-group-description").val(data.group_desc);

            $.each(data.group_menu, function(index, value) {
                if ($("#menu-" + value).val() == value)
                    $("#menu-" + value).attr("checked", true);
            });
            enabledForm("group-form");
            $("#txt-group-name").focus();
            
        }
    });
}

function deleteGroup(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "acl/group/ajaxDeleteGroup";

    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("group-form");
                    achtungSuccess("Delete Success");
                    getGroupList('');
                }
                else {
                    achtungFailed("Delete Failed");
                }
            }
        });
    }
}

function gotoMethodGroup(id){
    gbl_id = id;

    path= base_url + "acl/method_group";
    method = method || "post";
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
  
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", 'id');
    hiddenField.setAttribute("value", gbl_id);

    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
    
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
