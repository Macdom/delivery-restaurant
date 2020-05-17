SET search_path TO projekt;

--logowanie
SELECT COUNT(*) FROM kurier WHERE telefon = '$telefon';
SELECT id_adm FROM admin WHERE nazwa = '$login' AND haslo = '$pass';
SELECT COUNT(*) FROM klient WHERE telefon = '$telefon';
SELECT id_kl FROM klient WHERE telefon = '$telefon';
SELECT COUNT(*) FROM kurier WHERE telefon = '$telefon';
SELECT id_kur FROM kurier WHERE telefon = '$telefon';
SELECT COUNT(*) FROM kucharz WHERE telefon = '$telefon';
SELECT id_kuch FROM kucharz WHERE telefon = '$telefon';

--dodawanie
INSERT INTO klient(imie, nazwisko, telefon) VALUES('$imie', '$nazwisko', '$telefon');
INSERT INTO kucharz(imie, nazwisko, placa, telefon) VALUES('$imie', '$nazwisko', '$placa', '$telefon');
INSERT INTO kurier(placa, dostepny, nazwisko, imie, telefon) VALUES('$placa', 'false', '$nazwisko', '$imie', '$telefon');
INSERT INTO pojazd(model, cena, dostepny) VALUES('$model', '$cena', 'true');
INSERT INTO produkt(cena, nazwa) VALUES('$cena', '$nazwa');
INSERT INTO menu(nazwa, cena, typ) VALUES('$nazwa', '$cena', '$typ');

--ekran administratora
SELECT nazwa FROM admin WHERE id_adm = '$id';
CREATE VIEW lista_ad AS SELECT z.id_zam, z.adres, z.cena, z.stan, z.data_real, kl.imie ||' '|| kl.nazwisko AS klient, kur.imie ||' '|| kur.nazwisko AS kurier, kuch.imie ||' '|| kuch.nazwisko AS kucharz FROM zamowienie z LEFT JOIN klient kl ON kl.id_kl = z.id_kl LEFT JOIN kurier kur ON z.id_kur = kur.id_kur LEFT JOIN kucharz kuch ON z.id_kuch = kuch.id_kuch GROUP BY z.stan, z.id_zam, kur.imie, kur.nazwisko, kuch.imie, kuch.nazwisko, kl.imie, kl.nazwisko ORDER BY stan;
SELECT nazwa FROM menu INNER JOIN posilek ON menu.id_menu = posilek.id_menu WHERE posilek.id_zam = $idZ;

--ekran klienta
SELECT imie FROM klient WHERE id_kl = '$id';
SELECT nazwisko FROM klient WHERE id_kl = '$id';
SELECT COUNT(*) FROM zamowienie WHERE id_kl='$id' AND stan <> 3;
SELECT stan FROM zamowienie WHERE id_kl='$id' ORDER BY stan LIMIT 1;
SELECT imie, nazwisko, telefon FROM kucharz INNER JOIN zamowienie on zamowienie.id_kuch = kucharz.id_kuch WHERE id_kl='$id' AND zamowienie.stan = 1;
SELECT imie, nazwisko, telefon FROM kucharz INNER JOIN zamowienie on zamowienie.id_kuch = kucharz.id_kuch WHERE id_kl='$id' AND zamowienie.stan = 2;
SELECT imie, nazwisko, telefon FROM kurier INNER JOIN zamowienie on zamowienie.id_kur = kurier.id_kur WHERE id_kl='$id' AND zamowienie.stan = 2;
CREATE VIEW lista_kl AS SELECT z.id_zam, z.adres, z.cena, z.data_real, kur.imie ||' '|| kur.nazwisko AS kurier, kuch.imie ||' '|| kuch.nazwisko AS kucharz FROM zamowienie z INNER JOIN kurier kur ON z.id_kur = kur.id_kur INNER JOIN kucharz kuch ON z.id_kuch = kuch.id_kuch WHERE z.id_kl = $id;
SELECT z.id_kl, z.adres, z.cena, z.data_zam, z.data_przyg, z.data_kur, z.data_real, kur.imie ||' '|| kur.nazwisko AS kurier, kuch.imie ||' '|| kuch.nazwisko AS kucharz FROM zamowienie z INNER JOIN kurier kur ON z.id_kur = kur.id_kur INNER JOIN kucharz kuch ON z.id_kuch = kuch.id_kuch WHERE z.id_zam = $idZ;
SELECT nazwa FROM menu INNER JOIN posilek ON menu.id_menu = posilek.id_menu WHERE posilek.id_zam = $idZ;

