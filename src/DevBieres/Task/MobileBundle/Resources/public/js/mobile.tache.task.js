$(document).ready(function() {
    var picker = $( ".formulaire_date", this );
    picker.mobipick({
         locale: "fr",
         dateFormat: "dd/MM/yyyy",
    });
    picker.bind( "change", function() {
       // formatted date, like yyyy-mm-dd              
       var date = $( this ).val();
       // JavaScript Date object
       var dateObject = $( this ).mobipick( "option", "date" );
    });
    $("#datenulle").bind( "tap", function() {
            picker.mobipick("option", "date", null).mobipick("updateDateInput");
    });
       
});
