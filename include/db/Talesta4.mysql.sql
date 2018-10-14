# mysql-front dump 2.5
#
# host: localhost   database: talesta4
# --------------------------------------------------------
# server version 3.23.47-nt

##Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  
##
##$RCSfile: Talesta4.mysql.sql,v $
##$Revision: 1.16 $
##$Date: 2010/01/24 19:33:35 $

#
# TABLE structure for TABLE 'tlt_lieu'
#

DROP TABLE IF EXISTS tlt_lieu;

CREATE TABLE tlt_lieu (
  id_lieu INTEGER NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL DEFAULT '',
  flags tinytext NOT NULL,
  trigramme CHAR(3) NOT NULL DEFAULT '',
  `accessible` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  id_forum INTEGER UNSIGNED NOT NULL DEFAULT '0',
  provoqueetat VARCHAR(100) NULL,
  PRIMARY KEY  (id_lieu)
) type=myisam;



#
# dumping data for TABLE 'tlt_lieu'
#

INSERT into tlt_lieu (id_lieu, nom, flags, trigramme, `accessible`, id_forum, provoqueetat) values("1", "lieu de depart", "00000000000000000000000000000", "spe", "0", "0", "");


#
# TABLE structure for TABLE 'tlt_chemins'
#

DROP TABLE IF EXISTS tlt_chemins;

CREATE TABLE tlt_chemins (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_lieu_1 INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_lieu (id_lieu) ON DELETE cascade,
  id_lieu_2 INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_lieu (id_lieu) ON DELETE cascade,
  type TINYINT(4) NOT NULL DEFAULT '0',
  difficulte TINYINT(4) NULL DEFAULT '0',
  pass VARCHAR(50) NULL,
  distance TINYINT(4) NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



#
# dumping data for TABLE 'tlt_chemins'
#

#
# TABLE structure for TABLE 'tlt_inscriptions'
#

DROP TABLE IF EXISTS tlt_inscriptions;

CREATE TABLE tlt_inscriptions (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL DEFAULT '',
  pass VARCHAR(50) NOT NULL DEFAULT '',
  email VARCHAR(80) NOT NULL DEFAULT '',
  race VARCHAR(50) NOT NULL DEFAULT '',
  sexe TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  description blob NOT NULL,
  PRIMARY KEY  (id)
) type=myisam;



#
# dumping data for TABLE 'tlt_inscriptions'
#

#
# TABLE structure for TABLE 'tlt_mj'
#

DROP TABLE IF EXISTS tlt_mj;

CREATE TABLE tlt_mj (
  id_mj INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  nom 	VARCHAR(250) NOT NULL DEFAULT '',
  pass 	VARCHAR(50) NOT NULL DEFAULT '',
  titre VARCHAR(25) NOT NULL DEFAULT '',
  flags VARCHAR(80) DEFAULT NULL,
  email VARCHAR(80) NOT NULL DEFAULT '',
  lastaction INTEGER UNSIGNED NOT NULL DEFAULT '0',
  fanonlu TINYINT(3) UNSIGNED DEFAULT '0',
  wantmail TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_mj)
) type=myisam;



#
# dumping data for TABLE 'tlt_mj'
#

INSERT INTO tlt_mj  (id_mj, nom, pass, titre, flags, email, lastaction, fanonlu, wantmail) values("1", "admin", "votremotdepasse", "MJ supreme", "111111111111111111111111111111111111111111111111", "", "1051302306", "1", "0");


#
# TABLE structure for TABLE 'tlt_magie'
#

DROP TABLE IF EXISTS tlt_magie;

CREATE TABLE tlt_magie (
  id_magie INTEGER NOT NULL AUTO_INCREMENT,
  type VARCHAR(50) NOT NULL DEFAULT '',
  sous_type VARCHAR(50) NOT NULL DEFAULT '',
  nom VARCHAR(64) NOT NULL DEFAULT '',
  degats_min TINYINT(4) NOT NULL DEFAULT '0',
  degats_max TINYINT(4) NOT NULL DEFAULT '0',
  anonyme TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  prix_base INTEGER NOT NULL DEFAULT '0',
  description VARCHAR(255) NOT NULL DEFAULT '',
  image VARCHAR(50) NULL,
  permanent TINYINT(4) NOT NULL DEFAULT '0',
  place TINYINT(4) NOT NULL DEFAULT '0',
  charges TINYINT(4) NOT NULL DEFAULT '0',
  caracteristique VARCHAR(50) NULL,
  competence VARCHAR(50) NULL,
  provoqueetat VARCHAR(100) NULL,
  PRIMARY KEY  (id_magie)
) type=myisam;



#
# dumping data for TABLE 'tlt_magie'
#

INSERT into tlt_magie (id_magie, type, sous_type, nom, degats_min, degats_max, anonyme, prix_base, description, image, permanent, place, charges, caracteristique, competence, provoqueetat) values("1", "Air", "Soin", "sort depart - a editer", "1", "2", "0", "32", "", "", "1", "1", "-1", "Intelligence", "Air", "");


#
# TABLE structure for TABLE 'tlt_objets'
#

DROP TABLE IF EXISTS tlt_objets;

CREATE TABLE tlt_objets (
  id_objet INTEGER NOT NULL AUTO_INCREMENT,
  type VARCHAR(50) NOT NULL DEFAULT '',
  sous_type VARCHAR(50) NOT NULL DEFAULT '',
  nom VARCHAR(64) NOT NULL DEFAULT '',
  degats_min INTEGER NOT NULL DEFAULT '0',
  degats_max INTEGER NOT NULL DEFAULT '0',
  anonyme TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  durabilite INTEGER NOT NULL DEFAULT '-1',
  prix_base INTEGER NOT NULL DEFAULT '0',
  description VARCHAR(255) NOT NULL DEFAULT '',
  poids INTEGER NOT NULL DEFAULT '0',
  image VARCHAR(50),
  permanent TINYINT(4) NOT NULL DEFAULT '0',
  munitions INTEGER NOT NULL DEFAULT '-1',
  caracteristique VARCHAR(50) NULL,
  competence VARCHAR(50) NULL,
  provoqueetat VARCHAR(100) NULL,
  competencespe VARCHAR(50) NULL,
  PRIMARY KEY  (id_objet)
) type=myisam;



#
# dumping data for TABLE 'tlt_objets'
#

INSERT into tlt_objets (id_objet, type, sous_type, nom, degats_min, degats_max, anonyme, durabilite, prix_base, description, poids, image, permanent, munitions, caracteristique, competence, provoqueetat, competencespe) values("1", "ArmeMelee", "Arts Martiaux", "objet de depart - a editer", "1", "2", "0", "-1", "0", "un coup de poing", "0", "", "1", "-1", "Force", "Arts Martiaux", "", "");


