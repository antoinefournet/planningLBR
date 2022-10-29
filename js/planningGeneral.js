let tableau = document.getElementById("tableau");
let slider = document.getElementById("myRange");
let output = document.getElementById("demo");
let plus = document.getElementById("zoom1");
let moins = document.getElementById("zoom0");

output.innerHTML = Math.round(slider.value * 100);

slider.oninput = function () {
  output.innerHTML = Math.round(this.value * 100);
  let valeur = parseFloat(this.value);
  tableau.style.transform = "scale(" + valeur + ")";
}

plus.onclick = function () {
  let str = tableau.style.transform;
  let nb = str.substr(6, 3);
  if (nb[1] == ')') nb = nb[0];
  let valeur = parseFloat(nb);
  if (valeur < 2) {
    valeur += 0.1;
  }
  tableau.style.transform = "scale(" + valeur + ")";
  output.innerHTML = Math.round(valeur * 100);
  slider.value = valeur;
}

moins.onclick = function () {
  let str = tableau.style.transform;
  let nb = str.substr(6, 3);
  if (nb[1] == ')') nb = nb[0];
  let valeur = parseFloat(nb);
  if (valeur > 0) {
    valeur -= 0.1;
  }
  tableau.style.transform = "scale(" + valeur + ")";
  output.innerHTML = Math.round(valeur * 100);
  slider.value = valeur;
}

$(document).ready(function () {
  $('td').hover(
    function (oEvent) {
      let elTd = $(oEvent.currentTarget),
        aTable = elTd.parents('table');
      aTable.find('td:nth-child(' + (elTd.index() + 1) + ')').toggleClass('colonne-style');
    });
});

/* case coché pour bloquer le curseur/zoom et activier le mode sticky */
function exemple() {

  if (document.getElementById("case").checked == true) {
    valeur = 1;
    tableau.style.transform = "scale(" + valeur + ")";
    output.innerHTML = Math.round(valeur * 100);
    slider.value = valeur;

    document.getElementById("myRange").disabled = true;
    document.getElementById("zoom1").disabled = true;
    document.getElementById("zoom0").disabled = true;
    cellules = document.getElementsByClassName("tabTitre");
    for (let i = 0; i < cellules.length; i++) {
      cellules[i].classList = "tabTitre stickyActive";
    }
  } else {
    document.getElementById("myRange").disabled = false;
    document.getElementById("zoom1").disabled = false;
    document.getElementById("zoom0").disabled = false;

    /* on veut pas sticky */
    cellules = document.getElementsByClassName("tabTitre");
    for (let i = 0; i < cellules.length; i++) {
      cellules[i].classList = "tabTitre";
    }
  }
}