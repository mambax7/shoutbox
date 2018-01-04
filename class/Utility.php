<?php namespace XoopsModules\Shoutbox;

use Xmf\Request;
use XoopsModules\Shoutbox;
use XoopsModules\Shoutbox\Common;

/**
 * Class Utility
 */
class Utility
{
    use common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use common\ServerStats; // getServerStats Trait

    use common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------



    /**
     * @param        $option
     * @param string $dirname
     * @return mixed|null
     */
    public static function getOption($option, $dirname = 'shoutbox')
    {
        static $modOptions = [];
        if (is_array($modOptions) && array_key_exists($option, $modOptions)) {
            return $modOptions[$option];
        }

        $ret = null;
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($dirname);
        $configHandler = xoops_getHandler('config');
        if ($module) {
            $moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
            if (isset($moduleConfig[$option])) {
                $ret = $moduleConfig[$option];
            }
        }
        $modOptions[$option] = $ret;

        return $ret;
    }

    /**
     * @return string
     */
    public static function makeGuestName()
    {
        global $xoopsConfig;
        $ipadd     = getenv('REMOTE_ADDR');
        $iparr     = explode('.', $ipadd);
        $ipadd     = $iparr[0] + $iparr[1] + $iparr[2] + $iparr[3];
        $guestname = $xoopsConfig['anonymous'] . $ipadd;

        return $guestname;
    }

    /**
     * @param int $uid
     * @return string
     */
    public static function getUserName($uid = 0)
    {
        xoops_load('XoopsUserUtility');
        $uname = XoopsUserUtility::getUnameFromId($uid, static::getOption('user_realname'));

        return $uname;
    }

    /**
     * Most of these functions were written (originally)
     * by Florian Solcher <e-xoops.alphalogic.org>
     * @param $timestamp
     * @return bool
     */
    public static function setCookie($timestamp)
    {
        if (empty($_COOKIE['shoutcookie'])) {
            setcookie('shoutcookie', $timestamp);

            return false;
        }

        if ($_COOKIE['shoutcookie'] < $timestamp) {
            setcookie('shoutcookie', $timestamp);

            return true;
        } else {
            return false;
        }
    }

    //irc like commands

    /**
     * @param $command
     * @return bool
     */
    public static function ircLike($command)
    {
        global $xoopsModuleConfig, $xoopsUser, $special_stuff_head;
        if ('/quit' === $command) {
            $special_stuff_head .= '<script language="javascript">';
            $special_stuff_head .= '    top.window.close();';
            $special_stuff_head .= '</script>';

            return true;
        }
        $commandlines = explode(' ', $command);
        if (is_array($commandlines)) {
            //general commands
            //unregistered commands
            if (!$xoopsUser) {
                if (2 == count($commandlines)) {
                    if (('/nick' === $commandlines[0]) && ('' !== $commandlines[1])) {
                        if (1 == $xoopsModuleConfig['guests_may_chname']) {
                            $special_stuff_head .= '<script language="javascript">';
                            $special_stuff_head .= '    top.document.location.href="popup.php?username=' . htmlentities($commandlines[1], ENT_QUOTES) . '";';
                            $special_stuff_head .= '</script>';

                            return true;
                        } else {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
