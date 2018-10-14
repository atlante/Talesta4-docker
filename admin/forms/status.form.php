<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: status.form.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.10 $
$Date: 2010/01/24 16:37:10 $

*/


	if ((NOM_SCRIPT==("supprimer_spec.".$phpExtJeu))||(NOM_SCRIPT==("supprimer_etat.".$phpExtJeu)))
		{ $readonly= " readonly='readonly'  ";$disabled= " disabled='disabled'  "; }
	else { $readonly= "";$disabled= ""; }

$toto = array_keys($liste_caracs);
$tata = array_values($liste_caracs);
$nbCarac=count($liste_caracs);
$temp=array();
for($i=0;$i<$nbCarac;$i++){
	if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=0;}
	$temp[($i*3)]=GetImage($toto[$i]);
	$temp[($i*3)+1]=$toto[$i];
	$temp[($i*3)+2]="<input type='text' maxlength='4' $readonly name='comp[".$tata[$i]."]' size='4' value='".$comp[$tata[$i]]."' />";
}
$premiere_ligne=array(
		"&nbsp;",
		"&nbsp;",
		"&nbsp;",
		makeTableau(6, "", "details", $temp,"nowrap","",1)
);
unset($temp);unset($toto);unset($tata);

$toto = array_keys($liste_competences);
$tata = array_values($liste_competences);
$temp=array();
$nbComp=count($liste_competences);
for($i=0;$i<$nbComp;$i++){
	if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=0;}
	$temp[($i*3)]=GetImage($toto[$i]);
	$temp[($i*3)+1]=$toto[$i];
	$temp[($i*3)+2]="<input type='text' maxlength='4' $readonly name='comp[".$tata[$i]."]' size='4' value='".$comp[$tata[$i]]."' />";
}
unset ($toto); unset($tata);
$toto = array_keys($liste_magie);
$tata = array_values($liste_magie);
$temp2=array();
$nbMagie=count($liste_magie);
for($i=0;$i<$nbMagie;$i++){
	if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=0;}
	$temp2[($i*3)]=GetImage($toto[$i]);
	$temp2[($i*3)+1]=$toto[$i];
	$temp2[($i*3)+2]="<input type='text' maxlength='4' $readonly name='comp[".$tata[$i]."]' size='4' value='".$comp[$tata[$i]]."' />";
}

$deuxieme_ligne=array(
		"&nbsp;",
		makeTableau(9, "", "details", $temp,"nowrap","",1),
		makeTableau(3, "", "details", $temp2,"nowrap","",1),
		"&nbsp;"
);
unset($tata);unset($toto);
$temp3=array();
$toto = array_keys($liste_artisanat);
$tata = array_values($liste_artisanat);
$nbArtisanat=count($liste_artisanat);
for($i=0;$i<$nbArtisanat;$i++){
	if(!isset($comp[$tata[$i]])){$comp[$tata[$i]]=0;}
	$temp3[($i*3)]=GetImage($toto[$i]);
	$temp3[($i*3)+1]=$toto[$i];
	$temp3[($i*3)+2]="<input type='text' maxlength='4' $readonly name='comp[".$tata[$i]."]' size='4' value='".$comp[$tata[$i]]."' />";
}

$deuxiemeBis_ligne=array(
		"&nbsp;",
		makeTableau(12, "", "details", $temp3,"nowrap","",1),
		"&nbsp;"
);

unset($temp);unset($temp2);unset($temp3);unset($tata);unset($toto);

$template_main .= "<br /><a href=\"javascript:a('gethelp.$phpExtJeu?page=creer_etattemp.htm')\">Aide</a><br />";
$template_main .= makeTableau(4, "center","container", $premiere_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(4, "","container", $deuxieme_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
$template_main .= makeTableau(4, "","container", $deuxiemeBis_ligne,"","100%",0,true);
$template_main .= "<br />&nbsp;";
?>