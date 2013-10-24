CREATE SEQUENCE "user_group_seq" START 1;

CREATE TABLE "user_group"
(
	"ID" int NOT NULL DEFAULT nextval('user_group_seq'),
	"Name" varchar(255) NOT NULL,
	CONSTRAINT "user_group_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "user_group_UN_Name" UNIQUE ("Name")
);

ALTER SEQUENCE "user_group_seq" OWNED BY "user_group"."ID";

COMMENT ON TABLE "user_group" IS 'Группа пользователей';
COMMENT ON COLUMN "user_group"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "user_group"."Name" IS 'Наименование';