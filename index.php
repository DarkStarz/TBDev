<?php
/*
+------------------------------------------------
|   TBDev.net BitTorrent Tracker PHP
|   =============================================
|   by CoLdFuSiOn
|   (c) 2003 - 2009 TBDev.Net
|   http://www.tbdev.net
|   =============================================
|   svn: http://sourceforge.net/projects/tbdevnet/
|   Licence Info: GPL
+------------------------------------------------
|   $Date$
|   $Revision$
|   $Author$
|   $URL$
+------------------------------------------------
*/
ob_start("ob_gzhandler");

require_once "include/bittorrent.php";
require_once "include/user_functions.php";

dbconn(true);

loggedinorreturn();

    $lang = array_merge( load_language('global'), load_language('index') );
    //$lang = ;
    stdhead('Home');
    $HTMLOUT = '';
/*
$a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1")) or die(mysql_error());
if ($CURUSER)
  $latestuser = "<a href='userdetails.php?id=" . $a["id"] . "'>" . $a["username"] . "</a>";
else
  $latestuser = $a['username'];
*/

    $registered = number_format(get_row_count("users"));
    //$unverified = number_format(get_row_count("users", "WHERE status='pending'"));
    $torrents = number_format(get_row_count("torrents"));
    //$dead = number_format(get_row_count("torrents", "WHERE visible='no'"));

    $r = mysql_query("SELECT value_u FROM avps WHERE arg='seeders'") or sqlerr(__FILE__, __LINE__);
    $a = mysql_fetch_row($r);
    $seeders = 0 + $a[0];
    $r = mysql_query("SELECT value_u FROM avps WHERE arg='leechers'") or sqlerr(__FILE__, __LINE__);
    $a = mysql_fetch_row($r);
    $leechers = 0 + $a[0];
    if ($leechers == 0)
      $ratio = 0;
    else
      $ratio = round($seeders / $leechers * 100);
    $peers = number_format($seeders + $leechers);
    $seeders = number_format($seeders);
    $leechers = number_format($leechers);


    //stdhead();
    //$HTMLOUT .= "<div class='roundedCorners'><font class='small''>Welcome to our newest member, <b>$latestuser</b>!</font></div>\n";

