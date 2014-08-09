CREATE SEQUENCE "articles_seq" START 1;

CREATE TABLE "articles"
(
	"ID" int NOT NULL DEFAULT nextval('articles_seq'),
	"Date" date NOT NULL,
	"Title" varchar(255) NOT NULL,
	"Url" varchar(127) NOT NULL,
	"Anons" text,
	"Content" text,
	CONSTRAINT "articles_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "articles_UN_Title" UNIQUE ("Title"),
	CONSTRAINT "articles_UN_Url" UNIQUE ("Url")
);

ALTER SEQUENCE "articles_seq" OWNED BY "articles"."ID";

COMMENT ON TABLE "articles" IS 'Статьи';
COMMENT ON COLUMN "articles"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "articles"."Date" IS 'Дата';
COMMENT ON COLUMN "articles"."Title" IS 'Заголовок';
COMMENT ON COLUMN "articles"."Url" IS 'Урл';
COMMENT ON COLUMN "articles"."Anons" IS 'Анонс';
COMMENT ON COLUMN "articles"."Content" IS 'Содержимое';