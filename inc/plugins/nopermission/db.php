<?php
if(!defined("IN_MYBB"))
{
    die("Direct access not allowed.");
}

function nopermission_db_install()
{
    global $db;

    $db->write_query("CREATE TABLE IF NOT EXISTS " . TABLE_PREFIX . "nopermission(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    uid INT NOT NULL DEFAULT 0,
    dateline BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
    file TEXT,
    location TEXT,
    ipaddress VARBINARY(16) NOT NULL DEFAULT ''
    ) ENGINE=InnoDB" . $db->build_create_table_collation());
}

function nopermission_db_update()
{
    global $db;
    if(!$db->field_exists("ipaddress", "nopermission"))
    {
        $db->add_column("nopermission", "ipaddress", "VARBINARY(16) NOT NULL DEFAULT ''");
    }
}

function nopermission_db_uninstall()
{
    global $db;
    if($db->table_exists("nopermission"))
    {
        $db->drop_table("nopermission");
    }
}
