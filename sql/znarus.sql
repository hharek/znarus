--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

ALTER TABLE ONLY public.page DROP CONSTRAINT "page_FK_Parent";
ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_item_FK_Parent";
ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_FK_Menu_ID";
SET search_path = core, pg_catalog;

ALTER TABLE ONLY core.user_session DROP CONSTRAINT "user_session_FK_User_ID";
ALTER TABLE ONLY core.user_priv DROP CONSTRAINT "user_priv_FK_Group_ID";
ALTER TABLE ONLY core.user_priv DROP CONSTRAINT "user_priv_FK_Admin_ID";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_FK_Group_ID";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_FK_Module_ID";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_FK_Module_ID";
ALTER TABLE ONLY core.phpclass DROP CONSTRAINT "phpclass_FK_Module_ID";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_FK_Module_ID";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_FK_Module_ID";
ALTER TABLE ONLY core.html_inc DROP CONSTRAINT "html_inc_FK_Inc_ID";
ALTER TABLE ONLY core.html_inc DROP CONSTRAINT "html_inc_FK_Html_ID";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_FK_Module_ID";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_FK_Module_ID";
SET search_path = public, pg_catalog;

ALTER TABLE ONLY public.slider_a DROP CONSTRAINT "slider_a_PK";
ALTER TABLE ONLY public.page DROP CONSTRAINT "page_UN_Url";
ALTER TABLE ONLY public.page DROP CONSTRAINT "page_UN_Name";
ALTER TABLE ONLY public.page DROP CONSTRAINT "page_PK";
ALTER TABLE ONLY public.news DROP CONSTRAINT "news_UN_Url";
ALTER TABLE ONLY public.news DROP CONSTRAINT "news_UN_Title";
ALTER TABLE ONLY public.news DROP CONSTRAINT "news_PK";
ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_item_UN_Name";
ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_item_PK";
ALTER TABLE ONLY public.menu DROP CONSTRAINT "menu_UN_Name";
ALTER TABLE ONLY public.menu DROP CONSTRAINT "menu_PK";
ALTER TABLE ONLY public.faq DROP CONSTRAINT "faq_PK";
ALTER TABLE ONLY public.articles DROP CONSTRAINT "articles_UN_Url";
ALTER TABLE ONLY public.articles DROP CONSTRAINT "articles_UN_Title";
ALTER TABLE ONLY public.articles DROP CONSTRAINT "articles_PK";
SET search_path = core, pg_catalog;

ALTER TABLE ONLY core.user_session DROP CONSTRAINT "user_session_PK";
ALTER TABLE ONLY core.user_priv DROP CONSTRAINT "user_priv_PK";
ALTER TABLE ONLY core.user_group DROP CONSTRAINT "user_group_UN_Name";
ALTER TABLE ONLY core.user_group DROP CONSTRAINT "user_group_PK";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_UN_Name";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_UN_Email";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_PK";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_UN_Name";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_UN_Identified";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_PK";
ALTER TABLE ONLY core.tags DROP CONSTRAINT "tags_UN_Name";
ALTER TABLE ONLY core.tags DROP CONSTRAINT "tags_PK";
ALTER TABLE ONLY core.seo_url DROP CONSTRAINT "seo_url_UN_Url";
ALTER TABLE ONLY core.seo_url DROP CONSTRAINT "seo_url_PK";
ALTER TABLE ONLY core.seo_redirect DROP CONSTRAINT "seo_redirect_UN_From";
ALTER TABLE ONLY core.seo_redirect DROP CONSTRAINT "seo_redirect_PK";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_UN_Name";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_UN_Identified";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_PK";
ALTER TABLE ONLY core.phpclass DROP CONSTRAINT "phpclass_UN_Name";
ALTER TABLE ONLY core.phpclass DROP CONSTRAINT "phpclass_UN_Identified";
ALTER TABLE ONLY core.phpclass DROP CONSTRAINT "phpclass_PK";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_UN_Name";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_UN_Identified";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_PK";
ALTER TABLE ONLY core.module DROP CONSTRAINT "module_UN_Name";
ALTER TABLE ONLY core.module DROP CONSTRAINT "module_UN_Identified";
ALTER TABLE ONLY core.module DROP CONSTRAINT "module_PK";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_UN_Name";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_UN_Identified";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_PK";
ALTER TABLE ONLY core.html_inc DROP CONSTRAINT "html_inc_PK";
ALTER TABLE ONLY core.html DROP CONSTRAINT "html_UN_Name";
ALTER TABLE ONLY core.html DROP CONSTRAINT "html_UN_Identified";
ALTER TABLE ONLY core.html DROP CONSTRAINT "html_PK";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_UN_Name";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_UN_Identified";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_PK";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_UN_Name";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_UN_Identified";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_PK";
SET search_path = public, pg_catalog;

SET search_path = core, pg_catalog;

SET search_path = public, pg_catalog;

ALTER TABLE public.slider_a ALTER COLUMN "Sort" DROP DEFAULT;
ALTER TABLE public.slider_a ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE public.page ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE public.news ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE public.menu_item ALTER COLUMN "Sort" DROP DEFAULT;
ALTER TABLE public.menu_item ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE public.menu ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE public.faq ALTER COLUMN "Sort" DROP DEFAULT;
ALTER TABLE public.faq ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE public.articles ALTER COLUMN "ID" DROP DEFAULT;
SET search_path = core, pg_catalog;

ALTER TABLE core.user_group ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core."user" ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.text ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.tags ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.seo_url ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.seo_redirect ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.proc ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.phpclass ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.param ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.module ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.inc ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.html ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.exe ALTER COLUMN "Priority" DROP DEFAULT;
ALTER TABLE core.exe ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.admin ALTER COLUMN "Sort" DROP DEFAULT;
ALTER TABLE core.admin ALTER COLUMN "ID" DROP DEFAULT;
SET search_path = public, pg_catalog;

DROP SEQUENCE public.slider_a_seq;
DROP TABLE public.slider_a;
DROP SEQUENCE public.page_seq;
DROP TABLE public.page;
DROP SEQUENCE public.news_seq;
DROP TABLE public.news;
DROP SEQUENCE public.menu_seq;
DROP SEQUENCE public.menu_item_seq;
DROP TABLE public.menu_item;
DROP TABLE public.menu;
DROP SEQUENCE public.faq_seq;
DROP TABLE public.faq;
DROP SEQUENCE public.articles_seq;
DROP TABLE public.articles;
SET search_path = core, pg_catalog;

