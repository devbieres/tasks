$(document).ready(function() {

  // Navigation vers la gauche
  $('#tache_show').live("swipeleft", function() { slide(this, 'mobile_tache_next', 'slideleft'); });

  // Navigation vers la droite
  $('#tache_show').live("swiperight", function() { slide(this, 'mobile_tache_previous','slideright'); });

});

function slide(div, route, transition) {
        // recuperation de l'id
        var id = div.getAttribute('data-id'); 
        // calcul de 'url
        var url = Routing.generate(route, { 'id' : id });
        // redirection
        window.location.href = url;
}
