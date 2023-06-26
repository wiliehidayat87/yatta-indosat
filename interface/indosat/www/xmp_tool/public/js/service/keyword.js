$(document).ready(function() {
    $( "#tabs" ).tabs();
    $( ".tabs2" ).tabs();
    resetForm("mechanism-form");
    if(param=='update'){
        editDataReply(service_id,operator_id);
    }

    operator=$(".operator").attr('operator');

    $(".operator").click(function(){
        operator=$(this).attr('operator');
    });

    Array.max = function( array ){
        return Math.max.apply( Math, array );
    };              

    operatorid = gbl_operator.replace(/[#tabs-]+/g, "");
    id = operatorid.split(',');

    var operatorLength = Array.max(id);

    $(".operator").hide();

    /* For Delete Condition Show or Hide */
   
    var idD = $("li#tabs2.ui-tabs-selected a").attr('mechanism');
    getDeleteButtonCondition(idD);
   
    $("#tabs").tabs({
        select: function(event, ui) { 
            var tabID = ui.panel.id;
            opi = tabID.split('-');
            
            idOperator = opi[1];
            
            var idFind = $("#tabsPat"+idOperator).find("ul#tabs2 li#tabs2.ui-tabs-selected a").attr("mechanism");
            getDeleteButtonCondition(idFind);
        }
    });      

    $('.tabs2').bind('tabsselect', function(event, ui) {
        var url = ui.panel.id;
        mecha = url.split('-');

        idOp = mecha[1];
        idMc = mecha[2];

        getDeleteButtonCondition(idMc);
       
    });
    
    $( ".tabs2 span.ui-icon-close" ).live( "click", function() {
        $("#tabsPat" +$(this).attr("operator") + ".tabs2").tabs("remove", "#tabs-" +$(this).attr("operator")+"-"+ $(this).attr("mechanism"));
        var sendData  = "tab-data=" + nptabs;
        sendData += "&mechanism_id=" + $(this).attr("mechanism");
			
        var url = "";
        url = base_url + "service/creator/removeMechaTab";
						
        $.ajax({
            async: "false",
            data: sendData,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#fieldset2-" + data.id_operator ).show();
                    $("#tabsPat" + data.id_operator + ".tabs2").tabs("add", "#tabs-" +data.id_operator+"-"+ data.id_mecha, data.mecha_pattern);
                    $("#tabsPat"+data.id_operator).find("ul#tabs2 li.ui-state-default").last().attr("id","tabs2");
                    $("#tabsPat"+data.id_operator).find("ul#tabs2 li.ui-state-default a").last().attr("class","pattern testing-2").attr("mechanism",data.id_mecha).attr("operator",data.id_operator);

                    var vclass= $("#tabsPat"+data.id_operator).find("#tabs-"+data.id_operator+"-"+data.id_mecha).attr("class");
                    $("#tabsPat"+data.id_operator).find("#tabs-" +data.id_operator+"-"+ data.id_mecha).attr("class","testing-2 " +vclass);
                    $("#tabsPat"+data.id_operator).find("#tabs-" +data.id_operator+"-"+ data.id_mecha).append(data.form_tab);					
								                   
                }
                else {
                    achtungFailed(data.msg_tab);
                }
				
                return false;
            }
        });
    });
   
    /* End For Delete Condition Show or Hide */
   
    $("div[name='keywordForm']").hide();

    $.each(id, function(x, val) {                
        for (i = 1; i <= operatorLength; i++) {                                        
            if (i == val){                    
                $("a[href='#tabs-"+ i + "']").show();
                $("#keywordForm-"+ i ).show();
            }
        }

        $("input[name='btnClose-"+val+"']").live('click', function() {
            closeNewKeywordForm($(this).attr("id"));           
            return false;
        });

        $("input[name='btnkeyword-"+val+"']").live('click', function() {
            addNewPatternTabs($(this).attr("id"), $("input[name='txt-new-keyword-"+val+"']").val());           
            return false;
        });
    });

    $("div[name='add-keyword-form']").hide();       
    $("input[name='add-keyword']").live('click', function() {
        getNewKeywordForm($(this).attr("id"));        
        return false;
    });

    $("input[name='add-message']").live('click', function() {
        getAddReplyPattern($(this).attr("operator"), $(this).attr("mechanism"));          
        return false;
    });

    $(".buttonDelete").live('click', function() {
        deleteMessageReply($(this).attr("markOperator"), $(this).attr("markMechanism"), $(this).attr("id"));
        return false;
    });              
    
    $(".module-list").live('change',function()
    {
        var id=$(this).val();
        var dataString = 'id='+ id;

        $.ajax
        ({
            type: "POST",
            url: base_url + "service/creator/ajaxLoadCharging",
            data: dataString,
            cache: false,
            dataType: "json",
            success: function(data)
            {
                $("#select_service").append(data.status);
            }
        });

    });

    $(".module-list2").live('change', function(){
        mid = $(this).attr("id");
        pMid = Number(mid.replace(/[modulePattern]+/g, ""));
        
        idMecha = $(this).attr("mechanism");
        handler = $(this).val();
        nHandler = handler.split('-');
        phandler = handler.split('_');                        
        gHandler = phandler[0].split('-');
        
        if (gHandler[1] == "service" && phandler[1] == "module")
            sHandler = phandler[2];
        else
            sHandler = nHandler[1];
        
        $("#repMessage-"+pMid+"-"+idMecha).html("");        
        
        var sendParam  = "module_name=" + sHandler;
        sendParam += "&operator_id=" + pMid;
        sendParam += "&mechanism_id=" + idMecha;
            
        var url = "";
        url = base_url + "service/getmodule";
            
        $.ajax({
            async: "false",
            data: sendParam,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#repMessage-"+pMid+"-"+idMecha).append("<p><b>Message</b></p><textarea name='repMessage["+idMecha+"][]' rows='5' cols='40' mechanism="+idMecha+ " handler="+nHandler[1]+"["+idMecha+"][]'></textarea>");
                    $("#repMessage-"+pMid+"-"+idMecha).append(data.formModule);
                }
            }            
        });            
    });

    $("#mechanism-form").submit(function(){
        var  dataMechanism   = "service-id=" + gbl_service_id;
        dataMechanism  += "&operator-list=" + gbl_operator;
        //           dataMechanism += "&attrCheckBox=" + $("#chkRepAttrMsg").attr("othervalue");
        dataMechanism  += $(this).serialize();                       
            
        //           console.log(dataMechanism);
        //       alert("Under Construction");
        //       window.location = base_url + "service/creator/";
        if ($("#submit").val() == "Submit")
            url = base_url + "service/creator/ajaxAddKeyword";
        else
            url = base_url + "service/creator/ajaxUpdateKeyword/" + gbl_service_id;

        $.ajax({
            async: "false",
            data: dataMechanism,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {

                }

                hideFormLoader();
                return false;

            }
        });
        return false;
    });
    
    activeServiceCustom();
    $('#box-custom-handler-'+operator).live('change', function(){
        activeServiceCustom();
    })
});

