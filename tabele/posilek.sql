CREATE TABLE posilek(
	id_menu INTEGER NOT NULL,
	id_zam INTEGER NOT NULL,
	CONSTRAINT posilek_pk PRIMARY KEY(id_menu, id_zam),
	CONSTRAINT posilek_menu_id_fk FOREIGN KEY(id_menu) REFERENCES menu(id_menu),
	CONSTRAINT posilek_zam_id_fk FOREIGN KEY(id_zam) REFERENCES zamowienie(id_zam)
);