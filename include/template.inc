<?php
function parsetemplate($var_temp)
{
    global $menu_site;
    global $barre;
    global $titrepage;

    if (defined ("IN_PHPBB") && IN_PHPBB==1)
    {
        $var_temp = preg_replace('`{IN_PHPBB,([^>].*)}`', '$1', $var_temp);
    }
    else
    {
        $var_temp = preg_replace('`{IN_PHPBB,([^>].*)}`', '', $var_temp);
    }
    if (defined ("IN_NEWS") && IN_NEWS==1)
    {
        $var_temp = preg_replace('`{IN_NEWS,([^>].*)}`', '$1', $var_temp);
    }
    else
    {
        $var_temp = preg_replace('`{IN_NEWS,([^>].*)}`', '', $var_temp);
    }

    if (defined("NOM_JEU"))
    	$var_temp = str_replace ('{NOM_JEU}', NOM_JEU, $var_temp);
    else $var_temp = str_replace ('{NOM_JEU}', "Talesta 4+", $var_temp);	
    $var_temp = str_replace ('{SCRIPT_GEN}', "<!-- Gnr  partir du script, ".NOM_SCRIPT.", le [".date("d/m/Y  H:s")."] //-->", $var_temp);
    if (isset($titrepage))
    	$var_temp = str_replace ('{TITRE_PAGE}', $titrepage, $var_temp);
    else $var_temp = str_replace ('{TITRE_PAGE}',"", $var_temp);   
    if (defined("META_DESCRIPTION")) 	
    	$var_temp = str_replace ('{META_DESCRIPTION}', META_DESCRIPTION, $var_temp);
    else $var_temp = str_replace ('{META_DESCRIPTION}', "Moteur pour jeux de roles en PHP", $var_temp);	    	
    if (defined("META_DESCRIPTION"))
    	$var_temp = str_replace ('{META_KEYWORDS}', META_KEYWORDS, $var_temp);
    else 	
    	$var_temp = str_replace ('{META_KEYWORDS}', "Moteur, jeux de roles, PHP, jdr, communaut", $var_temp);
    $var_temp = str_replace ('{MENU_SITE}', $menu_site, $var_temp);
    
    if (!isset($barre))
    	$barre="";
    $var_temp = str_replace ('{BARRE}', $barre, $var_temp);
    $var_temp = str_replace ('{DEBUG_SQL_TIME}', DEBUG_SQL_TIME, $var_temp);
    echo $var_temp;

}

?>
