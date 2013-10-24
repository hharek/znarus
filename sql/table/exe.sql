CREATE SEQUENCE "exe_seq" START 1;

CREATE TABLE "exe"
(
	"ID" int NOT NULL DEFAULT nextval('exe_seq'),
	"Name" varchar(255) NOT NULL DEFAULT '',
	"Identified" varchar(127) NOT NULL DEFAULT '',
	"Module_ID" int NOT NULL,
	"Priority" int NOT NULL DEFAULT currval('exe_seq'),
	"Active" bool NOT NULL DEFAULT false,
	CONSTRAINT "exe_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "exe_UN_Name" UNIQUE ("Name", "Module_ID"),
	CONSTRAINT "exe_UN_Identified" UNIQUE ("Identified", "Module_ID"),
	CONSTRAINT "exe_FK_Module_ID" FOREIGN KEY ("Module_ID")
		REFERENCES "module" ("ID")
);

ALTER SEQUENCE "exe_seq" OWNED BY "exe"."ID";

COMMENT ON TABLE "exe" IS 'Исполнители';
COMMENT ON COLUMN "exe"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "exe"."Name" IS 'Наименование';
COMMENT ON COLUMN "exe"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "exe"."Module_ID" IS 'Привязка к модулю';
COMMENT ON COLUMN "exe"."Priority" IS 'Порядок исполнения';
COMMENT ON COLUMN "exe"."Active" IS 'Активность';