DROP TABLE core.user_session;
DROP SEQUENCE core.user_seq;
DROP TABLE core.user_priv;
DROP SEQUENCE core.user_group_seq;
DROP TABLE core.user_group;
DROP TABLE core."user";
DROP SEQUENCE core.text_seq;
DROP TABLE core.text;
DROP SEQUENCE core.tags_seq;
DROP TABLE core.tags;
DROP SEQUENCE core.seo_url_seq;
DROP TABLE core.seo_url;
DROP SEQUENCE core.seo_redirect_seq;
DROP TABLE core.seo_redirect;
DROP SEQUENCE core.proc_seq;
DROP TABLE core.proc;
DROP SEQUENCE core.phpclass_seq;
DROP TABLE core.phpclass;
DROP SEQUENCE core.param_seq;
DROP TABLE core.param;
DROP SEQUENCE core.module_seq;
DROP TABLE core.module;
DROP SEQUENCE core.inc_seq;
DROP TABLE core.inc;
DROP SEQUENCE core.html_seq;
DROP TABLE core.html_inc;
DROP TABLE core.html;
DROP SEQUENCE core.exe_seq;
DROP TABLE core.exe;
DROP SEQUENCE core.admin_seq;
DROP TABLE core.admin;
DROP TYPE core.proc_type;
DROP TYPE core.param_type;
DROP TYPE core.module_type;
DROP EXTENSION plpgsql;
DROP SCHEMA public;
DROP SCHEMA core;
--
-- Name: core; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA core;


--
-- Name: SCHEMA core; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA core IS 'Ядро';


--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = core, pg_catalog;

--
-- Name: module_type; Type: TYPE; Schema: core; Owner: -
--

CREATE TYPE module_type AS ENUM (
    'mod',
    'smod'
);


--
-- Name: param_type; Type: TYPE; Schema: core; Owner: -
--

CREATE TYPE param_type AS ENUM (
    'string',
    'int',
    'bool'
);


--
-- Name: proc_type; Type: TYPE; Schema: core; Owner: -
--

CREATE TYPE proc_type AS ENUM (
    'start',
    'end'
);


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: admin; Type: TABLE; Schema: core; Owner: -; Tablespace: 
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


--
-- Name: TABLE admin; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE admin IS 'Админки';


--
-- Name: COLUMN admin."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."ID" IS 'Порядковый номер';


--
-- Name: COLUMN admin."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Name" IS 'Наименование';


--
-- Name: COLUMN admin."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Identified" IS 'Идентификатор';


--
-- Name: COLUMN admin."Sort"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Sort" IS 'Сортировка';


--
-- Name: COLUMN admin."Get"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Get" IS 'Обработка GET данных';


--
-- Name: COLUMN admin."Post"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Post" IS 'Обработка POST данных';


--
-- Name: COLUMN admin."Visible"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Visible" IS 'Видимость';


--
-- Name: COLUMN admin."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN admin."Window"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Window" IS 'В новом окне';


--
-- Name: COLUMN admin."Allow_All"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN admin."Allow_All" IS 'Разрешить всем';


--
-- Name: admin_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE admin_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: admin_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE admin_seq OWNED BY admin."ID";


--
-- Name: exe; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE exe (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Module_ID" integer NOT NULL,
    "Priority" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL
);


--
-- Name: TABLE exe; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE exe IS 'Исполнители';


--
-- Name: COLUMN exe."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."ID" IS 'Порядковый номер';


--
-- Name: COLUMN exe."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Name" IS 'Наименование';


--
-- Name: COLUMN exe."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Identified" IS 'Идентификатор';


--
-- Name: COLUMN exe."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN exe."Priority"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Priority" IS 'Порядок исполнения';


--
-- Name: COLUMN exe."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Active" IS 'Активность';


--
-- Name: exe_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE exe_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: exe_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE exe_seq OWNED BY exe."ID";


--
-- Name: html; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE html (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL
);


--
-- Name: TABLE html; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE html IS 'Основной шаблон';


--
-- Name: COLUMN html."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN html."ID" IS 'Порядковый номер';


--
-- Name: COLUMN html."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN html."Name" IS 'Наименование';


--
-- Name: COLUMN html."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN html."Identified" IS 'Идентификатор';


--
-- Name: html_inc; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE html_inc (
    "Html_ID" integer NOT NULL,
    "Inc_ID" integer NOT NULL
);


--
-- Name: TABLE html_inc; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE html_inc IS 'Составные части шаблона';


--
-- Name: COLUMN html_inc."Html_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN html_inc."Html_ID" IS 'Привязка к шаблону';


--
-- Name: COLUMN html_inc."Inc_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN html_inc."Inc_ID" IS 'Привязка к инку';


--
-- Name: html_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE html_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: html_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE html_seq OWNED BY html."ID";


--
-- Name: inc; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE inc (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Module_ID" integer NOT NULL,
    "Active" boolean DEFAULT true NOT NULL
);


--
-- Name: TABLE inc; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE inc IS 'Инки';


--
-- Name: COLUMN inc."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN inc."ID" IS 'Порядковый номер';


--
-- Name: COLUMN inc."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN inc."Name" IS 'Наименование';


--
-- Name: COLUMN inc."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN inc."Identified" IS 'Идентификатор';


--
-- Name: COLUMN inc."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN inc."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN inc."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN inc."Active" IS 'Активность';


--
-- Name: inc_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE inc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inc_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE inc_seq OWNED BY inc."ID";


--
-- Name: module; Type: TABLE; Schema: core; Owner: -; Tablespace: 
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


--
-- Name: TABLE module; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE module IS 'Модуль';


--
-- Name: COLUMN module."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."ID" IS 'Порядковый номер';


--
-- Name: COLUMN module."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Name" IS 'Наименование';


--
-- Name: COLUMN module."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Identified" IS 'Идентификатор';


--
-- Name: COLUMN module."Desc"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Desc" IS 'Описание';


--
-- Name: COLUMN module."Version"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Version" IS 'Версия';


--
-- Name: COLUMN module."Type"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Type" IS 'Тип - обычный или системный';


--
-- Name: COLUMN module."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Active" IS 'Активность';


--
-- Name: COLUMN module."Pages_Isset"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Pages_Isset" IS 'Наличие страниц';


--
-- Name: module_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE module_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: module_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE module_seq OWNED BY module."ID";


--
-- Name: param; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE param (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Type" param_type DEFAULT 'string'::param_type NOT NULL,
    "Value" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Module_ID" integer
);


--
-- Name: TABLE param; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE param IS 'Параметры';


