<?php
/* Copyright (C) 2004-2017	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2006		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2007-2017	Regis Houssin			<regis.houssin@capnetworks.com>
 * Copyright (C) 2011		Philippe Grand			<philippe.grand@atoo-net.com>
 * Copyright (C) 2012		Juanjo Menent			<jmenent@2byte.es>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *		\file       htdocs/theme/eldy/style.css.php
 *		\brief      File for CSS style sheet Eldy
 */

//if (! defined('NOREQUIREUSER')) define('NOREQUIREUSER','1');	// Not disabled because need to load personalized language
//if (! defined('NOREQUIREDB'))   define('NOREQUIREDB','1');	// Not disabled to increase speed. Language code is found on url.
if (! defined('NOREQUIRESOC'))    define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN')) define('NOREQUIRETRAN','1');	// Not disabled because need to do translations
if (! defined('NOCSRFCHECK'))     define('NOCSRFCHECK',1);
if (! defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL',1);
if (! defined('NOLOGIN'))         define('NOLOGIN',1);          // File must be accessed by logon page so without login
//if (! defined('NOREQUIREMENU'))   define('NOREQUIREMENU',1);  // We need top menu content
if (! defined('NOREQUIREHTML'))   define('NOREQUIREHTML',1);
if (! defined('NOREQUIREAJAX'))   define('NOREQUIREAJAX','1');

// Colors
$colorbackhmenu1='80,90,120';      // topmenu
$colorbackvmenu1='255,255,255';      // vmenu
$colortopbordertitle1='120,120,120';    // top border of title
$colorbacktitle1='230,230,233';      // title of tables,list
$colorbacktabcard1='255,255,255';  // card
$colorbacktabactive='234,234,234';
$colorbacklineimpair1='255,255,255';    // line impair
$colorbacklineimpair2='255,255,255';    // line impair
$colorbacklinepair1='248,248,248';    // line pair
$colorbacklinepair2='248,248,248';    // line pair
$colorbacklinepairhover='238,246,252';    // line pair
$colorbackbody='255,255,255';
$colortexttitlenotab='100,60,20';
$colortexttitle='0,0,0';
$colortext='0,0,0';
$colortextlink='0,0,100';
$fontsize='13';
$fontsizesmaller='12';

if (defined('THEME_ONLY_CONSTANT')) return;

session_cache_limiter(FALSE);

require_once '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

// Load user to have $user->conf loaded (not done into main because of NOLOGIN constant defined)
if (empty($user->id) && ! empty($_SESSION['dol_login'])) $user->fetch('',$_SESSION['dol_login'],'',1);


// Define css type
top_httphead('text/css');
// Important: Following code is to avoid page request by browser and PHP CPU at each Dolibarr page access.
if (empty($dolibarr_nocache)) header('Cache-Control: max-age=3600, public, must-revalidate');
else header('Cache-Control: no-cache');

// On the fly GZIP compression for all pages (if browser support it). Must set the bit 3 of constant to 1.
if (isset($conf->global->MAIN_OPTIMIZE_SPEED) && ($conf->global->MAIN_OPTIMIZE_SPEED & 0x04)) { ob_start("ob_gzhandler"); }

if (GETPOST('theme','alpha')) $conf->theme=GETPOST('theme','alpha');  // If theme was forced on URL
if (GETPOST('lang','aZ09')) $langs->setDefaultLang(GETPOST('lang', 'aZ09'));	// If language was forced on URL

$langs->load("main",0,1);
$right=($langs->trans("DIRECTION")=='rtl'?'left':'right');
$left=($langs->trans("DIRECTION")=='rtl'?'right':'left');

$path='';    	// This value may be used in future for external module to overwrite theme
$theme='eldy';	// Value of theme
if (! empty($conf->global->MAIN_OVERWRITE_THEME_RES)) { $path='/'.$conf->global->MAIN_OVERWRITE_THEME_RES; $theme=$conf->global->MAIN_OVERWRITE_THEME_RES; }

// Define image path files and other constants
$fontlist='roboto,arial,tahoma,verdana,helvetica';    //$fontlist='helvetica, verdana, arial, sans-serif';
//$fontlist='"open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;';
$img_head='';
$img_button=dol_buildpath($path.'/theme/'.$theme.'/img/button_bg.png',1);
$dol_hide_topmenu=$conf->dol_hide_topmenu;
$dol_hide_leftmenu=$conf->dol_hide_leftmenu;
$dol_optimize_smallscreen=$conf->dol_optimize_smallscreen;
$dol_no_mouse_hover=$conf->dol_no_mouse_hover;

//$conf->global->THEME_ELDY_ENABLE_PERSONALIZED=0;
//$user->conf->THEME_ELDY_ENABLE_PERSONALIZED=0;
//var_dump($user->conf->THEME_ELDY_RGB);

$useboldtitle=(isset($conf->global->THEME_ELDY_USEBOLDTITLE)?$conf->global->THEME_ELDY_USEBOLDTITLE:1);
$borderwith=2;

// Case of option always editable
if (! isset($conf->global->THEME_ELDY_BACKBODY)) $conf->global->THEME_ELDY_BACKBODY=$colorbackbody;
if (! isset($conf->global->THEME_ELDY_TOPMENU_BACK1)) $conf->global->THEME_ELDY_TOPMENU_BACK1=$colorbackhmenu1;
if (! isset($conf->global->THEME_ELDY_BACKTITLE1)) $conf->global->THEME_ELDY_BACKTITLE1=$colorbacktitle1;
if (! isset($conf->global->THEME_ELDY_USE_HOVER)) $conf->global->THEME_ELDY_USE_HOVER=$colorbacklinepairhover;
if (! isset($conf->global->THEME_ELDY_TEXTTITLENOTAB)) $conf->global->THEME_ELDY_TEXTTITLENOTAB=$colortexttitlenotab;
if (! isset($conf->global->THEME_ELDY_TEXTLINK)) $conf->global->THEME_ELDY_TEXTLINK=$colortextlink;

// Case of option editable only if option THEME_ELDY_ENABLE_PERSONALIZED is on
if (empty($conf->global->THEME_ELDY_ENABLE_PERSONALIZED))
{
	$conf->global->THEME_ELDY_VERMENU_BACK1='255,255,255';    // vmenu
    $conf->global->THEME_ELDY_BACKTABCARD1='255,255,255';     // card
    $conf->global->THEME_ELDY_BACKTABACTIVE='234,234,234';
    $conf->global->THEME_ELDY_TEXT='0,0,0';
    $conf->global->THEME_ELDY_FONT_SIZE1='13';
    $conf->global->THEME_ELDY_FONT_SIZE2='12';
}

// Case of option availables only if THEME_ELDY_ENABLE_PERSONALIZED is on
$colorbackhmenu1     =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_TOPMENU_BACK1)?$colorbackhmenu1:$conf->global->THEME_ELDY_TOPMENU_BACK1)   :(empty($user->conf->THEME_ELDY_TOPMENU_BACK1)?$colorbackhmenu1:$user->conf->THEME_ELDY_TOPMENU_BACK1);
$colorbackvmenu1     =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_VERMENU_BACK1)?$colorbackvmenu1:$conf->global->THEME_ELDY_VERMENU_BACK1)   :(empty($user->conf->THEME_ELDY_VERMENU_BACK1)?$colorbackvmenu1:$user->conf->THEME_ELDY_VERMENU_BACK1);
$colortopbordertitle1=empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_TOPBORDER_TITLE1)?$colortopbordertitle1:$conf->global->THEME_ELDY_TOPBORDER_TITLE1)   :(empty($user->conf->THEME_ELDY_TOPBORDER_TITLE1)?$colortopbordertitle1:$user->conf->THEME_ELDY_TOPBORDER_TITLE1);
$colorbacktitle1     =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_BACKTITLE1)   ?$colorbacktitle1:$conf->global->THEME_ELDY_BACKTITLE1)      :(empty($user->conf->THEME_ELDY_BACKTITLE1)?$colorbacktitle1:$user->conf->THEME_ELDY_BACKTITLE1);
$colorbacktabcard1   =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_BACKTABCARD1) ?$colorbacktabcard1:$conf->global->THEME_ELDY_BACKTABCARD1)  :(empty($user->conf->THEME_ELDY_BACKTABCARD1)?$colorbacktabcard1:$user->conf->THEME_ELDY_BACKTABCARD1);
$colorbacktabactive  =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_BACKTABACTIVE)?$colorbacktabactive:$conf->global->THEME_ELDY_BACKTABACTIVE):(empty($user->conf->THEME_ELDY_BACKTABACTIVE)?$colorbacktabactive:$user->conf->THEME_ELDY_BACKTABACTIVE);
$colorbacklineimpair1=empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_LINEIMPAIR1)  ?$colorbacklineimpair1:$conf->global->THEME_ELDY_LINEIMPAIR1):(empty($user->conf->THEME_ELDY_LINEIMPAIR1)?$colorbacklineimpair1:$user->conf->THEME_ELDY_LINEIMPAIR1);
$colorbacklineimpair2=empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_LINEIMPAIR2)  ?$colorbacklineimpair2:$conf->global->THEME_ELDY_LINEIMPAIR2):(empty($user->conf->THEME_ELDY_LINEIMPAIR2)?$colorbacklineimpair2:$user->conf->THEME_ELDY_LINEIMPAIR2);
$colorbacklinepair1  =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_LINEPAIR1)    ?$colorbacklinepair1:$conf->global->THEME_ELDY_LINEPAIR1)    :(empty($user->conf->THEME_ELDY_LINEPAIR1)?$colorbacklinepair1:$user->conf->THEME_ELDY_LINEPAIR1);
$colorbacklinepair2  =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_LINEPAIR2)    ?$colorbacklinepair2:$conf->global->THEME_ELDY_LINEPAIR2)    :(empty($user->conf->THEME_ELDY_LINEPAIR2)?$colorbacklinepair2:$user->conf->THEME_ELDY_LINEPAIR2);
$colorbackbody       =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_BACKBODY)     ?$colorbackbody:$conf->global->THEME_ELDY_BACKBODY)          :(empty($user->conf->THEME_ELDY_BACKBODY)?$colorbackbody:$user->conf->THEME_ELDY_BACKBODY);
$colortexttitlenotab =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_TEXTTITLENOTAB)?$colortexttitlenotab:$conf->global->THEME_ELDY_TEXTTITLENOTAB)             :(empty($user->conf->THEME_ELDY_TEXTTITLENOTAB)?$colortexttitlenotab:$user->conf->THEME_ELDY_TEXTTITLENOTAB);
$colortexttitle      =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_TEXTTITLE)    ?$colortexttitle:$conf->global->THEME_ELDY_TEXTTITLE)             :(empty($user->conf->THEME_ELDY_TEXTTITLE)?$colortexttitle:$user->conf->THEME_ELDY_TEXTTITLE);
$colortext           =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_TEXT)         ?$colortext:$conf->global->THEME_ELDY_TEXT)                  :(empty($user->conf->THEME_ELDY_TEXT)?$colortext:$user->conf->THEME_ELDY_TEXT);
$colortextlink       =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_TEXTLINK)     ?$colortextlink:$conf->global->THEME_ELDY_TEXTLINK)              :(empty($user->conf->THEME_ELDY_TEXTLINK)?$colortextlink:$user->conf->THEME_ELDY_TEXTLINK);
$fontsize            =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_FONT_SIZE1)   ?$fontsize:$conf->global->THEME_ELDY_FONT_SIZE1)             :(empty($user->conf->THEME_ELDY_FONT_SIZE1)?$fontsize:$user->conf->THEME_ELDY_FONT_SIZE1);
$fontsizesmaller     =empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)?(empty($conf->global->THEME_ELDY_FONT_SIZE2)   ?$fontsize:$conf->global->THEME_ELDY_FONT_SIZE2)             :(empty($user->conf->THEME_ELDY_FONT_SIZE2)?$fontsize:$user->conf->THEME_ELDY_FONT_SIZE2);

// Hover color
$colorbacklinepairhover=((! isset($conf->global->THEME_ELDY_USE_HOVER) || (string) $conf->global->THEME_ELDY_USE_HOVER === '0')?'':($conf->global->THEME_ELDY_USE_HOVER === '1'?'edf4fb':$conf->global->THEME_ELDY_USE_HOVER));
if (! empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED))
{
    $colorbacklinepairhover=((! isset($user->conf->THEME_ELDY_USE_HOVER) || $user->conf->THEME_ELDY_USE_HOVER === '0')?'':($user->conf->THEME_ELDY_USE_HOVER === '1'?'edf4fb':$user->conf->THEME_ELDY_USE_HOVER));
}

//$colortopbordertitle1=$colorbackhmenu1;

// Set text color to black or white
$colorbackhmenu1=join(',',colorStringToArray($colorbackhmenu1));    // Normalize value to 'x,y,z'
$tmppart=explode(',',$colorbackhmenu1);
$tmpval=(! empty($tmppart[0]) ? $tmppart[0] : 0)+(! empty($tmppart[1]) ? $tmppart[1] : 0)+(! empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) $colortextbackhmenu='FFFFFF';
else $colortextbackhmenu='000000';

$colorbackvmenu1=join(',',colorStringToArray($colorbackvmenu1));    // Normalize value to 'x,y,z'
$tmppart=explode(',',$colorbackvmenu1);
$tmpval=(! empty($tmppart[0]) ? $tmppart[0] : 0)+(! empty($tmppart[1]) ? $tmppart[1] : 0)+(! empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) { $colortextbackvmenu='FFFFFF'; }
else { $colortextbackvmenu='000000'; }

$colorbacktitle1=join(',',colorStringToArray($colorbacktitle1));    // Normalize value to 'x,y,z'
$tmppart=explode(',',$colorbacktitle1);
if ($colortexttitle == '')
{
    $tmpval=(! empty($tmppart[0]) ? $tmppart[0] : 0)+(! empty($tmppart[1]) ? $tmppart[1] : 0)+(! empty($tmppart[2]) ? $tmppart[2] : 0);
    if ($tmpval <= 460) { $colortexttitle='FFFFFF'; $colorshadowtitle='888888'; }
    else { $colortexttitle='000000'; $colorshadowtitle='FFFFFF'; }
}

$colorbacktabcard1=join(',',colorStringToArray($colorbacktabcard1));    // Normalize value to 'x,y,z'
$tmppart=explode(',',$colorbacktabcard1);
$tmpval=(! empty($tmppart[0]) ? $tmppart[0] : 0)+(! empty($tmppart[1]) ? $tmppart[1] : 0)+(! empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) { $colortextbacktab='FFFFFF'; }
else { $colortextbacktab='111111'; }


// Format color value to match expected format (may be 'FFFFFF' or '255,255,255')
$colorbackhmenu1=join(',',colorStringToArray($colorbackhmenu1));
$colorbackvmenu1=join(',',colorStringToArray($colorbackvmenu1));
$colorbacktitle1=join(',',colorStringToArray($colorbacktitle1));
$colorbacktabcard1=join(',',colorStringToArray($colorbacktabcard1));
$colorbacktabactive=join(',',colorStringToArray($colorbacktabactive));
$colorbacklineimpair1=join(',',colorStringToArray($colorbacklineimpair1));
$colorbacklineimpair2=join(',',colorStringToArray($colorbacklineimpair2));
$colorbacklinepair1=join(',',colorStringToArray($colorbacklinepair1));
$colorbacklinepair2=join(',',colorStringToArray($colorbacklinepair2));
if ($colorbacklinepairhover != '') $colorbacklinepairhover=join(',',colorStringToArray($colorbacklinepairhover));
$colorbackbody=join(',',colorStringToArray($colorbackbody));
$colortexttitlenotab=join(',',colorStringToArray($colortexttitlenotab));
$colortexttitle=join(',',colorStringToArray($colortexttitle));
$colortext=join(',',colorStringToArray($colortext));
$colortextlink=join(',',colorStringToArray($colortextlink));

$nbtopmenuentries=$menumanager->showmenu('topnb');

print '/*'."\n";
print 'colorbackbody='.$colorbackbody."\n";
print 'colorbackvmenu1='.$colorbackvmenu1."\n";
print 'colorbackhmenu1='.$colorbackhmenu1."\n";
print 'colorbacktitle1='.$colorbacktitle1."\n";
print 'colorbacklineimpair1='.$colorbacklineimpair1."\n";
print 'colorbacklineimpair2='.$colorbacklineimpair2."\n";
print 'colorbacklinepair1='.$colorbacklinepair1."\n";
print 'colorbacklinepair2='.$colorbacklinepair2."\n";
print 'colorbacklinepairhover='.$colorbacklinepairhover."\n";
print '$colortexttitlenotab='.$colortexttitlenotab."\n";
print '$colortexttitle='.$colortexttitle."\n";
print '$colortext='.$colortext."\n";
print '$colortextlink='.$colortextlink."\n";
print '$colortextbackhmenu='.$colortextbackhmenu."\n";
print '$colortextbackvmenu='.$colortextbackvmenu."\n";
print 'dol_hide_topmenu='.$dol_hide_topmenu."\n";
print 'dol_hide_leftmenu='.$dol_hide_leftmenu."\n";
print 'dol_optimize_smallscreen='.$dol_optimize_smallscreen."\n";
print 'dol_no_mouse_hover='.$dol_no_mouse_hover."\n";
print 'dol_screenwidth='.$_SESSION['dol_screenwidth']."\n";
print 'dol_screenheight='.$_SESSION['dol_screenheight']."\n";
print 'fontsize='.$fontsize."\n";
print 'nbtopmenuentries='.$nbtopmenuentries."\n";
print '*/'."\n";

?>

/* ============================================================================== */
/* Default styles                                                                 */
/* ============================================================================== */


body {
<?php if (GETPOST('optioncss','aZ09') == 'print') {  ?>
	background-color: #FFFFFF;
<?php } else { ?>
	background: rgb(<?php print $colorbackbody; ?>);
<?php } ?>
	color: rgb(<?php echo $colortext; ?>);
	font-size: <?php print $fontsize ?>px;
	line-height: 1.4;
	font-family: <?php print $fontlist ?>;
    margin-top: 0;
    margin-bottom: 0;
    margin-right: 0;
    margin-left: 0;
    <?php print 'direction: '.$langs->trans("DIRECTION").";\n"; ?>
}

th a, .thumbstat, a.tab { color: rgb(<?php print $colortexttitle; ?>) !important; font-weight: bold !important; }
a.tab { font-weight: bold !important; }

a:link, a:visited, a:hover, a:active { font-family: <?php print $fontlist ?>; font-weight: normal; color: rgb(<?php print $colortextlink; ?>); text-decoration: none;  }
a:hover { text-decoration: underline; color: rgb(<?php print $colortextlink; ?>); }
a.commonlink { color: rgb(<?php print $colortextlink; ?>) !important; text-decoration: none; }
th.liste_titre a div div:hover, th.liste_titre_sel a div div:hover { text-decoration: underline; }
input, input.flat, textarea, textarea.flat, form.flat select, select, select.flat, .dataTables_length label select {
    background-color: #FFF;
}

