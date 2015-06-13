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
ALTER TABLE ONLY public.page DROP CONSTRAINT "page_FK_Html_ID";
ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_item_FK_Parent";
ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_FK_Menu_ID";
SET search_path = core, pg_catalog;

ALTER TABLE ONLY core.user_session DROP CONSTRAINT "user_session_FK_User_ID";
ALTER TABLE ONLY core.user_group_priv DROP CONSTRAINT "user_group_priv_FK_Group_ID";
ALTER TABLE ONLY core.user_group_priv DROP CONSTRAINT "user_group_priv_FK_Admin_ID";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_FK_Group_ID";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_FK_Module_ID";
ALTER TABLE ONLY core.task DROP CONSTRAINT "task_FK_To";
ALTER TABLE ONLY core.task DROP CONSTRAINT "task_FK_From";
ALTER TABLE ONLY core.search_index_tags DROP CONSTRAINT "search_index_tags_FK_Tags_ID";
ALTER TABLE ONLY core.search_index_tags DROP CONSTRAINT "search_index_tags_FK_Index_ID";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_FK_Module_ID";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_FK_Module_ID";
ALTER TABLE ONLY core.packjs_depend DROP CONSTRAINT "packjs_depend_FK_Packjs_ID";
ALTER TABLE ONLY core.packjs_depend DROP CONSTRAINT "packjs_depend_FK_Depend_ID";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_FK_Module_ID";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_FK_Module_ID";
ALTER TABLE ONLY core.ajax DROP CONSTRAINT "ajax_FK_Module_ID";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_FK_Module_ID";
DROP TRIGGER search_index_upd ON core.search_index;
DROP INDEX core."search_index_FTS";
DROP INDEX core."exe_UN_Identified";
SET search_path = public, pg_catalog;

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
ALTER TABLE ONLY core.user_group_priv DROP CONSTRAINT "user_group_priv_PK";
ALTER TABLE ONLY core.user_group DROP CONSTRAINT "user_group_UN_Name";
ALTER TABLE ONLY core.user_group DROP CONSTRAINT "user_group_PK";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_UN_Name";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_UN_Email";
ALTER TABLE ONLY core."user" DROP CONSTRAINT "user_PK";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_UN_Name";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_UN_Identified";
ALTER TABLE ONLY core.text DROP CONSTRAINT "text_PK";
ALTER TABLE ONLY core.task DROP CONSTRAINT "task_PK";
ALTER TABLE ONLY core.seo_url DROP CONSTRAINT "seo_url_UN_Url";
ALTER TABLE ONLY core.seo_url DROP CONSTRAINT "seo_url_PK";
ALTER TABLE ONLY core.seo_redirect DROP CONSTRAINT "seo_redirect_UN_From";
ALTER TABLE ONLY core.seo_redirect DROP CONSTRAINT "seo_redirect_PK";
ALTER TABLE ONLY core.search_tags DROP CONSTRAINT "search_tags_UN_Name";
ALTER TABLE ONLY core.search_tags DROP CONSTRAINT "search_tags_PK";
ALTER TABLE ONLY core.search_index_tags DROP CONSTRAINT "search_index_tags_PK";
ALTER TABLE ONLY core.search_index DROP CONSTRAINT "search_index_UN_Url";
ALTER TABLE ONLY core.search_index DROP CONSTRAINT "search_index_PK";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_UN_Name";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_UN_Identified";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_PK";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_UN_Name";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_UN_Identified";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_PK";
ALTER TABLE ONLY core.packjs_depend DROP CONSTRAINT "packjs_depend_PK";
ALTER TABLE ONLY core.packjs DROP CONSTRAINT "packjs_UN_Name";
ALTER TABLE ONLY core.packjs DROP CONSTRAINT "packjs_UN_Identified";
ALTER TABLE ONLY core.packjs DROP CONSTRAINT "packjs_PK";
ALTER TABLE ONLY core.module DROP CONSTRAINT "module_UN_Name";
ALTER TABLE ONLY core.module DROP CONSTRAINT "module_UN_Identified";
ALTER TABLE ONLY core.module DROP CONSTRAINT "module_PK";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_UN_Name";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_UN_Identified";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_PK";
ALTER TABLE ONLY core.html DROP CONSTRAINT "html_UN_Name";
ALTER TABLE ONLY core.html DROP CONSTRAINT "html_UN_Identified";
ALTER TABLE ONLY core.html DROP CONSTRAINT "html_PK";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_UN_Name";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_PK";
ALTER TABLE ONLY core.ajax DROP CONSTRAINT "ajax_UN_Name";
ALTER TABLE ONLY core.ajax DROP CONSTRAINT "ajax_UN_Identified";
ALTER TABLE ONLY core.ajax DROP CONSTRAINT "ajax_PK";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_UN_Name";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_UN_Identified";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_PK";
SET search_path = public, pg_catalog;

SET search_path = core, pg_catalog;

SET search_path = public, pg_catalog;

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
ALTER TABLE core.task ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.seo_url ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.seo_redirect ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.search_tags ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.search_index ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.proc ALTER COLUMN "Order" DROP DEFAULT;
ALTER TABLE core.proc ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.param ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.packjs ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.module ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.inc ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.html ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.exe ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.ajax ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.admin ALTER COLUMN "Sort" DROP DEFAULT;
ALTER TABLE core.admin ALTER COLUMN "ID" DROP DEFAULT;
SET search_path = public, pg_catalog;

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
DROP SEQUENCE core.user_group_seq;
DROP TABLE core.user_group_priv;
DROP TABLE core.user_group;
DROP TABLE core."user";
DROP SEQUENCE core.text_seq;
DROP TABLE core.text;
DROP SEQUENCE core.task_seq;
DROP TABLE core.task;
DROP SEQUENCE core.seo_url_seq;
DROP TABLE core.seo_url;
DROP SEQUENCE core.seo_redirect_seq;
DROP TABLE core.seo_redirect;
DROP SEQUENCE core.search_tags_seq;
DROP TABLE core.search_tags;
DROP TABLE core.search_index_tags;
DROP SEQUENCE core.search_index_seq;
DROP TABLE core.search_index;
DROP SEQUENCE core.proc_seq;
DROP TABLE core.proc;
DROP SEQUENCE core.param_seq;
DROP TABLE core.param;
DROP SEQUENCE core.packjs_seq;
DROP TABLE core.packjs_depend;
DROP TABLE core.packjs;
DROP SEQUENCE core.module_seq;
DROP TABLE core.module;
DROP SEQUENCE core.inc_seq;
DROP TABLE core.inc;
DROP SEQUENCE core.html_seq;
DROP TABLE core.html;
DROP SEQUENCE core.exe_seq;
DROP TABLE core.exe;
DROP SEQUENCE core.ajax_seq;
DROP TABLE core.ajax;
DROP SEQUENCE core.admin_seq;
DROP TABLE core.admin;
SET search_path = public, pg_catalog;

DROP FUNCTION public.page_is(id integer);
DROP FUNCTION public.page_html_by_id(id integer);
DROP FUNCTION public.page_get(id integer);
DROP FUNCTION public.page_all();
DROP FUNCTION public.news_url_all();
DROP FUNCTION public.news_is(id integer);
DROP FUNCTION public.news_get(id integer);
DROP FUNCTION public.news_all();
DROP FUNCTION public.menu_item_is(id integer);
DROP FUNCTION public.menu_item_by_parent(menu_id integer, parent integer);
DROP FUNCTION public.menu_is(id integer);
DROP FUNCTION public.faq_all();
DROP FUNCTION public.articles_url_all();
DROP FUNCTION public.articles_is(id integer);
DROP FUNCTION public.articles_get(id integer);
DROP FUNCTION public.articles_all();
SET search_path = core, pg_catalog;

