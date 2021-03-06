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
 /**
 * Class DatabaseHandler
 */
class DatabaseHandler extends \XoopsPersistableObjectHandler
{
    /**
     * DatabaseHandler constructor.
     * @param \XoopsDatabase|null $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'shoutbox', 'Database', 'id', 'uid');
    }

    /**
     * @return \XoopsObject
     */
    public function createShout()
    {
        return $this->create();
    }

    /**
     * @param $obj
     * @return mixed
     */
    public function saveShout($obj)
    {
        return $this->insert($obj);
    }

    /**
     * @param $limit
     * @return array
     */
    public function getShouts($limit)
    {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('time');
        $criteria->setOrder('DESC');
        $criteria->setStart(0);
        $criteria->setLimit($limit);

        return $this->getObjects($criteria);
    }

    /**
     * @param $limit
     * @return bool
     */
    public function pruneShouts($limit)
    {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->setOrder('DESC');
        $criteria->setStart(0);
        $criteria->setLimit($limit);
        $objs = $this->getList($criteria, true);
        unset($criteria);
        $criteria = new \Criteria('id', '(' . \implode(',', \array_keys($objs)) . ')', 'NOT IN');

        return $this->deleteAll($criteria);
    }

    /**
     * @return bool
     */
    public function deleteShouts()
    {
        return $this->deleteAll();
    }

    /**
     * @param $message
     * @param $ip
     * @return bool
     */
    public function shoutExists($message, $ip)
    {
        $myts     = \MyTextSanitizer::getInstance();
        $criteria = new \CriteriaCompo(new \Criteria('message', $myts->addSlashes($message)));
        $criteria->add(new \Criteria('ip', $ip));

        return $this->getCount($criteria) ? true : false;
    }
}