input.select2-input {
	border-bottom: none ! important;
}
.select2-choice {
	border: none;
	border-bottom:  solid 1px rgba(0,0,0,.2) !important;	/* required to avoid to lose bottom line when focus is lost on select2. */
}

.liste_titre input[name=monthvalid], .liste_titre input[name=search_ordermonth], .liste_titre input[name=search_deliverymonth],
.liste_titre input[name=search_smonth], .liste_titre input[name=search_month], .liste_titre input[name=search_emonth], .liste_titre input[name=smonth], .liste_titre input[name=month], .liste_titre select[name=month],
.liste_titre input[name=month_lim], .liste_titre input[name=month_create] {
	margin-right: 4px;
}
input[type=submit] {
	margin-left: 5px;
}
input, input.flat, form.flat select, select, select.flat, .dataTables_length label select {
	<?php if (empty($conf->global->THEME_ELDY_SHOW_BORDER_INPUT))
	print "border: none;"
	?>
}
input, input.flat, textarea, textarea.flat, form.flat select, select, select.flat, .dataTables_length label select {
    font-size: <?php print $fontsize ?>px;
    font-family: <?php print $fontlist ?>;
    outline: none;
    margin: 0px 0px 0px 0px;
    border-bottom: solid 1px rgba(0,0,0,.2);
}

input {
    line-height: 17px;
	padding: 4px;
	padding-left: 5px;
}
select {
	padding: 4px;
	padding-left: 2px;
}
input, select {
	margin-left:0px;
	margin-bottom:1px;
	margin-top:1px;
}

/* Focus definitions must be after standard definition */
textarea:focus, button:focus {
    /* v6 box-shadow: 0 0 4px #8091BF; */
	border: 1px solid #aaa !important;
}
input:focus, select:focus {
	border-bottom: 1px solid #666;
}
textarea.cke_source:focus
{
	box-shadow: none;
}

select {
	/* padding: 4px 4px 2px 1px; */
}
textarea {
	border-radius: 0;
	border-top:solid 1px rgba(0,0,0,.2);
	border-left:solid 1px rgba(0,0,0,.2);
	border-right:solid 1px rgba(0,0,0,.2);
	border-bottom:solid 1px rgba(0,0,0,.2);

	padding:4px;
	margin-left:0px;
	margin-bottom:1px;
	margin-top:1px;
	}
input.removedassigned  {
	padding: 2px !important;
	vertical-align: text-bottom;
	margin-bottom: -3px;
}
input.smallpadd {	/* Used for timesheet input */
	padding-left: 0px !important;
	padding-right: 0px !important;
}
input.buttongen {
	vertical-align: middle;
}
span.timesheetalreadyrecorded input {
    border: none;
    border-bottom: solid 1px rgba(0,0,0,0.4);
    margin-right: 1px !important;
}

select.flat, form.flat select {
	font-weight: normal;
}
.optionblue {
	color: rgb(<?php echo $colortextlink; ?>) !important;
}
.select2-results .select2-highlighted.optionblue {
	color: #FFF !important;
}
.optiongrey, .opacitymedium {
	opacity: 0.5;
}
.opacityhigh {
	opacity: 0.2;
}
.opacitytransp {
	opacity: 0;
}
select:invalid {
	color: gray;
}
input:disabled {
	background:#ddd;
}

