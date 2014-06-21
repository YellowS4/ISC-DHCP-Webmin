/*
 * Code par Jason Gantner
 * script pour postgreSQL
 */

/*
 * Phase 1: Création de la base et de l'utilisateur.
 */
CREATE DATABASE projet34 WITH ENCODING 'Unicode';
-- Céation de la base, encodage utilisé: UTF-8
CREATE USER projet34 WITH PASSWORD 'SuperSecurePass';
-- Création de l'utilisateur de la base

GRANT CONNECT ON DATABASE projet34 TO projet34;
-- on autorise projet34 à se connecter à la base projet34.
GRANT USAGE ON SCHEMA public to projet34;
-- on autorise projet34 à utiliser le schéma public.



/*
 * Phase 2: Création des tables dans la base.
 *	Script à exécuter à l'intérieur de la base Projet34
 */

CREATE TABLE Projet34_Grades(
  idGrade SERIAL UNIQUE NOT NULL PRIMARY KEY, -- id du grade
  nomGrade VARCHAR(30) UNIQUE NOT NULL -- intitulé du grade
);

CREATE TABLE Projet34_Users(
  idUser SERIAL UNIQUE NOT NULL PRIMARY KEY, --id de l'utilisateur
  nomUser VARCHAR(100) NOT NULL, -- son nom réel
  email VARCHAR(150) NOT NULL, -- son adresse de courriel
  login VARCHAR(30) UNIQUE NOT NULL, --le nom d'utilisateur à utiliser pour se connecter
  h1 VARCHAR(128) UNIQUE NOT NULL,
  /* condensat cryptographique du login concaténé à ':'  concaténé au mot
   * de passe en utilisant l'algorithme SHA512 */
  refGrade INTEGER NOT NULL REFERENCES Projet34_Grades
  ON UPDATE CASCADE ON DELETE RESTRICT, -- grade de l'utilisateur (entier)
  actif BOOLEAN NOT NULL DEFAULT TRUE -- booléen représentant la possibilité de se connecter
);

CREATE TABLE Projet34_Configurations(
  idConf SERIAL UNIQUE NOT NULL PRIMARY KEY, -- id de la configuration
  creation TIMESTAMP NOT NULL DEFAULT current_timestamp,-- instant de creation de la configuration
  contenuConf TEXT NOT NULL, --la configuration
  createurConf INTEGER NOT NULL  REFERENCES Projet34_Users 
  ON UPDATE CASCADE ON DELETE RESTRICT --référence à l'utilisateur
);



/*
 * Phase 3: Autorisation offertes à l'utilisateur projet34 sur la base
 *	Script à exécuter à l'intérieur de la base projet34
 */
 
GRANT SELECT ON ALL TABLES IN SCHEMA public TO projet34;
GRANT INSERT ON ALL TABLES IN SCHEMA public TO projet34;
GRANT UPDATE ON ALL TABLES IN SCHEMA public TO projet34;
GRANT DELETE ON ALL TABLES IN SCHEMA public TO projet34;

/*
 * on autorise la selection, l'insertion, la mise à jour
 * et la suppression de données dans toutes les tables du
 * schéma public de la base projet34 à l'utilisateur projet34.
 */

GRANT USAGE ON ALL SEQUENCES IN SCHEMA public TO projet34;
GRANT SELECT ON ALL SEQUENCES IN SCHEMA public TO projet34;
GRANT UPDATE ON ALL SEQUENCES IN SCHEMA public TO projet34;
/*
 * on autorise l'utilisation, la mise à jour et la selection
 * des données de toutes les séquences du schéma public à 
 * l'utilisateur projet34 dans la base projet34
 */



/*
 * Phase 4: Insertion de données pour rendre le site utilisable
 *	Script à exécuter à l'intérieur de la base projet34
 *	Il est conseillé de modifier l'utilisateur par défaut
 */

--insertion des grades
INSERT INTO Projet34_Grades 
  VALUES (1,'utilisateur standard'), -- est autorisé à afficher l'état du serveur
	 (2,'Technicien DHCP'), -- peut modifier la configuration du serveur DHCP
	 (3,'Administrateur'); -- gère le serveur DHCP et l'interface web
-- on force l'utilisation des id pour rester cohérent avec le site.

-- insertion de l'utilisateur par défaut
INSERT INTO projet34_users (nomuser,email,login,h1,refgrade)
  VALUES ('default user',
	  'user@domain.tld',
	  'azerty',-- login
	  '2eda6e8c608787025e0ea3f5c8e021114e9c1cfee3ef1329825097a69df5537f38772b7b98634353082375dddaaeb34e33f68cb8f432f0e8d44e4b9eb8baa8e8',
	  --condensat cryptographique de azerty:1234
	  3
);
-- rajouter ici des utilisateurs si besoin