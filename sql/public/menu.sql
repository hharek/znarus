CREATE SEQUENCE "menu_seq" START 1;

CREATE TABLE "menu"
(
	"ID" int NOT NULL DEFAULT nextval('menu_seq'),
	"Name" varchar(255) NOT NULL,
	CONSTRAINT "menu_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "menu_UN_Name" UNIQUE ("Name")
);

ALTER SEQUENCE "menu_seq" OWNED BY "menu"."ID";

COMMENT ON TABLE "menu" IS 'Меню';
COMMENT ON COLUMN "menu"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "menu"."Name" IS 'Наименование';