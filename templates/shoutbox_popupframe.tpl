<!DOCTYPE html>
<html>
<head>
    <{include file="db:shoutbox_popupheader.tpl"}>
</head>
<body>
<table width='100%' border='0' cellpadding='5' cellspacing='0' style='padding: 0; margin: 0;'>
    <{if $config.popup_sound==1 && $newmessage}>
        <script type="text/javascript">

                        //  if (top.xoopsGetElementById("soundselect").checked) {
                        //      document.write("<embed src='new_shout.wav' autostart='true' volume='100' HEIGHT='0' WIDTH='0' controls='smallconsole'>");
                        //  } else {
                        //      document.write("<embed src='new_shout.wav' autostart='false' volume='0' HEIGHT='0' WIDTH='0' controls='smallconsole'>");
                        // }

        </script>
    <{/if}>
    <{foreach item=shout from=$shouts}>
        <{cycle values="odd,even" assign="cycle_color"}>
        <tr class='<{$cycle_color}>'>
            <td title='<{$shout.time}><{if $xoops_isadmin}> [<{$shout.ip}>]<{/if}>' width='10%'>
                <{if $shout.avatar}>
                    <img src='<{$shout.avatar}>' alt=''>
                    <br>
                <{/if}>
                <{if $shout.uid}>
                    <{if $shout.online}><img src='<{$xoops_url}>/modules/shoutbox/assets/images/online.gif'
                                             alt='Online!'>
                    <{else}><img src='<{$xoops_url}>/modules/shoutbox/assets/images/offline.gif' alt='Offline'>
                    <{/if}>
                <{else}>
                    <span style="font-weight: bold;"><{$lang_anonymous}></span>
                <{/if}>

            </td>
            <td width='90%' valign='top' title='<{$shout.time}><{if $xoops_isadmin}> [<{$shout.ip}>]<{/if}>'>
                <{if $shout.uid}>
                    <span style="font-weight: bold;"><a href='javascript:top.window.opener.location="<{$xoops_url}>/userinfo.php?uid=<{$shout.uid}>";top.window.opener.focus();' "><{$shout.uname}></a>:</span>
                <{else}>
                    <span style="font-weight: bold;"><{$shout.uname}>:</span>
                <{/if}>
                <{$shout.message}>
            </td>
        </tr>
        <tr class='<{$cycle_color}>'>
            <td>
                <span style="font-style: italic;"><{$shout.time}></span>
            </td>
            <td align='right'>
                <{if !$shout.uid}>
                    <a href='javascript:openWithSelfMain("<{$xoops_url}>/pmlite.php?send2=1&amp;to_userid=<{$shout.uid}>","pmlite",450,380);'><img
                                src='<{$xoops_url}>/images/icons/pm.gif' alt=''
                                border='0'></a>
                <{/if}>
                <{if !$shout.email}>
                    <a href='mailto:<{$shout.email}>'><img src='<{$xoops_url}>/images/icons/email.gif' border='0'
                                                           alt=''></a>
                <{/if}>
                <{if !$shout.url}>
                    <a href='<{$shout.url}>' target='_blank'><img src='<{$xoops_url}>/images/icons/www.gif' border='0'
                                                                  alt=''></a>
                <{/if}>
            </td>
        </tr>
    <{/foreach}>
</table>
</body>
</html>