DROP FUNCTION core.text_get(module_identified character varying, identified character varying);
DROP FUNCTION core.show_index("table" character varying);
DROP FUNCTION core.seo_url_by_url(url character varying);
DROP FUNCTION core.seo_redirect_all();
DROP FUNCTION core.search_tags_get_by_name(name character varying);
DROP FUNCTION core.search_tags_add(name character varying);
DROP FUNCTION core.search_index_upd_trigger();
DROP FUNCTION core.search_index_find_count(word character varying, tags_id character varying);
DROP FUNCTION core.search_index_find(word character varying, tags_id character varying, "offset" integer, "limit" integer);
DROP FUNCTION core.proc_all(only_active integer);
DROP FUNCTION core.param_get(module_identified character varying, identified character varying);
DROP FUNCTION core.module_page_info();
DROP FUNCTION core.module_by_type(type character varying, only_active integer, access character varying);
DROP FUNCTION core.module_by_identified(identified character varying);
DROP FUNCTION core.inc_by_identified(module_identified character varying, identified character varying);
DROP FUNCTION core.html_is_identified(identified character varying);
DROP FUNCTION core.html_by_identified(identified character varying);
DROP FUNCTION core.exe_by_identified(module_identified character varying, identified character varying);
DROP TYPE core.task_status;
DROP TYPE core.param_type;
DROP TYPE core.module_access;
DROP TYPE core.ajax_data_type;
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
-- Name: ajax_data_type; Type: TYPE; Schema: core; Owner: -
--

CREATE TYPE ajax_data_type AS ENUM (
    'html',
    'text',
    'json',
    'json_std'
);


--
-- Name: TYPE ajax_data_type; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TYPE ajax_data_type IS 'Аякс. Тип возвращаемых данных';


--
-- Name: module_access; Type: TYPE; Schema: core; Owner: -
--

CREATE TYPE module_access AS ENUM (
    'no',
    'local',
    'global'
);


--
-- Name: TYPE module_access; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TYPE module_access IS 'Тип влияния модуля на досуп (нет, локальный, глобальный)';


--
-- Name: param_type; Type: TYPE; Schema: core; Owner: -
--

CREATE TYPE param_type AS ENUM (
    'string',
    'int',
    'bool'
);


--
-- Name: TYPE param_type; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TYPE param_type IS 'Тип параметра (строка, число, булевое значение)';


--
-- Name: task_status; Type: TYPE; Schema: core; Owner: -
--

CREATE TYPE task_status AS ENUM (
    'create',
    'done',
    'fail'
);


--
-- Name: TYPE task_status; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TYPE task_status IS 'Статус задачи (задание создано, задание выполнено, отказ от выполнения задания)';


