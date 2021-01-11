<?php
if (!defined("IN_MYBB"))
{
    die("Direct access not allowed.");
}
$lang->load("tools_nopermission");
$page->add_breadcrumb_item($lang->tools_and_maintenance, "index.php?module=tools");
$page->add_breadcrumb_item($lang->nopermission_info_name, "index.php?module=tools-nopermission");

$page->output_header($lang->nopermission_info_name);

$baseurl = "index.php?module=tools-nopermission";

// Get the current page
if($mybb->get_input("page"))
{
    $current_page = $mybb->get_input("page", MyBB::INPUT_INT);
}
else
{
    $current_page = 1;
}

// How many pages are there.
$query = $db->simple_select("nopermission", "COUNT(id) as rows");
$rows = $db->fetch_field($query, "rows");

$pages = ceil($rows / 50);

if($current_page > $pages)
{
    $current_page = $pages;
}
if($current_page < 1)
{
    $current_page = 1;
}

$start = $current_page * 50 - 50;

if($mybb->get_input("order_by"))
{
    $sortsql = " ORDER BY ";
    $order_by = $mybb->get_input("order_by");
    switch($order_by)
    {
        case "username":
            $sortsql .= " u.username ASC ";
            break;
        case "time":
            $sortsql .= "np.dateline DESC ";
            break;
        case "file":
            $sortsql .= "np.file ASC ";
            break;
        default:
            $sortsql .= " np.dateline DESC ";
            break;
    }
}
else
{
    $sortsql = " ORDER BY np.dateline DESC ";
    $order_by = "time";
}

$query = $db->query("SELECT np.*, u.username FROM " . TABLE_PREFIX . "nopermission np
LEFT JOIN " . TABLE_PREFIX . "users u ON(np.uid=u.uid)
" . $sortsql . " LIMIT " . $start . ", 50");

$pagination = draw_admin_pagination($current_page, 50, $pages, "index.php?module=tools-nopermission&sort_by=" . $order_by);
echo $pagination;

$table = new TABLE;

$table->construct_header("<a href='" . $baseurl . "&sort_by=username'>" . $lang->nopermission_username . "</a>");
$table->construct_header("<a href='" . $baseurl . "&sort_by=time'>" . $lang->nopermission_time . "</a>");
$table->construct_header("<a href='" . $baseurl . "&sort_by=file'>" . $lang->nopermission_file . "</a>");
$table->construct_header($lang->nopermission_url);
$table->construct_row();
if($db->num_rows($query) == 0)
{
    $table->construct_cell("There are no entries.", array("colspan" => 4));
    $table->construct_row();
}

while($logitem = $db->fetch_array($query))
{
    $date = my_date($mybb->settings['dateformat'], $logitem['dateline']);
    $time = my_date($mybb->settings['timeformat'], $logitem['dateline']);
    $logitem['formatted_time'] = $date . " " . $time;
    // Guests have no username
    if(!$logitem['username'])
    {
        $logitem['username'] = "Guest";
        // Take deleted accounts into account
        if($logitem['uid'])
        {
            $logitem['username'] .= " #" . $logitem['uid'];
        }
    }
    $table->construct_cell(htmlspecialchars_uni($logitem['username']));
    $table->construct_cell($logitem['formatted_time']);
    $table->construct_cell($logitem['file']);
    $table->construct_cell("<a href='" . $logitem['location'] . "' target=_'blank'>" . $logitem['location'] . "</a>");
    $table->construct_row();
}
$table->output($lang->nopermission_info_name);
echo $pagination;
$page->output_footer();
