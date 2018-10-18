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
require_once "include/bittorrent.php";
require_once "include/user_functions.php";
dbconn(true);
loggedinorreturn();
$lang = array_merge( load_language('global'), load_language('index') );
stdhead('Home');
echo '<script type="text/javascript" src="js/jssor.slider-21.1.6.min.js"></script>
<script type="text/javascript" src="js/jssor.slider.js"></script>';
//echo"<div style='border: 1px solid #555;width: 70%;'>"; // used to align the addon's (=
//Start of Lastest Torrents with Poster Slider [=
echo "<div style='margin-top:  -10px;margin-left: 0px;padding: 1em;max-width: 895px;' class='mCol'>
              <div class='myBlock'>
	          <div style='margin-top:  5px;box-shadow: 0 3px 10px rgba(0, 0, 0, 0.9), inset 0 1px 0 rgba(255,  255, 255, 0.2);'>
              <div class='myBlock-cap'><span  style='margin-left: -745px;font-weight:bold;font-size:12pt;'>{$lang['index_latest']}</span></div></div>
              <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
	  <div style='margin-top: -8px;background-color: #1f1f1f;'>
	  <div id='jssor_1' style='position: relative; margin: 0 auto; top: 20px; left: 0px; width: 1000px; height: 150px; overflow: hidden;'>
      <div data-u='slides' style='cursor: default; position: relative; top: 0px; left: 0px; width: 1000px; height: 150px; overflow: hidden;'>";
////////////// FreeTSP Query ///////////////////////
$freetsp = mysql_query("SELECT id, seeders, leechers, name, poster FROM torrents WHERE visible = 'yes' ORDER BY added DESC LIMIT 32") or sqlerr(__FILE__, __LINE__);
///////////////////////////////////////////////////
if (mysql_num_rows($freetsp) > 0) {
	
  while ($row = mysql_fetch_assoc($freetsp)) {
	   
	   $id       = (int) $row['id'];
	   $name     = htmlspecialchars($row['name']);
	   $poster   = ($row['poster'] == '' ? '/images/no_poster.png' : htmlspecialchars( $row['poster'] ));
       $seeders  = number_format($row['seeders']);
       $leechers = number_format($row['leechers']);
       $name     = str_replace('_','',$name);
       $name     = str_replace('.','',$name);
       $name     = substr($name, 0, 50);	   

  echo "<div class='smooth'>
        <a href=\"details.php?id=".$row['id']."\" title=\"".$name."\" /><img class=\"img-thumbnail\" src=\"".$poster."\" width=\"100\" height=\"100\" title=\"".$name."\" border=0 /></a>&nbsp;&nbsp;&nbsp;
		<div class='go-left'>
         <div style='float: left;'>&nbsp;&nbsp;&nbsp;&nbsp;".$row['seeders']." Seed</div> &nbsp; <div style='float:right;'>".$row['leechers']." Leech</div>
        </div>
        </div>";
  }
}
echo "</div></div><span style='padding: 0.1em;'></span></div></div><script type='text/javascript'>jssor_1_slider_init();</script><br />";
//End of Lastest Torrents with Poster Slider [=  		
echo "<div style='margin-left: 30px;'>";	
$adminbutton = '';   
    
    if (get_user_class() >= UC_ADMINISTRATOR)
          $adminbutton = "&nbsp;<span  style='float:right;'><a href='admin.php?action=news'><img title='Add News' width='20' height='20' src='pic/plus.png'/></a></span>\n";
          
    echo "
    <div style='margin-top:  -10px;margin-left: -40px;padding: 1em;min-width: 895px;' class='mCol'>
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
        
       echo "<div style='margin-top: -8px;background-color: #1f1f1f;border: 1px solid  #222;color: #b9b9b9;'  class='myBlock-con'>".format_comment($array['body'])."</div><div></div></div><div  style='padding: 1.5em;'></div><div style='margin-top:  -30px;'></div>";
        
      
      }
     
    }
    echo "</div></div></div>\n";
