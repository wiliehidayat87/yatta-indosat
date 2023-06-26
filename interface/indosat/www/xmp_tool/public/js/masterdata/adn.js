var gbl_id = 0,
    gbl_adn_name='',
    gbl_description='';

$(document).ready(function() {
    getAdnList('');

	$("#search-form").submit(function() {
        getAdnList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getAdnList($(this).attr("href"));

        return false;
    });
    
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("adn-form");
        $("#save").val("Save");
        $("#txt-adn-name").focus();
        $("#sort").hide();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("adn-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getAdnList('');
    });          
    
    //- create new adn -//
    $("#adn-form").submit(function() {
        disabledForm("adn-form");
       
        var datapost  = "txt-adn-name=" + $("#txt-adn-name").val();
            datapost += "&txt-description=" + $("#txt-description").val();
            datapost += "&adn_name_compare=" + gbl_adn_name;
            datapost += "&description_compare=" + gbl_description ;
                         
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "masterdata/adn/ajaxAddAdn";
            submitMessage="Add Data Success";	
		}
        else{
            url = base_url + "masterdata/adn/ajaxUpdateAdn/" + gbl_id ;
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
                    $("#submit").val("Save");
                    resetForm("adn-form");
                    achtungSuccess(submitMessage);
                    getAdnList('');
                }
                else {
                    //username
                    if (data.status_adn_name == false) {
						achtungFailed(data.msg_adn_name);
                        $("#txt-adn-name").addClass("error-field");
                    }
                    else {
                        $("#txt-adn-name").removeClass("error-field");
                    }
				}
                

                enabledForm("adn-form");
                $("#txt-adn-name").focus();
               
                return false;
            }
        });

        return false;
    });
    
});

function getAdnList(url) {
    if (url == '')
        url = base_url + "masterdata/adn/ajaxGetAdnList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#adn-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
                        
            return false;
        }
    });
}

function editAdn(id) {
    $("#txt-description").removeClass("error-field");  

    gbl_id = id;

    resetForm("adn-form");
    $(".boxcontent").slideDown();
    disabledForm("adn-form");
    $("#save").val("Update");

    
    url = base_url + "masterdata/adn/ajaxEditAdn";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-adn-name").val(data.adn_name);
            $("#txt-description").val(data.description);
            $("#txt-adn-name").focus();
            gbl_adn_name = data.adn_name;
            gbl_description = data.description;
    
            enabledForm("adn-form");
            
            $("#txt-adn-name").focus();
        }
    });
}

function deleteAdn(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "masterdata/adn/ajaxDeleteAdn/" + gbl_id;
 
	if (answer) {
        
            $.ajax({
                async: "false",
                data: "id=" + id,
                dataType: "json",
                url: url,
                type: 'POST',
                success: function(data) {
                    if (data.status == true) 
                    {
						resetForm("adn-form");
						achtungSuccess("Delete Success");
						getAdnList('');
                    }
                    else {
                        achtungFailed("Delete Failed");
                    }
                }
            });
	}
}

function validationCheck() {
    if($("#txt-adn-name").val()!="" && $("#txt-description").val()!="")
    {
        if($("#txt-adn-name").val()==gbl_adn_name && $("#txt-description").val()==gbl_description)
        {
            resetForm("adn-form");
            enabledForm("adn-form");
            $("#txt-adn-name").focus();
            return false;
        }
    }
    
    if($("#txt-adn-name").val()=="" || $("#txt-description").val()=="")
    {
        if ($("#txt-adn-name").val() == "") {
            $("#txt-adn-name").addClass("error-field");
            $("#inf-adn-name").addClass("error-font").html("required field");
        }
        else {
            $("#txt-adn-name").removeClass("error-field");
            $("#inf-adn-name").removeClass("error-font").html("");
        }
        if ($("#description").val() == "") {
            $("#description").addClass("error-field");
            $("#inf-description").addClass("error-font").html("required field");
        }
        else {
            $("#description").removeClass("error-field");
            $("#inf-description").removeClass("error-font").html("");
        }
        
         enabledForm("adn-form");
         $("#txt-adn-name").focus();
         return false;
       
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
