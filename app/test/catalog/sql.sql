/*---------------------------Категория---------------------------*/
CREATE SEQUENCE "catalog_category_seq";
CREATE TABLE "catalog_category"
(
	"ID" int NOT NULL DEFAULT nextval('catalog_category_seq'),
	"Name" varchar(255) NOT NULL,
	CONSTRAINT "catalog_category_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "catalog_category_UN_Name" UNIQUE ("Name")
);
ALTER SEQUENCE "catalog_category_seq" OWNED BY "catalog_category"."ID";

/*----------------------------Товар-----------------------------*/
CREATE SEQUENCE "catalog_tovar_seq";
CREATE TABLE "catalog_tovar"
(
	"ID" int NOT NULL DEFAULT nextval('catalog_tovar_seq'),
	"Name" varchar(255) NOT NULL,
	"Category_ID" int NOT NULL,
	CONSTRAINT "catalog_tovar_PK" PRIMARY KEY ("ID"),
	CONSTRAINT "catalog_tovar_UN_Name" UNIQUE ("Name"),
	CONSTRAINT "catalog_tovar_FK_Category_ID" FOREIGN KEY ("Category_ID") REFERENCES "catalog_category"("ID")
);
ALTER SEQUENCE "catalog_tovar_seq" OWNED BY "catalog_tovar"."ID";