--
-- Name: COLUMN param."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN param."ID" IS 'Порядковый номер';


--
-- Name: COLUMN param."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN param."Name" IS 'Наименование';


--
-- Name: COLUMN param."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN param."Identified" IS 'Идентификатор';


--
-- Name: COLUMN param."Type"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN param."Type" IS 'Тип';


--
-- Name: COLUMN param."Value"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN param."Value" IS 'Значение';


--
-- Name: COLUMN param."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN param."Module_ID" IS 'Привязка к модулю';


--
-- Name: param_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE param_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: param_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE param_seq OWNED BY param."ID";


--
-- Name: phpclass; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE phpclass (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Module_ID" integer NOT NULL
);


--
-- Name: TABLE phpclass; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE phpclass IS 'PHP класс';


--
-- Name: COLUMN phpclass."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN phpclass."ID" IS 'Порядковый номер';


--
-- Name: COLUMN phpclass."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN phpclass."Name" IS 'Наименование';


--
-- Name: COLUMN phpclass."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN phpclass."Identified" IS 'Идентификатор';


--
-- Name: COLUMN phpclass."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN phpclass."Module_ID" IS 'Привязка к модулю';


--
-- Name: phpclass_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE phpclass_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: phpclass_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE phpclass_seq OWNED BY phpclass."ID";


--
-- Name: proc; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE proc (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Type" proc_type,
    "Module_ID" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL
);


--
-- Name: TABLE proc; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE proc IS 'Процедуры';


--
-- Name: COLUMN proc."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."ID" IS 'Порядковый номер';


--
-- Name: COLUMN proc."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Name" IS 'Наименование';


--
-- Name: COLUMN proc."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Identified" IS 'Идентификатор';


--
-- Name: COLUMN proc."Type"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Type" IS 'Тип исполнения в начале или в конце';


--
-- Name: COLUMN proc."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN proc."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Active" IS 'Активность';


--
-- Name: proc_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE proc_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: proc_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE proc_seq OWNED BY proc."ID";


--
-- Name: seo_redirect; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE seo_redirect (
    "ID" integer NOT NULL,
    "From" character varying(127) NOT NULL,
    "To" character varying(127) NOT NULL
);


--
-- Name: TABLE seo_redirect; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE seo_redirect IS 'Адреса для переадресации';


--
-- Name: COLUMN seo_redirect."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_redirect."ID" IS 'Порядковый номер';


--
-- Name: COLUMN seo_redirect."From"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_redirect."From" IS 'Источник';


--
-- Name: COLUMN seo_redirect."To"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_redirect."To" IS 'Назначение';


--
-- Name: seo_redirect_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE seo_redirect_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seo_redirect_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE seo_redirect_seq OWNED BY seo_redirect."ID";


--
-- Name: seo_url; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE seo_url (
    "ID" integer NOT NULL,
    "Url" character varying(127) NOT NULL,
    "Title" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Keywords" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Description" character varying(255) DEFAULT ''::character varying NOT NULL
);


--
-- Name: TABLE seo_url; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE seo_url IS 'Адреса для продвижения';


--
-- Name: COLUMN seo_url."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_url."ID" IS 'Порядковый номер';


--
-- Name: COLUMN seo_url."Url"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_url."Url" IS 'Адрес';


--
-- Name: COLUMN seo_url."Title"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_url."Title" IS 'Тег title';


--
-- Name: COLUMN seo_url."Keywords"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_url."Keywords" IS 'Тег meta keywords';


--
-- Name: COLUMN seo_url."Description"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_url."Description" IS 'Тег meta description';


--
-- Name: seo_url_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE seo_url_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: seo_url_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE seo_url_seq OWNED BY seo_url."ID";


--
-- Name: tags; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE tags (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Count" integer DEFAULT 1 NOT NULL
);


--
-- Name: TABLE tags; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE tags IS 'Теги';


--
-- Name: COLUMN tags."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN tags."ID" IS 'Порядковый номер';


--
-- Name: COLUMN tags."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN tags."Name" IS 'Наименование';


--
-- Name: COLUMN tags."Count"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN tags."Count" IS 'Количество';


--
-- Name: tags_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE tags_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tags_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE tags_seq OWNED BY tags."ID";


--
-- Name: text; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE text (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Value" pg_catalog.text DEFAULT ''::pg_catalog.text NOT NULL,
    "Module_ID" integer
);


--
-- Name: TABLE text; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE text IS 'Тексты';


--
-- Name: COLUMN text."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN text."ID" IS 'Порядковый номер';


--
-- Name: COLUMN text."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN text."Name" IS 'Наименование';


--
-- Name: COLUMN text."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN text."Identified" IS 'Идентификатор';


--
-- Name: COLUMN text."Value"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN text."Value" IS 'Значение';


--
-- Name: COLUMN text."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN text."Module_ID" IS 'Привязка к модулю';


--
-- Name: text_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE text_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: text_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE text_seq OWNED BY text."ID";


--
-- Name: user; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE "user" (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Email" character varying(127) NOT NULL,
    "Password" character(32) NOT NULL,
    "Group_ID" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL
);


--
-- Name: TABLE "user"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE "user" IS 'Пользователи';


--
-- Name: COLUMN "user"."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."ID" IS 'Порядковый номер';


--
-- Name: COLUMN "user"."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Name" IS 'Наименование';


--
-- Name: COLUMN "user"."Email"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Email" IS 'Почтовый ящик';


--
-- Name: COLUMN "user"."Password"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Password" IS 'Хэш пароля';


--
-- Name: COLUMN "user"."Group_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Group_ID" IS 'Привязка к группе';


--
-- Name: COLUMN "user"."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Active" IS 'Активность';


--
-- Name: user_group; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE user_group (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL
);


--
-- Name: TABLE user_group; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE user_group IS 'Группа пользователей';


--
-- Name: COLUMN user_group."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_group."ID" IS 'Порядковый номер';


--
-- Name: COLUMN user_group."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_group."Name" IS 'Наименование';


--
-- Name: user_group_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE user_group_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_group_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE user_group_seq OWNED BY user_group."ID";


--
-- Name: user_priv; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE user_priv (
    "Admin_ID" integer NOT NULL,
    "Group_ID" integer NOT NULL
);


--
-- Name: TABLE user_priv; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE user_priv IS 'Привилегии пользователей';


--
-- Name: COLUMN user_priv."Admin_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_priv."Admin_ID" IS 'Привязка к админке';


--
-- Name: COLUMN user_priv."Group_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_priv."Group_ID" IS 'Привязка к группе';


