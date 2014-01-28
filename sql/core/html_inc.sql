CREATE TABLE "html_inc"
(
	"Html_ID" int NOT NULL,
	"Inc_ID" int NOT NULL,
	CONSTRAINT "html_inc_PK" PRIMARY KEY ("Html_ID", "Inc_ID"),
	CONSTRAINT "html_inc_FK_Html_ID" FOREIGN KEY ("Html_ID")
		REFERENCES "html" ("ID"),
	CONSTRAINT "html_inc_FK_Inc_ID" FOREIGN KEY ("Inc_ID")
		REFERENCES "inc" ("ID")
);

COMMENT ON TABLE "html_inc" IS 'Составные части шаблона';
COMMENT ON COLUMN "html_inc"."Html_ID" IS 'Привязка к шаблону';
COMMENT ON COLUMN "html_inc"."Inc_ID" IS 'Привязка к инку';