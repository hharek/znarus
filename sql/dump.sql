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

ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_item_FK_Parent";
ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_item_FK_Menu_ID";
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
ALTER TABLE ONLY core.page DROP CONSTRAINT "page_FK_Parent";
ALTER TABLE ONLY core.page DROP CONSTRAINT "page_FK_Html_ID";
ALTER TABLE ONLY core.packjs_depend DROP CONSTRAINT "packjs_depend_FK_Packjs_ID";
ALTER TABLE ONLY core.packjs_depend DROP CONSTRAINT "packjs_depend_FK_Depend_ID";
ALTER TABLE ONLY core.inc DROP CONSTRAINT "inc_FK_Module_ID";
ALTER TABLE ONLY core.exe DROP CONSTRAINT "exe_FK_Module_ID";
ALTER TABLE ONLY core.ajax DROP CONSTRAINT "ajax_FK_Module_ID";
ALTER TABLE ONLY core.admin DROP CONSTRAINT "admin_FK_Module_ID";
DROP TRIGGER search_index_upd ON core.search_index;
SET search_path = public, pg_catalog;

DROP INDEX public."menu_item_UN1_NULL";
DROP INDEX public."menu_item_UN1";
DROP INDEX public."menu_UN_1";
SET search_path = core, pg_catalog;

DROP INDEX core."search_index_FTS";
DROP INDEX core."page_UN2_NULL";
DROP INDEX core."page_UN2";
DROP INDEX core."page_UN1_NULL";
DROP INDEX core."page_UN1";
DROP INDEX core."exe_UN_Identified";
SET search_path = public, pg_catalog;

ALTER TABLE ONLY public.menu_item DROP CONSTRAINT "menu_item_PK";
ALTER TABLE ONLY public.menu DROP CONSTRAINT "menu_PK";
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
ALTER TABLE ONLY core.search_log DROP CONSTRAINT "search_log_PK";
ALTER TABLE ONLY core.search_index_tags DROP CONSTRAINT "search_index_tags_PK";
ALTER TABLE ONLY core.search_index DROP CONSTRAINT "search_index_UN_Url";
ALTER TABLE ONLY core.search_index DROP CONSTRAINT "search_index_PK";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_UN_Name";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_UN_Identified";
ALTER TABLE ONLY core.proc DROP CONSTRAINT "proc_PK";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_UN_Name";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_UN_Identified";
ALTER TABLE ONLY core.param DROP CONSTRAINT "param_PK";
ALTER TABLE ONLY core.page DROP CONSTRAINT "page_PK";
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

ALTER TABLE public.menu_item ALTER COLUMN "Order" DROP DEFAULT;
ALTER TABLE public.menu_item ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE public.menu ALTER COLUMN "ID" DROP DEFAULT;
SET search_path = core, pg_catalog;

ALTER TABLE core.user_group ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core."user" ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.text ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.task ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.seo_url ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.seo_redirect ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.search_tags ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.search_log ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.search_index ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.proc ALTER COLUMN "Order" DROP DEFAULT;
ALTER TABLE core.proc ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.param ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.page ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.packjs ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.module ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.inc ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.html ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.exe ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.ajax ALTER COLUMN "ID" DROP DEFAULT;
ALTER TABLE core.admin ALTER COLUMN "Sort" DROP DEFAULT;
ALTER TABLE core.admin ALTER COLUMN "ID" DROP DEFAULT;
SET search_path = public, pg_catalog;

DROP SEQUENCE public.menu_seq;
DROP SEQUENCE public.menu_item_seq;
DROP TABLE public.menu_item;
DROP TABLE public.menu;
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
DROP SEQUENCE core.search_log_seq;
DROP TABLE core.search_log;
DROP TABLE core.search_index_tags;
DROP SEQUENCE core.search_index_seq;
DROP TABLE core.search_index;
DROP SEQUENCE core.proc_seq;
DROP TABLE core.proc;
DROP SEQUENCE core.param_seq;
DROP TABLE core.param;
DROP SEQUENCE core.page_seq;
DROP TABLE core.page;
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
DROP FUNCTION core.text_get(module_identified character varying, identified character varying);
DROP FUNCTION core.show_index("table" character varying);
DROP FUNCTION core.seo_url_by_url(url character varying);
DROP FUNCTION core.seo_redirect_get_by_from("from" pg_catalog.text);
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
DROP FUNCTION core.ajax_by_identified(module_identified character varying, ajax_identified character varying);
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
    'json',
    'html',
    'text',
    'json_page'
);


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
-- Name: ajax_by_identified(character varying, character varying); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION ajax_by_identified(module_identified character varying, ajax_identified character varying) RETURNS TABLE("ID" integer, "Name" character varying, "Identified" character varying, "Data_Type" ajax_data_type, "Get" integer, "Post" integer, "Cache" integer, "Module_ID" integer, "Module_Identified" character varying)
    LANGUAGE plpgsql
    AS $_$

BEGIN

	RETURN QUERY SELECT

		"a"."ID",

		"a"."Name",

		"a"."Identified",

		"a"."Data_Type",

		"a"."Get"::int,

		"a"."Post"::int,

		"a"."Cache"::int,

		"m"."ID" as "Module_ID",

		"m"."Identified" as "Module_Identified"

	FROM

		"ajax" as "a",

		"module" as "m"

	WHERE

		"a"."Identified" = $2 AND

		"a"."Active" = true AND

		"a"."Module_ID" = "m"."ID" AND

		"m"."Identified" = $1 AND

		"m"."Active" = true;

END;

$_$;


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

CREATE FUNCTION seo_redirect_all() RETURNS TABLE("ID" integer, "From" pg_catalog.text, "To" pg_catalog.text, "Location" integer)
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
-- Name: seo_redirect_get_by_from(pg_catalog.text); Type: FUNCTION; Schema: core; Owner: -
--

CREATE FUNCTION seo_redirect_get_by_from("from" pg_catalog.text) RETURNS TABLE("ID" integer, "From" pg_catalog.text, "To" pg_catalog.text, "Location" integer)
    LANGUAGE plpgsql
    AS $_$

BEGIN

	RETURN QUERY SELECT

		"r"."ID",

		"r"."From",

		"r"."To",

		"r"."Location"::int

	FROM

		"seo_redirect" as "r"

	WHERE

		"r"."From" = $1;

END;

$_$;


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
    "Data_Type" ajax_data_type NOT NULL,
    "Module_ID" integer NOT NULL,
    "Get" boolean,
    "Post" boolean,
    "Active" boolean DEFAULT true NOT NULL,
    "Cache" boolean DEFAULT false NOT NULL
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

COMMENT ON COLUMN ajax."Data_Type" IS 'Тип возвращаемых данных (json,html,text,json_page)';


--
-- Name: COLUMN ajax."Module_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Module_ID" IS 'Привязка к модулю';


--
-- Name: COLUMN ajax."Get"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Get" IS 'Обработка GET данных (если json_page)';


--
-- Name: COLUMN ajax."Post"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Post" IS 'Обработка POST данных (если json_page)';


--
-- Name: COLUMN ajax."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Active" IS 'Активность';


--
-- Name: COLUMN ajax."Cache"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN ajax."Cache" IS 'Использовать кэширование';


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
-- Name: page; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE page (
    "ID" integer NOT NULL,
    "Name" character varying(255) NOT NULL,
    "Url" character varying(255) NOT NULL,
    "Content" pg_catalog.text,
    "Parent" integer,
    "Tags" pg_catalog.text,
    "Html_ID" integer,
    "Last_Modified" timestamp without time zone DEFAULT now() NOT NULL,
    "Meta_Title" character varying(255),
    "Meta_Description" pg_catalog.text,
    "Meta_Keywords" pg_catalog.text,
    "Active" boolean DEFAULT true NOT NULL
);


--
-- Name: TABLE page; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE page IS 'Страницы';


--
-- Name: COLUMN page."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."ID" IS 'Порядковый номер';


--
-- Name: COLUMN page."Name"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Name" IS 'Наименование';


--
-- Name: COLUMN page."Url"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Url" IS 'Урл';


--
-- Name: COLUMN page."Content"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Content" IS 'Содержимое';


--
-- Name: COLUMN page."Parent"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Parent" IS 'Корень';