--
-- Name: user_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE user_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE user_seq OWNED BY "user"."ID";


--
-- Name: user_session; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE user_session (
    "ID" character(32) NOT NULL,
    "Date" timestamp without time zone DEFAULT now() NOT NULL,
    "IP" character varying(15) NOT NULL,
    "Browser" character varying(255) NOT NULL,
    "User_ID" integer
);


--
-- Name: TABLE user_session; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE user_session IS 'Сессии пользователей';


--
-- Name: COLUMN user_session."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_session."ID" IS 'Идентификатор сессии';


--
-- Name: COLUMN user_session."Date"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_session."Date" IS 'Дата окончания действия сессии';


--
-- Name: COLUMN user_session."IP"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_session."IP" IS 'IP адрес создателя сессии';


--
-- Name: COLUMN user_session."Browser"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_session."Browser" IS 'Строка USER_AGENT браузера создателя сессии';


--
-- Name: COLUMN user_session."User_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_session."User_ID" IS 'Привязка к пользователю, если NULL то root';


SET search_path = public, pg_catalog;

--
-- Name: articles; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE articles (
    "ID" integer NOT NULL,
    "Date" date NOT NULL,
    "Title" character varying(255) NOT NULL,
    "Url" character varying(127) NOT NULL,
    "Anons" text,
    "Content" text,
    "Tags" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Last_Modified" timestamp without time zone DEFAULT now() NOT NULL
);


--
-- Name: TABLE articles; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE articles IS 'Статьи';


--
-- Name: COLUMN articles."ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."ID" IS 'Порядковый номер';


--
-- Name: COLUMN articles."Date"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."Date" IS 'Дата';


--
-- Name: COLUMN articles."Title"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."Title" IS 'Заголовок';


--
-- Name: COLUMN articles."Url"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."Url" IS 'Урл';


--
-- Name: COLUMN articles."Anons"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."Anons" IS 'Анонс';


--
-- Name: COLUMN articles."Content"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."Content" IS 'Содержимое';


--
-- Name: COLUMN articles."Tags"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."Tags" IS 'Теги';


--
-- Name: COLUMN articles."Last_Modified"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN articles."Last_Modified" IS 'Дата последнего изменения';


--
-- Name: articles_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE articles_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: articles_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE articles_seq OWNED BY articles."ID";


--
-- Name: faq; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE faq (
    "ID" integer NOT NULL,
    "Question" text NOT NULL,
    "Answer" text NOT NULL,
    "Sort" integer NOT NULL
);


--
-- Name: TABLE faq; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE faq IS 'Вопросы и ответы';


--
-- Name: COLUMN faq."ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN faq."ID" IS 'Порядковый номер';


--
-- Name: COLUMN faq."Question"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN faq."Question" IS 'Вопрос';


--
-- Name: COLUMN faq."Answer"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN faq."Answer" IS 'Ответ';


--
-- Name: COLUMN faq."Sort"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN faq."Sort" IS 'Сортировка';


--
-- Name: faq_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE faq_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: faq_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE faq_seq OWNED BY faq."ID";


--
-- Name: menu; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE menu (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL
);


--
-- Name: TABLE menu; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE menu IS 'Меню';


--
-- Name: COLUMN menu."ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu."ID" IS 'Порядковый номер';


--
-- Name: COLUMN menu."Name"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu."Name" IS 'Наименование';


--
-- Name: menu_item; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE menu_item (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Url" character varying(127) NOT NULL,
    "Parent" integer,
    "Menu_ID" integer NOT NULL,
    "Sort" integer NOT NULL
);


--
-- Name: TABLE menu_item; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE menu_item IS 'Пункты меню';


--
-- Name: COLUMN menu_item."ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."ID" IS 'Порядковый номер';


--
-- Name: COLUMN menu_item."Name"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Name" IS 'Наименование';


--
-- Name: COLUMN menu_item."Url"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Url" IS 'Урл';


--
-- Name: COLUMN menu_item."Parent"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Parent" IS 'Корень';


--
-- Name: COLUMN menu_item."Menu_ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Menu_ID" IS 'Привязка к меню';


--
-- Name: COLUMN menu_item."Sort"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Sort" IS 'Сортировка';


--
-- Name: menu_item_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE menu_item_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: menu_item_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE menu_item_seq OWNED BY menu_item."ID";


--
-- Name: menu_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE menu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: menu_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE menu_seq OWNED BY menu."ID";


--
-- Name: news; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE news (
    "ID" integer NOT NULL,
    "Date" date NOT NULL,
    "Title" character varying(255) NOT NULL,
    "Url" character varying(127) NOT NULL,
    "Anons" text,
    "Content" text,
    "Tags" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Last_Modified" timestamp without time zone DEFAULT now() NOT NULL
);


--
-- Name: TABLE news; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE news IS 'Новости';


--
-- Name: COLUMN news."ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."ID" IS 'Порядковый номер';


--
-- Name: COLUMN news."Date"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."Date" IS 'Дата';


--
-- Name: COLUMN news."Title"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."Title" IS 'Заголовок';


--
-- Name: COLUMN news."Url"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."Url" IS 'Урл';


--
-- Name: COLUMN news."Anons"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."Anons" IS 'Анонс';


--
-- Name: COLUMN news."Content"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."Content" IS 'Содержимое';


--
-- Name: COLUMN news."Tags"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."Tags" IS 'Теги';


--
-- Name: COLUMN news."Last_Modified"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN news."Last_Modified" IS 'Дата последнего изменения';


--
-- Name: news_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE news_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: news_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE news_seq OWNED BY news."ID";


--
-- Name: page; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE page (
    "ID" integer NOT NULL,
    "Name" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Url" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Content" text,
    "Parent" integer,
    "Html_Identified" character varying(127) DEFAULT ''::character varying NOT NULL,
    "Tags" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Last_Modified" timestamp without time zone DEFAULT now() NOT NULL
);


--
-- Name: TABLE page; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE page IS 'Страницы';


--
-- Name: COLUMN page."ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."ID" IS 'Порядковый номер';


--
-- Name: COLUMN page."Name"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."Name" IS 'Наименование';


--
-- Name: COLUMN page."Url"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."Url" IS 'Урл';


--
-- Name: COLUMN page."Content"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."Content" IS 'Содержимое';


--
-- Name: COLUMN page."Parent"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."Parent" IS 'Корень';


--
-- Name: COLUMN page."Html_Identified"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."Html_Identified" IS 'Наименование шаблона';


--
-- Name: COLUMN page."Tags"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."Tags" IS 'Теги';


