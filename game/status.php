<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: status.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.18 $
$Date: 2010/01/24 17:44:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN"))
	if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $status_pj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
$premiere_ligne=array(
		makeTableau(2, "", "details", $PERSO->DescriptionGenerale(),"nowrap","",1),
		"&nbsp;");
if (($tmp=$PERSO->DescriptionAvatar())!="") {
	array_push($premiere_ligne,makeTableau(1,"","details", array($tmp),"nowrap","",1));
}	
array_push($premiere_ligne,makeTableau(6, "", "details", $PERSO->DescriptionCaracteristiques(),"nowrap","",1));

$PERSO->getDescription();
$premiere_ligneBis=array(
		"&nbsp;",
		makeTableau(2, "", "details", $PERSO->DescriptionBackGround(),"nowrap","",1),
		"&nbsp;"
);


$deuxieme_ligne=array(
		"&nbsp;",
		makeTableau(12, "", "details", $PERSO->DescriptionCompetences(),"nowrap","",1),
		makeTableau(3, "", "details", $PERSO->DescriptionMagie(),"nowrap","",1),
		"&nbsp;"
);

$deuxiemeBis_ligne=array(
		"&nbsp;",
		makeTableau(15, "", "details", $PERSO->DescriptionArtisanales(),"nowrap","",1),
		"&nbsp;"
);

$troisieme_ligne=array(
	"&nbsp;",
	makeTableau(2, "", "details", $PERSO->DescriptionEtatsTemporaires(),"nowrap","",1),
	"&nbsp;"
);
$quatrieme_ligne=array(
	"&nbsp;",
	makeTableau(11, "", "details", $PERSO->DescriptionInventaire(),"","",1),
	"&nbsp;"
);
$cinquieme_ligne=array(
	"&nbsp;",
	makeTableau(14, "", "details", $PERSO->DescriptionGrimoire(),"","",1),
	"&nbsp;"
);
$sixieme_ligne=array(
	"&nbsp;",
	makeTableau(2, "", "details", $PERSO->DescriptionPreferences(),"nowrap","",1),
	"&nbsp;"
);

$quetes = array(
	"&nbsp;",
	makeTableau(10, "", "details", $PERSO->DescriptionQuetes(),"","",1),
	"&nbsp;"
);

$template_main .= makeTableau(4, "center","container", $premiere_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(2, "center","container",$premiere_ligneBis,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(4, "","container", $deuxieme_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(3, "","container", $deuxiemeBis_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(3, "center","container", $troisieme_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(3, "center","container", $quatrieme_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(3, "center","container", $cinquieme_ligne,"","100%",0,true);

$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(3, "center","container", $quetes,"","100%",0,true);

$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(2, "center","container", $sixieme_ligne,"","100%",0,true);

if(defined("PAGE_EN_JEU")) {
	$template_main .= "<br /><p>&nbsp;</p>";
	if(!defined("__MENU_JEU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
}
?>