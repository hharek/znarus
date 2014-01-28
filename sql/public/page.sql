CREATE SEQUENCE "page_seq" START 1;

CREATE TABLE "page"
(
	"ID" int NOT NULL DEFAULT nextval('page_seq'),
	"Name" varchar(255) NOT NULL,
	"Url" varchar(127) NOT NULL,
	"Content" text,
	"Parent" int,
	"Html_Identified" varchar(127) NOT NULL DEFAULT '',
	CONSTRAINT "page_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "page_UN_Name" UNIQUE ("Name", "Parent"),
	CONSTRAINT "page_UN_Url" UNIQUE ("Url", "Parent"),
	CONSTRAINT "page_FK_Parent" FOREIGN KEY ("Parent")
		REFERENCES "page" ("ID")
)

ALTER SEQUENCE "page_seq" OWNED BY "page"."ID";

COMMENT ON TABLE "page" IS 'Страницы';
COMMENT ON COLUMN "page"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "page"."Name" IS 'Наименование';
COMMENT ON COLUMN "page"."Url" IS 'Урл';
COMMENT ON COLUMN "page"."Content" IS 'Содержимое';
COMMENT ON COLUMN "page"."Parent" IS 'Корень';
COMMENT ON COLUMN "page"."Html_Identified" IS 'Наименование шаблона';
