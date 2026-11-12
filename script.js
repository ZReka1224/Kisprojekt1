document.addEventListener("DOMContentLoaded", () => {
const form = document.getElementById("regForm");

const nev = document.getElementById("nev");
const loginnev = document.getElementById("loginnev");
const jelszo = document.getElementById("jelszo");
const szerepkor = document.getElementById("szerepkor");
const mobil = document.getElementById("mobil");
const email = document.getElementById("email");

function ellenorizMezo(mezo, ervenyes) {
mezo.classList.toggle("is-valid", ervenyes);
mezo.classList.toggle("is-invalid", !ervenyes);

return ervenyes;
}

function ellenorizNev() {
const ertek = nev.value.trim();

return ellenorizMezo(
nev,
ertek.length > 0 && ertek.length <= 255
);
}

function ellenorizLogin() {
const ertek = loginnev.value.trim();

return ellenorizMezo(
loginnev,
ertek.length > 0 && ertek.length <= 100
);
}

function ellenorizJelszo() {
return ellenorizMezo(
jelszo,
jelszo.value.length > 0 &&
jelszo.value.length <= 25
);
}

function ellenorizSzerepkor() {
return ellenorizMezo(
szerepkor,
["felhasználó", "admin", "karbantartó"]
.includes(szerepkor.value)
);
}

function ellenorizMobil() {
const ertek = mobil.value.trim();

const minta = /^[0-9+\s()\-]+$/;

return ellenorizMezo(
mobil,
ertek.length > 0 &&
ertek.length <= 255 &&
minta.test(ertek)
);
}

function ellenorizEmail() {
const ertek = email.value.trim();

const minta = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

return ellenorizMezo(
email,
ertek.length <= 255 &&
minta.test(ertek)
);
}

nev.addEventListener("input", ellenorizNev);
loginnev.addEventListener("input", ellenorizLogin);
jelszo.addEventListener("input", ellenorizJelszo);
szerepkor.addEventListener("change", ellenorizSzerepkor);
mobil.addEventListener("input", ellenorizMobil);
email.addEventListener("input", ellenorizEmail);

form.addEventListener("submit", (esemeny) => {
const jo = [
ellenorizNev(),
ellenorizLogin(),
ellenorizJelszo(),
ellenorizSzerepkor(),
ellenorizMobil(),
ellenorizEmail()
].every(Boolean);

if (!jo) {
esemeny.preventDefault();

const elsoHibas =
form.querySelector(".is-invalid");

if (elsoHibas) {
elsoHibas.focus();
}
}
});

form.addEventListener("reset", () => {
setTimeout(() => {
form.querySelectorAll(
".is-valid, .is-invalid"
).forEach((mezo) => {
mezo.classList.remove(
"is-valid",
"is-invalid"
);
});
}, 0);
});
});