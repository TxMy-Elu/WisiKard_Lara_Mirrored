#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: compte
#------------------------------------------------------------

CREATE TABLE compte
(
    idCompte      Int Auto_increment NOT NULL,
    email         Varchar(100)       NOT NULL,
    password      Varchar(500)       NOT NULL,
    role          Varchar(50)        NOT NULL,
    tentativesCo  Int                NOT NULL DEFAULT 0,
    estDesactiver Bool               NOT NULL DEFAULT FALSE,
    CONSTRAINT compte_PK PRIMARY KEY (idCompte)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: template
#------------------------------------------------------------

CREATE TABLE template
(
    idTemplate Int Auto_increment NOT NULL,
    nom        Varchar(50)        NOT NULL,
    CONSTRAINT template_PK PRIMARY KEY (idTemplate)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: carte
#------------------------------------------------------------

CREATE TABLE carte
(
    idCarte       Int Auto_increment NOT NULL,
    nomEntreprise Varchar(255)       NOT NULL,
    titre         Varchar(150)       NOT NULL,
    tel           Varchar(25)        NOT NULL,
    ville         Varchar(255)       NOT NULL,
    imgPres       Varchar(100),
    imgLogo       Varchar(100),
    pdf           Varchar(100),
    nomBtnPdf     Varchar(100),
    couleur1      Varchar(10),
    couleur2      Varchar(10),
    descirptif    Varchar(500),
    LienCommande  Varchar(150),
    idCompte      Int                NOT NULL,
    idTemplate    Int                NOT NULL,
    CONSTRAINT carte_PK PRIMARY KEY (idCarte),
    CONSTRAINT carte_compte_FK FOREIGN KEY (idCompte) REFERENCES compte (idCompte),
    CONSTRAINT carte_template0_FK FOREIGN KEY (idTemplate) REFERENCES template (idTemplate)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: employer
#------------------------------------------------------------

CREATE TABLE employer
(
    idEmp    Int Auto_increment NOT NULL,
    nom      Varchar(100)       NOT NULL,
    prenom   Varchar(100)       NOT NULL,
    fonction Varchar(100)       NOT NULL,
    idCarte  Int,
    CONSTRAINT employer_PK PRIMARY KEY (idEmp),
    CONSTRAINT employer_carte_FK FOREIGN KEY (idCarte) REFERENCES carte (idCarte)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: vue
#------------------------------------------------------------

CREATE TABLE vue
(
    idVue   Int Auto_increment NOT NULL,
    date    Date               NOT NULL,
    idCarte Int                NOT NULL,
    idEmp   Int,
    CONSTRAINT vue_PK PRIMARY KEY (idVue),
    CONSTRAINT vue_carte_FK FOREIGN KEY (idCarte) REFERENCES carte (idCarte),
    CONSTRAINT vue_employer0_FK FOREIGN KEY (idEmp) REFERENCES employer (idEmp)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: reactivation
#------------------------------------------------------------

CREATE TABLE reactivation
(
    idReactivation                  Int Auto_increment NOT NULL,
    codeReactivation                Varchar(32)        NOT NULL UNIQUE,
    dateHeureExpirationReactivation Datetime           NOT NULL DEFAULT DATE_ADD(NOW(), INTERVAL 1 DAY),
    idCompte                        Int                NOT NULL,
    CONSTRAINT reactivation_PK PRIMARY KEY (idReactivation),
    CONSTRAINT reactivation_compte_FK FOREIGN KEY (idCompte) REFERENCES compte (idCompte)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: recuperation
#------------------------------------------------------------

CREATE TABLE recuperation
(
    idRecuperation                  Int Auto_increment NOT NULL,
    codeRecuperation                Varchar(32)        NOT NULL UNIQUE,
    dateHeureExpirationRecuperation Datetime           NOT NULL DEFAULT DATE_ADD(NOW(), INTERVAL 1 DAY),
    idCompte                        Int                NOT NULL,
    CONSTRAINT recuperation_PK PRIMARY KEY (idRecuperation),
    CONSTRAINT recuperation_compte_FK FOREIGN KEY (idCompte) REFERENCES compte (idCompte)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: logs
#------------------------------------------------------------

CREATE TABLE logs
(
    idLog        Int Auto_increment NOT NULL,
    typeAction   Varchar(500)       NOT NULL,
    dateHeureLog Datetime           NOT NULL,
    adresseIPLog Varchar(500)       NOT NULL,
    idCompte     Int                NOT NULL,
    CONSTRAINT logs_PK PRIMARY KEY (idLog),
    CONSTRAINT logs_compte_FK FOREIGN KEY (idCompte) REFERENCES compte (idCompte)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: social
#------------------------------------------------------------

CREATE TABLE social
(
    idSocial Int          NOT NULL,
    nom      Varchar(500) NOT NULL,
    lienLogo Varchar(500) NOT NULL,
    CONSTRAINT social_PK PRIMARY KEY (idSocial)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: rediriger
#------------------------------------------------------------

CREATE TABLE rediriger
(
    idSocial Int          NOT NULL,
    idCarte  Int          NOT NULL,
    lien     Varchar(500),
    CONSTRAINT rediriger_PK PRIMARY KEY (idSocial, idCarte),
    CONSTRAINT rediriger_social_FK FOREIGN KEY (idSocial) REFERENCES social (idSocial),
    CONSTRAINT rediriger_carte0_FK FOREIGN KEY (idCarte) REFERENCES carte (idCarte)
) ENGINE = InnoDB;