$adminbutton = '';   
    
    if (get_user_class() >= UC_ADMINISTRATOR)
          $adminbutton = "&nbsp;<span  style='float:right;'><a href='admin.php?action=news'><img title='Add News' width='20' height='20' src='pic/plus.png'/></a></span>\n";
          
    echo "
    <div style='margin-top:  -20px;margin-left: -25px;padding: 1em;max-width: 900px;' class='mCol'>
    <div class='myBlock'><div style='margin-top:  5px;box-shadow: 0 3px 10px rgba(0, 0, 0, 0.9), inset 0 1px 0 rgba(255,  255, 255, 0.2);'>
    <div class='myBlock-cap'><span  style='margin-left: -815px;font-weight:bold;font-size:12pt;'>News</span>{$adminbutton}</div></div>
    <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
    <br />";
      
    $res = mysql_query("SELECT * FROM news WHERE added + ( 3600 *24 *45 ) >
                    ".time()." ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
                    
    if (mysql_num_rows($res) > 0)
    {
      require_once "include/bbcode_functions.php";

      $button = "";
      
      while($array = mysql_fetch_assoc($res))
      {
        if (get_user_class() >= UC_ADMINISTRATOR)
        {
          $button = "<div style='float:right;'><a  href='admin.php?action=news&amp;mode=edit&amp;newsid={$array['id']}'><img  style='margin-top: -5px;' width='20' height='20' src='pic/edit.png'  title='Edit'/></a>&nbsp;<a  href='admin.php?action=news&amp;mode=delete&amp;newsid={$array['id']}'><img  style='margin-top: -5px;' width='20' height='20' src='pic/trash.png' title='Delete'/></a></div>"; 
        }
        
       echo "<div style='margin-top: -13px;margin-left:  0px;max-width: 900px;box-shadow: 0 3px 10px rgba(0, 0, 0, 0.9), inset 0 1px  0 rgba(255, 255, 255, 0.2);'>
                     <div class='myBlock-cap'><span  style='margin-left: -732px;font-size:8pt;color: #FFF;'>&nbsp;&nbsp;{$array['headline']}</span>  {$button}</div></div>
                     <div style='padding: 5px;margin-top:  -3px;margin-left: 0px;max-width: 900px;box-shadow: inset 0 1px 0 rgba(255,  255, 255, 0.2);'></div>\n";
        
       echo "<div style='margin-top: -15px;margin-left:  0px;max-width: 900px;box-shadow: 0 3px 10px rgba(0, 0, 0, 0.9), inset 0 1px  0 rgba(255, 255, 255, 0.2);'>
                     <div class='myBlock-con'></div>\n";
        
       echo "<div style='margin-top: -8px;border: 1px solid  #222;color: #b9b9b9;'  class='myBlock-con'>".format_comment($array['body'])."</div><div></div></div><div  style='padding: 1.5em;'></div><div style='margin-top:  -30px;'></div>";
        
      
      }
     
    }
    echo "</div></div></div>\n";

    // === shoutbox 09
    echo "<form action='shoutbox.php' method='get' target='sbox' name='shbox' onsubmit='mysubmit()'>
       <div class='roundedCorners' style='text-align:left;width:80%;border:1px solid black;padding:5px;'>
          <div style='background:transparent;height:25px;'><span style='font-weight:bold;font-size:12pt;'>{$lang['index_shout']}</span></div>
             <br />
       <b>{$lang['index_shoutbox']}</b> [ <a href='shoutbox.php?show_shout=1&show=no'><b>{$lang['index_shoutbox_close']}</b></a> ]
       <iframe src='shoutbox.php' width='100%' height='200' frameborder='0' name='sbox' marginwidth='0' marginheight='0'></iframe>
       <br />
       <br />
       <script type=\"text/javascript\" src=\"scripts/shout.js\"></script>    
       <div align='center'>
       <b>{$lang['index_shoutbox_shout']}</b>
       <input type='text' maxlength='180' name='shbox_text' size='100' />
       <input class='button' type='submit' value='{$lang['index_shoutbox_send']}' />
       <input type='hidden' name='sent' value='yes' />
       <br />
        <a href=\"javascript:SmileIT(':-)','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/smile1.gif' alt='Smile' title='Smile' /></a> 
       <a href=\"javascript:SmileIT(':smile:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/smile2.gif' alt='Smiling' title='Smiling' /></a> 
       <a href=\"javascript:SmileIT(':-D','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/grin.gif' alt='Grin' title='Grin' /></a> 
       <a href=\"javascript:SmileIT(':lol:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/laugh.gif' alt='Laughing' title='Laughing' /></a> 
       <a href=\"javascript:SmileIT(':w00t:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/w00t.gif' alt='W00t' title='W00t' /></a> 
       <a href=\"javascript:SmileIT(':blum:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/blum.gif' alt='Rasp' title='Rasp' /></a> 
       <a href=\"javascript:SmileIT(';-)','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/wink.gif' alt='Wink' title='Wink' /></a> 
       <a href=\"javascript:SmileIT(':devil:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/devil.gif' alt='Devil' title='Devil' /></a> 
       <a href=\"javascript:SmileIT(':yawn:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/yawn.gif' alt='Yawn' title='Yawn' /></a> 
       <a href=\"javascript:SmileIT(':-/','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/confused.gif' alt='Confused' title='Confused' /></a> 
       <a href=\"javascript:SmileIT(')','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/clown.gif' alt='Clown' title='Clown' /></a> 
       <a href=\"javascript:SmileIT(':innocent:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/innocent.gif' alt='Innocent' title='innocent' /></a> 
       <a href=\"javascript:SmileIT(':whistle:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/whistle.gif' alt='Whistle' title='Whistle' /></a> 
       <a href=\"javascript:SmileIT(':unsure:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/unsure.gif' alt='Unsure' title='Unsure' /></a> 
       <a href=\"javascript:SmileIT(':blush:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/blush.gif' alt='Blush' title='Blush' /></a> 
       <a href=\"javascript:SmileIT(':hmm:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/hmm.gif' alt='Hmm' title='Hmm' /></a> 
       <a href=\"javascript:SmileIT(':hmmm:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/hmmm.gif' alt='Hmmm' title='Hmmm' /></a> 
       <a href=\"javascript:SmileIT(':huh:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/huh.gif' alt='Huh' title='Huh' /></a> 
       <a href=\"javascript:SmileIT(':look:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/look.gif' alt='Look' title='Look' /></a> 
       <a href=\"javascript:SmileIT(':rolleyes:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/rolleyes.gif' alt='Roll Eyes' title='Roll Eyes' /></a> 
       <a href=\"javascript:SmileIT(':kiss:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/kiss.gif' alt='Kiss' title='Kiss' /></a> 
       <a href=\"javascript:SmileIT(':blink:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/blink.gif' alt='Blink' title='Blink' /></a> 
       <a href=\"javascript:SmileIT(':baby:','shbox','shbox_text')\"><img border='0' src='{$TBDEV['pic_base_url']}smilies/baby.gif' alt='Baby' title='Baby' /></a><br/>
        </div></div>
       </form><br />\n";
       //==end 09 shoutbox	

//== Latest forum posts [set limit from config]
echo "<div class='roundedCorners' style='text-align:left;width:80%;border:1px solid black;padding:5px;'>
	      <div style='background:transparent;height:25px;'><span style='font-weight:bold;font-size:12pt;'>5 Latest Forum Posts </span></div><br />";
$page = 1;
$num  = 0;
//== Latest posts query
$topicres = mysql_query("SELECT t.id, t.userid, t.subject, t.locked, t.forumid, t.lastpost, t.sticky, t.views, t.forumid, f.minclassread, f.name " . ", (SELECT COUNT(id) FROM posts WHERE topicid=t.id) AS p_count " . ", p.userid AS puserid, p.added " . ", u.id AS uid, u.username " . ", u2.username AS u2_username " . "FROM topics AS t " . "LEFT JOIN forums AS f ON f.id = t.forumid " . "LEFT JOIN posts AS p ON p.id=(SELECT MAX(id) FROM posts WHERE topicid = t.id) " . "LEFT JOIN users AS u ON u.id=p.userid " . "LEFT JOIN users AS u2 ON u2.id=t.userid " . "WHERE f.minclassread <= " . $CURUSER['class'] . " " . "ORDER BY t.lastpost DESC LIMIT 5") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($topicres) > 0) {
  echo "<table width='100%' cellspacing='0' cellpadding='5'><tr>
        <td align='left' class='colhead'>{$lang['latestposts_topic_title']}</td>
        <td align='center' class='colhead'>{$lang['latestposts_replies']}</td>
        <td align='center' class='colhead'>{$lang['latestposts_views']}</td>
        <td align='center' class='colhead'>{$lang['latestposts_last_post']}</td></tr>";
    while ($topicarr = mysql_fetch_assoc($topicres)) {
        
        $topicid      = 0 + $topicarr['id'];
        $topic_userid = 0 + $topicarr['userid'];
        $perpage      = $CURUSER['postsperpage'];
        ;
        
        if (!$perpage)
            $perpage = 24;
        $posts   = 0 + $topicarr['p_count'];
        $replies = max(0, $posts - 1);
        $first   = ($page * $perpage) - $perpage + 1;
        $last    = $first + $perpage - 1;
        
        if ($last > $num)
            $last = $num;
        $pages = ceil($posts / $perpage);
        $menu  = '';
        for ($i = 1; $i <= $pages; $i++) {
            if ($i == 1 && $i != $pages) {
                $menu .= "[ ";
            }
            if ($pages > 1) {
                $menu .= "<a href='/forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'>$i</a>\n";
            }
            if ($i < $pages) {
                $menu .= "|\n";
            }
            if ($i == $pages && $i > 1) {
                $menu .= "]";
            }
        }
        
        $added = get_date($topicarr['added'], '', 0, 1);
        if ($topicarr['pos_anon'] == 'yes') {
            if ($CURUSER['class'] < UC_MODERATOR && $CURUSER['id'] != $topicarr['puserid'])
                $username = "<i>Anonymous</i>";
            else
                $username = "<i>Anonymous</i><br />(" . (!empty($topicarr['username']) ? "<a href='/userdetails.php?id=" . (int) $topicarr['puserid'] . "'><b>" . htmlspecialchars($topicarr['username']) . "</b></a>" : "<i>Unknown[$topic_userid]</i>") . ")";
        } else {
            $username = (!empty($topicarr['username']) ? "<a href='/userdetails.php?id=" . (int) $topicarr['puserid'] . "'><b>" . htmlspecialchars($topicarr['username']) . "</b></a>" : ($topic_userid == '0' ? "<i>System</i>" : "<i>Unknown[$topic_userid]</i>"));
        }
        if ($topicarr['top_anon'] == 'yes') {
            if ($CURUSER['class'] < UC_MODERATOR && $CURUSER['id'] != $topic_userid)
                $author = "<i>Anonymous</i>";
            else
                $author = "<i>Anonymous</i>(" . (!empty($topicarr['u2_username']) ? "<a href='/userdetails.php?id=$topic_userid'><b>" . htmlspecialchars($topicarr['u2_username']) . "</b></a>" : "<i>Unknown[$topic_userid]</i>") . ")";
        } else {
            $author = (!empty($topicarr['u2_username']) ? "<a href='/userdetails.php?id=$topic_userid'><b>" . htmlspecialchars($topicarr['u2_username']) . "</b></a>" : ($topic_userid == '0' ? "<i>System</i>" : "<i>Unknown[$topic_userid]</i>"));
        }
        $staffimg  = ($topicarr['minclassread'] >= UC_MODERATOR ? "<img src='" . $TBDEV['pic_base_url'] . "staff.png' border='0' alt='Staff forum' title='Staff Forum' />" : '');
        $stickyimg = ($topicarr['sticky'] == 'yes' ? "<img src='" . $TBDEV['pic_base_url'] . "sticky.gif' border='0' alt='Sticky' title='Sticky Topic' />&nbsp;&nbsp;" : '');
        $lockedimg = ($topicarr['locked'] == 'yes' ? "<img src='" . $TBDEV['pic_base_url'] . "forumicons/locked.gif' border='0' alt='Locked' title='Locked Topic' />&nbsp;" : '');
        $subject   = $lockedimg . $stickyimg . "<a href='/forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=last#" . (int) $topicarr['lastpost'] . "'><b>" . htmlspecialchars($topicarr['subject']) . "</b></a>&nbsp;&nbsp;$staffimg&nbsp;&nbsp;$menu<br /><font class='small'>in <a href='forums.php?action=viewforum&amp;forumid=" . (int) $topicarr['forumid'] . "'>" . htmlspecialchars($topicarr['name']) . "</a>&nbsp;by&nbsp;$author&nbsp;&nbsp;($added)</font>";
        
      echo "<tr><td>{$subject}</td><td align='center'>{$replies}</td><td align='center'>" . number_format($topicarr['views']) . "</td><td align='center'>{$username}</td></tr>";
    }
   echo "</table></div><br />\n";
} else {
    //== If there are no posts...
   echo "<div class='roundedCorners' style='text-align:center;border:1px solid black;background:transparent;'><span style='font-weight:bold;font-size:10pt;'>No Post Yet</span></div></div><br />";
}
//== End latest forum posts

	   
    //==09 users on index
    $active3 ="";
    $file = "./cache/active.txt";
    $expire = 30; // 30 seconds
    if (file_exists($file) && filemtime($file) > (time() - $expire)) {
    $active3 = unserialize(file_get_contents($file));
    } else {
    $dt = sqlesc(time() - 180);
    $active1 = mysql_query("SELECT id, username, class, warned, donor FROM users WHERE last_access >= $dt ORDER BY class DESC") or sqlerr(__FILE__, __LINE__);
        while ($active2 = mysql_fetch_assoc($active1)) {
            $active3[] = $active2;
        }
        $OUTPUT = serialize($active3);
        $fp = fopen($file, "w");
        fputs($fp, $OUTPUT);
        fclose($fp);
    } // end else
    $activeusers = "";
    if (is_array($active3))
    foreach ($active3 as $arr) {
        if ($activeusers) $activeusers .= ",\n";
        $activeusers .= "<span style=\"white-space: nowrap;\">"; 
        $arr["username"] = "<font color='#" . get_user_class_color($arr['class']) . "'> " . htmlspecialchars($arr['username']) . "</font>";
        $donator = $arr["donor"] === "yes";
        $warned = $arr["warned"] === "yes";
     
        if ($CURUSER)
            $activeusers .= "<a href='{$TBDEV['baseurl']}/userdetails.php?id={$arr["id"]}'><b>{$arr["username"]}</b></a>";
        else
            $activeusers .= "<b>{$arr["username"]}</b>";
        if ($donator)
             $activeusers .= "<img src='{$TBDEV['pic_base_url']}star.gif' alt='Donated' />";
        if ($warned)
            $activeusers .= "<img src='{$TBDEV['pic_base_url']}warned.gif' alt='Warned' />";
        $activeusers .= "</span>";
    }
     
    if (!$activeusers)
        $activeusers = "{$lang['index_noactive']}";
	
     $owners      = get_row_count('users', "WHERE owners='yes'");
     $admins      = get_row_count('users', "WHERE admin='yes'");
     $moderator   = get_row_count('users', "WHERE moderator='yes'");	 
     $donors      = get_row_count('users', "WHERE donor='yes'");
	 $power       = get_row_count('users', "WHERE poweruser='yes'");
     $members     = get_row_count('users', "WHERE members='yes'");
     $unverified = number_format(get_row_count("users", "WHERE status='pending'"));	 
	 
      echo "<div style='margin-top: -10px;margin-left: -25px;text-align:left;max-width: 901px;padding: 1em;' class='mCol'>
	        <div class='myBlock'><div style='margin-top:  5px;'>
            <div class='myBlock-cap'><span style='font-weight:bold;font-size:12pt;'>{$lang['index_active']}</span></div></div>
		    <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
			<div style='margin-top: -5px;text-align: center;color: #b9b9b9;'>
			Owners ({$owners}) | Administrators ({$admins}) | Moderators ({$moderator}) | V.I.P ({$donors}) | Power Users ({$power}) | Members ({$members}) | Validating ({$unverified})
			</div>";
      echo "<table style='margin-top: 5px;border: 1px solid  #222;color: #b9b9b9;' border='0' cellpadding='10' cellspacing='0' width='100%'>
            <tr class='table'>
            <td class='text'>{$activeusers}</td>
			</tr></table></div><div style='margin-top:  11px;'></div>";

      echo "<div style='margin-top: -10px;margin-left: -10px;text-align:left;min-width: 901px;padding: 1em;' class='mCol'>
	        <div class='myBlock2'><div style='margin-top:  5px;'>
            <div class='myBlock-cap'><span style='font-weight:bold;font-size:12pt;'>Disclaimer</span></div></div>
		    <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
            <div style='color: #b9b9b9;'>
			<font class='small'>{$lang['foot_disclaimer']}</font>
			<div style='margin-top:  5px;'></div>
			</div>";
///////////////////////////// FINAL OUTPUT //////////////////////

    print $HTMLOUT . stdfoot();
?>