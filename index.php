<?php
$hibak = [];
$siker = false;

$nev = $loginnev = $jelszo = $szerepkor = $mobil = $email = "";
$auto = "van";
$pc = "van";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
$nev = trim($_POST["nev"] ?? "");
$loginnev = trim($_POST["loginnev"] ?? "");
$jelszo = $_POST["jelszo"] ?? "";
$szerepkor = $_POST["szerepkor"] ?? "";
$auto = $_POST["auto"] ?? "van";
$pc = $_POST["pc"] ?? "van";
$mobil = trim($_POST["mobil"] ?? "");
$email = trim($_POST["email"] ?? "");

if ($nev === "" || mb_strlen($nev) > 255) {
$hibak[] = "A név megadása kötelező (legfeljebb 255 karakter).";
}

if ($loginnev === "" || mb_strlen($loginnev) > 100) {
$hibak[] = "A login név megadása kötelező (legfeljebb 100 karakter).";
}

if ($jelszo === "" || mb_strlen($jelszo) > 25) {
$hibak[] = "A jelszó megadása kötelező (legfeljebb 25 karakter).";
}

if (!in_array($szerepkor, ["felhasználó", "admin", "karbantartó"], true)) {
$hibak[] = "Válassz érvényes szerepkört.";
}

if (!in_array($auto, ["van", "nincs"], true) ||
!in_array($pc, ["van", "nincs"], true)) {
$hibak[] = "Az autó és a PC mezőnél válassz a van/nincs lehetőségek közül.";
}

if ($mobil === "" || mb_strlen($mobil) > 255 ||
!preg_match('/^[0-9+\s()\-]+$/', $mobil)) {
$hibak[] = "Adj meg érvényes telefonszámot.";
}

if ($email === "" || mb_strlen($email) > 255 ||
!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$hibak[] = "Adj meg érvényes e-mail-címet.";
}

if (!$hibak) {
$db_host = "localhost";
$db_name = "Kisprojekt";
$db_user = "root";
$db_pass = "";

try {
$pdo = new PDO(
"mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
$db_user,
$db_pass,
[
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]
);

$jelszo_hash = password_hash(
$jelszo,
PASSWORD_DEFAULT
);

$sql = "INSERT INTO reg
(nev, loginnev, jelszo, szerepkor, auto, pc, mobil, email)
VALUES
(:nev, :loginnev, :jelszo, :szerepkor, :auto, :pc, :mobil, :email)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
":nev" => $nev,
":loginnev" => $loginnev,
":jelszo" => $jelszo_hash,
":szerepkor" => $szerepkor,
":auto" => $auto === "van" ? 1 : 0,
":pc" => $pc === "van" ? 1 : 0,
":mobil" => $mobil,
":email" => $email
]);

$siker = true;

$nev = $loginnev = $jelszo = "";
$szerepkor = $mobil = $email = "";
$auto = $pc = "van";

} catch (PDOException $e) {
$hibak[] = "Nem sikerült menteni az adatokat. Ellenőrizd az adatbázis beállításait és a táblát.";
}
}
}

function e($ertek) {
return htmlspecialchars(
(string)$ertek,
ENT_QUOTES,
"UTF-8"
);
}
?>
<!doctype html>
<html lang="hu">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kisprojekt – Regisztráció</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>

<link href="style.css" rel="stylesheet">
</head>

<body class="bg-light">
<main class="container py-5">
<div class="card shadow-sm mx-auto form-card">
<div class="card-body p-4 p-md-5">

<h1 class="h3 text-center mb-4">
Regisztráció
</h1>

<?php if ($siker): ?>
<div class="alert alert-success" role="alert">
A regisztráció sikeres, az adatok elmentve.
</div>
<?php endif; ?>

<?php if ($hibak): ?>
<div class="alert alert-danger" role="alert">
<strong>Kérlek, javítsd a következő hibákat:</strong>
<ul class="mb-0 mt-2">
<?php foreach ($hibak as $hiba): ?>
<li><?= e($hiba) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form id="regForm" method="post" action="" novalidate>

