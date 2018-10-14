
--  POSTGRESQL VER8.0.0
-- 
--  HOST: LOCALHOST   DATABASE: TALESTA4
--  --------------------------------------------------------
--  Script migré de celui de MYSQL.
--  donc sans mention de tablespace et de notion de stockage
--  ou de droits et d'utilisateurs
--Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  
--
--$RCSfile: Talesta4.postgres.sql,v $
--$Revision: 1.12 $
--$Date: 2010/01/24 19:33:34 $

CREATE OR REPLACE FUNCTION concat(varchar, varchar)
RETURNS varchar AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN  RETURN v_1 || v_2;END;' LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION concat(int2,varchar)
RETURNS varchar AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN  RETURN CAST (v_1 as varchar)|| v_2;END;' LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION concat(int4,varchar)
RETURNS varchar AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN  RETURN CAST (v_1 as varchar)|| v_2;END;' LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION concat(varchar,int4)
RETURNS varchar AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN  RETURN v_1 || CAST (v_2 as varchar);END;' LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION concat(varchar,int2)
RETURNS varchar AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN  RETURN v_1 || CAST (v_2 as varchar);END;' LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION concat(real,varchar)
RETURNS varchar AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN  RETURN CAST (v_1 as varchar)|| v_2;END;' LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION concat(varchar,real)
RETURNS varchar AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN  RETURN v_1 || CAST (v_2 as varchar);END;' LANGUAGE plpgsql;

-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_lieu'
-- 

CREATE SEQUENCE seq_tlt_lieu;

CREATE TABLE tlt_lieu (
  ID_LIEU INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_lieu'),
  NOM VARCHAR(50) NOT NULL ,
  FLAGS TEXT NOT NULL,
  TRIGRAMME CHAR(3) NOT NULL ,
  accessible SMALLINT  NOT NULL DEFAULT 1,
  ID_FORUM INTEGER  NOT NULL DEFAULT 0,
  PROVOQUEETAT VARCHAR(100) NULL 
) ;

-- 
--  DUMPING DATA FOR TABLE 'tlt_lieu'
-- 

INSERT INTO tlt_lieu (NOM, FLAGS, TRIGRAMME, accessible, ID_FORUM) 
VALUES('LIEU DE DEPART', '00000000000000000000000000000', 'SPE', 0, 0);



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_chemins'
-- 

CREATE SEQUENCE seq_tlt_chemins;


CREATE TABLE tlt_chemins (
  ID_CLEF INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_chemins'),
  ID_LIEU_1 INTEGER NOT NULL REFERENCES tlt_lieu (ID_LIEU) ON DELETE CASCADE,
  ID_LIEU_2 INTEGER NOT NULL REFERENCES tlt_lieu (ID_LIEU) ON DELETE CASCADE,
  TYPE SMALLINT NOT NULL DEFAULT 0,
  DIFFICULTE SMALLINT NULL DEFAULT 0,
  PASS VARCHAR(50) NULL,
  DISTANCE SMALLINT NULL DEFAULT 0
) ;

-- 
--  DUMPING DATA FOR TABLE 'tlt_chemins'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_inscriptions'
-- 

CREATE SEQUENCE seq_tlt_inscriptions;

