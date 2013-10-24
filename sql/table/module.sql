CREATE TYPE "module_type" AS ENUM ('mod','smod');

CREATE SEQUENCE "module_seq" START 1;

CREATE TABLE "module"
(
	"ID" int NOT NULL DEFAULT nextval('module_seq'),
	"Name" varchar(255) NOT NULL DEFAULT '',
	"Identified" varchar(127) NOT NULL DEFAULT '',
	"Desc" text DEFAULT '',
	"Version" varchar(255) NOT NULL DEFAULT '',
	"Type" "module_type",
	"Url" varchar(255) DEFAULT '',
	"Html_ID" int NULL,
	"Active" bool NOT NULL DEFAULT false,
	CONSTRAINT "module_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "module_FK_Html_ID" FOREIGN KEY ("Html_ID")
		REFERENCES "html" ("ID"),
	CONSTRAINT "module_UN_Name" UNIQUE ("Name"),
	CONSTRAINT "module_UN_Identified" UNIQUE ("Identified")
);

ALTER SEQUENCE "module_seq" OWNED BY "module"."ID";

COMMENT ON TABLE "module" IS 'Модуль';
COMMENT ON COLUMN "module"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "module"."Name" IS 'Наименование';
COMMENT ON COLUMN "module"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "module"."Desc" IS 'Описание';
COMMENT ON COLUMN "module"."Version" IS 'Версия';
COMMENT ON COLUMN "module"."Type" IS 'Тип - обычный или системный';
COMMENT ON COLUMN "module"."Url" IS 'Урл';
COMMENT ON COLUMN "module"."Html_ID" IS 'Привязка к основному шаблону';
COMMENT ON COLUMN "module"."Active" IS 'Активность';