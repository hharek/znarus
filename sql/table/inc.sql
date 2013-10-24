CREATE SEQUENCE "inc_seq" START 1;

CREATE TABLE "inc"
(
	"ID" int NOT NULL DEFAULT nextval('inc_seq'),
	"Name" varchar(255) NOT NULL DEFAULT '',
	"Identified" varchar(127) NOT NULL DEFAULT '',
	"Module_ID" int NOT NULL,
	"Active" boolean NOT NULL DEFAULT false 
	CONSTRAINT "inc_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "inc_UN_Name" UNIQUE ("Name", "Module_ID"),
	CONSTRAINT "inc_UN_Identified" UNIQUE ("Identified", "Module_ID"),
	CONSTRAINT "inc_FK_Module_ID" FOREIGN KEY ("Module_ID")
		REFERENCES "module" ("ID")
);

ALTER SEQUENCE "inc_seq" OWNED BY "admin"."ID";

COMMENT ON TABLE "inc" IS 'Инки';
COMMENT ON COLUMN "inc"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "inc"."Name" IS 'Наименование';
COMMENT ON COLUMN "inc"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "inc"."Module_ID" IS 'Привязка к модулю';
COMMENT ON COLUMN "inc"."Active" IS 'Активность';