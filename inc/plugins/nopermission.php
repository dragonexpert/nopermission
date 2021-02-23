<?php
if(!defined("IN_MYBB"))
{
    die("Direct access not allowed.");
}

require_once "nopermission/hooks.php";

function nopermission_info()
{
    global $lang;
    $lang->load("tools_nopermission");
    $donation_link = "<a href='https://www.paypal.me/MarkJanssen' target='_blank'>" . $lang->nopermission_info_donate . "</a>";
    return array(
        "name" => $lang->nopermission_info_name,
        "description" => $lang->nopermission_info_description . " " . $donation_link,
        "author" => "Mark Janssen",
        "codename" => "nopermission",
        "version" => "2.0",
        "compatibility" => "18*"
    );
}

function nopermission_install()
{
    require_once "nopermission/db.php";
    nopermission_db_install();
}

function nopermission_is_installed()
{
    global $db;
    return $db->table_exists("nopermission");
}

function nopermission_activate()
{

}

function nopermission_deactivate()
{

}

function nopermission_uninstall()
{
    require_once "nopermission/db.php";
    nopermission_db_uninstall();
}
