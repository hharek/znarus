CREATE SEQUENCE "faq_seq" START 1;

CREATE TABLE "faq"
(
	"ID" int NOT NULL DEFAULT nextval('faq_seq'),
	"Question" text NOT NULL,
	"Answer" text NOT NULL,
	"Sort" int NOT NULL DEFAULT currval('faq_seq'),
	CONSTRAINT "faq_PK" PRIMARY KEY ("ID")
);

ALTER SEQUENCE "faq_seq" OWNED BY "faq"."ID";

COMMENT ON TABLE "faq" IS 'Вопросы и ответы';
COMMENT ON COLUMN "faq"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "faq"."Question" IS 'Вопрос';
COMMENT ON COLUMN "faq"."Answer" IS 'Ответ';
COMMENT ON COLUMN "faq"."Sort" IS 'Сортировка';