input.liste_titre {
	box-shadow: none !important;
}
input.removedfile {
	padding: 0px !important;
	border: 0px !important;
	vertical-align: text-bottom;
}
textarea:disabled {
	background:#ddd;
}
input[type=file ]    { background-color: transparent; border-top: none; border-left: none; border-right: none; box-shadow: none; }
input[type=checkbox] { background-color: transparent; border: none; box-shadow: none; }
input[type=radio]    { background-color: transparent; border: none; box-shadow: none; }
input[type=image]    { background-color: transparent; border: none; box-shadow: none; }
input:-webkit-autofill {
	background-color: #FDFFF0 !important;
	background-image:none !important;
	-webkit-box-shadow: 0 0 0 50px #FDFFF0 inset;
}
::-webkit-input-placeholder { color:#ccc; }
:-moz-placeholder { color:#bbb; } 			/* firefox 18- */
::-moz-placeholder { color:#bbb; } 			/* firefox 19+ */
:-ms-input-placeholder { color:#ccc; } 		/* ie */
input:-moz-placeholder { color:#ccc; }
input[name=weight], input[name=volume], input[name=surface], input[name=sizeheight] { margin-right: 6px; }
input[name=surface] { margin-right: 4px; }
fieldset { border: 1px solid #AAAAAA !important; }
.legendforfieldsetstep { padding-bottom: 10px; }

hr { border: 0; border-top: 1px solid #ccc; }

.button, .buttonDelete, input[name="sbmtConnexion"] {
    font-family: <?php print $fontlist ?>;
	border-color: #c5c5c5;
	border-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.25);
	display: inline-block;
	padding: 3px 14px;
	margin-bottom: 0;
	margin-top: 0;
	text-align: center;
	cursor: pointer;
	text-decoration: none !important;
	text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
	background-color: #f5f5f5;
	background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
	background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
	background-repeat: repeat-x;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
	border-color: #e6e6e6 #e6e6e6 #bfbfbf;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
	border: 1px solid #bbbbbb;
	border-bottom-color: #a2a2a2;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;
}
.button:focus, .buttonDelete:focus  {
	-moz-box-shadow: 0px 0px 6px 1px rgba(0, 0, 60, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
	-webkit-box-shadow: 0px 0px 6px 1px rgba(0, 0, 60, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
	box-shadow: 0px 0px 6px 1px rgba(0, 0, 60, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
}
.button:hover, .buttonDelete:hover   {
	/* warning: having a larger shadow has side effect when button is completely on left of a table */
	-moz-box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
	-webkit-box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
	box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.2), 0px 0px 0px rgba(60,60,60,0.1);
}
.button:disabled, .buttonDelete:disabled {
	opacity: 0.4;
    filter: alpha(opacity=40); /* For IE8 and earlier */
    box-shadow: none;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    cursor: auto;
}
.buttonRefused {
	pointer-events: none;
   	cursor: default;
	opacity: 0.4;
    filter: alpha(opacity=40); /* For IE8 and earlier */
    box-shadow: none;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
}
form {
    padding:0px;
    margin:0px;
}
form#addproduct {
    padding-top: 6px;
}
div.float
{
    float:<?php print $left; ?>;
}
div.floatright
{
    float:<?php print $right; ?>;
}
.inline-block
{
	display:inline-block;
}

th .button {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
	-moz-border-radius:0px !important;
	-webkit-border-radius:0px !important;
	border-radius:0px !important;
}
.maxwidthsearch {		/* Max width of column with the search picto */
	width: 54px;
}
.valigntop {
	vertical-align: top;
}
.valignmiddle {
	vertical-align: middle;
}
.valignbottom {
	vertical-align: bottom;
}
.valigntextbottom {
	vertical-align: text-bottom;
}
.centpercent {
	width: 100%;
}
.quatrevingtpercent, .inputsearch {
	width: 80%;
}
.soixantepercent {
	width: 60%;
}
textarea.centpercent {
	width: 96%;
}
.center {
    text-align: center;
    margin: 0px auto;
}
.left {
	text-align: <?php print $left; ?>;
}
.right {
	text-align: <?php print $right; ?>;
}
.nowrap {
	white-space: <?php print ($dol_optimize_smallscreen?'normal':'nowrap'); ?>;
}
.nowraponall {
	white-space: nowrap;
}
.nobold {
	font-weight: normal !important;
}
.nounderline {
    text-decoration: none;
}
.paddingleft {
	padding-<?php print $left; ?>: 4px;
}
.paddingleft2 {
	padding-<?php print $left; ?>: 2px;
}
.paddingright {
	padding-<?php print $right; ?>: 4px;
}
.paddingright2 {
	padding-<?php print $right; ?>: 2px;
}
.cursorpointer {
	cursor: pointer;
}
.cursormove {
	cursor: move;
}
.badge {
	display: inline-block;
	min-width: 10px;
	padding: 2px 5px;
	font-size: 10px;
	font-weight: 700;
	line-height: 0.9em;
	color: #fff;
	text-align: center;
	white-space: nowrap;
	vertical-align: baseline;
	background-color: #777;
	border-radius: 10px;
}
.borderrightlight
{
	border-right: 1px solid #DDD;
}
#formuserfile {
	margin-top: 4px;
}
#formuserfile_link {
	margin-left: 1px;
}
.listofinvoicetype {
	height: 28px;
	vertical-align: middle;
}
div.divsearchfield {
	float: <?php print $left; ?>;
	margin-<?php print $right; ?>: 12px;
    margin-<?php print $left; ?>: 2px;
	margin-top: 4px;
    margin-bottom: 4px;
  	padding-left: 2px;
}
div.confirmmessage {
	padding-top: 6px;
}
div.myavailability {
	padding-top: 6px;
}
.googlerefreshcal {
	padding-top: 4px;
	padding-bottom: 4px;
}
.checkallactions {
	/* vertical-align: text-bottom;
    margin-top: 6px; */
    margin-left: 2px;		/* left must be same than right to keep checkbox centered */
    margin-right: 2px;		/* left must be same than right to keep checkbox centered */
}
.selectlimit, .marginrightonly {
	margin-right: 10px !important;
}
.selectlimit, .selectlimit:focus {
    border-left: none !important;
    border-top: none !important;
    border-right: none !important;
    outline: none;
}
.strikefordisabled {
	text-decoration: line-through;
}
.widthdate {
	width: 130px;
}
/* using a tdoverflowxxx make the min-width not working */
.tdoverflow {
    max-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.tdoverflowmax100 {			/* For tdoverflow, the max-midth become a minimum ! */
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.tdoverflowmax200 {			/* For tdoverflow, the max-midth become a minimum ! */
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.tdoverflowmax200 {			/* For tdoverflow, the max-midth become a minimum ! */
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.tdoverflowmax300 {			/* For tdoverflow, the max-midth become a minimum ! */
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.tdoverflowauto {
    max-width: 0;
    overflow: auto;
}
.tablelistofcalendars {
	margin-top: 25px !important;
}
.amountalreadypaid {
}
.amountpaymentcomplete {
	color: #008800;
	font-weight: bold;
}
.amountremaintopay {
	color: #880000;
	font-weight: bold;
}
.amountremaintopayback {
	font-weight: bold;
}
.savingdocmask {
	margin-top: 6px;
	margin-bottom: 12px;
}


/* For the long description of module */
.moduledesclong p img, .moduledesclong p a img {
    max-width: 90% !important;
    height: auto !important;
}
.imgdoc {
    margin: 18px;
    border: 1px solid #ccc;
    box-shadow: 1px 1px 25px #aaa;
    max-width: calc(100% - 56px);
}


/* DOL_XXX for future usage (when left menu has been removed). If we do not use datatable */
/*.table-responsive {
    width: calc(100% - 330px);
    margin-bottom: 15px;
    overflow-y: hidden;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}*/
/* Style used for most tables */
.div-table-responsive, .div-table-responsive-no-min {
    overflow-x: auto;
    min-height: 0.01%;
}
.div-table-responsive {
    line-height: 100%;
}
/* Style used for full page tables with field selector and no content after table (priority before previous for such tables) */
div.fiche>form>div.div-table-responsive, div.fiche>form>div.div-table-responsive-no-min {
    overflow-x: auto;
}
div.fiche>form>div.div-table-responsive {
    min-height: 392px;
}

.flexcontainer {
    <?php if (in_array($conf->browser->name, array('chrome','firefox'))) echo 'display: inline-flex;'."\n"; ?>
    flex-flow: row wrap;
    justify-content: flex-start;
}
.thumbstat {
	flex: 1 1 116px;
}
.thumbstat150 {
	flex: 1 1 170px;
}
.thumbstat, .thumbstat150 {
    /* flex-grow: 1; */
    /* flex-shrink: 1; */
    /* flex-basis: 140px; */
	display: inline;
    width: 100%;
    justify-content: flex-start;
    align-self: flex-start;
}


/* ============================================================================== */
/* Styles to hide objects                                                         */
/* ============================================================================== */

.clearboth  { clear:both; }
.hideobject { display: none; }
.minwidth50  { min-width: 50px; }
/* rule for not too small screen only */
@media only screen and (min-width: <?php echo round($nbtopmenuentries * $fontsize * 3.4, 0) + 7; ?>px)
{
    .width50  { width: 50px; }
    .width100 { width: 100px; }
    .width200 { width: 200px; }
    .minwidth100 { min-width: 100px; }
    .minwidth200 { min-width: 200px; }
    .minwidth300 { min-width: 300px; }
    .minwidth400 { min-width: 400px; }
    .minwidth500 { min-width: 500px; }
    .minwidth50imp  { min-width: 50px !important; }
    .minwidth75imp  { min-width: 75px !important; }
    .minwidth100imp { min-width: 100px !important; }
    .minwidth200imp { min-width: 200px !important; }
    .minwidth300imp { min-width: 300px !important; }
    .minwidth400imp { min-width: 400px !important; }
    .minwidth500imp { min-width: 500px !important; }
}
.width50  { width: 50px; }
.width100 { width: 100px; }
.width200 { width: 200px; }
.maxwidth25  { max-width: 25px; }
.maxwidth50  { max-width: 50px; }
.maxwidth75  { max-width: 75px; }
.maxwidth100 { max-width: 100px; }
.maxwidth150 { max-width: 150px; }
.maxwidth200 { max-width: 200px; }
.maxwidth300 { max-width: 300px; }
.maxwidth400 { max-width: 400px; }
.maxwidth500 { max-width: 500px; }
.maxwidth50imp  { max-width: 50px !important; }
.maxwidth75imp  { max-width: 75px !important; }
.minheight20 { min-height: 20px; }
.minheight40 { min-height: 40px; }
.titlefieldcreate { width: 20%; }
.titlefield       { width: 25%; }
.titlefieldmiddle { width: 50%; }
.imgmaxwidth180 { max-width: 180px; }

/* Force values for small screen 1400 */
@media only screen and (max-width: 1400px)
{
	.titlefield { width: 30% !important; }
	.titlefieldcreate { width: 30% !important; }
	.minwidth50imp  { min-width: 50px !important; }
    .minwidth75imp  { min-width: 75px !important; }
    .minwidth100imp { min-width: 100px !important; }
    .minwidth200imp { min-width: 200px !important; }
    .minwidth300imp { min-width: 300px !important; }
    .minwidth400imp { min-width: 300px !important; }
    .minwidth500imp { min-width: 300px !important; }
}

/* Force values for small screen 1000 */
@media only screen and (max-width: 1000px)
{
    .maxwidthonsmartphone { max-width: 100px; }
	.minwidth50imp  { min-width: 50px !important; }
    .minwidth75imp  { min-width: 70px !important; }
    .minwidth100imp { min-width: 80px !important; }
    .minwidth200imp { min-width: 100px !important; }
    .minwidth300imp { min-width: 100px !important; }
    .minwidth400imp { min-width: 150px !important; }
    .minwidth500imp { min-width: 250px !important; }
}

/* Force values for small screen 570 */
@media only screen and (max-width: 570px)
{
	.divmainbodylarge { margin-left: 20px !important; margin-right: 20px !important; }

    .tdoverflowonsmartphone {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
	div.fiche {
		    margin-top: <?php print ($dol_hide_topmenu?'12':'6'); ?>px !important;
	}
	div.titre {
		line-height: 2em;
	}
    .border tbody tr, .border tbody tr td, div.tabBar table.border tr, div.tabBar table.border tr td, div.tabBar div.border .table-border-row, div.tabBar div.border .table-key-border-col, div.tabBar div.border .table-val-border-col {
    	height: 40px !important;
    }

    .quatrevingtpercent, .inputsearch {
    	width: 95%;
    }

	input, input[type=text], input[type=password], select, textarea     {
		min-width: 20px;
    	min-height: 1.4em;
    	line-height: 1.4em;
    	/* padding: .4em .1em; */
    	/* border-bottom: 1px solid #BBB; */
    	/* max-width: inherit; why this ? */
     }

    .hideonsmartphone { display: none; }
    .hideonsmartphoneimp { display: none !important; }
    .noenlargeonsmartphone { width : 50px !important; display: inline !important; }
    .maxwidthonsmartphone, #search_newcompany.ui-autocomplete-input { max-width: 100px; }
    .maxwidth50onsmartphone { max-width: 40px; }
    .maxwidth75onsmartphone { max-width: 50px; }
    .maxwidth100onsmartphone { max-width: 70px; }
    .maxwidth150onsmartphone { max-width: 120px; }
    .maxwidth200onsmartphone { max-width: 200px; }
    .maxwidth300onsmartphone { max-width: 300px; }
    .maxwidth400onsmartphone { max-width: 400px; }
	.minwidth50imp  { min-width: 50px !important; }
	.minwidth75imp  { min-width: 60px !important; }
    .minwidth100imp { min-width: 60px !important; }
    .minwidth200imp { min-width: 60px !important; }
    .minwidth300imp { min-width: 100px !important; }
    .minwidth400imp { min-width: 150px !important; }
    .minwidth500imp { min-width: 250px !important; }
    .titlefield { width: auto; }
    .titlefieldcreate { width: auto; }

	#tooltip {
		position: absolute;
		width: <?php print dol_size(300,'width'); ?>px;
	}

	/* intput, input[type=text], */
	select {
		width: 98%;
		min-width: 40px;
	}

	div.divphotoref {
		padding-right: 5px;
	    padding-bottom: 5px;
	}
    img.photoref, div.photoref {
    	border: none;
    	-moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        padding: 4px;
    	height: 20px;
    	width: 20px;
        object-fit: contain;
    }

	div.statusref {
    	padding-right: 10px;
   	}
}
.linkobject { cursor: pointer; }
<?php if (GETPOST('optioncss','aZ09') == 'print') { ?>
.hideonprint { display: none; }
<?php } ?>


/* ============================================================================== */
/* Styles for dragging lines                                                      */
/* ============================================================================== */

.dragClass {
	color: #002255;
}
td.showDragHandle {
	cursor: move;
}
.tdlineupdown {
	white-space: nowrap;
	min-width: 10px;
}


/* ============================================================================== */
/* Styles de positionnement des zones                                             */
/* ============================================================================== */

#id-container {
	display: table;					/* DOL_XXX Empeche fonctionnement correct du scroll horizontal sur tableau, avec datatable ou CSS */
	table-layout: fixed;
}
#id-right, #id-left {
	padding-top: 16px;
	padding-bottom: 16px;

	display: table-cell;			/* DOL_XXX Empeche fonctionnement correct du scroll horizontal sur tableau, avec datatable ou CSS */
	float: none;
	vertical-align: top;
}
#id-right {	/* This must stay id-right and not be replaced with echo $right */
	width: 100%;
	background: rgb(<?php print $colorbackbody; ?>);
}
#id-left {
/*	background-color: #fff;
	border-right: 1px #888 solid;
	height: calc(100% - 50px);*/
}

.side-nav {
	display: table-cell;
	border-right: 1px solid #090407;
	box-shadow: 3px 0 6px -2px #eee;
	background: rgb(<?php echo $colorbackvmenu1; ?>);
}
div.blockvmenulogo
{
	border-bottom: 0 !important;
}
div.blockvmenupair, div.blockvmenuimpair {
	border-top: none !important;
	border-left: none !important;
	border-right: none !important;
	border-bottom: 1px solid #e0e0e0;
	padding-left: 0 !important;
}
div.blockvmenuend, div.blockvmenubookmarks {
	border: none !important;
	padding-left: 0 !important;
}
div.vmenu, td.vmenu {
	padding-right: 10px !important;
}



/* For smartphone (testmenuhider is on) */
<?php if ($conf->browser->layout == 'phone' && ((GETPOST('testmenuhider','int') || ! empty($conf->global->MAIN_TESTMENUHIDER)) && empty($conf->global->MAIN_OPTIMIZEFORTEXTBROWSER))) { ?>
#id-container {
	width: 100%;
}
.side-nav {
	border-bottom: 1px solid #090407;
	background: #FFF;
	padding-left: 20px;
	padding-right: 20px;
}
.side-nav {
	position: absolute;
    z-index: 90;
    display: none;
}
div.blockvmenulogo
{
	border-bottom: 0 !important;
}
div.blockvmenusearch {
	padding-bottom: 12px !important;
	border-bottom: 1px solid #e0e0e0;
}
div.blockvmenupair, div.blockvmenuimpair, div.blockvmenubookmarks, div.blockvmenuend {
	border-top: none !important;
	border-left: none !important;
	border-right: none !important;
	border-bottom: 1px solid #e0e0e0;
	padding-left: 0 !important;
}
div.vmenu, td.vmenu {
	padding-right: 6px !important;
}
div.fiche {
	margin-<?php print $left; ?>: 6px !important;
	margin-<?php print $right; ?>: 6px !important;
}
<?php } ?>



div.fiche {
	margin-<?php print $left; ?>: <?php print (GETPOST('optioncss','aZ09') == 'print'?6:($dol_hide_leftmenu?'6':'26')); ?>px;
	margin-<?php print $right; ?>: <?php print (GETPOST('optioncss','aZ09') == 'print'?8:(empty($conf->dol_optimize_smallscreen)?'16':'12')); ?>px;
	<?php if (! empty($conf->dol_hide_leftmenu) && ! empty($conf->dol_hide_topmenu)) print 'margin-top: 4px;'."\n"; ?>
	<?php if (! empty($conf->dol_hide_leftmenu)) print 'margin-bottom: 12px;'."\n"; ?>
}
body.onlinepaymentbody div.fiche {	/* For online payment page */
	margin: 40px !important;
}
div.fiche>table:first-child {
	margin-bottom: 15px !important;
}
div.fichecenter {
	/* margin-top: 10px; */
	width: 100%;
	clear: both;	/* This is to have div fichecenter that are true rectangles */
}
div.fichecenterbis {
	margin-top: 8px;
}
div.fichethirdleft {
	<?php if ($conf->browser->layout != 'phone')   { print "float: ".$left.";\n"; } ?>
	<?php if ($conf->browser->layout != 'phone')   { print "width: 50%;\n"; } ?>
	<?php if ($conf->browser->layout == 'phone')   { print "padding-bottom: 6px;\n"; } ?>
}
div.fichetwothirdright {
	<?php if ($conf->browser->layout != 'phone')   { print "float: ".$right.";\n"; } ?>
	<?php if ($conf->browser->layout != 'phone')   { print "width: 50%;\n"; } ?>
	<?php if ($conf->browser->layout == 'phone')   { print "padding-bottom: 6px\n"; } ?>
}
div.fichehalfleft {
	<?php if ($conf->browser->layout != 'phone')   { print "float: ".$left.";\n"; } ?>
	<?php if ($conf->browser->layout != 'phone')   { print "width: 50%;\n"; } ?>
}
div.fichehalfright {
	<?php if ($conf->browser->layout != 'phone')   { print "float: ".$right.";\n"; } ?>
	<?php if ($conf->browser->layout != 'phone')   { print "width: 50%;\n"; } ?>
}
div.ficheaddleft {
	<?php if ($conf->browser->layout != 'phone')   { print "padding-".$left.": 16px;\n"; }
	else print "margin-top: 10px;\n"; ?>
}
/* Force values on one colum for small screen */
@media only screen and (max-width: 1000px)
{
    div.fiche {
    	margin-<?php print $left; ?>: <?php print (GETPOST('optioncss','aZ09') == 'print'?6:($dol_hide_leftmenu?'6':'20')); ?>px;
    	margin-<?php print $right; ?>: <?php print (GETPOST('optioncss','aZ09') == 'print'?8:6); ?>px;
    	<?php if (! empty($conf->dol_hide_leftmenu) && ! empty($conf->dol_hide_topmenu)) print 'margin-top: 4px;'; ?>
    }
    div.fichecenter {
    	width: 100%;
    	clear: both;	/* This is to have div fichecenter that are true rectangles */
    }
    div.fichecenterbis {
    	margin-top: 8px;
    }
    div.fichethirdleft {
    	float: none;
    	width: auto;
    	padding-bottom: 6px;
    }
    div.fichetwothirdright {
    	float: none;
    	width: auto;
    	padding-bottom: 6px;
    }
    div.fichehalfleft {
    	float: none;
    	width: auto;
    }
    div.fichehalfright {
    	float: none;
    	width: auto;
    }
    div.ficheaddleft {
    	<?php print "padding-".$left.": 0px;\n"; ?>
    	margin-top: 10px;
    }
}

/* For table into table into card */
div.ficheaddleft tr.liste_titre:first-child td table.nobordernopadding td {
    padding: 0 0 0 0;
}
div.nopadding {
	padding: 0 !important;
}

.containercenter {
	display : table;
	margin : 0px auto;
}

#pictotitle {
	margin-<?php echo $right; ?>: 8px;
	margin-bottom: 4px;
}
.pictoobjectwidth {
	width: 14px;
}
.pictosubstatus {
    padding-left: 2px;
    padding-right: 2px;
}
.pictostatus {
	width: 15px;
	vertical-align: middle;
	margin-top: -3px
}
.pictowarning, .pictopreview {
    padding-<?php echo $left; ?>: 3px;
}
.pictoedit, .pictowarning, .pictodelete {
    vertical-align: text-bottom;
}
.colorthumb {
	padding-left: 1px !important;
	padding-right: 1px;
	padding-top: 1px;
	padding-bottom: 1px;
	width: 44px;
	text-align:center;
}
div.attacharea {
	padding-top: 18px;
	padding-bottom: 10px;
}

div.arearef {
	padding-top: 2px;
	margin-bottom: 10px;
	padding-bottom: 7px;
}
div.arearefnobottom {
	padding-top: 2px;
	padding-bottom: 4px;
}
div.heightref {
	min-height: 80px;
}
div.divphotoref {
	padding-right: 20px;
}
div.statusref {
	float: right;
	padding-left: 12px;
	margin-top: 8px;
	margin-bottom: 10px;
	clear: both;
}
div.statusref img {
    padding-left: 8px;
}
img.photoref, div.photoref {
	border: 1px solid #CCC;
	-moz-box-shadow: 3px 3px 4px #DDD;
    -webkit-box-shadow: 3px 3px 4px #DDD;
    box-shadow: 3px 3px 4px #DDD;
    padding: 4px;
	height: 80px;
	width: 80px;
    object-fit: contain;
}
img.fitcontain {
    object-fit: contain;
}
div.photoref {
	display:table-cell;
	vertical-align:middle;
	text-align:center;
}
img.photorefnoborder {
    padding: 2px;
	height: 48px;
	width: 48px;
    object-fit: contain;
    border: 1px solid #AAA;
    border-radius: 100px;
}
.underrefbanner {
}
.underbanner {
	border-bottom: <?php echo $borderwith ?>px solid rgb(<?php echo $colortopbordertitle1 ?>);
}
.tdhrthin {
	margin: 0;
	padding-bottom: 0 !important;
}

/* ============================================================================== */
/* Menu top et 1ere ligne tableau                                                 */
/* ============================================================================== */

<?php
$minwidthtmenu=66;		/* minimum width for one top menu entry */
$heightmenu=46;			/* height of top menu, part with image */
$heightmenu2=48;        /* height of top menu, part with login  */
$disableimages = 0;
$maxwidthloginblock = 130;
if (! empty($conf->global->THEME_TOPMENU_DISABLE_IMAGE)) { $disableimages = 1; $maxwidthloginblock = 180; $minwidthtmenu=0; }
?>

div#id-top {
<?php if (GETPOST('optioncss','aZ09') == 'print') {  ?>
	display:none;
<?php } else { ?>
	background: rgb(0,0,0);
	/*-webkit-box-shadow: 0 0 6px rgba(0,0,0,0.4);
    box-shadow: 0 0 6px rgba(0,0,0,0.4); */
	/*
	background-image: linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -o-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -moz-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -webkit-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -ms-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -webkit-gradient( linear, left top, left bottom, color-stop(0, rgba(255,255,255,.1)), color-stop(1, rgba(0,0,0,.4)) );
	*/
	/*<?php if ($disableimages) { ?>
	height: 34px;
	<?php } else { ?>
	height: <?php print $heightmenu2; ?>px;
	<?php } ?>*/
<?php } ?>
}

div#tmenu_tooltip {
<?php if (GETPOST('optioncss','aZ09') == 'print') {  ?>
	display:none;
<?php } else { ?>
	padding-<?php echo $right; ?>: <?php echo ($maxwidthloginblock - 10); ?>px;
<?php } ?>
}

div.tmenusep {
<?php if ($disableimages) { ?>
	display: none;
<?php } ?>
}

div.tmenudiv {
<?php if (GETPOST('optioncss','aZ09') == 'print') {  ?>
	display:none;
<?php } else { ?>
    position: relative;
    display: block;
    white-space: nowrap;
    border-top: 0px;
    border-<?php print $left; ?>: 0px;
    border-<?php print $right; ?>: 0px;
    padding: 0px 0px 0px 0px;	/* t r b l */
    margin: 0px 0px 0px 0px;	/* t r b l */
	font-size: 13px;
    font-weight: normal;
	color: #000000;
    text-decoration: none;
<?php } ?>
}
div.tmenudisabled, a.tmenudisabled {
	opacity: 0.6;
}
a.tmenudisabled:link, a.tmenudisabled:visited, a.tmenudisabled:hover, a.tmenudisabled:active {
    font-weight: normal;
	padding: 0px 5px 0px 5px;
	white-space: nowrap;
	color: #<?php echo $colortextbackhmenu; ?>;
	text-decoration: none;
	cursor: not-allowed;
}

a.tmenu:link, a.tmenu:visited, a.tmenu:hover, a.tmenu:active {
    font-weight: normal;
	padding: 0px 5px 0px 3px;
	white-space: nowrap;
	color: #<?php echo $colortextbackhmenu; ?>;
    text-decoration: none;
}
a.tmenusel:link, a.tmenusel:visited, a.tmenusel:hover, a.tmenusel:active {
	font-weight: normal;
	padding: 0px 5px 0px 3px;
	margin: 0px 0px 0px 0px;
	white-space: nowrap;
	color: #<?php echo $colortextbackhmenu; ?>;
	text-decoration: none !important;
}


ul.tmenu {	/* t r b l */
    padding: 0px 0px 0px 0px;
    margin: 0px 0px 0px 0px;
	list-style: none;
	display: table;
}
ul.tmenu li {	/* We need this to have background color when menu entry wraps on new lines */
	/*	background: rgb(<?php echo $colorbackhmenu1 ?>);
	/*
	background-image: linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -o-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -moz-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -webkit-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -ms-linear-gradient(top, rgba(255,255,255,.1) 0%, rgba(0,0,0,.4) 100%);
	background-image: -webkit-gradient( linear, left top, left bottom, color-stop(0, rgba(255,255,255,.1)), color-stop(1, rgba(0,0,0,.4)) );
	*/
}
li.tmenu, li.tmenusel {
	<?php print $minwidthtmenu?'min-width: '.$minwidthtmenu.'px;':''; ?>
	text-align: center;
	vertical-align: bottom;
	<?php if (empty($conf->global->MAIN_MENU_INVERT)) { ?>
	float: <?php print $left; ?>;
    <?php } ?>
	position:relative;
	display: block;
	padding: 0 0 0 0;
	margin: 0 0 0 0;
	font-weight: normal;
}
li.menuhider:hover {
	background-image: none !important;
}
li.tmenusel, li.tmenu:hover {
    background-image: -o-linear-gradient(bottom, rgba(250,250,250,0.3) 0%, rgba(0,0,0,0.5) 100%);
    background-image: -moz-linear-gradient(bottom, rgba(0,0,0,0.5) 0%, rgba(250,250,250,0) 100%);
    background-image: -webkit-linear-gradient(bottom, rgba(0,0,0,0.5) 0%, rgba(250,250,250,0) 100%);
    background-image: -ms-linear-gradient(bottom, rgba(250,250,250,0.3) 0%, rgba(0,0,0,0.5) 100%);
    background-image: linear-gradient(bottom, rgba(250,250,250,0.3) 0%, rgba(0,0,0,0.5) 100%);
	/* background: rgb(<?php echo $colorbackhmenu1 ?>); */
}
.tmenuend .tmenuleft { width: 0px; }
.tmenuend { display: none; }
div.tmenuleft
{
	float: <?php print $left; ?>;
	margin-top: 0px;
	<?php if (empty($conf->dol_optimize_smallscreen)) { ?>
	width: 5px;
	background: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menutab-r.png',1); ?>) 0 -6px no-repeat;
	<?php } ?>
	<?php if ($disableimages) { ?>
	height: 26px;
	<?php } else { ?>
	height: <?php print $heightmenu; ?>px;
	<?php } ?>
}
div.tmenucenter
{
	padding-left: 0px;
	padding-right: 3px;
	<?php if ($disableimages) { ?>
	padding-top: 8px;
	height: 26px;
	<?php } else { ?>
	padding-top: 2px;
    height: <?php print $heightmenu; ?>px;
	<?php } ?>
    width: 100%;
	/*
    max-width: <?php echo round($fontsize * 8); ?>px;
   	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	color: #<?php echo $colortextbackhmenu; ?>;
	*/
}
#menu_titre_logo {
	padding-top: 0;
	padding-bottom: 0;
}
div.menu_titre {
	padding-top: 4px;
	padding-bottom: 4px;
	overflow: hidden;
    text-overflow: ellipsis;
    width: 188px;				/* required to have overflow working. must be same than menu_contenu */
}
.mainmenuaspan
{
<?php if ($disableimages) { ?>
	padding-<?php print $left; ?>: 4px;
	padding-<?php print $right; ?>: 2px;
<?php } else { ?>
	padding-<?php print $right; ?>: 4px;
<?php } ?>
}

div.mainmenu {
	position : relative;
	background-repeat:no-repeat;
	background-position:center top;
	height: <?php echo ($heightmenu-22); ?>px;
	margin-left: 0px;
	min-width: 40px;
}

/* Do not load menu img if hidden to save bandwidth */
<?php if (empty($dol_hide_topmenu)) { ?>

div.mainmenu.home{
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/home.png',1) ?>);
	background-position-x: center;
}

div.mainmenu.accountancy {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/money.png',1) ?>);
}

div.mainmenu.agenda {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/agenda.png',1) ?>);
}

div.mainmenu.bank {
    background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/bank.png',1) ?>);
}

div.mainmenu.cashdesk {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/pointofsale.png',1) ?>);
}

div.mainmenu.companies {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/company.png',1) ?>);
}

div.mainmenu.commercial {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/commercial.png',1) ?>);
}

div.mainmenu.ecm {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/ecm.png',1) ?>);
}

div.mainmenu.externalsite {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/externalsite.png',1) ?>);
}

div.mainmenu.ftp {
    background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/tools.png',1) ?>);
}

div.mainmenu.hrm {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/holiday.png',1) ?>);
}

div.mainmenu.members {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/members.png',1) ?>);
}

div.mainmenu.menu {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/menu.png',1) ?>);
	top: 7px;
}

div.mainmenu.products {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/products.png',1) ?>);
}

div.mainmenu.project {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/project.png',1) ?>);
}

div.mainmenu.tools {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/tools.png',1) ?>);
}

div.mainmenu.websites {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus/externalsite.png',1) ?>);
}

<?php
// Add here more div for other menu entries. moduletomainmenu=array('module name'=>'name of class for div')

$moduletomainmenu=array('user'=>'','syslog'=>'','societe'=>'companies','projet'=>'project','propale'=>'commercial','commande'=>'commercial',
	'produit'=>'products','service'=>'products','stock'=>'products',
	'don'=>'accountancy','tax'=>'accountancy','banque'=>'accountancy','facture'=>'accountancy','compta'=>'accountancy','accounting'=>'accountancy','adherent'=>'members','import'=>'tools','export'=>'tools','mailing'=>'tools',
	'contrat'=>'commercial','ficheinter'=>'commercial','deplacement'=>'commercial',
	'fournisseur'=>'companies',
	'barcode'=>'','fckeditor'=>'','categorie'=>'',
);
$mainmenuused='home';
foreach($conf->modules as $val)
{
	$mainmenuused.=','.(isset($moduletomainmenu[$val])?$moduletomainmenu[$val]:$val);
}
//var_dump($mainmenuused);
$mainmenuusedarray=array_unique(explode(',',$mainmenuused));

$generic=1;
// Put here list of menu entries when the div.mainmenu.menuentry was previously defined
$divalreadydefined=array('home','companies','products','commercial','externalsite','accountancy','project','tools','members','agenda','ftp','holiday','hrm','bookmark','cashdesk','ecm','geoipmaxmind','gravatar','clicktodial','paypal','stripe','webservices','websites');
// Put here list of menu entries we are sure we don't want
$divnotrequired=array('multicurrency','salaries','margin','opensurvey','paybox','expensereport','incoterm','prelevement','propal','workflow','notification','supplier_proposal','cron','product','productbatch','expedition');
foreach($mainmenuusedarray as $val)
{
	if (empty($val) || in_array($val,$divalreadydefined)) continue;
	if (in_array($val,$divnotrequired)) continue;
	//print "XXX".$val;

	// Search img file in module dir
	$found=0; $url='';
	foreach($conf->file->dol_document_root as $dirroot)
	{
		if (file_exists($dirroot."/".$val."/img/".$val.".png"))
		{
			$url=dol_buildpath('/'.$val.'/img/'.$val.'.png', 1);
			$found=1;
			break;
		}
	}
	// Img file not found
	if (! $found)
	{
		$url=dol_buildpath($path.'/theme/'.$theme.'/img/menus/generic'.$generic.".png",1);
		$found=1;
		if ($generic < 4) $generic++;
		print "/* A mainmenu entry was found but img file ".$val.".png not found (check /".$val."/img/".$val.".png), so we use a generic one */\n";
	}
	if ($found)
	{
		print "div.mainmenu.".$val." {\n";
		print "	background-image: url(".$url.");\n";
		print "}\n";
	}
}
// End of part to add more div class css
?>

<?php
}	// End test if $dol_hide_topmenu
?>

.tmenuimage {
    padding:0 0 0 0 !important;
    margin:0 0px 0 0 !important;
    <?php if ($disableimages) { ?>
    	display: none;
    <?php } ?>
}



/* Login */

.container-login100
{
	background: #f0f0f0;
	background-size: cover; 
	background-position: center center; 
	background-attachment: fixed; 
	background-repeat: no-repeat; 
	background-image: url('img/background.jpg');
}
.login_center {
	display: table-cell;
    vertical-align: middle;
}
.login_vertical_align {
	padding: 10px;
	padding-bottom: 80px;
}
form#login {
	padding-bottom: 30px;
	font-size: 13px;
	vertical-align: middle;
}
.login_table_title {
	max-width: 530px;
	color: #aaa !important;
	padding-bottom: 20px;
	/* text-shadow: 1px 1px 1px #FFF; */
}
.login_table label {
	text-shadow: 1px 1px 1px #FFF;
}
.login_table {
	margin: 0px auto;  /* Center */
	padding-left:6px;
	padding-right:6px;
	padding-top:16px;
	padding-bottom:12px;
	max-width: 560px;

	background-color: #FFFFFF;

	-moz-box-shadow: 0 2px 23px 2px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(60,60,60,0.15);
	-webkit-box-shadow: 0 2px 23px 2px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(60,60,60,0.15);
	box-shadow: 0 2px 23px 2px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(60,60,60,0.15);

	border-radius: 5px;
	/*border-top:solid 1px rgba(180,180,180,.4);
	border-left:solid 1px rgba(180,180,180,.4);
	border-right:solid 1px rgba(180,180,180,.4);
	border-bottom:solid 1px rgba(180,180,180,.4);*/
}
.login_table input#username, .login_table input#password, .login_table input#securitycode {
	border: none;
	border-bottom: solid 1px rgba(180,180,180,.4);
	padding: 5px;
	margin-left: 18px;
	margin-top: 5px;
}
.login_table input#username:focus, .login_table input#password:focus, .login_table input#securitycode:focus {
	outline: none !important;
	/* box-shadow: none;
	-webkit-box-shadow: 0 0 0 50px #FFF inset;
	box-shadow: 0 0 0 50px #FFF inset;*/
}
.login_main_message {
	text-align: center;
	max-width: 570px;
	margin-bottom: 10px;
}
.login_main_message .error {
	border: 1px solid #caa;
	padding: 10px;
}
div#login_left, div#login_right {
	display: inline-block;
	min-width: 245px;
	padding-top: 10px;
	padding-left: 16px;
	padding-right: 16px;
	text-align: center;
	vertical-align: middle;
}
div#login_right select#entity {
    margin-top: 10px;
}
table.login_table tr td table.none tr td {
	padding: 2px;
}
table.login_table_securitycode {
	border-spacing: 0px;
}
table.login_table_securitycode tr td {
	padding-left: 0px;
	padding-right: 4px;
}
#securitycode {
	min-width: 60px;
}
#img_securitycode {
	border: 1px solid #DDDDDD;
}
#img_logo, .img_logo {
	max-width: 170px;
	max-height: 90px;
}

