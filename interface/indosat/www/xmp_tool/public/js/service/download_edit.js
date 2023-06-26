var statusD = "",
    old_title = "";

$(document).ready(function(){
    old_title = $("#txt-title").val();

    $("#txt-title").live("keyup",function(){
        
        var dataPost    = "name=" + $("#name-list").val();
            dataPost   += "&title=" + $(this).val();
            dataPost   += "&old-title=" + old_title;
            
        $.ajax({
            async: "false",
            data: dataPost,
            dataType: "json",
            url: base_url + "service/download_edit/ajaxCheckTitle",
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    statusD = data.status;
                    achtungSuccess("Title can be used");
                    $("#txt-title").removeClass("error-field");
                }
                else {
                    if (data.status == false) {
                        statusD = data.status;
                        achtungFailed(data.message);
                        $("#txt-title").addClass("error-field");
                    }else {
                        $("#txt-title").removeClass("error-field");
                    }
                }                
            }
        });
    });
    
    $("#name-list").live("keyup",function(){
        var dataPost    = "name=" + $(this).val();
            dataPost   += "&title=" + $("#txt-title").val();
        
        $.ajax({
            async: "false",
            data: dataPost,
            dataType: "json",
            url: base_url + "service/download_edit/ajaxCheckTitle",
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    statusD = data.status;
                    achtungSuccess("Title can be used");
                    $("#txt-title").removeClass("error-field");
                }
                else {
                    if (data.status == false) {
                        statusD = data.status;
                        achtungFailed(data.message);
                        $("#txt-title").addClass("error-field");
                    }else {
                        $("#txt-title").removeClass("error-field");
                    }
                }                
            }
        });
    });
   
    // Create New Service //
    $("#download-add-form").submit(function(){

        var name        = $("#name-list").val(),
            title       = $("#txt-title").val(),
            disclaimer  = $("#txt-disclaimer").val(),
            description = $("#txt-description").val(),
            type        = $("#type-list").val(),
            statusD     = true;

        if(name==="" || title==="" || disclaimer==="" || description==="" || type===""){
            if (name === "") {                        
                achtungFailed('Name Field is Required');
                $("#name-list").addClass("error-field");
            }
            else {
                $("#name-list").removeClass("error-field");
            }

            if (title === "") {
                achtungFailed('Title Field is Required');
                $("#txt-title").addClass("error-field");
            }
            else {
                if(statusD == false){
                    $("#txt-title").addClass("error-field");  
                    achtungFailed('Service title for that service name is already exist, please try another combination');
                }else{
                    $("#txt-title").removeClass("error-field");
                }                
            }

            if (disclaimer === "") {
                achtungFailed('Disclaimer Field is Required');
                $("#txt-disclaimer").addClass("error-field");
            }
            else {
                $("#txt-disclaimer").removeClass("error-field");
            }

            if (description === "") {
                achtungFailed('Description Field is Required');
                $("#txt-description").addClass("error-field");
            }
            else {
                $("#txt-description").removeClass("error-field");
            }

            if (type === "") {
                achtungFailed('Type Field is Required');
                $("#type-list").addClass("error-field");
            }
            else {
                $("#type-list").removeClass("error-field");
            }
            return false;
        }        
        else{
            $("#name-list").removeClass("error-field");
            $("#txt-disclaimer").removeClass("error-field");
            $("#txt-description").removeClass("error-field");
            $("#type-list").removeClass("error-field");
            if(statusD == false){
                $("#txt-title").addClass("error-field");
                achtungFailed('Service title for that service name is already exist, please try another combination');
                return false;
            }else{
                $("#txt-title").removeClass("error-field");
            }
        }
                                   
    });
    
    $("#reset-form").live("click", function(){
        $("#name-list").removeClass("error-field");
        $("#txt-disclaimer").removeClass("error-field");
        $("#txt-description").removeClass("error-field");
        $("#type-list").removeClass("error-field");
        $("#txt-title").removeClass("error-field");
    });
});

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