--
-- Name: COLUMN page."Tags"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Tags" IS 'Тэги';


--
-- Name: COLUMN page."Html_ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Html_ID" IS 'Привязка к основному шаблону';


--
-- Name: COLUMN page."Last_Modified"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Last_Modified" IS 'Дата последнего изменения';


--
-- Name: COLUMN page."Meta_Title"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Meta_Title" IS 'Тэг title';


--
-- Name: COLUMN page."Meta_Description"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Meta_Description" IS 'Тэг meta name=description';


--
-- Name: COLUMN page."Meta_Keywords"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Meta_Keywords" IS 'Тэг meta name=keywords';


--
-- Name: COLUMN page."Active"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN page."Active" IS 'Активность';


--
-- Name: page_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE page_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: page_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE page_seq OWNED BY page."ID";


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
-- Name: search_log; Type: TABLE; Schema: core; Owner: -; Tablespace: 
--

CREATE TABLE search_log (
    "ID" integer NOT NULL,
    "Query" character varying(255) NOT NULL,
    "Date" timestamp without time zone DEFAULT now(),
    "IP" cidr NOT NULL
);


--
-- Name: TABLE search_log; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON TABLE search_log IS 'Поиск. Логирование.';


--
-- Name: COLUMN search_log."ID"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_log."ID" IS 'Порядковый номер';


--
-- Name: COLUMN search_log."Query"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_log."Query" IS 'Запрос';


--
-- Name: COLUMN search_log."Date"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_log."Date" IS 'Дата';


--
-- Name: COLUMN search_log."IP"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN search_log."IP" IS 'IP-адрес';


--
-- Name: search_log_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE search_log_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: search_log_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE search_log_seq OWNED BY search_log."ID";


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
    "From" pg_catalog.text NOT NULL,
    "To" pg_catalog.text NOT NULL,
    "Location" boolean DEFAULT true NOT NULL,
    "Tags" character varying(255)[] DEFAULT NULL::character varying[]
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
-- Name: COLUMN seo_redirect."Location"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_redirect."Location" IS 'Делать переход на другой урл';


--
-- Name: COLUMN seo_redirect."Tags"; Type: COMMENT; Schema: core; Owner: -
--

COMMENT ON COLUMN seo_redirect."Tags" IS 'Тэги';


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
    "Password" character varying,
    "Group_ID" integer NOT NULL,
    "Active" boolean DEFAULT false NOT NULL,
    "Password_Change_Code" character varying(32),
    "Password_Change_Date" timestamp without time zone,
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
    "Url" character varying(255) NOT NULL,
    "Parent" integer,
    "Menu_ID" integer NOT NULL,
    "Order" integer NOT NULL,
    "Icon" character varying(255) NOT NULL,
    "Active" boolean DEFAULT true NOT NULL
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
-- Name: COLUMN menu_item."Order"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Order" IS 'Сортировка';


--
-- Name: COLUMN menu_item."Icon"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Icon" IS 'Иконка';


--
-- Name: COLUMN menu_item."Active"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN menu_item."Active" IS 'Активность';


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

ALTER TABLE ONLY page ALTER COLUMN "ID" SET DEFAULT nextval('page_seq'::regclass);


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

ALTER TABLE ONLY search_log ALTER COLUMN "ID" SET DEFAULT nextval('search_log_seq'::regclass);


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

ALTER TABLE ONLY menu ALTER COLUMN "ID" SET DEFAULT nextval('menu_seq'::regclass);


--
-- Name: ID; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item ALTER COLUMN "ID" SET DEFAULT nextval('menu_item_seq'::regclass);


--
-- Name: Order; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item ALTER COLUMN "Order" SET DEFAULT currval('menu_item_seq'::regclass);


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
17	Управление	ls	17	t	f	t	3	f	f
7	Управление	user	7	t	f	t	2	f	f
44	Удалить robots.txt	robots_delete	44	f	t	f	10	f	f
1	Модули	module	1	t	f	t	1	f	t
15	Сменить пароль	passwd	16	t	t	t	2	f	t
36	Добавить адрес	url_add	36	t	t	f	10	f	f
38	Удалить адрес	url_delete	38	f	t	f	10	f	f
37	Редактировать адрес	url_edit	37	t	t	f	10	f	f
39	Переадресация	redirect	39	t	f	t	10	f	f
40	Добавить переадресацию	redirect_add	40	t	t	f	10	f	f
41	Редактировать переадресацию	redirect_edit	41	t	t	f	10	f	f
42	Удалить переадресацию	redirect_delete	42	f	t	f	10	f	f
78	Правка шаблон	html_content	79	t	t	f	17	f	f
140	Задания	to	145	t	f	t	32	f	t
43	robots.txt	robots	43	t	t	t	10	f	f
81	Правка exe	exe_content	82	t	t	f	17	f	f
77	Шаблоны	html	78	t	f	t	17	f	f
80	Правка inc	inc_content	81	t	t	f	17	f	f
79	Модули	module	80	t	f	t	17	f	f
82	Управление	url	77	t	f	t	17	f	f
150	Удалить поручение	from_delete	151	f	t	f	32	f	t
23	Скачать	download	22	f	t	f	3	t	f
45	Другие страницы	other	45	t	t	t	10	f	f
149	Редактировать поручение	from_edit	150	t	t	f	32	f	t
142	Управление	list	140	t	f	t	32	f	f
143	Дать поручение	from_add	149	t	t	f	32	f	t
141	Поручения	from	148	t	f	t	32	f	t
151	Посмотреть задание	to_view	146	t	f	f	32	f	t
148	Сменить статус задания	to_status	147	t	t	f	32	f	t
146	Редактировать	edit	142	t	t	f	32	f	f
145	Добавить	add	141	t	t	t	32	f	f
147	Удалить	delete	143	f	t	f	32	f	f
238	Правка куска кода	html_part_content	238	t	t	f	17	f	f
229	Другие страницы	other_page	229	t	t	t	1	f	f
230	Главная страница	home	230	t	t	f	1	f	f
231	Страница 404	404	231	t	t	f	1	f	f
232	Страница 403	403	232	t	t	f	1	f	f
35	Продвижение	url	35	t	t	t	10	f	f
367	Отчёт	log	367	t	t	t	54	f	f
368	Отчёт. Статистика	log_stats	368	t	t	t	54	f	f
369	Отчёт. Удалить запись	log_delete	369	f	t	f	54	f	f
370	Отчёт. Статистика. Удалить запись	log_stats_delete	370	f	t	f	54	f	f
371	Управление	list	371	t	f	t	74	f	f
373	Добавить	add	372	t	t	f	74	f	f
374	Редактировать	edit	373	t	t	f	74	f	f
375	Удалить	delete	374	f	t	f	74	f	f
377	Мета	meta	376	f	t	f	74	f	f
372	Настройки	settings	377	t	t	t	74	f	f
378	Другие страницы	other	378	t	t	t	74	f	f
423	Правка меню	menu	423	t	f	t	83	f	f
424	Добавить меню	menu_add	424	t	t	f	83	f	f
425	Редактировать меню	menu_edit	425	t	t	f	83	f	f
426	Удалить меню	menu_delete	426	f	t	f	83	f	f
427	Управление	item	427	t	f	t	83	f	f
428	Добавить пункт меню	item_add	428	t	t	f	83	f	f
429	Редактировать пункт меню	item_edit	429	t	t	f	83	f	f
430	Удалить пункт меню	item_delete	430	f	t	f	83	f	f
431	Пункт меню. Сортировка	item_order	431	f	t	f	83	f	f
\.


--
-- Name: admin_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('admin_seq', 431, true);


--
-- Data for Name: ajax; Type: TABLE DATA; Schema: core; Owner: -
--

COPY ajax ("ID", "Name", "Identified", "Data_Type", "Module_ID", "Get", "Post", "Active", "Cache") FROM stdin;
25	Индексатор	indexer	text	54	\N	\N	t	f
\.


--
-- Name: ajax_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('ajax_seq', 31, true);


--
-- Data for Name: exe; Type: TABLE DATA; Schema: core; Owner: -
--