#
# TABLE structure for TABLE 'tlt_perso'
#

DROP TABLE IF EXISTS tlt_perso;

CREATE TABLE tlt_perso (
  id_perso INTEGER NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL DEFAULT '',
  pass VARCHAR(50) NOT NULL DEFAULT '',
  race VARCHAR(50) NOT NULL DEFAULT '',
  sexe TINYINT(4) NOT NULL DEFAULT '0',
  pa INTEGER NOT NULL DEFAULT '0',
  pv INTEGER NOT NULL DEFAULT '0',
  po INTEGER NOT NULL DEFAULT '0',
  banque INT(10) UNSIGNED NOT NULL DEFAULT '0',
  id_lieu INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_lieu (id_lieu) ON DELETE restrict,
  email VARCHAR(80) DEFAULT NULL,
  interval_remise INT(10) UNSIGNED NOT NULL DEFAULT '72',
  derniere_remise INT(35) NOT NULL DEFAULT '0',
  lastaction INT(10) UNSIGNED NOT NULL DEFAULT '0',
  fanonlu TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  wantmail TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_perso)
) type=myisam;



#
# dumping data for TABLE 'tlt_perso'
#

#
# TABLE structure for TABLE 'tlt_etattempnom'
#

DROP TABLE IF EXISTS tlt_etattempnom;

CREATE TABLE tlt_etattempnom (
  id_etattemp INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL DEFAULT '',
  rpa INTEGER NOT NULL DEFAULT '0',
  rpv INTEGER NOT NULL DEFAULT '0',
  rpo INTEGER NOT NULL DEFAULT '0',
  visible TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_etattemp)
) type=myisam;



#
# dumping data for TABLE 'tlt_etattempnom'
#

INSERT into tlt_etattempnom (id_etattemp, nom, rpa, rpv, rpo, visible) values("1", "etat normal", "0", "0", "0", "0");

#
# TABLE structure for TABLE 'tlt_comp'
#

DROP TABLE IF EXISTS tlt_comp;

CREATE TABLE tlt_comp (
  id INTEGER NOT NULL AUTO_INCREMENT,
  id_perso INTEGER NOT NULL DEFAULT '0',
  id_comp INTEGER NOT NULL DEFAULT '0',
  xp INTEGER NOT NULL DEFAULT '0',
  PRIMARY KEY  (id)
) type=myisam;



#
# dumping data for TABLE 'tlt_comp'
#



#
# TABLE structure for TABLE 'tlt_etattemp'
#

DROP TABLE IF EXISTS tlt_etattemp;

