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

// require_once __DIR__ . '/../class/Helper.php';
$helper = Shoutbox\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$adminmenu[] = [
    'title' => _MI_SHOUTBOX_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

//$adminmenu[] = [
//'title' =>  _AM_MODULEADMIN_HOME,
//'link' =>  "admin/main.php",
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
//];

$adminmenu[] = [
    'title' => _MI_SHOUTBOX_MENU_DB,
    'link'  => 'admin/main.php?op=shoutboxList',
    'icon'  => $pathIcon32 . '/list.png',
];

$adminmenu[] = [
    'title' => _MI_SHOUTBOX_MENU_FILE,
    'link'  => 'admin/main.php?op=shoutboxFile',
    'icon'  => $pathIcon32 . '/index.png',
];

$adminmenu[] = [
    'title' => _MI_SHOUTBOX_MENU_STATUS,
    'link'  => 'admin/main.php?op=shoutboxStatus',
    'icon'  => $pathIcon32 . '/search.png',
];

$adminmenu[] = [
    'title' => _MI_SHOUTBOX_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