COPY exe ("ID", "Name", "Identified", "Module_ID", "Active", "Cache_Route", "Cache_Page") FROM stdin;
179	Главная страница	home	1	t	t	t
180	Страница 404	404	1	t	t	t
181	Страница 403	403	1	t	t	t
46	Карта сайта	sitemap	25	t	t	t
187	Поиск по сайту	find	54	t	t	f
211	Содержание страницы	content	74	t	t	t
212	Главная страница	home	74	t	t	t
213	Страница 404	404	74	t	t	t
214	Страница 403	403	74	t	t	t
\.


--
-- Name: exe_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('exe_seq', 224, true);


--
-- Data for Name: html; Type: TABLE DATA; Schema: core; Owner: -
--

COPY html ("ID", "Name", "Identified") FROM stdin;
5	По умолчанию	default
\.


--
-- Name: html_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('html_seq', 21, true);


--
-- Data for Name: inc; Type: TABLE DATA; Schema: core; Owner: -
--

COPY inc ("ID", "Name", "Identified", "Module_ID", "Active") FROM stdin;
39	Форма поиска	form	54	t
51	Левое меню	left	83	t
52	Левое меню для мобильной версии	left_mob	83	t
\.


--
-- Name: inc_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('inc_seq', 52, true);


--
-- Data for Name: module; Type: TABLE DATA; Schema: core; Owner: -
--

COPY module ("ID", "Name", "Identified", "Description", "Version", "Active", "Access", "Page_Info_Function") FROM stdin;
1	Сервис	_service	Сведения о модулях.\r\nСведения о системе.\r\nСведения о PHP.\r\nСведения о PostgreSQL.	1.0	t	no	\N
2	Пользователи	_user	Управление пользователями	1.0	t	no	\N
3	Проводник	_explorer	Управление статическими файлами	1.0	t	no	\N
25	Карта сайта	_sitemap		1.0	t	no	\N
32	Задачи	_task		1.0	t	no	\N
17	HTML-код	_html_code	HTML-вёрстка	1.0	t	no	\N
10	Поисковая оптимизация	_seo	Управление тегами title, meta. Правка файла robots.txt. Переадресация.	1.0	t	no	\N
54	Поиск	_search	Поиск по сайту. Два режима работы обычный и через sphinx.	1.0	t	no	_Search::page_info
74	Страницы	_page	Основной модуль для создания страниц	2.0	t	no	_Page::page_info
83	Меню	menu	Много-уровневое меню	2.0	t	no	\N
\.


--
-- Name: module_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('module_seq', 83, true);


--
-- Data for Name: packjs; Type: TABLE DATA; Schema: core; Owner: -
--

COPY packjs ("ID", "Identified", "Name", "Description", "Version", "Url", "Category") FROM stdin;
5	tinymce	TinyMCE	WYSIWYG редактор	4.1.9	http://www.tinymce.com/	editor
1	codemirror	CodeMirror	Редактор с подсветкой синтаксиса	5.0.0	http://codemirror.net/	
6	jquery_ui	jQuery UI	Библиотека для создания пользовательского интерфейса	1.11.4	https://jqueryui.com/	
2	datepick	jQuery Datepicker	Календарь для выбора даты, альтернатива jQuery-UI Datepicker. 	5.0.0.	http://keith-wood.name/datepick.html	
10	datepicker	jQuery UI Datepicker	Календарь для выбора даты	1.11.4	https://jqueryui.com/datepicker/	calendar
\.


--
-- Data for Name: packjs_depend; Type: TABLE DATA; Schema: core; Owner: -
--

COPY packjs_depend ("Packjs_ID", "Depend_ID", "Order") FROM stdin;
10	6	1
\.


--
-- Name: packjs_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('packjs_seq', 12, true);


--
-- Data for Name: page; Type: TABLE DATA; Schema: core; Owner: -
--

COPY page ("ID", "Name", "Url", "Content", "Parent", "Tags", "Html_ID", "Last_Modified", "Meta_Title", "Meta_Description", "Meta_Keywords", "Active") FROM stdin;
102	Модули	modules	<p>Модули в Znarus разделены на две категории: системные и обычные. Системные модули предоставляют базовую функциональность по CMS, и не могут быть удалены. Обычные модули предоставляют уникальную функциональность для сайта, их легко создать и можно удалить.</p>\r\n<h2>Системные модули</h2>\r\n<ul>\r\n<li><strong>Пользователи</strong> - Даёт возможность управлять пользователями и группами &laquo;Панели управления&raquo;. Также позволяет управляет доступом группы пользователей, на отдельные элементы &laquo;Панели управления&raquo;.</li>\r\n<li><strong>Проводник</strong> - Управление статическими файлами на сайте.</li>\r\n<li><strong>HTML-код</strong> - Правка файлов с шаблонам сайт. Позволяет определить по урлу, какие шаблоны влияют на отображение страницы.</li>\r\n<li><strong>Поиск</strong> - Индексирует страницы сайта и предоставляет форму для полнотекстового поиска по сайту. Может использовать PostgreSQL с полнотекстовыми индексами или движок Sphinx.</li>\r\n<li><strong>Поисковая оптимизация</strong> - Возможность правки мета-данных по странице, в зависимости от урла, установка переадресаций, правка robots.txt и др.</li>\r\n<li><strong>Сервис</strong> - Показ сведений по программному обеспечению установленному на хостинге.</li>\r\n<li><strong>Карта сайта</strong> - Создание sitemap.xml и генерация страницы &laquo;Карта-сайта&raquo;.</li>\r\n<li><strong>Задачи</strong> - Возможность раздачи задания между пользователями &laquo;Панели управления&raquo; и контроль за выполнением.</li>\r\n<li><strong>Страницы</strong> - Простой модуль для размещения страниц на сайте.</li>\r\n</ul>\r\n<h2>Обычные модули</h2>\r\n<ul>\r\n<li><strong>Меню</strong> - размещение на сайте многомерного и одномерного меню.</li>\r\n</ul>	\N	\N	\N	2017-04-24 17:39:20.680654	\N	\N	\N	t
99	Установка	install	<p>Если хостинг содержит всё необходимое программное обеспечение, необходимо:</p>\r\n<ul>\r\n<li>Создать базу PostgreSQL и поместить в неё SQL-данные лежащие в файл &laquo;sql/dump.sql&raquo;</li>\r\n<li>Поправить файл &laquo;app/conf/conf.php&raquo; согласно свои настройкам.</li>\r\n</ul>	\N		\N	2017-04-24 19:46:20.642078	\N	\N	\N	t
100	Требования к хостингу	hosting	<h2>Требования к хостингу</h2>\r\n<ul>\r\n<li>Операционная система Linux</li>\r\n<li>PHP 7</li>\r\n<li>PostgreSQL 9.4 и выше</li>\r\n</ul>\r\n<h2>Обязательные модули PHP</h2>\r\n<ul>\r\n<li>pgsql</li>\r\n<li>zip</li>\r\n<li>mbstring</li>\r\n<li>curl</li>\r\n<li>openssl</li>\r\n</ul>\r\n<h2>Модули PHP (опционально)</h2>\r\n<ul>\r\n<li>db4 или qdbm (если включено кэширование через dba)</li>\r\n<li>memcache или memcached (если включено кэширование через memcache)</li>\r\n<li>gd (если работаете с изображением)</li>\r\n<li>mysql (если поиск через sphinx)</li>\r\n</ul>	\N		\N	2017-04-24 17:45:54.493215	\N	\N	\N	t
101	Лицензия	licence	<h2>The MIT License</h2>\r\n<p class="mono">Copyright &copy; 2011 Sergeev Denis, https://github.com/hharek<br /> Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:<br /> The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.<br /> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>\r\n<hr />\r\n<h2>Лицензия MIT</h2>\r\n<p class="mono">Copyright &copy; 2011 Сергеев Денис, https://github.com/hharek<br /> Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми &laquo;Программное Обеспечение&raquo;), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение, сублицензирование и/или продажу копий Программного Обеспечения, также как и лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:<br /> Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.<br /> ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ &laquo;КАК ЕСТЬ&raquo;, БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ ГАРАНТИЯМИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ ПРАВ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО ИСКАМ О ВОЗМЕЩЕНИИ УЩЕРБА, УБЫТКОВ ИЛИ ДРУГИХ ТРЕБОВАНИЙ ПО ДЕЙСТВУЮЩИМ КОНТРАКТАМ, ДЕЛИКТАМ ИЛИ ИНОМУ, ВОЗНИКШИМ ИЗ, ИМЕЮЩИМ ПРИЧИНОЙ ИЛИ СВЯЗАННЫМ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ.</p>	\N		\N	2017-04-24 17:54:05.687663	\N	\N	\N	t
104	Панель разработки	constr	<p>&laquo;Панель разработки&raquo; позволяет увидеть разработчику сайта текущие установленные модули и другую техническую информацию по сайту.</p>	\N		\N	2017-04-24 17:54:52.534593	\N	\N	\N	t
103	Панель управления	admin	<p>&laquo;Панель управления&raquo; предназначена для создания и управления страницами сайта. Страница &laquo;Панели управления&raquo; расположена по урлу &laquo;/admin/&raquo; (можно поменять в настройках). Для доступа к ней нужно указать &laquo;E-mail&raquo; и &laquo;Пароль&raquo;. Обладая правами &laquo;Администратора&raquo; (по умолчанию root) можно через &laquo;Панель управления&raquo; создать группы и пользователей. Для каждой группы пользователей можно задать права на осуществления тех или иных действий. В CMS есть пользователь &laquo;Администратор&raquo;, который обладает всеми привилегиями.</p>\r\n<p>На разных сайтах функционал &laquo;Панели управления&raquo; отличается, это зависит от количества установленных модулей на сайте. Панель управления позволяет создавать страницы без знания HTML и участия программиста, для этого в ней присутствует редактор (WYSIWYG-редактор) с удобным интерфейсом напоминающий Word или LibreOffice Writer. Через редактор оператор сможет загружать рисунки и править тексты. на сайте. Панель управления защищена от популярных атак типа CSRF и SQL-инъекции.</p>	\N		\N	2017-04-24 17:45:09.917549	\N	\N	\N	t
98	Описание	about	<p><strong>Znarus</strong> - это система для создания и управления вашим сайтом. Для управления содержимым сайта используется &laquo;Панель управления&raquo;, а для управления модулями CMS используется &laquo;Панель разработчика&raquo;. Znarus является свободным программным обеспечением с открытым исходным кодом и открытой лицензией MIT. В основе её используется язык программирование PHP и система управления базой данных PostgreSQL.</p>\r\n<h2>Преимущества:</h2>\r\n<ul>\r\n<li>Быстрая, за счёт использования</li>\r\n<li>Удобный и понятный интерфейс панели управления</li>\r\n<li>Обладает встроенными инструментами для развёртывания поиска по сайту</li>\r\n<li>Хранит историю ранее изменённых документов</li>\r\n<li>Встроенный LESS-обработчик для CSS</li>\r\n<li>Встроенные модуля для работы с SEO</li>\r\n<li>Панель разработчика позволяет понять какое кол-во модулей установлено на сайте</li>\r\n<li>Возможность встраивать CMS в любой html-код.</li>\r\n</ul>	\N		\N	2017-04-24 17:56:38.632373	\N	\N	\N	t
\.


