CREATE SEQUENCE "tags_seq" START 1;

CREATE TABLE "tags"
(
	"ID" int NOT NULL DEFAULT nextval('tags_seq'),
	"Name" varchar(255) NOT NULL,
	"Count" int NOT NULL DEFAULT 1,
	CONSTRAINT "tags_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "tags_UN_Name" UNIQUE ("Name")
);

ALTER SEQUENCE "tags_seq" OWNED BY "tags"."ID";
COMMENT ON TABLE "tags" IS 'Теги';
COMMENT ON COLUMN "tags"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "tags"."Name" IS 'Наименование';
COMMENT ON COLUMN "tags"."Count" IS 'Количество';
