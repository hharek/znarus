--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: core; Type: SCHEMA; Schema: -; Owner: znarus
--

CREATE SCHEMA core;


ALTER SCHEMA core OWNER TO znarus;

--
-- Name: SCHEMA core; Type: COMMENT; Schema: -; Owner: znarus
--

COMMENT ON SCHEMA core IS 'Ядро';


--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = core, pg_catalog;

--
-- Name: module_type; Type: TYPE; Schema: core; Owner: znarus
--

CREATE TYPE module_type AS ENUM (
    'mod',
    'smod'
);


ALTER TYPE core.module_type OWNER TO znarus;

--
-- Name: param_type; Type: TYPE; Schema: core; Owner: znarus
--

CREATE TYPE param_type AS ENUM (
    'string',
    'int',
    'bool'
);


ALTER TYPE core.param_type OWNER TO znarus;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: admin; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE admin (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Visible" boolean DEFAULT false NOT NULL,
    "Sort" integer NOT NULL,
    "Module_ID" integer NOT NULL
);


ALTER TABLE core.admin OWNER TO znarus;

--
-- Name: TABLE admin; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE admin IS 'Админки';


--
-- Name: COLUMN admin."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."ID" IS 'Порядковый номер';


--
-- Name: COLUMN admin."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Name" IS 'Наименование';


--
-- Name: COLUMN admin."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Identified" IS 'Идентификатор';


--
-- Name: COLUMN admin."Visible"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Visible" IS 'Видимость';


--
-- Name: COLUMN admin."Sort"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Sort" IS 'Сортировка';


--
-- Name: COLUMN admin."Module_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Module_ID" IS 'Привязка к модулю';


--
-- Name: admin_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE admin_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.admin_seq OWNER TO znarus;

--
-- Name: admin_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE admin_seq OWNED BY admin."ID";


--
-- Name: exe; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE exe (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Module_ID" integer NOT NULL,
    "Priority" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL
);


ALTER TABLE core.exe OWNER TO znarus;

--
-- Name: TABLE exe; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE exe IS 'Исполнители';


--
-- Name: COLUMN exe."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN exe."ID" IS 'Порядковый номер';


--
-- Name: COLUMN exe."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN exe."Name" IS 'Наименование';


--
-- Name: COLUMN exe."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN exe."Identified" IS 'Идентификатор';


--
-- Name: COLUMN exe."Module_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN exe."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN exe."Priority"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN exe."Priority" IS 'Порядок исполнения';


--
-- Name: COLUMN exe."Active"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN exe."Active" IS 'Активность';


--
-- Name: exe_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE exe_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.exe_seq OWNER TO znarus;

--
-- Name: exe_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE exe_seq OWNED BY exe."ID";


