<?php
/**
 * @package Social Kit - Social Networking Platform v1.3.3
 * @author Rehan Adil (ThemePhysics) http://codecanyon.net/user/ThemePhysics
 * @copyright 2015 Rehan Adil. All rights reserved.
 */

require_once('assets/includes/core.php');

function userOnly()
{
    if (! isLogged())
    {
        global $conn;
        $conn->close();
        exit("Please log in to continue!");
    }
}

$t = (! isset($_GET['t'])) ? "" : $escapeObj->stringEscape($_GET['t']);
$a = (! isset($_GET['a'])) ? "" : $escapeObj->stringEscape($_GET['a']);
$data = array(
    'status' => 417
);

include('requests/' . $t . '.php');