--ekran kucharza
SELECT imie FROM kucharz WHERE id_kuch = '$id';
SELECT nazwisko FROM kucharz WHERE id_kuch = '$id';
SELECT z.id_zam, k.imie, k.nazwisko, k.telefon  FROM zamowienie z INNER JOIN klient k ON k.id_kl = z.id_kl WHERE stan = 0;
SELECT nazwa FROM menu INNER JOIN posilek ON menu.id_menu = posilek.id_menu WHERE posilek.id_zam = $zam;
UPDATE zamowienie SET id_kuch = $id, stan = 1, data_przyg = now() WHERE id_zam = $zam;

--ekran kuriera
SELECT imie FROM kurier WHERE id_kur = '$id';
SELECT nazwisko FROM kurier WHERE id_kur = '$id';
SELECT id_poj, model FROM pojazd WHERE dostepny='true' ORDER BY id_poj;
INSERT INTO dzien_pracy (id_kur, id_poj) VALUES($id, $wybor);
UPDATE kurier SET dostepny='true' WHERE id_kur = $id;
UPDATE pojazd SET dostepny='false' WHERE id_poj = $wybor;
SELECT  model FROM pojazd WHERE id_poj = '$pojazdID';
SELECT z.id_zam, k.imie, k.nazwisko, k.telefon  FROM zamowienie z INNER JOIN klient k ON k.id_kl = z.id_kl WHERE stan = 1;
UPDATE zamowienie SET id_kur = $id, stan = 2, data_kur = now() WHERE id_zam = $zam;
UPDATE zamowienie SET id_kur = $id, stan = 3, data_real = now() WHERE id_zam = $zam;
UPDATE kurier SET dostepny='false' WHERE id_kur = $id;
UPDATE pojazd SET dostepny='true' WHERE id_poj = $pojazdID;

--aktualizowanie
UPDATE klient SET imie = '$imie', nazwisko = '$nazwisko', telefon = '$telefon' WHERE id_kl = $id;
DELETE FROM klient WHERE id_kl = $id;
UPDATE kurier SET imie = '$imie', nazwisko = '$nazwisko', placa = '$placa', telefon = '$telefon' WHERE id_kur = $id;
DELETE FROM kurier WHERE id_kur = $id;
UPDATE kucharz SET imie = '$imie', nazwisko = '$nazwisko', placa = '$placa', telefon = '$telefon' WHERE id_kuch = $id;
DELETE FROM kucharz WHERE id_kuch = $id;
UPDATE menu SET nazwa = '$nazwa', typ = '$typ' WHERE id_menu = $id;
UPDATE menu SET cena = $cena WHERE id_menu = $id;
DELETE FROM prod_menu WHERE id_menu = $id;
INSERT INTO prod_menu (id_prod, id_menu) VALUES ($obecny_skladnik, $id);
DELETE FROM posilek WHERE id_menu = $id;
DELETE FROM prod_menu WHERE id_menu = $id;
DELETE FROM menu WHERE id_menu = $id;

--zamowienie
SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'glowne';
SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'zupa';
SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'dodatek';
SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'napoj';
SELECT id_menu, nazwa,cena FROM menu WHERE typ = 'glowne';
SELECT COALESCE(MAX(id_zam),0) FROM zamowienie;
INSERT INTO zamowienie(id_kl, adres, stan) VALUES('$idK', '$adres', 0);
INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $glowne);
INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $zupa);
INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $dodatek1);
INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $dodatek2);
INSERT INTO posilek(id_zam, id_menu) VALUES ($zam, $napoj);
UPDATE zamowienie SET cena = $cena, data_zam = now() WHERE id_zam = $zam;
DELETE FROM posilek WHERE id_zam = $zam;
DELETE FROM zamowienie WHERE id_zam = $zam;
ALTER SEQUENCE zamowienie_id_zam_seq RESTART WITH $zam;