function activeServiceCustom(){
    if($('#box-custom-handler-'+operator).is(':checked') === true){
        $('#custom-handler-'+operator).removeAttr('disabled');
        $('#custom-handler-'+operator).show();
    }else{
        $('#custom-handler-'+operator).attr('disabled', true);
        $('#custom-handler-'+operator).hide()
    }
}

function getNewKeywordForm(oid){
    $("input[class='"+oid+"']").val('');
    $("div[id='"+oid+"']").show();    
}

function closeNewKeywordForm(vid){
    cid = vid.replace(/[btnClose-]+/g, "");
    $("div[id='add-new-keyword-"+cid+"']").hide();
}

function addNewPatternTabs(pid, ptabs){
    nid = pid.replace(/[btnKeyword-]+/g, "");
    nptabs = $.trim(ptabs);

    var sendData  = "tab-data=" + nptabs;
    sendData += "&service_id=" + gbl_service_id;
    sendData += "&operator_id=" + operator;
    if($('#box-custom-handler-'+operator).is(':checked') === true){
        sendData += "&handler="+$('#custom-handler-'+operator).val();
    }
			
    var url = "";
    url = base_url + "service/creator/createNewTab";
						
    $.ajax({
        async: "false",
        data: sendData,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            if (data.status == true) {
                $("#fieldset2-" + data.id_operator ).show();
                $("#tabsPat" + data.id_operator + ".tabs2").tabs("add", "#tabs-" +data.id_operator+"-"+ data.id_mecha, data.mecha_pattern);
                $("#tabsPat"+data.id_operator).find("ul#tabs2 li.ui-state-default").last().attr("id","tabs2");
                $("#tabsPat"+data.id_operator).find("ul#tabs2 li.ui-state-default a").last().attr("class","pattern testing-2").attr("mechanism",data.id_mecha).attr("operator",data.id_operator);

                var vclass= $("#tabsPat"+data.id_operator).find("#tabs-"+data.id_operator+"-"+data.id_mecha).attr("class");
                $("#tabsPat"+data.id_operator).find("#tabs-" +data.id_operator+"-"+ data.id_mecha).attr("class","testing-2 " +vclass);
                $("#tabsPat"+data.id_operator).find("#tabs-" +data.id_operator+"-"+ data.id_mecha).append(data.form_tab);					
								                   
            }
            else {
                achtungFailed(data.msg_tab);
            }
				
            return false;
        }
    });
}