--
-- Name: page_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('page_seq', 104, true);


--
-- Data for Name: param; Type: TABLE DATA; Schema: core; Owner: -
--

COPY param ("ID", "Name", "Identified", "Type", "Value", "Module_ID") FROM stdin;
126	Sphinx. Хост	sphinx_host	string	127.0.0.1	54
115	Главная страница. Тэги	home_tags	string	Главная страница, Добро пожаловать	1
118	Страница 403. Тэги	403_tags	string	Доступ запрещён, Ошибка 403, 403 Forbidden, 403 error	1
119	Страница 404. Тэги	404_tags	string	Страница не найдена, Ошибка 404, Not Found, 404 error	1
116	Главная страница. Заголовок	home_title	string	Добро пожаловать	1
117	Страница 404. Заголовок	404_title	string	Страница не найдена	1
120	Страница 403. Заголовок	403_title	string	Доступ запрещён	1
125	Кол-во результатов на страницу	limit	int	10	54
127	Sphinx. Порт	sphinx_port	int	9312	54
128	Sphinx. Наименование индекса	sphinx_index	string	znarus	54
124	Тип поискового движка (pgsql, sphinx)	type	string	pgsql	54
34	Файл CSS по умолчанию	css_default	string	/css/admin.css	\N
5	Страница 403. Модуль	403_module	string	_page	\N
110	Страница 404. Урл админки	404_admin_url	string	#_page/other#tab_404	\N
187	Показывать поле «Родитель» в админке	admin_parent_show	bool	1	74
1	Главная страница. Модуль	home_module	string	_page	\N
3	Страница 404. Модуль	404_module	string	_page	\N
190	Страница 404. Заголовок	404_title	string	Страница не найдена	74
2	Главная страница. Exe	home_exe	string	home	\N
197	Страница 404. Идентификатор шаблона	404_html_identified	string		74
4	Страница 404. Exe	404_exe	string	404	\N
191	Страница 403. Заголовок	403_title	string	Доступ запрещён	74
186	Показывать поле «Шаблон» в админке	admin_html_show	bool	1	74
6	Страница 403. Exe	403_exe	string	403	\N
19	Шаблон по умолчанию	html_default	string	default	\N
111	Страница 403. Урл админки	403_admin_url	string	#_page/other#tab_403	\N
109	Главная страница. Урл админки	home_admin_url	string	#_page/other#tab_home	\N
196	Страница 403. Идентификатор шаблона	403_html_identified	string		74
105	Страница 404. Тэг title	404_title	string		10
106	Страница 404. Тэг meta name="keywords"	404_keywords	string		10
107	Страница 403. Тэг title	403_title	string		10
108	Страница 403. Тэг meta name="keywords"	403_keywords	string		10
183	Урл формировать автоматически	url_auto	bool	0	74
185	Префикс для урлов создаваемых автоматически	url_auto_prefix	string	a	74
88	Последняя посещаемая страница в конструкторе	constr_visit_last	string	#module/edit?id=74	\N
193	Длина автоматического урла	url_auto_length	int	3	74
103	Главная страница. Тэг title	home_title	string	CMS Znarus	10
104	Главная страница. Тэг meta name="keywords"	home_keywords	string	znarus, cms, создание сайта	10
189	Главная. Заголовок	home_title	string	CMS Znarus	74
198	Главная страница. Идентификатор шаблона	home_html_identified	string		74
194	Формировать урл на основе транслита имени	url_translit	bool	1	74
184	Транслитерация с русскими и англ. символами	url_translit_rus	bool	0	74
195	Делать транслитерацию при редактировании	url_translit_edit	bool	0	74
89	Последняя посещаемая страница в адмнике root-ом	admin_root_visit_last	string	#_page/list	\N
192	Урл. Иерархический	url_hierarchy	bool	0	74
86	Сессия в конструкторе	constr_session	string	a:5:{s:2:"ID";s:32:"30286f2ae6391a94e0b7c44e1971a362";s:2:"IP";s:32:"bc7f5a2b4952eae9d61dca557314e40e";s:7:"Browser";s:32:"f005c4cebeab0f0ea05f396c78189705";s:4:"Date";s:19:"2017-04-24 19:17:29";s:7:"User_ID";i:0;}	\N
\.


--
-- Name: param_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('param_seq', 207, true);


--
-- Data for Name: proc; Type: TABLE DATA; Schema: core; Owner: -
--

COPY proc ("ID", "Name", "Identified", "Module_ID", "Active", "Order") FROM stdin;
4	Теги title, meta	tag	10	t	8
\.


--
-- Name: proc_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('proc_seq', 32, true);


--
-- Data for Name: search_index; Type: TABLE DATA; Schema: core; Owner: -
--

