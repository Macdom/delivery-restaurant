CREATE TABLE dzien_pracy(
	id_kur INTEGER NOT NULL,
	id_poj INTEGER NOT NULL,
	CONSTRAINT dzien_pracy_pk PRIMARY KEY(id_kur, id_poj),
	CONSTRAINT dzien_pracy_kurier_id_fk FOREIGN KEY(id_kur) REFERENCES kurier(id_kur),
	CONSTRAINT dzien_pracy_pojazd_id_fk FOREIGN KEY(id_poj) REFERENCES pojazd(id_poj)
);