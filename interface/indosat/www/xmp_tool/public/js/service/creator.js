var gbl_id = 0,
gbl_pattern='',
gbl_operator='',
gbl_service='',
method='',
path='',
counter='1'
;

$(document).ready(function() {

    /*
    $(".showEditDialog").click(function(){
        var url = base_url+"service/add_service/getIniFormEdit/"+this.id;
        alert(url);
        $('#form_edit_fileini').load(url, function() {
            $( "#dialog-form" ).dialog( "open" );
        })
        $( "#dialog-form" ).dialog( "open" );
    });
*/
    var urlsubmit = base_url + '/service/add_service/editIniFile';

    $( "#dialog-form" ).dialog({
        autoOpen: false,
        width: 500,
        modal: true,
        buttons: {
            "Edit": function() {
                var bValid = true;
                if ( bValid ) {
                    var service_name = $("#ini_service_name").val();
                    var operator_name = $("#ini_operator").val();

                    /* $('#form_edit_inifile').submit(); */
                    $.post( urlsubmit, $("#form_edit_inifile").serialize() );
                    /* refresh */

                    selectIniFile( operator_name , service_name);
                    selectIniFile( operator_name , service_name);
                    $( this ).dialog( "close" );
                }
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
            allFields.val( "" ).removeClass( "ui-state-error" );
        }
    });


    getCreatorList('');


    $("#search-form").submit(function() {
        getCreatorList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getCreatorList($(this).attr("href"));

        return false;
    });
 
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("creator-form");
        $("#save").val("Save");
        $("#txt-pattern").focus();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("creator-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getCreatorList('');
    }); 

    //- create new service -//
    $("#creator-form").submit(function() {
        disabledForm("creator-form");
               
        var datapost  = "txt-pattern=" + $("#txt-pattern").val();
        datapost += "&txt-operatorId=" + $("#operatorId").val();
        datapost += "&txt-serviceId=" + $("#serviceId").val();
        datapost += "&pattern_compare=" + gbl_pattern;
        datapost += "&operatorId_compare=" + gbl_operator;
        datapost += "&serviceId_compare=" + gbl_service;
                     
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "service/creator/ajaxAddNewCreator";
            submitMessage="Add Data Success";  
        }
        else{
            url = base_url + "service/creator/ajaxUpdateCreator/" + gbl_id;
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
                    resetForm("creator-form");
                    achtungSuccess(submitMessage);
                    getCreatorList('');
                }
                else {
                    //username
                    if (data.status_pattern == false) {
                        achtungFailed(data.status_pattern)
                        $("#txt-pattern").addClass("error-field");
                    }
                    else {
                        $("#txt-pattern").removeClass("error-field");
                    }
                }
                
                enabledForm("creator-form");
                $("#txt-pattern").focus();

                return false;
            }
        });

        return false;
    });
    
});

function getCreatorList2(url) {
    if (url == '')
        url = base_url + "service/creator/ajaxGetCreatorList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#service-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);

            return false;
        }
    });
}

function editSCreator(id) {
    window.location = base_url + 'service/add_service/edit/'+id;
}


function editCreator(id) {
    gbl_id = id;
   
    resetForm("creator-form");
    $(".boxcontent").slideDown();
    disabledForm("creator-form");
    $("#save").val("Update");
    
    url = base_url + "service/creator/ajaxEditCreator";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-pattern").val(data.pattern);
            $("#operatorId").val(data.operator_id);
            $("#serviceId").val(data.service_id);
            $("#txt-pattern").focus();
            gbl_pattern=data.pattern;
            gbl_operatorId=data.operator_id;
            
            enabledForm("creator-form");
          
        }
    });
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

function filterby(){
    getCreatorList( '' );
}