COPY search_index ("ID", "Url", "Title", "Content", "Tags", "FTS") FROM stdin;
1	/s	Поиск по сайту	Здесь вы можете воспользоваться поиском чтобы найти необходимую информацию.	поиск по сайту, найти, поиск	'/s':1A 'воспользова':8 'информац':13 'может':7 'найт':11,17B 'необходим':12 'поиск':2C,9,14B,18B 'сайт':4C,16B
2	/licence	Лицензия	<h2>The MIT License</h2>\r\n<p class="mono">Copyright &copy; 2011 Sergeev Denis, https://github.com/hharek<br /> Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:<br /> The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.<br /> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>\r\n<hr />\r\n<h2>Лицензия MIT</h2>\r\n<p class="mono">Copyright &copy; 2011 Сергеев Денис, https://github.com/hharek<br /> Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми &laquo;Программное Обеспечение&raquo;), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение, сублицензирование и/или продажу копий Программного Обеспечения, также как и лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:<br /> Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.<br /> ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ &laquo;КАК ЕСТЬ&raquo;, БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ ГАРАНТИЯМИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ ПРАВ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО ИСКАМ О ВОЗМЕЩЕНИИ УЩЕРБА, УБЫТКОВ ИЛИ ДРУГИХ ТРЕБОВАНИЙ ПО ДЕЙСТВУЮЩИМ КОНТРАКТАМ, ДЕЛИКТАМ ИЛИ ИНОМУ, ВОЗНИКШИМ ИЗ, ИМЕЮЩИМ ПРИЧИНОЙ ИЛИ СВЯЗАННЫМ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ.</p>		'/hharek':12,183 '/licence':1A '2011':7,178 'action':150 'and/or':55 'aris':156 'associ':30 'author':134 'charg':19 'claim':142 'condit':78 'connect':162 'contract':152 'copi':25,49,57,92 'copyright':6,81,136,177 'damag':143 'deal':36,171 'deni':9 'distribut':53 'document':31 'event':131 'express':110 'file':32 'fit':122 'follow':77 'free':17 'furnish':70 'github.com':11,182 'github.com/hharek':10,181 'grant':16 'herebi':15 'holder':137 'impli':112 'includ':42,89,113 'kind':109 'liabil':146 'liabl':139 'licens':5 'limit':44,116 'merchant':121 'merg':51 'mit':4,176 'modifi':50 'noninfring':128 'notic':82,86 'obtain':23 'otherwis':155 'particular':125 'permiss':13,85 'permit':63 'person':22,64 'portion':95 'provid':102 'publish':52 'purpos':126 'restrict':41 'right':46 'sell':56 'sergeev':8 'shall':87,132 'softwar':28,34,39,60,68,98,100,165,174 'subject':74 'sublicens':54 'substanti':94 'tort':153 'use':48,168 'warranti':106,119 'whether':147 'without':40,43,105 'автор':293 'авторск':241 'безвозмездн':201 'включ':207,248,273 'возмещен':302 'возникш':314 'выражен':270 'выш':238 'гарант':268,277 'дальн':197 'дан':184,190,230,244,255,258 'действ':309,329 'деликт':311 'денис':180 'добавлен':214 'документац':195 'должн':246 'друг':306 'значим':253 'изменен':213 'именуем':198 'имеющ':316 'ин':313,328 'иск':300 'использова':202 'использован':211,324 'как':266,291 'каких-либ':265 'конкретн':283 'контракт':310 'коп':189,221,251 'копирован':212 'котор':228 'либ':267 'лиц':187,227 'лиценз':2C,175,185 'назначен':284 'нарушен':287 'неограничен':208 'несут':297 'обеспечен':192,200,204,223,232,257,260,322,326,332 'ограничен':206 'ограничив':276 'ответствен':298 'отсутств':286 'подразумева':272 'получ':188 'прав':209,242,288 'правообладател':295 'предоставля':229,261 'пригодн':279 'причин':317 'программн':191,199,203,222,231,256,259,321,325,331 'продаж':220 'публикац':215 'разреша':186 'распространен':216 'связа':319 'серге':179 'след':235 'случа':292 'соблюден':234 'соответств':280 'сопутств':194 'сублицензирован':217 'такж':224 'товарн':278 'требован':307 'убытк':304 'уведомлен':239 'указа':237 'услов':236,245 'ущерб':303 'част':254 'явн':269
3	/modules	Модули	<p>Модули в Znarus разделены на две категории: системные и обычные. Системные модули предоставляют базовую функциональность по CMS, и не могут быть удалены. Обычные модули предоставляют уникальную функциональность для сайта, их легко создать и можно удалить.</p>\r\n<h2>Системные модули</h2>\r\n<ul>\r\n<li><strong>Пользователи</strong> - Даёт возможность управлять пользователями и группами &laquo;Панели управления&raquo;. Также позволяет управляет доступом группы пользователей, на отдельные элементы &laquo;Панели управления&raquo;.</li>\r\n<li><strong>Проводник</strong> - Управление статическими файлами на сайте.</li>\r\n<li><strong>HTML-код</strong> - Правка файлов с шаблонам сайт. Позволяет определить по урлу, какие шаблоны влияют на отображение страницы.</li>\r\n<li><strong>Поиск</strong> - Индексирует страницы сайта и предоставляет форму для полнотекстового поиска по сайту. Может использовать PostgreSQL с полнотекстовыми индексами или движок Sphinx.</li>\r\n<li><strong>Поисковая оптимизация</strong> - Возможность правки мета-данных по странице, в зависимости от урла, установка переадресаций, правка robots.txt и др.</li>\r\n<li><strong>Сервис</strong> - Показ сведений по программному обеспечению установленному на хостинге.</li>\r\n<li><strong>Карта сайта</strong> - Создание sitemap.xml и генерация страницы &laquo;Карта-сайта&raquo;.</li>\r\n<li><strong>Задачи</strong> - Возможность раздачи задания между пользователями &laquo;Панели управления&raquo; и контроль за выполнением.</li>\r\n<li><strong>Страницы</strong> - Простой модуль для размещения страниц на сайте.</li>\r\n</ul>\r\n<h2>Обычные модули</h2>\r\n<ul>\r\n<li><strong>Меню</strong> - размещение на сайте многомерного и одномерного меню.</li>\r\n</ul>		'/modules':1A 'cms':19 'html':67 'html-код':66 'postgresql':98 'robots.txt':121 'sitemap.xml':136 'sphinx':104 'znarus':5 'базов':16 'влия':80 'возможн':42,107,144 'выполнен':154 'генерац':138 'групп':46,53 'дан':111 'даёт':41 'две':8 'движок':103 'доступ':52 'др':123 'зависим':115 'задан':146 'задач':143 'индекс':101 'индексир':85 'использова':97 'как':78 'карт':133,141 'карта-сайт':140 'категор':9 'код':68 'контрол':152 'легк':33 'мен':165,172 'мет':110 'мета-да':109 'многомерн':169 'могут':22 'модул':2C,3,14,26,39,157,164 'обеспечен':129 'обычн':12,25,163 'одномерн':171 'определ':75 'оптимизац':106 'отдельн':56 'отображен':82 'панел':47,58,149 'переадресац':119 'позволя':50,74 'поиск':84,93 'поисков':105 'показ':125 'полнотекстов':92,100 'пользовател':40,44,54,148 'правк':69,108,120 'предоставля':15,27,89 'проводник':60 'программн':128 'прост':156 'раздач':145 'раздел':6 'размещен':159,166 'сайт':31,65,73,87,95,134,142,162,168 'сведен':126 'сервис':124 'системн':10,13,38 'созда':34 'создан':135 'статическ':62 'страниц':83,86,113,139,155,160 'такж':49 'удал':24,37 'уникальн':28 'управлен':48,59,61,150 'управля':43,51 'урл':77,117 'установк':118 'установлен':130 'файл':63,70 'форм':90 'функциональн':17,29 'хостинг':132 'шаблон':72,79 'элемент':57
4	/about	Описание	<p><strong>Znarus</strong> - это система для создания и управления вашим сайтом. Для управления содержимым сайта используется &laquo;Панель управления&raquo;, а для управления модулями CMS используется &laquo;Панель разработчика&raquo;. Znarus является свободным программным обеспечением с открытым исходным кодом и открытой лицензией MIT. В основе её используется язык программирование PHP и система управления базой данных PostgreSQL.</p>\r\n<h2>Преимущества:</h2>\r\n<ul>\r\n<li>Быстрая, за счёт использования</li>\r\n<li>Удобный и понятный интерфейс панели управления</li>\r\n<li>Обладает встроенными инструментами для развёртывания поиска по сайту</li>\r\n<li>Хранит историю ранее изменённых документов</li>\r\n<li>Встроенный LESS-обработчик для CSS</li>\r\n<li>Встроенные модуля для работы с SEO</li>\r\n<li>Панель разработчика позволяет понять какое кол-во модулей установлено на сайте</li>\r\n<li>Возможность встраивать CMS в любой html-код.</li>\r\n</ul>		'/about':1A 'cms':23,103 'css':82 'html':107 'html-код':106 'less':79 'less-обработчик':78 'mit':39 'php':46 'postgresql':52 'seo':88 'znarus':3,27 'баз':50 'быстр':54 'ваш':10 'возможн':101 'встраива':102 'встроен':65,77,83 'дан':51 'документ':76 'её':42 'изменён':75 'инструмент':66 'интерфейс':61 'использ':16,24,43 'использован':57 'истор':73 'исходн':34 'как':93 'код':35,108 'кол':95 'кол-в':94 'лиценз':38 'люб':105 'модул':22,84,97 'обеспечен':31 'облада':64 'обработчик':80 'описан':2C 'основ':41 'открыт':33,37 'панел':17,25,62,89 'позволя':91 'поиск':69 'поня':92 'понятн':60 'преимуществ':53 'программирован':45 'программн':30 'работ':86 'развёртыван':68 'разработчик':26,90 'ран':74 'сайт':11,15,71,100 'свободн':29 'систем':5,48 'содержим':14 'создан':7 'счёт':56 'удобн':58 'управлен':9,13,18,21,49,63 'установл':98 'хран':72 'эт':4 'явля':28 'язык':44
5	/constr	Панель разработки	<p>&laquo;Панель разработки&raquo; позволяет увидеть разработчику сайта текущие установленные модули и другую техническую информацию по сайту.</p>		'/constr':1A 'друг':14 'информац':16 'модул':12 'панел':2C,4 'позволя':6 'разработк':3C,5 'разработчик':8 'сайт':9,18 'текущ':10 'техническ':15 'увидет':7 'установлен':11
6	/admin	Панель управления	<p>&laquo;Панель управления&raquo; предназначена для создания и управления страницами сайта. Страница &laquo;Панели управления&raquo; расположена по урлу &laquo;/admin/&raquo; (можно поменять в настройках). Для доступа к ней нужно указать &laquo;E-mail&raquo; и &laquo;Пароль&raquo;. Обладая правами &laquo;Администратора&raquo; (по умолчанию root) можно через &laquo;Панель управления&raquo; создать группы и пользователей. Для каждой группы пользователей можно задать права на осуществления тех или иных действий. В CMS есть пользователь &laquo;Администратор&raquo;, который обладает всеми привилегиями.</p>\r\n<p>На разных сайтах функционал &laquo;Панели управления&raquo; отличается, это зависит от количества установленных модулей на сайте. Панель управления позволяет создавать страницы без знания HTML и участия программиста, для этого в ней присутствует редактор (WYSIWYG-редактор) с удобным интерфейсом напоминающий Word или LibreOffice Writer. Через редактор оператор сможет загружать рисунки и править тексты. на сайте. Панель управления защищена от популярных атак типа CSRF и SQL-инъекции.</p>		'/admin':1A,19 'cms':63 'csrf':132 'e':31 'e-mail':30 'html':93 'libreoffic':112 'mail':32 'root':40 'sql':135 'sql-инъекц':134 'word':110 'writer':113 'wysiwyg':104 'wysiwyg-редактор':103 'администратор':37,66 'атак':130 'всем':69 'групп':46,51 'действ':61 'доступ':25 'завис':79 'загружа':118 'зада':54 'защищ':127 'знан':92 'ин':60 'интерфейс':108 'инъекц':136 'кажд':50 'количеств':81 'котор':67 'модул':83 'напомина':109 'настройк':23 'нужн':28 'облад':35 'облада':68 'оператор':116 'осуществлен':57 'отлича':77 'панел':2C,4,14,43,75,86,125 'парол':34 'позволя':88 'пользовател':48,52,65 'поменя':21 'популярн':129 'прав':36,55,121 'предназнач':6 'привилег':70 'присутств':101 'программист':96 'разн':72 'располож':16 'редактор':102,105,115 'рисунк':119 'сайт':12,73,85,124 'сможет':117 'созда':45 'создава':89 'создан':8 'страниц':11,13,90 'текст':122 'тех':58 'тип':131 'удобн':107 'указа':29 'умолчан':39 'управлен':3C,5,10,15,44,76,87,126 'урл':18 'установлен':82 'участ':95 'функциона':74 'эт':78
7	/hosting	Требования к хостингу	<h2>Требования к хостингу</h2>\r\n<ul>\r\n<li>Операционная система Linux</li>\r\n<li>PHP 7</li>\r\n<li>PostgreSQL 9.4 и выше</li>\r\n</ul>\r\n<h2>Обязательные модули PHP</h2>\r\n<ul>\r\n<li>pgsql</li>\r\n<li>zip</li>\r\n<li>mbstring</li>\r\n<li>curl</li>\r\n<li>openssl</li>\r\n</ul>\r\n<h2>Модули PHP (опционально)</h2>\r\n<ul>\r\n<li>db4 или qdbm (если включено кэширование через dba)</li>\r\n<li>memcache или memcached (если включено кэширование через memcache)</li>\r\n<li>gd (если работаете с изображением)</li>\r\n<li>mysql (если поиск через sphinx)</li>\r\n</ul>		'/hosting':1A '7':12 '9.4':14 'curl':23 'db4':28 'dba':35 'gd':44 'linux':10 'mbstring':22 'memcach':36,38,43 'mysql':49 'openssl':24 'pgsql':20 'php':11,19,26 'postgresql':13 'qdbm':30 'sphinx':53 'zip':21 'включ':32,40 'выш':16 'изображен':48 'кэширован':33,41 'модул':18,25 'обязательн':17 'операцион':8 'опциональн':27 'поиск':51 'работа':46 'систем':9 'требован':2C,5 'хостинг':4C,7
8	/install	Установка	<p>Если хостинг содержит всё необходимое программное обеспечение, необходимо:</p>\r\n<ul>\r\n<li>Создать базу PostgreSQL и поместить в неё SQL-данные лежащие в файл &laquo;sql/dump.sql&raquo;</li>\r\n<li>Поправить файл &laquo;app/conf/conf.php&raquo; согласно свои настройкам.</li>\r\n</ul>		'/install':1A 'app/conf/conf.php':27 'postgresql':13 'sql':19 'sql-дан':18 'sql/dump.sql':24 'баз':12 'всё':6 'дан':20 'лежа':21 'настройк':30 'необходим':7,10 'неё':17 'обеспечен':9 'помест':15 'поправ':25 'программн':8 'сво':29 'согласн':28 'содерж':5 'созда':11 'установк':2C 'файл':23,26 'хостинг':4
\.


