<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use XoopsModules\Shoutbox;

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

/** @var Shoutbox\Helper $helper */
$helper = Shoutbox\Helper::getInstance();

$adminObject = \Xmf\Module\Admin::getInstance();

$adminObject->addInfoBox(_AM_SHOUTBOX_CURRENT_SELECTION);
if ('Database' === $helper->getConfig('storage_type')) {
    $database = '[' . _AM_SH_EDIT_INUSE . ']';
    $file     = '';
    $imgDB    = "<img src='../assets/images/on.png'>";
    $imgFile  = "<img src='../assets/images/off.png'>";
    $adminObject->addInfoBoxLine(sprintf($imgDB . "<a href='main.php?op=shoutboxList'>" . _AM_SH_EDIT_DB . "</a> $database", 0), '', 'Green');
    $adminObject->addInfoBoxLine(sprintf($imgFile . "<a href='main.php?op=shoutboxFile'>" . _AM_SH_EDIT_FILE . "</a> $file", 0), '', 'Green');
} elseif ('File' === $helper->getConfig('storage_type')) {
    $database = '';
    $file     = '[' . _AM_SH_EDIT_INUSE . ']';
    $imgDB    = "<img src='../assets/images/off.png'>";
    $imgFile  = "<img src='../assets/images/on.png'>";
    $adminObject->addInfoBoxLine(sprintf($imgDB . "<a href='main.php?op=shoutboxList'>" . _AM_SH_EDIT_DB . "</a> $database", 0), '', 'Green');
    $adminObject->addInfoBoxLine(sprintf($imgFile . "<a href='main.php?op=shoutboxFile'>" . _AM_SH_EDIT_FILE . "</a> $file", 0), '', 'Green');
}

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

require_once __DIR__ . '/admin_footer.php';
