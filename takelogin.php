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
require_once 'include/bittorrent.php';
require_once "include/password_functions.php";

    if (!mkglobal('username:password'))
      die();
      
    session_start();
    dbconn();
    $lang = array_merge( load_language('global'), load_language('takelogin') );


    $res = mysql_query("SELECT id, passhash, secret, enabled FROM users WHERE username = " . sqlesc($username) . " AND status = 'confirmed'");
    $row = mysql_fetch_assoc($res);

    if (!$row)
      stderr($lang['tlogin_failed'], 'Username or password incorrect');
    
    if ($row['passhash'] != make_passhash( $row['secret'], md5($password) ) )
    //if ($row['passhash'] != md5($row['secret'] . $password))
      stderr($lang['tlogin_failed'], 'Username or password incorrect');

    if ($row['enabled'] == 'no')
      stderr($lang['tlogin_failed'], $lang['tlogin_disabled']);

    logincookie($row['id'], $row['passhash']);
    header("Location: {$TBDEV['baseurl']}/my.php");
?>