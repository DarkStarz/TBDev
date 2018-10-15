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

/////////////// REP SYSTEM /////////////
//$CURUSER['reputation'] = 650;

function get_reputation($user, $mode = 0, $rep_is_on = TRUE)
	{
	global $TBDEV;
	
	
	
	$member_reputation = "";
	if( $rep_is_on )
		{
			@include 'cache/rep_cache.php';
			// ok long winded file checking, but it's much better than file_exists
			if( ! isset( $reputations ) || ! is_array( $reputations ) || count( $reputations ) < 1)
			{
				return '<span title="Cache doesn\'t exist or zero length">Reputation: Offline</span>';
			}
			
			$user['g_rep_hide'] = isset( $user['g_rep_hide'] ) ? $user['g_rep_hide'] : 0;
	
			// Hmmm...bit of jiggery-pokery here, couldn't think of a better way.
			$max_rep = max(array_keys($reputations));
			if($user['reputation'] >= $max_rep)
			{
				$user_reputation = $reputations[$max_rep];
			}
			else
			foreach($reputations as $y => $x) 
			{
				if( $y > $user['reputation'] ) { $user_reputation = $old; break; }
				$old = $x;
			}
			
			//$rep_is_on = TRUE;
			//$CURUSER['g_rep_hide'] = FALSE;
					
			$rep_power = $user['reputation'];
			$posneg = '';
			if( $user['reputation'] == 0 )
			{
				$rep_img   = 'balance';
				$rep_power = $user['reputation'] * -1;
			}
			elseif( $user['reputation'] < 0 )
			{
				$rep_img   = 'neg';
				$rep_img_2 = 'highneg';
				$rep_power = $user['reputation'] * -1;
			}
			else
			{
				$rep_img   = 'pos';
				$rep_img_2 = 'highpos';
			}

			if( $rep_power > 500 )
			{
				// work out the bright green shiny bars, cos they cost 100 points, not the normal 100
				$rep_power = ( $rep_power - ($rep_power - 500) ) + ( ($rep_power - 500) / 2 );
			}

			// shiny, shiny, shiny boots...
			// ok, now we can work out the number of bars/pippy things
			$rep_bar = intval($rep_power / 100);
			if( $rep_bar > 10 )
			{
				$rep_bar = 10;
			}

			if( $user['g_rep_hide'] ) // can set this to a group option if required, via admin?
			{
				$posneg = 'off';
				$rep_level = 'rep_off';
			}
			else
			{ // it ain't off then, so get on with it! I wanna see shiny stuff!!
				$rep_level = $user_reputation ? $user_reputation : 'rep_undefined';// just incase

				for( $i = 0; $i <= $rep_bar; $i++ )
				{
					if( $i >= 5 )
					{
						$posneg .= "<img src='pic/rep/reputation_$rep_img_2.gif' border='0' alt=\"Reputation Power $rep_power\n{$user['username']} $rep_level\" title=\"Reputation Power $rep_power {$user['username']} $rep_level\" />";
					}
					else
					{
						$posneg .= "<img src='pic/rep/reputation_$rep_img.gif' border='0' alt=\"Reputation Power $rep_power\n{$user['username']} $rep_level\" title=\"Reputation Power $rep_power {$user['username']} $rep_level\" />";
					}
				}
			}
			
			// now decide if we in a forum or statusbar?
			if( $mode === 0 )
			return "Rep: ".$posneg . "<br /><a href='javascript:;' onclick=\"PopUp('{$TBDEV['baseurl']}/reputation.php?pid={$user['id']}','Reputation',400,241,1,1);\"><img src='./pic/plus.gif' border='0' alt='Add reputation:: {$user['username']}' title='Add reputation:: {$user['username']}' /></a>";
			else
			return "Rep: ".$posneg;
			
		} // END IF ONLINE
		
		// default
		return '<span title="Set offline by admin setting">Rep System Offline</span>';
	}
////////////// REP SYSTEM END //////////

function get_user_icons($arr, $big = false)
  {
    global $TBDEV;
    
    if ($big)
    {
      $donorpic = "starbig.gif";
      $warnedpic = "warnedbig.gif";
      $disabledpic = "disabledbig.gif";
      $style = "style='margin-left: 4pt'";
    }
    else
    {
      $donorpic = "star.gif";
      $warnedpic = "warned.gif";
      $disabledpic = "disabled.gif";
      $style = "style=\"margin-left: 2pt\"";
    }
    $pics = $arr["donor"] == "yes" ? "<img src=\"{$TBDEV['pic_base_url']}{$donorpic}\" alt='Donor' border='0' $style />" : "";
    if ($arr["enabled"] == "yes")
      $pics .= $arr["warned"] == "yes" ? "<img src=\"{$TBDEV['pic_base_url']}{$warnedpic}\" alt=\"Warned\" border='0' $style />" : "";
    else
      $pics .= "<img src=\"{$TBDEV['pic_base_url']}{$disabledpic}\" alt=\"Disabled\" border='0' $style />\n";
    return $pics;
}

function get_ratio_color($ratio)
  {
    if ($ratio < 0.1) return "#ff0000";
    if ($ratio < 0.2) return "#ee0000";
    if ($ratio < 0.3) return "#dd0000";
    if ($ratio < 0.4) return "#cc0000";
    if ($ratio < 0.5) return "#bb0000";
    if ($ratio < 0.6) return "#aa0000";
    if ($ratio < 0.7) return "#990000";
    if ($ratio < 0.8) return "#880000";
    if ($ratio < 0.9) return "#770000";
    if ($ratio < 1) return "#660000";
    return "#000000";
  }

