CREATE SEQUENCE "menu_item_seq" START 1;

CREATE TABLE "menu_item"
(
	"ID" int NOT NULL DEFAULT nextval('menu_item_seq'),
	"Name" varchar(255) NOT NULL,
	"Url" varchar(127) NOT NULL,
	"Parent" int,
	"Menu_ID" int NOT NULL,
	"Sort" int NOT NULL DEFAULT currval('menu_item_seq'),
	CONSTRAINT "menu_item_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "menu_item_UN_Name" UNIQUE ("Name", "Parent", "Menu_ID"),
	CONSTRAINT "menu_item_FK_Parent" FOREIGN KEY ("Parent")
		REFERENCES "menu_item" ("ID"),
	CONSTRAINT "menu_FK_Menu_ID" FOREIGN KEY ("Menu_ID")
		REFERENCES "menu" ("ID")
);

ALTER SEQUENCE "menu_item_seq" OWNED BY "menu_item"."ID";

COMMENT ON TABLE "menu_item" IS 'Пункты меню';
COMMENT ON COLUMN "menu_item"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "menu_item"."Name" IS 'Наименование';
COMMENT ON COLUMN "menu_item"."Url" IS 'Урл';
COMMENT ON COLUMN "menu_item"."Parent" IS 'Корень';
COMMENT ON COLUMN "menu_item"."Menu_ID" IS 'Привязка к меню';
COMMENT ON COLUMN "menu_item"."Sort" IS 'Сортировка';
