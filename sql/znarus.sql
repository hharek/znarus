--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: creator; Type: SCHEMA; Schema: -; Owner: znarus
--

CREATE SCHEMA creator;


ALTER SCHEMA creator OWNER TO znarus;

--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: root
--

CREATE OR REPLACE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO root;

SET search_path = creator, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: entity; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE entity (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Desc" text,
    "Pack_ID" integer NOT NULL,
    "Table" character varying(127) NOT NULL,
    "MD5_File" character varying(32)
);


ALTER TABLE creator.entity OWNER TO znarus;

--
-- Name: entity_seq; Type: SEQUENCE; Schema: creator; Owner: znarus
--

CREATE SEQUENCE entity_seq
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE creator.entity_seq OWNER TO znarus;

--
-- Name: entity_seq; Type: SEQUENCE OWNED BY; Schema: creator; Owner: znarus
--

ALTER SEQUENCE entity_seq OWNED BY entity."ID";


--
-- Name: entity_seq; Type: SEQUENCE SET; Schema: creator; Owner: znarus
--

SELECT pg_catalog.setval('entity_seq', 80, true);


--
-- Name: enum; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE enum (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Field_ID" integer NOT NULL,
    "Sort" integer NOT NULL
);


ALTER TABLE creator.enum OWNER TO znarus;

--
-- Name: enum_seq; Type: SEQUENCE; Schema: creator; Owner: znarus
--

CREATE SEQUENCE enum_seq
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE creator.enum_seq OWNER TO znarus;

--
-- Name: enum_seq; Type: SEQUENCE OWNED BY; Schema: creator; Owner: znarus
--

ALTER SEQUENCE enum_seq OWNED BY enum."ID";


--
-- Name: enum_seq; Type: SEQUENCE SET; Schema: creator; Owner: znarus
--

SELECT pg_catalog.setval('enum_seq', 40, true);


--
-- Name: field; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE field (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Type_ID" integer NOT NULL,
    "Desc" text,
    "Default" character varying(255) NOT NULL,
    "Foreign_ID" integer,
    "Entity_ID" integer NOT NULL,
    "Null" boolean DEFAULT false,
    "Sort" integer NOT NULL,
    "Is_Order" boolean DEFAULT false NOT NULL,
    "Foreign_Change" boolean
);


ALTER TABLE creator.field OWNER TO znarus;

--
-- Name: field_seq; Type: SEQUENCE; Schema: creator; Owner: znarus
--

CREATE SEQUENCE field_seq
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE creator.field_seq OWNER TO znarus;

--
-- Name: field_seq; Type: SEQUENCE OWNED BY; Schema: creator; Owner: znarus
--

ALTER SEQUENCE field_seq OWNED BY field."ID";


--
-- Name: field_seq; Type: SEQUENCE SET; Schema: creator; Owner: znarus
--

SELECT pg_catalog.setval('field_seq', 305, true);


--
-- Name: field_type; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE field_type (
    "ID" integer NOT NULL,
    "Desc" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL
);


ALTER TABLE creator.field_type OWNER TO znarus;

--
-- Name: field_type_seq; Type: SEQUENCE; Schema: creator; Owner: znarus
--

CREATE SEQUENCE field_type_seq
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE creator.field_type_seq OWNER TO znarus;

--
-- Name: field_type_seq; Type: SEQUENCE OWNED BY; Schema: creator; Owner: znarus
--

ALTER SEQUENCE field_type_seq OWNED BY field_type."ID";


--
-- Name: field_type_seq; Type: SEQUENCE SET; Schema: creator; Owner: znarus
--

SELECT pg_catalog.setval('field_type_seq', 24, true);


--
-- Name: pack_seq; Type: SEQUENCE; Schema: creator; Owner: znarus
--

CREATE SEQUENCE pack_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE creator.pack_seq OWNER TO znarus;

--
-- Name: pack_seq; Type: SEQUENCE SET; Schema: creator; Owner: znarus
--

SELECT pg_catalog.setval('pack_seq', 20, true);


--
-- Name: pack; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE pack (
    "ID" integer DEFAULT nextval('pack_seq'::regclass) NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL
);