<div class="mb-3">
<label for="nev" class="form-label">Név</label>
<input
type="text"
class="form-control"
id="nev"
name="nev"
maxlength="255"
value="<?= e($nev) ?>"
required
>
<div class="invalid-feedback">
A név kötelező, legfeljebb 255 karakter lehet.
</div>
</div>

<div class="mb-3">
<label for="loginnev" class="form-label">
Login név
</label>
<input
type="text"
class="form-control"
id="loginnev"
name="loginnev"
maxlength="100"
value="<?= e($loginnev) ?>"
required
>
<div class="invalid-feedback">
A login név kötelező, legfeljebb 100 karakter lehet.
</div>
</div>

<div class="mb-3">
<label for="jelszo" class="form-label">Jelszó</label>
<input
type="password"
class="form-control"
id="jelszo"
name="jelszo"
maxlength="25"
required
>
<div class="invalid-feedback">
A jelszó kötelező, legfeljebb 25 karakter lehet.
</div>
</div>

<div class="mb-3">
<label for="szerepkor" class="form-label">
Szerepkör
</label>

<select
class="form-select"
id="szerepkor"
name="szerepkor"
required
>
<option value="">Válassz…</option>

<option value="felhasználó"
<?= $szerepkor === "felhasználó" ? "selected" : "" ?>>
felhasználó
</option>

<option value="admin"
<?= $szerepkor === "admin" ? "selected" : "" ?>>
admin
</option>

<option value="karbantartó"
<?= $szerepkor === "karbantartó" ? "selected" : "" ?>>
karbantartó
</option>
</select>

<div class="invalid-feedback">
Válassz egy szerepkört.
</div>
</div>

<fieldset class="mb-3">
<legend class="form-label fs-6">Autó</legend>

<div class="form-check form-check-inline">
<input
class="form-check-input"
type="radio"
name="auto"
id="autoVan"
value="van"
<?= $auto === "van" ? "checked" : "" ?>
>
<label class="form-check-label" for="autoVan">
Van
</label>
</div>

<div class="form-check form-check-inline">
<input
class="form-check-input"
type="radio"
name="auto"
id="autoNincs"
value="nincs"
<?= $auto === "nincs" ? "checked" : "" ?>
>
<label class="form-check-label" for="autoNincs">
Nincs
</label>
</div>
</fieldset>

<fieldset class="mb-3">
<legend class="form-label fs-6">PC</legend>

<div class="form-check form-check-inline">
<input
class="form-check-input"
type="radio"
name="pc"
id="pcVan"
value="van"
<?= $pc === "van" ? "checked" : "" ?>
>
<label class="form-check-label" for="pcVan">
Van
</label>
</div>

<div class="form-check form-check-inline">
<input
class="form-check-input"
type="radio"
name="pc"
id="pcNincs"
value="nincs"
<?= $pc === "nincs" ? "checked" : "" ?>
>
<label class="form-check-label" for="pcNincs">
Nincs
</label>
</div>
</fieldset>

<div class="mb-3">
<label for="mobil" class="form-label">Mobil</label>
<input
type="tel"
class="form-control"
id="mobil"
name="mobil"
maxlength="255"
value="<?= e($mobil) ?>"
required
>
<div class="invalid-feedback">
A telefonszám kötelező; számokat, + jelet,
szóközt, zárójelet és kötőjelet használj.
</div>
</div>

<div class="mb-4">
<label for="email" class="form-label">E-mail</label>
<input
type="email"
class="form-control"
id="email"
name="email"
maxlength="255"
value="<?= e($email) ?>"
required
>
<div class="invalid-feedback">
Adj meg érvényes e-mail-címet, például nev@domain.hu.
</div>
</div>

<div class="d-flex gap-2 justify-content-center">
<button type="submit" class="btn btn-primary px-4">
Küldés
</button>

<button type="reset" class="btn btn-outline-secondary px-4">
Mégsem
</button>
</div>

</form>
</div>
</div>
</main>

<script src="script.js"></script>
</body>
</html>