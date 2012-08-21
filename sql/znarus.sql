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
-- Name: public_old; Type: SCHEMA; Schema: -; Owner: znarus
--

CREATE SCHEMA public_old;


ALTER SCHEMA public_old OWNER TO znarus;

--
-- Name: SCHEMA public_old; Type: COMMENT; Schema: -; Owner: znarus
--

COMMENT ON SCHEMA public_old IS 'standard public schema';


--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: root
--

CREATE OR REPLACE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO root;

SET search_path = public, pg_catalog;

--
-- Name: catalog_tovar_color; Type: TYPE; Schema: public; Owner: znarus
--

CREATE TYPE catalog_tovar_color AS ENUM (
);


ALTER TYPE public.catalog_tovar_color OWNER TO znarus;

--
-- Name: news_news_type; Type: TYPE; Schema: public; Owner: znarus
--

CREATE TYPE news_news_type AS ENUM (
);


ALTER TYPE public.news_news_type OWNER TO znarus;

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
    "Pack_ID" integer NOT NULL
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

SELECT pg_catalog.setval('entity_seq', 30, true);


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

SELECT pg_catalog.setval('enum_seq', 7, true);


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
    "Sort" integer NOT NULL
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

SELECT pg_catalog.setval('field_seq', 105, true);


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

SELECT pg_catalog.setval('field_type_seq', 23, true);


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

SELECT pg_catalog.setval('pack_seq', 6, true);


--
-- Name: pack; Type: TABLE; Schema: creator; Owner: znarus; Tablespace: 
--

CREATE TABLE pack (
    "ID" integer DEFAULT nextval('pack_seq'::regclass) NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL
);


ALTER TABLE creator.pack OWNER TO znarus;

SET search_path = public, pg_catalog;

--
-- Name: catalog_category; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE catalog_category (
    "Name" character varying(255),
    "Type" character varying(255) NOT NULL,
    "ID" integer NOT NULL,
    "Sort" integer
);


ALTER TABLE public.catalog_category OWNER TO znarus;

--
-- Name: catalog_category_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE catalog_category_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.catalog_category_seq OWNER TO znarus;

--
-- Name: catalog_category_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE catalog_category_seq OWNED BY catalog_category."ID";


--
-- Name: catalog_category_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('catalog_category_seq', 10, true);


--
-- Name: catalog_image; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE catalog_image (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Sort" integer,
    "Tovar_ID" integer NOT NULL
);


ALTER TABLE public.catalog_image OWNER TO znarus;

--
-- Name: catalog_image_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE catalog_image_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.catalog_image_seq OWNER TO znarus;

--
-- Name: catalog_image_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE catalog_image_seq OWNED BY catalog_image."ID";


--
-- Name: catalog_image_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('catalog_image_seq', 1, false);


--
-- Name: catalog_tovar; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE catalog_tovar (
    "Name" character varying(255) NOT NULL,
    "Color" catalog_tovar_color,
    "ID" integer NOT NULL,
    "Sort" integer,
    "Category_ID" integer NOT NULL
);


ALTER TABLE public.catalog_tovar OWNER TO znarus;

--
-- Name: catalog_tovar_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE catalog_tovar_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.catalog_tovar_seq OWNER TO znarus;

--
-- Name: catalog_tovar_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE catalog_tovar_seq OWNED BY catalog_tovar."ID";


--
-- Name: catalog_tovar_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('catalog_tovar_seq', 1, false);


--
-- Name: news; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE news (
    "ID" integer NOT NULL,
    "Name_New" character varying(255) NOT NULL,
    "Type" news_news_type,
    "Category_ID_New" integer,
    "Sort" integer
);


ALTER TABLE public.news OWNER TO znarus;

--
-- Name: news_autor; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE news_autor (
    "ID" integer NOT NULL,
    "Fio" character varying(255) NOT NULL
);


ALTER TABLE public.news_autor OWNER TO znarus;

--
-- Name: news_autor_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE news_autor_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_autor_seq OWNER TO znarus;

--
-- Name: news_autor_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE news_autor_seq OWNED BY news_autor."ID";


