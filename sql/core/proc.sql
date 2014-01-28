CREATE TYPE "proc_type" AS ENUM ('start','end');

CREATE SEQUENCE "proc_seq" START 1;

CREATE TABLE "proc"
(
	"ID" int NOT NULL DEFAULT nextval('proc_seq'),
	"Name" varchar(255) NOT NULL DEFAULT '',
	"Identified" varchar(127) NOT NULL DEFAULT '',
	"Type" "proc_type",
	"Module_ID" int NOT NULL,
	"Active" boolean NOT NULL DEFAULT false,
	CONSTRAINT "proc_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "proc_UN_Name" UNIQUE ("Name", "Module_ID"),
	CONSTRAINT "proc_UN_Identified" UNIQUE ("Identified", "Module_ID"),
	CONSTRAINT "proc_FK_Module_ID" FOREIGN KEY ("Module_ID")
		REFERENCES "module" ("ID")
);

ALTER SEQUENCE "proc_seq" OWNED BY "proc"."ID";

COMMENT ON TABLE "proc" IS 'Процедуры';
COMMENT ON COLUMN "proc"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "proc"."Name" IS 'Наименование';
COMMENT ON COLUMN "proc"."Identified" IS 'Идентификатор';
COMMENT ON COLUMN "proc"."Type" IS 'Тип исполнения в начале или в конце';
COMMENT ON COLUMN "proc"."Module_ID" IS 'Привязка к модулю';
COMMENT ON COLUMN "proc"."Active" IS 'Активность';
