CREATE SEQUENCE "phpclass_seq" START 1;

CREATE TABLE "phpclass"
(
	"ID" int NOT NULL DEFAULT nextval('phpclass_seq'),
	"Name" varchar(255) NOT NULL,
	"Identified" varchar(127) NOT NULL,
	"Module_ID" int NOT NULL,
	CONSTRAINT "phpclass_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "phpclass_UN_Name" UNIQUE ("Name", "Module_ID"),
	CONSTRAINT "phpclass_UN_Identified" UNIQUE ("Identified", "Module_ID"),
	CONSTRAINT "phpclass_FK_Module_ID" FOREIGN KEY ("Module_ID")
		REFERENCES "module" ("ID")
);

ALTER SEQUENCE "phpclass_seq" OWNED BY "phpclass"."ID";

COMMENT ON TABLE "phpclass" IS 'PHP класс';
COMMENT ON COLUMN "phpclass"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "phpclass"."Name" IS 'Наименование';
COMMENT ON COLUMN "phpclass"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "phpclass"."Module_ID" IS 'Привязка к модулю';