--
-- Name: COLUMN page."Last_Modified"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN page."Last_Modified" IS 'Дата последнего изменения';


--
-- Name: page_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE page_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: page_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE page_seq OWNED BY page."ID";


--
-- Name: slider_a; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE slider_a (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Url" character varying(127) NOT NULL,
    "File" character varying(127) NOT NULL,
    "Sort" integer NOT NULL
);


--
-- Name: TABLE slider_a; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON TABLE slider_a IS 'Рисунки слайдера';


--
-- Name: COLUMN slider_a."ID"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN slider_a."ID" IS 'Порядковый номер';


--
-- Name: COLUMN slider_a."Name"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN slider_a."Name" IS 'Заголовок';


--
-- Name: COLUMN slider_a."Url"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN slider_a."Url" IS 'Урл';


--
-- Name: COLUMN slider_a."File"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN slider_a."File" IS 'Имя файла';


--
-- Name: COLUMN slider_a."Sort"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN slider_a."Sort" IS 'Сортировка';


--
-- Name: slider_a_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE slider_a_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: slider_a_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE slider_a_seq OWNED BY slider_a."ID";


SET search_path = core, pg_catalog;

--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY admin ALTER COLUMN "ID" SET DEFAULT nextval('admin_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY admin ALTER COLUMN "Sort" SET DEFAULT currval('admin_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY exe ALTER COLUMN "ID" SET DEFAULT nextval('exe_seq'::regclass);


--
-- Name: Priority; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY exe ALTER COLUMN "Priority" SET DEFAULT currval('exe_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY html ALTER COLUMN "ID" SET DEFAULT nextval('html_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY inc ALTER COLUMN "ID" SET DEFAULT nextval('inc_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY module ALTER COLUMN "ID" SET DEFAULT nextval('module_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY param ALTER COLUMN "ID" SET DEFAULT nextval('param_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY phpclass ALTER COLUMN "ID" SET DEFAULT nextval('phpclass_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY proc ALTER COLUMN "ID" SET DEFAULT nextval('proc_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY seo_redirect ALTER COLUMN "ID" SET DEFAULT nextval('seo_redirect_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY seo_url ALTER COLUMN "ID" SET DEFAULT nextval('seo_url_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY tags ALTER COLUMN "ID" SET DEFAULT nextval('tags_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY text ALTER COLUMN "ID" SET DEFAULT nextval('text_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY "user" ALTER COLUMN "ID" SET DEFAULT nextval('user_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY user_group ALTER COLUMN "ID" SET DEFAULT nextval('user_group_seq'::regclass);


SET search_path = public, pg_catalog;

--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY articles ALTER COLUMN "ID" SET DEFAULT nextval('articles_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY faq ALTER COLUMN "ID" SET DEFAULT nextval('faq_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY faq ALTER COLUMN "Sort" SET DEFAULT currval('faq_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu ALTER COLUMN "ID" SET DEFAULT nextval('menu_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item ALTER COLUMN "ID" SET DEFAULT nextval('menu_item_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item ALTER COLUMN "Sort" SET DEFAULT currval('menu_item_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY news ALTER COLUMN "ID" SET DEFAULT nextval('news_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY page ALTER COLUMN "ID" SET DEFAULT nextval('page_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider_a ALTER COLUMN "ID" SET DEFAULT nextval('slider_a_seq'::regclass);


--
-- Name: Sort; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY slider_a ALTER COLUMN "Sort" SET DEFAULT currval('slider_a_seq'::regclass);


SET search_path = core, pg_catalog;

--
-- Data for Name: admin; Type: TABLE DATA; Schema: core; Owner: -
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
78	Правка шаблон	html_content	79	t	t	f	17	f	f
51	Добавить пункт меню	item_add	51	t	t	f	11	f	f
52	Редактировать пункт меню	item_edit	52	t	t	f	11	f	f
53	Удалить пункт меню	item_delete	53	f	t	f	11	f	f
46	Правка меню	menu	46	t	f	t	11	f	f
55	Пункт меню вниз	item_sort_down	55	f	t	f	11	f	f
54	Пункт меню вверх	item_sort_up	54	f	t	f	11	f	f
50	Управление	item	50	t	f	t	11	f	f
49	Удалить меню	menu_delete	49	f	t	f	11	f	f
56	Управление	list	56	t	f	t	12	f	f
58	Редактировать	edit	58	t	t	f	12	f	f
59	Удалить	delete	59	f	t	f	12	f	f
57	Добавить	add	57	t	t	t	12	f	f
60	Управление	list	60	t	f	t	13	f	f
61	Добавить	add	61	t	t	t	13	f	f
62	Редактировать	edit	62	t	t	f	13	f	f
63	Удалить	delete	63	f	t	f	13	f	f
64	Настройки	settings	64	t	t	t	14	f	f
65	Управление	list	65	t	f	t	15	f	f
67	Редактировать	edit	67	t	t	f	15	f	f
66	Добавить	add	66	t	t	t	15	f	f
68	Удалить	delete	68	f	t	f	15	f	f
69	Сортировка вверх	sort_up	69	f	t	f	15	f	f
70	Сортировка вниз	sort_down	70	f	t	f	15	f	f
71	Управление	list	71	t	f	t	16	f	f
72	Добавить	add	72	t	t	t	16	f	f
73	Редактировать	edit	73	t	t	f	16	f	f
74	Удалить	delete	74	f	t	f	16	f	f
75	Сортировка вверх	sort_up	75	f	t	f	16	f	f
76	Сортировка вниз	sort_down	76	f	t	f	16	f	f
43	robots.txt	robots	43	t	t	t	10	f	f
35	Управление	url	35	t	f	t	10	f	f
81	Правка exe	exe_content	82	t	t	f	17	f	f
77	Шаблоны	html	78	t	f	t	17	f	f
80	Правка inc	inc_content	81	t	t	f	17	f	f
79	Модули	module	80	t	f	t	17	f	f
82	Управление	url	77	t	f	t	17	f	f
\.


--
-- Name: admin_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('admin_seq', 118, true);


--
-- Data for Name: exe; Type: TABLE DATA; Schema: core; Owner: -
--

COPY exe ("ID", "Name", "Identified", "Module_ID", "Priority", "Active") FROM stdin;
10	Описание	content	9	10	t
11	Главная страница	home	9	11	t
12	Страница 404	404	9	12	t
13	Страница 403	403	9	13	t
14	Список	list	12	14	t
15	Содержание	content	12	15	t
16	Список	list	13	16	t
17	Содержание	content	13	17	t
20	Сообщение об удачной отправки	mess_ok	14	20	t
18	Форма и отправка	form	14	18	t
21	Список	list	15	21	t
41	Результат поиска	result	23	41	t
46	Карта сайта	sitemap	25	46	t
47	Поиск по тегам	search	26	47	t
\.


--
-- Name: exe_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('exe_seq', 47, true);


--
-- Data for Name: html; Type: TABLE DATA; Schema: core; Owner: -
--

COPY html ("ID", "Name", "Identified") FROM stdin;
5	По умолчанию	default
6	Главная	home
\.


--
-- Data for Name: html_inc; Type: TABLE DATA; Schema: core; Owner: -
--

COPY html_inc ("Html_ID", "Inc_ID") FROM stdin;
5	6
6	6
6	7
\.


--
-- Name: html_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('html_seq', 8, true);


--
-- Data for Name: inc; Type: TABLE DATA; Schema: core; Owner: -
--

COPY inc ("ID", "Name", "Identified", "Module_ID", "Active") FROM stdin;
6	Верхнее	top	11	t
7	На главной	home	16	t
16	Форма поиска	form	23	t
\.


--
-- Name: inc_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('inc_seq', 16, true);


--
-- Data for Name: module; Type: TABLE DATA; Schema: core; Owner: -
--

COPY module ("ID", "Name", "Identified", "Desc", "Version", "Type", "Active", "Pages_Isset") FROM stdin;
1	Сервис	zn_service	Сведения о модулях.\r\nСведения о системе.\r\nСведения о PHP.\r\nСведения о PostgreSQL.	1.0	smod	t	f
2	Пользователи	zn_user	Управление пользователями	1.0	smod	t	f
9	Страницы	page	Странице на сайте	1.0	mod	t	t
3	Проводник	zn_explorer	Управление статическими файлами	1.0	smod	t	f
10	Поисковая оптимизация	zn_seo	Управление тегами title, meta. Правка файла robots.txt. Переадресация.	1.0	smod	t	f
11	Меню	menu	Многоуровневое меню	1.0	mod	t	f
12	Новости	news		1.0	mod	t	t
13	Статьи	articles		1.0	mod	t	t
14	Обратная связь	feedback		1.0	mod	t	t
15	Вопрос-Ответ	faq	Часто задаваемые вопросы	1.0	mod	t	t
16	Слайдер	slider_a	Простой слайдер на главной	1.0	mod	t	f
17	HTML-код	zn_html_code	HTML-вёрстка 	1.0	smod	t	f
23	Поиск	zn_sphinx	Поиск с помощью полнотекствой поисковой системы Sphinx	1.0	smod	t	t
25	Карта сайта	zn_sitemap		1.0	smod	t	t
26	Теги	zn_tags		1.0	smod	t	t
\.


--
-- Name: module_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('module_seq', 26, true);


--
-- Data for Name: param; Type: TABLE DATA; Schema: core; Owner: -
--

COPY param ("ID", "Name", "Identified", "Type", "Value", "Module_ID") FROM stdin;
22	Заголовок страницы 403	403_title	string	Доступ запрещён	9
19	Шаблон по умолчанию	html_default	string	default	\N
6	Страница 403. Exe	403_exe	string	403	\N
5	Страница 403. Модуль	403_module	string	page	\N
18	Страница 403. Заголовок	403_title	string	Доступ запрещён	\N
3	Страница 404. Модуль	404_module	string	page	\N
4	Страница 404. Exe	404_exe	string	404	\N
1	Модуль по умолчанию	default_module	string	page	\N
2	Exe по умолчанию	default_exe	string	home	\N
14	Страница 404. Заголовок	404_title	string	Страница не найдена	\N
10	Заголовок по умолчанию	default_title	string	Добро пожаловать	\N
52	Кол-во результатов на страницу	count_to_page	int	10	23
21	Загловок страницы 404	404_title	string	Страница не найдена	9
55	Кол-во результатов на страницу	count_to_page	int	10	26
54	Дата последнего изменения	last_modified	string	2014-07-04 18:00:02	23
56	Дата последнего изменения	last_modified	string	2014-07-04 18:00:02	26
34	Файл CSS по умолчанию	css_default	string	/css/default.css	\N
53	Дата последнего изменения	last_modified	string	2014-08-09 09:22:31	15
33	Адрес получателя	email	string	admin@example.com	14
27	Имя отправителя	from_name	string	Example	14
32	Заголовок сообщения	subject	string	Сообщение с сайта example.com	14
35	Ширина рисунка	width	int	300	16
36	Высота рисунка	height	int	150	16
37	Папка для хранения рисунков	path	string	slider	16
23	Тег title для главной страницы	home_title	string		10
24	Тег meta keywords для главной страницы	home_keywords	string		10
25	Тег meta description для главной страницы	home_description	string		10
20	Заголовок главной страницы	home_title	string	Мой сайт	9
\.


--
-- Name: param_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('param_seq', 56, true);


--
-- Data for Name: phpclass; Type: TABLE DATA; Schema: core; Owner: -
--

COPY phpclass ("ID", "Name", "Identified", "Module_ID") FROM stdin;
1	Страницы	Page	9
2	Меню	Menu	11
3	Пункты меню	Menu_Item	11
4	Новости	News	12
5	Статьи	Articles	13
7	Вопрос-Ответ	Faq	15
8	Рисунки слайдера	Slider_A	16
\.


--
-- Name: phpclass_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('phpclass_seq', 26, true);


--
-- Data for Name: proc; Type: TABLE DATA; Schema: core; Owner: -
--

COPY proc ("ID", "Name", "Identified", "Type", "Module_ID", "Active") FROM stdin;
4	Теги title, meta	tag	start	10	t
5	Переадресация	redirect	start	10	t
6	Замена комментариев	comment	end	10	t
\.


--
-- Name: proc_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('proc_seq', 7, true);


--
-- Data for Name: seo_redirect; Type: TABLE DATA; Schema: core; Owner: -
--

COPY seo_redirect ("ID", "From", "To") FROM stdin;
\.


--
-- Name: seo_redirect_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('seo_redirect_seq', 1, false);


--
-- Data for Name: seo_url; Type: TABLE DATA; Schema: core; Owner: -
--

COPY seo_url ("ID", "Url", "Title", "Keywords", "Description") FROM stdin;
\.


--
-- Name: seo_url_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('seo_url_seq', 1, false);


--
-- Data for Name: tags; Type: TABLE DATA; Schema: core; Owner: -
--

COPY tags ("ID", "Name", "Count") FROM stdin;
\.


--
-- Name: tags_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('tags_seq', 1, false);


--
-- Data for Name: text; Type: TABLE DATA; Schema: core; Owner: -
--

COPY text ("ID", "Name", "Identified", "Value", "Module_ID") FROM stdin;
5	Страница 403. Текст	403_content	<p>Доступ запрещён.</p>	\N
1	Содержание по умолчанию	default_content	<p>Добро пожаловать</p>	\N
3	Страница 404. Текст	404_content	<p>Страница не найдена.</p>	\N
9	Содежимое страницы 403	403_content	<p>Доступ к запрашиваемой странице запрещён.&nbsp;</p>	9
8	Содержимое страницы 404	404_content	<p>Запрашиваемой страницы не существует. Возможно страница была перемещена или удалена с сайта. Проверьте правильность указания адреса.</p>\r\n<p>Попробуйте воспользоваться <strong><a href="/поиск">поиском</a> </strong>или <strong><a href="/карта-сайта">картой сайта</a></strong> (на карте сайта указаны все страницы, которые только могут быть на нашем сайте), чтобы найти необходимую страницу.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>	9
7	Содержание главной страницы	home_content	<p>Добро пожаловать</p>	9
\.


--
-- Name: text_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('text_seq', 12, true);


--
-- Data for Name: user; Type: TABLE DATA; Schema: core; Owner: -
--

COPY "user" ("ID", "Name", "Email", "Password", "Group_ID", "Active") FROM stdin;
\.


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_group ("ID", "Name") FROM stdin;
1	Операторы
14	Дизайнеры
\.


--
-- Name: user_group_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('user_group_seq', 14, true);


--
-- Data for Name: user_priv; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_priv ("Admin_ID", "Group_ID") FROM stdin;
82	14
77	14
78	14
79	14
80	14
81	14
65	14
65	1
66	14
66	1
67	14
67	1
68	14
68	1
69	14
69	1
70	14
70	1
50	14
50	1
51	14
51	1
52	14
52	1
53	14
53	1
54	14
54	1
55	14
55	1
56	14
56	1
57	14
57	1
58	14
58	1
59	14
59	1
64	14
64	1
35	14
35	1
36	14
36	1
37	14
37	1
38	14
38	1
39	14
39	1
40	14
40	1
41	14
41	1
42	14
42	1
43	14
43	1
44	14
44	1
45	14
45	1
17	14
18	14
19	14
20	14
21	14
23	14
24	14
25	14
71	14
71	1
72	14
72	1
73	14
73	1
74	14
74	1
75	14
75	1
76	14
76	1
60	14
60	1
61	14
61	1
62	14
62	1
63	14
63	1
26	14
26	1
27	14
27	1
28	14
28	1
29	14
29	1
30	14
30	1
31	14
31	1
32	14
32	1
33	14
33	1
\.


--
-- Name: user_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('user_seq', 7, true);


--
-- Data for Name: user_session; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_session ("ID", "Date", "IP", "Browser", "User_ID") FROM stdin;
10703b4dc4794be0c973d03bdb40ae67	2014-08-08 20:52:53.830931	127.0.0.1	Mozilla/5.0 (X11; Linux i686; rv:30.0) Gecko/20100101 Firefox/30.0	\N
\.


SET search_path = public, pg_catalog;

--
-- Data for Name: articles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY articles ("ID", "Date", "Title", "Url", "Anons", "Content", "Tags", "Last_Modified") FROM stdin;
1	2014-08-08	Статья 1	статья-1	Анонс статьи 1	<p>Описание статьи 1</p>	тег1, тег2, тег3	2014-08-09 09:21:51.333575
2	2014-08-09	Статья 2	статья-2	Анонс статьи 2	<p>Описание статьи 2</p>	тег1	2014-08-09 09:22:10.99319
\.


--
-- Name: articles_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('articles_seq', 2, true);


--
-- Data for Name: faq; Type: TABLE DATA; Schema: public; Owner: -
--

COPY faq ("ID", "Question", "Answer", "Sort") FROM stdin;
1	Вопрос 1	Ответ 1	1
2	Вопрос 2	Ответ 2	2
\.


--
-- Name: faq_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('faq_seq', 2, true);


--
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: -
--

COPY menu ("ID", "Name") FROM stdin;
1	Верхнее
\.


--
-- Data for Name: menu_item; Type: TABLE DATA; Schema: public; Owner: -
--

COPY menu_item ("ID", "Name", "Url", "Parent", "Menu_ID", "Sort") FROM stdin;
4	Новости	/новости	\N	1	5
3	Обратная связь	/обратная-связь	\N	1	4
2	ЧАВо	/вопрос-ответ	\N	1	3
5	Главная	/	\N	1	1
1	Статьи	/статьи	\N	1	2
\.


--
-- Name: menu_item_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('menu_item_seq', 5, true);


--
-- Name: menu_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('menu_seq', 1, true);


--
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: -
--

COPY news ("ID", "Date", "Title", "Url", "Anons", "Content", "Tags", "Last_Modified") FROM stdin;
1	2014-08-08	Новость 1	новость-1	Анонс новости 1	<p>Описание новости 1</p>	тег1, тег2	2014-08-09 09:20:53.374292
2	2014-08-09	Новость 2	новость-2	Анонс новости 2	<p>Описание новости 2</p>	тег2, тег3	2014-08-09 09:21:17.947279
\.


--
-- Name: news_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('news_seq', 2, true);


--
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: -
--

COPY page ("ID", "Name", "Url", "Content", "Parent", "Html_Identified", "Tags", "Last_Modified") FROM stdin;
\.


--
-- Name: page_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('page_seq', 1, false);


--
-- Data for Name: slider_a; Type: TABLE DATA; Schema: public; Owner: -
--

COPY slider_a ("ID", "Name", "Url", "File", "Sort") FROM stdin;
1	Рисунок 1	#1	3bcc4b.png	1
2	Рисунок 2	#2	8ad28d.png	2
3	Рисунок 3	#3	ae0647.png	3
\.


--
-- Name: slider_a_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('slider_a_seq', 3, true);


SET search_path = core, pg_catalog;

--
-- Name: admin_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_PK" PRIMARY KEY ("ID");


--
-- Name: admin_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: admin_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: exe_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_PK" PRIMARY KEY ("ID");


--
-- Name: exe_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: exe_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: html_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY html
    ADD CONSTRAINT "html_PK" PRIMARY KEY ("ID");


--
-- Name: html_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY html
    ADD CONSTRAINT "html_UN_Identified" UNIQUE ("Identified");


--
-- Name: html_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY html
    ADD CONSTRAINT "html_UN_Name" UNIQUE ("Name");


--
-- Name: html_inc_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY html_inc
    ADD CONSTRAINT "html_inc_PK" PRIMARY KEY ("Html_ID", "Inc_ID");


--
-- Name: inc_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_PK" PRIMARY KEY ("ID");


--
-- Name: inc_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: inc_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: module_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY module
    ADD CONSTRAINT "module_PK" PRIMARY KEY ("ID");


--
-- Name: module_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY module
    ADD CONSTRAINT "module_UN_Identified" UNIQUE ("Identified");


--
-- Name: module_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY module
    ADD CONSTRAINT "module_UN_Name" UNIQUE ("Name");


--
-- Name: param_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_PK" PRIMARY KEY ("ID");


--
-- Name: param_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: param_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: phpclass_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_PK" PRIMARY KEY ("ID");


--
-- Name: phpclass_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: phpclass_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: proc_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_PK" PRIMARY KEY ("ID");


--
-- Name: proc_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: proc_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: seo_redirect_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY seo_redirect
    ADD CONSTRAINT "seo_redirect_PK" PRIMARY KEY ("ID");


--
-- Name: seo_redirect_UN_From; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY seo_redirect
    ADD CONSTRAINT "seo_redirect_UN_From" UNIQUE ("From");


--
-- Name: seo_url_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY seo_url
    ADD CONSTRAINT "seo_url_PK" PRIMARY KEY ("ID");


--
-- Name: seo_url_UN_Url; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY seo_url
    ADD CONSTRAINT "seo_url_UN_Url" UNIQUE ("Url");


--
-- Name: tags_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tags
    ADD CONSTRAINT "tags_PK" PRIMARY KEY ("ID");


--
-- Name: tags_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tags
    ADD CONSTRAINT "tags_UN_Name" UNIQUE ("Name");


--
-- Name: text_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_PK" PRIMARY KEY ("ID");


--
-- Name: text_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: text_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: user_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_PK" PRIMARY KEY ("ID");


--
-- Name: user_UN_Email; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_UN_Email" UNIQUE ("Email");


--
-- Name: user_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_UN_Name" UNIQUE ("Name");


--
-- Name: user_group_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT "user_group_PK" PRIMARY KEY ("ID");


--
-- Name: user_group_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT "user_group_UN_Name" UNIQUE ("Name");


--
-- Name: user_priv_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY user_priv
    ADD CONSTRAINT "user_priv_PK" PRIMARY KEY ("Admin_ID", "Group_ID");


--
-- Name: user_session_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY user_session
    ADD CONSTRAINT "user_session_PK" PRIMARY KEY ("ID");


SET search_path = public, pg_catalog;

--
-- Name: articles_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY articles
    ADD CONSTRAINT "articles_PK" PRIMARY KEY ("ID");


--
-- Name: articles_UN_Title; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY articles
    ADD CONSTRAINT "articles_UN_Title" UNIQUE ("Title");


--
-- Name: articles_UN_Url; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY articles
    ADD CONSTRAINT "articles_UN_Url" UNIQUE ("Url");


--
-- Name: faq_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY faq
    ADD CONSTRAINT "faq_PK" PRIMARY KEY ("ID");


--
-- Name: menu_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT "menu_PK" PRIMARY KEY ("ID");


--
-- Name: menu_UN_Name; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT "menu_UN_Name" UNIQUE ("Name");


--
-- Name: menu_item_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_PK" PRIMARY KEY ("ID");


--
-- Name: menu_item_UN_Name; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_UN_Name" UNIQUE ("Name", "Parent", "Menu_ID");


--
-- Name: news_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY news
    ADD CONSTRAINT "news_PK" PRIMARY KEY ("ID");


--
-- Name: news_UN_Title; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY news
    ADD CONSTRAINT "news_UN_Title" UNIQUE ("Title");


--
-- Name: news_UN_Url; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY news
    ADD CONSTRAINT "news_UN_Url" UNIQUE ("Url");


--
-- Name: page_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_PK" PRIMARY KEY ("ID");


--
-- Name: page_UN_Name; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_UN_Name" UNIQUE ("Name", "Parent");


--
-- Name: page_UN_Url; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_UN_Url" UNIQUE ("Url", "Parent");


--
-- Name: slider_a_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY slider_a
    ADD CONSTRAINT "slider_a_PK" PRIMARY KEY ("ID");


SET search_path = core, pg_catalog;

--
-- Name: admin_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: exe_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: html_inc_FK_Html_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY html_inc
    ADD CONSTRAINT "html_inc_FK_Html_ID" FOREIGN KEY ("Html_ID") REFERENCES html("ID");


--
-- Name: html_inc_FK_Inc_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY html_inc
    ADD CONSTRAINT "html_inc_FK_Inc_ID" FOREIGN KEY ("Inc_ID") REFERENCES inc("ID");


--
-- Name: inc_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: param_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: phpclass_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY phpclass
    ADD CONSTRAINT "phpclass_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: proc_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: text_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: user_FK_Group_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_FK_Group_ID" FOREIGN KEY ("Group_ID") REFERENCES user_group("ID");


--
-- Name: user_priv_FK_Admin_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY user_priv
    ADD CONSTRAINT "user_priv_FK_Admin_ID" FOREIGN KEY ("Admin_ID") REFERENCES admin("ID");


--
-- Name: user_priv_FK_Group_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY user_priv
    ADD CONSTRAINT "user_priv_FK_Group_ID" FOREIGN KEY ("Group_ID") REFERENCES user_group("ID");


--
-- Name: user_session_FK_User_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY user_session
    ADD CONSTRAINT "user_session_FK_User_ID" FOREIGN KEY ("User_ID") REFERENCES "user"("ID");


SET search_path = public, pg_catalog;

--
-- Name: menu_FK_Menu_ID; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_FK_Menu_ID" FOREIGN KEY ("Menu_ID") REFERENCES menu("ID");


--
-- Name: menu_item_FK_Parent; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_FK_Parent" FOREIGN KEY ("Parent") REFERENCES menu_item("ID");


--
-- Name: page_FK_Parent; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_FK_Parent" FOREIGN KEY ("Parent") REFERENCES page("ID");


--
-- PostgreSQL database dump complete
--

