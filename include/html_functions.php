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

  //-------- Begins a main frame

  function begin_main_frame()
  {
    return "<table class='main' width='750px' border='0' cellspacing='0' cellpadding='0'>" .
      "<tr><td class='embedded'>\n";
  }

  function begin_forum_frame()
  {
    return "";
  }  
  
  //-------- Ends a main frame

  function end_main_frame()
  {
    return "</td></tr></table>\n";
  }

  function begin_frame($caption = "", $center = false, $padding = 10)
  {
    $tdextra = "";
    $htmlout = '';
    if ($caption)
      $htmlout .= "<h2>$caption</h2>\n";

    if ($center)
      $tdextra .= " align='center'";

    $htmlout .= "<table width='100%' border='1' cellspacing='0' cellpadding='$padding'><tr><td$tdextra>\n";
    
    return $htmlout;
  }

  function attach_frame($padding = 10)
  {
    print("</td></tr><tr><td style='border-top: 0px'>\n");
  }

  function end_frame()
  {
    return "</td></tr></table>\n";
  }

  function begin_table($fullwidth = false, $padding = 5)
  {
    $width = "";
    $htmlout = '';
    
    if ($fullwidth)
      $width .= " width='100%'";
    $htmlout .= "<table class='main'$width border='1' cellspacing='0' cellpadding='$padding'>\n";
    
    return $htmlout;
  }

  function end_table()
  {
    return "</table>\n";
  }
  
  //  function end_table()
//  {
//    print("</td></tr></table>\n");
//  }
  
	function tr($x,$y,$noesc=0) {
		if ($noesc)
			$a = $y;
		else {
			$a = htmlspecialchars($y);
			$a = str_replace("\n", "<br />\n", $a);
		}
		
		return "<tr><td class='heading' valign='top' align='right'>$x</td><td valign='top' align='left'>$a</td></tr>\n";
	}


  //-------- Inserts a smilies frame

function insert_smilies_frame()
  {
    global $smilies, $TBDEV;
    
    $htmlout = '';
    
    $htmlout .= begin_frame("Smilies", true);

    $htmlout .= begin_table(false, 5);

    $htmlout .= "<tr><td class='colhead'>Type...</td><td class='colhead'>To make a...</td></tr>\n";

    foreach($smilies as $code => $url)
    {
      $htmlout .= "<tr><td>$code</td><td><img src=\"{$TBDEV['pic_base_url']}smilies/{$url}\" alt='' /></td></tr>\n";
    }
    
    $htmlout .= end_table();

    $htmlout .= end_frame();
    
    return $htmlout;
}

?>