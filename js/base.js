$( document ).ready(function() {
    $( ".show_hideReg" ).on( "click", function() {
        $(".slidingDivReg").toggle();
        if ($(".slidingDiv").is(":visible")) {
                $(".slidingDiv").hide();
        }
    });

    $( ".show_hide" ).on( "click", function() {
        $(".slidingDiv").toggle();
        if ($(".slidingDivReg").is(":visible")) {
                $(".slidingDivReg").hide();
        }
    });

    $( ".error" ).on( "click", function() {
        $( ".error" ).remove();
    });

    $( ".success" ).on( "click", function() {
        $( ".success" ).remove();
    });
});
