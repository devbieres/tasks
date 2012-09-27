$(document).ready(function() {
    ///*
    // TODO : pas top car s'applique à toutes les dates ... bonn y'en a qu'une mais quand même ...
    var picker = $( ".formulaire_date", this );
    picker.scroller({ 
                preset: 'date',
                mode: 'mixed',
                dateOrder: 'ddmmyyyy'
          });
    //*/

    // Gestion du click sur le bouton
    $("#cleardate").click(function() {
        $(".formulaire_date").val('');
        return false;
    });

    

});
