$(document).ready(function() {

  // Navigation vers la gauche
  $('div.ui-page').live("swipeleft", function() {
         // recuperation de l'id
         var id = this.getAttribute('data-id'); 
         // calcul de l'url
         var url = Routing.generate('mobile_tache_next', { 'id' : id });
         // redirection
         window.location.href = url;
  }); // Fin de navigation gauche //


  // Navigation vers la droite
  $('div.ui-page').live("swiperight", function() {
        // recuperation de l'id
        var id = this.getAttribute('data-id'); 
        // calcul de 'url
        var url = Routing.generate('mobile_tache_previous', { 'id' : id });
        // redirection
        window.location.href = url;
  }); // Fin de navigation droite //

});