div.login_block {
	position: absolute;
	text-align: <?php print $right; ?>;
	<?php print $right; ?>: 5px;
	top: 4px;
	font-weight: bold;
	max-width: <?php echo $maxwidthloginblock; ?>px;
	<?php if (GETPOST('optioncss','aZ09') == 'print') { ?>
	display: none;
	<?php } ?>
}
div.login_block a {
	color: #<?php echo $colortextbackvmenu; ?>;
}
div.login_block table {
	display: inline;
}
div.login {
	white-space:nowrap;
	font-weight: bold;
	float: right;
}
div.login a {
	color: #<?php echo $colortextbackvmenu; ?>;
}
div.login a:hover {
	color: #<?php echo $colortextbackvmenu; ?>;
	text-decoration:underline;
}
div.login_block_user {
	display: inline-block;
	<?php if (empty($conf->global->THEME_TOPMENU_DISABLE_IMAGE)) { ?>
	min-width: 120px;
	<?php } ?>
}
div.login_block_other {
	display: inline-block;
	clear: both;
}
div.login_block_other { padding-top: 3px; text-align: right; }
.login_block_elem {
	float: right;
	vertical-align: top;
	padding: 0px 3px 0px 4px !important;
	height: 16px;
}
.atoplogin, .atoplogin:hover {
	color: #<?php echo $colortextbackhmenu; ?> !important;
	font-weight: normal !important;
}
.alogin, .alogin:hover {
	font-weight: normal !important;
	font-size: <?php echo $fontsizesmaller; ?>px !important;
	padding-top: 2px;
}
.alogin:hover, .atoplogin:hover {
	text-decoration:underline !important;
}
span.fa.atoplogin, span.fa.atoplogin:hover {
    font-size: 16px;
    text-decoration: none !important;
}
img.login, img.printer, img.entity {
	/* padding: 0px 0px 0px 4px; */
	/* margin: 0px 0px 0px 8px; */
	text-decoration: none;
	color: white;
	font-weight: bold;
}
.userimgatoplogin img.userphoto {		/* size for user photo in login bar */
	width: 16px;
    height: 16px;
    border-radius: 8px;
    background-size: contain;
    background-size: contain;
}
img.userphoto {			/* size for user photo in lists */
	border-radius: 9px;
	width: 18px;
    height: 18px;
    background-size: contain;
    vertical-align: middle;
}
img.userphotosmall {			/* size for user photo in lists */
	border-radius: 6px;
	width: 12px;
    height: 12px;
    background-size: contain;
    vertical-align: middle;
    background-color: #FFF;
}
.span-icon-user {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/object_user.png',1); ?>);
	background-repeat: no-repeat;
}
.span-icon-password {
	background-image: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/lock.png',1); ?>);
	background-repeat: no-repeat;
}
/*
.span-icon-user input, .span-icon-password input {
	/* margin-left: 18px; */
	margin-left: 0px;
}*/

/* ============================================================================== */
/* Menu gauche                                                                    */
/* ============================================================================== */

div.vmenu, td.vmenu {
    margin-<?php print $right; ?>: 2px;
    position: relative;
    float: left;
    padding: 0px;
    padding-bottom: 0px;
    padding-top: 1px;
    width: 190px;
}

.vmenu {
    width: 190px;
	margin-left: 6px;
	<?php if (GETPOST('optioncss','aZ09') == 'print') { ?>
    display: none;
	<?php } ?>
}

/* Force vmenusearchselectcombo with type=text differently than without because beautify with select2 affect vmenusearchselectcombo differently */
input.vmenusearchselectcombo[type=text] {
	width: 180px !important;
}
.vmenusearchselectcombo {
	width: 188px;
}

.menu_contenu {
	padding-top: 3px;
	padding-bottom: 3px;
	overflow: hidden;
    text-overflow: ellipsis;
    width: 188px;				/* required to have overflow working. must be same than .menu_titre */
}
#menu_contenu_logo { /* padding-top: 0; */ }
.companylogo { }
.searchform { padding-top: 10px; }

