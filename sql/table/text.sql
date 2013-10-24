CREATE SEQUENCE "text_seq" START 1;

CREATE TABLE "text"
(
	"ID" int NOT NULL DEFAULT nextval('text_seq'),
	"Name" varchar(255) NOT NULL,
	"Identified" varchar(127) NOT NULL,
	"Value" text NOT NULL DEFAULT '',
	"Module_ID" int NOT NULL,
	CONSTRAINT "text_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "text_UN_Name" UNIQUE ("Name", "Module_ID"),
	CONSTRAINT "text_UN_Identified" UNIQUE ("Identified", "Module_ID"),
	CONSTRAINT "text_FK_Module_ID" FOREIGN KEY ("Module_ID")
		REFERENCES "module" ("ID")
);

ALTER SEQUENCE "text_seq" OWNED BY "text"."ID";

COMMENT ON TABLE "text" IS 'Тексты';
COMMENT ON COLUMN "text"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "text"."Name" IS 'Наименование';
COMMENT ON COLUMN "text"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "text"."Value" IS 'Значение';
COMMENT ON COLUMN "text"."Module_ID" IS 'Привязка к модулю';