CREATE TABLE tlt_inscriptions (
  ID INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_inscriptions'),
  NOM VARCHAR(50) NOT NULL ,
  PASS VARCHAR(50) NOT NULL ,
  EMAIL VARCHAR(80) NOT NULL ,
  RACE VARCHAR(50) NOT NULL ,
  SEXE SMALLINT  NOT NULL DEFAULT 0,
  DESCRIPTION TEXT NOT NULL
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_inscriptions'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_mj'
-- 
CREATE SEQUENCE seq_tlt_mj;


CREATE TABLE tlt_mj (
  ID_MJ INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_mj'),
  NOM VARCHAR(250) NOT NULL ,
  PASS VARCHAR(50) NOT NULL ,
  TITRE VARCHAR(25) NOT NULL ,
  FLAGS VARCHAR(80) NULL,
  EMAIL VARCHAR(80) NOT NULL ,
  LASTACTION INTEGER  NOT NULL DEFAULT 0,
  FANONLU SMALLINT  DEFAULT 0,
  WANTMAIL SMALLINT  NOT NULL DEFAULT 0
) ;



-- 
--  DUMPING DATA FOR TABLE 'tlt_mj'
-- 

INSERT INTO tlt_mj (nom, pass, titre, flags, email, lastaction, fanonlu, wantmail)
 VALUES('admin', 'votremotdepasse', 'MJ supreme', '111111111111111111111111111111111111111111111111', 'votreemail', 1051302306,1,0);


-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_magie'
-- 

CREATE SEQUENCE seq_tlt_magie;

CREATE TABLE tlt_magie (
  ID_MAGIE INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_magie'),
  TYPE VARCHAR(50) NOT NULL ,
  SOUS_TYPE VARCHAR(50) NOT NULL ,
  NOM VARCHAR(64) NOT NULL ,
  DEGATS_MIN SMALLINT NOT NULL DEFAULT 0,
  DEGATS_MAX SMALLINT NOT NULL DEFAULT 0,
  ANONYME SMALLINT  NOT NULL DEFAULT 0,
  PRIX_BASE INTEGER NOT NULL DEFAULT 0,
  DESCRIPTION VARCHAR(255) NOT NULL,
  IMAGE VARCHAR(50) NULL ,
  PERMANENT SMALLINT NOT NULL DEFAULT 0,
  PLACE SMALLINT NOT NULL DEFAULT 0,
  CHARGES SMALLINT NOT NULL DEFAULT 0,
  CARACTERISTIQUE VARCHAR(50) NOT NULL ,
  COMPETENCE VARCHAR(50) NOT NULL ,
  PROVOQUEETAT VARCHAR(100) NULL 
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_magie'
-- 

INSERT INTO tlt_magie (TYPE, SOUS_TYPE, NOM, DEGATS_MIN, DEGATS_MAX, ANONYME, PRIX_BASE, DESCRIPTION, PERMANENT, PLACE, CHARGES, CARACTERISTIQUE, COMPETENCE) 
VALUES('Air', 'Soin', 'SORT DEPART - A EDITER', 1, 2, 0, 32, 'SORT DE SOINS MINIMES',  1, 1, -1, 'Intelligence', 'Air');


-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_objets'
-- 
CREATE SEQUENCE seq_tlt_objets;


CREATE TABLE tlt_objets (
  ID_OBJET INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_objets'),
  TYPE VARCHAR(50) NOT NULL ,
  SOUS_TYPE VARCHAR(50) NOT NULL ,
  NOM VARCHAR(64) NOT NULL ,
  DEGATS_MIN INTEGER NULL DEFAULT 0,
  DEGATS_MAX INTEGER NULL DEFAULT 0,
  ANONYME SMALLINT  NOT NULL DEFAULT 0,
  DURABILITE INTEGER NOT NULL DEFAULT -1,
  PRIX_BASE INTEGER NOT NULL DEFAULT 0,
  DESCRIPTION VARCHAR(255) NOT NULL ,
  POIDS INTEGER NOT NULL DEFAULT 0,
  IMAGE VARCHAR(50) NULL ,
  PERMANENT SMALLINT NOT NULL DEFAULT 0,
  MUNITIONS INTEGER NOT NULL DEFAULT -1,
  CARACTERISTIQUE VARCHAR(50) NULL ,
  COMPETENCE VARCHAR(50) NULL ,
  PROVOQUEETAT VARCHAR(100) NULL ,
  COMPETENCESPE VARCHAR(50) NULL 
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_objets'
-- 

INSERT INTO tlt_objets ( TYPE, SOUS_TYPE, NOM, DEGATS_MIN, DEGATS_MAX, ANONYME, DURABILITE, PRIX_BASE, DESCRIPTION, POIDS, PERMANENT, MUNITIONS, CARACTERISTIQUE, COMPETENCE) 
VALUES('ArmeMelee', 'Arts Martiaux', 'OBJET DE DEPART - A EDITER', 1, 2, 0, -1, 0, 'UN COUP DE POING', 0,  1, -1, 'Force', 'Arts Martiaux');


-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_perso'
-- 
CREATE SEQUENCE seq_tlt_perso;

CREATE TABLE tlt_perso (
  id_perso INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_perso'),
  NOM VARCHAR(50) NOT NULL ,
  PASS VARCHAR(50) NOT NULL ,
  RACE VARCHAR(50) NOT NULL ,
  SEXE SMALLINT NOT NULL DEFAULT 0,
  PA INTEGER NOT NULL DEFAULT 0,
  PV INTEGER NOT NULL DEFAULT 0,
  PO INTEGER NOT NULL DEFAULT 0,
  BANQUE INTEGER  NOT NULL DEFAULT 0,
  ID_LIEU INTEGER NOT NULL REFERENCES tlt_lieu (ID_LIEU) ON DELETE RESTRICT,
  EMAIL VARCHAR(80) DEFAULT NULL,
  INTERVAL_REMISE INTEGER  NOT NULL DEFAULT 72,
  DERNIERE_REMISE INTEGER NOT NULL DEFAULT 0,
  LASTACTION INTEGER NOT NULL DEFAULT 0,
  FANONLU SMALLINT NOT NULL DEFAULT 0,
  WANTMAIL SMALLINT NOT NULL DEFAULT 0
) ;



-- 
--  DUMPING DATA FOR TABLE 'tlt_perso'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_etattempnom'
-- 
CREATE SEQUENCE seq_tlt_etattempnom;

CREATE TABLE tlt_etattempnom (
  ID_ETATTEMP INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_etattempnom'),
  NOM VARCHAR(50) NOT NULL ,
  RPA INTEGER NOT NULL DEFAULT 0,
  RPV INTEGER NOT NULL DEFAULT 0,
  RPO INTEGER NOT NULL DEFAULT 0,
  VISIBLE SMALLINT NOT NULL DEFAULT 0
) ;



-- 
--  DUMPING DATA FOR TABLE 'tlt_etattempnom'
-- 


INSERT INTO tlt_etattempnom (NOM, RPA, RPV, RPO, VISIBLE) VALUES('ETAT NORMAL', 0, 0, 0, 0);



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_comp'
-- 

CREATE SEQUENCE seq_tlt_comp;


CREATE TABLE tlt_comp (
  ID INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_comp'),
  id_perso INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  ID_COMP INTEGER NOT NULL,
  XP INTEGER NOT NULL DEFAULT 0
) ;

-- 
--  DUMPING DATA FOR TABLE 'tlt_comp'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_etattemp'
-- 
CREATE SEQUENCE seq_tlt_etattemp;

CREATE TABLE tlt_etattemp (
  ID_CLEF INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_etattemp'),
  ID_ETATTEMP INTEGER NOT NULL,
  ID_COMP INTEGER NOT NULL,
  BONUS SMALLINT NOT NULL DEFAULT 0
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_etattemp'
-- 


-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_persoETATTEMP'
-- 
CREATE SEQUENCE seq_tlt_persoETATTEMP;

CREATE TABLE tlt_persoETATTEMP (
  ID_CLEF INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_persoETATTEMP'),
  id_perso INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  ID_ETATTEMP INTEGER NOT NULL,
  FIN INTEGER NOT NULL DEFAULT 0
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_persoetattemp'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_persomagie'
-- 
CREATE SEQUENCE seq_tlt_persomagie;

CREATE TABLE tlt_persomagie (
  id_clef INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_persomagie'),
  id_perso INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  id_magie INTEGER NOT NULL REFERENCES tlt_magie (id_magie) ON DELETE CASCADE,
  charges SMALLINT NOT NULL DEFAULT 0
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_persoMAGIE'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_persoOBJETS'
-- 
CREATE SEQUENCE seq_tlt_persoOBJETS;

CREATE TABLE tlt_persoOBJETS (
  ID_CLEF INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_persoOBJETS'),
  id_perso INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  ID_OBJET INTEGER NOT NULL REFERENCES tlt_objets (ID_OBJET) ON DELETE CASCADE,  
  DURABILITE INTEGER NOT NULL DEFAULT -1,
  MUNITIONS INTEGER NOT NULL DEFAULT -1,
  TEMPORAIRE SMALLINT NOT NULL DEFAULT 0,
  EQUIPE SMALLINT NOT NULL DEFAULT 0
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_persoOBJETS'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_persospec'
-- 
CREATE SEQUENCE seq_tlt_persospec;

CREATE TABLE tlt_persospec (
  ID_CLEF INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_persospec'),
  id_perso INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  ID_SPEC INTEGER NOT NULL DEFAULT 0
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_persospec'
-- 



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_sessions'
-- 
CREATE SEQUENCE seq_tlt_sessions;

CREATE TABLE tlt_sessions (
  idsession VARCHAR(100) NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_sessions'),
  ip VARCHAR(25) NOT NULL ,
  datestart INTEGER NOT NULL DEFAULT 0,
  duree INTEGER  NOT NULL DEFAULT 3600,
  permanent SMALLINT NOT NULL DEFAULT 0,
  id_joueur INTEGER NOT NULL DEFAULT 0,
  lastaction INTEGER NOT NULL DEFAULT 0,
  pj SMALLINT  NOT NULL DEFAULT 1
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_sessions'
-- 


-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_specnom'
-- 
CREATE SEQUENCE seq_tlt_specnom;

CREATE TABLE tlt_specnom (
  ID_SPEC INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_specnom'),
  NOM VARCHAR(50) NOT NULL ,
  RPO INTEGER NOT NULL DEFAULT 0,
  RPA INTEGER NOT NULL DEFAULT 0,
  RPV INTEGER NOT NULL DEFAULT 0,
  VISIBLE SMALLINT NOT NULL DEFAULT 0
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_specnom'
-- 

INSERT INTO tlt_specnom (NOM, RPO, RPA, RPV, VISIBLE) 
VALUES('PAS DE SPÉCIALISATION', 0, 0, 0, 0);



-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_spec'
-- 
CREATE SEQUENCE seq_tlt_spec;

CREATE TABLE tlt_spec (
  ID_CLEF INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_spec'),
  ID_SPEC INTEGER NOT NULL DEFAULT 0,
  ID_COMP INTEGER NOT NULL DEFAULT 0,
  BONUS SMALLINT NOT NULL DEFAULT 0
) ;



-- 
--  DUMPING DATA FOR TABLE 'tlt_spec'
-- 


-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_zone'
-- 
CREATE SEQUENCE seq_tlt_zone;

CREATE TABLE tlt_zone (
  ID_ZONE INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_zone'),
  ID_LIEU INTEGER NOT NULL DEFAULT 0 REFERENCES tlt_lieu (ID_LIEU) ON DELETE CASCADE,
  TYPE SMALLINT NOT NULL DEFAULT 0,
  POINTEUR INTEGER NOT NULL DEFAULT 0
) ;


-- 
--  DUMPING DATA FOR TABLE 'tlt_zone'
-- 




--  DEBUT MODIF HIXCKS
ALTER TABLE tlt_perso    ADD PNJ SMALLINT DEFAULT 0 NOT NULL;
ALTER TABLE tlt_perso    ADD RELATION SMALLINT DEFAULT 2 NOT NULL;
ALTER TABLE tlt_perso    ADD REACTION SMALLINT DEFAULT 4 NOT NULL;
ALTER TABLE tlt_perso    ADD ARMEPREFEREE SMALLINT NULL;
ALTER TABLE tlt_perso    ADD SORTPREFERE SMALLINT  NULL;
ALTER TABLE tlt_perso    ADD PHRASEPREFEREE TEXT  NULL;
ALTER TABLE tlt_perso    ADD ACTIONSURPRISE SMALLINT DEFAULT 4 NOT NULL;

CREATE SEQUENCE seq_tlt_entitecachee;

CREATE TABLE tlt_entitecachee (
  ID INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_entitecachee'),  
  ID_ENTITE INTEGER  NOT NULL,  
  ID_LIEU  INTEGER NOT NULL REFERENCES tlt_lieu (ID_LIEU) ON DELETE CASCADE,
  TYPE SMALLINT NOT NULL DEFAULT 0
) ;


CREATE INDEX tlt_entitecachee_ID_LIEU
   ON tlt_entitecachee (ID_LIEU);


CREATE SEQUENCE seq_tlt_entitecacheeconnuede;

CREATE TABLE tlt_entitecacheeconnuede (
  ID INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_entitecacheeconnuede'),  
  ID_ENTITECACHEE INTEGER  NOT NULL REFERENCES tlt_entitecachee (ID),  
  id_perso  INTEGER NOT NULL 
) ;


CREATE UNIQUE INDEX tlt_entitecacheeconnuede_UK1
   ON tlt_entitecacheeconnuede (ID_ENTITECACHEE, id_perso);


CREATE UNIQUE INDEX tlt_perso_NOM
   ON tlt_perso (NOM);

CREATE UNIQUE INDEX tlt_inscriptions_NOM
   ON tlt_inscriptions (NOM);

CREATE UNIQUE INDEX tlt_mj_NOM
   ON tlt_mj (NOM);

ALTER TABLE tlt_perso ADD ARCHIVE SMALLINT DEFAULT 0 NOT NULL;

CREATE SEQUENCE seq_tlt_archive;

CREATE TABLE tlt_archive (
  ID_ARCHIVE INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_archive'),  
  id_perso  INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  DATEARCHIVAGE DATE NOT NULL,
  DATEDESARCHIVAGE DATE NULL
) ;


--  AJOUT DES GROUPES DE PJ
	CREATE SEQUENCE seq_tlt_groupe;
	
	CREATE TABLE tlt_groupe (
	  ID_GROUPE INTEGER  NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_groupe'),  
	  NOM VARCHAR(50) NOT NULL
	) ;

	CREATE UNIQUE INDEX tlt_groupe_nom
	   ON tlt_groupe (NOM);
	
	ALTER TABLE tlt_perso ADD ID_GROUPE INTEGER  NULL REFERENCES tlt_groupe (ID_GROUPE) ON DELETE SET NULL;

	CREATE INDEX tlt_perso_id_groupe
	   ON tlt_perso (id_groupe);
	
--  FIN AJOUT DES GROUPES DE PJ


	CREATE INDEX tlt_sessions_1
	   ON tlt_sessions (id_joueur);
	   
	CREATE INDEX tlt_sessions_2
	   ON tlt_sessions (pj);
	   
	CREATE INDEX tlt_sessions_3
	   ON tlt_sessions (permanent);
	   
	CREATE INDEX tlt_chemins_1
	   ON tlt_chemins (id_lieu_1);

	CREATE INDEX tlt_chemins_2
	   ON tlt_chemins (id_lieu_2);	   	   

	CREATE INDEX tlt_chemins_TYPE
	   ON tlt_chemins (type);	   	   

	CREATE INDEX tlt_comp_id_perso
	   ON tlt_comp (id_perso);	   	   

	CREATE INDEX tlt_persospec_1
	   ON tlt_persospec (id_perso);	

	CREATE INDEX tlt_persoETATTEMP_1
	   ON tlt_persoETATTEMP (id_perso);	
	   
	CREATE INDEX tlt_persoOBJETS_1
	   ON tlt_persoOBJETS (id_perso);	
	   
	CREATE INDEX tlt_persoMAGIE_1
	   ON tlt_persoMAGIE (id_perso);	


--  MODIF POUR AJOUTER DES POINTS D'INTELLECT
	ALTER TABLE tlt_etattempnom ADD RPI INTEGER NOT NULL DEFAULT 0;
	
	ALTER TABLE tlt_specnom  ADD RPI INTEGER NOT NULL DEFAULT 0;
	
	ALTER TABLE tlt_perso ADD PI INTEGER NOT NULL DEFAULT 0;
	
	ALTER TABLE tlt_perso RENAME INTERVAL_REMISE  TO INTERVAL_REMISEPA;
	ALTER TABLE tlt_perso RENAME DERNIERE_REMISE  TO DERNIERE_REMISEPA;
	
	ALTER TABLE tlt_perso ADD INTERVAL_REMISEPI INTEGER  NOT NULL DEFAULT 90;
	ALTER TABLE tlt_perso ADD DERNIERE_REMISEPI INTEGER NOT NULL DEFAULT 0;
	
	--  SUPPRESSION DE ARMEPREFEREE QUI NE SERT PLUS 
	ALTER TABLE tlt_perso    DROP ARMEPREFEREE;
--  FIN MODIFS POUR AJOUTER DES POINTS D'INTELLECT

--  MISE A 25 POUR ETRE DE MEME TAILLE QUE PHPBB_USERS
ALTER TABLE tlt_perso ALTER NOM TYPE VARCHAR(25);
--  MISE A 25 POUR ETRE DE MEME TAILLE QUE PHPBB_USERS
ALTER TABLE tlt_mj ALTER NOM TYPE VARCHAR(25);

--  MISE A 25 POUR ETRE DE MEME TAILLE QUE PHPBB_USERS
ALTER TABLE tlt_inscriptions ALTER NOM TYPE VARCHAR(25);

	CREATE UNIQUE INDEX tlt_objets_uk1 ON tlt_objets (NOM);

	CREATE UNIQUE INDEX tlt_specnom_uk1 ON tlt_specnom (NOM);

	CREATE UNIQUE INDEX tlt_magie_uk1 ON tlt_magie (NOM);

	CREATE UNIQUE INDEX tlt_lieu_UK1 ON tlt_lieu (NOM, TRIGRAMME);

	CREATE UNIQUE INDEX tlt_etattempnom_uk1 ON tlt_etattempnom (NOM);


ALTER TABLE tlt_perso ADD IP_JOUEUR VARCHAR(9) NULL;

--  PASSAGE EN NULL => NULL DEVIENT CONNU DE TOUS LES PERSOS (UTILISÉ POUR PLACER UN OBJET A TEL ENDROIT)
ALTER TABLE tlt_entitecacheeconnuede ALTER COLUMN id_perso DROP NOT NULL;

--  PASSAGE DU MOT DE PASSE DES JOUEURS EN CRYPTE
	UPDATE tlt_inscriptions SET PASS = MD5(PASS);
	
	ALTER TABLE tlt_inscriptions ALTER PASS TYPE VARCHAR(32);
	
	UPDATE tlt_perso SET PASS = MD5(PASS);
	
	ALTER TABLE tlt_perso ALTER PASS TYPE VARCHAR(32);

--  FIN DE PASSAGE DU MOT DE PASSE DES JOUEURS EN CRYPTE


-- SQL DU BUG PERMANENT TEMPORAIRE. NORMALEMENT NE FAIT RIEN
-- MAIS ON NE SAIT JAMAIS 
UPDATE tlt_objets SET PERMANENT = 1 WHERE SOUS_TYPE <> 'CLEF';

UPDATE tlt_persoOBJETS SET  TEMPORAIRE = 0
WHERE ID_OBJET = (SELECT ID_OBJET
FROM  tlt_objets
WHERE PERMANENT = 1);
-- FIN BUG PERMANENT TEMPORAIRE

--  MODIFS POUR GESTION DES ARMES A 2 MAINS
	DELETE FROM tlt_persoOBJETS WHERE ID_OBJET=1;
	
	UPDATE  tlt_persoOBJETS SET EQUIPE=0;
	
	INSERT INTO tlt_persoOBJETS (id_perso,ID_OBJET,DURABILITE,MUNITIONS,TEMPORAIRE,EQUIPE)
	SELECT tlt_perso.id_perso,1,-1,-1,0,1 FROM tlt_perso;
--  FIN MODIFS POUR GESTION DES ARMES A 2 MAINS


ALTER TABLE tlt_perso ADD ID_CATEGORIEAGE INTEGER;

ALTER TABLE tlt_inscriptions ADD ID_CATEGORIEAGE INTEGER;
--  MODIF RACE, SEXE

ALTER TABLE tlt_perso ADD ID_SEXE INTEGER;

ALTER TABLE tlt_inscriptions ADD ID_SEXE INTEGER;

ALTER TABLE tlt_perso ADD ID_RACE INTEGER;

ALTER TABLE tlt_inscriptions ADD ID_RACE INTEGER;

	CREATE SEQUENCE seq_tlt_typeetattemp;
		
	CREATE TABLE tlt_typeetattemp (
	  ID_TYPEETATTEMP INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_typeetattemp'),
	  NOMTYPE VARCHAR(50) NOT NULL 
	) ;
	
INSERT INTO tlt_typeetattemp (NOMTYPE) VALUES ('Age') ;

INSERT INTO tlt_typeetattemp (NOMTYPE) VALUES ('Race') ;

INSERT INTO tlt_typeetattemp (NOMTYPE) VALUES ('Sexe') ;

INSERT INTO tlt_typeetattemp (NOMTYPE) VALUES ('Lieu') ;

INSERT INTO tlt_typeetattemp (NOMTYPE) VALUES ('Divers') ;


ALTER TABLE tlt_etattempnom ADD ID_TYPEETATTEMP  INTEGER  DEFAULT 5 NOT NULL REFERENCES tlt_typeetattemp (ID_TYPEETATTEMP) ON DELETE RESTRICT;

UPDATE tlt_etattempnom SET  ID_TYPEETATTEMP  = 5;



INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (1, 'ENFANT');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (1, 'JEUNE ADULTE');

INSERT INTO tlt_persoETATTEMP (id_perso, ID_ETATTEMP, FIN)
SELECT  id_perso,CURRVAL('seq_tlt_etattempnom') ,-1
FROM tlt_perso
WHERE ID_CATEGORIEAGE IS NULL;

UPDATE tlt_perso SET ID_CATEGORIEAGE= CURRVAL('seq_tlt_etattempnom') WHERE ID_CATEGORIEAGE IS NULL;

UPDATE tlt_inscriptions SET ID_CATEGORIEAGE= CURRVAL('seq_tlt_etattempnom') WHERE ID_CATEGORIEAGE IS NULL;


ALTER TABLE tlt_perso ALTER ID_CATEGORIEAGE SET NOT NULL;

ALTER TABLE tlt_inscriptions ALTER ID_CATEGORIEAGE SET NOT NULL;

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (1, 'ADULTE EXPÉRIMENTÉ');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (1, 'VIELLARD');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (2, 'HUMAIN');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (2, 'ELFE');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (2, 'NAIN');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (2, 'TROLL');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (2, 'GOBELIN');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (2, 'ORC');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (2, 'DEMI-ELFE');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (3, 'MALE');

INSERT INTO tlt_etattempnom ( ID_TYPEETATTEMP, NOM ) VALUES (3, 'FEMELLE');


CREATE INDEX tlt_etattempnom_ID_TYPEETATTEMP
ON tlt_etattempnom (ID_TYPEETATTEMP);

--  MIGRATION DES RACES EXISTANTES
INSERT INTO tlt_etattempnom (ID_TYPEETATTEMP, NOM, VISIBLE)
SELECT  DISTINCT tlt_typeetattemp.ID_TYPEETATTEMP ,RACE, 1
FROM (tlt_perso LEFT JOIN tlt_etattempnom ON tlt_perso.RACE = tlt_etattempnom.NOM) , tlt_typeetattemp
WHERE nomtype='Race'
AND tlt_perso.RACE IS NOT NULL
AND tlt_perso.RACE <>''
AND tlt_etattempnom.NOM IS NULL;

INSERT INTO tlt_etattempnom (ID_TYPEETATTEMP, NOM, VISIBLE)
SELECT  DISTINCT tlt_typeetattemp.ID_TYPEETATTEMP ,RACE, 1
FROM (tlt_inscriptions  LEFT JOIN tlt_etattempnom ON tlt_inscriptions.RACE = tlt_etattempnom.NOM) , tlt_typeetattemp
WHERE nomtype='Race'
AND tlt_inscriptions.RACE IS NOT NULL
AND tlt_inscriptions.RACE <>''
AND tlt_etattempnom.NOM IS NULL;


--  MIGRATION DES RACES DES PJS
INSERT INTO tlt_persoETATTEMP (id_perso, ID_ETATTEMP, FIN)
SELECT  id_perso,ID_ETATTEMP ,-1
FROM tlt_perso,tlt_etattempnom  , tlt_typeetattemp
WHERE nomtype='Race'
AND tlt_perso.RACE = tlt_etattempnom.NOM
AND tlt_perso.RACE IS NOT NULL
AND tlt_perso.RACE <>''
AND tlt_etattempnom.ID_TYPEETATTEMP = tlt_typeetattemp.ID_TYPEETATTEMP;

UPDATE tlt_perso SET ID_RACE =  (
SELECT tlt_etattempnom.ID_ETATTEMP FROM tlt_etattempnom, tlt_typeetattemp
WHERE tlt_etattempnom.ID_TYPEETATTEMP=tlt_typeetattemp.ID_TYPEETATTEMP
AND nomtype='Race' AND tlt_etattempnom.NOM=tlt_perso.RACE);


--  MIGRATION DES SEXES EXISTANTS
UPDATE tlt_perso SET SEXE =-1 WHERE SEXE=0;

UPDATE tlt_inscriptions SET SEXE =-1 WHERE SEXE=0;

INSERT INTO tlt_etattempnom (ID_TYPEETATTEMP, NOM, VISIBLE)
SELECT  DISTINCT tlt_typeetattemp.ID_TYPEETATTEMP ,SEXE, 1
FROM (tlt_perso LEFT JOIN tlt_etattempnom ON tlt_perso.SEXE = tlt_etattempnom.NOM) , tlt_typeetattemp
WHERE nomtype='Sexe'
AND tlt_perso.SEXE IS NOT NULL
AND tlt_etattempnom.NOM IS NULL;

INSERT INTO tlt_etattempnom (ID_TYPEETATTEMP, NOM, VISIBLE)
SELECT  DISTINCT tlt_typeetattemp.ID_TYPEETATTEMP ,SEXE, 1
FROM (tlt_inscriptions LEFT JOIN tlt_etattempnom ON tlt_inscriptions.SEXE = tlt_etattempnom.NOM) , tlt_typeetattemp
WHERE nomtype='Sexe'
AND tlt_inscriptions.SEXE IS NOT NULL
AND tlt_etattempnom.NOM IS NULL;

UPDATE tlt_etattempnom SET NOM = '0' WHERE NOM='-1';

UPDATE tlt_perso SET SEXE = '0' WHERE SEXE='-1';

UPDATE tlt_inscriptions SET SEXE = '0' WHERE SEXE='-1';

--  MIGRATION DES SEXES DES PJS
INSERT INTO tlt_persoETATTEMP (id_perso, ID_ETATTEMP, FIN)
SELECT  id_perso,ID_ETATTEMP ,-1
FROM tlt_perso,tlt_etattempnom  , tlt_typeetattemp
WHERE nomtype='Sexe'
AND tlt_perso.SEXE = tlt_etattempnom.NOM
AND tlt_etattempnom.ID_TYPEETATTEMP = tlt_typeetattemp.ID_TYPEETATTEMP;

UPDATE tlt_perso  SET ID_SEXE =  
(
SELECT tlt_etattempnom.ID_ETATTEMP FROM tlt_etattempnom, tlt_typeetattemp
WHERE tlt_etattempnom.ID_TYPEETATTEMP=tlt_typeetattemp.ID_TYPEETATTEMP
AND nomtype='Sexe' AND tlt_etattempnom.NOM=tlt_perso.SEXE);


-- MIGRATION DES INSCRIPTIONS A FAIRE


UPDATE tlt_inscriptions  SET ID_RACE =  
(
SELECT tlt_etattempnom.ID_ETATTEMP FROM tlt_etattempnom, tlt_typeetattemp
WHERE tlt_etattempnom.ID_TYPEETATTEMP=tlt_typeetattemp.ID_TYPEETATTEMP
AND nomtype='Race' AND tlt_etattempnom.NOM=tlt_inscriptions.RACE);

UPDATE tlt_inscriptions  SET ID_SEXE =  
(
SELECT tlt_etattempnom.ID_ETATTEMP FROM tlt_etattempnom, tlt_typeetattemp
WHERE tlt_etattempnom.ID_TYPEETATTEMP=tlt_typeetattemp.ID_TYPEETATTEMP
AND NOMTYPE='Saxe' AND tlt_etattempnom.NOM=tlt_inscriptions.SEXE);

ALTER TABLE tlt_perso DROP RACE;

ALTER TABLE tlt_perso DROP SEXE;




ALTER TABLE tlt_inscriptions    DROP SEXE;	
	
ALTER TABLE tlt_inscriptions    DROP RACE;

INSERT INTO  tlt_persoETATTEMP (id_perso ,ID_ETATTEMP , FIN)
SELECT  tlt_perso.id_perso, ID_SEXE, '-1'
FROM tlt_perso LEFT JOIN tlt_persoETATTEMP ON 
 tlt_perso.ID_SEXE =  tlt_persoETATTEMP.ID_ETATTEMP AND  tlt_perso.id_perso = tlt_persoETATTEMP.id_perso
WHERE ID_ETATTEMP IS NULL;

INSERT INTO  tlt_persoETATTEMP (id_perso ,ID_ETATTEMP , FIN)
SELECT  tlt_perso.id_perso, ID_RACE, '-1'
FROM tlt_perso LEFT JOIN tlt_persoETATTEMP ON 
 tlt_perso.ID_RACE =  tlt_persoETATTEMP.ID_ETATTEMP AND  tlt_perso.id_perso = tlt_persoETATTEMP.id_perso
WHERE ID_ETATTEMP IS NULL;

INSERT INTO  tlt_persoETATTEMP (id_perso ,ID_ETATTEMP , FIN)
SELECT  tlt_perso.id_perso, ID_CATEGORIEAGE, '-1'
FROM tlt_perso LEFT JOIN tlt_persoETATTEMP ON 
 tlt_perso.ID_CATEGORIEAGE=  tlt_persoETATTEMP.ID_ETATTEMP AND  tlt_perso.id_perso = tlt_persoETATTEMP.id_perso
WHERE ID_ETATTEMP IS NULL;

-- FIN MODIF RACE, SEXE . IL RESTE A ADMIN A RENOMMER LES ETATS TEMPORAIRES DU SEXE AVEC L'INTERFACE
--  DANS LA VERSION DE BASE, 0 DOIT ETRE MALE, 1 DOIT ETRE FEMELLE MAIS CERTAINS ADMIN ONT PU LE MODIFIER OU EN AJOUTER


-- AJOUT DE SE CACHER
ALTER TABLE tlt_lieu ADD DIFFICULTEDESECACHER SMALLINT DEFAULT 0 NOT NULL;

ALTER TABLE tlt_perso ADD DISSIMULE SMALLINT NOT NULL DEFAULT 0;



--  MISE A JOUR DES DROITS POUR SE CACHER 
UPDATE tlt_lieu SET FLAGS = 
 RPAD(SUBSTRING(FLAGS FROM 1 FOR 7)||'1',LENGTH(FLAGS),'0') WHERE TRIGRAMME <>'SPE';


-- TABLES DE NEWS

CREATE SEQUENCE seq_tlt_n_commentaires;  

CREATE TABLE  tlt_n_commentaires (
  ID INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_n_commentaires'),
  NEWS INTEGER NOT NULL,
  NEWS_DATE INTEGER NOT NULL,
  AUTEUR VARCHAR(25) NULL,
  TEXTE TEXT NOT NULL
) ;

CREATE SEQUENCE seq_tlt_n_config;  

CREATE TABLE  tlt_n_config (
  ID INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_n_config'),
  TITLE VARCHAR(25) NOT NULL DEFAULT 'BIENVENUE SUR TALESTA-NEW',
  NBRE_NEWS SMALLINT NOT NULL DEFAULT '5',
  NOM_ARCHIVE VARCHAR(25) NOT NULL DEFAULT 'VOIR LES ARCHIVES',
  NOM_PROPOSER VARCHAR(25) NOT NULL DEFAULT 'PROPOSER UNE NEWS',
  NOM_COMMENTAIRES VARCHAR(25) NOT NULL DEFAULT 'COMMENTAIRES(-- )',
  NOM_INDEX VARCHAR(25) NOT NULL DEFAULT 'INDEX'
) ;
     
CREATE SEQUENCE seq_tlt_n_news;
        
CREATE TABLE  tlt_n_news (
  ID INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_n_news'),
  NEWS_DATE INTEGER NOT NULL ,
  TITRE VARCHAR(25) NOT NULL ,
  AUTEUR VARCHAR(25) NOT NULL ,
  TEXTE TEXT NOT NULL
) ;


-- 
-- CONTENU DE LA TABLE tlt_n_news
-- 

INSERT INTO tlt_n_news (NEWS_DATE, TITRE, AUTEUR, TEXTE) VALUES (1109590009, 'VERSION DE FÉVRIER 2005', 'HIXCKS', 'PREMIÈRE VERSION DE LA COMMUNAUTÉ:\R\N<UL TYPE="SQUARE">\R\N<LI> CORRECTIONS DE PLUSIEURS BUGS DE LA VERSION NOVEMBRE2004 TROUVÉS SUR LE FORUM. </LI>\R\N<LI> UNE DOC D''INSTALL GRACE À KAERU. </LI>\R\N<LI> LES MENUS ADMIN/JOUEUR SONT LA FUSION DE CEUX DU DUO CHUB/TIDOU ET DE LUKA. </LI>\R\N<LI> LE SYSTEME DE NEWS (ADAPTÉ DE CHUB) </LI>\R\N<LI> PLUSIEURS ÉVOLS/MODIFS FOURNIES SUR LE FORUM:</LI><UL TYPE="SQUARE"> <LI>SOIN VIA OBJET (URIEL) </LI><LI> LISTECHEMIN (LAPIN) </LI>\R\N<LI> DIFFÉRENTS PATCHES DE KAERU (LES CONNECTÉS , L''INTÉGRATION DU FORUM AU STYLE TALESTA, PLUSIEURS NIVEAUX DE MAINTENANCE).</LI> \R\N<LI> QCM OPTIONNEL AVANT INSCRIPTION (CHUB, SAIKOH).</LI>\R\N</UL>\R\N<UL TYPE="SQUARE"> PLUSIEURS ÉVOLS/MODIFS PROPOSÉES SUR LE FORUM.\R\N         <LI> SUPPRESSION DES BALISES HTML DANS LES SAISIES (PARLER ...) </LI>\R\N		 <LI> OBJETS MAUDITS (NE POUVANT ETRE ENLEVES UNE FOIS ÉQUIPÉS) </LI>\R\N		 <LI> SE CACHER</LI><LI> REVELER DES OBJETS, PERSOS, CHEMINS CACHES</LI>\R\N		 <LI> ABANDONNER OBJET</LI>\R\N		 <LI> INTEGRATION DE L''IMAGE DE L''AVATAR DU FORUM DANS LE JEU</LI>\R\N		 <LI> VOLER DES PO À UN CADAVRE => RÉUSSITE AUTO</LI>\R\N		 <LI> MAIL AU FORMAT HTML</LI>\R\N		 <LI> MOTS DE PASSE DES PJ CRYPTES EN BASE</LI>\R\N</UL>\R\N<LI>AUTRES.</LI>\R\N<UL TYPE="SQUARE"> \R\N<LI> GESTION DES ÉTATS TEMPORAIRES POUR LA RACE, L''AGE ET LE SEXE (CF. DOC D''EXEMPLE FOURNIE)</LI>\R\N<LI> LIMITATIONS D''UTILISATION DES OBJETS ET SORTS AUX PJS AYANT UN ÉTAT TEMPORAIRE, CE QUI PERMET DE CRÉER DES SORTS ET ARMES SPÉCIFIQUES AUX ELFES, OU AUX MAGES...</LI>\R\N<LI> POSSIBILITÉ DE FERMER LES INSCRIPTIONS.</LI></UL>\R\N\R\N<LI> J''EN OUBLIE SUREMENT.... POUR PLUS D''INFOS <A HREF=''HTTP://VKNAB.FREE.FR/PHPBB2/''>FORUM TALESTA4</A> </LI>\R\N\R\N</UL>');
 

-- 
-- CONTENU DE LA TABLE tlt_n_config
-- 

INSERT INTO tlt_n_config ( TITLE, NBRE_NEWS, NOM_ARCHIVE, NOM_PROPOSER, NOM_COMMENTAIRES, NOM_INDEX) VALUES ( 'BIENVENUE SUR TALESTA-NEW', 5, 'VOIR LES ARCHIVES', 'PROPOSER UNE NEWS', 'COMMENTAIRES(-- )', 'INDEX');
   

--  FIN DES TABLES DE NEWS        

--  AJOUT DE LA COLONNE NOM POUR EVITER DE FAIRE DES JOINTURES SANS CESSE POUR L'AFFICHAGE DE CE QUE L'ON A TROUVE

ALTER TABLE tlt_entitecachee ADD NOM VARCHAR(64) NULL  ;


UPDATE  tlt_entitecachee SET 
NOM = (SELECT 'CHEMIN VERS '|| tlt_lieu.NOM
FROM tlt_chemins, tlt_lieu 
WHERE tlt_entitecachee.ID_ENTITE = tlt_chemins.ID_CLEF AND
tlt_lieu.ID_LIEU = tlt_chemins.ID_LIEU_1
AND tlt_chemins.ID_LIEU_2 = tlt_entitecachee.ID_LIEU AND
tlt_entitecachee.TYPE = 0);

UPDATE  tlt_entitecachee SET 
NOM = (SELECT tlt_objets.NOM FROM tlt_objets 
WHERE tlt_entitecachee.ID_ENTITE = tlt_objets.ID_OBJET AND
tlt_entitecachee.TYPE = 1);


UPDATE  tlt_entitecachee SET 
NOM = (SELECT tlt_perso.NOM 
FROM tlt_perso
WHERE tlt_entitecachee.ID_ENTITE = tlt_perso.id_perso AND
tlt_entitecachee.TYPE = 2);

--  FIN AJOUT DE LA COLONNE NOM POUR EVITER DE FAIRE DES JOINTURES SANS CESSE POUR L'AFFICHAGE DE CE QUE L'ON A TROUVE

--  AJOUT DE LA TABLE QUESTIONNAIRE

-- 
--  TABLE STRUCTURE FOR TABLE 'tlt_qcm'
-- 
CREATE SEQUENCE seq_tlt_qcm;

CREATE TABLE tlt_qcm (
  ID_QUESTION INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_qcm'),
  QUESTION VARCHAR(128) NOT NULL,
  REPONSE1 VARCHAR(128) NOT NULL,
  REPONSE2 VARCHAR(128) NOT NULL,
  REPONSE3 VARCHAR(128) NOT NULL,
  REPONSE4 VARCHAR(128) NOT NULL,
  BONNE SMALLINT NOT NULL  
);



--INSERT INTO tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) VALUES ( 'N&#39;EN AVEZ VOUS PAS ASSEZ DE LA GUÉGUERRE KAERU/CHUB ?', 'OUI', 'NON', 'AH BON&#44; ILS NE S&#39;AIMENT PAS ?', 'ON PEUT PARTICIPER ?', 1);
--INSERT INTO tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) VALUES ( 'POURQUOI LA GÉGUERRE KAERU/CHUB EST-ELLE DOMMAGE ?', 'PARCE QU&#39;ON PEUT PAS Y PARTICIPER', 'PARCE QU&#39;ON VOIT PAS LES DÉGATS FAITS', 'PARCE QU&#39;IL FAUT EFFACER LEUR MESSAGES', 'PARCE QU&#39;ILS SONT PARMI LES PLUS ACTIFS DU FORUM', 4);
--INSERT INTO tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) VALUES ( 'COMMENT RÉGLER CETTE GUÉGUERRE KAERU/CHUB ?', 'ON LES VIRE', 'ON ACHEVE LE SURVIVANT', 'ON LES ENFERME ENSEMBLE JUSQU&#39;A CE QU&#39;ILS S&#39;APPRECIENT', 'ON FAIT RIEN ET ON ATTEND QU&#39;ILS SE CALMENT', 3);
INSERT INTO tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) VALUES ( 'PUIS AVOIR DEUX COMPTES DE JEU ?', 'OUI N''IMPORTE QUAND', 'OUI SI JE DEMANDE', 'OUI MAIS IL FAUT PAS SE FAIRE CAPTER', 'NON', 4);
INSERT INTO tlt_qcm (QUESTION,REPONSE1,REPONSE2,REPONSE3,REPONSE4,BONNE) VALUES ( 'AI-JE LE DROIT D&#39;INSULTER TOUT LE MONDE ?', 'SEULEMENT LES ORCS', 'OUI SI JE DEMANDE', 'OUI MAIS IL FAUT PAS SE FAIRE CAPTER', 'NON', 4);
 
UPDATE tlt_mj SET FLAGS='11111111111111111111111111111111111111111111111111111111111111111111111111111111'
WHERE ID_MJ=1;


--  FIN AJOUT DE LA TABLE QUESTIONNAIRE

ALTER TABLE tlt_perso ADD BACKGROUND TEXT NULL;

ALTER TABLE tlt_inscriptions ADD BACKGROUND TEXT NULL;

UPDATE tlt_inscriptions SET BACKGROUND  = '.' WHERE BACKGROUND IS NULL;

UPDATE tlt_perso SET BACKGROUND  = '.' WHERE BACKGROUND IS NULL;

ALTER TABLE tlt_perso ALTER BACKGROUND SET NOT NULL;

ALTER TABLE tlt_inscriptions ALTER BACKGROUND SET NOT NULL;

ALTER TABLE tlt_objets ADD ID_ETATTEMPSPECIFIQUE INTEGER 
REFERENCES tlt_etattempnom ( ID_ETATTEMP)  ON DELETE SET NULL;

ALTER TABLE tlt_magie ADD ID_ETATTEMPSPECIFIQUE INTEGER 
REFERENCES tlt_etattempnom ( ID_ETATTEMP)  ON DELETE SET NULL;


CREATE INDEX tlt_etattemp_FK1
   ON tlt_etattemp (ID_ETATTEMP);

CREATE INDEX tlt_archive_FK1
   ON tlt_archive (id_perso);

CREATE INDEX tlt_zone_FK1
   ON tlt_zone (ID_LIEU);

CREATE UNIQUE INDEX tlt_typeetattemp_UK1
   ON tlt_typeetattemp (NOMTYPE);


CREATE UNIQUE INDEX tlt_etattemp_UK1
   ON tlt_etattemp (ID_ETATTEMP,ID_COMP );


ALTER TABLE tlt_magie ADD typecible SMALLINT DEFAULT 1 NOT NULL;

ALTER TABLE tlt_magie ADD sortdistant SMALLINT DEFAULT 0 NOT NULL;	

alter table tlt_perso add wantmusic  SMALLINT default 0 not null;

alter table tlt_lieu add cheminfichieraudio varchar(50) null;

--modif suite erreur remontée par Uriel sortprefere etant id_magie de tlt_magie, il doit etre du meme type
ALTER TABLE tlt_perso alter sortprefere TYPE INTEGER;


-- engagement de Tidou
ALTER TABLE tlt_perso ADD engagement SMALLINT DEFAULT 0 NOT NULL;



CREATE TABLE tlt_engagement (
  id_perso INTEGER NOT NULL,
  id_adversaire INTEGER NOT NULL,
  nom	VARCHAR(25) NOT NULL,
  propdes SMALLINT DEFAULT 0 NOT NULL,
  constraint pk_tlt_engagement PRIMARY KEY  (id_perso, id_adversaire), 
  CONSTRAINT tlt_engagement_1_fkey FOREIGN KEY (id_perso) REFERENCES  tlt_perso (id_perso) ON DELETE CASCADE,
  CONSTRAINT tlt_engagement_2_fkey FOREIGN KEY (id_adversaire) REFERENCES  tlt_perso (id_perso) ON DELETE CASCADE 
);


--  mise a jour des droits pour parler
update tlt_lieu set flags =
 rpad(substr(flags,1,11)||'1',length(flags),'0');

commit;

-- ajout de combiner objet
ALTER TABLE tlt_objets ADD composantes VARCHAR( 100 );


ALTER TABLE tlt_zone ADD stockmax SMALLINT DEFAULT -1 NOT NULL;

ALTER TABLE tlt_zone ADD quantite SMALLINT DEFAULT -1 NOT NULL;

ALTER TABLE tlt_zone ADD remisestock SMALLINT DEFAULT -1 NOT NULL ;

ALTER TABLE tlt_zone ADD derniereremise  integer DEFAULT 0 NOT NULL;
 
ALTER TABLE tlt_mj ADD wantmusic  SMALLINT default 0 not null;

ALTER TABLE tlt_objets ALTER poids TYPE REAL; 

alter TABLE tlt_perso add commentaires_mj TEXT null;

ALTER TABLE tlt_mj ADD dispo_pour_ppa SMALLINT DEFAULT 1 not null;

ALTER TABLE tlt_etattempnom ADD utilisableinscription SMALLINT DEFAULT 1 not null;

ALTER TABLE tlt_typeetattemp ADD critereinscription SMALLINT default 0  NOT NULL;

ALTER TABLE tlt_typeetattemp ADD modifiableparpj SMALLINT default 0 NOT NULL;

update tlt_typeetattemp set critereinscription = 2 where nomtype = 'Age' or nomtype = 'Race' or nomtype = 'Sexe';

update tlt_etattempnom set visible = 1 
where id_typeetattemp  in (select tlt_typeetattemp.id_typeetattemp  
	from tlt_typeetattemp
	where nomtype in ('Age','Race','Sexe')
	);


INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ( 'Taille', '1','0');

INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ('Corpulence', '1','1');

INSERT INTO tlt_typeetattemp (  nomtype , critereinscription , modifiableparpj)
VALUES ('Humeur', '1','1');

CREATE SEQUENCE seq_tlt_inscriptetattemp;

CREATE TABLE tlt_inscriptetattemp (
  id_clef INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_inscriptetattemp'),
  id_inscript INTEGER NOT NULL ,
  id_etattemp INTEGER NOT NULL ,
  CONSTRAINT tlt_inscriptetattemp_1_fkey FOREIGN KEY (id_inscript) REFERENCES tlt_inscriptions (id) ON DELETE CASCADE,
  CONSTRAINT tlt_inscriptetattemp_2_fkey FOREIGN KEY (id_etattemp) REFERENCES  tlt_etattempnom (id_etattemp) ON DELETE CASCADE 
);

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_categorieage from tlt_inscriptions;

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_sexe from tlt_inscriptions;

insert into tlt_inscriptetattemp (id_inscript, id_etattemp)
select id, id_race from tlt_inscriptions;

alter table tlt_inscriptions DROP id_categorieage;

alter table tlt_inscriptions DROP id_sexe;

alter table tlt_inscriptions DROP id_race;

ALTER TABLE tlt_perso DROP id_categorieage;

ALTER TABLE tlt_perso DROP id_sexe;

ALTER TABLE tlt_perso DROP id_race;


--fonction ifnull pour nvl

CREATE or replace FUNCTION ifnull( numeric, numeric ) RETURNS
numeric AS 'DECLARE  input_value  ALIAS FOR $1;  else_value   ALIAS FOR $2;  output_value numeric; BEGIN   select coalesce( input_value, else_value ) into output_value ;  return output_value ;END;   ' LANGUAGE 'plpgsql' ;


CREATE or replace FUNCTION ifnull( varchar, varchar ) RETURNS
varchar AS 'DECLARE input_value  ALIAS FOR $1;  else_value   ALIAS FOR $2;   output_value varchar; BEGIN   select coalesce( input_value, else_value ) into output_value ; return output_value ; END;' LANGUAGE 'plpgsql' ; 

alter table tlt_magie add composantes VARCHAR(100) NULL;

-- mise a jour des droits pour se recevoir des sorts exterieurs
UPDATE tlt_lieu SET flags = 
RPAD(SUBSTRING(FLAGS FROM 1 FOR 12)||'1',LENGTH(FLAGS),'0') WHERE TRIGRAMME <>'SPE';

ALTER TABLE tlt_perso ADD role_mj INTEGER;

--mise a jour des droits pour admin. Pourquoi avait-il encore des 0....
update tlt_mj set flags =
 rpad('1',length(flags),'1') where id_mj = 1;
 
 
-- debut bug sur les objets caches 
alter table tlt_persoobjets ALTER id_perso drop not null;


CREATE SEQUENCE seq_tlt_migbugobjetscaches;

CREATE TABLE tlt_migbugobjetscaches (
  id_entite INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_migbugobjetscaches'),
  id INTEGER NOT NULL,
  id_entiteOLD INTEGER NOT NULL,
  durabilite INTEGER NOT NULL DEFAULT -1,
  munitions INTEGER NOT NULL DEFAULT -1
);


insert into tlt_migbugobjetscaches (id,id_entiteOLD,durabilite,munitions)
select id,id_entite as id_entiteOLD,durabilite , munitions from tlt_entitecachee, tlt_objets
where id_entite = id_objet and tlt_entitecachee.type=1;

insert into tlt_persoobjets (id_perso,id_objet, durabilite, munitions,  temporaire ,  equipe   )
select null,id_entiteOLD,durabilite , munitions,0,0  from tlt_migbugobjetscaches
order by id;

update tlt_migbugobjetscaches set id_entite = id_entite + (SELECT NEXTVAL('seq_tlt_persoobjets')- count(id_entite)-1 from tlt_migbugobjetscaches);

-- ne fonctionne plus en 8.1. Correction suit
--update tlt_entitecachee set id_entite = 
--(select 
--tlt_migbugobjetscaches.id_entite from tlt_migbugobjetscaches where tlt_entitecachee.id = tlt_migbugobjetscaches.id)
--where tlt_entitecachee.id = tlt_migbugobjetscaches.id;

update tlt_entitecachee set id_entite = 
tlt_migbugobjetscaches.id_entite from 
tlt_migbugobjetscaches where tlt_entitecachee.id = tlt_migbugobjetscaches.id;


DROP TABLE tlt_migbugobjetscaches;

DROP SEQUENCE seq_tlt_migbugobjetscaches;

-- fin bug sur les objets caches 

ALTER TABLE tlt_lieu ADD ID_ETATTEMPSPECIFIQUE INTEGER 
REFERENCES tlt_etattempnom ( ID_ETATTEMP)  ON DELETE SET NULL;


--quetes

CREATE SEQUENCE seq_tlt_quetes;

CREATE TABLE tlt_quetes (
  id_quete INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_quetes'),
  nom_quete  VARCHAR(50) NOT NULL,
  type_quete INTEGER NOT NULL,
  detail_type_quete INTEGER NOT NULL,
  duree_quete INTEGER NOT NULL DEFAULT -1,
  public INTEGER NOT NULL DEFAULT 0,
  cyclique INTEGER NOT NULL DEFAULT 0,
  proposepar INTEGER NOT NULL,
  proposepartype SMALLINT NOT NULL default 1,	
  texteproposition text not null,
  textereussite text not null,
  texteechec text not null,
  refuspossible INTEGER NOT NULL DEFAULT 0,
  abandonpossible INTEGER NOT NULL DEFAULT 0,
  validationquete INTEGER NOT NULL DEFAULT 0,
  id_lieu INTEGER NULL REFERENCES tlt_lieu (id_lieu) ON DELETE CASCADE,
  proposant_anonyme SMALLINT NOT NULL DEFAULT 0
);


CREATE SEQUENCE seq_tlt_recompensequete;

CREATE TABLE tlt_recompensequete (
  id_recompensequete INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_recompensequete'),
  id_quete INTEGER NOT NULL REFERENCES tlt_quetes (id_quete) ON DELETE CASCADE,
  type_recompense INTEGER NOT NULL,
  recompense INTEGER NOT NULL
);


CREATE SEQUENCE seq_tlt_persoquete;

CREATE TABLE tlt_persoquete (
  id_persoquete	INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_persoquete'),
  id_quete INTEGER NOT NULL REFERENCES tlt_quetes (id_quete) ON DELETE CASCADE,
  id_perso INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,
  etat INTEGER NOT NULL,	
  debut INTEGER NOT NULL DEFAULT 0,
  fin INTEGER NOT NULL DEFAULT -1 
);

CREATE OR REPLACE FUNCTION curdate() 
RETURNS date AS 'BEGIN  RETURN current_date;END;' LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION adddate(date,integer)
RETURNS date AS 'DECLARE v_1 ALIAS FOR $1;v_2 ALIAS FOR $2;BEGIN   RETURN v_1+ v_2; END;' LANGUAGE plpgsql;

update tlt_objets set type='Nourriture' where type='Divers' and sous_type='Nourriture';

--bestiaires n'ont pas de lieu
alter table tlt_perso ALTER id_lieu drop not null;

--modif du site du forum
update tlt_n_news set texte= replace (texte, 'HTTP://VKNAB.FREE.FR/PHPBB2/','http://www.talesta.free.fr/puntal');

CREATE SEQUENCE seq_tlt_apparitionmonstre;

CREATE TABLE tlt_apparitionmonstre (
  id_apparitionmonstre	INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_apparitionmonstre'),
  id_typelieu INTEGER NOT NULL,
  id_perso INTEGER NOT NULL REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,	
  nb_max_apparition SMALLINT NOT NULL  DEFAULT 1,	
  nb_max_lieu SMALLINT NOT NULL  DEFAULT -1,	
  chance_apparition SMALLINT NOT NULL
);

alter table tlt_lieu add apparition_monstre smallint not null default 0;

alter table tlt_lieu add type_lieu_apparition smallint not null default 1;

CREATE SEQUENCE seq_tlt_ppa;
 
create table tlt_ppa (
  id_ppa INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_ppa'),
  id_perso INTEGER NOT NULL  REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,	
  id_mj INTEGER NOT NULL  REFERENCES tlt_mj (id_mj) ON DELETE CASCADE,	    
  date_ppa INTEGER NOT NULL,
  detail_ppa TEXT NOT NULL,
  qte_pa smallint not null default 0,
  qte_pi smallint not null default 0    
);

--alter table tlt_perso add img_avatar varchar(100) null;

ALTER TABLE tlt_perso DROP COLUMN engagement;

create index tlt_apparitionmonstre_id_perso on tlt_apparitionmonstre  (id_perso);

create index tlt_apparitionmonstre_type_lie on tlt_apparitionmonstre  (id_typelieu );

create index tlt_inscriptetattemp_id_inscr on tlt_inscriptetattemp  (id_inscript) ;

create index tlt_lieu_type_lieu_apparition on tlt_lieu  (type_lieu_apparition);

create index tlt_magie_type on tlt_magie  (type); 

create index tlt_magie_soustype on tlt_magie  (sous_type); 

create index tlt_mj_dispoppa on tlt_mj  (dispo_pour_ppa);

create index tlt_n_commentaires_news on tlt_n_commentaires  (news);

create index tlt_objets_type on tlt_objets  (type);

create index tlt_objets_sstype on tlt_objets  (sous_type);

create index tlt_perso_pnj on tlt_perso  (pnj);

create index tlt_perso_idlieu on tlt_perso  (id_lieu);

create index tlt_persoquete_etat on tlt_persoquete  (etat);

create index tlt_persoquete_is_perso on tlt_persoquete  (id_perso);

create index tlt_ppa_id_mj on tlt_ppa  (id_mj);

create index tlt_recompensequete_id_quete  on tlt_recompensequete  (id_quete) ;

create index tlt_recompensequete_typeRecomp on tlt_recompensequete  (type_recompense);

create index tlt_quetes_nom_quete on tlt_quetes  (nom_quete);

create index tlt_quetes_type_quete on tlt_quetes  (type_quete);

create index tlt_quetes_public on tlt_quetes  (public);

create index tlt_quetes_proposepar on tlt_quetes  (proposepar);

ALTER TABLE tlt_perso add pourcentage_reaction smallint DEFAULT 100 not null;

ALTER table tlt_etattempnom add id_lieudepart integer null REFERENCES tlt_lieu (id_lieu) ON DELETE set NULL;

ALTER table tlt_etattempnom add objetsfournis varchar(50) null;

ALTER table tlt_etattempnom add sortsfournis varchar(50) null;

ALTER TABLE tlt_perso ADD nb_deces smallint DEFAULT 0 NOT NULL;

ALTER TABLE tlt_quetes ADD id_etattempspecifique integer NULL;

ALTER TABLE tlt_magie ADD coutpa smallint NULL;

ALTER TABLE tlt_magie ADD coutpi smallint NULL;

ALTER TABLE tlt_magie ADD coutpo smallint DEFAULT 0 NOT NULL;

ALTER TABLE tlt_magie ADD coutpv smallint DEFAULT 0 NOT NULL;

ALTER TABLE tlt_perso ADD moment_mort  integer NULL;

update tlt_perso set moment_mort = 0 where pv <0 and pnj <> 2;

commit;


CREATE SEQUENCE seq_tlt_traceactions;

CREATE TABLE tlt_traceactions(
id_trace INTEGER NOT NULL PRIMARY KEY DEFAULT NEXTVAL('seq_tlt_traceactions'),
action VARCHAR( 30 ) NOT NULL ,
id_acteur integer NOT NULL  REFERENCES tlt_perso (id_perso) ON DELETE CASCADE,	
id_lieu  integer NOT NULL  REFERENCES tlt_lieu (id_lieu) ON DELETE CASCADE,	
detail varchar( 100 ) NOT NULL ,
heure_action integer NOT NULL );

ALTER TABLE tlt_lieu RENAME COLUMN accessible TO accessible_telp;