echo "</div>";
echo "<div style='margin-left: 25px;'>";
    // === shoutbox 09
    echo "<div style='margin-top: -10px;margin-left: -25px;text-align:left;max-width: 895px;padding: 1em;' class='mCol'>
	      <div class='myBlock'><div style='margin-top:  5px;'>
          <div class='myBlock-cap'><span style='font-weight:bold;font-size:12pt;'>Chat</span></div></div>
		  <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>  
	      <iframe src='shoutbox.php' width='100%' height='200' frameborder='0' name='sbox' marginwidth='0' marginheight='0'></iframe>  
          <div align='center'>
          <div style='max-width: 98%;padding: 8px 12px 10px 12px;border: 1px solid rgba(0,0,0,.5);background: rgba(0,0,0,.25);margin-left: 1px;'>
		  <form action='shoutbox.php' method='get' target='sbox' name='shbox' onsubmit='mysubmit()'> 
		  <input style='color: #b9b9b9;background: rgba(0,0,0,.25);padding: 8px 5px 5px 5px;border: 1px solid rgba(0,0,0,.5);' type='text' maxlength='180' name='shbox_text' size='100' />
		  <div style='margin-top: -36px;margin-left: 800px;'>
          <input class='button' type='submit' value='{$lang['index_shoutbox_send']}' />
		  <input type='hidden' name='sent' value='yes' /></div>          
		  </form></div></div></div></div>";
       //==end 09 shoutbox	

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
	 
      echo "<div style='margin-top: -10px;margin-left: -25px;text-align:left;max-width: 897px;padding: 1em;' class='mCol'>
	        <div class='myBlock'><div style='margin-top:  5px;'>
            <div class='myBlock-cap'><span style='font-weight:bold;font-size:12pt;'>{$lang['index_active']}</span></div></div>
		    <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
			<div style='margin-top: -5px;text-align: center;color: #b9b9b9;'>
			Owners ({$owners}) | Administrators ({$admins}) | Moderators ({$moderator}) | V.I.P ({$donors}) | Power Users ({$power}) | Members ({$members}) | Validating ({$unverified})
			</div>";
      echo "<table style='background-color: #1f1f1f;margin-top: 5px;color: #b9b9b9;' border='0' cellpadding='10' cellspacing='0' width='100%'>
            <tr class='table'>
            <td class='text'>{$activeusers}</td>
			</tr></table></div><div style='margin-top:  11px;'></div>";
			
// whitelist clients	
  echo "<div style='margin-top:  -10px;margin-left: -10px;padding: 1em;min-width: 895px;' class='mCol'>
        <div class='myBlock'>
	    <div style='margin-top:  5px;box-shadow: 0 3px 10px rgba(0, 0, 0, 0.9), inset 0 1px 0 rgba(255,  255, 255, 0.2);'>
        <div class='myBlock-cap'><span  style='margin-left: -5px;font-weight:bold;font-size:12pt;'>Client Whitelist</span></div></div>
        <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
	    <div style='margin-top: -9px;margin-left: -0px;color: #b9b9b9;' class='stats_info'>
        <text>qBittorrent For Mac</text>
	    <div style='padding:0.2em;'></div>
        <text>qBittorrent For PC</text>
        <div style='padding:0.2em;'></div>
	    <text>rTorrent 0.8.1 - 0.9.2</text>
	    <div style='padding:0.2em;'></div>
        <text>uTorrent 3.0 Build 26473</text>
	    <div style='padding:0.2em;'></div>
        <text>uTorrent 3.2.2 Build 28500</text>
	    <div style='padding:0.2em;'></div>
        <text>No other torrent clients are supported, if you use another client and have any problems with your stats not reporting correctly, Sorry, we cannot help you.</text></pre><br />
	    </div></div></div><div style='padding:0.4em;'></div>";

