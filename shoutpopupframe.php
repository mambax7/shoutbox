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
//require_once XOOPS_ROOT_PATH . '/modules/shoutbox/class/Utility.php';

global $xoopsModuleConfig;

/** @var Shoutbox\Helper $helper */
$helper = Shoutbox\Helper::getInstance();

$shoutbox = new Shoutbox\MyShoutbox($helper->getConfig('storage_type'));

$onlineHandler = xoops_getHandler('online');
// set gc probability to 10% for now..
if (mt_rand(1, 100) < 11) {
    $onlineHandler->gc(300);
}

if (is_object($xoopsUser)) {
    $uid   = $xoopsUser->getVar('uid');
    $uname = Shoutbox\Utility::getUserName($uid);
} else {
    $uid   = 0;
    $uname = '';
}

$onlineHandler->write($uid, $uname, time(), $xoopsModule->getVar('mid'), $_SERVER['REMOTE_ADDR']);

$addit              = 1;
$special_stuff_head = '';
$lastmine           = 0;
$double             = 0;
$newmessage         = 0;

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
        /* if ($helper->getConfig('captcha_enable')) {
         xoops_load('XoopsCaptcha');
         $xoopsCaptcha = XoopsCaptcha::getInstance();
         if (!$xoopsCaptcha->verify()) {
         $xoopsTpl->assign('captcha_error', $xoopsCaptcha->getMessage());
         $xoopsTpl->assign('message', $message);
         $xoopsTpl->assign('uname', $uname);
         $addit = false;
         }
         }*/
    } else {
        $uid   = $xoopsUser->getVar('uid');
        $uname = Shoutbox\Utility::getUserName($uid);
    }
    //check if it is a double post
    if ($addit && $shoutbox->shoutExists($message)) {
        $addit = false;
        $xoopsTpl->assign('refresh', true);
    }

    // Enable IRC Commands
    if (1 == $helper->getConfig('popup_irc') && isset($message) && false !== mb_strpos($message, '/')) {
        if (Shoutbox\Utility::ircLike($message)) {
            unset($message);
            $addit = false;
            $xoopsTpl->assign('refresh', true);
        }
    }

    if ($addit) {
        $shoutbox->saveShout($uid, $uname, $message);
        $shoutbox->pruneShouts($helper->getConfig('maxshouts_trim'));
        $xoopsTpl->assign('refresh', true);
    }
}

/*
 if ($shouts = file($shoutbox->csvfile)) {
 $totalshouts = count($shouts);
 }

 // Check or there is a new message
 if (!empty($shouts)) {
 $oneline = explode("|", $shouts[$totalshouts-1]);

 /*
 echo '$_COOKIE["shoutcookie"] ='.$_COOKIE["shoutcookie"]."<br \>\n";
 echo '$online[2] ='.$oneline[2]."<br \>\n";
 echo '$time() ='.time()."<br \>\n";
 */
/*
 if ($xoopsUser && $xoopsUser->uname() == $oneline[0]) {
 $lastmine=1;
 } elseif (!empty($username) && $username == $oneline[0]) {
 $lastmine=1;
 }

 if (Utility::setCookie($oneline[2]) && $lastmine==0) {
 $newmessage = 1;
 }
 }
 */
$shouts = $shoutbox->getShouts(1, $helper->getConfig('allow_bbcode'), $helper->getConfig('maxshouts_view'));

if (!empty($shouts)) {
    $xoopsTpl->assign('shouts', $shouts);
} else {
    xoops_result(_MD_SHOUTBOX_POPUP_NOSHOUTS, 0);
}

$xoopsTpl->assign('lang_anonymous', $xoopsConfig['anonymous']);
$xoopsTpl->assign('special_stuff_head', $special_stuff_head);
$xoopsTpl->assign('newmessage', $newmessage);
$xoopsTpl->assign('config', $xoopsModuleConfig); //TODO

$xoopsTpl->caching = 0;
$xoopsTpl->display('db:shoutbox_popupframe.tpl');
