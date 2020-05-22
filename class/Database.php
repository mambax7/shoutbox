<?php

namespace XoopsModules\Shoutbox;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *  Shoutbox class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Shoutbox
 * @since           5.0
 * @author          trabis <lusopoemas@gmail.com>
 */


class Database extends \XoopsObject
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->initVar('id', \XOBJ_DTYPE_INT);
        $this->initVar('uid', \XOBJ_DTYPE_INT);
        $this->initVar('uname', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('time', \XOBJ_DTYPE_STIME);
        $this->initVar('ip', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('message', \XOBJ_DTYPE_TXTAREA);

        $this->initVar('dohtml', \XOBJ_DTYPE_INT, 0);
        $this->initVar('doxcode', \XOBJ_DTYPE_INT, 0);
        $this->initVar('dosmiley', \XOBJ_DTYPE_INT, 1);
        $this->initVar('doimage', \XOBJ_DTYPE_INT, 1);
        $this->initVar('dobr', \XOBJ_DTYPE_INT, 0);
    }

    /**
     * @param string $dateFormat
     * @param string $format
     * @return string
     */
    public function time($dateFormat = 's', $format = 'S')
    {
        return \formatTimestamp($this->getVar('time', $format), $dateFormat);
    }
}
