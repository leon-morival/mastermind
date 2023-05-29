document.addEventListener("DOMContentLoaded", function () {
  var selectionsCouleur = document.getElementsByClassName("selection-couleur");
  var inputProposition = document.getElementById("proposition");

  for (var i = 0; i < selectionsCouleur.length; i++) {
    selectionsCouleur[i].addEventListener("click", function () {
      var couleur = this.getAttribute("data-color");
      inputProposition.value += couleur;
    });
  }
});
