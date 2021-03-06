<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Shoutbox
 * @author          Alphalogic <alphafake@hotmail.com>
 * @author          tank <tanksplace@comcast.net>
 * @author          trabis <lusopoemas@gmail.com>
 */

use XoopsModules\Shoutbox;

if (!defined('XOOPS_MAINFILE_INCLUDED') || false === mb_strpos($_SERVER['SCRIPT_NAME'], 'admin/main.php')) {
    exit();
}
$id      = \Xmf\Request::getInt('id', 0, 'REQUEST');
$handler = Shoutbox\Helper::getInstance()->getHandler('Database');
// Request or confirmation?
if (!empty($_POST['confirm']) && 'yes' === $_POST['confirm']) {
    // Sanitize inputs
    $obj = $handler->get($id);
    if (is_object($obj) && $handler->delete($obj)) {
        redirect_header('index.php', 2, _AM_SH_REMOVE_SUCCES);
    } else {
        redirect_header('index.php', 4, _AM_SH_REMOVE_FAILURE);
    }
} else {
    xoops_cp_header();
    // Check or we got a shout
    if (!$obj = $handler->get($id)) {
        /**
         * Or we got none, or something really strange happend here...
         */
        redirect_header('index.php', 3, _AM_SH_INVALID_ID);
    }

    // Make code ready for preview
    $shout         = $obj->getValues();
    $shout['date'] = $obj->time(_DATESTRING);

    echo "
<form action='main.php?op=shoutboxRemove' method='post'>
    <table width='100%' class='outer' cellspacing='1'>
        <tbody>
        <tr>
            <th colspan='2'>" . sprintf(_AM_SH_REMOVE_TITLE, $shout['date']) . "</th>
        </tr>
        <tr valign='top' align='left'>
            <td class='odd'>
                <b>" . _AM_SH_POSTER . "</b>
            </td>
            <td class='even'>
                $shout[uname]
            </td>
        </tr>
        <tr valign='top' align='left'>
            <td class='odd'>
                <b>" . _AM_SH_REMOVE_FROM . "</b>
            </td>
            <td class='even'>
                $shout[ip]
            </td>
        </tr>
        <tr valign='top' align='left'>
            <td class='odd'>
                <b>" . _AM_SH_MESSAGE . "</b>
            </td>
            <td class='even'>
                $shout[message]
            </td>
        </tr>
        <tr class='foot'>
            <td colspan='2' align='center'>
                <input type='hidden' name='id' value='$shout[id]'>
                <input type='hidden' name='confirm' value='yes'>
                <input type='submit' name='submit' value='" . _DELETE . "'>
                <input type='button' value='" . _CANCEL . "' onClick='location=\"main.php?op=shoutboxList\"'>
            </td>
        </tr>
        </tbody>
    </table>
</form>
    ";
}
