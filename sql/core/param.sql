CREATE TYPE "param_type" AS ENUM ('string','int','bool');

CREATE SEQUENCE "param_seq" START 1;

CREATE TABLE "param"
(
	"ID" int NOT NULL DEFAULT nextval('param_seq'),
	"Name" varchar(255) NOT NULL,
	"Identified" varchar(127) NOT NULL,
	"Type" "param_type" NOT NULL DEFAULT 'string',
	"Value" varchar(255) NOT NULL DEFAULT '',
	"Module_ID" int NULL,
	CONSTRAINT "param_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "param_UN_Name" UNIQUE ("Name", "Module_ID"),
	CONSTRAINT "param_UN_Identified" UNIQUE ("Identified", "Module_ID"),
	CONSTRAINT "param_FK_Module_ID" FOREIGN KEY ("Module_ID")
		REFERENCES "module" ("ID")
);

ALTER SEQUENCE "param_seq" OWNED BY "param"."ID";

COMMENT ON TABLE "param" IS 'Параметры';
COMMENT ON COLUMN "param"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "param"."Name" IS 'Наименование';
COMMENT ON COLUMN "param"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "param"."Type" IS 'Тип';
COMMENT ON COLUMN "param"."Value" IS 'Значение';
COMMENT ON COLUMN "param"."Module_ID" IS 'Привязка к модулю, если NULL то параметр системный';