function get_slr_color($ratio)
  {
    if ($ratio < 0.025) return "#ff0000";
    if ($ratio < 0.05) return "#ee0000";
    if ($ratio < 0.075) return "#dd0000";
    if ($ratio < 0.1) return "#cc0000";
    if ($ratio < 0.125) return "#bb0000";
    if ($ratio < 0.15) return "#aa0000";
    if ($ratio < 0.175) return "#990000";
    if ($ratio < 0.2) return "#880000";
    if ($ratio < 0.225) return "#770000";
    if ($ratio < 0.25) return "#660000";
    if ($ratio < 0.275) return "#550000";
    if ($ratio < 0.3) return "#440000";
    if ($ratio < 0.325) return "#330000";
    if ($ratio < 0.35) return "#220000";
    if ($ratio < 0.375) return "#110000";
    return "#000000";
  }


function get_user_class()
{
    global $CURUSER;
    return $CURUSER["class"];
}

/** class functions - pdq 2010 **/
/** START **/
   $class_names = array(
        UC_USER                 => 'User',
        UC_POWER_USER           => 'Power User',
        UC_VIP                  => 'VIP',
        UC_UPLOADER             => 'Uploader',
        UC_MODERATOR            => 'Moderator',
        UC_ADMINISTRATOR        => 'Administrator',
        UC_SYSOP                => 'SysOp');
        
   $class_colors = array(
        UC_USER                 => '8E35EF',
        UC_POWER_USER           => 'f9a200',
        UC_VIP                  => '009F00',
        UC_UPLOADER             => '0000FF',
        UC_MODERATOR            => 'FE2E2E',
        UC_ADMINISTRATOR        => 'B000B0',
        UC_SYSOP                => '4080B0');
   $class_images = array(
        UC_USER                 => $TBDEV['pic_base_url'].'class/user.gif',
        UC_POWER_USER           => $TBDEV['pic_base_url'].'class/power.gif',
        UC_VIP                  => $TBDEV['pic_base_url'].'class/vip.gif',
        UC_UPLOADER             => $TBDEV['pic_base_url'].'class/uploader.gif',
        UC_MODERATOR            => $TBDEV['pic_base_url'].'class/moderator.gif',
        UC_ADMINISTRATOR        => $TBDEV['pic_base_url'].'class/administrator.gif',
        UC_SYSOP                => $TBDEV['pic_base_url'].'class/sysop.gif');
        
   function get_user_class_name($class) {
        global $class_names;
        $class = (int)$class;
        if (!valid_class($class))
            return '';
        if (isset($class_names[$class]))
            return $class_names[$class];
        else
            return '';
    }
    
    function get_user_class_color($class) {
        global $class_colors;
        $class = (int)$class;
        if (!valid_class($class))
            return '';
        if (isset($class_colors[$class]))
            return $class_colors[$class];
        else
            return '';
    }
    
    function get_user_class_image($class) {
        global $class_images;
        $class = (int)$class;
        if (!valid_class($class))
            return '';
        if (isset($class_images[$class]))
            return $class_images[$class];
        else
            return '';
    }
    
    function valid_class($class) {
        $class = (int)$class;
        return (bool)($class >= UC_MIN && $class <= UC_MAX);
    }
    function min_class($min = UC_MIN, $max = UC_MAX) {
        global $CURUSER;
        $minclass = (int)$min;
        $maxclass = (int)$max;
        if (!isset($CURUSER))
            return false;
        if (!valid_class($minclass) || !valid_class($maxclass))
            return false;
        if ($maxclass < $minclass)
            return false;
        return (bool)($CURUSER['class'] >= $minclass && $CURUSER['class'] <= $maxclass);
    }
       
function format_username($user, $icons = true) {
        global $TBDEV;
        $user['id'] = (int)$user['id'];
        $user['class'] = (int)$user['class'];
        if ($user['id'] == 0)
            return 'System';
        elseif ($user['username'] == '')
            return 'unknown['.$user['id'].']';
        $username = '<span style="color:#'.get_user_class_color($user['class']).';"><b>'.$user['username'].'</b></span>';
        $str = '<span style="white-space: nowrap;"><a class="user_'.$user['id'].'" href="'.$TBDEV['baseurl'].'/userdetails.php?id='.$user['id'].'"target="_blank">'.$username.'</a>';
        if ($icons != false) {
            $str .= ($user['donor'] == 'yes' ? '<img src="'.$TBDEV['pic_base_url'].'star.png" alt="Donor" title="Donor" />' : '');
            $str .= ($user['warned'] >= 1 ? '<img src="'.$TBDEV['pic_base_url'].'warned.png" alt="Warned" title="Warned" />' : '');
            $str .= ($user['enabled'] != 'yes' ? '<img src="'.$TBDEV['pic_base_url'].'disabled.gif" alt="Disabled" title="Disabled" />' : '');
        }
        $str .= "</span>\n";
        return $str;
}

function is_valid_user_class($class)
{
  return is_numeric($class) && floor($class) == $class && $class >= UC_USER && $class <= UC_SYSOP;
}

function is_valid_id($id)
{
  return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

?>