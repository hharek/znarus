<?php

/* Пути */
Reg::path_cache("/site/znarus/cache", true);

/* Данные для подключения по FTP */
Reg::ftp_host("localhost");
Reg::ftp_user("znarus");
Reg::ftp_pass("111");
Reg::ftp_path_app("/app");
Reg::ftp_path_www("/www");

/* Данные для поключиния к PostgreSQL */
Reg::db_host("localhost");
Reg::db_user("znarus");
Reg::db_pass("111");
Reg::db_name("znarus");
Reg::db_schema_creator("creator");
Reg::db_schema_core("core");
Reg::db_schema_public("public");
Reg::db_cache_dir("/site/znarus/cache/db");

?>