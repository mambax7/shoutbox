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
/** @var Shoutbox\Helper $helper */
$helper = Shoutbox\Helper::getInstance();

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/shoutbox/class/Utility.php';
require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

if (!is_object($xoopsUser) && (!$helper->getConfig('popup_guests') || !$helper->getConfig('guests_may_post'))) {
    xoops_header(false);
    xoops_error("<br>You aren't allowed to enter this section!<br><br>");
    xoops_footer();
    die();
}

$uname = isset($_POST['uname']) ? trim($_POST['uname']) : '';

if (!is_object($xoopsUser)) {
    if (1 == $helper->getConfig('guests_may_chname') && !empty($uname)) {
        $myts = \MyTextSanitizer::getInstance();
        $xoopsTpl->assign('uname', $myts->htmlSpecialChars($uname, ENT_QUOTES));
    } elseif (!$helper->getConfig('guests_may_chname')) {
        $xoopsTpl->assign('uname', Shoutbox\Utility::makeGuestName());
    } else {
        $xoopsTpl->assign('uname', '');
    }
} else {
    $xoopsTpl->assign('uname', Shoutbox\Utility::getUserName($xoopsUser->uid()));
}

ob_start();
require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';
xoopsSmilies('shoutfield');
$smiliesbar = str_replace("<a href='#moresmiley' onmouseover='style.cursor=\"hand\"' alt=''", "<a href='#moresmiley' onmouseover='style.cursor=\"hand\"' title='More'", ob_get_contents());
ob_end_clean();

$xoopsTpl->assign('smiliesbar', $smiliesbar);
$xoopsTpl->assign('config', $xoopsModuleConfig); //TODO check on using here Helper

$xoopsTpl->caching=(0);
$xoopsTpl->display('db:shoutbox_popup.tpl');
