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

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/shoutbox/class/MyShoutbox.php';

/** @var Shoutbox\Helper $helper */
$helper = Shoutbox\Helper::getInstance();

$shoutbox = new Shoutbox\MyShoutbox($helper->getConfig('storage_type'));

// Admins may delete posts
if (!empty($_POST['clear']) && !empty($xoopsUser) && $xoopsUser->isAdmin()) {
    $shoutbox->deleteShouts();
}

$addit   = true;
$double  = false;
$message = !empty($_POST['message']) ? trim($_POST['message']) : '';

$isUser      = is_object($xoopsUser);
$isAnonymous = !$isUser && $helper->getConfig('guests_may_post');
$isMessage   = !empty($message);
if ($isMessage && ($isUser || $isAnonymous)) {
    //Populate uid and name and verify captcha
    if ($isAnonymous) {
        $uid        = 0;
        $post_uname = isset($_POST['uname']) ? trim($_POST['uname']) : '';
        if ($helper->getConfig('guests_may_chname') && !empty($post_uname)) {
            $uname = $post_uname;
        } else {
            $uname = Shoutbox\Utility::makeGuestName();
        }
        if ($helper->getConfig('captcha_enable')) {
            xoops_load('XoopsCaptcha');
            $xoopsCaptcha = XoopsCaptcha::getInstance();
            if (!$xoopsCaptcha->verify()) {
                $xoopsTpl->assign('captcha_error', $xoopsCaptcha->getMessage());
                $xoopsTpl->assign('message', $message);
                $xoopsTpl->assign('uname', $uname);
                $addit = false;
            }
        }
    } else {
        $uid   = $xoopsUser->getVar('uid');
        $uname = Shoutbox\Utility::getUserName($uid);
    }

    //check if it is a double post
    if ($addit && $shoutbox->shoutExists($message)) {
        $addit = false;
        $xoopsTpl->assign('refresh', true);
    }

    if ($addit) {
        $shoutbox->saveShout($uid, $uname, $message);
        $shoutbox->pruneShouts($helper->getConfig('maxshouts_trim'));
        $xoopsTpl->assign('refresh', true);
    }
}

$shouts = $shoutbox->getShouts(0, $helper->getConfig('allow_bbcode'), $helper->getConfig('maxshouts_view'));

$scrollDirection = $helper->getConfig('scroll_type');
if (!empty($shouts)) {
    if (0 === $scrollDirection) {
        $xoopsTpl->assign('shouts', array_reverse($shouts));
    } else {
        $xoopsTpl->assign('shouts', $shouts);
    }
}


$xoopsTpl->assign('config', $xoopsModuleConfig); //TODO

$xoopsTpl->caching = 0;
$xoopsTpl->display('db:shoutbox_shoutframe.tpl');
