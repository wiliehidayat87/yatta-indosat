//if($('.daterange input').length > 0){
//$(function(){
//	 $('.daterange input').daterangepicker({dateFormat: 'M d, yy',					
//		datepickerOptions: {
//			changeMonth: false,
//			changeYear: true
//		}}); 
//});
//};
$(document).ready(function() {
	$('#searchBar,').hide();
	$('.reporttable table tr:odd').addClass('odd');
	$('.boxoption').click(function(){
		$(this).parent('.roundedbox').find('.boxoption-content').slideToggle();
	})
	$('.boxtoggle').click(function(){
		$(this).parent().parent('.roundedbox').find('.boxcontent').slideToggle('slow');
	});
//        Example Using achtung
        
//	var notice = $.achtung({
//    timeout: 5, // Seconds
//    className: 'achtungSuccess',
//    icon: 'ui-icon-check',
//    message: 'This is a timed notification!'
//	});
//	
//	$.achtung({
//    timeout: 7, // Seconds
//    className: 'achtungFail',
//    icon: 'ui-icon-check',
//    message: 'This is a timed notification!'
//	});
//	
//	$.achtung({
//    timeout: 9, // Seconds
//    className: 'achtungSuccess',
//    icon: 'ui-icon-check',
//    message: 'This is a timed notification!'
//	});
});

	function showLoader() {
        setCenterLoader();
        $(".ajax-loader").fadeIn(100);
    }

    function hideLoader() {
        $(".ajax-loader").fadeOut(100);
    }

    function setCenterLoader() {
        var pTop  = ($(".table-list-area").height() - $(".ajax-loader").outerHeight()) / 2,
            pLeft = ($(".table-list-area").width() - $(".ajax-loader").outerWidth()) / 2;

        $(".ajax-loader").css({"top": pTop + "px", "left": pLeft + "px"});
    }

    function showFormLoader() {
        setCenterFormLoader();
        $(".form-loader").fadeIn(100);
    }

    function hideFormLoader() {
        $(".form-loader").fadeOut(100);
    }

    function setCenterFormLoader() {
        var pTop  = ($(".loadContainer").height() - $(".form-loader").outerHeight()) / 2,
            pLeft = ($(".loadContainer").width() - $(".form-loader").outerWidth()) / 2;

        $(".form-loader").css({"top": pTop + "px", "left": pLeft + "px"});
    }

    function enabledForm(formId) {
        $("#" + formId + " input").attr("disabled", false);
        $("#" + formId + " textarea").attr("disabled", false);
    }

    function disabledForm(formId) {
        $("#" + formId + " input").attr("disabled", true);
        $("#" + formId + " textarea").attr("disabled", true);
    }

    function resetForm(id) {
        $('#' + id).each(function() {
	        this.reset();
        });
        $('#' + id + " input:checkbox").attr("checked", false);
        $('#' + id + " input").removeClass("error-field");
        $('#' + id + " span.error-font").html("");
    }