// Statistics
 $cache_stats = "./cache/stats.txt";
 $cache_stats_life = 5 * 60; // 5min
 if (file_exists($cache_stats) && is_array(unserialize(file_get_contents($cache_stats))) && (time() - filemtime($cache_stats)) < $cache_stats_life)
 $row = unserialize(@file_get_contents($cache_stats));
 else {
 $stats = mysql_query("SELECT *, seeders + leechers AS peers, seeders / leechers AS ratio, unconnectables / (seeders + leechers) AS ratiounconn FROM stats WHERE id = '1' LIMIT 1") or sqlerr(__FILE__, __LINE__);
 $row = mysql_fetch_assoc($stats);
 $handle = fopen($cache_stats, "w+");
 fwrite($handle, serialize($row));
 fclose($handle);
 }
 $seeders = number_format($row['seeders']);
 $leechers = number_format($row['leechers']);
 $registered = number_format($row['regusers']);
 $unverified = number_format($row['unconusers']);
 $torrents = number_format($row['torrents']);
 $torrentstoday = number_format($row['torrentstoday']);
 $ratiounconn = $row['ratiounconn'];
 $unconnectables = $row['unconnectables'];
 $ratio = round(($row['ratio'] * 100));
 $peers = number_format($row['peers']);
 $numactive = number_format($row['numactive']);
 $donors = number_format($row['donors']);
 $forumposts = number_format($row['forumposts']);
 $forumtopics = number_format($row['forumtopics']);
  echo "<div style='margin-top:  -10px;margin-left: -10px;padding: 1em;min-width: 895px;' class='mCol'>
        <div class='myBlock'>
	    <div style='margin-top:  5px;box-shadow: 0 3px 10px rgba(0, 0, 0, 0.9), inset 0 1px 0 rgba(255,  255, 255, 0.2);'>
        <div class='myBlock-cap'><span  style='margin-left: -5px;font-weight:bold;font-size:12pt;'>Statistics</span></div></div>
        <div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
        <table style='margin-top: -9px;background-color:#1f1f1f;' width='100%' border='1' cellspacing='0' cellpadding='10'><tr><td align='center'>
        <table style='background-color: #181818;color: #b9b9b9;' class='main' width='100%' border='1' cellspacing='0' cellpadding='5'>
        <tr>
        <td class='rowhead'>{$lang['index_stats_regged']}</td><td align='right'>{$registered}/{$TBDEV['maxusers']}</td>
        <td class='rowhead'>{$lang['index_stats_online']}</td><td align='right'>{$numactive}</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['index_stats_uncon']}</td><td align='right'>{$unverified}</td>
        <td class='rowhead'>{$lang['index_stats_donor']}</td><td align='right'>{$donors}</td>
        </tr>
        <tr>
        <td colspan='4'> </td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['index_stats_topics']}</td><td align='right'>{$forumtopics}</td>
        <td class='rowhead'>{$lang['index_stats_torrents']}</td><td align='right'>{$torrents}</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['index_stats_posts']}</td><td align='right'>{$forumposts}</td>
        <td class='rowhead'>{$lang['index_stats_newtor']}</td><td align='right'>{$torrentstoday}</td>
        </tr>
        <tr>
        <td colspan='4'> </td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['index_stats_peers']}</td><td align='right'>{$peers}</td>
        <td class='rowhead'>{$lang['index_stats_unconpeer']}</td><td align='right'>{$unconnectables}</td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['index_stats_seeders']}</td><td align='right'>{$seeders}</td>
        <td class='rowhead' align='right'><b>{$lang['index_stats_unconratio']}</b></td><td align='right'><b>".round($ratiounconn * 100)."</b></td>
        </tr>
        <tr>
        <td class='rowhead'>{$lang['index_stats_leechers']}</td><td align='right'>{$leechers}</td>
        <td class='rowhead'>{$lang['index_stats_slratio']}</td><td align='right'>{$ratio}</td>
        </tr></table></td></tr></table></div></div><br />";		
// Disclaimer
  echo "<div style='margin-top: -15px;margin-left: -10px;text-align:left;min-width: 895px;padding: 1em;' class='mCol'>
	    <div class='myBlock2'><div style='margin-top:  5px;'>
        <div class='myBlock-cap'><span style='font-weight:bold;font-size:12pt;'>Disclaimer</span></div></div>
		<div style='padding: 5px;margin-top: -3px;margin-left: 0px;max-width:  900px;box-shadow: inset 0 1px 0 rgba(255, 255, 255,  0.2);'></div>
        <div style='margin-top: -8px;color: #b9b9b9;padding: 1em;background-color: #1f1f1f;'>
        <font class='small'>{$lang['foot_disclaimer']}</font>
	    <div style='margin-top:  5px;'></div>
	    </div></div>";
///////////////////////////// FINAL OUTPUT //////////////////////
echo "</div>"; // removed a div because they are align to center of page
echo stdfoot();
?>