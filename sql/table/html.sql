CREATE SEQUENCE "html_seq" START 1;

CREATE TABLE "html"
(
	"ID" int NOT NULL DEFAULT nextval('html_seq'),
	"Name" varchar(255) NOT NULL DEFAULT '',
	"Identified" varchar(127) NOT NULL DEFAULT '',
	CONSTRAINT "html_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "html_UN_Identified" UNIQUE ("Identified"),
	CONSTRAINT "html_UN_Name" UNIQUE ("Name")
);

ALTER SEQUENCE "html_seq" OWNED BY "html"."ID";

COMMENT ON TABLE "html" IS 'Основной шаблон';
COMMENT ON COLUMN "html"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "html"."Name" IS 'Наименование';
COMMENT ON COLUMN "html"."Identified" IS 'Идентификатор';