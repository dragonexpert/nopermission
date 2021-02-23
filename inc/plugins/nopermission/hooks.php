<?php

// hook list
$plugins->add_hook("no_permission", "nopermission_no_permission");
$plugins->add_hook("admin_tools_menu_logs", "nopermission_admin_tools_menu_logs");
$plugins->add_hook("admin_tools_action_handler", "nopermission_admin_tools_action_handler");
$plugins->add_hook("admin_tools_permissions", "nopermission_admin_tools_permissions");

function nopermission_no_permission()
{
    global $db, $mybb;

    if(defined(THIS_SCRIPT))
    {
        $file = THIS_SCRIPT;
    }
    else
    {
        $file = $_SERVER['PHP_SELF'];
    }

    $no_permission = array(
        "uid" => (int) $mybb->user['uid'],
        "dateline" => TIME_NOW,
        "file" => $db->escape_string($file),
        "location" => $db->escape_string($_SERVER['REQUEST_URI']),
        "ipaddress" => $db->escape_string(my_inet_pton(get_ip()))
    );

    $db->insert_query("nopermission", $no_permission);
}

function nopermission_admin_tools_menu_logs(&$sub_menu)
{
    $sub_menu[] = array(
        "id" => "nopermission",
        "title" => "No Permission Log",
        "link" => "index.php?module=tools-nopermission"
    );
}

function nopermission_admin_tools_action_handler(&$actions)
{
    $actions['nopermission'] = array(
        "active" => "nopermission",
        "file" => "nopermission.php"
    );
}

function nopermission_admin_tools_permissions(&$admin_permissions)
{
    global $lang;
    $lang->load("tools_nopermission");
    $admin_permissions['nopermission'] = $lang->nopermission_admin_permission;
}
