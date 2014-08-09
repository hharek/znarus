CREATE SEQUENCE "news_seq" START 1;

CREATE TABLE "news"
(
	"ID" int NOT NULL DEFAULT nextval('news_seq'),
	"Date" date NOT NULL,
	"Title" varchar(255) NOT NULL,
	"Url" varchar(127) NOT NULL,
	"Anons" text,
	"Content" text,
	CONSTRAINT "news_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "news_UN_Title" UNIQUE ("Title"),
	CONSTRAINT "news_UN_Url" UNIQUE ("Url")
);

ALTER SEQUENCE "news_seq" OWNED BY "news"."ID";

COMMENT ON TABLE "news" IS 'Новости';
COMMENT ON COLUMN "news"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "news"."Date" IS 'Дата';
COMMENT ON COLUMN "news"."Title" IS 'Заголовок';
COMMENT ON COLUMN "news"."Url" IS 'Урл';
COMMENT ON COLUMN "news"."Anons" IS 'Анонс';
COMMENT ON COLUMN "news"."Content" IS 'Содержимое';