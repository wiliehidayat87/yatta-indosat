function toggleForm() {
    $("#loadContainer").toggle();
}

function closeForm() {
    $("#loadContainer").slideUp();
}

function openForm() {
    $("#loadContainer").slideDown();
}

$(function() {
	$(".warning").hide();
    
    // SlideUp form visibility
    $("button#cancel").live('click', function() {
        $("#loadContainer").slideUp();
    });
});
