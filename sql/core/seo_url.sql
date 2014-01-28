CREATE SEQUENCE "seo_url_seq" START 1;

CREATE TABLE "seo_url"
(
	"ID" int NOT NULL DEFAULT nextval('seo_url_seq'),
	"Url" varchar(127) NOT NULL,
	"Title" varchar(255) NOT NULL DEFAULT '',
	"Keywords" varchar(255) NOT NULL DEFAULT '',
	"Description" varchar(255) NOT NULL DEFAULT '',
	CONSTRAINT "seo_url_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "seo_url_UN_Url" UNIQUE ("Url")
);

ALTER SEQUENCE "seo_url_seq" OWNED BY "seo_url"."ID";

COMMENT ON TABLE "seo_url" IS 'Адреса для продвижения';
COMMENT ON COLUMN "seo_url"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "seo_url"."Url" IS 'Адрес';
COMMENT ON COLUMN "seo_url"."Title" IS 'Тег title';
COMMENT ON COLUMN "seo_url"."Keywords" IS 'Тег meta keywords';
COMMENT ON COLUMN "seo_url"."Description" IS 'Тег meta description';