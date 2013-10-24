CREATE TABLE "user_session"
(
	"ID" char(32) NOT NULL,
	"Date" timestamp NOT NULL DEFAULT now(),
	"IP" varchar(15) NOT NULL,
	"Browser" varchar(255) NOT NULL,
	"User_ID" int NULL,
	CONSTRAINT "user_session_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "user_session_FK_User_ID" FOREIGN KEY ("User_ID")
		REFERENCES "user" ("ID")
);

COMMENT ON TABLE "user_session" IS 'Сессии пользователей';
COMMENT ON COLUMN "user_session"."ID" IS 'Идентификатор сессии';
COMMENT ON COLUMN "user_session"."Date" IS 'Дата окончания действия сессии';
COMMENT ON COLUMN "user_session"."IP" IS 'IP адрес создателя сессии';
COMMENT ON COLUMN "user_session"."Browser" IS 'Строка USER_AGENT браузера создателя сессии';
COMMENT ON COLUMN "user_session"."User_ID" IS 'Привязка к пользователю, если NULL то root';