CREATE SEQUENCE "slider_a_seq" START 1;

CREATE TABLE "slider_a"
(
	"ID" int NOT NULL DEFAULT nextval('slider_a_seq'),
	"Name" varchar(255) NOT NULL,
	"Url" varchar(127) NOT NULL,
	"File" varchar(127) NOT NULL,
	"Sort" int NOT NULL DEFAULT currval('slider_a_seq'),
	CONSTRAINT "slider_a_PK" PRIMARY KEY ("ID")
);

ALTER SEQUENCE "slider_a_seq" OWNED BY "slider_a"."ID";

COMMENT ON TABLE "slider_a" IS 'Рисунки слайдера';
COMMENT ON COLUMN "slider_a"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "slider_a"."Name" IS 'Заголовок';
COMMENT ON COLUMN "slider_a"."Url" IS 'Урл';
COMMENT ON COLUMN "slider_a"."File" IS 'Имя файла';
COMMENT ON COLUMN "slider_a"."Sort" IS 'Сортировка';