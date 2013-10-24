CREATE SEQUENCE "admin_seq" START 1;

CREATE TABLE "admin"
(
	"ID" int NOT NULL DEFAULT nextval('admin_seq'),
	"Name" varchar(255) NOT NULL DEFAULT '',
	"Identified" varchar(127) NOT NULL DEFAULT '',
	"Sort" int NOT NULL DEFAULT currval('admin_seq'),
	"Get" bool NOT NULL DEFAULT true,
	"Post" bool NOT NULL DEFAULT false,
	"Visible" bool NOT NULL DEFAULT false,
	"Module_ID" int NOT NULL,
	CONSTRAINT "admin_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "admin_UN_Name" UNIQUE ("Name", "Module_ID"),
	CONSTRAINT "admin_UN_Identified" UNIQUE ("Identified", "Module_ID"),
	CONSTRAINT "admin_FK_Module_ID" FOREIGN KEY ("Module_ID")
		REFERENCES "module" ("ID")
);

ALTER SEQUENCE "admin_seq" OWNED BY "admin"."ID";

COMMENT ON TABLE "admin" IS 'Админки';
COMMENT ON COLUMN "admin"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "admin"."Name" IS 'Наименование';
COMMENT ON COLUMN "admin"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "admin"."Sort" IS 'Сортировка';
COMMENT ON COLUMN "admin"."Get" IS 'Обработка GET данных';
COMMENT ON COLUMN "admin"."Post" IS 'Обработка POST данных';
COMMENT ON COLUMN "admin"."Visible" IS 'Видимость';
COMMENT ON COLUMN "admin"."Module_ID" IS 'Привязка к модулю';