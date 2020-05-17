CREATE TABLE zamowienie(
	id_zam SERIAL NOT NULL,
	id_kur INTEGER,
	id_kl INTEGER NOT NULL,
	id_kuch INTEGER,
	stan INTEGER NOT NULL,
	cena NUMERIC(7,2),
	adres VARCHAR(64) NOT NULL,
	data_zam DATE,
	data_przyg DATE,
	data_kur DATE,
	data_real DATE,
	CONSTRAINT zamowienie_pk PRIMARY KEY(id_zam),
	CONSTRAINT zamowienie_kurier_id_fk FOREIGN KEY(id_kur) REFERENCES kurier(id_kur),
	CONSTRAINT zamowienie_klient_id_fk FOREIGN KEY(id_kl) REFERENCES klient(id_kl),
	CONSTRAINT zamowienie_kucharz_id_fk FOREIGN KEY(id_kl) REFERENCES kucharz(id_kuch)	
);