ALTER TABLE creator.pack OWNER TO znarus;

--
-- Name: unique; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE "unique" (
    "ID" integer NOT NULL,
    "Entity_ID" integer NOT NULL
);


ALTER TABLE creator."unique" OWNER TO znarus;

--
-- Name: unique_field; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE unique_field (
    "Unique_ID" integer NOT NULL,
    "Field_ID" integer NOT NULL
);


ALTER TABLE creator.unique_field OWNER TO znarus;

--
-- Name: unique_seq; Type: SEQUENCE; Schema: creator; Owner: znarus
--

CREATE SEQUENCE unique_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE creator.unique_seq OWNER TO znarus;

--
-- Name: unique_seq; Type: SEQUENCE OWNED BY; Schema: creator; Owner: znarus
--

ALTER SEQUENCE unique_seq OWNED BY "unique"."ID";


--
-- Name: unique_seq; Type: SEQUENCE SET; Schema: creator; Owner: znarus
--

SELECT pg_catalog.setval('unique_seq', 58, true);


--
-- Name: ID; Type: DEFAULT; Schema: creator; Owner: znarus
--

ALTER TABLE entity ALTER COLUMN "ID" SET DEFAULT nextval('entity_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: creator; Owner: znarus
--

ALTER TABLE enum ALTER COLUMN "ID" SET DEFAULT nextval('enum_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: creator; Owner: znarus
--

ALTER TABLE enum ALTER COLUMN "Sort" SET DEFAULT currval('enum_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: creator; Owner: znarus
--

ALTER TABLE field ALTER COLUMN "ID" SET DEFAULT nextval('field_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: creator; Owner: znarus
--

ALTER TABLE field ALTER COLUMN "Sort" SET DEFAULT currval('field_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: creator; Owner: znarus
--

ALTER TABLE field_type ALTER COLUMN "ID" SET DEFAULT nextval('field_type_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: creator; Owner: znarus
--

ALTER TABLE "unique" ALTER COLUMN "ID" SET DEFAULT nextval('unique_seq'::regclass);


--
-- Data for Name: entity; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY entity ("ID", "Name", "Identified", "Desc", "Pack_ID", "Table", "MD5_File") FROM stdin;
\.


--
-- Data for Name: enum; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY enum ("ID", "Name", "Field_ID", "Sort") FROM stdin;
\.


--
-- Data for Name: field; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY field ("ID", "Name", "Identified", "Type_ID", "Desc", "Default", "Foreign_ID", "Entity_ID", "Null", "Sort", "Is_Order", "Foreign_Change") FROM stdin;
\.


--
-- Data for Name: field_type; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY field_type ("ID", "Desc", "Identified") FROM stdin;
1	Число без знака	uint
2	Cтрока без пробельных символов и html-тегов	string
4	Число со знаком	int
3	uint, начинается с одного, и зависит от своей последовательности	id
5	Число с плавающей запятой	float
6	Цена два числа после запятой всегда положительная	price
7	Строка без html-тегов	text
8	Строка без содержания тега script	html
9	Строка, содержит только [a-z0-9_]	identified
11	Строка, содержит только [a-z0-9_].(jpg|png|gif)	image
10	Строка, содержит только [a-z0-9_].[a-z0-9]	file
12	Строка, содержит только [a-zа-я0-9_]	url
13	Строка содержащая только email	email
14	Дата в формате dd.mm.YYYY	date
15	Дата и время в формате TIMESTAMP	timestamp
16	Число, по умолчанию данные берет от поле ID	sort
17	Булёвое значение	bool
18	Перечисления	enum
20	Строка содержащая 32 символа [a-z0-9]	md5
21	Бинарная строка, не проверяется	blob
19	Внешний ключ	foreign
\.


--
-- Data for Name: pack; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY pack ("ID", "Name", "Identified") FROM stdin;
\.


--
-- Data for Name: unique; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY "unique" ("ID", "Entity_ID") FROM stdin;
\.


--
-- Data for Name: unique_field; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY unique_field ("Unique_ID", "Field_ID") FROM stdin;
\.


--
-- Name: entity_PK; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY entity
    ADD CONSTRAINT "entity_PK" PRIMARY KEY ("ID");


--
-- Name: entity_UN_Identified; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY entity
    ADD CONSTRAINT "entity_UN_Identified" UNIQUE ("Identified", "Pack_ID");


--
-- Name: entity_UN_Name; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY entity
    ADD CONSTRAINT "entity_UN_Name" UNIQUE ("Name", "Pack_ID");


--
-- Name: entity_UN_Table; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY entity
    ADD CONSTRAINT "entity_UN_Table" UNIQUE ("Table");


--
-- Name: enum_PK; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY enum
    ADD CONSTRAINT "enum_PK" PRIMARY KEY ("ID");


--
-- Name: enum_UN_Name; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY enum
    ADD CONSTRAINT "enum_UN_Name" UNIQUE ("Name", "Field_ID");


--
-- Name: field_PK; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY field
    ADD CONSTRAINT "field_PK" PRIMARY KEY ("ID");


--
-- Name: field_UN_Identified; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY field
    ADD CONSTRAINT "field_UN_Identified" UNIQUE ("Identified", "Entity_ID");


--
-- Name: field_UN_Name; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY field
    ADD CONSTRAINT "field_UN_Name" UNIQUE ("Name", "Entity_ID");


--
-- Name: field_type_PK; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY field_type
    ADD CONSTRAINT "field_type_PK" PRIMARY KEY ("ID");


--
-- Name: field_type_UN_Identified; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY field_type
    ADD CONSTRAINT "field_type_UN_Identified" UNIQUE ("Identified");


--
-- Name: pack_PK; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY pack
    ADD CONSTRAINT "pack_PK" PRIMARY KEY ("ID");


--
-- Name: pack_UN_Identified; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY pack
    ADD CONSTRAINT "pack_UN_Identified" UNIQUE ("Identified");


--
-- Name: pack_UN_Name; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY pack
    ADD CONSTRAINT "pack_UN_Name" UNIQUE ("Name");


--
-- Name: unique_PK; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY "unique"
    ADD CONSTRAINT "unique_PK" PRIMARY KEY ("ID");


--
-- Name: unique_field_PK; Type: CONSTRAINT; Schema: creator; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY unique_field
    ADD CONSTRAINT "unique_field_PK" PRIMARY KEY ("Unique_ID", "Field_ID");


--
-- Name: entity_FK_Pack_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY entity
    ADD CONSTRAINT "entity_FK_Pack_ID" FOREIGN KEY ("Pack_ID") REFERENCES pack("ID");


--
-- Name: enum_FK_Field_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY enum
    ADD CONSTRAINT "enum_FK_Field_ID" FOREIGN KEY ("Field_ID") REFERENCES field("ID");


--
-- Name: field_FK_Entity_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY field
    ADD CONSTRAINT "field_FK_Entity_ID" FOREIGN KEY ("Entity_ID") REFERENCES entity("ID");


--
-- Name: field_FK_Foreign_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY field
    ADD CONSTRAINT "field_FK_Foreign_ID" FOREIGN KEY ("Foreign_ID") REFERENCES field("ID");


--
-- Name: field_FK_Type_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY field
    ADD CONSTRAINT "field_FK_Type_ID" FOREIGN KEY ("Type_ID") REFERENCES field_type("ID");


--
-- Name: unique_FK_Entity_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY "unique"
    ADD CONSTRAINT "unique_FK_Entity_ID" FOREIGN KEY ("Entity_ID") REFERENCES entity("ID");


--
-- Name: unique_field_FK_Field_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY unique_field
    ADD CONSTRAINT "unique_field_FK_Field_ID" FOREIGN KEY ("Field_ID") REFERENCES field("ID");


--
-- Name: unique_field_FK_Unique_ID; Type: FK CONSTRAINT; Schema: creator; Owner: znarus
--

ALTER TABLE ONLY unique_field
    ADD CONSTRAINT "unique_field_FK_Unique_ID" FOREIGN KEY ("Unique_ID") REFERENCES "unique"("ID");


--
-- PostgreSQL database dump complete
--

