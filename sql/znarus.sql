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

--
-- Name: proc_type; Type: TYPE; Schema: core; Owner: znarus
--

CREATE TYPE proc_type AS ENUM (
    'start',
    'end'
);


ALTER TYPE core.proc_type OWNER TO znarus;

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
    "Module_ID" integer NOT NULL,
    "Window" boolean DEFAULT false NOT NULL,
    "Allow_All" boolean DEFAULT false NOT NULL
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
-- Name: COLUMN admin."Window"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Window" IS 'В новом окне';


--
-- Name: COLUMN admin."Allow_All"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN admin."Allow_All" IS 'Разрешить всем';


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
-- Name: html_inc; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE html_inc (
    "Html_ID" integer NOT NULL,
    "Inc_ID" integer NOT NULL
);


ALTER TABLE core.html_inc OWNER TO znarus;

--
-- Name: TABLE html_inc; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE html_inc IS 'Составные части шаблона';


--
-- Name: COLUMN html_inc."Html_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN html_inc."Html_ID" IS 'Привязка к шаблону';


--
-- Name: COLUMN html_inc."Inc_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN html_inc."Inc_ID" IS 'Привязка к инку';


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
    "Active" boolean DEFAULT false NOT NULL,
    "Pages_Isset" boolean DEFAULT true NOT NULL
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
-- Name: COLUMN module."Active"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Active" IS 'Активность';


--
-- Name: COLUMN module."Pages_Isset"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN module."Pages_Isset" IS 'Наличие страниц';


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
    "Module_ID" integer
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
-- Name: proc; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE proc (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Type" proc_type,
    "Module_ID" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL
);


ALTER TABLE core.proc OWNER TO znarus;

--
-- Name: TABLE proc; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE proc IS 'Процедуры';


--
-- Name: COLUMN proc."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN proc."ID" IS 'Порядковый номер';


--
-- Name: COLUMN proc."Name"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN proc."Name" IS 'Наименование';


--
-- Name: COLUMN proc."Identified"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN proc."Identified" IS 'Идентификатор';


--
-- Name: COLUMN proc."Type"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN proc."Type" IS 'Тип исполнения в начале или в конце';


--
-- Name: COLUMN proc."Module_ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN proc."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN proc."Active"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN proc."Active" IS 'Активность';


--
-- Name: proc_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE proc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.proc_seq OWNER TO znarus;

--
-- Name: proc_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE proc_seq OWNED BY proc."ID";


--
-- Name: seo_redirect; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE seo_redirect (
    "ID" integer NOT NULL,
    "From" character varying(127) NOT NULL,
    "To" character varying(127) NOT NULL
);


ALTER TABLE core.seo_redirect OWNER TO znarus;

--
-- Name: TABLE seo_redirect; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE seo_redirect IS 'Адреса для переадресации';


--
-- Name: COLUMN seo_redirect."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_redirect."ID" IS 'Порядковый номер';


--
-- Name: COLUMN seo_redirect."From"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_redirect."From" IS 'Источник';


--
-- Name: COLUMN seo_redirect."To"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_redirect."To" IS 'Назначение';


--
-- Name: seo_redirect_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE seo_redirect_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.seo_redirect_seq OWNER TO znarus;

--
-- Name: seo_redirect_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE seo_redirect_seq OWNED BY seo_redirect."ID";


