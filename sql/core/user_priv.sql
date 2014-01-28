CREATE TABLE "user_priv"
(
	"Admin_ID" int NOT NULL,
	"Group_ID" int NOT NULL,
	CONSTRAINT "user_priv_PK" PRIMARY KEY ("Admin_ID", "Group_ID"),
	CONSTRAINT "user_priv_FK_Admin_ID" FOREIGN KEY ("Admin_ID")
		REFERENCES "admin" ("ID"),
	CONSTRAINT "user_priv_FK_Group_ID" FOREIGN KEY ("Group_ID")
		REFERENCES "user_group" ("ID")
);

COMMENT ON TABLE "user_priv" IS 'Привилегии пользователей';
COMMENT ON COLUMN "user_priv"."Admin_ID" IS 'Привязка к админке';
COMMENT ON COLUMN "user_priv"."Group_ID" IS 'Привязка к группе';