--
-- Name: search_index_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('search_index_seq', 8, true);


--
-- Data for Name: search_index_tags; Type: TABLE DATA; Schema: core; Owner: -
--

COPY search_index_tags ("Index_ID", "Tags_ID") FROM stdin;
1	1
1	2
1	3
\.


--
-- Data for Name: search_log; Type: TABLE DATA; Schema: core; Owner: -
--

COPY search_log ("ID", "Query", "Date", "IP") FROM stdin;
1	проверка	2017-02-07 13:00:13.547945	127.0.0.1/32
2	поиск	2017-02-15 18:13:25.876563	127.0.0.1/32
3	штукатурка vetonit tt	2017-02-15 18:13:37.654148	127.0.0.1/32
4	автомат	2017-02-15 19:55:33.754281	127.0.0.1/32
5	автомат	2017-02-15 19:55:46.563484	127.0.0.1/32
6	байпас	2017-02-15 19:56:01.61185	127.0.0.1/32
7	байпас	2017-02-15 19:56:33.071221	127.0.0.1/32
8	байпас	2017-02-15 19:58:15.048717	127.0.0.1/32
9	байпас	2017-02-15 19:59:41.808821	127.0.0.1/32
10	байпас	2017-02-15 20:00:46.552558	127.0.0.1/32
11	байпас	2017-02-15 20:01:12.00737	127.0.0.1/32
12	байпас	2017-02-15 20:01:56.253954	127.0.0.1/32
13	байпас	2017-02-15 20:05:01.748176	127.0.0.1/32
14	байпас	2017-02-15 20:05:59.555173	127.0.0.1/32
15	автомат	2017-02-16 12:02:20.224505	127.0.0.1/32
16	автомат	2017-02-16 12:02:47.119347	127.0.0.1/32
17	автомат	2017-02-16 12:03:43.202964	127.0.0.1/32
18	автомат	2017-02-16 12:03:58.305718	127.0.0.1/32
19	автомат	2017-02-16 12:04:06.288478	127.0.0.1/32
20	автомат	2017-02-16 12:04:16.916863	127.0.0.1/32
21	автомат	2017-02-16 12:07:27.942113	127.0.0.1/32
22	автомат	2017-02-16 12:10:42.061865	127.0.0.1/32
23	автомат	2017-02-16 12:12:54.38051	127.0.0.1/32
24	автомат	2017-02-16 12:13:42.642082	127.0.0.1/32
25	автомат	2017-02-16 12:14:22.57765	127.0.0.1/32
26	автомат	2017-02-16 12:15:34.532042	127.0.0.1/32
27	автомат	2017-02-16 12:15:43.449942	127.0.0.1/32
28	автомат	2017-02-16 12:16:17.308171	127.0.0.1/32
29	автомат	2017-02-16 12:17:27.146503	127.0.0.1/32
30	автомат	2017-02-16 12:17:47.195366	127.0.0.1/32
31	автомат	2017-02-16 12:18:29.47866	127.0.0.1/32
32	автомат	2017-02-16 12:19:09.599033	127.0.0.1/32
33	автомат	2017-02-16 12:21:06.668971	127.0.0.1/32
34	автомат	2017-02-16 12:21:23.02255	127.0.0.1/32
35	автомат	2017-02-16 12:21:33.753305	127.0.0.1/32
36	автомат	2017-02-16 12:21:40.319602	127.0.0.1/32
37	автомат	2017-02-16 12:21:49.093585	127.0.0.1/32
38	автомат	2017-02-16 12:21:53.595177	127.0.0.1/32
39	автомат	2017-02-16 12:22:25.194159	127.0.0.1/32
40	автомат	2017-02-16 12:23:05.970628	127.0.0.1/32
41	автомат	2017-02-16 12:24:14.028015	127.0.0.1/32
42	автомат	2017-02-16 12:24:28.18465	127.0.0.1/32
43	автомат	2017-02-16 12:25:01.075366	127.0.0.1/32
44	автомат	2017-02-16 12:25:08.433367	127.0.0.1/32
45	автомат	2017-02-16 12:25:45.291318	127.0.0.1/32
46	автомат	2017-02-16 12:25:54.859642	127.0.0.1/32
47	автомат	2017-02-16 12:26:40.439048	127.0.0.1/32
48	автомат	2017-02-16 12:27:52.065749	127.0.0.1/32
49	автомат	2017-02-16 12:27:59.266189	127.0.0.1/32
50	автомат	2017-02-16 12:28:11.526182	127.0.0.1/32
51	автомат	2017-02-16 12:28:19.148526	127.0.0.1/32
52	автомат	2017-02-16 12:28:27.858796	127.0.0.1/32
53	автомат	2017-02-16 12:28:34.405767	127.0.0.1/32
54	автомат	2017-02-16 12:28:45.531158	127.0.0.1/32
55	автомат	2017-02-16 12:30:03.493629	127.0.0.1/32
56	автоматфыва	2017-02-16 12:30:30.301119	127.0.0.1/32
57	автоматфыва	2017-02-16 12:31:01.526029	127.0.0.1/32
58	автоматфыва	2017-02-16 12:31:19.374319	127.0.0.1/32
59	автоматфыва	2017-02-16 12:31:28.771825	127.0.0.1/32
60	автоматфыва	2017-02-16 12:31:39.297543	127.0.0.1/32
61	автоматфыва	2017-02-16 12:31:53.835159	127.0.0.1/32
62	автоматфыва	2017-02-16 12:32:16.605083	127.0.0.1/32
63	автомат	2017-02-16 12:32:29.060636	127.0.0.1/32
64	автомат	2017-02-16 12:34:08.214736	127.0.0.1/32
65	автомат	2017-02-16 12:34:26.982433	127.0.0.1/32
66	автомат	2017-02-16 12:34:36.580781	127.0.0.1/32
67	автомат	2017-02-16 12:35:27.08023	127.0.0.1/32
68	автомат	2017-02-16 12:36:27.735833	127.0.0.1/32
69	автомат	2017-02-16 12:36:36.457989	127.0.0.1/32
70	авторм	2017-02-16 15:27:14.069802	127.0.0.1/32
71	автомат	2017-02-16 15:27:18.606405	127.0.0.1/32
72	автомат	2017-02-16 15:27:31.713134	127.0.0.1/32
73	автомат	2017-02-16 15:28:54.170207	127.0.0.1/32
74	автомат	2017-02-16 15:29:16.12866	127.0.0.1/32
75	автомат	2017-02-16 15:29:31.730275	127.0.0.1/32
76	мастер	2017-02-16 18:31:53.210693	127.0.0.1/32
77	электрика	2017-02-20 09:10:54.080371	5.18.174.183/32
78	электрика	2017-02-20 09:14:38.943726	5.18.174.183/32
79	сантехника	2017-02-20 09:15:05.110926	5.18.174.183/32
80	фум-лента	2017-02-20 09:15:32.630913	5.18.174.183/32
81	унитаз	2017-02-20 16:41:38.000517	5.18.174.183/32
82	плитка	2017-02-20 21:35:42.493066	5.18.174.183/32
83	плитка	2017-02-20 21:35:49.79999	5.18.174.183/32
84	вакансии	2017-03-15 15:42:18.85488	5.251.177.216/32
85	декаративни плитка	2017-03-29 19:20:32.424234	94.19.98.176/32
86	сделать унитаз	2017-04-14 05:15:12.413992	92.255.176.208/32
87	слово	2017-04-24 19:20:35.588852	127.0.0.1/32
88	слово	2017-04-24 19:21:19.451209	127.0.0.1/32
89	установка	2017-04-24 19:21:27.239082	127.0.0.1/32
90	лиц	2017-04-24 19:22:06.138854	127.0.0.1/32
91	znarus	2017-04-24 19:22:12.195274	127.0.0.1/32
92	znarus	2017-04-24 19:26:16.591827	127.0.0.1/32
93	znarus	2017-04-24 19:26:39.377498	127.0.0.1/32
94	znarus	2017-04-24 19:26:54.638664	127.0.0.1/32
95	znarus	2017-04-24 19:27:04.462641	127.0.0.1/32
96	znarus	2017-04-24 19:27:22.093899	127.0.0.1/32
97	znarus	2017-04-24 19:27:45.704446	127.0.0.1/32
98	znarus	2017-04-24 19:27:57.69535	127.0.0.1/32
99	znarus1	2017-04-24 19:28:53.609634	127.0.0.1/32
100	znarus	2017-04-24 19:29:20.421798	127.0.0.1/32
101	znarus	2017-04-24 19:29:50.639517	127.0.0.1/32
102	znarus	2017-04-24 19:29:54.397685	127.0.0.1/32
103	znarus	2017-04-24 19:30:19.735163	127.0.0.1/32
104	znarus	2017-04-24 19:30:42.362313	127.0.0.1/32
105	ыфва	2017-04-24 19:32:21.615037	127.0.0.1/32
106	ыфва	2017-04-24 19:35:57.615734	127.0.0.1/32
107	ыфва	2017-04-24 19:36:54.240718	127.0.0.1/32
108	сло	2017-04-24 19:36:59.274548	127.0.0.1/32
109	слово	2017-04-24 19:37:03.578543	127.0.0.1/32
110	лицензия	2017-04-24 19:37:08.515227	127.0.0.1/32
111	лицензия	2017-04-24 19:38:22.216185	127.0.0.1/32
112	лицензия	2017-04-24 19:39:06.974341	127.0.0.1/32
113	лицензия	2017-04-24 19:39:15.185573	127.0.0.1/32
114	лицензия	2017-04-24 19:39:23.711198	127.0.0.1/32
115	dump	2017-04-24 19:45:46.528803	127.0.0.1/32
116	dump.sql	2017-04-24 19:45:50.206162	127.0.0.1/32
117	дамп	2017-04-24 19:45:54.164372	127.0.0.1/32
118	postgresql	2017-04-24 19:46:01.787885	127.0.0.1/32
\.


