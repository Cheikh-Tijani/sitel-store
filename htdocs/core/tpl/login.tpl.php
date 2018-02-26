<?php
/* Copyright (C) 2009-2015 Regis Houssin <regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2013 Laurent Destailleur <eldy@users.sourceforge.net>
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

// Need global variable $title to be defined by caller (like dol_loginfunction)
// Caller can also set 	$morelogincontent = array(['options']=>array('js'=>..., 'table'=>...);

header('Cache-Control: Public, must-revalidate');
header("Content-type: text/html; charset=" . $conf->file->character_set_client);

if (GETPOST('dol_hide_topmenu')) $conf->dol_hide_topmenu = 1;
if (GETPOST('dol_hide_leftmenu')) $conf->dol_hide_leftmenu = 1;
if (GETPOST('dol_optimize_smallscreen')) $conf->dol_optimize_smallscreen = 1;
if (GETPOST('dol_no_mouse_hover')) $conf->dol_no_mouse_hover = 1;
if (GETPOST('dol_use_jmobile')) $conf->dol_use_jmobile = 1;

// If we force to use jmobile, then we reenable javascript
if (!empty($conf->dol_use_jmobile)) $conf->use_javascript_ajax = 1;

$php_self = dol_escape_htmltag($_SERVER['PHP_SELF']);
$php_self .= dol_escape_htmltag($_SERVER["QUERY_STRING"]) ? '?' . dol_escape_htmltag($_SERVER["QUERY_STRING"]) : '';
if (!preg_match('/mainmenu=/', $php_self)) $php_self .= (preg_match('/\?/', $php_self) ? '&' : '?') . 'mainmenu=home';

// Javascript code on logon page only to detect user tz, dst_observed, dst_first, dst_second
$arrayofjs = array(
    '/includes/jstz/jstz.min.js' . (empty($conf->dol_use_jmobile) ? '' : '?version=' . urlencode(DOL_VERSION)),
    '/core/js/dst.js' . (empty($conf->dol_use_jmobile) ? '' : '?version=' . urlencode(DOL_VERSION)),
    'theme/eldy/vendor/jquery/jquery-3.2.1.min.js',
    'theme/eldy/vendor/animsition/js/animsition.min.js',
    'theme/eldy/vendor/bootstrap/js/popper.js',
    'theme/eldy/vendor/bootstrap/js/bootstrap.min.js',
    'theme/eldy/vendor/select2/select2.min.js',
    'theme/eldy/vendor/countdowntime/countdowntime.js',
    'theme/eldy/js/main.js'
);
$arraycss = array(
    'theme/eldy/vendor/bootstrap/css/bootstrap.min.cs',
    'theme/eldy/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
    'theme/eldy/fonts/iconic/css/material-design-iconic-font.min.css',
    'theme/eldy/vendor/animate/animate.css',
    'theme/eldy/vendor/css-hamburgers/hamburgers.min.css',
    'theme/eldy/vendor/animsition/css/animsition.min.css',
    'theme/eldy/vendor/select2/select2.min.css',
    'theme/eldy/vendor/daterangepicker/daterangepicker.css',
    'theme/eldy/css/util.css',
    'theme/eldy/css/main.css',
);
$titleofloginpage = $langs->trans('Login') . ' @ ' . $titletruedolibarrversion;    // $titletruedolibarrversion is defined by dol_loginfunction in security2.lib.php. We must keep the @, some tools use it to know it is login page and find true dolibarr version.

$disablenofollow = 1;
if (!preg_match('/' . constant('DOL_APPLICATION_TITLE') . '/', $title)) $disablenofollow = 0;

print top_htmlhead('', '', 0, 0, $arrayofjs, $arraycss, 0, $disablenofollow);
?>
<!-- BEGIN PHP TEMPLATE LOGIN.TPL.PHP -->

<body class="body bodylogin">

<?php if (empty($conf->dol_use_jmobile)) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            // Set focus on correct field
            <?php if ($focus_element) { ?>$('#<?php echo $focus_element; ?>').focus(); <?php } ?>        // Warning to use this only on visible element
        });
    </script>
<?php } ?>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">

            <form id="login" name="login" method="post" action="<?php echo $php_self; ?>"
                  class="login100-form validate-form">
                <input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>"/>
                <input type="hidden" name="loginfunction" value="loginfunction"/>
                <!-- Add fields to send local user information -->
                <input type="hidden" name="tz" id="tz" value=""/>
                <input type="hidden" name="tz_string" id="tz_string" value=""/>
                <input type="hidden" name="dst_observed" id="dst_observed" value=""/>
                <input type="hidden" name="dst_first" id="dst_first" value=""/>
                <input type="hidden" name="dst_second" id="dst_second" value=""/>
                <input type="hidden" name="screenwidth" id="screenwidth" value=""/>
                <input type="hidden" name="screenheight" id="screenheight" value=""/>
                <input type="hidden" name="dol_hide_topmenu" id="dol_hide_topmenu"
                       value="<?php echo $dol_hide_topmenu; ?>"/>
                <input type="hidden" name="dol_hide_leftmenu" id="dol_hide_leftmenu"
                       value="<?php echo $dol_hide_leftmenu; ?>"/>
                <input type="hidden" name="dol_optimize_smallscreen" id="dol_optimize_smallscreen"
                       value="<?php echo $dol_optimize_smallscreen; ?>"/>
                <input type="hidden" name="dol_no_mouse_hover" id="dol_no_mouse_hover"
                       value="<?php echo $dol_no_mouse_hover; ?>"/>
                <input type="hidden" name="dol_use_jmobile" id="dol_use_jmobile"
                       value="<?php echo $dol_use_jmobile; ?>"/>


                <span class="login100-form-logo">
						<img alt="" src="<?php echo $urllogo; ?>" id="img_logo"/>
					</span>

                <span class="login100-form-title p-b-34 p-t-27">
						<?php
                        if ($disablenofollow) echo '<a class="login_table_title" href="#" target="_blank">';
                        echo dol_escape_htmltag('SITEL-STORE');
                        if ($disablenofollow) echo '</a>';
                        ?>
					</span>
                <div class="wrap-input100 validate-input" data-validate="Enter username">
                    <input class="input100" value="<?php echo dol_escape_htmltag($login); ?>" type="text"
                           name="username" placeholder="<?php echo $langs->trans("Login"); ?>">
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <input class="input100" value="<?php echo dol_escape_htmltag($password); ?>" type="password"
                           name="password"
                           placeholder="<?php echo $langs->trans("Password"); ?>"
                           autocomplete="<?php echo empty($conf->global->MAIN_LOGIN_ENABLE_PASSWORD_AUTOCOMPLETE) ? 'off' : 'on'; ?>">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div class="container-login100-form-btn">
                    <input type="submit" class="login100-form-btn">
                    </input>
                </div>

            </form>


            <?php if (!empty($_SESSION['dol_loginmesg'])) {
                ?>
                <div class="center login_main_message">
                    <div class="error">
                        <?php echo $_SESSION['dol_loginmesg']; ?>
                    </div>
                </div>
                <?php
            }

            // Add commit strip
            if (!empty($conf->global->MAIN_EASTER_EGG_COMMITSTRIP)) {
                include_once DOL_DOCUMENT_ROOT . '/core/lib/geturl.lib.php';
                if (substr($langs->defaultlang, 0, 2) == 'fr') {
                    $resgetcommitstrip = getURLContent("http://www.commitstrip.com/fr/feed/");
                } else {
                    $resgetcommitstrip = getURLContent("http://www.commitstrip.com/en/feed/");
                }
                if ($resgetcommitstrip && $resgetcommitstrip['http_code'] == '200') {
                    $xml = simplexml_load_string($resgetcommitstrip['content']);
                    $little = $xml->channel->item[0]->children('content', true);
                    print $little->encoded;
                }
            }

            ?>

            <?php if ($main_home) {
                ?>
                <div class="center login_main_home" style="max-width: 70%">
                    <?php echo $main_home; ?>
                </div><br>
                <?php
            }
            ?>

            <!-- authentication mode = <?php echo $main_authentication ?> -->
            <!-- cookie name used for this session = <?php echo $session_name ?> -->
            <!-- urlfrom in this session = <?php echo isset($_SESSION["urlfrom"]) ? $_SESSION["urlfrom"] : ''; ?> -->

            <!-- Common footer is not used for login page, this is same than footer but inside login tpl -->

            <?php
            if (!empty($conf->global->MAIN_HTML_FOOTER)) print $conf->global->MAIN_HTML_FOOTER;

            if (!empty($morelogincontent) && is_array($morelogincontent)) {
                foreach ($morelogincontent as $format => $option) {
                    if ($format == 'js') {
                        echo "\n" . '<!-- Javascript by hook -->';
                        echo $option . "\n";
                    }
                }
            } else if (!empty($moreloginextracontent)) {
                echo '<!-- Javascript by hook -->';
                echo $moreloginextracontent;
            }

            // Google Analytics (need Google module)
            if (!empty($conf->google->enabled) && !empty($conf->global->MAIN_GOOGLE_AN_ID)) {
                if (empty($conf->dol_use_jmobile)) {
                    print "\n";
                    print '<script type="text/javascript">' . "\n";
                    print '  var _gaq = _gaq || [];' . "\n";
                    print '  _gaq.push([\'_setAccount\', \'' . $conf->global->MAIN_GOOGLE_AN_ID . '\']);' . "\n";
                    print '  _gaq.push([\'_trackPageview\']);' . "\n";
                    print '' . "\n";
                    print '  (function() {' . "\n";
                    print '    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;' . "\n";
                    print '    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';' . "\n";
                    print '    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);' . "\n";
                    print '  })();' . "\n";
                    print '</script>' . "\n";
                }
            }

            // Google Adsense
            if (!empty($conf->google->enabled) && !empty($conf->global->MAIN_GOOGLE_AD_CLIENT) && !empty($conf->global->MAIN_GOOGLE_AD_SLOT)) {
                if (empty($conf->dol_use_jmobile)) {
                    ?>
                    <div class="center"><br>
                        <script type="text/javascript"><!--
                            google_ad_client = "<?php echo $conf->global->MAIN_GOOGLE_AD_CLIENT ?>";
                            google_ad_slot = "<?php echo $conf->global->MAIN_GOOGLE_AD_SLOT ?>";
                            google_ad_width = <?php echo $conf->global->MAIN_GOOGLE_AD_WIDTH ?>;
                            google_ad_height = <?php echo $conf->global->MAIN_GOOGLE_AD_HEIGHT ?>;
                            //-->
                        </script>
                        <script type="text/javascript"
                                src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                    </div>
                    <?php
                }
            }
            ?>


        </div>
    </div>
</div>    <!-- end of center -->


</body>
</html>
<!-- END PHP TEMPLATE -->
