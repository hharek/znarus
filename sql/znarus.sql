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
    "Sort" integer NOT NULL,
    "Get" boolean DEFAULT true NOT NULL,
    "Post" boolean DEFAULT false NOT NULL,
    "Visible" boolean DEFAULT false NOT NULL,
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
-- Name: COLUMN admin."Sort"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Sort" IS 'Сортировка';


--
-- Name: COLUMN admin."Get"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Get" IS 'Обработка GET данных';


--
-- Name: COLUMN admin."Post"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Post" IS 'Обработка POST данных';


--
-- Name: COLUMN admin."Visible"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Visible" IS 'Видимость';


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
    "User_ID" integer
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

COMMENT ON COLUMN user_session."User_ID" IS 'Привязка к пользователю, если NULL то root';


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

COPY admin ("ID", "Name", "Identified", "Sort", "Get", "Post", "Visible", "Module_ID") FROM stdin;
1	Модули	module	1	t	f	t	1
3	Сведения о PHP	php	3	t	f	t	1
2	Сведения о системе	sys	2	t	f	t	1
5	phpinfo	phpinfo	4	t	f	f	1
4	Сведения о PostgreSQL	pgsql	5	t	f	t	1
\.


--
-- Name: admin_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('admin_seq', 5, true);


--
-- Data for Name: exe; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY exe ("ID", "Name", "Identified", "Module_ID", "Priority", "Active") FROM stdin;
\.


--
-- Name: exe_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('exe_seq', 1, false);


--
-- Data for Name: html; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY html ("ID", "Name", "Identified") FROM stdin;
\.


--
-- Name: html_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('html_seq', 1, false);


--
-- Data for Name: inc; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY inc ("ID", "Name", "Identified", "Module_ID", "Active") FROM stdin;
\.


--
-- Name: inc_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('inc_seq', 1, false);


--
-- Data for Name: module; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY module ("ID", "Name", "Identified", "Desc", "Version", "Type", "Url", "Html_ID", "Active") FROM stdin;
1	Сервис	zn_service	Сведения о модулях\r\nСведение о системе\r\nСведения о PHP\r\nСведения о PostgreSQL	1.0	smod		\N	t
\.


--
-- Name: module_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('module_seq', 1, true);


--
-- Data for Name: param; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY param ("ID", "Name", "Identified", "Type", "Value", "Module_ID") FROM stdin;
\.


--
-- Name: param_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('param_seq', 1, false);


--
-- Data for Name: phpclass; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY phpclass ("ID", "Name", "Identified", "Module_ID") FROM stdin;
\.


--
-- Name: phpclass_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('phpclass_seq', 1, false);


--
-- Data for Name: text; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY text ("ID", "Name", "Identified", "Value", "Module_ID") FROM stdin;
\.


--
-- Name: text_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('text_seq', 1, false);


--
-- Data for Name: user; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY "user" ("ID", "Name", "Email", "Password", "Group_ID", "Active") FROM stdin;
1	Один	odin@znarus.znt	f4c95d547fbff32e2326355af37f7524	1	t
2	Два	dva@znarus.znt	a91269733f5b1d55974d537e9147e775	1	f
\.


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_group ("ID", "Name") FROM stdin;
1	Операторы
\.


--
-- Name: user_group_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('user_group_seq', 1, true);


--
-- Data for Name: user_priv; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_priv ("Admin_ID", "Group_ID") FROM stdin;
1	1
2	1
3	1
5	1
4	1
\.


--
-- Name: user_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('user_seq', 2, true);


--
-- Data for Name: user_session; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_session ("ID", "Date", "IP", "Browser", "User_ID") FROM stdin;
b3ddcc9bc37adcaa8d20ac60bd3f5abf	2013-10-24 22:05:14.673337	127.0.0.1	Opera/9.80 (X11; Linux i686) Presto/2.12.388 Version/12.16	\N
d6b367d04eb7f5aedfc5b402d0df5660	2013-10-24 22:05:27.124636	127.0.0.1	Opera/9.80 (X11; Linux i686) Presto/2.12.388 Version/12.16	1
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

