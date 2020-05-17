CREATE TABLE produkt(
	id_prod SERIAL NOT NULL,
	nazwa VARCHAR(32) NOT NULL,
	cena NUMERIC(7,2) NOT NULL,
	CONSTRAINT produkt_pk PRIMARY KEY(id_prod)
);