--
-- Name: search_log_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('search_log_seq', 118, true);


--
-- Data for Name: search_tags; Type: TABLE DATA; Schema: core; Owner: -
--

COPY search_tags ("ID", "Name", "Count") FROM stdin;
1	поиск по сайту	1
2	найти	1
3	поиск	1
\.


--
-- Name: search_tags_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('search_tags_seq', 3, true);


--
-- Data for Name: seo_redirect; Type: TABLE DATA; Schema: core; Owner: -
--

COPY seo_redirect ("ID", "From", "To", "Location", "Tags") FROM stdin;
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
35	Главная страница. Содержание	home_content	<p>Добро пожаловать</p>	1
36	Страница 404. Содержание	404_content	<p>Страница не найдена.</p>	1
37	Страница 403. Содержание	403_content	<p>Доступ запрещён.</p>	1
41	Страница 404. Содержание	404_content	<p>Страница не найдена.</p>	74
42	Страница 403. Содержание	403_content	<p>К сожалению, данная страница для Вас не доступна.</p>	74
32	Главная страница. Тэг meta name="description"	home_description	CMS Znarus - быстрая и умная CMS	10
33	Страница 404. Тэг meta name="description"	404_description		10
34	Страница 403. meta name="description"	403_description		10
40	Главная. Содержание	home_content	<p><strong>Znarus</strong> - это система для создания и управления вашим сайтом. Для управления содержимым сайта используется &laquo;Панель управления&raquo;, а для управления модулями CMS используется &laquo;Панель разработчика&raquo;. Znarus является свободным программным обеспечением с открытым исходным кодом и открытой лицензией MIT. В основе её используется язык программирование PHP и система управления базой данных PostgreSQL.</p>\r\n<h2>Преимущества:</h2>\r\n<ul>\r\n<li>Быстрая, за счёт использования</li>\r\n<li>Удобный и понятный интерфейс панели управления</li>\r\n<li>Обладает встроенными инструментами для развёртывания поиска по сайту</li>\r\n<li>Хранит историю ранее изменённых документов</li>\r\n<li>Встроенный LESS-обработчик для CSS</li>\r\n<li>Встроенные модуля для работы с SEO</li>\r\n<li>Панель разработчика позволяет понять какое кол-во модулей установлено на сайте</li>\r\n<li>Возможность встраивать CMS в любой html-код.</li>\r\n</ul>	74
\.


