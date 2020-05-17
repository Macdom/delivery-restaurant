CREATE TABLE prod_menu(
	id_prod INTEGER NOT NULL,
	id_menu INTEGER NOT NULL,
	CONSTRAINT prod_menu_pk PRIMARY KEY(id_prod, id_menu),
	CONSTRAINT prod_menu_produkt_id_fk FOREIGN KEY(id_prod) REFERENCES produkt(id_prod),
	CONSTRAINT prod_dost_menu_id_fk FOREIGN KEY(id_menu) REFERENCES menu(id_menu)
);