function getAddReplyPattern(opNum, mecNum){
    var numDel  = $("div[hint='"+opNum+"-"+mecNum+"']").last().attr("id");
    pnumDel = numDel.replace(/[repContent]+/g, "");
    num = pnumDel.split('-');

    var newNumber  = new Number(parseInt(num[1]) + 1);

    var newEleme = $("div[hint='"+opNum+"-"+mecNum+"']").last().clone().attr("id", "repContent-" + newNumber +"-"+mecNum).attr("counter", newNumber);
    newEleme.find(".message_reply").attr("id", "repMessage-"+newNumber+"-"+mecNum);
    newEleme.find("#label_module").attr("for", "txt-module-pattern-"+opNum+"-"+mecNum+"-" + newNumber);
    newEleme.find("#select_module select").attr("id", "modulePattern" + newNumber).attr("counter", newNumber).val("");
    newEleme.find("#label_service").attr("for", "txt-service-pattern-"+opNum+"-"+mecNum+"-" + newNumber);
    newEleme.find("#select_service select").attr("id", "servicePattern" + newNumber).attr("counter", newNumber).val("");            
    newEleme.find(".buttonDelete").attr("id", "btnDel"+mecNum+"-"+ newNumber);

    // insert the new element after the last "duplicatable" input field
    $("div[hint='"+opNum+"-"+mecNum+"']").last().after(newEleme);
    var delNum = Number($("input[mark='deleButton"+mecNum+"']").length);
    
    if (delNum > 1)
        $("input[id='btnDel"+mecNum+"-"+delNum+"']").show();
    else
        $("input[id='btnDel"+mecNum+"-"+delNum+"']").hide();
}

function deleteMessageReply(opNum, mecNum, did){    
    pdid = did.replace(/[btnDel-]+/g, "");
    var repNum = Number($("div[hint='"+opNum+"-"+mecNum+"']").length);    
    if(repNum > 1){
        $("#repContent-"+ pdid+"-"+mecNum).slideUp('fast', function(){
            $(this).remove()
        });        
    }
    
    if(repNum == 2)
        $("input[mark='deleButton"+mecNum+"']").hide();    
}

function getDeleteButtonCondition(idMechanism){
    //alert(idMechanism);
    var cek = $("input[mark='deleButton"+idMechanism+"']").length;
    //alert(cek);
    if (cek > 1)
        $("input[id='btnDel"+idMechanism+"-"+cek+"']").show();
    else
        $("input[id='btnDel"+idMechanism+"-"+cek+"']").hide();
}

function achtungFailed(message){
    $.achtung({
        timeout: 5, // Seconds
        className: 'achtungFail',
        icon: 'ui-icon-check',
        message: message
    });
}
function editDataReply(service_id,operator_id){
    var sendData  = "service_id=" + service_id;
    sendData += "&operator_id=" + operator_id;
    // sendData += "&operator_id=" + operator;
    var cpr_mecha_id;
    var url = "";
    url = base_url + "service/creator/ajaxGetDataUpdate";
          
    $.ajax({
        async: "false",
        data: sendData,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            if (data.status == true) {
                $.each(data, function(index, value) {   
                    
                    
                    if(value['mecha_id']==cpr_mecha_id){
                        $('#addMessage-'+ value['operator_id'] +'-'+value['mecha_id']).trigger('click');
                    }
                    else{
                        cpr_mecha_id=value['mecha_id'];
                    }
                    
                    console.log(value['mecha_id']);
                //    console.log($('#addMessage-'+ value['operator_id'] +'-'+value['mecha_id']));
                   
                //                    console.log(value['charging_id']);
                //                    console.log(value['charging_name']);
                //                    console.log(value['message']);
                //                    console.log(value['module_id']);
                //                    console.log(value['module_handler']);
                //                    console.log(value['module_name']);
                             
                });
                
            //    alert(data[0].charging_id);
            }
        }            
    });           
}
