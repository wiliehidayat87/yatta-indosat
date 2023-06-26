var id          = 0,
    statusD     = "",
    statusS     = "",
    statusP     = "",
    statusL     = "",
    old_title   = "";

$(document).ready(function(){
    resetForm("content-add-form");
    old_title       = $("#hidden-title").val();
   
   $("#txt-title").live("keyup",function(){
        var dataPost    = "code=" + $("#txt-contentcode").val();
            dataPost   += "&title=" + $(this).val();
            dataPost   += "&old-title=" + old_title;
        
        $.ajax({
            async: "false",
            data: dataPost,
            dataType: "json",
            url: base_url + "service/download_content_edit/ajaxCheckTitle",
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
    
    $("#txt-contentcode").live("keyup",function(){        
        var dataPost    = "code=" + $(this).val();
            dataPost   += "&title=" + $("#txt-title").val();
        
        $.ajax({
            async: "false",
            data: dataPost,
            dataType: "json",
            url: base_url + "service/download_content_edit/ajaxCheckTitle",
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    statusD = data.status;
                    if(titleD!==""){
                        achtungSuccess("Title can be used");
                    }
                    $("#txt-contentcode").removeClass("error-field");
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
    
    $("#txt-sort").live("keyup",function(){        
        var dataPost    = "sort=" + $(this).val();
        
        $.ajax({
            async: "false",
            data: dataPost,
            dataType: "json",
            url: base_url + "service/download_content_edit/ajaxCheckNumericSort",
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    statusS = data.status;
                    $("#txt-sort").removeClass("error-field");
                }
                else {
                    if (data.status == false) {
                        statusS = data.status;
                        achtungFailed(data.message);
                        $("#txt-sort").addClass("error-field");
                    }else {
                        $("#txt-sort").removeClass("error-field");
                    }
                }                
            }
        });
    });
    
    $("#txt-price").live("keyup",function(){        
        var dataPost    = "price=" + $(this).val();
        
        $.ajax({
            async: "false",
            data: dataPost,
            dataType: "json",
            url: base_url + "service/download_content_edit/ajaxCheckNumericPrice",
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    statusP = data.status;
                    $("#txt-price").removeClass("error-field");
                }
                else {
                    if (data.status == false) {
                        statusP = data.status;
                        achtungFailed(data.message);
                        $("#txt-price").addClass("error-field");
                    }else {
                        $("#txt-price").removeClass("error-field");
                    }
                }                
            }
        });
    });        
    
    $("#txt-limit").live("keyup",function(){        
        var dataPost    = "limit=" + $(this).val();
        
        $.ajax({
            async: "false",
            data: dataPost,
            dataType: "json",
            url: base_url + "service/download_content_edit/ajaxCheckNumericLimit",
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    statusL = data.status;
                    $("#txt-limit").removeClass("error-field");
                }
                else {
                    if (data.status == false) {
                        statusL = data.status;
                        achtungFailed(data.message);
                        $("#txt-limit").addClass("error-field");
                    }else {
                        $("#txt-limit").removeClass("error-field");
                    }
                }                
            }
        });
    });
    
    // Create New Service //
    $("#content-add-form").submit(function(){      
        var code        = $("#txt-contentcode").val(),
            title       = $("#txt-title").val(),
            price       = $("#txt-price").val(),
            limit       = $("#txt-limit").val(),
            sort        = $("#txt-sort").val(),
            statusD     = true,
            statusS     = true,
            statusP     = true,
            statusL     = true;            

        if(code==="" || title==="" || price===""){
            if (code === "") {                        
                achtungFailed('Content Code Field is Required');
                $("#txt-contentcode").addClass("error-field");
            }
            else {
                $("#txt-contentcode").removeClass("error-field");
            }

            if (title === "") {
                achtungFailed('Title Field is Required');
                $("#txt-title").addClass("error-field");
            }
            else {
                if(statusD == false){
                    $("#txt-title").addClass("error-field");  
                    achtungFailed('Service title for that content code is already exist, please try another combination');
                }else{
                    $("#txt-title").removeClass("error-field");
                }                
            }

            if (price === "") {
                achtungFailed('Price Field is Required');
                $("#txt-price").addClass("error-field");
            }
            else {
                $("#txt-price").removeClass("error-field");
            }

            return false;
        }        
        else{
            $("#txt-contentcode").removeClass("error-field");
            $("#txt-title").removeClass("error-field");            
            if(statusD == false){
                $("#txt-title").addClass("error-field");
                achtungFailed('Service title for that content code is already exist, please try another combination');
                return false;
            }else{
                $("#txt-title").removeClass("error-field");
            }
            if(statusS == false){
                $("#txt-sort").addClass("error-field");
                achtungFailed('Input must numeric');
                return false;
            }else{
                $("#txt-sort").removeClass("error-field");
            }
            if(statusP == false){
                $("#txt-price").addClass("error-field");
                achtungFailed('Input must numeric');
                return false;
            }else{
                $("#txt-price").removeClass("error-field");
            }
            if(statusL == false){
                $("#txt-limit").addClass("error-field");
                achtungFailed('Input must numeric');
                return false;
            }else{
                $("#txt-limit").removeClass("error-field");
            }
        }
                                   
    });
       
    $("#reset-form").live("click", function(){
        $("#txt-contentcode").removeClass("error-field");
        $("#txt-title").removeClass("error-field");
        $("#txt-price").removeClass("error-field"); 
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

