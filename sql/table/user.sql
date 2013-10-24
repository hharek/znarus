CREATE SEQUENCE "user_seq" START 1;

CREATE TABLE "user"
(
	"ID" int NOT NULL DEFAULT nextval('user_seq'),
	"Name" varchar(255) NOT NULL,
	"Email" varchar(127) NOT NULL,
	"Password" char(32) NOT NULL,
	"Group_ID" int NOT NULL,
	"Active" bool NOT NULL DEFAULT false,
	CONSTRAINT "user_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "user_UN_Name" UNIQUE ("Name"),
	CONSTRAINT "user_UN_Email" UNIQUE ("Email"),
	CONSTRAINT "user_FK_Group_ID" FOREIGN KEY ("Group_ID")
		REFERENCES "user_group" ("ID")
);

ALTER SEQUENCE "user_seq" OWNED BY "user"."ID";

COMMENT ON TABLE "user" IS 'Пользователи';
COMMENT ON COLUMN "user"."ID" IS 'Порядковый номер';
COMMENT ON COLUMN "user"."Name" IS 'Наименование';
COMMENT ON COLUMN "user"."Email" IS 'Почтовый ящик';
COMMENT ON COLUMN "user"."Password" IS 'Хэш пароля';
COMMENT ON COLUMN "user"."Group_ID" IS 'Привязка к группе';
COMMENT ON COLUMN "user"."Active" IS 'Активность';