--
-- Name: text_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('text_seq', 46, true);


--
-- Data for Name: user; Type: TABLE DATA; Schema: core; Owner: -
--

COPY "user" ("ID", "Name", "Email", "Password", "Group_ID", "Active", "Password_Change_Code", "Password_Change_Date", "Visit_Last_Admin") FROM stdin;
\.


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_group ("ID", "Name") FROM stdin;
1	Операторы
3	Администраторы
4	Верстальщик
\.


--
-- Data for Name: user_group_priv; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_group_priv ("Admin_ID", "Group_ID") FROM stdin;
82	4
77	4
78	4
79	4
80	4
81	4
238	4
367	4
367	1
368	4
368	1
369	4
369	1
370	4
370	1
35	4
36	4
37	4
38	4
39	4
40	4
41	4
42	4
43	4
44	4
45	4
7	3
8	3
9	3
10	3
11	3
12	3
13	3
16	3
14	3
17	4
18	4
19	4
20	4
21	4
23	4
24	4
25	4
371	4
371	1
373	4
373	1
374	4
374	1
375	4
375	1
377	4
377	1
372	4
372	1
378	4
378	1
\.


--
-- Name: user_group_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('user_group_seq', 4, true);


--
-- Name: user_seq; Type: SEQUENCE SET; Schema: core; Owner: -
--

SELECT pg_catalog.setval('user_seq', 5, true);


--
-- Data for Name: user_session; Type: TABLE DATA; Schema: core; Owner: -
--

COPY user_session ("ID", "Date", "IP", "Browser", "User_ID") FROM stdin;
a68646d02330cf7668b3dd71d8cc51e6	2017-04-24 19:40:44	8c08ae167bcae38e5b794bce1deb0f61	db6702d15b2cd35b27d7d2be214632ea	\N
\.


SET search_path = public, pg_catalog;

--
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: -
--

COPY menu ("ID", "Name") FROM stdin;
1	Левое
\.


--
-- Data for Name: menu_item; Type: TABLE DATA; Schema: public; Owner: -
--

COPY menu_item ("ID", "Name", "Url", "Parent", "Menu_ID", "Order", "Icon", "Active") FROM stdin;
3	Требования	/hosting	\N	1	3	server	t
4	Лицензия	/licence	\N	1	4	file-text-o	t
5	Модули	/modules	\N	1	5	sitemap	t
6	Панель управления	/admin	\N	1	6	wrench	t
7	Панель разработки	/constr	\N	1	7	puzzle-piece	t
1	Описание	/about	\N	1	1	info	t
2	Установка	/install	\N	1	2	cog	t
\.


--
-- Name: menu_item_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('menu_item_seq', 7, true);


--
-- Name: menu_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('menu_seq', 1, true);


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
-- Name: page_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_PK" PRIMARY KEY ("ID");


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
-- Name: search_log_PK; Type: CONSTRAINT; Schema: core; Owner: -; Tablespace: 
--

ALTER TABLE ONLY search_log
    ADD CONSTRAINT "search_log_PK" PRIMARY KEY ("ID");


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
-- Name: menu_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT "menu_PK" PRIMARY KEY ("ID");


--
-- Name: menu_item_PK; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_PK" PRIMARY KEY ("ID");


SET search_path = core, pg_catalog;

--
-- Name: exe_UN_Identified; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "exe_UN_Identified" ON exe USING btree (lower(("Identified")::pg_catalog.text), "Module_ID");


--
-- Name: page_UN1; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "page_UN1" ON page USING btree ("Name", "Parent") WHERE ("Parent" IS NOT NULL);


--
-- Name: page_UN1_NULL; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "page_UN1_NULL" ON page USING btree ("Name") WHERE ("Parent" IS NULL);


--
-- Name: page_UN2; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "page_UN2" ON page USING btree ("Url", "Parent") WHERE ("Parent" IS NOT NULL);


--
-- Name: page_UN2_NULL; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "page_UN2_NULL" ON page USING btree ("Url") WHERE ("Parent" IS NULL);


--
-- Name: search_index_FTS; Type: INDEX; Schema: core; Owner: -; Tablespace: 
--

CREATE INDEX "search_index_FTS" ON search_index USING gin ("FTS");


SET search_path = public, pg_catalog;

--
-- Name: menu_UN_1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "menu_UN_1" ON menu USING btree ("Name");


--
-- Name: menu_item_UN1; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "menu_item_UN1" ON menu_item USING btree ("Name", "Parent", "Menu_ID") WHERE ("Parent" IS NOT NULL);


--
-- Name: menu_item_UN1_NULL; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX "menu_item_UN1_NULL" ON menu_item USING btree ("Name", "Menu_ID") WHERE ("Parent" IS NULL);


SET search_path = core, pg_catalog;

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
-- Name: page_FK_Html_ID; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_FK_Html_ID" FOREIGN KEY ("Html_ID") REFERENCES html("ID") ON DELETE SET NULL;


--
-- Name: page_FK_Parent; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "page_FK_Parent" FOREIGN KEY ("Parent") REFERENCES page("ID") ON DELETE CASCADE;


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
-- Name: menu_item_FK_Menu_ID; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_FK_Menu_ID" FOREIGN KEY ("Menu_ID") REFERENCES menu("ID") ON DELETE CASCADE;


--
-- Name: menu_item_FK_Parent; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menu_item
    ADD CONSTRAINT "menu_item_FK_Parent" FOREIGN KEY ("Parent") REFERENCES menu_item("ID") ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