--
-- Name: exe_by_identified(character varying, character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION exe_by_identified(module_identified character varying, identified character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Module_ID" integer, "Cache_Route" integer, "Cache_Page" integer, "Active" integer)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"e"."ID",
		"e"."Name",
		"e"."Identified",
		"e"."Module_ID",
		"e"."Cache_Route"::int,
		"e"."Cache_Page"::int,
		"e"."Active"::int
	FROM
		"exe" as "e",
		"module" as "m"
	WHERE
		"e"."Identified" = $2 AND
		"e"."Module_ID" = "m"."ID" AND
		"m"."Identified" = $1;
END;
$_$;


--
-- Name: html_by_identified(character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION html_by_identified(identified character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"h"."ID",
		"h"."Name",
		"h"."Identified"
	FROM
		"html" as "h"
	WHERE
		"h"."Identified" = $1;
END;
$_$;


--
-- Name: html_is_identified(character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION html_is_identified(identified character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE html_exists int;
BEGIN
	SELECT INTO html_exists EXISTS
	(
		SELECT
			true
		FROM
			"html"
		WHERE
			"Identified" = $1
	)::int;

	RETURN html_exists;
END;
$_$;


--
-- Name: inc_by_identified(character varying, character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION inc_by_identified(module_identified character varying, identified character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Active" integer, "Module_ID" integer, "Module_Identified" character varying, "Module_Name" character varying)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"i"."ID",
		"i"."Name",
		"i"."Identified",
		"i"."Active"::int,
		"m"."ID" as "Module_ID",
		"m"."Identified" as "Module_Identified",
		"m"."Name" as "Module_Name"
	FROM
		"inc" as "i",
		"module" as "m"
	WHERE
		"i"."Identified" = $2 AND
		"i"."Module_ID" = "m"."ID" AND
		"m"."Identified" = $1;
END;
$_$;


--
-- Name: module_by_identified(character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION module_by_identified(identified character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Access" module_access, "Active" integer)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"m"."ID",
		"m"."Name",
		"m"."Identified",
		"m"."Access",
		"m"."Active"::int
	FROM
		"module" as "m"
	WHERE
		"m"."Identified" = $1;
END;
$_$;


--
-- Name: module_by_type(character varying, integer, character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION module_by_type(type character varying, only_active integer DEFAULT 0, access character varying DEFAULT 'all'::character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Description" pg_catalog.text, "Version" character varying, "Active" integer, "Type" character varying, "Access" character varying)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"m"."ID",
		"m"."Name", 
		"m"."Identified", 
		"m"."Description", 
		"m"."Version", 
		"m"."Active"::int,
		CASE LEFT("m"."Identified", 1)
			WHEN '_' THEN 'smod'::varchar
			ELSE 'mod'::varchar
		END as "Type",
		"m"."Access"::varchar
	FROM 
		"module" as "m"
	WHERE 
		(
			($1 = 'all') OR
			($1 = 'smod' AND LEFT ("m"."Identified", 1) = '_') OR
			($1 = 'mod' AND LEFT ("m"."Identified", 1) != '_')
		) AND
		(
			($2 = 1 AND "m"."Active" = true) OR
			($2 = 0)
		) AND
		(
			($3 = 'all') OR
			("m"."Access"::varchar = $3)
		)
	ORDER BY 
		"Type" DESC,
		"m"."Identified" ASC;
END;
$_$;


--
-- Name: module_page_info(); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION module_page_info() RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Type" character varying, "Page_Info_Function" character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT
		"m"."ID",
		"m"."Name",
		"m"."Identified",
		CASE LEFT("m"."Identified", 1)
			WHEN '_' THEN 'smod'::varchar
			ELSE 'mod'::varchar
		END as "Type",
		"m"."Page_Info_Function"
	FROM
		"module" as "m"
	WHERE
		"m"."Active" = true AND
		"m"."Page_Info_Function" IS NOT NULL;
END;
$$;


--
-- Name: param_get(character varying, character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION param_get(module_identified character varying, identified character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Type" param_type, "Value" character varying)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT 
		"p"."ID", 
		"p"."Name", 
		"p"."Identified", 
		"p"."Type", 
		"p"."Value"
	FROM 
		"param" as "p"
	WHERE 
		"p"."Identified" = $2 AND
		(
			(
				$1 != 'sys' AND
				"p"."Module_ID" IN 
				(
					SELECT 
						"m"."ID"
					FROM
						"module" as "m"
					WHERE
						"m"."Identified" = $1
				)
			) OR
			$1 = 'sys' AND
			"p"."Module_ID" IS NULL
		);
END;
$_$;


--
-- Name: proc_all(integer); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION proc_all(only_active integer) RETURNS TABLE("ID" integer, "Identified" character varying, "Name" character varying, "Active" integer, "Module_ID" integer, "Module_Identified" character varying, "Module_Name" character varying, "Module_Active" integer)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT 
		"p"."ID",
		"p"."Identified",
		"p"."Name",
		"p"."Active"::int,
		"m"."ID" as "Module_ID",
		"m"."Identified" as "Module_Identified", 
		"m"."Name" as "Module_Name",
		"m"."Active"::int
	FROM 
		"proc" as "p",
		"module" as "m"
	WHERE
		(
			($1 = 1 AND "p"."Active" = true) OR
			($1 = 0)
		) AND
		"p"."Module_ID" = "m"."ID" AND
		(
			($1 = 1 AND "m"."Active" = true) OR
			($1 = 0)
		)
	ORDER BY
		"p"."Order" ASC;
END;
$_$;


--
-- Name: search_index_find(character varying, character varying, integer, integer); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION search_index_find(word character varying, tags_id character varying, "offset" integer, "limit" integer) RETURNS TABLE("ID" integer, "Url" character varying, "Title" character varying, "Content" pg_catalog.text, "Tags" character varying)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"i"."ID",
		"i"."Url",
		"i"."Title",
		"i"."Content",
		"i"."Tags"
	FROM
		"search_index" as "i"
	WHERE
		(
			TRIM($2) = '' OR
			(
				TRIM($2) != '' AND
				"i"."ID" IN 
				(
					SELECT 
						"si"."Index_ID"
					FROM
						"search_index_tags" as "si"
					WHERE
						"si"."Tags_ID" = ANY ($2::int[])
				)
			)
		) 
		AND
		(
			TRIM($1) = '' OR
			TRIM($1) != '' AND
			"i"."FTS" @@ to_tsquery('russian', $1)
		)
	OFFSET $3
	LIMIT $4;
END;
$_$;


--
-- Name: search_index_find_count(character varying, character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION search_index_find_count(word character varying, tags_id character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE count int;
BEGIN
	SELECT INTO count
		COUNT(*)
	FROM
		"search_index" as "i"
	WHERE
	(
		TRIM($2) = '' OR
		(
			TRIM($2) != '' AND
			"i"."ID" IN 
			(
				SELECT 
					"si"."Index_ID"
				FROM
					"search_index_tags" as "si"
				WHERE
					"si"."Tags_ID" = ANY ($2::int[])
			)
		)
	) 
	AND
	(
		TRIM($1) = '' OR
		TRIM($1) != '' AND
		"i"."FTS" @@ to_tsquery('russian', $1)
	);

	RETURN count;
END;
$_$;


--
-- Name: search_index_upd_trigger(); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION search_index_upd_trigger() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
	new."FTS" := 
		setweight(coalesce(to_tsvector('russian', new."Url"), ''), 'A') || ' ' || 
		setweight(coalesce(to_tsvector('russian', new."Title"), ''), 'C') || ' ' ||
		setweight(coalesce(to_tsvector('russian', new."Content"), ''), 'D') || ' ' ||
		setweight(coalesce(to_tsvector('russian', new."Tags"), ''), 'B');
	return new;
end
$$;


--
-- Name: search_tags_add(character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION search_tags_add(name character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE tags_isset bool;
DECLARE tags_id int;
BEGIN
	tags_isset := EXISTS
	(
		SELECT 
			true
		FROM
			"search_tags"
		WHERE
			"Name" = $1
	);

	IF tags_isset = false
	THEN
		INSERT INTO "search_tags" ("Name") 
		VALUES ($1) 
		RETURNING "ID" INTO tags_id;
	ELSE
		UPDATE	
			"search_tags"
		SET 
			"Count" = "Count" + 1
		WHERE
			"Name" = $1;
	
		SELECT INTO tags_id 
			"ID"
		FROM
			"search_tags"
		WHERE
			"Name" = $1;
	END IF;

	RETURN tags_id;
END;
$_$;


--
-- Name: search_tags_get_by_name(character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION search_tags_get_by_name(name character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Count" integer)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"t"."ID",
		"t"."Name",
		"t"."Count"
	FROM
		"search_tags" as "t"
	WHERE
		"t"."Name" = $1;
END;
$_$;


--
-- Name: seo_redirect_all(); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION seo_redirect_all() RETURNS TABLE("ID" integer, "From" character varying, "To" character varying, "Location" integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT
		"r"."ID",
		"r"."From",
		"r"."To",
		"r"."Location"::int
	FROM
		"seo_redirect" as "r"
	ORDER BY
		"r"."From" ASC;
END;
$$;


--
-- Name: seo_url_by_url(character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION seo_url_by_url(url character varying) RETURNS TABLE("ID" integer, "Url" character varying, "Title" character varying, "Keywords" pg_catalog.text, "Description" pg_catalog.text)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"u"."ID",
		"u"."Url",
		"u"."Title",
		"u"."Keywords",
		"u"."Description"
	FROM
		"seo_url" as "u"
	WHERE
		"u"."Url" ILIKE $1;
END;
$_$;


--
-- Name: show_index(character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION show_index("table" character varying) RETURNS TABLE(pg_get_indexdef pg_catalog.text)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT 
		pg_get_indexdef(indexrelid)::text
	FROM 
		pg_index 
	WHERE 
		indrelid = $1::regclass;
END;
$_$;


--
-- Name: text_get(character varying, character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION text_get(module_identified character varying, identified character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Value" pg_catalog.text)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT 
		"t"."ID", 
		"t"."Name", 
		"t"."Identified", 
		"t"."Value"
	FROM 
		"text" as "t"
	WHERE 
		"t"."Identified" = $2 AND
		(
			(
				$1 != 'sys' AND
				"t"."Module_ID" IN 
				(
					SELECT 
						"m"."ID"
					FROM
						"module" as "m"
					WHERE
						"m"."Identified" = $1
				)
			) OR
			(
				$1 = 'sys' AND
				"t"."Module_ID" IS NULL
			)
		);
END;
$_$;


SET search_path = public, pg_catalog;

--
-- Name: articles_all(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION articles_all() RETURNS TABLE("ID" integer, "Date" date, "Title" character varying, "Url" character varying, "Anons" text, "Last_Modified" timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT 
		"a"."ID",
		"a"."Date",
		"a"."Title",
		"a"."Url",
		"a"."Anons",
		"a"."Last_Modified"
	FROM 
		"articles" as "a"
	ORDER BY
		"a"."Date" DESC;
END;
$$;


--
-- Name: articles_get(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION articles_get(id integer) RETURNS TABLE("ID" integer, "Date" date, "Title" character varying, "Url" character varying, "Anons" text, "Content" text, "Tags" character varying, "Last_Modified" timestamp without time zone)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT 
		"a"."ID", 
		"a"."Date", 
		"a"."Title", 
		"a"."Url", 
		"a"."Anons", 
		"a"."Content",
		"a"."Tags",
		"a"."Last_Modified"
	FROM 
		"articles" as "a"
	WHERE
		"a"."ID" = $1;
END;
$_$;


--
-- Name: articles_is(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION articles_is(id integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN EXISTS
	(
		SELECT 
			true
		FROM
			"articles"
		WHERE
			"ID" = $1
	)::int;
END;
$_$;


--
-- Name: articles_url_all(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION articles_url_all() RETURNS TABLE("ID" integer, "Url" character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT 
		"a"."ID",
		"a"."Url"
	FROM 
		"articles" as "a"
	ORDER BY
		"a"."Url" DESC;
END;
$$;


--
-- Name: faq_all(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION faq_all() RETURNS TABLE("ID" integer, "Question" text, "Answer" text, "Sort" integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT 
		"f"."ID", 
		"f"."Question",
		"f"."Answer",
		"f"."Sort"
	FROM 
		"faq" as "f"
	ORDER BY
		"f"."Sort" ASC;
END;
$$;


--
-- Name: menu_is(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION menu_is(id integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN EXISTS
	(
		SELECT 
			true
		FROM
			"menu"
		WHERE
			"ID" = $1
	)::int;
END;
$_$;


--
-- Name: menu_item_by_parent(integer, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION menu_item_by_parent(menu_id integer, parent integer) RETURNS TABLE("ID" integer, "Name" character varying, "Url" character varying)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"mi"."ID",
		"mi"."Name",
		"mi"."Url"
	FROM 
		"menu_item" as "mi"
	WHERE
		COALESCE("mi"."Menu_ID", 0) = $1 AND
		COALESCE("mi"."Parent", 0) = $2
	ORDER BY 
		"mi"."Sort" ASC;
END;
$_$;


--
-- Name: menu_item_is(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION menu_item_is(id integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN EXISTS
	(
		SELECT 
			true
		FROM
			"menu_item"
		WHERE
			"ID" = $1
	)::int;
END;
$_$;


--
-- Name: news_all(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION news_all() RETURNS TABLE("ID" integer, "Date" date, "Title" character varying, "Url" character varying, "Anons" text, "Last_Modified" timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT 
		"n"."ID",
		"n"."Date",
		"n"."Title",
		"n"."Url",
		"n"."Anons",
		"n"."Last_Modified"
	FROM 
		"news" as "n"
	ORDER BY
		"n"."Date" DESC;
END;
$$;


--
-- Name: news_get(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION news_get(id integer) RETURNS TABLE("ID" integer, "Date" date, "Title" character varying, "Url" character varying, "Anons" text, "Content" text, "Tags" character varying, "Last_Modified" timestamp without time zone)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT 
		"n"."ID", 
		"n"."Date", 
		"n"."Title", 
		"n"."Url", 
		"n"."Anons", 
		"n"."Content",
		"n"."Tags",
		"n"."Last_Modified"
	FROM 
		"news" as "n"
	WHERE
		"n"."ID" = $1;
END;
$_$;


--
-- Name: news_is(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION news_is(id integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN EXISTS
	(
		SELECT 
			true
		FROM
			"news"
		WHERE
			"ID" = $1
	)::int;
END;
$_$;


--
-- Name: news_url_all(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION news_url_all() RETURNS TABLE("ID" integer, "Url" character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT 
		"n"."ID",
		"n"."Url"
	FROM 
		"news" as "n"
	ORDER BY
		"n"."Url" DESC;
END;
$$;


--
-- Name: page_all(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION page_all() RETURNS TABLE("ID" integer, "Name" character varying, "Url" character varying, "Parent" integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY SELECT 
		"p"."ID", 
		"p"."Name", 
		"p"."Url",
		COALESCE("p"."Parent", 0) as "Parent"
	FROM 
		"page" as "p"
	ORDER BY 
		"p"."Name" ASC,
		"p"."Parent" ASC;
END;
$$;


--
-- Name: FUNCTION page_all(); Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON FUNCTION page_all() IS 'Все страницы';


--
-- Name: page_get(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION page_get(id integer) RETURNS TABLE("ID" integer, "Name" character varying, "Url" character varying, "Content" text, "Tags" character varying, "Parent" integer, "Html_ID" integer, "Last_Modified" timestamp without time zone)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"p"."ID", 
		"p"."Name",
		"p"."Url",
		"p"."Content",
		"p"."Tags",
		COALESCE("p"."Parent", 0) as "Parent",
		COALESCE("p"."Html_ID", 0) as "Html_ID",
		"p"."Last_Modified"
	FROM 
		"page" as "p"
	WHERE 
		"p"."ID" = $1;
END;
$_$;


--
-- Name: page_html_by_id(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION page_html_by_id(id integer) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying)
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN QUERY SELECT
		"h"."ID",
		"h"."Name",
		"h"."Identified"
	FROM
		"page" as "p",
		"core"."html" as "h"
	WHERE
		"p"."ID" = $1 AND
		"p"."Html_ID" = "h"."ID";
END;
$_$;


--
-- Name: page_is(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION page_is(id integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
BEGIN
	RETURN EXISTS
	(
		SELECT 
			true
		FROM
			"page"
		WHERE
			"ID" = $1
	)::int;
END;
$_$;


SET search_path = core, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: admin; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE admin (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
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
-- Name: ajax; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE ajax (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Data_Type" ajax_data_type DEFAULT 'html'::ajax_data_type NOT NULL,
    "Token" boolean DEFAULT false NOT NULL,
    "Module_ID" integer NOT NULL,
    "Get" boolean DEFAULT true NOT NULL,
    "Post" boolean DEFAULT false NOT NULL
);


--
-- Name: TABLE ajax; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE ajax IS 'Аяксы';


--
-- Name: COLUMN ajax."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."ID" IS 'Порядковый номер';


--
-- Name: COLUMN ajax."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Name" IS 'Наименование';


--
-- Name: COLUMN ajax."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Identified" IS 'Идентификатор';


--
-- Name: COLUMN ajax."Data_Type"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Data_Type" IS 'Тип возвращаемых данных (html,text,json)';


--
-- Name: COLUMN ajax."Token"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Token" IS 'Проверять ли токен';


--
-- Name: COLUMN ajax."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN ajax."Get"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Get" IS 'Обработка GET данных';


--
-- Name: COLUMN ajax."Post"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Post" IS 'Обработка POST данных';


--
-- Name: ajax_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE ajax_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ajax_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE ajax_seq OWNED BY ajax."ID";


--
-- Name: exe; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE exe (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Module_ID" integer NOT NULL,
    "Active" boolean DEFAULT true NOT NULL,
    "Cache_Route" boolean DEFAULT false NOT NULL,
    "Cache_Page" boolean DEFAULT false NOT NULL
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
-- Name: COLUMN exe."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Active" IS 'Активность';


--
-- Name: COLUMN exe."Cache_Route"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Cache_Route" IS 'Использовать кэширование маршрутизатора';


--
-- Name: COLUMN exe."Cache_Page"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN exe."Cache_Page" IS 'Использовать кэширование страниц';


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
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL
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
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
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
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Description" pg_catalog.text DEFAULT ''::pg_catalog.text,
    "Version" character varying(255) DEFAULT ''::character varying NOT NULL,
    "Active" boolean DEFAULT true NOT NULL,
    "Access" module_access DEFAULT 'no'::module_access NOT NULL,
    "Page_Info_Function" character varying
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
-- Name: COLUMN module."Description"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Description" IS 'Описание';


--
-- Name: COLUMN module."Version"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Version" IS 'Версия';


--
-- Name: COLUMN module."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Active" IS 'Активность';


--
-- Name: COLUMN module."Access"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Access" IS 'Влияние на доступ';


--
-- Name: COLUMN module."Page_Info_Function"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN module."Page_Info_Function" IS 'Функция или метод показывающая информацию по страницам модуля';


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
-- Name: packjs; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE packjs (
    "ID" integer NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Description" pg_catalog.text,
    "Version" character varying(255),
    "Url" character varying(255),
    "Category" character varying(127)
);


--
-- Name: TABLE packjs; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE packjs IS 'Пакеты JavaScript';


--
-- Name: COLUMN packjs."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs."ID" IS 'Порядковый номер';


--
-- Name: COLUMN packjs."Identified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs."Identified" IS 'Идентификатор';


--
-- Name: COLUMN packjs."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs."Name" IS 'Наименование';


--
-- Name: COLUMN packjs."Description"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs."Description" IS 'Описание';


--
-- Name: COLUMN packjs."Version"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs."Version" IS 'Версия';


--
-- Name: COLUMN packjs."Url"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs."Url" IS 'Адрес в интернете';


--
-- Name: COLUMN packjs."Category"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs."Category" IS 'Категория';


--
-- Name: packjs_depend; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE packjs_depend (
    "Packjs_ID" integer NOT NULL,
    "Depend_ID" integer NOT NULL,
    "Order" integer NOT NULL
);


--
-- Name: TABLE packjs_depend; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE packjs_depend IS 'Пакеты JavaScript. Зависемости';


--
-- Name: COLUMN packjs_depend."Packjs_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs_depend."Packjs_ID" IS 'ID пакета';


--
-- Name: COLUMN packjs_depend."Depend_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs_depend."Depend_ID" IS 'ID пакета, от которого зависит';


--
-- Name: COLUMN packjs_depend."Order"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN packjs_depend."Order" IS 'Порядок загрузки зависемостей';


--
-- Name: packjs_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE packjs_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: packjs_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE packjs_seq OWNED BY packjs."ID";


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
-- Name: proc; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE proc (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Identified" character varying(127) NOT NULL,
    "Module_ID" integer NOT NULL,
    "Active" boolean DEFAULT true NOT NULL,
    "Order" integer NOT NULL
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
-- Name: COLUMN proc."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN proc."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Active" IS 'Активность';


--
-- Name: COLUMN proc."Order"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN proc."Order" IS 'Порядок загрузки';


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
-- Name: search_index; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE search_index (
    "ID" integer NOT NULL,
    "Url" character varying NOT NULL,
    "Title" character varying NOT NULL,
    "Content" pg_catalog.text,
    "Tags" character varying,
    "FTS" tsvector
);


--
-- Name: TABLE search_index; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE search_index IS 'Поисковые индекс сайта';


--
-- Name: COLUMN search_index."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index."ID" IS 'Порядковый номер';


--
-- Name: COLUMN search_index."Url"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index."Url" IS 'Урл';


--
-- Name: COLUMN search_index."Title"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index."Title" IS 'Заголовок';


--
-- Name: COLUMN search_index."Content"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index."Content" IS 'Содержание';


--
-- Name: COLUMN search_index."Tags"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index."Tags" IS 'Тэги';


--
-- Name: COLUMN search_index."FTS"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index."FTS" IS 'Полнотекстовой индекс по странице';


--
-- Name: search_index_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE search_index_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: search_index_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE search_index_seq OWNED BY search_index."ID";


--
-- Name: search_index_tags; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE search_index_tags (
    "Index_ID" integer NOT NULL,
    "Tags_ID" integer NOT NULL
);


--
-- Name: TABLE search_index_tags; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE search_index_tags IS 'Привязка страниц в поиске с тэгами';


--
-- Name: COLUMN search_index_tags."Index_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index_tags."Index_ID" IS 'Номер индекса';


--
-- Name: COLUMN search_index_tags."Tags_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_index_tags."Tags_ID" IS 'Номер тэга';


--
-- Name: search_tags; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE search_tags (
    "ID" integer NOT NULL,
    "Name" character varying,
    "Count" integer DEFAULT 1 NOT NULL
);


--
-- Name: TABLE search_tags; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE search_tags IS 'Тэги';


--
-- Name: COLUMN search_tags."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_tags."ID" IS 'Порядковый номер';


--
-- Name: COLUMN search_tags."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_tags."Name" IS 'Наименование';


--
-- Name: COLUMN search_tags."Count"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_tags."Count" IS 'Количество';


--
-- Name: search_tags_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE search_tags_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: search_tags_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE search_tags_seq OWNED BY search_tags."ID";


--
-- Name: seo_redirect; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE seo_redirect (
    "ID" integer NOT NULL,
    "From" character varying(255) NOT NULL,
    "To" character varying(255) NOT NULL,
    "Location" boolean DEFAULT true NOT NULL
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

COMMENT ON COLUMN seo_redirect."To" IS 'Делать переход на другой урл';


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
    "Url" character varying(255) NOT NULL,
    "Title" character varying(255),
    "Keywords" pg_catalog.text,
    "Description" pg_catalog.text
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
-- Name: task; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE task (
    "ID" integer NOT NULL,
    "From" integer,
    "To" integer,
    "Name" character varying(255) NOT NULL,
    "Content" pg_catalog.text,
    "Status" task_status DEFAULT 'create'::task_status NOT NULL,
    "Date_Create" timestamp without time zone DEFAULT now() NOT NULL,
    "Date_Require" timestamp without time zone,
    "Date_Done" timestamp without time zone,
    "Date_Fail" timestamp without time zone,
    "Note" pg_catalog.text
);


--
-- Name: TABLE task; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE task IS 'Задания';


--
-- Name: COLUMN task."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."ID" IS 'Порядковый номер';


--
-- Name: COLUMN task."From"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."From" IS 'Заказчик';


--
-- Name: COLUMN task."To"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."To" IS 'Исполнитель';


--
-- Name: COLUMN task."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Name" IS 'Наименование';


--
-- Name: COLUMN task."Content"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Content" IS 'Описание';


--
-- Name: COLUMN task."Status"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Status" IS 'Статус';


--
-- Name: COLUMN task."Date_Create"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Date_Create" IS 'Дата создания';


--
-- Name: COLUMN task."Date_Require"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Date_Require" IS 'Выполнить до';


--
-- Name: COLUMN task."Date_Done"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Date_Done" IS 'Дата выполения';


--
-- Name: COLUMN task."Date_Fail"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Date_Fail" IS 'Дата отказа';


--
-- Name: COLUMN task."Note"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN task."Note" IS 'Примечание';


--
-- Name: task_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE task_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: task_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE task_seq OWNED BY task."ID";


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
    "Password" character(32),
    "Group_ID" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL,
    "Password_Change_Code" character varying(32),
    "Password_Change_Date" timestamp without time zone,
    "Salt" character(4) NOT NULL,
    "Visit_Last_Admin" character varying(255)
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
-- Name: COLUMN "user"."Password_Change_Code"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Password_Change_Code" IS 'Код на восстановления пароля';


--
-- Name: COLUMN "user"."Password_Change_Date"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Password_Change_Date" IS 'Дата последнего изменения пароля';


--
-- Name: COLUMN "user"."Salt"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Salt" IS 'Соля для пароля';


--
-- Name: COLUMN "user"."Visit_Last_Admin"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN "user"."Visit_Last_Admin" IS 'Последняя посещаемая страница в адмнике';


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
-- Name: user_group_priv; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE user_group_priv (
    "Admin_ID" integer NOT NULL,
    "Group_ID" integer NOT NULL
);


--
-- Name: TABLE user_group_priv; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE user_group_priv IS 'Привилегии группы';


--
-- Name: COLUMN user_group_priv."Admin_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_group_priv."Admin_ID" IS 'Привязка к админке';


--
-- Name: COLUMN user_group_priv."Group_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_group_priv."Group_ID" IS 'Привязка к группе';


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
    "IP" character(32) NOT NULL,
    "Browser" character(32) NOT NULL,
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

COMMENT ON COLUMN user_session."IP" IS 'Хэш IP-адреса создателя сессии';


--
-- Name: COLUMN user_session."Browser"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN user_session."Browser" IS 'Хэш строки USER_AGENT браузера создателя сессии';


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
    "Tags" character varying(255),
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
    "Name" character varying(255) NOT NULL,
    "Url" character varying(127) NOT NULL,
    "Content" text,
    "Parent" integer,
    "Tags" character varying(255),
    "Last_Modified" timestamp without time zone DEFAULT now() NOT NULL,
    "Html_ID" integer
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

ALTER TABLE ONLY ajax ALTER COLUMN "ID" SET DEFAULT nextval('ajax_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY exe ALTER COLUMN "ID" SET DEFAULT nextval('exe_seq'::regclass);


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

ALTER TABLE ONLY packjs ALTER COLUMN "ID" SET DEFAULT nextval('packjs_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY param ALTER COLUMN "ID" SET DEFAULT nextval('param_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY proc ALTER COLUMN "ID" SET DEFAULT nextval('proc_seq'::regclass);


--
-- Name: Order; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY proc ALTER COLUMN "Order" SET DEFAULT currval('proc_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY search_index ALTER COLUMN "ID" SET DEFAULT nextval('search_index_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY search_tags ALTER COLUMN "ID" SET DEFAULT nextval('search_tags_seq'::regclass);


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

ALTER TABLE ONLY task ALTER COLUMN "ID" SET DEFAULT nextval('task_seq'::regclass);


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
65	Управление	list	65	t	f	t	15	f	f
67	Редактировать	edit	67	t	t	f	15	f	f
66	Добавить	add	66	t	t	t	15	f	f
68	Удалить	delete	68	f	t	f	15	f	f
140	Задания	to	145	t	f	t	32	f	t
43	robots.txt	robots	43	t	t	t	10	f	f
81	Правка exe	exe_content	82	t	t	f	17	f	f
77	Шаблоны	html	78	t	f	t	17	f	f
80	Правка inc	inc_content	81	t	t	f	17	f	f
79	Модули	module	80	t	f	t	17	f	f
82	Управление	url	77	t	f	t	17	f	f
150	Удалить поручение	from_delete	151	f	t	f	32	f	t
23	Скачать	download	22	f	t	f	3	t	f
69	Сортировка вверх	sort	69	f	t	f	15	f	f
54	Пункт меню вверх	item_sort	54	f	t	f	11	f	f
45	Другие страницы	other	45	t	t	t	10	f	f
35	Продвижение	url	35	t	f	t	10	f	f
149	Редактировать поручение	from_edit	150	t	t	f	32	f	t
142	Управление	list	140	t	f	t	32	f	f
143	Дать поручение	from_add	149	t	t	f	32	f	t
141	Поручения	from	148	t	f	t	32	f	t
151	Посмотреть задание	to_view	146	t	f	f	32	f	t
148	Сменить статус задания	to_status	147	t	t	f	32	f	t
146	Редактировать	edit	142	t	t	f	32	f	f
145	Добавить	add	141	t	t	t	32	f	f
147	Удалить	delete	143	f	t	f	32	f	f
229	Другие страницы	other_page	229	t	t	t	1	f	f
230	Главная страница	home	230	t	t	f	1	f	f
231	Страница 404	404	231	t	t	f	1	f	f
232	Страница 403	403	232	t	t	f	1	f	f
\.


--
-- Name: admin_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('admin_seq', 232, true);


--
-- Data for Name: ajax; Type: TABLE DATA; Schema: core; Owner: -
--

COPY ajax ("ID", "Name", "Identified", "Data_Type", "Token", "Module_ID", "Get", "Post") FROM stdin;
\.


--
-- Name: ajax_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('ajax_seq', 98, true);


--
-- Data for Name: exe; Type: TABLE DATA; Schema: core; Owner: -
--

COPY exe ("ID", "Name", "Identified", "Module_ID", "Active", "Cache_Route", "Cache_Page") FROM stdin;
12	Страница 404	404	9	t	t	t
11	Главная страница	home	9	t	t	t
10	Описание	content	9	t	t	t
13	Страница 403	403	9	t	t	t
179	Главная страница	home	1	t	t	t
180	Страница 404	404	1	t	t	t
181	Страница 403	403	1	t	t	t
16	Список	list	13	t	t	t
17	Содержание	content	13	t	t	t
14	Список	list	12	t	t	t
15	Содержание	content	12	t	t	t
21	Список	list	15	t	t	t
46	Карта сайта	sitemap	25	t	t	t
187	Поиск по сайту	find	54	t	t	f
\.


--
-- Name: exe_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('exe_seq', 187, true);


--
-- Data for Name: html; Type: TABLE DATA; Schema: core; Owner: -
--

COPY html ("ID", "Name", "Identified") FROM stdin;
5	По умолчанию	default
6	Главная	home
\.


--
-- Name: html_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('html_seq', 18, true);


--
-- Data for Name: inc; Type: TABLE DATA; Schema: core; Owner: -
--

COPY inc ("ID", "Name", "Identified", "Module_ID", "Active") FROM stdin;
6	Верхнее	top	11	t
39	Форма поиска	form	54	t
\.


--
-- Name: inc_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('inc_seq', 39, true);


--
-- Data for Name: module; Type: TABLE DATA; Schema: core; Owner: -
--

COPY module ("ID", "Name", "Identified", "Description", "Version", "Active", "Access", "Page_Info_Function") FROM stdin;
11	Меню	menu	Многоуровневое меню	1.0	t	no	\N
1	Сервис	_service	Сведения о модулях.\r\nСведения о системе.\r\nСведения о PHP.\r\nСведения о PostgreSQL.	1.0	t	no	\N
2	Пользователи	_user	Управление пользователями	1.0	t	no	\N
3	Проводник	_explorer	Управление статическими файлами	1.0	t	no	\N
25	Карта сайта	_sitemap		1.0	t	no	\N
32	Задачи	_task		1.0	t	no	\N
17	HTML-код	_html_code	HTML-вёрстка	1.0	t	no	\N
10	Поисковая оптимизация	_seo	Управление тегами title, meta. Правка файла robots.txt. Переадресация.	1.0	t	no	\N
9	Страницы	page	Странице на сайте	1.0	t	no	Page::page_info
13	Статьи	articles		1.0	t	no	Articles::page_info
12	Новости	news		1.0	t	no	News::page_info
15	Вопрос-Ответ	faq	Часто задаваемые вопросы	1.0	t	no	Faq::page_info
54	Поиск	_search	Поиск по сайту. Два режима работы обычный и через sphinx.	1.0	t	no	_Search::page_info
\.


--
-- Name: module_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('module_seq', 54, true);


--
-- Data for Name: packjs; Type: TABLE DATA; Schema: core; Owner: -
--

COPY packjs ("ID", "Identified", "Name", "Description", "Version", "Url", "Category") FROM stdin;
\.


--
-- Data for Name: packjs_depend; Type: TABLE DATA; Schema: core; Owner: -
--

COPY packjs_depend ("Packjs_ID", "Depend_ID", "Order") FROM stdin;
\.


--
-- Name: packjs_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('packjs_seq', 17, true);


--
-- Data for Name: param; Type: TABLE DATA; Schema: core; Owner: -
--

COPY param ("ID", "Name", "Identified", "Type", "Value", "Module_ID") FROM stdin;
126	Sphinx. Хост	sphinx_host	string	127.0.0.1	54
124	Тип поискового движка (pgsql, sphinx)	type	string	pgsql	54
34	Файл CSS по умолчанию	css_default	string	/css/content.css	\N
19	Шаблон по умолчанию	html_default	string	default	\N
5	Страница 403. Модуль	403_module	string	page	\N
111	Страница 403. Урл админки	403_admin_url	string	#page/403	\N
21	Страница 404. Заголовок	404_title	string	Страница не найдена	9
1	Главная страница. Модуль	home_module	string	page	\N
6	Страница 403. Exe	403_exe	string	403	\N
2	Главная страница. Exe	home_exe	string	home	\N
115	Главная страница. Тэги	home_tags	string	Главная страница, Добро пожаловать	1
4	Страница 404. Exe	404_exe	string	404	\N
122	Страница 404. Шаблон	404_html	string		9
109	Главная страница. Урл админки	home_admin_url	string	#page/home	\N
3	Страница 404. Модуль	404_module	string	page	\N
118	Страница 403. Тэги	403_tags	string	Доступ запрещён, Ошибка 403, 403 Forbidden, 403 error	1
119	Страница 404. Тэги	404_tags	string	Страница не найдена, Ошибка 404, Not Found, 404 error	1
116	Главная страница. Заголовок	home_title	string	Добро пожаловать	1
117	Страница 404. Заголовок	404_title	string	Страница не найдена	1
110	Страница 404. Урл админки	404_admin_url	string	#page/404	\N
120	Страница 403. Заголовок	403_title	string	Доступ запрещён	1
113	Страница 404. Тэги	404_tags	string	Страница не найдена, Страница 404, Not Found	9
125	Кол-во результатов на страницу	limit	int	10	54
127	Sphinx. Порт	sphinx_port	int	9312	54
114	Страница 403. Тэги	403_tags	string	Доступ запрещён, Access Denied, страница 403	9
123	Страница 403. Шаблон	403_html	string		9
53	Дата последнего изменения	last_modified	string	2015-06-14 01:12:16	15
20	Главная страница. Заголовок	home_title	string	Добро пожаловать	9
112	Главная страница. Тэги	home_tags	string	тег 1	9
121	Главная страница. Шаблон	home_html	string	home	9
128	Sphinx. Наименование индекса	sphinx_index	string	example	54
86	Сессия в конструкторе	constr_session	string	a:5:{s:2:"ID";s:32:"fff53a21ebf31c4914143299e7010b8b";s:2:"IP";s:32:"9ec226bb04ae774434c107948c0db5bd";s:7:"Browser";s:32:"6a286da3b897ca08af5014870326fb03";s:4:"Date";s:19:"2015-06-14 01:51:34";s:7:"User_ID";i:0;}	\N
88	Последняя посещаемая страница в конструкторе	constr_visit_last	string	#module/list	\N
103	Главная страница. Тэг title	home_title	string		10
22	Страница 403. Заголовок	403_title	string	Доступ запрещён	9
104	Главная страница. Тэг meta name="keywords"	home_keywords	string		10
105	Страница 404. Тэг title	404_title	string		10
106	Страница 404. Тэг meta name="keywords"	404_keywords	string		10
107	Страница 403. Тэг title	403_title	string		10
108	Страница 403. Тэг meta name="keywords"	403_keywords	string		10
89	Последняя посещаемая страница в адмнике root-ом	admin_root_visit_last	string	#_service/module	\N
\.


--
-- Name: param_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('param_seq', 128, true);


--
-- Data for Name: proc; Type: TABLE DATA; Schema: core; Owner: -
--

COPY proc ("ID", "Name", "Identified", "Module_ID", "Active", "Order") FROM stdin;
4	Теги title, meta	tag	10	t	8
\.


--
-- Name: proc_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('proc_seq', 31, true);


--
-- Data for Name: search_index; Type: TABLE DATA; Schema: core; Owner: -
--

COPY search_index ("ID", "Url", "Title", "Content", "Tags", "FTS") FROM stdin;
1	/страница-1	Страница 1	<p>Первая страница</p>	тэг 3	'-1':2A '1':4C '3':8B 'перв':5 'страниц':1A,3C,6 'тэг':7B
2	/статьи	Статьи	Полезные статьи	статьи, полезные статьи	'полезн':3,6B 'стат':1A,2C,4,5B,7B
3	/статьи/статья-2	Статья 2	<p>Описание статьи 2</p>\nДата: 13.06.2015\nАнонс: Анонс статьи 2	тег 1	'-2':3A '1':16B '13.06.2015':10 '2':5C,8,14 'анонс':11,12 'дат':9 'описан':6 'стат':1A,2A,4C,7,13 'тег':15B
4	/статьи/статья-1	Статья 1	<p>Описание статьи 1</p>\nДата: 12.06.2015\nАнонс: Анонс статьи 1	тег 1, тег 2, тег 3	'-1':3A '1':5C,8,14,16B '12.06.2015':10 '2':18B '3':20B 'анонс':11,12 'дат':9 'описан':6 'стат':1A,2A,4C,7,13 'тег':15B,17B,19B
5	/новости	Новости	Последние новости. Новости сайта	новости, новости сайта, последние новости	'новост':1A,2C,4,5,7B,8B,11B 'последн':3,10B 'сайт':6,9B
6	/новости/новость-2	Новость 2	<p>Описание новости 2</p>\nДата: 13.06.2015\nАнонс: Анонс новости 2	тег 2, тег 3	'-2':3A '13.06.2015':10 '2':5C,8,14,16B '3':18B 'анонс':11,12 'дат':9 'новост':1A,2A,4C,7,13 'описан':6 'тег':15B,17B
7	/новости/новость-1	Новость 1	<p>Описание новости 1</p>\nДата: 12.06.2015\nАнонс: Анонс новости 1	тег 1, тег 2	'-1':3A '1':5C,8,14,16B '12.06.2015':10 '2':18B 'анонс':11,12 'дат':9 'новост':1A,2A,4C,7,13 'описан':6 'тег':15B,17B
8	/вопрос-ответ	Вопрос-ответ	Часто задаваемые вопросы. FAQ. ЧАВО \n\n\nВопрос 1 Ответ 1\n\nВопрос 2 Ответ 2	вопрос-ответ, чаво, часто задаваемые вопросы, faq, F.A.Q., ответы на вопросы, вопросы и ответы, ФАК	'1':13,15 '2':17,19 'f.a.q':28B 'faq':10,27B 'вопрос':2A,5C,9,12,16,21B,26B,31B,32B 'вопрос-ответ':1A,4C,20B 'задава':8,25B 'ответ':3A,6C,14,18,22B,29B,34B 'фак':35B 'чав':11,23B 'част':7,24B
9	/поиск	Поиск по сайту	Здесь вы можете воспользоваться поиском чтобы найти необходимую информацию.	поиск по сайту, найти, поиск	'воспользова':8 'информац':13 'может':7 'найт':11,17B 'необходим':12 'поиск':1A,2C,9,14B,18B 'сайт':4C,16B
\.


--
-- Name: search_index_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('search_index_seq', 9, true);


--
-- Data for Name: search_index_tags; Type: TABLE DATA; Schema: core; Owner: -
--

COPY search_index_tags ("Index_ID", "Tags_ID") FROM stdin;
1	1
2	2
2	3
3	4
4	4
4	5
4	6
5	7
5	8
5	9
6	5
6	6
7	4
7	5
8	10
8	11
8	12
8	13
8	14
8	15
8	16
8	17
9	18
9	19
9	20
\.


--
-- Data for Name: search_tags; Type: TABLE DATA; Schema: core; Owner: -
--

COPY search_tags ("ID", "Name", "Count") FROM stdin;
1	тэг 3	1
2	статьи	1
3	полезные статьи	1
7	новости	1
8	новости сайта	1
9	последние новости	1
6	тег 3	2
4	тег 1	3
5	тег 2	3
10	вопрос-ответ	1
11	чаво	1
12	часто задаваемые вопросы	1
13	faq	1
14	F.A.Q.	1
15	ответы на вопросы	1
16	вопросы и ответы	1
17	ФАК	1
18	поиск по сайту	1
19	найти	1
20	поиск	1
\.


--
-- Name: search_tags_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('search_tags_seq', 20, true);


--
-- Data for Name: seo_redirect; Type: TABLE DATA; Schema: core; Owner: -
--

COPY seo_redirect ("ID", "From", "To", "Location") FROM stdin;
\.


--
-- Name: seo_redirect_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('seo_redirect_seq', 20, true);


--
-- Data for Name: seo_url; Type: TABLE DATA; Schema: core; Owner: -
--

COPY seo_url ("ID", "Url", "Title", "Keywords", "Description") FROM stdin;
\.


--
-- Name: seo_url_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('seo_url_seq', 11, true);


--
-- Data for Name: task; Type: TABLE DATA; Schema: core; Owner: -
--

COPY task ("ID", "From", "To", "Name", "Content", "Status", "Date_Create", "Date_Require", "Date_Done", "Date_Fail", "Note") FROM stdin;
\.


--
-- Name: task_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('task_seq', 2, true);


--
-- Data for Name: text; Type: TABLE DATA; Schema: core; Owner: -
--

COPY text ("ID", "Name", "Identified", "Value", "Module_ID") FROM stdin;
8	Содержимое страницы 404	404_content	<p>Запрашиваемой страницы не существует. Возможно страница была перемещена или удалена с сайта. Проверьте правильность указания адреса.</p>\r\n<p>Попробуйте воспользоваться <strong><a href="/поиск">поиском</a> </strong>или <strong><a href="/карта-сайта">картой сайта</a></strong> (на карте сайта указаны все страницы, которые только могут быть на нашем сайте), чтобы найти необходимую страницу.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>	9
35	Главная страница. Содержание	home_content	<p>Добро пожаловать</p>	1
36	Страница 404. Содержание	404_content	<p>Страница не найдена.</p>	1
37	Страница 403. Содержание	403_content	<p>Доступ запрещён.</p>	1
7	Содержание главной страницы	home_content	<p>Главная страница сайта</p>	9
9	Содежимое страницы 403	403_content	<p>Доступ запрещён.&nbsp; </p>	9
32	Главная страница. Тэг meta name="description"	home_description		10
33	Страница 404. Тэг meta name="description"	404_description		10
34	Страница 403. meta name="description"	403_description		10
\.


--
-- Name: text_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('text_seq', 37, true);


--
-- Data for Name: user; Type: TABLE DATA; Schema: core; Owner: -
--

COPY "user" ("ID", "Name", "Email", "Password", "Group_ID", "Active", "Password_Change_Code", "Password_Change_Date", "Salt", "Visit_Last_Admin") FROM stdin;
19	Оператор	info@example.com	93cb92cff46d8086432815fc96b88dc1	1	t	\N	2015-06-14 02:14:05.936947	916b	#_service/module
\.


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_group ("ID", "Name") FROM stdin;
1	Операторы
\.


--
-- Data for Name: user_group_priv; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_group_priv ("Admin_ID", "Group_ID") FROM stdin;
65	1
66	1
67	1
68	1
69	1
50	1
51	1
52	1
53	1
54	1
56	1
57	1
58	1
59	1
60	1
61	1
62	1
63	1
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
-- Name: user_group_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('user_group_seq', 20, true);


--
-- Name: user_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('user_seq', 19, true);


--
-- Data for Name: user_session; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_session ("ID", "Date", "IP", "Browser", "User_ID") FROM stdin;
\.


SET search_path = public, pg_catalog;

--
-- Data for Name: articles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY articles ("ID", "Date", "Title", "Url", "Anons", "Content", "Tags", "Last_Modified") FROM stdin;
2	2015-06-13	Статья 2	статья-2	Анонс статьи 2	<p>Описание статьи 2</p>	тег 1	2015-06-14 02:10:33.46792
1	2015-06-12	Статья 1	статья-1	Анонс статьи 1	<p>Описание статьи 1</p>	тег 1, тег 2, тег 3	2015-06-14 02:10:48.063252
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
3	ЧАВо	/вопрос-ответ	\N	1	4
4	Главная	/	\N	1	1
1	Новости	/новости	\N	1	2
2	Статьи	/статьи	\N	1	3
5	Поиск	/поиск	\N	1	5
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
2	2015-06-13	Новость 2	новость-2	Анонс новости 2	<p>Описание новости 2</p>	тег 2, тег 3	2015-06-14 02:11:29.758717
1	2015-06-12	Новость 1	новость-1	Анонс новости 1	<p>Описание новости 1</p>	тег 1, тег 2	2015-06-14 02:11:40.15436
\.


--
-- Name: news_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('news_seq', 2, true);


--
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: -
--

COPY page ("ID", "Name", "Url", "Content", "Parent", "Tags", "Last_Modified", "Html_ID") FROM stdin;
1	Страница 1	страница-1	<p>Первая страница</p>	\N	тэг 3	2015-06-14 02:16:33.307194	\N
\.


--
-- Name: page_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('page_seq', 1, true);


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
-- Name: ajax_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ajax
    ADD CONSTRAINT "ajax_PK" PRIMARY KEY ("ID");


--
-- Name: ajax_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ajax
    ADD CONSTRAINT "ajax_UN_Identified" UNIQUE ("Identified", "Module_ID");


--
-- Name: ajax_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ajax
    ADD CONSTRAINT "ajax_UN_Name" UNIQUE ("Name", "Module_ID");


--
-- Name: exe_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_PK" PRIMARY KEY ("ID");


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
-- Name: packjs_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY packjs
    ADD CONSTRAINT "packjs_PK" PRIMARY KEY ("ID");


--
-- Name: packjs_UN_Identified; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY packjs
    ADD CONSTRAINT "packjs_UN_Identified" UNIQUE ("Identified");


--
-- Name: packjs_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY packjs
    ADD CONSTRAINT "packjs_UN_Name" UNIQUE ("Name");


--
-- Name: packjs_depend_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY packjs_depend
    ADD CONSTRAINT "packjs_depend_PK" PRIMARY KEY ("Packjs_ID", "Depend_ID");


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
-- Name: search_index_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY search_index
    ADD CONSTRAINT "search_index_PK" PRIMARY KEY ("ID");


--
-- Name: search_index_UN_Url; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY search_index
    ADD CONSTRAINT "search_index_UN_Url" UNIQUE ("Url");


--
-- Name: search_index_tags_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY search_index_tags
    ADD CONSTRAINT "search_index_tags_PK" PRIMARY KEY ("Index_ID", "Tags_ID");


--
-- Name: search_tags_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY search_tags
    ADD CONSTRAINT "search_tags_PK" PRIMARY KEY ("ID");


--
-- Name: search_tags_UN_Name; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY search_tags
    ADD CONSTRAINT "search_tags_UN_Name" UNIQUE ("Name");


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
-- Name: task_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY task
    ADD CONSTRAINT "task_PK" PRIMARY KEY ("ID");


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
-- Name: user_group_priv_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY user_group_priv
    ADD CONSTRAINT "user_group_priv_PK" PRIMARY KEY ("Admin_ID", "Group_ID");


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


SET search_path = core, pg_catalog;

--
-- Name: exe_UN_Identified; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "exe_UN_Identified" ON exe USING btree (lower(("Identified")::pg_catalog.text), "Module_ID");


--
-- Name: search_index_FTS; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE INDEX "search_index_FTS" ON search_index USING gin ("FTS");


--
-- Name: search_index_upd; Type: TRIGGER; Schema: core; Owner: -
--

CREATE TRIGGER search_index_upd BEFORE INSERT OR UPDATE ON search_index FOR EACH ROW EXECUTE PROCEDURE search_index_upd_trigger();


--
-- Name: admin_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY admin
    ADD CONSTRAINT "admin_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: ajax_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY ajax
    ADD CONSTRAINT "ajax_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: exe_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY exe
    ADD CONSTRAINT "exe_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: inc_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY inc
    ADD CONSTRAINT "inc_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: packjs_depend_FK_Depend_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY packjs_depend
    ADD CONSTRAINT "packjs_depend_FK_Depend_ID" FOREIGN KEY ("Depend_ID") REFERENCES packjs("ID") ON DELETE CASCADE;


--
-- Name: packjs_depend_FK_Packjs_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY packjs_depend
    ADD CONSTRAINT "packjs_depend_FK_Packjs_ID" FOREIGN KEY ("Packjs_ID") REFERENCES packjs("ID") ON DELETE CASCADE;


--
-- Name: param_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY param
    ADD CONSTRAINT "param_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: proc_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY proc
    ADD CONSTRAINT "proc_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: search_index_tags_FK_Index_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY search_index_tags
    ADD CONSTRAINT "search_index_tags_FK_Index_ID" FOREIGN KEY ("Index_ID") REFERENCES search_index("ID") ON DELETE CASCADE;


--
-- Name: search_index_tags_FK_Tags_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY search_index_tags
    ADD CONSTRAINT "search_index_tags_FK_Tags_ID" FOREIGN KEY ("Tags_ID") REFERENCES search_tags("ID") ON DELETE CASCADE;


--
-- Name: task_FK_From; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY task
    ADD CONSTRAINT "task_FK_From" FOREIGN KEY ("From") REFERENCES "user"("ID") ON DELETE CASCADE;


--
-- Name: task_FK_To; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY task
    ADD CONSTRAINT "task_FK_To" FOREIGN KEY ("To") REFERENCES "user"("ID") ON DELETE CASCADE;


--
-- Name: text_FK_Module_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY text
    ADD CONSTRAINT "text_FK_Module_ID" FOREIGN KEY ("Module_ID") REFERENCES module("ID");


--
-- Name: user_FK_Group_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "user_FK_Group_ID" FOREIGN KEY ("Group_ID") REFERENCES user_group("ID") ON DELETE CASCADE;


--
-- Name: user_group_priv_FK_Admin_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY user_group_priv
    ADD CONSTRAINT "user_group_priv_FK_Admin_ID" FOREIGN KEY ("Admin_ID") REFERENCES admin("ID") ON DELETE CASCADE;


--
-- Name: user_group_priv_FK_Group_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY user_group_priv
    ADD CONSTRAINT "user_group_priv_FK_Group_ID" FOREIGN KEY ("Group_ID") REFERENCES user_group("ID") ON DELETE CASCADE;


--
-- Name: user_session_FK_User_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY user_session
    ADD CONSTRAINT "user_session_FK_User_ID" FOREIGN KEY ("User_ID") REFERENCES "user"("ID") ON DELETE CASCADE;


SET search_path = public, pg_catalog;

--
-- Name: menu_FK_Menu_ID; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_FK_Menu_ID" FOREIGN KEY ("Menu_ID") REFERENCES menu("ID") ON DELETE CASCADE;


--
-- Name: menu_item_FK_Parent; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_FK_Parent" FOREIGN KEY ("Parent") REFERENCES menu_item("ID") ON DELETE CASCADE;


--
-- Name: page_FK_Html_ID; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_FK_Html_ID" FOREIGN KEY ("Html_ID") REFERENCES core.html("ID") ON DELETE SET NULL;


--
-- Name: page_FK_Parent; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_FK_Parent" FOREIGN KEY ("Parent") REFERENCES page("ID") ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

