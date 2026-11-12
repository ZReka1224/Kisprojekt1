KISPROJEKT – REGISZTRÁCIÓ

Fájlok:
- index.php Az űrlap és a PHP feldolgozás
- script.js JavaScript-validáció
- style.css Kiegészítő megjelenés
- adatbazis.sql Az adatbázis és a tábla létrehozása

TELEPÍTÉS (például XAMPP):

1. Másold a kisprojekt_regisztracio mappát
a htdocs könyvtárba.

2. Indítsd el az Apache és MySQL szolgáltatást.

3. Nyisd meg a phpMyAdmin felületet, és futtasd
az adatbazis.sql fájl tartalmát.

4. Ha a MySQL felhasználóneved vagy jelszavad
nem root és üres jelszó, módosítsd az index.php
fájlban a db_user és db_pass értékét.

5. Böngészőben nyisd meg:
http://localhost/kisprojekt_regisztracio/

MEGJEGYZÉSEK:

- A jelszó password_hash() segítségével,
hash-elve kerül mentésre.

- A JavaScript-validáció kényelmi ellenőrzés;
a PHP is ellenőrzi az adatokat.

- Az autó és a PC mező a táblában BOOLEAN:
1 = van, 0 = nincs.

- A jelszó beviteli korlátja 25 karakter,
de az adatbázismező VARCHAR(255), mert a
biztonságos jelszóhash hosszabb lehet.