a.vmenu:link, a.vmenu:visited, a.vmenu:hover, a.vmenu:active { white-space: nowrap; font-size:<?php print $fontsize ?>px; font-family: <?php print $fontlist ?>; text-align: <?php print $left; ?>; font-weight: bold; }
font.vmenudisabled  { font-size:<?php print $fontsize ?>px; font-family: <?php print $fontlist ?>; text-align: <?php print $left; ?>; font-weight: bold; color: #aaa; margin-left: 4px; }
a.vmenu:link, a.vmenu:visited { color: #<?php echo $colortextbackvmenu; ?>; }

a.vsmenu:link, a.vsmenu:visited, a.vsmenu:hover, a.vsmenu:active, span.vsmenu { font-size:<?php print $fontsize ?>px; font-family: <?php print $fontlist ?>; text-align: <?php print $left; ?>; font-weight: normal; color: #202020; margin: 1px 1px 1px 6px; }
font.vsmenudisabled { font-size:<?php print $fontsize ?>px; font-family: <?php print $fontlist ?>; text-align: <?php print $left; ?>; font-weight: normal; color: #aaa; }
a.vsmenu:link, a.vsmenu:visited { color: #<?php echo $colortextbackvmenu; ?>; white-space: nowrap; }
font.vsmenudisabledmargin { margin: 1px 1px 1px 6px; }
li a.vsmenudisabled, li.vsmenudisabled { color: #aaa !important; }

a.help:link, a.help:visited, a.help:hover, a.help:active, span.help { font-size:<?php print $fontsizesmaller ?>px; font-family: <?php print $fontlist ?>; text-align: <?php print $left; ?>; font-weight: normal; color: #aaa; text-decoration: none; }

.vmenu div.blockvmenufirst, .vmenu div.blockvmenulogo, .vmenu div.blockvmenusearchphone, .vmenu div.blockvmenubookmarks
{
    border-top: 1px solid #BBB;
}
a.vsmenu.addbookmarkpicto {
    padding-right: 10px;
}
div.blockvmenusearchphone
{
	border-bottom: none !important;
}
.vmenu div.blockvmenuend, .vmenu div.blockvmenulogo
{
	margin: 0 0 8px 2px;
}
.vmenu div.blockvmenusearch
{
	padding-bottom: 4px;
/*	border-bottom: 1px solid #e0e0e0;  */
}
.vmenu div.blockvmenuend
{
	padding-bottom: 5px;
}
.vmenu div.blockvmenulogo
{
	padding-bottom: 10px;
	padding-top: 0;
}
div.blockvmenubookmarks
{
	padding-top: 10px !important;
	padding-bottom: 16px !important;
}
div.blockvmenupair, div.blockvmenuimpair, div.blockvmenubookmarks, div.blockvmenuend
{
	font-family: <?php print $fontlist ?>;
	color: #000000;
	text-align: <?php print $left; ?>;
	text-decoration: none;
    padding-left: 5px;
    padding-right: 1px;
    padding-top: 3px;
    padding-bottom: 3px;
    margin: 0 0 0 2px;

	background: rgb(<?php echo $colorbackvmenu1; ?>);

    border-left: 1px solid #AAA;
    border-right: 1px solid #BBB;
}

div.blockvmenusearch
{
	font-family: <?php print $fontlist ?>;
	color: #000000;
	text-align: <?php print $left; ?>;
	text-decoration: none;
    margin: 1px 0px 0px 2px;
	background: rgb(<?php echo $colorbackvmenu1; ?>);
}

div.blockvmenusearch > form > div {
	padding-top: 3px;
}
div.blockvmenusearch > form > div > label {
	padding-right: 2px;
}

div.blockvmenuhelp
{
<?php if (empty($conf->dol_optimize_smallscreen)) { ?>
	font-family: <?php print $fontlist ?>;
	color: #000000;
	text-align: center;
	text-decoration: none;
    padding-left: 0px;
    padding-right: 6px;
    padding-top: 3px;
    padding-bottom: 3px;
    margin: 4px 0px 0px 0px;
<?php } else { ?>
    display: none;
<?php } ?>
}


td.barre {
	border-right: 1px solid #000000;
	border-bottom: 1px solid #000000;
	background: #b3c5cc;
	font-family: <?php print $fontlist ?>;
	color: #000000;
	text-align: <?php print $left; ?>;
	text-decoration: none;
}

td.barre_select {
	background: #b3c5cc;
	color: #000000;
}

td.photo {
	background: #F4F4F4;
	color: #000000;
    border: 1px solid #bbb;
}

/* ============================================================================== */
/* Panes for Main                                                   */
/* ============================================================================== */

/*
 *  PANES and CONTENT-DIVs
 */

#mainContent, #leftContent .ui-layout-pane {
    padding:    0px;
    overflow:	auto;
}

#mainContent, #leftContent .ui-layout-center {
	padding:    0px;
	position:   relative; /* contain floated or positioned elements */
    overflow:   auto;  /* add scrolling to content-div */
}


/* ============================================================================== */
/* Toolbar for ECM or Filemanager                                                 */
/* ============================================================================== */

td.ecmroot {
    padding-bottom: 0 !important;
}

.largebutton {
    /*background-image: -o-linear-gradient(bottom, rgba(200,200,200,0.1) 0%, rgba(255,255,255,0.3) 120%) !important;
    background-image: -moz-linear-gradient(bottom, rgba(200,200,200,0.1) 0%, rgba(255,255,255,0.3) 120%) !important;
    background-image: -webkit-linear-gradient(bottom, rgba(200,200,200,0.1) 0%, rgba(255,255,255,0.3) 120%) !important;
    background-image: -ms-linear-gradient(bottom, rgba(200,200,200,0.1) 0%, rgba(255,255,255,0.3) 120%) !important;
    background-image: linear-gradient(bottom, rgba(200,200,200,0.1) 0%, rgba(255,255,255,0.3) 120%) !important;

    background: #FFF;
    background-repeat: repeat-x !important;
    */
	border-top: 1px solid #CCC !important;

    /*-moz-border-radius: 4px 4px 4px 4px !important;
	-webkit-border-radius: 4px 4px 4px 4px !important;
	border-radius: 4px 4px 4px 4px !important;
    -moz-box-shadow: 2px 2px 4px #DDD;
    -webkit-box-shadow: 2px 2px 4px #DDD;
    box-shadow: 2px 2px 4px #DDD;
	*/

    padding: 10px 4px 14px 4px !important;
    min-height: 32px;
}


a.toolbarbutton {
    margin-top: 0px;
    margin-left: 4px;
    margin-right: 4px;
    height: 30px;
}
img.toolbarbutton {
	margin-top: 1px;
    height: 30px;
}





/* ============================================================================== */
/* Onglets                                                                        */
/* ============================================================================== */
div.tabs {
    text-align: <?php print $left; ?>;
    padding-left: 6px !important;
    padding-right: 6px !important;
	clear:both;
	height:100%;
	/* background-image: linear-gradient(to top,#f6f6f6 0,#fff 8px);  */
}
div.tabsElem {
	margin-top: 1px;
}	/* To avoid overlap of tabs when not browser */

div.tabBar {
    color: #<?php echo $colortextbacktab; ?>;
    padding-top: 16px;
    padding-left: 0px; padding-right: 0px;
    padding-bottom: 2px;
    margin: 0px 0px 16px 0px;
    border-top: 1px solid #BBB;
    /* border-bottom: 1px solid #AAA; */
	width: auto;
	background: rgb(<?php echo $colorbacktabcard1; ?>);
}
div.tabBar div.titre {
	padding-top: 10px;
}

div.tabBarWithBottom {
	padding-bottom: 18px;
	border-bottom: 1px solid #aaa;
}
div.tabBar table.tableforservicepart2:last-child {
    border-bottom: 1px solid #aaa;
}

div.popuptabset {
	padding: 6px;
	background: #fff;
	border: 1px solid #888;
}
div.popuptab {
	padding-top: 3px;
	padding-bottom: 3px;
	padding-left: 5px;
	padding-right: 5px;
}
div.tabsAction {
    margin: 20px 0em 20px 0em;
    padding: 0em 0em;
    text-align: right;
}
div.tabsActionNoBottom {
    margin-bottom: 0px;
}
div.tabsAction > a {
	margin-bottom: 16px !important;
}

a.tabTitle {
    color:rgba(0,0,0,.5) !important;
    text-shadow:1px 1px 1px #ffffff;
	font-family: <?php print $fontlist ?>;
	font-weight: normal !important;
    padding: 4px 6px 2px 0px;
    margin-<?php print $right; ?>: 10px;
    text-decoration: none;
    white-space: nowrap;
}

a.tabunactive {
    color: rgb(<?php print $colortextlink; ?>) !important;
}
a.tab:link, a.tab:visited, a.tab:hover, a.tab#active {
	font-family: <?php print $fontlist ?>;
	padding: 12px 9px 12px;
    margin: 0em 0.2em;
    text-decoration: none;
    white-space: nowrap;

	border-right: 1px solid transparent;
	border-left: 1px solid transparent;
	border-top: 1px solid transparent;
	border-bottom: 0px !important;

	background-image: none !important;
}
.tabactive, a.tab#active {
	color: #<?php echo $colortextbacktab; ?> !important;
	background: rgb(<?php echo $colorbacktabcard1; ?>) !important;
	margin: 0 0.2em 0 0.2em !important;

	border-right: 1px solid #AAA !important;
	border-left: 1px solid #AAA !important;
	border-top: 2px solid rgb(<?php echo $colortopbordertitle1; ?>) !important;
	/*
	box-shadow: 0 -1px 4px rgba(0,0,0,.1);
	-moz-box-shadow: 0 -1px 4px rgba(0,0,0,.1);
	-webkit-box-shadow: 0 -1px 4px rgba(0,0,0,.1);
	-moz-border-radius:4px 4px 0 0;
    -webkit-border-radius: 4px 4px 0 0;
	border-radius: 4px 4px 0 0;
	*/
}
a.tab:hover
{
	/*
	background: rgba(<?php echo $colorbacktabcard1; ?>, 0.5)  url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/nav-overlay3.png',1); ?>) 50% 0 repeat-x;
	color: #<?php echo $colortextbacktab; ?>;
	*/
	text-decoration: underline;
}
a.tabimage {
    color: #434956;
	font-family: <?php print $fontlist ?>;
    text-decoration: none;
    white-space: nowrap;
}

td.tab {
    background: #dee7ec;
}

span.tabspan {
    background: #dee7ec;
    color: #434956;
	font-family: <?php print $fontlist ?>;
    padding: 0px 6px;
    margin: 0em 0.2em;
    text-decoration: none;
    white-space: nowrap;
    -moz-border-radius:4px 4px 0px 0px;
	-webkit-border-radius:4px 4px 0px 0px;
	border-radius:4px 4px 0px 0px;

    border-<?php print $right; ?>: 1px solid #555555;
    border-<?php print $left; ?>: 1px solid #D8D8D8;
    border-top: 1px solid #D8D8D8;
}

/* ============================================================================== */
/* Boutons actions                                                                */
/* ============================================================================== */

div.divButAction {
	margin-bottom: 1.4em;
}

span.butAction, span.butActionDelete {
	cursor: pointer;
}

.butAction, .butAction:link, .butAction:visited, .butAction:hover, .butAction:active, .butActionDelete, .butActionDelete:link, .butActionDelete:visited, .butActionDelete:hover, .butActionDelete:active {
	text-decoration: none;
	margin: 0em <?php echo ($dol_optimize_smallscreen?'0.7':'0.9'); ?>em;
	padding: 0.6em <?php echo ($dol_optimize_smallscreen?'0.4':'0.7'); ?>em;
	font-family: <?php print $fontlist ?>;
    font-weight: normal;
    border-color: #c5c5c5;
    border-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.25);
    display: inline-block;
    text-align: center;
    cursor: pointer;
    color: #fff;
    background: rgb(<?php echo $colorbackhmenu1 ?>);
    border: 1px solid rgb(<?php echo $colorbackhmenu1 ?>);
}

.butAction:hover   {
  -moz-box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
  -webkit-box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
  box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
}

.butActionDelete, .butActionDelete:link, .butActionDelete:visited, .butActionDelete:hover, .butActionDelete:active, .buttonDelete {
    background: #633;
    border: 1px solid #633;
    color: #FFF;
}

.butActionDelete:hover {
  -moz-box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
  -webkit-box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
  box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
}

.butActionRefused {
    text-decoration: none !important;
	white-space: nowrap !important;
	cursor: not-allowed !important;
	margin: 0em <?php echo ($dol_optimize_smallscreen?'0.7':'0.9'); ?>em;
	padding: 0.6em <?php echo ($dol_optimize_smallscreen?'0.4':'0.7'); ?>em;
    font-family: <?php print $fontlist ?> !important;
    font-weight: normal !important;
    display: inline-block;
    text-align: center;
    cursor: pointer;
    color: #999 !important;
    border: 1px solid #bbb;
}

.butActionTransparent {
	color: #222 ! important;
	background-color: transparent ! important;
}

<?php if (! empty($conf->global->MAIN_BUTTON_HIDE_UNAUTHORIZED) && (! $user->admin)) { ?>
.butActionRefused {
	display: none;
}
<?php } ?>



/* ============================================================================== */
/* Tables                                                                         */
/* ============================================================================== */

.allwidth {
	width: 100%;
}

#undertopmenu {
	background-repeat: repeat-x;
	margin-top: <?php echo ($dol_hide_topmenu?'6':'0'); ?>px;
}


.paddingrightonly {
	border-collapse: collapse;
	border: 0px;
	margin-left: 0px;
	padding-<?php print $left; ?>: 0px !important;
	padding-<?php print $right; ?>: 4px !important;
}
.nocellnopadd {
	list-style-type:none;
	margin: 0px !important;
	padding: 0px !important;
}
tr.nocellnopadd td.nobordernopadding, tr.nocellnopadd td.nocellnopadd
{
	border: 0px;
}

.notopnoleft {
	border-collapse: collapse;
	border: 0px;
	padding-top: 0px;
	padding-<?php print $left; ?>: 0px;
	padding-<?php print $right; ?>: 16px;
	padding-bottom: 4px;
	margin-right: 0px;
}
.notopnoleftnoright {
	border-collapse: collapse;
	border: 0px;
	padding-top: 0px;
	padding-left: 0px;
	padding-right: 0px;
	padding-bottom: 4px;
	margin: 0px 0px 0px 0px;
}


table.border, table.dataTable, .table-border, .table-border-col, .table-key-border-col, .table-val-border-col, div.border {
	border-collapse: collapse !important;
	padding: 1px 2px 1px 3px;			/* t r b l */
}
table.borderplus {
	border: 1px solid #BBB;
}
.border tbody tr, .border tbody tr td, div.tabBar table.border tr, div.tabBar table.border tr td, div.tabBar div.border .table-border-row, div.tabBar div.border .table-key-border-col, div.tabBar div.border .table-val-border-col {
	height: 22px;
}
div.tabBar div.border .table-border-row, div.tabBar div.border .table-key-border-col, div.tabBar .table-val-border-col {
	vertical-align: middle;
}
div .tdtop {
    vertical-align: top !important;
	/* padding-top: 8px !important; */
	padding-bottom: 2px !important;
	padding-bottom: 0px;
}

table.border td, div.border div div.tagtd {
	padding: 5px 2px 5px 2px;
	border-collapse: collapse;
}
div.tabBar .fichecenter table.border>tbody>tr>td, div.tabBar .fichecenter div.border div div.tagtd, div.tabBar div.border div div.tagtd
{
	padding-top: 5px;
	border-bottom: 1px solid #E0E0E0;
}

td.border, div.tagtable div div.border {
	border-top: 1px solid #000000;
	border-right: 1px solid #000000;
	border-bottom: 1px solid #000000;
	border-left: 1px solid #000000;
}
.table-key-border-col {
	/* width: 25%; */
	vertical-align:top;
}
.table-val-border-col {
	width:auto;
}


/* Main boxes */
.noborderbottom {
    border-bottom: none !important;
}
.ficheaddleft table.noborder {
	margin: 0px 0px 0px 0px;
}
table.liste, table.noborder, table.formdoc, div.noborder {
	width: 100%;

	border-collapse: separate !important;
	border-spacing: 0px;

	border-top-width: <?php echo $borderwith ?>px;
	border-top-color: rgb(<?php echo $colortopbordertitle1 ?>);
	border-top-style: solid;

	border-bottom-width: 1px;
	border-bottom-color: #BBB;
	border-bottom-style: solid;

	margin: 0px 0px 5px 0px;
}
div.tabBar div.ficheaddleft table.noborder:last-of-type {
    border-bottom: 1px solid #aaa;
}
div.tabBar div.ficheaddleft table.noborder {
    border-bottom: none;
}

table.paddingtopbottomonly tr td {
	padding-top: 1px;
	padding-bottom: 2px;
}
.liste_titre_filter {
	background: rgb(<?php echo $colorbacktitle1; ?>) !important;
}
tr.liste_titre_filter td.liste_titre {
    border-bottom: 1px solid #ddd;
}
.liste_titre_create td, .liste_titre_create th, .liste_titre_create .tagtd
{
    /*border-top-width: 1px;
    border-top-color: rgb(<?php echo $colortopbordertitle1 ?>);
    border-top-style: solid;*/
}
.liste_titre_add td, .liste_titre_add th, .liste_titre_add .tagtd
{
    border-top-width: 2px;
    border-top-color: rgb(<?php echo $colortopbordertitle1 ?>);
    border-top-style: solid;
}
table.liste tr, table.noborder tr, div.noborder form {
	border-top-color: #FEFEFE;
	min-height: 20px;
}
table.liste th, table.noborder th, table.noborder tr.liste_titre td, table.noborder tr.box_titre td {
	padding: 7px 2px 7px 3px;			/* t r b l */
}
table.liste td, table.noborder td, div.noborder form div {
	padding: 7px 2px 7px 3px;			/* t r b l */
}
div.liste_titre_bydiv .divsearchfield {
	padding: 2px 1px 2px 0px;			/* t r b l */
}

tr.box_titre .nobordernopadding td {
	padding: 0 ! important;
}
table.nobordernopadding {
	border-collapse: collapse !important;
	border: 0;
}
table.nobordernopadding tr {
	border: 0 !important;
	padding: 0 0 !important;
}
table.nobordernopadding tr td {
	border: 0 !important;
	padding: 0 3px 0 0;
}
table.border tr td table.nobordernopadding tr td {
	padding-top: 0;
	padding-bottom: 0;
}
td.borderright {
    border: none;	/* to erase value for table.nobordernopadding td */
	border-right-width: 1px !important;
	border-right-color: #BBB !important;
	border-right-style: solid !important;
}


/* For table with no filter before */
table.listwithfilterbefore {
	border-top: none !important;
}


.tagtable, .table-border { display: table; }
.tagtr, .table-border-row  { display: table-row; }
.tagtd, .table-border-col, .table-key-border-col, .table-val-border-col { display: table-cell; }


/* Pagination */
div.refidpadding  {
	padding-top: 3px;
}
div.refid  {
	font-weight: bold;
  	color: #868;
  	font-size: 160%;
}
div.refidno  {
	padding-top: 3px;
	font-weight: normal;
  	color: #444;
  	font-size: <?php print $fontsize ?>px;
  	line-height: 21px;
}
div.refidno form {
    display: inline-block;
}

div.pagination {
	float: right;
}
div.pagination a {
	font-weight: normal;
}
div.pagination ul
{
  list-style: none;
  display: inline-block;
  padding-left: 0px;
  padding-right: 0px;
  margin: 0;
}
div.pagination li {
  display: inline-block;
  padding-left: 0px;
  padding-right: 0px;
  padding-top: 6px;
  padding-bottom: 5px;
}
.pagination {
  display: inline-block;
  padding-left: 0;
  border-radius: 4px;
}
div.pagination li.pagination a,
div.pagination li.pagination span {
  padding: 6px 12px;
  line-height: 1.42857143;
  color: #000;
  text-decoration: none;
  background-repeat: repeat-x;
}
div.pagination li.pagination span.inactive {
  cursor: default;
  color: #ccc;
}
li.noborder.litext, li.noborder.litext a,
div.pagination li a.inactive:hover,
div.pagination li span.inactive:hover {
	-moz-box-shadow: none !important;
  	-webkit-box-shadow: none !important;
  	box-shadow: none !important;
}
/*div.pagination li.litext {
	padding-top: 8px;
}*/
div.pagination li.litext a {
  border: none;
  padding-right: 10px;
  padding-left: 4px;
  font-weight: bold;
}
div.pagination li.litext a:hover {
	background-color: transparent;
	background-image: none;
}
div.pagination li.litext a:hover {
	background-color: transparent;
	background-image: none;
}
div.pagination li.noborder a:hover {
  border: none;
  background-color: transparent;
}
div.pagination li a,
div.pagination li span {
  /* background-color: #fff; */
  /* border: 1px solid #ddd; */
}
div.pagination li:first-child a,
div.pagination li:first-child span {
  margin-left: 0;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
}
div.pagination li:last-child a,
div.pagination li:last-child span {
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
}
div.pagination li a:hover,
div.pagination li span:hover,
div.pagination li a:focus,
div.pagination li span:focus {
  -moz-box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
  -webkit-box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
  box-shadow: 0px 0px 6px 1px rgba(50, 50, 50, 0.4), 0px 0px 0px rgba(60,60,60,0.1);
	padding-top: 8px;
}
div.pagination li .active a,
div.pagination li .active span,
div.pagination li .active a:hover,
div.pagination li .active span:hover,
div.pagination li .active a:focus,
div.pagination li .active span:focus {
  z-index: 2;
  color: #fff;
  cursor: default;
  background-color: <?php $colorbackhmenu1 ?>;
  border-color: #337ab7;
}
div.pagination .disabled span,
div.pagination .disabled span:hover,
div.pagination .disabled span:focus,
div.pagination .disabled a,
div.pagination .disabled a:hover,
div.pagination .disabled a:focus {
  color: #777;
  cursor: not-allowed;
  background-color: #fff;
  border-color: #ddd;
}
div.pagination li.pagination .active {
  text-decoration: underline;
  box-shadow: none;
}
.paginationafterarrows .nohover {
  box-shadow: none !important;
}

div.pagination li.paginationafterarrows {
	margin-left: 10px;
}
.paginationatbottom {
	margin-top: 9px;
}




/* Set the color for hover lines */
.oddeven:hover, .evenodd:hover, .impair:hover, .pair:hover
{
<?php if ($colorbacklinepairhover) { ?>
	background: rgb(<?php echo $colorbacklinepairhover; ?>) !important;		/* Must be background to be stronger than background of odd or even */
<?php } ?>
}

.oddeven, .evenodd, .impair, .nohover .impair:hover, tr.impair td.nohover
{
	font-family: <?php print $fontlist ?>;
	margin-bottom: 1px;
	color: #202020;
}
.impair, .nohover .impair:hover, tr.impair td.nohover
{
	background: #<?php echo colorArrayToHex(colorStringToArray($colorbacklineimpair1)); ?>;
}
#GanttChartDIV {
	background-color: #<?php echo colorArrayToHex(colorStringToArray($colorbacklineimpair1)); ?>;
}

.oddeven, .evenodd, .pair, .nohover .pair:hover, tr.pair td.nohover {
	font-family: <?php print $fontlist ?>;
	margin-bottom: 1px;
	color: #202020;
}
.pair, .nohover .pair:hover, tr.pair td.nohover {
	background-color: #<?php echo colorArrayToHex(colorStringToArray($colorbacklinepair1)); ?>;
}

table.dataTable tr.oddeven {
	background-color: #<?php echo colorArrayToHex(colorStringToArray($colorbacklinepair1)); ?> !important;
}

/* For no hover style */
td.oddeven, table.nohover tr.impair, table.nohover tr.pair, table.nohover tr.impair td, table.nohover tr.pair td, tr.nohover td, form.nohover, form.nohover:hover {
	background-color: #<?php echo colorArrayToHex(colorStringToArray($colorbacklineimpair1)); ?> !important;
	background: #<?php echo colorArrayToHex(colorStringToArray($colorbacklineimpair1)); ?> !important;
}
td.evenodd, tr.nohoverpair td {
	background-color: #<?php echo colorArrayToHex(colorStringToArray($colorbacklinepair1)); ?> !important;
	background: #<?php echo colorArrayToHex(colorStringToArray($colorbacklinepair1)); ?> !important;
}


table.dataTable td {
    padding: 5px 2px 5px 3px !important;
}
tr.pair td, tr.impair td, form.impair div.tagtd, form.pair div.tagtd, div.impair div.tagtd, div.pair div.tagtd, div.liste_titre div.tagtd {
    padding: 7px 2px 7px 3px;
    border-bottom: 1px solid #ddd;
}
form.pair, form.impair {
	font-weight: normal;
}
form.tagtr:last-of-type div.tagtd, tr.pair:last-of-type td, tr.impair:last-of-type td {
    border-bottom: 0px !important;
}
tr.pair td .nobordernopadding tr td, tr.impair td .nobordernopadding tr td {
    border-bottom: 0px !important;
}
tr.nobottom td, tr.nobottom , td.nobottom {
    border-bottom: 0px !important;
}
div.liste_titre .tagtd {
	vertical-align: middle;
}
/*div.liste_titre {
	box-shadow: 2px 2px 4px #CCC;
}*/
div.liste_titre {
	min-height: 26px !important;	/* We cant use height because it's a div and it should be higher if content is more. but min-height does not work either for div */

	padding-top: 2px;
	padding-bottom: 2px;

/*	border-top-width: 1px;
	border-top-color: #BBB;
	border-top-style: solid;*/
}
div.liste_titre_bydiv {
	border-top-width: <?php echo $borderwith ?>px;
    border-top-color: rgb(<?php echo $colortopbordertitle1 ?>);
    border-top-style: solid;

	border-collapse: collapse;
	display: table;
	padding: 2px 0px 2px 0;
	box-shadow: none;
	width: calc(100% - 1px);	/* 1px more, i don't know why */
}
tr.liste_titre, tr.liste_titre_sel, form.liste_titre, form.liste_titre_sel, table.dataTable.tr
{
	height: 26px !important;
}
div.liste_titre_bydiv, .liste_titre div.tagtr, tr.liste_titre, tr.liste_titre_sel, form.liste_titre, form.liste_titre_sel, table.dataTable thead tr
{
	background: rgb(<?php echo $colorbacktitle1; ?>);
	font-weight: <?php echo $useboldtitle?'bold':'normal'; ?>;
    border-bottom: 1px solid #ddd;

    color: rgb(<?php echo $colortexttitle; ?>);
    font-family: <?php print $fontlist ?>;
    text-align: <?php echo $left; ?>;
}
tr.liste_titre th, tr.liste_titre td, th.liste_titre
{
	border-bottom: 1px solid #888;
}
tr.liste_titre:first-child th, tr:first-child th.liste_titre {
    border-bottom: 1px solid #ddd ! important;
}
tr.liste_titre th, th.liste_titre, tr.liste_titre td, td.liste_titre, form.liste_titre div
{
    font-family: <?php print $fontlist ?>;
    font-weight: <?php echo $useboldtitle?'bold':'normal'; ?>;
    vertical-align: middle;
    height: 24px;
}
tr.liste_titre th a, th.liste_titre a, tr.liste_titre td a, td.liste_titre a, form.liste_titre div a, div.liste_titre a {
	text-shadow: none !important;
}
tr.liste_titre_topborder td {
	border-top-width: <?php echo $borderwith; ?>px;
    border-top-color: rgb(<?php echo $colortopbordertitle1 ?>);
    border-top-style: solid;
}
.liste_titre td a {
	text-shadow: none !important;
	color: rgb(<?php echo $colortexttitle; ?>);
}
.liste_titre td a.notasortlink {
	color: rgb(<?php echo $colortextlink; ?>);
}
.liste_titre td a.notasortlink:hover {
	background: transparent;
}
tr.liste_titre:last-child th.liste_titre, tr.liste_titre:last-child th.liste_titre_sel, tr.liste_titre td.liste_titre, tr.liste_titre td.liste_titre_sel, form.liste_titre div.tagtd {				/* For last line of table headers only */
    border-bottom: 1px solid #090407;
}


tr.liste_titre_sel th, th.liste_titre_sel, tr.liste_titre_sel td, td.liste_titre_sel, form.liste_titre_sel div
{
    font-family: <?php print $fontlist ?>;
    font-weight: normal;
    border-bottom: 1px solid #FDFFFF;
    text-decoration: underline;
}
input.liste_titre {
    background: transparent;
    border: 0px;
}
.listactionlargetitle .liste_titre {
	line-height: 24px;
}

.noborder tr.liste_total, .noborder tr.liste_total td, tr.liste_total, form.liste_total {
	/* height: 32px; */
}
.noborder tr.liste_total td, tr.liste_total td, form.liste_total div {
    color: #552266;
    font-weight: normal;
    white-space: nowrap;
}
form.liste_total div {
    border-top: 1px solid #DDDDDD;
}
tr.liste_sub_total, tr.liste_sub_total td {
	border-bottom: 1px solid #aaa;
}

.tableforservicepart1 .impair, .tableforservicepart1 .pair, .tableforservicepart2 .impair, .tableforservicepart2 .pair {
	background: #FFF;
}
.tableforservicepart1 tbody tr td, .tableforservicepart2 tbody tr td {
	border-bottom: none;
}

.paymenttable, .margintable {
	border-top-width: <?php echo $borderwith ?>px !important;
	border-top-color: rgb(<?php echo $colortopbordertitle1 ?>) !important;
	border-top-style: solid !important;
	margin: 0px 0px 0px 0px !important;
}
.paymenttable tr td:first-child, .margintable tr td:first-child
{
	padding-left: 2px;
}

/* Disable shadows */
.noshadow {
	-moz-box-shadow: 0px 0px 0px #DDD !important;
	-webkit-box-shadow: 0px 0px 0px #DDD !important;
	box-shadow: 0px 0px 0px #DDD !important;
}

div.tabBar .noborder {
	-moz-box-shadow: 0px 0px 0px #DDD !important;
	-webkit-box-shadow: 0px 0px 0px #DDD !important;
	box-shadow: 0px 0px 0px #DDD !important;
}