function getCreatorList(url) {
    if (url == '')
        url = base_url + "service/creator/ajaxGetCreatorList";

    var serviceid = $("select#filter_service").val();
    var operatorid = $("select#filter_operator").val();

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val() +  "&operatorid=" + operatorid + "&serviceid=" + serviceid,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#service-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            $("#total_data").html(data.total);
            //alert(data.result);
            return false;
        }
    });
}

function selectHandler(operator, service_name){
    var handler_type = $('#handler-type-'+operator).val();
    if(handler_type == 'custom'){
        $("#creator-"+operator).hide("slow");
        $("#module-content-"+operator).empty();
        $("#content-operator-"+operator).empty();
        $("#custom-"+operator).show("slow");
        getCustomHandler(operator, service_name);
    }else if(handler_type == 'creator'){
        $("#custom-"+operator).hide("slow");
        $("#custom-"+operator).empty();
        $("#creator-"+operator).show("slow");
    }
    return false;
}

function getCustomHandler(operator, service_name){
    var url, sendParam;
    url = base_url + "service/add_service/getCustomHandler";
    sendParam = "operator=" + operator + "&service_name=" + service_name;
    $.ajax({
        async: "false",
        data: sendParam,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            if (data.status == true) {
                $("#custom-"+operator).append(data.data);
            }
        }
    });

}

function addModule(operator, adn){
    var url, sendParam = '';
    url = base_url + "service/add_service/getSelectModule";
    sendParam += "operator=" + operator + "&adn=" + adn + "&counter=" + counter;
    $.ajax({
        async: "false",
        data: sendParam,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            if (data.status == true) {
                $("#content-operator-"+operator).append(data.data);
            }
        }
    });

    counter ++;
    
}

function getModule(operator, adn){
    var select_module = $('#select-module-'+operator).val();

    var url, sendParam = "";
    url = base_url + "service/add_service/getModulePull";

    if(select_module != ''){
        sendParam += "operator=" + operator + "&module=" + select_module + "&adn=" + adn;

        $.ajax({
            async: "false",
            data: sendParam,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#module-content-"+operator).html(data.data);
                }
            }
        });
    }
}

function removeModule(operator, id){
    if(confirm("Are you sure want to delete this module ?"))
        $("#module-area-"+operator+"-"+id).remove();

    return false;
}

function getModule2(operator, adn, id, ctr){
    var select_module = $('#select-module-'+operator+"-"+id).val();

    var url, sendParam = "";
    url = base_url + "service/add_service/getModule";

    if(select_module != ''){
        sendParam += "operator=" + operator + "&module=" + select_module + "&adn=" + adn + "&counter=" + ctr;

        $.ajax({
            async: "false",
            data: sendParam,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#content-operator-"+operator+"-"+id).html(data.data);
                }
            }
        });
    }
}

function selectIniFile(operator, service_name){
    var url, sendParam = "";
    url = base_url + "service/add_service/readIniFile";
    var option = $("#select-custom-handler-"+operator).val();
    var handler_name = $("#select-custom-handler-"+operator+" option[value='"+option+"']").text();

    sendParam += "service_name=" + service_name + "&operator=" + operator + "&handler_name=" + handler_name ;

    $.ajax({
        async: "false",
        data: sendParam,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            if (data.status == true) {
                $("#custom-"+operator+"-inifile").html(data.data);
            }else{
                alert(data.data);
            }
        }
    });
}

function showEditDialog(key,value,price, service, operator){

    var url = base_url+"service/add_service/getIniFormEdit";
    var sendParam = "key="+key+"&value="+value+"&price="+price+"&service="+service+"&operator="+operator;
  
    $.ajax({
        async: "false",
        data: sendParam,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            if (data.status == true) {
                $("#form_edit_inifile").html(data.data);
                $( "#dialog-form" ).dialog( "open" );
            }else{
                alert('Failed Load Ini');
            }
        }
    });

/*
    $('#form_edit_inifile').load(url, function() {
        $( "#dialog-form" ).dialog( "open" );
    })
    */

}


   