--
-- Name: html; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE html (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE core.html OWNER TO znarus;

--
-- Name: TABLE html; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE html IS 'Основной шаблон';


--
-- Name: COLUMN html."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN html."ID" IS 'Порядковый номер';


--
-- Name: COLUMN html."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN html."Name" IS 'Наименование';


--
-- Name: COLUMN html."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN html."Identified" IS 'Идентификатор';


--
-- Name: html_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE html_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.html_seq OWNER TO znarus;

--
-- Name: html_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE html_seq OWNED BY html."ID";


--
-- Name: inc; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE inc (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Module_ID" integer NOT NULL,
    "Active" boolean DEFAULT true NOT NULL
);


ALTER TABLE core.inc OWNER TO znarus;

--
-- Name: TABLE inc; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE inc IS 'Инки';


--
-- Name: COLUMN inc."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN inc."ID" IS 'Порядковый номер';


--
-- Name: COLUMN inc."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN inc."Name" IS 'Наименование';


--
-- Name: COLUMN inc."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN inc."Identified" IS 'Идентификатор';


--
-- Name: COLUMN inc."Module_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN inc."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN inc."Active"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN inc."Active" IS 'Активность';


--
-- Name: inc_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE inc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.inc_seq OWNER TO znarus;

--
-- Name: inc_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE inc_seq OWNED BY inc."ID";


--
-- Name: module; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE module (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Desc" pg_catalog.text DEFAULT ''::pg_catalog.text,
    "Version" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Type" module_type,
    "Url" character varying(255) DEFAULT ''::character varying,
    "Html_ID" integer,
    "Active" boolean DEFAULT false NOT NULL
);


ALTER TABLE core.module OWNER TO znarus;

--
-- Name: TABLE module; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE module IS 'Модуль';


--
-- Name: COLUMN module."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."ID" IS 'Порядковый номер';


--
-- Name: COLUMN module."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Name" IS 'Наименование';


--
-- Name: COLUMN module."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Identified" IS 'Идентификатор';


--
-- Name: COLUMN module."Desc"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Desc" IS 'Описание';


--
-- Name: COLUMN module."Version"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Version" IS 'Версия';


--
-- Name: COLUMN module."Type"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Type" IS 'Тип - обычный или системный';


--
-- Name: COLUMN module."Url"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Url" IS 'Урл';


--
-- Name: COLUMN module."Html_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Html_ID" IS 'Привязка к основному шаблону';


--
-- Name: COLUMN module."Active"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Active" IS 'Активность';


--
-- Name: module_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE module_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.module_seq OWNER TO znarus;

--
-- Name: module_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE module_seq OWNED BY module."ID";


--
-- Name: param; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE param (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Type" param_type DEFAULT 'string'::param_type NOT NULL,
    "Value" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Module_ID" integer NOT NULL
);


ALTER TABLE core.param OWNER TO znarus;

--
-- Name: TABLE param; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE param IS 'Параметры';


--
-- Name: COLUMN param."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN param."ID" IS 'Порядковый номер';


--
-- Name: COLUMN param."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN param."Name" IS 'Наименование';


--
-- Name: COLUMN param."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN param."Identified" IS 'Идентификатор';


--
-- Name: COLUMN param."Type"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN param."Type" IS 'Тип';


--
-- Name: COLUMN param."Value"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN param."Value" IS 'Значение';


--
-- Name: COLUMN param."Module_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN param."Module_ID" IS 'Привязка к модулю';


--
-- Name: param_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE param_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.param_seq OWNER TO znarus;

--
-- Name: param_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE param_seq OWNED BY param."ID";


--
-- Name: phpclass; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE phpclass (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Module_ID" integer NOT NULL
);


ALTER TABLE core.phpclass OWNER TO znarus;

--
-- Name: TABLE phpclass; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE phpclass IS 'PHP класс';


--
-- Name: COLUMN phpclass."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN phpclass."ID" IS 'Порядковый номер';


--
-- Name: COLUMN phpclass."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN phpclass."Name" IS 'Наименование';


--
-- Name: COLUMN phpclass."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN phpclass."Identified" IS 'Идентификатор';


--
-- Name: COLUMN phpclass."Module_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN phpclass."Module_ID" IS 'Привязка к модулю';


--
-- Name: phpclass_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE phpclass_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.phpclass_seq OWNER TO znarus;

--
-- Name: phpclass_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE phpclass_seq OWNED BY phpclass."ID";


--
-- Name: text; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE text (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Value" pg_catalog.text DEFAULT ''::pg_catalog.text NOT NULL,
    "Module_ID" integer NOT NULL
);


ALTER TABLE core.text OWNER TO znarus;

--
-- Name: TABLE text; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE text IS 'Тексты';


--
-- Name: COLUMN text."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN text."ID" IS 'Порядковый номер';


--
-- Name: COLUMN text."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN text."Name" IS 'Наименование';


--
-- Name: COLUMN text."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN text."Identified" IS 'Идентификатор';


--
-- Name: COLUMN text."Value"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN text."Value" IS 'Значение';


--
-- Name: COLUMN text."Module_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN text."Module_ID" IS 'Привязка к модулю';


--
-- Name: text_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE text_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.text_seq OWNER TO znarus;

--
-- Name: text_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE text_seq OWNED BY text."ID";


--
-- Name: user; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE "user" (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Email" character varying(127) NOT NULL,
    "Password" character(32) NOT NULL,
    "Group_ID" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL
);


ALTER TABLE core."user" OWNER TO znarus;

--
-- Name: TABLE "user"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE "user" IS 'Пользователи';


--
-- Name: COLUMN "user"."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN "user"."ID" IS 'Порядковый номер';


--
-- Name: COLUMN "user"."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN "user"."Name" IS 'Наименование';


--
-- Name: COLUMN "user"."Email"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN "user"."Email" IS 'Почтовый ящик';


--
-- Name: COLUMN "user"."Password"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN "user"."Password" IS 'Хэш пароля';


--
-- Name: COLUMN "user"."Group_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN "user"."Group_ID" IS 'Привязка к группе';


--
-- Name: COLUMN "user"."Active"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN "user"."Active" IS 'Активность';


--
-- Name: user_group; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE user_group (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL
);


ALTER TABLE core.user_group OWNER TO znarus;

--
-- Name: TABLE user_group; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE user_group IS 'Группа пользователей';


--
-- Name: COLUMN user_group."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_group."ID" IS 'Порядковый номер';


--
-- Name: COLUMN user_group."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_group."Name" IS 'Наименование';


--
-- Name: user_group_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE user_group_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.user_group_seq OWNER TO znarus;

--
-- Name: user_group_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE user_group_seq OWNED BY user_group."ID";


--
-- Name: user_priv; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE user_priv (
    "Admin_ID" integer NOT NULL,
    "Group_ID" integer NOT NULL
);


ALTER TABLE core.user_priv OWNER TO znarus;

--
-- Name: TABLE user_priv; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE user_priv IS 'Привилегии пользователей';


--
-- Name: COLUMN user_priv."Admin_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_priv."Admin_ID" IS 'Привязка к админке';


--
-- Name: COLUMN user_priv."Group_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_priv."Group_ID" IS 'Привязка к группе';


--
-- Name: user_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE user_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.user_seq OWNER TO znarus;

--
-- Name: user_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE user_seq OWNED BY "user"."ID";


--
-- Name: user_session; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE user_session (
    "ID" character(32) NOT NULL,
    "Date" timestamp without time zone DEFAULT now() NOT NULL,
    "IP" character varying(15) NOT NULL,
    "Browser" character varying(255) NOT NULL,
    "User_ID" integer NOT NULL
);


ALTER TABLE core.user_session OWNER TO znarus;

--
-- Name: TABLE user_session; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE user_session IS 'Сессии пользователей';


--
-- Name: COLUMN user_session."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_session."ID" IS 'Идентификатор сессии';


--
-- Name: COLUMN user_session."Date"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_session."Date" IS 'Дата окончания действия сессии';


--
-- Name: COLUMN user_session."IP"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_session."IP" IS 'IP адрес создателя сессии';


--
-- Name: COLUMN user_session."Browser"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_session."Browser" IS 'Строка USER_AGENT браузера создателя сессии';


--
-- Name: COLUMN user_session."User_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN user_session."User_ID" IS 'Привязка к пользователю';


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY admin ALTER COLUMN "ID" SET DEFAULT nextval('admin_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY admin ALTER COLUMN "Sort" SET DEFAULT currval('admin_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY exe ALTER COLUMN "ID" SET DEFAULT nextval('exe_seq'::regclass);


--
-- Name: Priority; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY exe ALTER COLUMN "Priority" SET DEFAULT currval('exe_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY html ALTER COLUMN "ID" SET DEFAULT nextval('html_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY inc ALTER COLUMN "ID" SET DEFAULT nextval('inc_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY module ALTER COLUMN "ID" SET DEFAULT nextval('module_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY param ALTER COLUMN "ID" SET DEFAULT nextval('param_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY phpclass ALTER COLUMN "ID" SET DEFAULT nextval('phpclass_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY text ALTER COLUMN "ID" SET DEFAULT nextval('text_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY "user" ALTER COLUMN "ID" SET DEFAULT nextval('user_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY user_group ALTER COLUMN "ID" SET DEFAULT nextval('user_group_seq'::regclass);


--
-- Data for Name: admin; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY admin ("ID", "Name", "Identified", "Visible", "Sort", "Module_ID") FROM stdin;
1	Настройки	settings	t	8	2
5	Удалить	delete	f	7	2
8	Редактировать	list	t	1	2
7	Редактировать статью	edit	f	5	2
4	Добавить	add	f	4	2
13	Категория. Сортировка вверх	category_sort_up	f	13	13
14	Категория. Сортировка вниз	category_sort_down	f	14	13
15	Категория. Активация	category_active	f	15	13
10	Категория. Добавить	category_add	f	10	13
11	Категория. Редактировать	category_edit	f	11	13
12	Категория. Удалить	category_delete	f	12	13
9	Редактировать	category	t	9	13
16	Категория. Деактивировать	category_deactive	f	16	13
17	Товары	tovar	f	17	13
18	Товар. Добавить	tovar_add	f	18	13
19	Товар. Редактировать	tovar_edit	f	19	13
20	Товар. Удалить	tovar_delete	f	20	13
21	Товар. Сортировка вверх	tovar_sort_up	f	21	13
22	Товар. Сортировка вниз	tovar_sort_down	f	22	13
23	Товар. Активировать	tovar_active	f	23	13
24	Товар. Деактивировать	tovar_deactive	f	24	13
26	Акции	action	t	25	13
25	Настройки	settings	t	27	13
27	Акции. Удалить	action_delete	f	26	13
\.


--
-- Name: admin_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('admin_seq', 29, true);


--
-- Data for Name: exe; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY exe ("ID", "Name", "Identified", "Module_ID", "Priority", "Active") FROM stdin;
2	Описание	desc	2	3	t
12	Один	odin	2	2	t
6	Список	list	2	6	f
3	Категории	category	2	12	t
13	Главная страница	glav	13	13	t
14	Категория	category	13	14	t
15	Товар	tovar	13	15	t
16	Акции	action	13	16	t
17	Личный кабинет	kabinet	13	17	t
\.


--
-- Name: exe_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('exe_seq', 19, true);


--
-- Data for Name: html; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY html ("ID", "Name", "Identified") FROM stdin;
6	По умолчанию	default
7	Новости	news
8	Ошибка 404	404
10	Главная страница	home
11	Каталог	catalog
1	Статьи	articles
3	Авторизация	auth
9	Ошибка 403	403
\.


--
-- Name: html_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('html_seq', 14, true);


--
-- Data for Name: inc; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY inc ("ID", "Name", "Identified", "Module_ID", "Active") FROM stdin;
3	Меню	left	2	t
1	Случайная статья	rand	2	t
6	Корзина	basket	2	f
7	Новый инк	inc1	2	t
8	Меню слева	menu_left	13	t
9	Акции	action	13	t
10	Инициализация корзины	basket_init	13	t
\.


--
-- Name: inc_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('inc_seq', 12, true);


--
-- Data for Name: module; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY module ("ID", "Name", "Identified", "Desc", "Version", "Type", "Url", "Html_ID", "Active") FROM stdin;
12	Страницы	page		1.0	mod		\N	t
16	Глоссарий	glossary		1.0	mod		\N	f
2	Статьи	articles	Описание статьей	1.10	mod	статьи	\N	t
19	Меню	menu	Описание меню	2.1	mod	menu	\N	t
23	Пользователи	user	Управление пользователями и привилегиями	2.1	smod	user	\N	t
24	Мета	meta	Управление метой	3.1	smod	meta	\N	t
25	Вёрстка	design	Вёрстка шаблонов и прочее	1.0	smod	design	\N	t
13	Каталог	catalog		2.0	mod		\N	t
27	Облако тэгов	tag		1.0	smod	tag	\N	t
28	Поиск	search	Поиск	2.1	smod	search	\N	t
29	Портфолио	portfolio	Портфолио	2.0	mod	portfolio	\N	t
\.


--
-- Name: module_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('module_seq', 35, true);


--
-- Data for Name: param; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY param ("ID", "Name", "Identified", "Type", "Value", "Module_ID") FROM stdin;
6	Отображать	visible	bool	1	2
3	Кол-во статьей на страницу	str_count	int	5	2
4	Просто строка	stroka	string	Всё может быть, что быть не может, и даже то что может быть. И даже то что быть не может, всё тоже очень даже может быть.	2
7	Кол-во товаров на страницу	tovar_str	int	10	13
8	Почтовый ящик для оповещения	email_notify	string	test@znarus.ru	13
9	Дата последнего заказа	zakaz_data_last	string	10.09.2013	13
\.


--
-- Name: param_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('param_seq', 11, true);


--
-- Data for Name: phpclass; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY phpclass ("ID", "Name", "Identified", "Module_ID") FROM stdin;
10	Статья	Articles	2
12	Категория	Articles_Category	2
13	Категория	Catalog_Category	13
14	Товар	Catalog_Tovar	13
16	Корзина	Category_Basket	13
\.


--
-- Name: phpclass_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('phpclass_seq', 18, true);


--
-- Data for Name: text; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY text ("ID", "Name", "Identified", "Value", "Module_ID") FROM stdin;
8	Приветсвтенная страница	home	Добро пожаловать	2
9	Бегущая строка	running_man	Всё может быть что быть не может и даже то что может быть.	2
10	Текст при оформлении заказа	zakaz	Вы оформили заказ, всего хорошего.	13
11	Текст на главной	glav	Добро пожаловать в наш каталог	13
12	Просто текст	prosto	Всё может быть, что быть не может.\r\n	13
\.


--
-- Name: text_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('text_seq', 14, true);


--
-- Data for Name: user; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY "user" ("ID", "Name", "Email", "Password", "Group_ID", "Active") FROM stdin;
5	Пользователь 5	email5@znarus.znt	9f406368f742eadadef944d3315837cc	3	f
3	Пользователь 3	test@znarus.ru	decb50efb72e7577b968417f776c8c81	3	t
6	Комбайнёр	kombain@znarus.znt	a91269733f5b1d55974d537e9147e775	3	f
7	Один	odin@znarus.znt	f4c95d547fbff32e2326355af37f7524	5	f
\.


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_group ("ID", "Name") FROM stdin;
3	Группа 3
4	Группа 4
5	Группа 5
7	Тестеры
\.


--
-- Name: user_group_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('user_group_seq', 8, true);


--
-- Data for Name: user_priv; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_priv ("Admin_ID", "Group_ID") FROM stdin;
9	3
9	4
9	5
10	3
10	4
10	5
11	3
11	5
12	3
12	5
13	3
13	5
14	3
14	5
15	3
15	5
16	3
16	5
17	3
17	5
18	3
18	5
19	3
19	5
20	3
20	5
21	3
21	5
22	3
22	5
23	3
23	5
24	3
24	5
26	3
26	5
27	3
27	5
25	3
25	5
8	4
4	4
7	4
5	4
1	4
\.


--
-- Name: user_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('user_seq', 9, true);


--
-- Data for Name: user_session; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_session ("ID", "Date", "IP", "Browser", "User_ID") FROM stdin;
454d1eb34fe968609b2622b5517ad42c	2013-09-29 03:12:59.072791	127.0.0.1	Opera/9.80 (X11; Linux i686) Presto/2.12.388 Version/12.16	3
\.


--
-- Name: admin_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_PK" PRIMARY KEY ("ID");


--
-- Name: admin_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: admin_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: exe_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_PK" PRIMARY KEY ("ID");


--
-- Name: exe_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: exe_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: html_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY html
    ADD CONSTRAINT "html_PK" PRIMARY KEY ("ID");


--
-- Name: html_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY html
    ADD CONSTRAINT "html_UN_Identified" UNIQUE ("Identified");


--
-- Name: html_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY html
    ADD CONSTRAINT "html_UN_Name" UNIQUE ("Name");


--
-- Name: inc_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_PK" PRIMARY KEY ("ID");


--
-- Name: inc_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: inc_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: module_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY module
    ADD CONSTRAINT "module_PK" PRIMARY KEY ("ID");


--
-- Name: module_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY module
    ADD CONSTRAINT "module_UN_Identified" UNIQUE ("Identified");


--
-- Name: module_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY module
    ADD CONSTRAINT "module_UN_Name" UNIQUE ("Name");


--
-- Name: param_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_PK" PRIMARY KEY ("ID");


--
-- Name: param_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: param_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: phpclass_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_PK" PRIMARY KEY ("ID");


--
-- Name: phpclass_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: phpclass_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: text_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_PK" PRIMARY KEY ("ID");


--
-- Name: text_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: text_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: user_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_PK" PRIMARY KEY ("ID");


--
-- Name: user_UN_Email; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_UN_Email" UNIQUE ("Email");


--
-- Name: user_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_UN_Name" UNIQUE ("Name");


--
-- Name: user_group_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT "user_group_PK" PRIMARY KEY ("ID");


--
-- Name: user_group_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT "user_group_UN_Name" UNIQUE ("Name");


--
-- Name: user_priv_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY user_priv
    ADD CONSTRAINT "user_priv_PK" PRIMARY KEY ("Admin_ID", "Group_ID");


--
-- Name: user_session_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY user_session
    ADD CONSTRAINT "user_session_PK" PRIMARY KEY ("ID");


--
-- Name: admin_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: exe_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: inc_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: module_FK_Html_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY module
    ADD CONSTRAINT "module_FK_Html_ID" FOREIGN KEY ("Html_ID") REFERENCES html("ID");


--
-- Name: param_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: phpclass_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: text_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: user_FK_Group_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_FK_Group_ID" FOREIGN KEY ("Group_ID") REFERENCES user_group("ID");


--
-- Name: user_priv_FK_Admin_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY user_priv
    ADD CONSTRAINT "user_priv_FK_Admin_ID" FOREIGN KEY ("Admin_ID") REFERENCES admin("ID");


--
-- Name: user_priv_FK_Group_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY user_priv
    ADD CONSTRAINT "user_priv_FK_Group_ID" FOREIGN KEY ("Group_ID") REFERENCES user_group("ID");


--
-- Name: user_session_FK_User_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY user_session
    ADD CONSTRAINT "user_session_FK_User_ID" FOREIGN KEY ("User_ID") REFERENCES "user"("ID");


--
-- Name: public; Type: ACL; Schema: -; Owner: root
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM root;
GRANT ALL ON SCHEMA public TO root;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