#tablelines tr.liste_titre td, .paymenttable tr.liste_titre td, .margintable tr.liste_titre td, .tableforservicepart1 tr.liste_titre td {
	border-bottom: 1px solid #AAA !important;
}


/* Prepare to remove class pair - impair */

.noborder > tbody > tr:nth-child(even):not(.liste_titre), .liste > tbody > tr:nth-child(even):not(.liste_titre) {
	background: linear-gradient(bottom, rgb(<?php echo $colorbacklineimpair1; ?>) 85%, rgb(<?php echo $colorbacklineimpair2; ?>) 100%);
	background: -o-linear-gradient(bottom, rgb(<?php echo $colorbacklineimpair1; ?>) 85%, rgb(<?php echo $colorbacklineimpair2; ?>) 100%);
	background: -moz-linear-gradient(bottom, rgb(<?php echo $colorbacklineimpair1; ?>) 85%, rgb(<?php echo $colorbacklineimpair2; ?>) 100%);
	background: -webkit-linear-gradient(bottom, rgb(<?php echo $colorbacklineimpair1; ?>) 85%, rgb(<?php echo $colorbacklineimpair2; ?>) 100%);
	background: -ms-linear-gradient(bottom, rgb(<?php echo $colorbacklineimpair1; ?>) 85%, rgb(<?php echo $colorbacklineimpair2; ?>) 100%);
}
.noborder > tbody > tr:nth-child(even):not(:last-child) td:not(.liste_titre), .liste > tbody > tr:nth-child(even):not(:last-child) td:not(.liste_titre) {
	border-bottom: 1px solid #ddd;
}

.noborder > tbody > tr:nth-child(odd):not(.liste_titre), .liste > tbody > tr:nth-child(odd):not(.liste_titre) {
	background: linear-gradient(bottom, rgb(<?php echo $colorbacklinepair1; ?>) 85%, rgb(<?php echo $colorbacklinepair2; ?>) 100%);
	background: -o-linear-gradient(bottom, rgb(<?php echo $colorbacklinepair1; ?>) 85%, rgb(<?php echo $colorbacklinepair2; ?>) 100%);
	background: -moz-linear-gradient(bottom, rgb(<?php echo $colorbacklinepair1; ?>) 85%, rgb(<?php echo $colorbacklinepair2; ?>) 100%);
	background: -webkit-linear-gradient(bottom, rgb(<?php echo $colorbacklinepair1; ?>) 85%, rgb(<?php echo $colorbacklinepair2; ?>) 100%);
	background: -ms-linear-gradient(bottom, rgb(<?php echo $colorbacklinepair1; ?>) 85%, rgb(<?php echo $colorbacklinepair2; ?>) 100%);
}
.noborder > tbody > tr:nth-child(odd):not(:last-child) td:not(.liste_titre), .liste > tbody > tr:nth-child(odd):not(:last-child) td:not(.liste_titre) {
	border-bottom: 1px solid #ddd;
}

ul.noborder li:nth-child(even):not(.liste_titre) {
	background-color: rgb(<?php echo $colorbacklinepair2; ?>) !important;
	background-color: rgb(<?php echo $colorbacklinepair2; ?>) !important;
	background-color: rgb(<?php echo $colorbacklinepair2; ?>) !important;
	background-color: rgb(<?php echo $colorbacklinepair2; ?>) !important;
	background-color: rgb(<?php echo $colorbacklinepair2; ?>) !important;
}


/*
 *  Boxes
 */

.ficheaddleft div.boxstats {
    border: none;
}
.boxstatsborder {
    border: 1px solid #CCC !important;
}
.boxstats, .boxstats130 {
    display: inline-block;
    margin: 3px;
    border: 1px solid #CCC;
    text-align: center;
    border-radius: 2px;
}
.boxstats, .boxstats130, .boxstatscontent {
	white-space: nowrap;
	overflow: hidden;
    text-overflow: ellipsis;
}
.boxstats {
    padding: 3px;
    width: 103px;
}
.boxstats130 {
    width: 158px;
    height: 48px;
    padding: 3px
}
.boxstatscontent {
	padding: 3px;
}

@media only screen and (max-width: 767px)
{
	.thumbstat {
		flex: 1 1 110px;
	}
	.thumbstat150 {
		flex: 1 1 110px;
	}
	.boxstats, .boxstats130 {
        width: 90px;
    }
    .dashboardlineindicator {
        float: left;
    	padding-left: 5px;
    }
    .boxstats {
        width: 100px;
    }
}

.boxstats:hover {
	box-shadow: 0px 0px 8px 0px rgba(0,0,0,0.20);
}
span.boxstatstext {
	opacity: 0.8;
    line-height: 18px;
}
span.boxstatstext img, a.dashboardlineindicatorlate img {
	border: 0;
}
a img {
	border: 0;
}
span.boxstatsindicator {
	font-size: 130%;
	font-weight: normal;
	line-height: 29px;
}
span.dashboardlineindicator, span.dashboardlineindicatorlate {
	font-size: 130%;
	font-weight: normal;
}
.dashboardlineindicatorlate img {
	width: 16px;
}
span.dashboardlineok {
	color: #008800;
}
span.dashboardlineko {
	color: #FFF;
	/*color: #8c4446 ! important;
	padding-left: 1px;*/

	font-size: 80%;
}
.dashboardlinelatecoin {
	float: right;
	position: relative;
    text-align: right;
    top: -24px;
    padding: 1px 2px 1px 2px;
    border-radius: .25em;

    background-color: #9f4705;
    padding: 0px 5px 0px 5px;
    top: -26px;
}
.imglatecoin {
    padding: 1px 3px 1px 1px;
    margin-left: 4px;
    margin-right: 2px;
    background-color: #8c4446;
    color: #FFFFFF ! important;
    border-radius: .25em;
	display: inline-block;
	vertical-align: middle;
}
.boxtable {
    margin-bottom: 8px !important;
    border-bottom-width: 1px;
}
.boxtablenobottom {
    border-bottom-width: 0 !important;
}
.tdboxstats {
	text-align: center;
}
a.valignmiddle.dashboardlineindicator {
    line-height: 30px;
}

.box {
    padding-right: 0px;
    padding-left: 0px;
    padding-bottom: 12px;
}

tr.box_titre {
    height: 26px;

    /* TO MATCH BOOTSTRAP */
	/*background: #ddd;
	color: #000 !important;*/

	/* TO MATCH ELDY */
	background: rgb(<?php echo $colorbacktitle1; ?>)
	color: rgb(<?php echo $colortexttitle; ?>);
    font-family: <?php print $fontlist ?>, sans-serif;
    font-weight: <?php echo $useboldtitle?'bold':'normal'; ?>;
    border-bottom: 1px solid #FDFFFF;
    white-space: nowrap;
}

tr.box_titre td.boxclose {
	width: 30px;
}
img.boxhandle, img.boxclose {
	padding-left: 5px;
}

.noborderbottom {
	border-bottom: none !important;
}
.formboxfilter {
	vertical-align: middle;
	margin-bottom: 6px;
}
.formboxfilter input[type=image]
{
	top: 5px;
	position: relative;
}
.boxfilter {
	margin-bottom: 2px;
	margin-right: 1px;
}
.prod_entry_mode_free, .prod_entry_mode_predef {
    height: 26px !important;
    vertical-align: middle;
}

.modulebuilderbox {
	border: 1px solid #888;
	padding: 16px;
}


/*
 *   Ok, Warning, Error
 */
