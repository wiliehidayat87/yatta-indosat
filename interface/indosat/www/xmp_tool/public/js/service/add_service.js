$(document).ready(function() {
    $("tr[attr='hide']").hide();
    $(".keyword_list").click(function(){
        var key = $(this).attr("attr");
        $("." + key).toggle();
        return false;
    });
    
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
		
    var name = $( "#name" ),
    email = $( "#email" ),
    password = $( "#password" ),
    allFields = $( [] ).add( name ).add( email ).add( password ),
    tips = $( ".validateTips" );

    function updateTips( t ) {
        tips
        .text( t )
        .addClass( "ui-state-highlight" );
        setTimeout(function() {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    }

    function checkLength( o, n, min, max ) {
        if ( o.val().length > max || o.val().length < min ) {
            o.addClass( "ui-state-error" );
            updateTips( "Length of " + n + " must be between " +
                min + " and " + max + "." );
            return false;
        } else {
            return true;
        }
    }

    function checkRegexp( o, regexp, n ) {
        if ( !( regexp.test( o.val() ) ) ) {
            o.addClass( "ui-state-error" );
            updateTips( n );
            return false;
        } else {
            return true;
        }
    }
		
    $( "#dialog-form" ).dialog({
        autoOpen: false,
        width: 300,
        modal: true,
        buttons: {
            "Add Operator": function() {
                var bValid = true;
                if ( bValid ) {
                    $('#form_list_operator').submit();
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

    $( ".add-operator" )
    .click(function() {
        var service_name = $("#service_name").val();
        var adn = $("#adn").val();
        var url = base_url+"service/add_service/ajaxGetOperatorAvailable/"+this.id+"/"+service_id+"/"+service_name+"/"+adn;
        $('#form_list_operator').load(url, function() {
            $( "#dialog-form" ).dialog( "open" );
        })
    });
    $( "#add-new-keyword" )
    .click(function() {
        var service_id = $("#service_id").val();
        var service_name = $("#service_name").val();
        var adn = $("#adn").val();
        var url = base_url+"service/add_service/ajaxGetOperator/"+service_id+"/"+service_name+"/"+adn;
        $('#form_list_operator').load(url, function() {
            $( "#dialog-form" ).dialog( "open" );
        })
    });

})