--
-- Name: news_autor_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('news_autor_seq', 1, false);


--
-- Name: news_category; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE news_category (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL
);


ALTER TABLE public.news_category OWNER TO znarus;

--
-- Name: news_category_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE news_category_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_category_seq OWNER TO znarus;

--
-- Name: news_category_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE news_category_seq OWNED BY news_category."ID";


--
-- Name: news_category_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('news_category_seq', 1, false);


--
-- Name: news_prosto; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE news_prosto (
);


ALTER TABLE public.news_prosto OWNER TO znarus;

--
-- Name: news_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE news_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_seq OWNER TO znarus;

--
-- Name: news_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE news_seq OWNED BY news."ID";


--
-- Name: news_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('news_seq', 1, false);


--
-- Name: news_test; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE news_test (
    "ID" integer NOT NULL
);


ALTER TABLE public.news_test OWNER TO znarus;

--
-- Name: news_test_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE news_test_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_test_seq OWNER TO znarus;

--
-- Name: news_test_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE news_test_seq OWNED BY news_test."ID";


--
-- Name: news_test_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('news_test_seq', 1, false);


--
-- Name: user; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE "user" (
    "User_ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Privileges" integer,
    "Group_ID" integer
);


ALTER TABLE public."user" OWNER TO znarus;

--
-- Name: user_group; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE user_group (
    "Group_ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Sortirovka" integer
);


ALTER TABLE public.user_group OWNER TO znarus;

--
-- Name: user_group_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE user_group_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_group_seq OWNER TO znarus;

--
-- Name: user_group_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE user_group_seq OWNED BY user_group."Group_ID";


--
-- Name: user_group_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('user_group_seq', 1, false);


--
-- Name: user_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE user_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_seq OWNER TO znarus;

--
-- Name: user_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE user_seq OWNED BY "user"."User_ID";


--
-- Name: user_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('user_seq', 1, false);


SET search_path = public_old, pg_catalog;

--
-- Name: catalog_category; Type: TABLE; Schema: public_old; Owner: znarus; Tablespace: 
--

CREATE TABLE catalog_category (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL
);


ALTER TABLE public_old.catalog_category OWNER TO znarus;

--
-- Name: catalog_category_seq; Type: SEQUENCE; Schema: public_old; Owner: znarus
--

CREATE SEQUENCE catalog_category_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public_old.catalog_category_seq OWNER TO znarus;

--
-- Name: catalog_category_seq; Type: SEQUENCE OWNED BY; Schema: public_old; Owner: znarus
--

ALTER SEQUENCE catalog_category_seq OWNED BY catalog_category."ID";


--
-- Name: catalog_category_seq; Type: SEQUENCE SET; Schema: public_old; Owner: znarus
--

SELECT pg_catalog.setval('catalog_category_seq', 5, true);


--
-- Name: catalog_tovar; Type: TABLE; Schema: public_old; Owner: znarus; Tablespace: 
--

CREATE TABLE catalog_tovar (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Category_ID" integer NOT NULL
);


ALTER TABLE public_old.catalog_tovar OWNER TO znarus;

--
-- Name: catalog_tovar_seq; Type: SEQUENCE; Schema: public_old; Owner: znarus
--

CREATE SEQUENCE catalog_tovar_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public_old.catalog_tovar_seq OWNER TO znarus;

--
-- Name: catalog_tovar_seq; Type: SEQUENCE OWNED BY; Schema: public_old; Owner: znarus
--

ALTER SEQUENCE catalog_tovar_seq OWNED BY catalog_tovar."ID";


--
-- Name: catalog_tovar_seq; Type: SEQUENCE SET; Schema: public_old; Owner: znarus
--

SELECT pg_catalog.setval('catalog_tovar_seq', 5, true);


--
-- Name: t1_cat; Type: TABLE; Schema: public_old; Owner: root; Tablespace: 
--

CREATE TABLE t1_cat (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL
);


ALTER TABLE public_old.t1_cat OWNER TO root;

--
-- Name: t1_tovar; Type: TABLE; Schema: public_old; Owner: root; Tablespace: 
--

