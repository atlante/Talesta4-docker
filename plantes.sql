insert into tlt_objets (type,nom,sous_type,degats_min,degats_max ,durabilite ,prix_base,description,poids,permanent,munitions,caracteristique,competence,composantes)
values ('ProduitNaturel','Baies Rouges','Nourriture','1','3','-1','5','Baies rouges sucrées.','0',0,'10',null,'Soin Naturel',null);

INSERT INTO tlt_objets ( type, sous_type, nom, degats_min, degats_max, anonyme, durabilite, prix_base, description, poids, image, permanent, munitions, caracteristique, competence, provoqueetat, competencespe, id_etattempspecifique, composantes) 
 VALUES ( 'ProduitNaturel', 'Vegetaux', 'Feuilles de chenes', 1, 4, 0, -1, 2, 'Feuilles de chenes', 0, NULL, 0, 4, 'Sagesse', 'Soin Naturel', NULL, NULL, NULL, NULL);

commit;
