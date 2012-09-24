$(document).ready(function() {
    var picker = $( ".formulaire_date", this );
    ///*
    picker.mobipick({
         locale: "fr",
    });//*/

    $("#datenulle").bind( "tap", function() {
            picker.mobipick("option", "date", null).mobipick("updateDateInput");
    });
    //*/
});