.ok      { color: #114466; }
.warning { color: #887711; }
.error   { color: #550000 !important; font-weight: bold; }

div.ok {
  color: #114466;
}

/* Warning message */
div.warning {
  color: #302020;
  padding: 0.3em 0.3em 0.3em 0.3em;
  margin: 0.5em 0em 0.5em 0em;
  border: 1px solid #e0d0b0;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  background: #EFDF9A;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}

/* Error message */
div.error {
  background: #EFCFCF;
}

/* Info admin */
div.info {
  color: #fff;
  padding: 0.4em 0.4em 0.4em 0.4em;
  margin: 0.5em 0em 0.5em 0em;
  /* border: 1px solid #e0e0e0; */
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  background: #798080;
}

div.warning a, div.info a, div.error a {
	color: rgb(<?php echo $colortext; ?>);
}

/*
 *   Liens Payes/Non payes
 */

a.normal:link { font-weight: normal }
a.normal:visited { font-weight: normal }
a.normal:active { font-weight: normal }
a.normal:hover { font-weight: normal }

a.impayee:link { font-weight: bold; color: #550000; }
a.impayee:visited { font-weight: bold; color: #550000; }
a.impayee:active { font-weight: bold; color: #550000; }
a.impayee:hover { font-weight: bold; color: #550000; }



/*
 *  Other
 */

.product_line_stock_ok { color: #002200; }
.product_line_stock_too_low { color: #664400; }

.fieldrequired { font-weight: bold; color: #000055; }

.widthpictotitle { width: 40px; text-align: <?php echo $left; ?>; }

.dolgraphtitle { margin-top: 6px; margin-bottom: 4px; }
.dolgraphtitlecssboxes { margin: 0px; }
.legendColorBox, .legendLabel { border: none !important; }
div.dolgraph div.legend, div.dolgraph div.legend div { background-color: rgba(255,255,255,0) !important; }
div.dolgraph div.legend table tbody tr { height: auto; }
td.legendColorBox { padding: 2px 2px 2px 0 !important; }
td.legendLabel { padding: 2px 2px 2px 0 !important; }

.photo {
	border: 0px;
}
.photowithmargin {
	margin-bottom: 2px;
	margin-top: 10px;
}
.photowithborder {
	border: 1px solid #f0f0f0;
}
.photointooltip {
	margin-top: 6px;
	margin-bottom: 6px;
	text-align: center;
	/*-moz-box-shadow: 3px 3px 4px #DDD;
    -webkit-box-shadow: 3px 3px 4px #DDD;
    box-shadow: 3px 3px 4px #DDD;*/
}
.photodelete {
	margin-top: 6px !important;
}

.logo_setup
{
	content:url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/logo_setup.svg',1) ?>);	/* content is used to best fit the container */
	display: inline-block;
}
.nographyet
{
	content:url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/nographyet.svg',1) ?>);
	display: inline-block;
    opacity: 0.1;
    background-repeat: no-repeat;
}
.nographyettext
{
    opacity: 0.5;
}

div.titre {
	font-family: <?php print $fontlist ?>;
	font-size: 14px;
	font-weight: bold;
	color: rgb(<?php print $colortexttitlenotab; ?>);
	text-decoration: none;
	padding-top: 5px;
    padding-bottom: 5px;
	/* text-shadow: 1px 1px 2px #FFFFFF; */
}

#dolpaymenttable { width: 600px; font-size: 13px; }
#tablepublicpayment { border: 1px solid #CCCCCC !important; width: 100%; }
#tablepublicpayment .CTableRow1  { background-color: #F0F0F0 !important; }
#tablepublicpayment tr.liste_total { border-bottom: 1px solid #CCCCCC !important; }
#tablepublicpayment tr.liste_total td { border-top: none; }

.divmainbodylarge { margin-left: 40px; margin-right: 40px; }
#divsubscribe { max-width: 900px; }
#tablesubscribe { width: 100%; }


/*
 * Effect Postit
 */
.effectpostit
{
  position: relative;
}
.effectpostit:before, .effectpostit:after
{
  z-index: -1;
  position: absolute;
  content: "";
  bottom: 15px;
  left: 10px;
  width: 50%;
  top: 80%;
  max-width:300px;
  background: #777;
  -webkit-box-shadow: 0 15px 10px #777;
  -moz-box-shadow: 0 15px 10px #777;
  box-shadow: 0 15px 10px #777;
  -webkit-transform: rotate(-3deg);
  -moz-transform: rotate(-3deg);
  -o-transform: rotate(-3deg);
  -ms-transform: rotate(-3deg);
  transform: rotate(-3deg);
}
.effectpostit:after
{
  -webkit-transform: rotate(3deg);
  -moz-transform: rotate(3deg);
  -o-transform: rotate(3deg);
  -ms-transform: rotate(3deg);
  transform: rotate(3deg);
  right: 10px;
  left: auto;
}



/* ============================================================================== */
/* Formulaire confirmation (When Ajax JQuery is used)                             */
/* ============================================================================== */

.ui-dialog-titlebar {
}
.ui-dialog-content {
    font-size: <?php print $fontsize; ?>px !important;
}

/* ============================================================================== */
/* Formulaire confirmation (When HTML is used)                                    */
/* ============================================================================== */

table.valid {
    border-top: solid 1px #E6E6E6;
    border-<?php print $left; ?>: solid 1px #E6E6E6;
    border-<?php print $right; ?>: solid 1px #444444;
    border-bottom: solid 1px #555555;
	padding-top: 0px;
	padding-left: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	margin: 0px 0px;
    background: #D5BAA8;
}

.validtitre {
    background: #D5BAA8;
	font-weight: bold;
}


/* ============================================================================== */
/* Tooltips                                                                       */
/* ============================================================================== */

#tooltip {
	position: absolute;
	width: <?php print dol_size(450,'width'); ?>px;
	border-top: solid 1px #BBBBBB;
	border-<?php print $left; ?>: solid 1px #BBBBBB;
	border-<?php print $right; ?>: solid 1px #444444;
	border-bottom: solid 1px #444444;
	padding: 2px;
	z-index: 3000;
	background-color: #FFF;
	opacity: 1;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
}
#tiptip_content {
    -moz-border-radius:0px;
    -webkit-border-radius: 0px;
    border-radius: 0px;
    background-color: rgb(255,255,255);
	line-height: 1.4em;
	min-width: 200px;
}

/* ============================================================================== */
/* Calendar                                                                       */
/* ============================================================================== */

img.datecallink { padding-left: 2px !important; padding-right: 2px !important; }

.ui-datepicker-trigger {
	vertical-align: middle;
	cursor: pointer;
}

.bodyline {
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	border-radius: 8px;
	border: 1px #E4ECEC outset;
	padding: 0px;
	margin-bottom: 5px;
}
table.dp {
    width: 180px;
    background-color: #FFFFFF;
    border-top: solid 2px #DDDDDD;
    border-<?php print $left; ?>: solid 2px #DDDDDD;
    border-<?php print $right; ?>: solid 1px #222222;
    border-bottom: solid 1px #222222;
    padding: 0px;
	border-spacing: 0px;
	border-collapse: collapse;
}
.dp td, .tpHour td, .tpMinute td{padding:2px; font-size:10px;}
/* Barre titre */
.dpHead,.tpHead,.tpHour td:Hover .tpHead{
	font-weight:bold;
	background-color:#b3c5cc;
	color:white;
	font-size:11px;
	cursor:auto;
}
/* Barre navigation */
.dpButtons,.tpButtons {
	text-align:center;
	background-color:#617389;
	color:#FFFFFF;
	font-weight:bold;
	cursor:pointer;
}
.dpButtons:Active,.tpButtons:Active{border: 1px outset black;}
.dpDayNames td,.dpExplanation {background-color:#D9DBE1; font-weight:bold; text-align:center; font-size:11px;}
.dpExplanation{ font-weight:normal; font-size:11px;}
.dpWeek td{text-align:center}

.dpToday,.dpReg,.dpSelected{
	cursor:pointer;
}
.dpToday{font-weight:bold; color:black; background-color:#DDDDDD;}
.dpReg:Hover,.dpToday:Hover{background-color:black;color:white}

/* Jour courant */
.dpSelected{background-color:#0B63A2;color:white;font-weight:bold; }

.tpHour{border-top:1px solid #DDDDDD; border-right:1px solid #DDDDDD;}
.tpHour td {border-left:1px solid #DDDDDD; border-bottom:1px solid #DDDDDD; cursor:pointer;}
.tpHour td:Hover {background-color:black;color:white;}

.tpMinute {margin-top:5px;}
.tpMinute td:Hover {background-color:black; color:white; }
.tpMinute td {background-color:#D9DBE1; text-align:center; cursor:pointer;}

/* Bouton X fermer */
.dpInvisibleButtons
{
    border-style:none;
    background-color:transparent;
    padding:0px;
    font-size:9px;
    border-width:0px;
    color:#0B63A2;
    vertical-align:middle;
    cursor: pointer;
}
.datenowlink
{
	color: rgb(<?php print $colortextlink; ?>);
}


/* ============================================================================== */
/*  Afficher/cacher                                                               */
/* ============================================================================== */

div.visible {
    display: block;
}

div.hidden, td.hidden, img.hidden {
    display: none;
}

tr.visible {
    display: block;
}


/* ============================================================================== */
/*  Module website                                                                */
/* ============================================================================== */

.websitebar {
	border-bottom: 1px solid #888;
	background: #eee;
	display: inline-block;
}
.websitebar .button, .websitebar .buttonDelete
{
	padding: 2px 5px 3px 5px !important;
	margin: 2px 4px 2px 4px  !important;
    line-height: normal;
}
.websiteselection {
	display: inline-block;
	padding-left: 10px;
	vertical-align: middle;
	/* line-height: 29px; */
}
.websitetools {
	float: right;
	/* height: 28px; */
}
.websiteselection, .websitetools {
	padding-top: 3px;
	padding-bottom: 3px;
}
.websiteinputurl {
    display: inline-block;
    vertical-align: top;
}
.websiteiframenoborder {
	border: 0px;
}
a.websitebuttonsitepreview {
	vertical-align: middle;
}
a.websitebuttonsitepreview img {
	width: 26px;
	display: inline-block;
}
.websitehelp {
    vertical-align: middle;
    float: right;
    padding-top: 8px;
}


/* ============================================================================== */
/*  Module agenda                                                                 */
/* ============================================================================== */

.agendacell { height: 60px; }
table.cal_month    { border-spacing: 0px;  }
table.cal_month td:first-child  { border-left: 0px; }
table.cal_month td:last-child   { border-right: 0px; }
.cal_current_month { border-top: 0; border-left: solid 1px #E0E0E0; border-right: 0; border-bottom: solid 1px #E0E0E0; }
.cal_current_month_peruserleft { border-top: 0; border-left: solid 2px #6C7C7B; border-right: 0; border-bottom: solid 1px #E0E0E0; }
.cal_current_month_oneday { border-right: solid 1px #E0E0E0; }
.cal_other_month   { border-top: 0; border-left: solid 1px #C0C0C0; border-right: 0; border-bottom: solid 1px #C0C0C0; }
.cal_other_month_peruserleft { border-top: 0; border-left: solid 2px #6C7C7B !important; border-right: 0; }
.cal_current_month_right { border-right: solid 1px #E0E0E0; }
.cal_other_month_right   { border-right: solid 1px #C0C0C0; }
.cal_other_month   { /* opacity: 0.6; */ background: #EAEAEA; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 1px; padding-top: 0px; padding-bottom: 0px; }
.cal_past_month    { /* opacity: 0.6; */ background: #EEEEEE; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 1px; padding-top: 0px; padding-bottom: 0px; }
.cal_current_month { background: #FFFFFF; border-left: solid 1px #E0E0E0; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 1px; padding-top: 0px; padding-bottom: 0px; }
.cal_current_month_peruserleft { background: #FFFFFF; border-left: solid 2px #6C7C7B; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 1px; padding-top: 0px; padding-bottom: 0px; }
.cal_today         { background: #FDFDF0; border-left: solid 1px #E0E0E0; border-bottom: solid 1px #E0E0E0; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 1px; padding-top: 0px; padding-bottom: 0px; }
.cal_today_peruser { background: #FDFDF0; border-right: solid 1px #E0E0E0; border-bottom: solid 1px #E0E0E0; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 1px; padding-top: 0px; padding-bottom: 0px; }
.cal_today_peruser_peruserleft { background: #FDFDF0; border-left: solid 2px #6C7C7B; border-right: solid 1px #E0E0E0; border-bottom: solid 1px #E0E0E0; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 1px; padding-top: 0px; padding-bottom: 0px; }
.cal_past          { }
.cal_peruser       { padding-top: 0 !important; padding-bottom: 0 !important; padding-<?php print $left; ?>: 1px !important; padding-<?php print $right; ?>: 1px !important; }
.cal_impair        { background: #F8F8F8; }
.cal_today_peruser_impair { background: #F8F8F0; }
.peruser_busy      { background: #CC8888; }
.peruser_notbusy   { background: #EEDDDD; opacity: 0.5; }
table.cal_event    { border: none; border-collapse: collapse; margin-bottom: 1px; -webkit-border-radius: 6px; border-radius: 6px; min-height: 20px; }
table.cal_event td { border: none; padding-<?php print $left; ?>: 2px; padding-<?php print $right; ?>: 2px; padding-top: 0px; padding-bottom: 0px; }
table.cal_event td.cal_event { padding: 4px 4px !important; }
table.cal_event td.cal_event_right { padding: 4px 4px !important; }
.cal_event a:link    { color: #111111; font-size: 11px; font-weight: normal !important; }
.cal_event a:visited { color: #111111; font-size: 11px; font-weight: normal !important; }
.cal_event a:active  { color: #111111; font-size: 11px; font-weight: normal !important; }
.cal_event a:hover   { color: #111111; font-size: 11px; font-weight: normal !important; color:rgba(255,255,255,.75); }
.cal_event_busy      { }
.cal_peruserviewname { max-width: 100px; height: 22px; }


/* ============================================================================== */
/*  Ajax - Liste deroulante de l'autocompletion                                   */
/* ============================================================================== */

.ui-widget-content { border: solid 1px rgba(0,0,0,.3); background: #fff !important; }

.ui-autocomplete-loading { background: white url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/working.gif',1) ?>) right center no-repeat; }
.ui-autocomplete {
	       position:absolute;
	       width:auto;
	       font-size: 1.0em;
	       background-color:white;
	       border:1px solid #888;
	       margin:0px;
/*	       padding:0px; This make combo crazy */
	     }
.ui-autocomplete ul {
	       list-style-type:none;
	       margin:0px;
	       padding:0px;
	     }
.ui-autocomplete ul li.selected { background-color: #D3E5EC;}
.ui-autocomplete ul li {
	       list-style-type:none;
	       display:block;
	       margin:0;
	       padding:2px;
	       height:18px;
	       cursor:pointer;
	     }

/* ============================================================================== */
/* Gantt
/* ============================================================================== */

div.gTaskInfo {
    background: #f0f0f0 !important;
}
.gtaskblue {
	background: rgb(108,152,185) !important;
}
td.gtaskname {
    overflow: hidden;
    text-overflow: ellipsis;
}


/* ============================================================================== */
/*  jQuery - jeditable                                                            */
/* ============================================================================== */

.editkey_textarea, .editkey_ckeditor, .editkey_string, .editkey_email, .editkey_numeric, .editkey_select, .editkey_autocomplete {
	background: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/edit.png',1) ?>) right top no-repeat;
	cursor: pointer;
}

.editkey_datepicker {
	background: url(<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/calendar.png',1) ?>) right center no-repeat;
	cursor: pointer;
}

.editval_textarea.active:hover, .editval_ckeditor.active:hover, .editval_string.active:hover, .editval_email.active:hover, .editval_numeric.active:hover, .editval_select.active:hover, .editval_autocomplete.active:hover, .editval_datepicker.active:hover {
	background: white;
	cursor: pointer;
}

.viewval_textarea.active:hover, .viewval_ckeditor.active:hover, .viewval_string.active:hover, .viewval_email.active:hover, .viewval_numeric.active:hover, .viewval_select.active:hover, .viewval_autocomplete.active:hover, .viewval_datepicker.active:hover {
	background: white;
	cursor: pointer;
}

.viewval_hover {
	background: white;
}


/* ============================================================================== */
/* Admin Menu                                                                     */
/* ============================================================================== */

/* CSS for treeview */
.treeview ul { background-color: transparent !important; margin-top: 0; }
.treeview li { background-color: transparent !important; padding: 0 0 0 16px !important; min-height: 20px; }
.treeview .hover { color: rgb(<?php print $colortextlink; ?>) !important; text-decoration: underline !important; }



/* ============================================================================== */
/*  Show Excel tabs                                                               */
/* ============================================================================== */

.table_data
{
	border-style:ridge;
	border:1px solid;
}
.tab_base
{
	background:#C5D0DD;
	font-weight:bold;
	border-style:ridge;
	border: 1px solid;
	cursor:pointer;
}
.table_sub_heading
{
	background:#CCCCCC;
	font-weight:bold;
	border-style:ridge;
	border: 1px solid;
}
.table_body
{
	background:#F0F0F0;
	font-weight:normal;
	font-family:sans-serif;
	border-style:ridge;
	border: 1px solid;
	border-spacing: 0px;
	border-collapse: collapse;
}
.tab_loaded
{
	background:#222222;
	color:white;
	font-weight:bold;
	border-style:groove;
	border: 1px solid;
	cursor:pointer;
}


/* ============================================================================== */
/*  CSS for color picker                                                          */
/* ============================================================================== */

A.color, A.color:active, A.color:visited {
 position : relative;
 display : block;
 text-decoration : none;
 width : 10px;
 height : 10px;
 line-height : 10px;
 margin : 0px;
 padding : 0px;
 border : 1px inset white;
}
A.color:hover {
 border : 1px outset white;
}
A.none, A.none:active, A.none:visited, A.none:hover {
 position : relative;
 display : block;
 text-decoration : none;
 width : 10px;
 height : 10px;
 line-height : 10px;
 margin : 0px;
 padding : 0px;
 cursor : default;
 border : 1px solid #b3c5cc;
}
.tblColor {
 display : none;
}
.tdColor {
 padding : 1px;
}
.tblContainer {
 background-color : #b3c5cc;
}
.tblGlobal {
 position : absolute;
 top : 0px;
 left : 0px;
 display : none;
 background-color : #b3c5cc;
 border : 2px outset;
}
.tdContainer {
 padding : 5px;
}
.tdDisplay {
 width : 50%;
 height : 20px;
 line-height : 20px;
 border : 1px outset white;
}
.tdDisplayTxt {
 width : 50%;
 height : 24px;
 line-height : 12px;
 font-family : <?php print $fontlist ?>;
 font-size : 8pt;
 color : black;
 text-align : center;
}
.btnColor {
 width : 100%;
 font-family : <?php print $fontlist ?>;
 font-size : 10pt;
 padding : 0px;
 margin : 0px;
}
.btnPalette {
 width : 100%;
 font-family : <?php print $fontlist ?>;
 font-size : 8pt;
 padding : 0px;
 margin : 0px;
}


/* Style to overwrites JQuery styles */
.ui-menu .ui-menu-item a {
    text-decoration:none;
    display:block;
    padding:.2em .4em;
    line-height:1.5;
    zoom:1;
    font-weight: normal;
    font-family:<?php echo $fontlist; ?>;
    font-size:1em;
}
.ui-widget {
    font-family:<?php echo $fontlist; ?>;
    font-size:<?php echo $fontsize; ?>px;
}
.ui-button { margin-left: -2px; <?php print (preg_match('/chrome/',$conf->browser->name)?'padding-top: 1px;':''); ?> }
.ui-button-icon-only .ui-button-text { height: 8px; }
.ui-button-icon-only .ui-button-text, .ui-button-icons-only .ui-button-text { padding: 2px 0px 6px 0px; }
.ui-button-text
{
    line-height: 1em !important;
}
.ui-autocomplete-input { margin: 0; padding: 4px; }


/* ============================================================================== */
/*  CKEditor                                                                      */
/* ============================================================================== */

.cke_dialog {
    border: 1px #bbb solid ! important;
}
.cke_editable
{
	margin: 5px !important;
}
/*.cke_editor table, .cke_editor tr, .cke_editor td
{
    border: 0px solid #FF0000 !important;
}
span.cke_skin_kama { padding: 0 !important; }*/
.cke_wrapper { padding: 4px !important; }
a.cke_dialog_ui_button
{
    font-family: <?php print $fontlist ?> !important;
	background-image: url(<?php echo $img_button ?>) !important;
	background-position: bottom !important;
    border: 1px solid #C0C0C0 !important;
    -moz-border-radius:0px 5px 0px 5px !important;
	-webkit-border-radius:0px 5px 0px 5px !important;
	border-radius:0px 5px 0px 5px !important;
    -moz-box-shadow: 3px 3px 4px #DDD !important;
    -webkit-box-shadow: 3px 3px 4px #DDD !important;
    box-shadow: 3px 3px 4px #DDD !important;
}
.cke_dialog_ui_hbox_last
{
	vertical-align: bottom ! important;
}
.cke_editable
{
	line-height: 1.4 !important;
	margin: 6px !important;
}
a.cke_dialog_ui_button_ok span {
    text-shadow: none !important;
    color: #333 !important;
}


/* ============================================================================== */
/*  File upload                                                                   */
/* ============================================================================== */

.template-upload {
    height: 72px !important;
}


/* ============================================================================== */
/*  Holiday                                                                       */
/* ============================================================================== */

#types .btn {
    cursor: pointer;
}

#types .btn-primary {
    font-weight: bold;
}

#types form {
    padding: 20px;
}

#types label {
    display:inline-block;
    width:100px;
    margin-right: 20px;
    padding: 4px;
    text-align: right;
    vertical-align: top;
}

#types input.text, #types textarea {
    width: 400px;
}

#types textarea {
    height: 100px;
}



/* ============================================================================== */
/*  JSGantt                                                                       */
/* ============================================================================== */

div.scroll2 {
	width: <?php print isset($_SESSION['dol_screenwidth'])?max($_SESSION['dol_screenwidth']-830,450):'450'; ?>px !important;
}


/* ============================================================================== */
/*  jFileTree                                                                     */
/* ============================================================================== */

.ecmfiletree {
	width: 99%;
	height: 99%;
	background: #FFF;
	padding-left: 2px;
	font-weight: normal;
}

.fileview {
	width: 99%;
	height: 99%;
	background: #FFF;
	padding-left: 2px;
	padding-top: 4px;
	font-weight: normal;
}

div.filedirelem {
    position: relative;
    display: block;
    text-decoration: none;
}

ul.filedirelem {
    padding: 2px;
    margin: 0 5px 5px 5px;
}
ul.filedirelem li {
    list-style: none;
    padding: 2px;
    margin: 0 10px 20px 10px;
    width: 160px;
    height: 120px;
    text-align: center;
    display: block;
    float: <?php print $left; ?>;
    border: solid 1px #DDDDDD;
}

ul.ecmjqft {
	line-height: 16px;
	padding: 0px;
	margin: 0px;
	font-weight: normal;
}

ul.ecmjqft li {
	list-style: none;
	padding: 0px;
	padding-left: 20px;
	margin: 0px;
	white-space: nowrap;
	display: block;
}

ul.ecmjqft a {
	line-height: 24px;
	vertical-align: middle;
	color: #333;
	padding: 0px 0px;
	font-weight:normal;
	display: inline-block !important;
}
ul.ecmjqft a:active {
	font-weight: bold !important;
}
ul.ecmjqft a:hover {
    text-decoration: underline;
}
div.ecmjqft {
	vertical-align: middle;
	display: inline-block !important;
	text-align: right;
	float: right;
	right:4px;
	clear: both;
}
div#ecm-layout-west {
    width: 380px;
    vertical-align: top;
}
div#ecm-layout-center {
    width: calc(100% - 390px);
    vertical-align: top;
    float: right;
}

.ecmjqft LI.directory { font-weight:normal; background: url(<?php echo dol_buildpath($path.'/theme/common/treemenu/folder2.png',1); ?>) left top no-repeat; }
.ecmjqft LI.expanded { font-weight:normal; background: url(<?php echo dol_buildpath($path.'/theme/common/treemenu/folder2-expanded.png',1); ?>) left top no-repeat; }
.ecmjqft LI.wait { font-weight:normal; background: url(<?php echo dol_buildpath('/theme/'.$theme.'/img/working.gif',1); ?>) left top no-repeat; }


/* ============================================================================== */
/*  jNotify                                                                       */
/* ============================================================================== */

.jnotify-container {
	position: fixed !important;
<?php if (! empty($conf->global->MAIN_JQUERY_JNOTIFY_BOTTOM)) { ?>
	top: auto !important;
	bottom: 4px !important;
<?php } ?>
	text-align: center;
	min-width: <?php echo $dol_optimize_smallscreen?'200':'480'; ?>px;
	width: auto;
	max-width: 1024px;
	padding-left: 10px !important;
	padding-right: 10px !important;
}

/* use or not ? */
div.jnotify-background {
	opacity : 0.95 !important;
    -moz-box-shadow: 2px 2px 4px #888 !important;
    -webkit-box-shadow: 2px 2px 4px #888 !important;
    box-shadow: 2px 2px 4px #888 !important;
}

/* ============================================================================== */
/*  blockUI                                                                      */
/* ============================================================================== */

/*div.growlUI { background: url(check48.png) no-repeat 10px 10px }*/
div.dolEventValid h1, div.dolEventValid h2 {
	color: #567b1b;
	background-color: #e3f0db;
	padding: 5px 5px 5px 5px;
	text-align: left;
}
div.dolEventError h1, div.dolEventError h2 {
	color: #a72947;
	background-color: #d79eac;
	padding: 5px 5px 5px 5px;
	text-align: left;
}

/* ============================================================================== */
/*  Maps                                                                          */
/* ============================================================================== */

.divmap, #google-visualization-geomap-embed-0, #google-visualization-geomap-embed-1, #google-visualization-geomap-embed-2 {
/*    -moz-box-shadow: 0px 0px 10px #AAA;
    -webkit-box-shadow: 0px 0px 10px #AAA;
    box-shadow: 0px 0px 10px #AAA; */
}


/* ============================================================================== */
/*  Datatable                                                                     */
/* ============================================================================== */

table.dataTable tr.odd td.sorting_1, table.dataTable tr.even td.sorting_1 {
  background: none !important;
}
.sorting_asc  { background: url('<?php echo dol_buildpath('/theme/'.$theme.'/img/sort_asc.png',1); ?>') no-repeat center right !important; }
.sorting_desc { background: url('<?php echo dol_buildpath('/theme/'.$theme.'/img/sort_desc.png',1); ?>') no-repeat center right !important; }
.sorting_asc_disabled  { background: url('<?php echo dol_buildpath('/theme/'.$theme.'/img/sort_asc_disabled.png',1); ?>') no-repeat center right !important; }
.sorting_desc_disabled { background: url('<?php echo dol_buildpath('/theme/'.$theme.'/img/sort_desc_disabled.png',1); ?>') no-repeat center right !important; }
.dataTables_paginate {
	margin-top: 8px;
}
.paginate_button_disabled {
  opacity: 1 !important;
  color: #888 !important;
  cursor: default !important;
}
.paginate_disabled_previous:hover, .paginate_enabled_previous:hover, .paginate_disabled_next:hover, .paginate_enabled_next:hover
{
	font-weight: normal;
}
.paginate_enabled_previous:hover, .paginate_enabled_next:hover
{
	text-decoration: underline !important;
}
.paginate_active
{
	text-decoration: underline !important;
}
.paginate_button
{
	font-weight: normal !important;
    text-decoration: none !important;
}
.paging_full_numbers {
	height: inherit !important;
}
.paging_full_numbers a.paginate_active:hover, .paging_full_numbers a.paginate_button:hover {
	background-color: #DDD !important;
}
.paging_full_numbers, .paging_full_numbers a.paginate_active, .paging_full_numbers a.paginate_button {
	background-color: #FFF !important;
	border-radius: inherit !important;
}
.paging_full_numbers a.paginate_button_disabled:hover, .paging_full_numbers a.disabled:hover {
    background-color: #FFF !important;
}
.paginate_button, .paginate_active {
  border: 1px solid #ddd !important;
  padding: 6px 12px !important;
  margin-left: -1px !important;
  line-height: 1.42857143 !important;
  margin: 0 0 !important;
}

/* For jquery plugin combobox */
/* Disable this. It breaks wrapping of boxes
.ui-corner-all { white-space: nowrap; } */

.ui-state-disabled, .ui-widget-content .ui-state-disabled, .ui-widget-header .ui-state-disabled, .paginate_button_disabled {
	opacity: .35;
	filter: Alpha(Opacity=35);
	background-image: none;
}

div.dataTables_length {
	float: right !important;
	padding-left: 8px;
}
div.dataTables_length select {
	background: #fff;
}
.dataTables_wrapper .dataTables_paginate {
	padding-top: 0px !important;
}

/* ============================================================================== */
/*  Select2                                                                       */
/* ============================================================================== */

.select2-default {
    color: #999 !important;
    /*opacity: 0.2;*/
}
.select2-choice, .select2-container .select2-choice {
	border-bottom: solid 1px rgba(0,0,0,.4);
}
.select2-container .select2-choice > .select2-chosen {
    margin-right: 23px;
}
.select2-container .select2-choice .select2-arrow {
	border-radius: 0;
    background: transparent;
}
.select2-container-multi .select2-choices {
	background-image: none;
}
.select2-container .select2-choice {
	color: #000;
	border-radius: 0;
}
.selectoptiondisabledwhite {
	background: #FFFFFF !important;
}
.select2-arrow {
	border: none;
	border-left: none !important;
	background: none !important;
}
.select2-choice
{
	border-top: none !important;
	border-left: none !important;
	border-right: none !important;
}
.select2-drop.select2-drop-above {
	box-shadow: none !important;
}
.select2-drop.select2-drop-above.select2-drop-active {
	border-top: 1px solid #ccc;
	border-bottom: solid 1px rgba(0,0,0,.2);
}
.select2-container-active .select2-choice, .select2-container-active .select2-choices
{
	outline: none;
	border-top: none;
	border-left: none;
	border-bottom: none;
	-webkit-box-shadow: none !important;
	box-shadow: none !important;
}
.select2-dropdown-open {
	background-color: #fff;
}
.select2-dropdown-open .select2-choice, .select2-dropdown-open .select2-choices
{
	outline: none;
	border-top: none;
	border-left: none;
	border-bottom: none;
	-webkit-box-shadow: none !important;
	box-shadow: none !important;
	background-color: #fff;
}
.select2-disabled
{
	color: #888;
}
.select2-drop.select2-drop-above.select2-drop-active, .select2-drop {
	border-radius: 0;
}
.select2-drop.select2-drop-above {
	border-radius:  0;
}
.select2-dropdown-open.select2-drop-above .select2-choice, .select2-dropdown-open.select2-drop-above .select2-choices {
	background-image: none;
	border-radius: 0 !important;
}
div.select2-drop-above
{
	background: #fff;
	-webkit-box-shadow: none !important;
	box-shadow: none !important;
}
.select2-drop-active
{
	border: 1px solid #ccc;
	padding-top: 4px;
}
.select2-search input {
	border: none;
}
a span.select2-chosen
{
	font-weight: normal !important;
}
.select2-container .select2-choice {
	background-image: none;
	/* line-height: 24px; */
}
.select2-results .select2-no-results, .select2-results .select2-searching, .select2-results .select2-ajax-error, .select2-results .select2-selection-limit
{
	background: #FFFFFF;
}
.select2-results {
	max-height:	400px;
}
.css-searchselectcombo ul.select2-results {
	max-height:	none;
}
.select2-container.select2-container-disabled .select2-choice, .select2-container-multi.select2-container-disabled .select2-choices {
	background-color: #FFFFFF;
	background-image: none;
	border: none;
	cursor: default;
}
.select2-container-disabled .select2-choice .select2-arrow b {
	opacity: 0.5;
}
.select2-container-multi .select2-choices .select2-search-choice {
  margin-bottom: 3px;
}
.select2-dropdown-open.select2-drop-above .select2-choice, .select2-dropdown-open.select2-drop-above .select2-choices, .select2-container-multi .select2-choices,
.select2-container-multi.select2-container-active .select2-choices
{
	border-bottom: 1px solid #ccc;
	border-right: none;
	border-top: none;
	border-left: none;

}


/* Special case for the select2 add widget */
#addbox .select2-container .select2-choice > .select2-chosen, #actionbookmark .select2-container .select2-choice > .select2-chosen {
    text-align: left;
    opacity: 0.4;
}
/* Style used before the select2 js is executed on boxcombo */
#boxbookmark.boxcombo, #boxcombo.boxcombo {
    text-align: left;
    opacity: 0.4;
    border-bottom: solid 1px rgba(0,0,0,.4) !important;
    height: 26px;
    line-height: 24px;
    padding: 0 0 2px 0;
    vertical-align: top;
}

/* To emulate select 2 style */
.select2-container-multi-dolibarr .select2-choices-dolibarr .select2-search-choice-dolibarr {
  padding: 2px 5px 1px 5px;
  margin: 0 0 2px 3px;
  position: relative;
  line-height: 13px;
  color: #333;
  cursor: default;
  border: 1px solid #aaaaaa;
  border-radius: 3px;
  -webkit-box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0, 0, 0, 0.05);
  box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0, 0, 0, 0.05);
  background-clip: padding-box;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-color: #e4e4e4;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#f4f4f4', GradientType=0);
  background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, color-stop(20%, #f4f4f4), color-stop(50%, #f0f0f0), color-stop(52%, #e8e8e8), color-stop(100%, #eee));
  background-image: -webkit-linear-gradient(top, #f4f4f4 20%, #f0f0f0 50%, #e8e8e8 52%, #eee 100%);
  background-image: -moz-linear-gradient(top, #f4f4f4 20%, #f0f0f0 50%, #e8e8e8 52%, #eee 100%);
  background-image: linear-gradient(to bottom, #f4f4f4 20%, #f0f0f0 50%, #e8e8e8 52%, #eee 100%);
}
.select2-container-multi-dolibarr .select2-choices-dolibarr .select2-search-choice-dolibarr a {
	font-weight: normal;
}
.select2-container-multi-dolibarr .select2-choices-dolibarr li {
  float: left;
  list-style: none;
}
.select2-container-multi-dolibarr .select2-choices-dolibarr {
  height: auto !important;
  height: 1%;
  margin: 0;
  padding: 0 5px 0 0;
  position: relative;
  cursor: text;
  overflow: hidden;
}


/* ============================================================================== */
/*  For categories                                                                */
/* ============================================================================== */

.noborderoncategories {
	border: none !important;
	border-radius: 5px !important;
	box-shadow: none;
	-webkit-box-shadow: none !important;
    box-shadow: none !important;
}
span.noborderoncategories a, li.noborderoncategories a {
	line-height: normal;
	vertical-align: top;
}
span.noborderoncategories {
	padding: 3px 5px 0px 5px;
}
.categtextwhite, .treeview .categtextwhite.hover {
	color: #fff !important;
}
.categtextblack {
	color: #000 !important;
}


/* ============================================================================== */
/*  Multiselect with checkbox                                                     */
/* ============================================================================== */

ul.ulselectedfields {
    z-index: 95;			/* To have the select box appears on first plan even when near buttons are decorated by jmobile */
}
dl.dropdown {
    margin:0px;
	margin-left: 2px;
    margin-right: 2px;
    padding:0px;
    vertical-align: text-bottom;
    display: inline-block;
}
.dropdown dd, .dropdown dt {
    margin:0px;
    padding:0px;
}
.dropdown ul {
    margin: -1px 0 0 0;
    text-align: left;
}
.dropdown dd {
    position:relative;
}
.dropdown dt a {
    display:block;
    overflow: hidden;
    border:0;
}
.dropdown dt a span, .multiSel span {
    cursor:pointer;
    display:inline-block;
    padding: 0 3px 2px 0;
}
.dropdown dd ul {
    background-color: #FFF;
    border: 1px solid #888;
    display:none;
    right:0px;						/* pop is align on right */
    padding: 2px 15px 2px 5px;
    position:absolute;
    top:2px;
    list-style:none;
    max-height: 270px;
    overflow: auto;
}
.dropdown span.value {
    display:none;
}
.dropdown dd ul li {
	white-space: nowrap;
	font-weight: normal;
	padding: 2px;
}
.dropdown dd ul li input[type="checkbox"] {
    margin-right: 3px;
}
.dropdown dd ul li a, .dropdown dd ul li span {
    padding: 3px;
    display: block;
}
.dropdown dd ul li span {
	color: #888;
}
.dropdown dd ul li a:hover {
    background-color:#fff;
}


/* ============================================================================== */
/*  JMobile                                                                       */
/* ============================================================================== */

li.ui-li-divider .ui-link {
	color: #FFF !important;
}
.ui-btn {
	margin: 0.1em 2px
}
a.ui-link, a.ui-link:hover, .ui-btn:hover, span.ui-btn-text:hover, span.ui-btn-inner:hover {
	text-decoration: none !important;
}
.ui-body-c {
	background: #fff;
}

.ui-btn-inner {
	min-width: .4em;
	padding-left: 6px;
	padding-right: 6px;
	font-size: <?php print $fontsize ?>px;
	/* white-space: normal; */		/* Warning, enable this break the truncate feature */
}
.ui-btn-icon-right .ui-btn-inner {
	padding-right: 30px;
}
.ui-btn-icon-left .ui-btn-inner {
	padding-left: 30px;
}
.ui-select .ui-btn-icon-right .ui-btn-inner {
	padding-right: 30px;
}
.ui-select .ui-btn-icon-left .ui-btn-inner {
	padding-left: 30px;
}
.ui-select .ui-btn-icon-right .ui-icon {
    right: 8px;
}
.ui-btn-icon-left > .ui-btn-inner > .ui-icon, .ui-btn-icon-right > .ui-btn-inner > .ui-icon {
    margin-top: -10px;
}
select {
    /* display: inline-block; */	/* We can't set this. This disable ability to make */
    overflow:hidden;
    white-space: nowrap;			/* Enabling this make behaviour strange when selecting the empty value if this empty value is '' instead of '&nbsp;' */
    text-overflow: ellipsis;
}
.fiche .ui-controlgroup {
	margin: 0px;
	padding-bottom: 0px;
}
div.ui-controlgroup-controls div.tabsElem
{
	margin-top: 2px;
}
div.ui-controlgroup-controls div.tabsElem a
{
	-moz-box-shadow: 0 -3px 6px rgba(0,0,0,.2);
	-webkit-box-shadow: 0 -3px 6px rgba(0,0,0,.2);
	box-shadow: 0 -3px 6px rgba(0,0,0,.2);
}
div.ui-controlgroup-controls div.tabsElem a#active {
	-moz-box-shadow: 0 -3px 6px rgba(0,0,0,.3);
	-webkit-box-shadow: 0 -3px 6px rgba(0,0,0,.3);
	box-shadow: 0 -3px 6px rgba(0,0,0,.3);
}

a.tab span.ui-btn-inner
{
	border: none;
	padding: 0;
}

.ui-link {
	color: rgb(<?php print $colortext; ?>);
}
.liste_titre .ui-link {
	color: rgb(<?php print $colortexttitle; ?>) !important;
}

a.ui-link {
	word-wrap: break-word;
}

/* force wrap possible onto field overflow does not works */
.formdoc .ui-btn-inner
{
	white-space: normal;
	overflow: hidden;
	text-overflow: clip; /* "hidden" : do not exists as a text-overflow value (https://developer.mozilla.org/fr/docs/Web/CSS/text-overflow) */
}

/* Warning: setting this may make screen not beeing refreshed after a combo selection */
/*.ui-body-c {
	background: #fff;
}*/

div.ui-radio, div.ui-checkbox
{
	display: inline-block;
	border-bottom: 0px !important;
}
.ui-checkbox input, .ui-radio input {
	height: auto;
	width: auto;
	margin: 4px;
	position: static;
}
div.ui-checkbox label+input, div.ui-radio label+input {
	position: absolute;
}
.ui-mobile fieldset
{
	padding-bottom: 10px; margin-bottom: 4px; border-bottom: 1px solid #AAAAAA !important;
}

ul.ulmenu {
	border-radius: 0;
	-webkit-border-radius: 0;
}

.ui-field-contain label.ui-input-text {
	vertical-align: middle !important;
}
.ui-mobile fieldset {
	border-bottom: none !important;
}

/* Style for first level menu with jmobile */
.ui-li .ui-btn-inner a.ui-link-inherit, .ui-li-static.ui-li {
    padding: 1em 15px;
    display: block;
}
.ui-btn-up-c {
	font-weight: normal;
}
.ui-focus, .ui-btn:focus {
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    box-shadow: none;
}
.ui-bar-b {
    /*border: 1px solid #888;*/
    border: none;
    background: none;
    text-shadow: none;
    color: rgb(<?php print $colortexttitlenotab; ?>) !important;
}
.ui-bar-b, .lilevel0 {
    background-repeat: repeat-x;
    border: none;
    background: none;
    text-shadow: none;
    color: rgb(<?php print $colortexttitlenotab; ?>) !important;
}
.alilevel0 {
	font-weight: normal !important;
}

.ui-li.ui-last-child, .ui-li.ui-field-contain.ui-last-child {
    border-bottom-width: 0px !important;
}
.alilevel0 {
    color: rgb(<?php echo $colortexttitle; ?>) !important;
    background: #f8f8f8
}
.ulmenu {
	box-shadow: none !important;
	border-bottom: 1px solid #ccc;
}
.ui-btn-icon-right {
	border-right: 1px solid #ccc !important;
}
.ui-body-c {
	border: 1px solid #ccc;
	text-shadow: none;
}
.ui-btn-up-c, .ui-btn-hover-c {
	/* border: 1px solid #ccc; */
	text-shadow: none;
}
.ui-body-c .ui-link, .ui-body-c .ui-link:visited, .ui-body-c .ui-link:hover {
	color: rgb(<?php print $colortextlink; ?>);
}
.ui-btn-up-c .vsmenudisabled {
	color: #<?php echo $colorshadowtitle; ?> !important;
	text-shadow: none !important;
}
div.tabsElem a.tab {
	background: transparent;
}
.alilevel1 {
    color: rgb(<?php print $colortexttitlenotab; ?>) !important;
}
.lilevel1 {
    border-top: 2px solid #444;
    background: #fff ! important;
}
.lilevel1 div div a {
	font-weight: bold !important;
}
.lilevel2
{
	padding-left: 22px;
    background: #fff ! important;
}
.lilevel3
{
	padding-left: 54px;
	background: #fff ! important;
}



/* ============================================================================== */
/*  POS                                                                           */
/* ============================================================================== */

.menu_choix1 a {
	background: url('<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus_black/money.png',1) ?>') top left no-repeat;
}
.menu_choix2 a {
	background: url('<?php echo dol_buildpath($path.'/theme/'.$theme.'/img/menus_black/home.png',1) ?>') top left no-repeat;
}
.menu_choix1,.menu_choix2 {
	font-size: 1.4em;
	text-align: left;
	border: 1px solid #666;
	margin-right: 20px;
}
.menu_choix1 a, .menu_choix2 a {
	display: block;
	color: #fff;
	text-decoration: none;
	padding-top: 18px;
	padding-left: 54px;
	font-size: 14px;
	height: 38px;
}
.menu_choix1 a:hover,.menu_choix2 a:hover {
	color: #6d3f6d;
}
.menu li.menu_choix1 {
    padding-top: 6px;
    padding-right: 10px;
    padding-bottom: 2px;
}
.menu li.menu_choix2 {
    padding-top: 6px;
    padding-right: 10px;
    padding-bottom: 2px;
}
@media only screen and (max-width: 767px)
{
	.menu_choix1 a, .menu_choix2 a {
		background-size: 36px 36px;
		height: 30px;
		padding-left: 40px;
	}
    .menu li.menu_choix1, .menu li.menu_choix2 {
        padding-left: 4px;
        padding-right: 0;
    }
    .liste_articles {
    	margin-right: 0 !important;
    }
}



/* ============================================================================== */
/*  Public                                                                        */
/* ============================================================================== */

/* The theme for public pages */
.public_body {
	margin: 20px;
}
.public_border {
	border: 1px solid #888;
}




/* ============================================================================== */
/* CSS style used for small screen                                                */
/* ============================================================================== */

.topmenuimage {
	background-size: 22px auto;
	top: 2px;
}
.imgopensurveywizard
{
	padding: 0 4px 0 4px;
}
@media only screen and (max-width: 767px)
{
	.imgopensurveywizard, .imgautosize { width:95%; height: auto; }

	#tooltip {
		position: absolute;
		width: <?php print dol_size(350,'width'); ?>px;
	}

    div.tabBar {
        padding-left: 0px;
        padding-right: 0px;
        -moz-border-radius: 0;
        -webkit-border-radius: 0;
    	border-radius: 0px;
        border-right: none;
        border-left: none;
    }
}

@media only screen and (max-width: 1024px)
{
	div#ecm-layout-west {
		width: 100%;
		clear: both;
	}
	div#ecm-layout-center {
		width: 100%;
	}
}

/* nboftopmenuentries = <?php echo $nbtopmenuentries ?>, fontsize=<?php echo $fontsize ?> */
/* rule to reduce top menu - 1st reduction */
@media only screen and (max-width: <?php echo round($nbtopmenuentries * $fontsize * 6.9, 0) + 24; ?>px)	/* reduction 1 */
{
	div.tmenucenter {
	    width: <?php echo round($fontsize * 4); ?>px;	/* size of viewport */
    	white-space: nowrap;
  		overflow: hidden;
  		text-overflow: ellipsis;
  		color: #<?php echo $colortextbackhmenu; ?>;
	}
	.mainmenuaspan {
    	/*display: none;*/
  		font-size: 10px;
    }
    .topmenuimage {
    	background-size: 22px auto;
    	margin-top: 0px;
	}

    li.tmenu, li.tmenusel {
    	min-width: 36px;
    }
    div.mainmenu {
    	min-width: auto;
    }
	div.tmenuleft {
		display: none;
	}
}
/* rule to reduce top menu - 2nd reduction */
@media only screen and (max-width: <?php echo round($nbtopmenuentries * $fontsize * 5, 0) + 24; ?>px)	/* reduction 2 */
{
	div.mainmenu {
		height: 23px;
	}
	div.tmenucenter {
	    max-width: <?php echo round($fontsize * 2); ?>px;	/* size of viewport */
  		text-overflow: clip;
	}
	span.mainmenuaspan {
    	margin-left: 1px;
	}
	.mainmenuaspan {
    	/*display: none;*/
  		font-size: 10px;
    }
    .topmenuimage {
    	background-size: 20px auto;
    	margin-top: 2px;
    	left: 4px;
	}
}
/* rule to reduce top menu - 3rd reduction */
@media only screen and (max-width: <?php echo round($nbtopmenuentries * $fontsize * 3.6, 0) + 12; ?>px)	/* reduction 3 */
{
	.side-nav {
		z-index: 200;
    	background: #FFF;
		padding-top: 70px;
    }
	#id-left {
    	z-index: 201;
        background: #FFF;
	}

    .login_vertical_align {
    	padding-left: 20px;
    	padding-right: 20px;
    }

	/* Reduce login top right info */
	.help {
	<?php if ($disableimages) {  ?>
		display: none;
	<?php } ?>
	}
	div#tmenu_tooltip {
	<?php if (GETPOST('optioncss','aZ09') == 'print') {  ?>
		display:none;
	<?php } else { ?>
		padding-<?php echo $right; ?>: 0;
	<?php } ?>
	}
	div.login_block_user {
		min-width: 0;
		width: 100%;
	}
	div.login_block {
		<?php if ($conf->browser->layout == 'phone' && ((GETPOST('testmenuhider','int') || ! empty($conf->global->MAIN_TESTMENUHIDER)) && empty($conf->global->MAIN_OPTIMIZEFORTEXTBROWSER))) { ?>
		display: none;
		padding-top: 20px;
		padding-left: 20px;
    	padding-right: 20px;
		<?php } else { ?>
		padding-top: 10px;
		padding-left: 5px;
    	padding-right: 5px;
    	<?php } ?>
		top: inherit !important;
		left: 0 !important;
		text-align: center;
        vertical-align: middle;
        background: #FFF;
        height: 42px;

    	z-index: 202;
    	min-width: 190px;
    	max-width: 190px;
    	width: 190px;
    }
	div.login_block_user, div.login_block_other { clear: both; }
	.atoplogin, .atoplogin:hover
	{
		color: #000 !important;
	}
	.login_block_elem {
		padding: 0 !important;
	}
    li.tmenu, li.tmenusel {
        min-width: 32px;
    }
	div.mainmenu {
		height: 23px;
	}
	div.tmenucenter {
  		text-overflow: clip;
	}
    .topmenuimage {
    	background-size: 20px auto;
    	margin-top: 2px !important;
    	left: 2px;
	}
	div.mainmenu {
    	min-width: 20px;
    }
}

<?php
if (is_object($db)) $db->close();