CREATE TABLE t1_tovar (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Cat_ID" integer NOT NULL
);


ALTER TABLE public_old.t1_tovar OWNER TO root;

SET search_path = creator, pg_catalog;

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


SET search_path = public, pg_catalog;

--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE catalog_category ALTER COLUMN "ID" SET DEFAULT nextval('catalog_category_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE catalog_category ALTER COLUMN "Sort" SET DEFAULT currval('catalog_category_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE catalog_image ALTER COLUMN "ID" SET DEFAULT nextval('catalog_image_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE catalog_image ALTER COLUMN "Sort" SET DEFAULT currval('catalog_image_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE catalog_tovar ALTER COLUMN "ID" SET DEFAULT nextval('catalog_tovar_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE catalog_tovar ALTER COLUMN "Sort" SET DEFAULT currval('catalog_tovar_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE news ALTER COLUMN "ID" SET DEFAULT nextval('news_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE news ALTER COLUMN "Sort" SET DEFAULT currval('news_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE news_autor ALTER COLUMN "ID" SET DEFAULT nextval('news_autor_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE news_category ALTER COLUMN "ID" SET DEFAULT nextval('news_category_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE news_test ALTER COLUMN "ID" SET DEFAULT nextval('news_test_seq'::regclass);


--
-- Name: User_ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE "user" ALTER COLUMN "User_ID" SET DEFAULT nextval('user_seq'::regclass);


--
-- Name: Privileges; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE "user" ALTER COLUMN "Privileges" SET DEFAULT currval('user_seq'::regclass);


--
-- Name: Group_ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE user_group ALTER COLUMN "Group_ID" SET DEFAULT nextval('user_group_seq'::regclass);


--
-- Name: Sortirovka; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE user_group ALTER COLUMN "Sortirovka" SET DEFAULT currval('user_group_seq'::regclass);


SET search_path = public_old, pg_catalog;

--
-- Name: ID; Type: DEFAULT; Schema: public_old; Owner: znarus
--

ALTER TABLE catalog_category ALTER COLUMN "ID" SET DEFAULT nextval('catalog_category_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public_old; Owner: znarus
--

ALTER TABLE catalog_tovar ALTER COLUMN "ID" SET DEFAULT nextval('catalog_tovar_seq'::regclass);


SET search_path = creator, pg_catalog;

--
-- Data for Name: entity; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY entity ("ID", "Name", "Identified", "Desc", "Pack_ID") FROM stdin;
16	Категория	category		1
17	Товар	tovar		1
19	Рисунки	image		1
20	Новости	news		5
21	Категория	category		5
22	Автор	autor		5
27	Группа	group		6
28	Пользователь	user		6
29	Просто	prosto		5
30	Тесты	test		5
\.


--
-- Data for Name: enum; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY enum ("ID", "Name", "Field_ID", "Sort") FROM stdin;
\.


--
-- Data for Name: field; Type: TABLE DATA; Schema: creator; Owner: znarus
--

COPY field ("ID", "Name", "Identified", "Type_ID", "Desc", "Default", "Foreign_ID", "Entity_ID", "Null", "Sort") FROM stdin;
95	Тип	Type	18			\N	20	f	95
96	Идишник группы	Group_ID	3			\N	27	f	96
97	Наименование	Name	2			\N	27	f	97
98	Сортировка	Sortirovka	16			\N	27	f	98
99	ID	User_ID	3			\N	28	f	99
100	Наименование	Name	2			\N	28	f	100
101	Привилегии	Privileges	16			\N	28	f	101
102	Привязка к группе	Group_ID	19			96	28	f	102
103	Категория	Category_ID	19			43	20	f	103
45	ID	ID	3	123213		\N	20	f	45
104	Сортировка	Sort	16			\N	20	f	104
105	ID	ID	3			\N	30	f	105
31	ID	ID	3			\N	19	f	31
32	Наименование	Name	2			\N	19	f	32
33	Идентификатор	Identified	9			\N	19	f	33
34	Сортировка	Sort	16			\N	19	f	34
29	Цвет	Color	18			\N	17	f	35
35	ID	ID	3			\N	17	f	27
27	Наименование	Name	2			\N	17	f	29
36	Сортировка	Sort	16			\N	17	f	36
37	Привязка к товару	Tovar_ID	19			35	19	f	37
39	Сортировка	Sort	16			\N	16	f	39
23	Тип	Type	2			\N	16	f	38
38	ID	ID	3			\N	16	f	22
22	Наименование	Name	2			\N	16	t	23
40	Привязка к категории	Category_ID	19			38	17	f	40
41	ID	ID	3			\N	22	f	41
42	ФИО	Fio	2			\N	22	f	42
43	ID	ID	3			\N	21	f	43
44	Наименование	Name	2			\N	21	f	44
46	Наименование	Name	2			\N	20	f	46
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
1	Каталог	catalog
5	Новости	news
6	Пользователи	user
\.


SET search_path = public, pg_catalog;

--
-- Data for Name: catalog_category; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY catalog_category ("Name", "Type", "ID", "Sort") FROM stdin;
odin	type1	1	\N
dva	type2	2	\N
tri	type3	3	\N
chetire	type4	4	\N
pjat	type5	5	\N
odin	type1	6	\N
dva	type2	7	\N
tri	type3	8	\N
chetire	type4	9	\N
pjat	type5	10	\N
\.


--
-- Data for Name: catalog_image; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY catalog_image ("ID", "Name", "Identified", "Sort", "Tovar_ID") FROM stdin;
\.


--
-- Data for Name: catalog_tovar; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY catalog_tovar ("Name", "Color", "ID", "Sort", "Category_ID") FROM stdin;
\.


--
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY news ("ID", "Name_New", "Type", "Category_ID_New", "Sort") FROM stdin;
\.


--
-- Data for Name: news_autor; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY news_autor ("ID", "Fio") FROM stdin;
\.


--
-- Data for Name: news_category; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY news_category ("ID", "Name") FROM stdin;
\.


--
-- Data for Name: news_prosto; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY news_prosto  FROM stdin;
\.


--
-- Data for Name: news_test; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY news_test ("ID") FROM stdin;
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY "user" ("User_ID", "Name", "Privileges", "Group_ID") FROM stdin;
\.


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY user_group ("Group_ID", "Name", "Sortirovka") FROM stdin;
\.


SET search_path = public_old, pg_catalog;

--
-- Data for Name: catalog_category; Type: TABLE DATA; Schema: public_old; Owner: znarus
--

COPY catalog_category ("ID", "Name") FROM stdin;
1	Категория 1
3	Категория 3
4	Категория 4
5	Категория 5
\.


--
-- Data for Name: catalog_tovar; Type: TABLE DATA; Schema: public_old; Owner: znarus
--

COPY catalog_tovar ("ID", "Name", "Category_ID") FROM stdin;
1	Товар 1	1
3	Товар 3	1
4	Товар 4	1
5	Товар 5	3
\.


--
-- Data for Name: t1_cat; Type: TABLE DATA; Schema: public_old; Owner: root
--

COPY t1_cat ("ID", "Name") FROM stdin;
\.


--
-- Data for Name: t1_tovar; Type: TABLE DATA; Schema: public_old; Owner: root
--

COPY t1_tovar ("ID", "Name", "Cat_ID") FROM stdin;
\.


SET search_path = creator, pg_catalog;

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


SET search_path = public, pg_catalog;

--
-- Name: catalog_category_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY catalog_category
    ADD CONSTRAINT "catalog_category_PK" PRIMARY KEY ("ID");


--
-- Name: catalog_image_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY catalog_image
    ADD CONSTRAINT "catalog_image_PK" PRIMARY KEY ("ID");


--
-- Name: catalog_tovar_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY catalog_tovar
    ADD CONSTRAINT "catalog_tovar_PK" PRIMARY KEY ("ID");


--
-- Name: news_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY news
    ADD CONSTRAINT "news_PK" PRIMARY KEY ("ID");


--
-- Name: news_autor_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY news_autor
    ADD CONSTRAINT "news_autor_PK" PRIMARY KEY ("ID");


--
-- Name: news_category_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY news_category
    ADD CONSTRAINT "news_category_PK" PRIMARY KEY ("ID");


--
-- Name: news_test_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY news_test
    ADD CONSTRAINT "news_test_PK" PRIMARY KEY ("ID");


--
-- Name: user_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_PK" PRIMARY KEY ("User_ID");


--
-- Name: user_group_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT "user_group_PK" PRIMARY KEY ("Group_ID");


SET search_path = public_old, pg_catalog;

--
-- Name: catalog_category_PK; Type: CONSTRAINT; Schema: public_old; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY catalog_category
    ADD CONSTRAINT "catalog_category_PK" PRIMARY KEY ("ID");


--
-- Name: catalog_category_UN_Name; Type: CONSTRAINT; Schema: public_old; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY catalog_category
    ADD CONSTRAINT "catalog_category_UN_Name" UNIQUE ("Name");


--
-- Name: catalog_tovar_PK; Type: CONSTRAINT; Schema: public_old; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY catalog_tovar
    ADD CONSTRAINT "catalog_tovar_PK" PRIMARY KEY ("ID");


--
-- Name: catalog_tovar_UN_Name; Type: CONSTRAINT; Schema: public_old; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY catalog_tovar
    ADD CONSTRAINT "catalog_tovar_UN_Name" UNIQUE ("Name");


--
-- Name: t1_cat_PK; Type: CONSTRAINT; Schema: public_old; Owner: root; Tablespace: 
--

ALTER TABLE ONLY t1_cat
    ADD CONSTRAINT "t1_cat_PK" PRIMARY KEY ("ID");


--
-- Name: t1_tovar_PK; Type: CONSTRAINT; Schema: public_old; Owner: root; Tablespace: 
--

ALTER TABLE ONLY t1_tovar
    ADD CONSTRAINT "t1_tovar_PK" PRIMARY KEY ("ID");


SET search_path = creator, pg_catalog;

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


SET search_path = public, pg_catalog;

--
-- Name: catalog_image_FK_Tovar_ID; Type: FK CONSTRAINT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY catalog_image
    ADD CONSTRAINT "catalog_image_FK_Tovar_ID" FOREIGN KEY ("Tovar_ID") REFERENCES catalog_tovar("ID");


--
-- Name: catalog_tovar_FK_Category_ID; Type: FK CONSTRAINT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY catalog_tovar
    ADD CONSTRAINT "catalog_tovar_FK_Category_ID" FOREIGN KEY ("Category_ID") REFERENCES catalog_category("ID");


--
-- Name: news_FK_Category_ID; Type: FK CONSTRAINT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY news
    ADD CONSTRAINT "news_FK_Category_ID" FOREIGN KEY ("Category_ID_New") REFERENCES news_category("ID");


--
-- Name: user_FK_Group_ID; Type: FK CONSTRAINT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_FK_Group_ID" FOREIGN KEY ("Group_ID") REFERENCES user_group("Group_ID");


SET search_path = public_old, pg_catalog;

--
-- Name: catalog_tovar_FK_Category_ID; Type: FK CONSTRAINT; Schema: public_old; Owner: znarus
--

ALTER TABLE ONLY catalog_tovar
    ADD CONSTRAINT "catalog_tovar_FK_Category_ID" FOREIGN KEY ("Category_ID") REFERENCES catalog_category("ID");


--
-- Name: t1_tovar_FK_Cat_ID; Type: FK CONSTRAINT; Schema: public_old; Owner: root
--

ALTER TABLE ONLY t1_tovar
    ADD CONSTRAINT "t1_tovar_FK_Cat_ID" FOREIGN KEY ("Cat_ID") REFERENCES t1_cat("ID");


--
-- Name: public_old; Type: ACL; Schema: -; Owner: znarus
--

REVOKE ALL ON SCHEMA public_old FROM PUBLIC;
REVOKE ALL ON SCHEMA public_old FROM znarus;
GRANT ALL ON SCHEMA public_old TO znarus;
GRANT ALL ON SCHEMA public_old TO PUBLIC;


--
-- PostgreSQL database dump complete
--