CREATE TABLE tlt_etattemp (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_etattemp INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_etattempnom ( id_etattemp)  ON DELETE cascade,
  id_comp INTEGER NOT NULL DEFAULT '0',
  bonus TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



#
# dumping data for TABLE 'tlt_etattemp'
#

#
# TABLE structure for TABLE 'tlt_persoetattemp'
#

DROP TABLE IF EXISTS tlt_persoetattemp;

CREATE TABLE tlt_persoetattemp (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_perso INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_perso (id_perso) ON DELETE cascade,
  id_etattemp INTEGER NOT NULL DEFAULT '0',
  fin INT(35) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



#
# dumping data for TABLE 'tlt_persoetattemp'
#



#
# TABLE structure for TABLE 'tlt_persomagie'
#

DROP TABLE IF EXISTS tlt_persomagie;

CREATE TABLE tlt_persomagie (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_perso INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_perso (id_perso) ON DELETE cascade,
  id_magie INTEGER NOT NULL DEFAULT '0',
  charges TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



#
# dumping data for TABLE 'tlt_persomagie'
#



#
# TABLE structure for TABLE 'tlt_persoobjets'
#

DROP TABLE IF EXISTS tlt_persoobjets;

CREATE TABLE tlt_persoobjets (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_perso INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_perso (id_perso) ON DELETE cascade,
  id_objet INTEGER NOT NULL DEFAULT '0',
  durabilite INTEGER NOT NULL DEFAULT '-1',
  munitions INTEGER NOT NULL DEFAULT '-1',
  temporaire TINYINT(4) NOT NULL DEFAULT '0',
  equipe TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



#
# dumping data for TABLE 'tlt_persoobjets'
#



#
# TABLE structure for TABLE 'tlt_persospec'
#

DROP TABLE IF EXISTS tlt_persospec;

CREATE TABLE tlt_persospec (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_perso INTEGER NOT NULL DEFAULT '0',
  id_spec INTEGER NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



#
# dumping data for TABLE 'tlt_persospec'
#



#
# TABLE structure for TABLE 'tlt_sessions'
#

DROP TABLE IF EXISTS tlt_sessions;

CREATE TABLE tlt_sessions (
  idsession VARCHAR(100) NOT NULL DEFAULT '',
  ip VARCHAR(25) NOT NULL DEFAULT '',
  datestart INTEGER UNSIGNED NOT NULL DEFAULT '0',
  duree INTEGER UNSIGNED NOT NULL DEFAULT '3600',
  permanent TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  id_joueur INTEGER UNSIGNED NOT NULL DEFAULT '0',
  lastaction INTEGER UNSIGNED NOT NULL DEFAULT '0',
  pj TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY  (idsession)
) type=myisam;



#
# dumping data for TABLE 'tlt_sessions'
#





# TABLE structure for TABLE 'tlt_specnom'
#

DROP TABLE IF EXISTS tlt_specnom;

CREATE TABLE tlt_specnom (
  id_spec INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL DEFAULT '',
  rpo INTEGER NOT NULL DEFAULT '0',
  rpa INTEGER NOT NULL DEFAULT '0',
  rpv INTEGER NOT NULL DEFAULT '0',
  visible TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_spec)
) type=myisam;




#
# dumping data for TABLE 'tlt_specnom'
#

INSERT into tlt_specnom (id_spec, nom, rpo, rpa, rpv, visible) values("1", "pas de spécialisation", "0", "0", "0", "0");

#
# TABLE structure for TABLE 'tlt_spec'
#

DROP TABLE IF EXISTS tlt_spec;

CREATE TABLE tlt_spec (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_spec INTEGER NOT NULL DEFAULT '0',
  id_comp INTEGER NOT NULL DEFAULT '0',
  bonus TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



#
# dumping data for TABLE 'tlt_spec'
#




#
# TABLE structure for TABLE 'tlt_zone'
#

DROP TABLE IF EXISTS tlt_zone;

CREATE TABLE tlt_zone (
  id_zone INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_lieu INT(10) UNSIGNED NOT NULL DEFAULT '0' REFERENCES tlt_lieu (id_lieu) ON DELETE cascade,
  type TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  pointeur INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_zone)
) type=myisam;



#
# dumping data for TABLE 'tlt_zone'
#




# debut modif hixcks
ALTER TABLE tlt_perso    ADD pnj TINYINT DEFAULT '0' NOT NULL;

ALTER TABLE tlt_perso    ADD relation TINYINT DEFAULT '2' NOT NULL;

ALTER TABLE tlt_perso    ADD reaction TINYINT DEFAULT '4' NOT NULL;

ALTER TABLE tlt_perso    ADD armepreferee TINYINT NULL;

ALTER TABLE tlt_perso    ADD sortprefere TINYINT  NULL;

ALTER TABLE tlt_perso    ADD phrasepreferee text  NULL;

ALTER TABLE tlt_perso    ADD actionsurprise TINYINT DEFAULT '4' NOT NULL;


DROP TABLE IF EXISTS tlt_entitecachee;

CREATE TABLE tlt_entitecachee (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  
  id_entite INT(10) UNSIGNED NOT NULL,  
  id_lieu  INTEGER NOT NULL REFERENCES tlt_lieu (id_lieu) ON DELETE cascade,
  type TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (id)
) type=myisam;

ALTER TABLE tlt_entitecachee ADD INDEX (id_lieu);

DROP TABLE IF EXISTS tlt_entitecacheeconnuede;

CREATE TABLE tlt_entitecacheeconnuede (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  
  id_entitecachee INT(10) UNSIGNED NOT NULL REFERENCES tlt_entitecachee (id),  
  id_perso  INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE cascade,
  PRIMARY KEY  (id)
) type=myisam;

ALTER TABLE tlt_entitecacheeconnuede ADD UNIQUE(id_entitecachee, id_perso);

ALTER TABLE tlt_perso ADD UNIQUE(nom);

ALTER TABLE tlt_inscriptions ADD UNIQUE(nom);

ALTER TABLE tlt_mj ADD UNIQUE(nom);

ALTER TABLE tlt_perso ADD archive TINYINT DEFAULT '0' NOT NULL;


DROP TABLE IF EXISTS tlt_archive;

CREATE TABLE tlt_archive (
  id_archive INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  
  id_perso  INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE cascade,
  datearchivage date NOT NULL,
  datedesarchivage date NULL,
  PRIMARY KEY  (id_archive)
) type=myisam;


# ajout des groupes de pj
DROP TABLE IF EXISTS tlt_groupe;

CREATE TABLE tlt_groupe (
  id_groupe INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  
  nom VARCHAR(50) NOT NULL,
  PRIMARY KEY  (id_groupe)
) type=myisam;

ALTER TABLE tlt_groupe ADD UNIQUE(nom);

ALTER TABLE tlt_perso ADD id_groupe INT(10) UNSIGNED NULL REFERENCES tlt_groupe (id_groupe) ON DELETE SET NULL;

ALTER TABLE tlt_perso ADD INDEX (id_groupe);

# fin ajout des groupes de pj

ALTER TABLE tlt_sessions ADD INDEX (id_joueur);

ALTER TABLE tlt_sessions ADD INDEX (pj);

ALTER TABLE tlt_sessions ADD INDEX (permanent); 

ALTER TABLE tlt_chemins ADD INDEX (id_lieu_1);

ALTER TABLE tlt_chemins ADD INDEX (id_lieu_2);

ALTER TABLE tlt_chemins ADD INDEX (type);

ALTER TABLE tlt_comp ADD INDEX (id_perso);

ALTER TABLE tlt_persospec ADD INDEX (id_perso);

ALTER TABLE tlt_persoetattemp ADD INDEX (id_perso);

ALTER TABLE tlt_persoobjets ADD INDEX (id_perso);

ALTER TABLE tlt_persomagie ADD INDEX (id_perso);


# modif pour ajouter des points d'intellect

ALTER TABLE tlt_etattempnom ADD rpi INTEGER NOT NULL DEFAULT '0';

ALTER TABLE tlt_specnom  ADD rpi INTEGER NOT NULL DEFAULT '0';

ALTER TABLE tlt_perso ADD pi INTEGER NOT NULL DEFAULT '0';

ALTER TABLE tlt_perso CHANGE interval_remise interval_remisepa INT( 10 ) UNSIGNED DEFAULT '72' NOT NULL ;

ALTER TABLE tlt_perso CHANGE derniere_remise derniere_remisepa INT( 35 ) DEFAULT '0' NOT NULL ;

ALTER TABLE tlt_perso ADD interval_remisepi INT(10) UNSIGNED NOT NULL DEFAULT '90';

ALTER TABLE tlt_perso ADD derniere_remisepi INT(35) NOT NULL DEFAULT '0';

# suppression de armepreferee qui ne sert plus 

ALTER TABLE tlt_perso    DROP armepreferee;

# fin modifs pour ajouter des points d'intellect

# mise a 25 pour etre de meme taille que phpbb_users

ALTER TABLE tlt_perso CHANGE nom nom VARCHAR( 25 ) NOT NULL;

# mise a 25 pour etre de meme taille que phpbb_users

ALTER TABLE tlt_mj CHANGE nom nom VARCHAR( 25 ) NOT NULL ;

# mise a 25 pour etre de meme taille que phpbb_users

ALTER TABLE tlt_inscriptions CHANGE nom nom VARCHAR( 25 ) NOT NULL ;

ALTER TABLE tlt_objets ADD UNIQUE (nom);


ALTER TABLE tlt_specnom ADD UNIQUE (nom);

ALTER TABLE tlt_magie ADD UNIQUE (nom) ;

ALTER TABLE tlt_lieu ADD UNIQUE (nom, trigramme) ;

ALTER TABLE tlt_etattempnom ADD UNIQUE (nom) ;

ALTER TABLE tlt_perso ADD ip_joueur VARCHAR(9) NULL;

# passage en NULL => NULL devient connu de tous les persos (utilisé pour placer un objet a tel endroit)

ALTER TABLE tlt_entitecacheeconnuede CHANGE id_perso id_perso INTEGER;

# passage du mot de passe des joueurs en crypte

UPDATE tlt_inscriptions SET pass = md5(pass);

ALTER TABLE tlt_inscriptions CHANGE pass pass VARCHAR(32);

UPDATE tlt_perso SET pass = md5(pass);

ALTER TABLE tlt_perso CHANGE pass pass VARCHAR(32);

# fin de passage du mot de passe des joueurs en crypte




# sql du bug permanent temporaire. normalement ne fait rien
# mais on ne sait jamais 

UPDATE tlt_objets SET permanent = 1 WHERE sous_type <> 'clef';

UPDATE tlt_persoobjets, tlt_objets SET tlt_persoobjets.temporaire = 0
WHERE tlt_persoobjets.id_objet = tlt_objets.id_objet and tlt_objets.permanent = 1;

# fin bug permanent temporaire

# modifs pour gestion des armes a 2 mains

DELETE FROM tlt_persoobjets WHERE id_objet=1;

UPDATE  tlt_persoobjets SET equipe=0;

INSERT into tlt_persoobjets (id_perso,id_objet,durabilite,munitions,temporaire,equipe)
SELECT tlt_perso.id_perso,1,-1,-1,0,1 FROM tlt_perso;

# fin modifs pour gestion des armes a 2 mains


ALTER TABLE tlt_perso ADD id_categorieage INTEGER NULL;

ALTER TABLE tlt_inscriptions ADD id_categorieage INTEGER NULL;

# modif race , sexe

ALTER TABLE tlt_perso ADD id_sexe INTEGER NOT NULL;

ALTER TABLE tlt_inscriptions ADD id_sexe INTEGER NOT NULL;

ALTER TABLE tlt_perso ADD id_race INTEGER NOT NULL;

ALTER TABLE tlt_inscriptions ADD id_race INTEGER NOT NULL;

DROP TABLE IF EXISTS tlt_typeetattemp;

CREATE TABLE tlt_typeetattemp (
  id_typeetattemp INTEGER NOT NULL AUTO_INCREMENT,
  nomtype VARCHAR(50) NOT NULL DEFAULT '',
  PRIMARY KEY  (id_typeetattemp)
) type=myisam;

INSERT into tlt_typeetattemp values (1,'Age') ;

INSERT into tlt_typeetattemp values (2,'Race') ;

INSERT into tlt_typeetattemp values (3,'Sexe') ;

INSERT into tlt_typeetattemp values (4,'Lieu') ;

INSERT into tlt_typeetattemp values (5,'Divers') ;


ALTER TABLE tlt_etattempnom ADD id_typeetattemp  INTEGER UNSIGNED DEFAULT '5' NOT NULL after id_etattemp;

UPDATE tlt_etattempnom SET  id_typeetattemp  = 5;

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (1, 'enfant');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (1, 'jeune adulte');

SELECT @categoriepardefaut:=last_INSERT_id();

INSERT into tlt_persoetattemp (id_perso, id_etattemp, fin)
SELECT  id_perso,@categoriepardefaut ,-1
FROM tlt_perso
WHERE id_categorieage is NULL;

UPDATE tlt_perso SET id_categorieage= @categoriepardefaut WHERE id_categorieage is NULL;

UPDATE tlt_inscriptions SET id_categorieage= @categoriepardefaut WHERE id_categorieage is NULL;


ALTER TABLE tlt_perso CHANGE id_categorieage id_categorieage INTEGER NOT NULL;

ALTER TABLE tlt_inscriptions CHANGE id_categorieage id_categorieage INTEGER NOT NULL;

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (1, 'adulte expérimenté');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (1, 'viellard');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (2, 'humain');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (2, 'elfe');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (2, 'nain');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (2, 'troll');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (2, 'gobelin');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (2, 'orc');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (2, 'demi-elfe');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (3, 'male');

INSERT into tlt_etattempnom ( id_typeetattemp, nom ) values (3, 'femelle');


ALTER TABLE tlt_etattempnom ADD INDEX ( id_typeetattemp ) ;

# migration des races existantes

INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
SELECT  distinct tlt_typeetattemp.id_typeetattemp ,race, 1
FROM (tlt_perso LEFT JOIN tlt_etattempnom on tlt_perso.race = tlt_etattempnom.nom) , tlt_typeetattemp
WHERE nomtype='Race'
and tlt_perso.race is NOT NULL
and tlt_perso.race <>''
and tlt_etattempnom.nom is NULL;

INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
SELECT  distinct tlt_typeetattemp.id_typeetattemp ,race, 1
FROM (tlt_inscriptions  LEFT JOIN tlt_etattempnom on tlt_inscriptions.race = tlt_etattempnom.nom) , tlt_typeetattemp
WHERE nomtype='Race'
and tlt_inscriptions.race is NOT NULL
and tlt_inscriptions.race <>''
and tlt_etattempnom.nom is NULL;


# migration des races des pjs

INSERT into tlt_persoetattemp (id_perso, id_etattemp, fin)
SELECT  id_perso,id_etattemp ,-1
FROM tlt_perso,tlt_etattempnom  , tlt_typeetattemp
WHERE nomtype='Race'
and tlt_perso.race = tlt_etattempnom.nom
and tlt_perso.race is NOT NULL
and tlt_perso.race <>''
and tlt_etattempnom.id_typeetattemp = tlt_typeetattemp.id_typeetattemp;

UPDATE tlt_perso, tlt_etattempnom, tlt_typeetattemp SET id_race =  tlt_etattempnom.id_etattemp
WHERE tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Race' and tlt_etattempnom.nom=tlt_perso.race;

# migration des sexes existants

UPDATE tlt_perso SET sexe =-1 WHERE sexe=0;

UPDATE tlt_inscriptions SET sexe =-1 WHERE sexe=0;

INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
SELECT  distinct tlt_typeetattemp.id_typeetattemp ,sexe, 1
FROM (tlt_perso LEFT JOIN tlt_etattempnom on tlt_perso.sexe = tlt_etattempnom.nom) , tlt_typeetattemp
WHERE nomtype='Sexe'
and tlt_perso.sexe is NOT NULL
and tlt_etattempnom.nom is NULL;

INSERT into tlt_etattempnom (id_typeetattemp, nom, visible)
SELECT  distinct tlt_typeetattemp.id_typeetattemp ,sexe, 1
FROM (tlt_inscriptions LEFT JOIN tlt_etattempnom on tlt_inscriptions.sexe = tlt_etattempnom.nom) , tlt_typeetattemp
WHERE nomtype='Sexe'
and tlt_inscriptions.sexe is NOT NULL
and tlt_etattempnom.nom is NULL;

UPDATE tlt_etattempnom SET nom = '0' WHERE nom='-1';

UPDATE tlt_perso SET sexe = '0' WHERE sexe='-1';

UPDATE tlt_inscriptions SET sexe = '0' WHERE sexe='-1';

# migration des sexes des pjs
INSERT into tlt_persoetattemp (id_perso, id_etattemp, fin)
SELECT  id_perso,id_etattemp ,-1
FROM tlt_perso,tlt_etattempnom  , tlt_typeetattemp
WHERE nomtype='Sexe'
and tlt_perso.sexe = tlt_etattempnom.nom
and tlt_etattempnom.id_typeetattemp = tlt_typeetattemp.id_typeetattemp;

UPDATE tlt_perso, tlt_etattempnom, tlt_typeetattemp SET id_sexe =  tlt_etattempnom.id_etattemp 
WHERE tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Sexe' and tlt_etattempnom.nom=tlt_perso.sexe;


#migration des inscriptions a faire


UPDATE tlt_inscriptions, tlt_etattempnom, tlt_typeetattemp SET id_race =  tlt_etattempnom.id_etattemp
WHERE tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Race' and tlt_etattempnom.nom=tlt_inscriptions.race;


UPDATE tlt_inscriptions, tlt_etattempnom, tlt_typeetattemp SET id_sexe =  tlt_etattempnom.id_etattemp 
WHERE tlt_etattempnom.id_typeetattemp=tlt_typeetattemp.id_typeetattemp
and nomtype='Sexe' and tlt_etattempnom.nom=tlt_inscriptions.sexe;

ALTER TABLE tlt_perso DROP race;

ALTER TABLE tlt_perso DROP sexe;

ALTER TABLE tlt_inscriptions    DROP sexe;

ALTER TABLE tlt_inscriptions    DROP race;

INSERT into  tlt_persoetattemp (id_perso ,id_etattemp , fin)
SELECT  tlt_perso.id_perso, id_sexe, '-1'
FROM tlt_perso LEFT JOIN tlt_persoetattemp on 
 tlt_perso.id_sexe =  tlt_persoetattemp.id_etattemp and  tlt_perso.id_perso = tlt_persoetattemp.id_perso
WHERE id_etattemp is NULL;

INSERT into  tlt_persoetattemp (id_perso ,id_etattemp , fin)
SELECT  tlt_perso.id_perso, id_race, '-1'
FROM tlt_perso LEFT JOIN tlt_persoetattemp on 
 tlt_perso.id_race =  tlt_persoetattemp.id_etattemp and  tlt_perso.id_perso = tlt_persoetattemp.id_perso
WHERE id_etattemp is NULL;

INSERT into  tlt_persoetattemp (id_perso ,id_etattemp , fin)
SELECT  tlt_perso.id_perso, id_categorieage, '-1'
FROM tlt_perso LEFT JOIN tlt_persoetattemp on 
 tlt_perso.id_categorieage=  tlt_persoetattemp.id_etattemp and  tlt_perso.id_perso = tlt_persoetattemp.id_perso
WHERE id_etattemp is NULL;

#fin modif race, sexe . il reste a admin a renommer les etats temporaires du sexe avec l'interface
# dans la version de base, 0 doit etre male, 1 doit etre femelle mais certains admin ont pu le modifier ou en ajouter



#ajout de se cacher
ALTER TABLE tlt_lieu ADD difficultedesecacher TINYINT DEFAULT 0 NOT NULL;

UPDATE tlt_lieu SET difficultedesecacher=0 WHERE difficultedesecacher is NULL;

ALTER TABLE tlt_perso ADD dissimule TINYINT(3) UNSIGNED NOT NULL DEFAULT 0;

# mise a jour des droits pour se cacher 
UPDATE tlt_lieu SET flags = 
rpad(concat(LEFT(flags,7),'1'), length(flags),'0') WHERE trigramme <>'spe';


# TABLEs de news
DROP TABLE IF EXISTS tlt_n_commentaires;

CREATE TABLE if NOT EXISTS tlt_n_commentaires (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  news INT(10) UNSIGNED NOT NULL DEFAULT '0',
  news_date INT(16) UNSIGNED NOT NULL DEFAULT '0',
  auteur VARCHAR(25) NOT NULL DEFAULT '',
  texte text NOT NULL,
  KEY id (id)
) type=myisam;

DROP TABLE IF EXISTS tlt_n_config;

CREATE TABLE if NOT EXISTS tlt_n_config (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(25) NOT NULL DEFAULT 'bienvenue sur talesta-new',
  nbre_news INT(2) UNSIGNED NOT NULL DEFAULT '5',
  nom_archive VARCHAR(25) NOT NULL DEFAULT 'voir les archives',
  nom_proposer VARCHAR(25) NOT NULL DEFAULT 'proposer une news',
  nom_commentaires VARCHAR(25) NOT NULL DEFAULT 'commentaires(#)',
  nom_INDEX VARCHAR(25) NOT NULL DEFAULT 'INDEX',
  PRIMARY KEY (id)
) type=myisam;

	
DROP TABLE IF EXISTS tlt_n_news;

CREATE TABLE if NOT EXISTS tlt_n_news (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  news_date INT(16) UNSIGNED NOT NULL DEFAULT '0',
  titre VARCHAR(25) NOT NULL DEFAULT '',
  auteur VARCHAR(25) NOT NULL DEFAULT '',
  texte text NOT NULL,
  PRIMARY KEY id (id)
) type=myisam;


--
-- contenu de la TABLE tlt_n_news
--

INSERT into tlt_n_news ( news_date, titre, auteur, texte) values (1109590009, 'version de février 2005', 'hixcks', 'première version de la communauté:<ul type="square"><li> corrections de plusieurs bugs de la version novembre2004 trouvés sur le forum. </li><li> une doc d''install grace à kaeru. </li><li> les menus admin/joueur sont la fusion de ceux du duo chub/tidou et de luka. </li><li> le systeme de news (adapté de chub) </li><li> plusieurs évols/modifs fournies sur le forum:</li><ul type="square"> <li>soin via objet (uriel) </li><li> listechemin (lapin) </li><li> différents patches de kaeru (les connectés , l''intégration du forum au style talesta, plusieurs niveaux de maintenance).</li> <li> qcm optionnel avant inscription (chub, saikoh).</li></ul><ul type="square"> plusieurs évols/modifs proposées sur le forum.         <li> suppression des balises html dans les saisies (parler ...) </li>		 <li> objets maudits (ne pouvant etre enleves une fois équipés) </li>		 <li> se cacher</li><li> reveler des objets, persos, chemins caches</li>		 <li> abandonner objet</li>		 <li> integration de l''image de l''avatar du forum dans le jeu</li>		 <li> voler des po à un cadavre => réussite auto</li>		 <li> mail au format html</li>		 <li> mots de passe des pj cryptes en base</li></ul><li>autres.</li><ul type="square"> <li> gestion des états temporaires pour la race, l''age et le sexe (cf. doc d''exemple fournie)</li><li> limitations d''utilisation des objets et sorts aux pjs ayant un état temporaire, ce qui permet de créer des sorts et armes spécifiques aux elfes, ou aux mages...</li><li> possibilité de fermer les inscriptions.</li></ul><li> j''en oublie surement.... pour plus d''infos <a href=''http://vknab.free.fr/phpbb2/''>forum talesta4</a> </li></ul>');

--
-- contenu de la TABLE tlt_n_config
--

INSERT into tlt_n_config (id, title, nbre_news, nom_archive, nom_proposer, nom_commentaires, nom_INDEX) values (1, 'bienvenue sur talesta-new', 5, 'voir les archives', 'proposer une news', 'commentaires(#)', 'INDEX');

# fin des TABLEs de news

# ajout de la colonne nom pour eviter de faire des JOINtures sans cesse pour l'affichage de ce que l'on a trouve

ALTER TABLE tlt_entitecachee ADD nom VARCHAR(64) NOT NULL after id_entite ;

UPDATE  tlt_entitecachee, tlt_chemins, tlt_lieu SET tlt_entitecachee.nom = concat('chemin vers ', tlt_lieu.nom) 
WHERE tlt_entitecachee.id_entite = tlt_chemins.id_clef and
tlt_lieu.id_lieu = tlt_chemins.id_lieu_1
and tlt_chemins.id_lieu_2 = tlt_entitecachee.id_lieu and
tlt_entitecachee.type = 0;

UPDATE  tlt_entitecachee, tlt_chemins, tlt_lieu SET tlt_entitecachee.nom = concat('chemin vers ', tlt_lieu.nom) 
WHERE tlt_entitecachee.id_entite = tlt_chemins.id_clef and
tlt_lieu.id_lieu = tlt_chemins.id_lieu_2
and tlt_chemins.id_lieu_1 = tlt_entitecachee.id_lieu and
tlt_entitecachee.type = 0;

UPDATE  tlt_entitecachee, tlt_objets SET tlt_entitecachee.nom = tlt_objets.nom 
WHERE tlt_entitecachee.id_entite = tlt_objets.id_objet and
tlt_entitecachee.type = 1;

UPDATE  tlt_entitecachee, tlt_perso SET tlt_entitecachee.nom = tlt_perso.nom 
WHERE tlt_entitecachee.id_entite = tlt_perso.id_perso and
tlt_entitecachee.type = 2;

# fin ajout de la colonne nom pour eviter de faire des JOINtures sans cesse pour l'affichage de ce que l'on a trouve

# ajout de la TABLE questionnaire

#
# TABLE structure for TABLE 'tlt_qcm'
#

DROP TABLE IF EXISTS tlt_qcm;

CREATE TABLE tlt_qcm (
  id_question INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  question VARCHAR(128) NOT NULL,
  reponse1 VARCHAR(128) NOT NULL,
  reponse2 VARCHAR(128) NOT NULL,
  reponse3 VARCHAR(128) NOT NULL,
  reponse4 VARCHAR(128) NOT NULL,
  bonne TINYINT(3) UNSIGNED NOT NULL,
  PRIMARY KEY  (id_question)
) type=myisam ;



#INSERT into tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) values ( 'n&#39;en avez vous pas assez de la guéguerre kaeru/chub ?', 'oui', 'non', 'ah bon&#44; ils ne s&#39;aiment pas ?', 'on peut participer ?', 1);

#INSERT into tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) values ( 'pourquoi la géguerre kaeru/chub est-elle dommage ?', 'parce qu&#39;on peut pas y participer', 'parce qu&#39;on voit pas les dégats faits', 'parce qu&#39;il faut effacer leur messages', 'parce qu&#39;ils sont parmi les plus actifs du forum', 4);

#INSERT into tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) values ( 'comment régler cette guéguerre kaeru/chub ?', 'on les vire', 'on acheve le survivant', 'on les enferme ensemble jusqu&#39;a ce qu&#39;ils s&#39;apprecient', 'on fait rien et on attend qu&#39;ils se calment', 3);

INSERT into tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) values ( 'puis avoir deux comptes de jeu ?', 'oui n''importe quand', 'oui si je demande', 'oui mais il faut pas se faire capter', 'non', 4);

INSERT into tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) values ( 'ai-je le droit d&#39;insulter tout le monde ?', 'seulement les orcs', 'oui si je demande', 'oui mais il faut pas se faire capter', 'non', 4);

UPDATE tlt_mj SET flags='11111111111111111111111111111111111111111111111111111111111111111111111111111111'
WHERE id_mj=1;

# fin ajout de la TABLE questionnaire

ALTER TABLE tlt_perso ADD background blob NULL;

ALTER TABLE tlt_inscriptions ADD background blob NULL;

UPDATE tlt_inscriptions SET background  = '.' WHERE background is NULL;

UPDATE tlt_perso SET background  = '.' WHERE background is NULL;

ALTER TABLE tlt_perso CHANGE background background blob NOT NULL;

ALTER TABLE tlt_inscriptions CHANGE background background blob NOT NULL;

ALTER TABLE tlt_objets ADD id_etattempspecifique INTEGER 
REFERENCES tlt_etattempnom ( id_etattemp)  ON DELETE SET NULL;

ALTER TABLE tlt_magie ADD id_etattempspecifique INTEGER 
REFERENCES tlt_etattempnom ( id_etattemp)  ON DELETE SET NULL;

ALTER TABLE tlt_etattemp ADD INDEX ( id_etattemp );

ALTER TABLE tlt_archive ADD INDEX (id_perso);

ALTER TABLE tlt_zone ADD INDEX (id_lieu);

ALTER TABLE tlt_typeetattemp ADD UNIQUE INDEX (nomtype);

ALTER TABLE tlt_magie ADD typecible SMALLINT DEFAULT 1 NOT NULL;

ALTER TABLE tlt_magie ADD sortdistant SMALLINT DEFAULT 0 NOT NULL;

#ALTER TABLE tlt_magie ADD pnjinvoque  INT(10) UNSIGNED NULL;

#ALTER TABLE tlt_magie ADD degatspi_min TINYINT(4) NOT NULL DEFAULT '0';
#ALTER TABLE tlt_magie ADD degatspi_max TINYINT(4) NOT NULL DEFAULT '0';
#ALTER TABLE tlt_magie ADD degatspa_min TINYINT(4) NOT NULL DEFAULT '0';
#ALTER TABLE tlt_magie ADD degatspa_max TINYINT(4) NOT NULL DEFAULT '0';


alter table tlt_perso add wantmusic  SMALLINT default 0 not null;

alter table tlt_lieu add cheminfichieraudio varchar(50) null;

#modif suite erreur remontée par Uriel sortprefere etant id_magie de tlt_magie, il doit etre du meme type
ALTER TABLE tlt_perso CHANGE sortprefere sortprefere INT( 11 ) DEFAULT NULL;


# engagement de Tidou 
ALTER TABLE tlt_perso ADD engagement TINYINT DEFAULT '0' NOT NULL;



#
# TABLE structure for TABLE 'tlt_engagement'
#

DROP TABLE IF EXISTS tlt_engagement;

CREATE TABLE tlt_engagement (
  id_perso INTEGER NOT NULL DEFAULT '0',
  id_adversaire INTEGER NOT NULL DEFAULT '0',
  nom	VARCHAR( 25 ) NOT NULL,
  propdes TINYINT DEFAULT '0' NOT NULL,
  PRIMARY KEY  (id_perso, id_adversaire)
) type=myisam;


# mise a jour des droits pour parler
UPDATE tlt_lieu SET flags = 
rpad(concat(LEFT(flags,11),'1'), length(flags),'0');

#ajout de combiner objet deKyuJack
ALTER TABLE tlt_objets ADD composantes VARCHAR( 100 );

ALTER TABLE tlt_zone ADD stockmax SMALLINT DEFAULT '-1' NOT NULL ,
ADD quantite SMALLINT DEFAULT '-1' NOT NULL ,
ADD remisestock SMALLINT DEFAULT '-1' NOT NULL,
ADD derniereremise  INT(35) NOT NULL DEFAULT '0';


ALTER TABLE tlt_mj ADD wantmusic  SMALLINT default 0 not null;

ALTER TABLE tlt_objets CHANGE poids poids FLOAT UNSIGNED DEFAULT '0' NOT NULL;

alter TABLE tlt_perso add commentaires_mj TEXT  null;

ALTER TABLE tlt_mj ADD dispo_pour_ppa TINYINT DEFAULT '1' not null;

ALTER TABLE tlt_etattempnom ADD utilisableinscription TINYINT(3) UNSIGNED NOT NULL DEFAULT '1';
 
ALTER TABLE tlt_typeetattemp ADD critereinscription TINYINT NOT NULL default '0';

ALTER TABLE tlt_typeetattemp ADD modifiableparpj tinyint NOT NULL default '0';

update tlt_typeetattemp set critereinscription = 2 where nomtype = 'Age' or nomtype = 'Race' or nomtype = 'Sexe';

 commit;
 
 update tlt_etattempnom,tlt_typeetattemp set visible = 1 where tlt_typeetattemp.id_typeetattemp = tlt_etattempnom.id_typeetattemp and nomtype in ('Age','Race','Sexe');

 commit;
 
INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ( 'Taille', '1','0');

INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ('Corpulence', '1','1');


INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ('Humeur', '1','1');

commit; 
 
 

#
# TABLE structure for TABLE 'tlt_inscriptetattemp'
#

DROP TABLE IF EXISTS tlt_inscriptetattemp;

CREATE TABLE tlt_inscriptetattemp (
  id_clef INTEGER NOT NULL AUTO_INCREMENT,
  id_inscript INTEGER NOT NULL DEFAULT '0' REFERENCES tlt_inscriptions (id) ON DELETE cascade,
  id_etattemp INTEGER NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_clef)
) type=myisam;



insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_categorieage from tlt_inscriptions;

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_sexe from tlt_inscriptions;

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_race from tlt_inscriptions;

ALTER TABLE tlt_perso
  DROP id_categorieage,
  DROP id_sexe,
  DROP id_race;
  
  
ALTER TABLE tlt_inscriptions
  DROP id_categorieage,
  DROP id_sexe,
  DROP id_race;


alter table tlt_magie add composantes VARCHAR(100) NULL;


# mise a jour des droits pour se recevoir des sorts exterieurs
UPDATE tlt_lieu SET flags = 
rpad(concat(LEFT(flags,12),'1'), length(flags),'0') WHERE trigramme <>'spe';



ALTER TABLE tlt_perso ADD role_mj INTEGER;

#mise a jour des droits pour admin. Pourquoi avait-il encore des 0....
update tlt_mj set flags =
 rpad('1',length(flags),'1') where id_mj = 1;
 
# debut bug sur les objets caches 
alter table tlt_persoobjets CHANGE id_perso id_perso  INTEGER null;

DROP TABLE IF EXISTS tlt_migbugobjetscaches;

CREATE TABLE tlt_migbugobjetscaches (
  id_entite INTEGER NOT NULL AUTO_INCREMENT,
  id INTEGER NOT NULL,
  id_entiteOLD INTEGER NOT NULL,
  durabilite INTEGER NOT NULL DEFAULT '-1',
  munitions INTEGER NOT NULL DEFAULT '-1',
  PRIMARY KEY  (id_entite)
) type=myisam;


insert into tlt_migbugobjetscaches (id,id_entiteOLD,durabilite,munitions)
select id,id_entite as id_entiteOLD,durabilite , munitions from tlt_entitecachee, tlt_objets
where id_entite = id_objet and tlt_entitecachee.type=1;

SELECT @premierInsertBug:=max(id_clef) from tlt_persoobjets;

insert into tlt_persoobjets (id_perso,id_objet, durabilite, munitions,  temporaire ,  equipe   )
select null,id_entiteOLD,durabilite , munitions,0,0  from tlt_migbugobjetscaches
order by id;

update tlt_migbugobjetscaches set id_entite = id_entite+ @premierInsertBug;

update tlt_entitecachee,tlt_migbugobjetscaches set tlt_entitecachee.id_entite = tlt_migbugobjetscaches.id_entite where tlt_entitecachee.id = tlt_migbugobjetscaches.id;

DROP TABLE IF EXISTS tlt_migbugobjetscaches;

# fin bug sur les objets caches 


ALTER TABLE tlt_lieu ADD id_etattempspecifique INTEGER 
REFERENCES tlt_etattempnom ( id_etattemp)  ON DELETE SET NULL;


#quetes

DROP TABLE IF EXISTS tlt_quetes;

CREATE TABLE tlt_quetes (
  id_quete INTEGER NOT NULL AUTO_INCREMENT,
  nom_quete  VARCHAR(50) NOT NULL,
  type_quete INTEGER NOT NULL,
  detail_type_quete INTEGER NOT NULL,
  duree_quete INTEGER NOT NULL DEFAULT '-1',
  public INTEGER NOT NULL DEFAULT '0',
  cyclique INTEGER NOT NULL DEFAULT '0',
  proposepar INTEGER NOT NULL,
  proposepartype TINYINT NOT NULL default '1',	
  texteproposition text not null,
  textereussite text not null,
  texteechec text not null,
  refuspossible INTEGER NOT NULL DEFAULT '0',
  abandonpossible INTEGER NOT NULL DEFAULT '0',
  validationquete INTEGER NOT NULL DEFAULT '0',
  id_lieu INTEGER NULL,
  proposant_anonyme TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY  (id_quete)
) type=myisam;

DROP TABLE IF EXISTS tlt_recompensequete;

CREATE TABLE tlt_recompensequete (
  id_recompensequete	INTEGER NOT NULL AUTO_INCREMENT,
  id_quete INTEGER NOT NULL,
  type_recompense INTEGER NOT NULL,
  recompense INTEGER NOT NULL,
  PRIMARY KEY  (id_recompensequete)
) type=myisam;

DROP TABLE IF EXISTS tlt_persoquete;

CREATE TABLE tlt_persoquete (
  id_persoquete	INTEGER NOT NULL AUTO_INCREMENT,
  id_quete INTEGER NOT NULL,
  id_perso INTEGER NOT NULL,	
  etat INTEGER NOT NULL,	
  debut  INT(11) NOT NULL DEFAULT '0',
  fin  INT(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY  (id_persoquete)
) type=myisam;

update tlt_objets set type='Nourriture' where type='Divers' and sous_type='Nourriture';

#bestiaires n'ont pas de lieu
alter table tlt_perso CHANGE id_lieu id_lieu  INTEGER null;

# modif du site du forum
update tlt_n_news set texte= replace (texte, 'http://vknab.free.fr/phpbb2/','http://www.talesta.free.fr/puntal');

alter table tlt_lieu add type_lieu_apparition tinyint not null default '1';

DROP TABLE IF EXISTS tlt_apparitionmonstre;

CREATE TABLE tlt_apparitionmonstre (
  id_apparitionmonstre	INTEGER NOT NULL AUTO_INCREMENT,
  id_typelieu INTEGER NOT NULL,
  id_perso INTEGER NOT NULL,	
  nb_max_apparition SMALLINT NOT NULL  DEFAULT 1,
  nb_max_lieu SMALLINT NOT NULL  DEFAULT -1,
  chance_apparition SMALLINT NOT NULL,
  PRIMARY KEY  (id_apparitionmonstre)
) type=myisam;



alter table tlt_lieu add apparition_monstre tinyint not null default '0';

DROP TABLE IF EXISTS tlt_ppa;
 
create table tlt_ppa (
  id_ppa INTEGER NOT NULL AUTO_INCREMENT,
  id_perso INTEGER NOT NULL,	
  id_mj INTEGER NOT NULL,	    
  date_ppa INT(11) NOT NULL,
  detail_ppa TEXT NOT NULL,
  qte_pa tinyint not null default '0',
  qte_pi tinyint not null default '0',  
  PRIMARY KEY  (id_ppa)
) type=myisam;


#alter table tlt_perso add img_avatar varchar(100) null;

ALTER TABLE tlt_perso drop engagement;

ALTER TABLE tlt_apparitionmonstre ADD INDEX (id_perso);

ALTER TABLE tlt_apparitionmonstre ADD INDEX (id_typelieu );

ALTER TABLE tlt_inscriptetattemp ADD INDEX (id_inscript) ;

ALTER TABLE tlt_lieu ADD INDEX (type_lieu_apparition);

ALTER TABLE tlt_magie ADD INDEX (type); 

ALTER TABLE tlt_magie ADD INDEX (sous_type); 

ALTER TABLE tlt_mj ADD INDEX (dispo_pour_ppa);

ALTER TABLE tlt_n_commentaires ADD INDEX (news);

ALTER TABLE tlt_objets ADD INDEX (type);

ALTER TABLE tlt_objets ADD INDEX (sous_type);

ALTER TABLE tlt_perso ADD INDEX (pnj);

ALTER TABLE tlt_perso ADD INDEX (id_lieu);

ALTER TABLE tlt_persoquete ADD INDEX (etat);

ALTER TABLE tlt_persoquete ADD INDEX (id_perso);

ALTER TABLE tlt_ppa ADD INDEX (id_mj);

ALTER TABLE tlt_recompensequete ADD INDEX (id_quete) ;

ALTER TABLE tlt_recompensequete ADD INDEX (type_recompense);

ALTER TABLE tlt_quetes ADD INDEX (nom_quete);

ALTER TABLE tlt_quetes ADD INDEX (type_quete);

ALTER TABLE tlt_quetes ADD INDEX (public);

ALTER TABLE tlt_quetes ADD INDEX (proposepar);

ALTER TABLE tlt_perso add pourcentage_reaction tinyint DEFAULT 100 not null;

ALTER table tlt_etattempnom add id_lieudepart integer null REFERENCES tlt_lieu (id_lieu) ON DELETE set NULL;

ALTER table tlt_etattempnom add objetsfournis varchar(50) null;

ALTER table tlt_etattempnom add sortsfournis varchar(50) null;

ALTER TABLE tlt_perso ADD nb_deces INT( 11 ) DEFAULT '0' NOT NULL;

ALTER TABLE tlt_quetes ADD id_etattempspecifique INT NULL;

ALTER TABLE tlt_magie ADD coutpa TINYINT NULL;

ALTER TABLE tlt_magie ADD coutpi TINYINT NULL;

ALTER TABLE tlt_magie ADD coutpo TINYINT DEFAULT 0 NOT NULL;

ALTER TABLE tlt_magie ADD coutpv TINYINT DEFAULT 0 NOT NULL;

ALTER TABLE tlt_perso ADD moment_mort  INT(35) NULL;

update tlt_perso set moment_mort = 0  where pv <0 and pnj <> 2;

commit;


DROP TABLE IF EXISTS tlt_traceactions;

CREATE TABLE tlt_traceactions(
id_trace INTEGER NOT NULL AUTO_INCREMENT ,
action VARCHAR( 30 ) NOT NULL ,
id_acteur integer NOT NULL ,
id_lieu  integer NOT NULL ,
detail varchar( 100 ) NOT NULL ,
heure_action integer NOT NULL ,
PRIMARY KEY ( id_trace ) 
) TYPE = MYISAM ;


ALTER TABLE `tlt_lieu` CHANGE `accessible` `accessible_telp` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '1';