--
-- Name: seo_url; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE seo_url (
    "ID" integer NOT NULL,
    "Url" character varying(127) NOT NULL,
    "Title" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Keywords" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Description" character varying(255) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE core.seo_url OWNER TO znarus;

--
-- Name: TABLE seo_url; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON TABLE seo_url IS 'Адреса для продвижения';


--
-- Name: COLUMN seo_url."ID"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_url."ID" IS 'Порядковый номер';


--
-- Name: COLUMN seo_url."Url"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_url."Url" IS 'Адрес';


--
-- Name: COLUMN seo_url."Title"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_url."Title" IS 'Тег title';


--
-- Name: COLUMN seo_url."Keywords"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_url."Keywords" IS 'Тег meta keywords';


--
-- Name: COLUMN seo_url."Description"; Type: COMMENT; Schema: core; Owner: znarus
--

COMMENT ON COLUMN seo_url."Description" IS 'Тег meta description';


--
-- Name: seo_url_seq; Type: SEQUENCE; Schema: core; Owner: znarus
--

CREATE SEQUENCE seo_url_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE core.seo_url_seq OWNER TO znarus;

--
-- Name: seo_url_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: znarus
--

ALTER SEQUENCE seo_url_seq OWNED BY seo_url."ID";


--
-- Name: text; Type: TABLE; Schema: core; Owner: znarus; Tablespace: 
--

CREATE TABLE text (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Value" pg_catalog.text DEFAULT ''::pg_catalog.text NOT NULL,
    "Module_ID" integer
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


SET search_path = public, pg_catalog;

--
-- Name: menu; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE menu (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL
);


ALTER TABLE public.menu OWNER TO znarus;

--
-- Name: TABLE menu; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON TABLE menu IS 'Меню';


--
-- Name: COLUMN menu."ID"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu."ID" IS 'Порядковый номер';


--
-- Name: COLUMN menu."Name"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu."Name" IS 'Наименование';


--
-- Name: menu_item; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE menu_item (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Url" character varying(127) NOT NULL,
    "Parent" integer,
    "Menu_ID" integer NOT NULL,
    "Sort" integer NOT NULL
);


ALTER TABLE public.menu_item OWNER TO znarus;

--
-- Name: TABLE menu_item; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON TABLE menu_item IS 'Пункты меню';


--
-- Name: COLUMN menu_item."ID"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu_item."ID" IS 'Порядковый номер';


--
-- Name: COLUMN menu_item."Name"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu_item."Name" IS 'Наименование';


--
-- Name: COLUMN menu_item."Url"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu_item."Url" IS 'Урл';


--
-- Name: COLUMN menu_item."Parent"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu_item."Parent" IS 'Корень';


--
-- Name: COLUMN menu_item."Menu_ID"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu_item."Menu_ID" IS 'Привязка к меню';


--
-- Name: COLUMN menu_item."Sort"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN menu_item."Sort" IS 'Сортировка';


--
-- Name: menu_item_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE menu_item_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.menu_item_seq OWNER TO znarus;

--
-- Name: menu_item_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE menu_item_seq OWNED BY menu_item."ID";


--
-- Name: menu_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE menu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.menu_seq OWNER TO znarus;

--
-- Name: menu_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE menu_seq OWNED BY menu."ID";


--
-- Name: page; Type: TABLE; Schema: public; Owner: znarus; Tablespace: 
--

CREATE TABLE page (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Url" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Content" text,
    "Parent" integer,
    "Html_Identified" character varying(127) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.page OWNER TO znarus;

--
-- Name: TABLE page; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON TABLE page IS 'Страницы';


--
-- Name: COLUMN page."ID"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN page."ID" IS 'Порядковый номер';


--
-- Name: COLUMN page."Name"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN page."Name" IS 'Наименование';


--
-- Name: COLUMN page."Url"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN page."Url" IS 'Урл';


--
-- Name: COLUMN page."Content"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN page."Content" IS 'Содержимое';


--
-- Name: COLUMN page."Parent"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN page."Parent" IS 'Корень';


--
-- Name: COLUMN page."Html_Identified"; Type: COMMENT; Schema: public; Owner: znarus
--

COMMENT ON COLUMN page."Html_Identified" IS 'Наименование шаблона';


--
-- Name: page_seq; Type: SEQUENCE; Schema: public; Owner: znarus
--

CREATE SEQUENCE page_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.page_seq OWNER TO znarus;

--
-- Name: page_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: znarus
--

ALTER SEQUENCE page_seq OWNED BY page."ID";


SET search_path = core, pg_catalog;

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

ALTER TABLE ONLY proc ALTER COLUMN "ID" SET DEFAULT nextval('proc_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY seo_redirect ALTER COLUMN "ID" SET DEFAULT nextval('seo_redirect_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY seo_url ALTER COLUMN "ID" SET DEFAULT nextval('seo_url_seq'::regclass);


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


SET search_path = public, pg_catalog;

--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY menu ALTER COLUMN "ID" SET DEFAULT nextval('menu_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY menu_item ALTER COLUMN "ID" SET DEFAULT nextval('menu_item_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY menu_item ALTER COLUMN "Sort" SET DEFAULT currval('menu_item_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY page ALTER COLUMN "ID" SET DEFAULT nextval('page_seq'::regclass);


SET search_path = core, pg_catalog;

--
-- Data for Name: admin; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY admin ("ID", "Name", "Identified", "Sort", "Get", "Post", "Visible", "Module_ID", "Window", "Allow_All") FROM stdin;
2	Сведения о системе	sys	2	t	f	t	1	f	f
6	Вывод массива SERVER	server	4	t	f	f	1	t	f
5	Вывод функции phpinfo	phpinfo	5	t	f	f	1	t	f
8	Добавить группу	group_add	8	t	t	f	2	f	f
9	Редактировать группу	group_edit	9	t	t	f	2	f	f
10	Удалить группу	group_delete	10	f	t	f	2	f	f
11	Добавить пользователя	user_add	11	t	t	f	2	f	f
12	Редактировать пользователя	user_edit	12	t	t	f	2	f	f
13	Удалить пользователя	user_delete	13	f	t	f	2	f	f
16	Сменить пароль пользователя	user_passwd	14	t	t	f	2	f	f
14	Привилегии	priv	15	t	t	t	2	f	f
18	Закачать	upload	18	f	t	f	3	f	f
19	Редактировать файл	put	19	t	t	f	3	f	f
20	Удалить	rm	20	f	t	f	3	f	f
21	Переименовать	rename	21	f	t	f	3	f	f
24	Добавить	add	23	t	t	f	3	f	f
25	Создать папку	mkdir	24	f	t	f	3	f	f
23	Скачать	download	22	t	t	f	3	t	f
27	Добавить	add	27	t	t	f	9	f	f
29	Удалить	delete	29	f	t	f	9	f	f
31	Главная страница	home	31	t	t	f	9	f	f
32	Страница 404	404	32	t	t	f	9	f	f
33	Страница 403	403	33	t	t	f	9	f	f
26	Управление	list	26	t	f	t	9	f	f
17	Управление	ls	17	t	f	t	3	f	f
7	Управление	user	7	t	f	t	2	f	f
30	Другие страницы	other	30	t	f	t	9	f	f
28	Редактировать	edit	28	t	t	f	9	f	f
44	Удалить robots.txt	robots_delete	44	f	t	f	10	f	f
35	Адреса для продвижения	url	35	t	f	t	10	f	f
45	Настройки	settings	45	t	t	t	10	f	f
1	Модули	module	1	t	f	t	1	f	t
15	Сменить пароль	passwd	16	t	t	t	2	f	t
47	Добавить меню	menu_add	47	t	t	f	11	f	f
48	Редактировать меню	menu_edit	48	t	t	f	11	f	f
36	Добавить адрес	url_add	36	t	t	f	10	f	f
38	Удалить адрес	url_delete	38	f	t	f	10	f	f
37	Редактировать адрес	url_edit	37	t	t	f	10	f	f
39	Переадресация	redirect	39	t	f	t	10	f	f
40	Добавить переадресацию	redirect_add	40	t	t	f	10	f	f
41	Редактировать переадресацию	redirect_edit	41	t	t	f	10	f	f
42	Удалить переадресацию	redirect_delete	42	f	t	f	10	f	f
43	Править robots.txt	robots	43	t	t	t	10	f	f
51	Добавить пункт меню	item_add	51	t	t	f	11	f	f
52	Редактировать пункт меню	item_edit	52	t	t	f	11	f	f
53	Удалить пункт меню	item_delete	53	f	t	f	11	f	f
46	Правка меню	menu	46	t	f	t	11	f	f
55	Пункт меню вниз	item_sort_down	55	f	t	f	11	f	f
54	Пункт меню вверх	item_sort_up	54	f	t	f	11	f	f
50	Управление	item	50	t	f	t	11	f	f
49	Удалить меню	menu_delete	49	f	t	f	11	f	f
\.


--
-- Name: admin_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('admin_seq', 55, true);


--
-- Data for Name: exe; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY exe ("ID", "Name", "Identified", "Module_ID", "Priority", "Active") FROM stdin;
10	Описание	content	9	10	t
11	Главная страница	home	9	11	t
12	Страница 404	404	9	12	t
13	Страница 403	403	9	13	t
\.


--
-- Name: exe_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('exe_seq', 13, true);


--
-- Data for Name: html; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY html ("ID", "Name", "Identified") FROM stdin;
5	По умолчанию	default
6	Главная	home
\.


--
-- Data for Name: html_inc; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY html_inc ("Html_ID", "Inc_ID") FROM stdin;
6	6
5	6
\.


--
-- Name: html_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('html_seq', 6, true);


--
-- Data for Name: inc; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY inc ("ID", "Name", "Identified", "Module_ID", "Active") FROM stdin;
6	Верхнее	top	11	t
\.


--
-- Name: inc_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('inc_seq', 6, true);


--
-- Data for Name: module; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY module ("ID", "Name", "Identified", "Desc", "Version", "Type", "Active", "Pages_Isset") FROM stdin;
1	Сервис	zn_service	Сведения о модулях.\r\nСведения о системе.\r\nСведения о PHP.\r\nСведения о PostgreSQL.	1.0	smod	t	f
2	Пользователи	zn_user	Управление пользователями	1.0	smod	t	f
9	Страницы	page	Странице на сайте	1.0	mod	t	t
3	Проводник	zn_explorer	Управление статическими файлами	1.0	smod	t	f
10	Поисковая оптимизация	zn_seo	Управление тегами title, meta. Правка файла robots.txt. Переадресация.	1.0	smod	t	f
11	Меню	menu	Многоуровневое меню	1.0	mod	t	f
\.


--
-- Name: module_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('module_seq', 11, true);


--
-- Data for Name: param; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY param ("ID", "Name", "Identified", "Type", "Value", "Module_ID") FROM stdin;
19	Шаблон по умолчанию	html_default	string	default	\N
21	Загловок страницы 404	404_title	string	Страница не найдена	9
22	Заголовок страницы 403	403_title	string	Доступ запрещён	9
6	Страница 403. Exe	403_exe	string	403	\N
5	Страница 403. Модуль	403_module	string	page	\N
18	Страница 403. Заголовок	403_title	string	Доступ запрещён	\N
3	Страница 404. Модуль	404_module	string	page	\N
4	Страница 404. Exe	404_exe	string	404	\N
1	Модуль по умолчанию	default_module	string	page	\N
2	Exe по умолчанию	default_exe	string	home	\N
14	Страница 404. Заголовок	404_title	string	Страница не найдена	\N
20	Заголовок главной страницы	home_title	string	Добро пожаловать!	9
23	Тег title по умолчанию	title_default	string		10
24	Тег meta keywords по умолчанию	keywords_default	string		10
25	Тег meta description по умолчанию	description_default	string		10
10	Заголовок по умолчанию	default_title	string	Добро пожаловать	\N
\.


--
-- Name: param_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('param_seq', 25, true);


--
-- Data for Name: phpclass; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY phpclass ("ID", "Name", "Identified", "Module_ID") FROM stdin;
1	Страницы	Page	9
2	Меню	Menu	11
3	Пункты меню	Menu_Item	11
\.


--
-- Name: phpclass_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('phpclass_seq', 3, true);


--
-- Data for Name: proc; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY proc ("ID", "Name", "Identified", "Type", "Module_ID", "Active") FROM stdin;
4	Теги title, meta	tag	start	10	t
5	Переадресация	redirect	start	10	t
6	Замена комментариев	comment	end	10	t
\.


--
-- Name: proc_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('proc_seq', 6, true);


--
-- Data for Name: seo_redirect; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY seo_redirect ("ID", "From", "To") FROM stdin;
\.


--
-- Name: seo_redirect_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('seo_redirect_seq', 1, false);


--
-- Data for Name: seo_url; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY seo_url ("ID", "Url", "Title", "Keywords", "Description") FROM stdin;
\.


--
-- Name: seo_url_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('seo_url_seq', 1, false);


--
-- Data for Name: text; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY text ("ID", "Name", "Identified", "Value", "Module_ID") FROM stdin;
5	Страница 403. Текст	403_content	<p>Доступ запрещён.</p>	\N
1	Содержание по умолчанию	default_content	<p>Добро пожаловать</p>	\N
8	Содержимое страницы 404	404_content	<p>Запрашиваемой страницы не существует. </p>	9
9	Содежимое страницы 403	403_content	<p>Доступ к запрашиваемой странице запрещён.</p>	9
7	Содержание главной страницы	home_content	<p>Добро пожаловать на наш сайт.</p>	9
3	Страница 404. Текст	404_content	<p>Страница не найдена.</p>	\N
\.


--
-- Name: text_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('text_seq', 9, true);


--
-- Data for Name: user; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY "user" ("ID", "Name", "Email", "Password", "Group_ID", "Active") FROM stdin;
2	Два	dva@znarus.znt	a91269733f5b1d55974d537e9147e775	1	t
1	Один	odin@znarus.znt	a91269733f5b1d55974d537e9147e775	1	t
\.


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_group ("ID", "Name") FROM stdin;
1	Операторы
13	Тестовая
\.


--
-- Name: user_group_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('user_group_seq', 13, true);


--
-- Data for Name: user_priv; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_priv ("Admin_ID", "Group_ID") FROM stdin;
26	1
27	1
28	1
29	1
30	1
31	1
32	1
33	1
\.


--
-- Name: user_seq; Type: SEQUENCE SET; Schema: core; Owner: znarus
--

SELECT pg_catalog.setval('user_seq', 4, true);


--
-- Data for Name: user_session; Type: TABLE DATA; Schema: core; Owner: znarus
--

COPY user_session ("ID", "Date", "IP", "Browser", "User_ID") FROM stdin;
3c009882228cd335e33400d04c810a56	2014-01-23 17:50:16.84717	127.0.0.1	Opera/9.80 (X11; Linux i686) Presto/2.12.388 Version/12.16	1
4e30e77843dc1b77fbdc87c804fa4294	2014-01-27 18:49:23.416628	127.0.0.1	Opera/9.80 (X11; Linux i686) Presto/2.12.388 Version/12.16	\N
\.


SET search_path = public, pg_catalog;

--
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY menu ("ID", "Name") FROM stdin;
8	Верхнее
\.


--
-- Data for Name: menu_item; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY menu_item ("ID", "Name", "Url", "Parent", "Menu_ID", "Sort") FROM stdin;
35	Доставка	/доставка	\N	8	36
34	Контакты	/контакты	\N	8	35
33	Услуги	/услуги	\N	8	34
36	Главная	/	\N	8	32
32	О нас	/о-нас	\N	8	33
\.


--
-- Name: menu_item_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('menu_item_seq', 36, true);


--
-- Name: menu_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('menu_seq', 8, true);


--
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: znarus
--

COPY page ("ID", "Name", "Url", "Content", "Parent", "Html_Identified") FROM stdin;
7	Контакты	контакты	<p>Телефон - +7 000 000 00 00</p>\r\n<p>Почтовый ящик - mail@mail</p>	\N	
8	Доставка	доставка	<p>Доставка осуществляется почтой</p>	\N	
6	Услуги	услуги	<p>Мы предоставляем комплекс много</p>	\N	
5	О нас	о-нас	<p>Немного о нас</p>	\N	
\.


--
-- Name: page_seq; Type: SEQUENCE SET; Schema: public; Owner: znarus
--

SELECT pg_catalog.setval('page_seq', 8, true);


SET search_path = core, pg_catalog;

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
-- Name: html_inc_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY html_inc
    ADD CONSTRAINT "html_inc_PK" PRIMARY KEY ("Html_ID", "Inc_ID");


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
-- Name: proc_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_PK" PRIMARY KEY ("ID");


--
-- Name: proc_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: proc_UN_Name; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: seo_redirect_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY seo_redirect
    ADD CONSTRAINT "seo_redirect_PK" PRIMARY KEY ("ID");


--
-- Name: seo_redirect_UN_From; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY seo_redirect
    ADD CONSTRAINT "seo_redirect_UN_From" UNIQUE ("From");


--
-- Name: seo_url_PK; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY seo_url
    ADD CONSTRAINT "seo_url_PK" PRIMARY KEY ("ID");


--
-- Name: seo_url_UN_Url; Type: CONSTRAINT; Schema: core; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY seo_url
    ADD CONSTRAINT "seo_url_UN_Url" UNIQUE ("Url");


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


SET search_path = public, pg_catalog;

--
-- Name: menu_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT "menu_PK" PRIMARY KEY ("ID");


--
-- Name: menu_UN_Name; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT "menu_UN_Name" UNIQUE ("Name");


--
-- Name: menu_item_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_PK" PRIMARY KEY ("ID");


--
-- Name: menu_item_UN_Name; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_UN_Name" UNIQUE ("Name", "Parent", "Menu_ID");


--
-- Name: page_PK; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_PK" PRIMARY KEY ("ID");


--
-- Name: page_UN_Name; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_UN_Name" UNIQUE ("Name", "Parent");


--
-- Name: page_UN_Url; Type: CONSTRAINT; Schema: public; Owner: znarus; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_UN_Url" UNIQUE ("Url", "Parent");


SET search_path = core, pg_catalog;

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
-- Name: html_inc_FK_Html_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY html_inc
    ADD CONSTRAINT "html_inc_FK_Html_ID" FOREIGN KEY ("Html_ID") REFERENCES html("ID");


--
-- Name: html_inc_FK_Inc_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY html_inc
    ADD CONSTRAINT "html_inc_FK_Inc_ID" FOREIGN KEY ("Inc_ID") REFERENCES inc("ID");


--
-- Name: inc_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


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
-- Name: proc_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: znarus
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


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


SET search_path = public, pg_catalog;

--
-- Name: menu_FK_Menu_ID; Type: FK CONSTRAINT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_FK_Menu_ID" FOREIGN KEY ("Menu_ID") REFERENCES menu("ID");


--
-- Name: menu_item_FK_Parent; Type: FK CONSTRAINT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_FK_Parent" FOREIGN KEY ("Parent") REFERENCES menu_item("ID");


--
-- Name: page_FK_Parent; Type: FK CONSTRAINT; Schema: public; Owner: znarus
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_FK_Parent" FOREIGN KEY ("Parent") REFERENCES page("ID");


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

