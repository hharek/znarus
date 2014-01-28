CREATE SEQUENCE "seo_redirect_seq" START 1;

CREATE TABLE "seo_redirect"
(
	"ID" int NOT NULL DEFAULT nextval('seo_redirect_seq'),
	"From" varchar(127) NOT NULL,
	"To" varchar(127) NOT NULL,
	CONSTRAINT "seo_redirect_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "seo_redirect_UN_From" UNIQUE ("From")
);

ALTER SEQUENCE "seo_redirect_seq" OWNED BY "seo_redirect"."ID";

COMMENT ON TABLE "seo_redirect" IS 'Адреса для переадресации';
COMMENT ON COLUMN "seo_redirect"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "seo_redirect"."From" IS 'Источник';
COMMENT ON COLUMN "seo_redirect"."To" IS 'Назначение';