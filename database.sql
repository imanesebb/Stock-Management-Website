create table Client(
   Idclient int NOT NULL AUTO_INCREMENT,
   Nomclient varchar(50),
   Adresseclient varchar(100),
   Teleclient varchar(10),
   Emailclient varchar(200),
   PRIMARY KEY (Idclient));

create table CommandeClient(
  Idcommandecli int NOT NULL AUTO_INCREMENT,
  Datecommandecli date ,
  Montantcommandecli double NOT NULL,
  PRIMARY KEY(Idcommandecli)); 

create table Lignecommandeclient(
 Idlignecommandecli int NOT NULL AUTO_INCREMENT,
 Quantitecomposant int NOT NULL,
 PRIMARY KEY(Idlignecommandecli));
 
create table Composant(
 Idcomposant int NOT NULL AUTO_INCREMENT,
 Nomcomposant varchar(20),
 QuantitestockComp int NOT NULL,
 Descriptioncomp varchar(200),
 Seuilalertcomp int NOT NULL,
 Prixunitairecomp double NOT NULL,
 PRIMARY KEY(Idcomposant));

create table Produit(
Idproduit int NOT NULL AUTO_INCREMENT,
Nomproduit varchar(100) NOT NULL,
Descriptionproduit varchar(500),
QuantitestockPro int NOT NULL,
Seuilalertproduit int NOT NULL,
Prixunitaire double NOT NULL,
PRIMARY KEY(Idproduit));

create table Fournisseur(
 Idfournisseur int NOT NULL AUTO_INCREMENT,
 Nomfournisseur varchar(300),
 Adressefournisseur varchar(100),
 Telefournisseur varchar(10),
 Emailfournisseur varchar(200),
 PRIMARY KEY(Idfournisseur));
 
create table Commandefournisseur(
 Idcommandefourni int NOT NULL AUTO_INCREMENT,
 Datecommandefourni date NOT NULL,
 Montantcommadefourni double NOT NULL,
 PRIMARY KEY(Idcommandefourni));
 
create table Lignecommandefourni(
  Idlignecommandefourni int NOT NULL AUTO_INCREMENT,
  Quantiteproduit int NOT NULL,
  PRIMARY KEY(Idlignecommandefourni));


create table  compprod (
idcomp integer NOT null ,
idprod integer NOT NULL,
 PRIMARY KEY(idcomp,idprod));


create table login(
idlogin integer not null AUTO_INCREMENT,
loginusername varchar(30) not null,
loginmdp varchar(50) not null ,
primary key(idlogin));


create table admin(
   Idadmin int NOT NULL AUTO_INCREMENT,
   Nomadmin varchar(50),
   Adresseadmin varchar(100),
   Teleadmin varchar(10),
   Emailadmin varchar(200),
   PRIMARY KEY (Idadmin));

alter table Lignecommandeclient
add Idcomposant int NOT NULL;
alter table Lignecommandeclient
add constraint fk1 foreign key(Idcomposant) references composant(Idcomposant);

alter table Lignecommandeclient
add Idcommandecli int NOT NULL;
alter table Lignecommandeclient
add constraint fk11 foreign key(Idcommandecli) references Commandeclient(Idcommandecli);

alter table Lignecommandefourni
add Idproduit int NOT NULL;
alter table Lignecommandefourni
add constraint fk2 foreign key(Idproduit) references Produit(IdProduit);

alter table Lignecommandefourni
add Idcommandefourni int NOT NULL;
alter table Lignecommandefourni
add constraint fk22 foreign key(Idcommandefourni) references Commandefournisseur(Idcommandefourni);

alter table Commandefournisseur
add Idfournisseur int NOT NULL;
alter table Commandefournisseur
add constraint fk3 foreign key(Idfournisseur) references fournisseur(Idfournisseur);

alter table Produit
add Idfournisseur int NOT NULL;
alter table Produit
add constraint fk4 foreign key(Idfournisseur) references fournisseur(Idfournisseur);

alter table commandeclient
add Idclient int NOT NULL;
alter table commandeclient
add constraint fk5 foreign key(Idclient) references client(Idclient);

ALTER TABLE composant ADD COLUMN image BLOB;

alter table compprod
add constraint fk6 foreign key(Idcomp) references composant(Idcomposant);
alter table compprod
add constraint fk66 foreign key(Idprod) references produit(Idproduit);


alter table client
add idlogin integer not null;
 alter table client
add constraint fk7 foreign key(idlogin) references login(idlogin);

alter table admin
add idlogin integer not null;
 alter table admin
add constraint fk8 foreign key(idlogin) references login(idlogin);


insert into login(idlogin,loginusername,loginmdp)
values(1,'kenipharma','KENI1234'),
(2,'lagrande','20252024'),
(3,'abbouda','pharabb@'),
(4,'nacer','abdnacer222'),
(5,'bahae','1234321'),
(6,'boustane','@boustane@'),
(7,'ghofrane','252002'),
(8,'hayat','pharmaha567'),
(9,'houda','88HOUDA88');


insert into client(idclient,nomclient,adresseclient,teleclient,emailclient,idlogin)
values(1,'kenipharma','rue Saad Zaghloul ang. bd Moulay Slimane - Kénitra',0537307000,'kenipharma@gmail.com',1),
      (2,'La Grande Pharmacie','62 bd Mohamed Diouri Kénitra',0537371046,'lagrandepharmacy@gmail.com',2),
      (3,'Pharmacie Abbouda','159 lotiss. Al Bassatine al Fouarat Kénitra',0537385546,'pharmacyabbouda@gmail.com',3),
      (4,'Pharmacie AbdNacer','5 rue Tanger Kénitra ',0537371217,'pharmacyabderrahmanenacer',4),
      (5,'Pharmacie Al Bahae','88 z.i Bir Rami Kénitra ',0673581007,'Pharmacyalbahae@gmail.com',5),
      (6,'Pharmacie Boustane','hay bir Rami Est Jnane 3 n903 Kénitra',0622067905,'pharmacyalboustane@gmail.com',6),
      (7,'Pharmacie ghofrane','568 hay saknia Wafaa3 Kénitra',0630734370,'pharmacyalghofrane@gmail.com',7),
      (8,'Pharmacie Al Hayat','Bir Rami lotiss Laraichi n162 Kénitra',0537377015,'pharmacyalhayat@gmail.com',8),
      (9,'Pharmacie Al houda','hay bir rami rte d Ecole n323 Kénitra',0637029480,'pharmacyalhouda@gmail.com',9);

INSERT INTO composant (Idcomposant,Nomcomposant,QuantitestockComp,Descriptioncomp,Seuilalertcomp,Prixunitairecomp) VALUES
 ('1', 'Doliprane 1000 mg', '1000', 'le medicament doliprane 1000mg est indiqué en cas de douleurs et/ou fiévre tel que maux de téte, états grippaux, douleurs dentaires, courbatures, régles douloureuses', '10', '5.65'),
 (2,'Biafine',1000,'Le m dicament Biafine est indiqu  pour le traitement des br lures, des plaies superficielles non infect es et des rougeurs apr s radioth rapie.',10,5.65),
(3,'Donormyl 15mg',500,'Donormyl 15mg comprim  pellicul  s cable est un m dicament indiqu  dans l insomnie occasionnelle chez l adulte',10,2.8),
(4,'Doxylamine 15mg',600,'Le m dicament Doxylamine Biogaran est indiqu  dans linsomnie occasionnelle chez l adulte.',10,1.6),
(5,'Gaviscon',1000,'Le m dicament Gaviscon est indiqu  dans le traitement du reflux gastro-oesophagien qui se traduit par des br lures d estomac (pyrosis), des remont es ou renvois acides et des aigreurs d estomac',10,4.99),
(6,'Nicopass 1,5mg',700,'Ce m dicament est indiqu  dans le traitement de la d pendance tabagique afin de soulager les sympt mes du sevrage nicotinique chez les sujets d pendants   la nicotine',10,9.26),
(7,'Titanoreine',1000,'Cette cr me   base de lidoca ne est pr conis e pour le traitement des pouss es h morro daires et notamment des sympt mes li s   ces crises comme les douleurs, les prurits et les sensations congestives',10,4.36),
(8,'Daflon 1000mg',800,'Daflon 1000mg comprim  est pr conis  dans le traitement des signes fonctionnels li s   la crise h morro daire',10,12.15),
(9,'Emoflon pommade',1000,'La pommade Emoflon soulage les h morro des',10,6.05),
(10,'Hom oplasmine',900,'Homeoplasmine de Boiron est un m dicament hom opatique utilis  dans le traitement dappoint pour les irritations et les rougeurs de la peau',10,6.2);




insert into fournisseur values(1,'FIBERFIL','Via Serio 6724020 Casnigo - Italie','0503574186','febirfelitali@gmail.com'),
                    (2,'CARMEUSE','5300 Andenne - Belgique','0585830111','carmeusebelqique@gmail.com'),
                                         (3,'TECHNIC','4240 La Chapelle Sur Erdre - France','0524072800','technicprod@gmail.com'),
                                        (4,'MCA SALVAGE SALES','34000 Montpellier - France','0546713914','Mcasalvagesales@gmail.com'),
                                          (5,'KRONCHEM','Agadir 80000, Morocco','0662441105','kronchem@gmail.com'),
                                    (6,'Sepca','65 Rue Nationale Centre Ville, Casablanca, Casablanca 20100','0523325604','sodipiaplastique@gmail.com|'),
                              (7,'ATLAS MATÉRIAUX','bd Mohammed VI, Km 9,10020430 Casablanca','0522385666','atlasmateriaux@gmail.com');






INSERT INTO `commandeclient` (`Idcommandecli`, `Datecommandecli`, `Montantcommandecli`, `Idclient`) VALUES ('1', '2024-01-05', '400', '1');
INSERT INTO `commandeclient` (`Idcommandecli`, `Datecommandecli`, `Montantcommandecli`, `Idclient`) VALUES ('2', '2024-01-03', '500', '1');
INSERT INTO `commandeclient` (`Idcommandecli`, `Datecommandecli`, `Montantcommandecli`, `Idclient`) VALUES ('3', '2024-01-07', '543', '1');            
INSERT INTO `commandeclient` (`Idcommandecli`, `Datecommandecli`, `Montantcommandecli`, `Idclient`) VALUES ('4', '2024-01-12', '600', '2');
INSERT INTO `commandeclient` (`Idcommandecli`, `Datecommandecli`, `Montantcommandecli`, `Idclient`) VALUES ('5', '2024-01-17', '589', '2');
INSERT INTO `commandeclient` (`Idcommandecli`, `Datecommandecli`, `Montantcommandecli`, `Idclient`) VALUES ('6', '2024-01-22', '488', '2');






INSERT INTO `produit` (`Idproduit`, `Nomproduit`, `Descriptionproduit`, `QuantitestockPro`, `Seuilalertproduit`, `Prixunitaire`, `Idfournisseur`) VALUES 
('1', 'Amidon de maïs', ' Agit comme agent de charge et de liant, donnant au comprimé sa forme et sa consistance', '1000', '10', '20', '1'),
('2', 'Cellulose ','Un autre agent de charge et de désintégration qui aide le comprimé à se désagréger dans l\'estomac', '1000', '10', '40', '1'),
('3', 'Stéarate ','C\'est un lubrifiant qui facilite la fabrication et la prise du comprimé.', '1000', '10', '30', '1'),
('4', 'Gélatine','C\'est un gélifiant utilisé dans l\'enrobage du comprimé', '1000', '10', '20', '1'),


(5,'Paraffine Liquide','agit comme un émollient pour adoucir et apaiser la peau.',1000,10,11,2),
(6,'Lanoline','procure des propriétés hydratantes et aide à l’hydratation de la peau.',1000,10,20,2),
(7,'Cire dabeille',' contribue aux propriétés émulsifiantes de la formulation et fournit une barrière protectrice sur la peau.',1000,10,12,2),


(8,'Doxylamine',' un antihistaminique de première génération qui est utilisé comme sédatif',1000,10,11,3),
(9,'Lactose',' un sucre naturel présent dans le lait',1000,10,20,3),
(10,'Stéarate magnésium','un sel de magnésium de lacide stéarique',1000,10,25,3),
(12,'Amidon de maïs',' un polysaccharide dérivé du maïs.',1000,10,10,3),

(13,'nicotine','La nicotine est un alcaloïde toxique issu principalement de la plante de tabac',1000,10,11,2),
(14,'isolmat','un édulcorant (polyol) employé en alimentation humaine ',1000,10,22,2),
(15,'hypromellose',' un éther de cellulose, inerte, viscoélastique utilisé comme collyre en goutte pour les yeux,',1000,10,25,2),
(16,'aspartam','édulcorant artificiel faible en calories dont le pouvoir sucrant est environ 200 fois supérieur à celui du sucre',1000,10,30,2),

(17,'Propy glycol','un diol de formule chimique CH₃–CHOH–CH₂OH ayant de nombreux usages',1000,10,11,3),
(18,'Oxyde Zinc',' un composé chimique utilisé dans divers médicaments comme anti-inflammatoires',1000,10,20,3),
(19,'Dioxyde Titane','un pigment blanc utilisé comme colorant dans de nombreux médicaments.',1000,10,25,3),
(20,'Para Méthyle',' un conservateur chimique utilisé dans de nombreux médicaments',1000,10,10,3),

(21,'Diosmine','un flavonoïde naturel dérivé de la plante Citrus aurantium',1000,10,21,4),
(22,'Hespéridine','un flavonoïde trouvé dans les agrumes,en particulier dans les écorces de citron et dOrange.',1000,10,20,4),
(23,'Héparine','un anticoagulant qui agit en empêchant la coagulation du sang. ',1000,10,25,4),
(24,'Dexpanthénol','forme de vitamine B5 qui est utilisée pour ses propriétés hydratantes et apaisantes',1000,10,16,4),
(25,'Allantoïne','un composé organique présent dans de nombreuses plantes',1000,10,11,4),

(26,'Benzocaïne','un anesthésique local qui agit en engourdissant la peau',1000,10,20,5),
(27,'Bryonia','une plante médicinale qui a des effets anti-inflammatoires et analgésiques',1000,10,25,5),
(28,'Calendula','une plante aux propriétés apaisantes et cicatrisantes',1000,10,10,5),
(29,'Saponaria ',' une plante qui contient des saponines, des composés aux propriétés nettoyantes et émollientes',1000,10,30,5);


insert into compprod(idcomp,idprod) values
(1,1),
(1,2),
(1,3),
(1,4);

INSERT INTO `commandefournisseur` (`Idcommandefourni`, `Datecommandefourni`, `Montantcommadefourni`, `Idfournisseur`) VALUES ('1', '2023-12-10', '400', '1');
INSERT INTO `commandefournisseur` (`Idcommandefourni`, `Datecommandefourni`, `Montantcommadefourni`, `Idfournisseur`) VALUES ('2', '2023-12-09', '500', '2');
INSERT INTO `commandefournisseur` (`Idcommandefourni`, `Datecommandefourni`, `Montantcommadefourni`, `Idfournisseur`) VALUES ('3', '2023-11-25', '499', '3');
INSERT INTO `commandefournisseur` (`Idcommandefourni`, `Datecommandefourni`, `Montantcommadefourni`, `Idfournisseur`) VALUES ('4', '2023-10-27', '557', '4');
INSERT INTO `commandefournisseur` (`Idcommandefourni`, `Datecommandefourni`, `Montantcommadefourni`, `Idfournisseur`) VALUES ('5', '2023-11-20', '389', '5');




INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('1', '30', '1', '1');
INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('2', '45', '2', '1');
INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('3', '12', '8', '1');

INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('4', '47', '6', '2'), ('5', '7', '9', '2'), ('6', '35', '5', '2');

INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('7', '100', '8', '3'), ('8', '50', '7', '3'), ('9', '25', '9', '3'), ('10', '30', '5', '3');

INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('11', '20', '10', '4'), ('12', '20', '8', '4'), ('13', '20', '5', '4');

INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('14', '30', '2', '5'), ('15', '50', '3', '5'), ('16', '20', '5', '5'), ('17', '30', '6', '5');

INSERT INTO `lignecommandeclient` (`Idlignecommandecli`, `Quantitecomposant`, `Idcomposant`, `Idcommandecli`) VALUES ('18', '100', '3', '6'), ('19', '100', '9', '6');





INSERT INTO `lignecommandefourni` (`Idlignecommandefourni`, `Quantiteproduit`, `Idproduit`, `Idcommandefourni`) VALUES ('1', '500', '25', '1'), ('2', '500', '1', '1'), ('3', '500', '16', '1'), ('4', '500', '26', '1'), ('5', '500', '27', '1');

INSERT INTO `lignecommandefourni` (`Idlignecommandefourni`, `Quantiteproduit`, `Idproduit`, `Idcommandefourni`) VALUES ('6', '500', '28', '2'), ('7', '500', '2', '2'), ('8', '500', '7', '2'), ('9', '500', '24', '2'), ('10', '500', '21', '2');
INSERT INTO `lignecommandefourni` (`Idlignecommandefourni`, `Quantiteproduit`, `Idproduit`, `Idcommandefourni`) VALUES ('11', '500', '21', '3'), ('12', '500', '19', '3'), ('13', '500', '8', '3'), ('14', '500', '4', '3'), ('15', '500', '22', '3');
INSERT INTO `lignecommandefourni` (`Idlignecommandefourni`, `Quantiteproduit`, `Idproduit`, `Idcommandefourni`) VALUES ('16', '500', '15', '4'), ('17', '500', '23', '4'), ('18', '500', '14', '4'), ('19', '500', '6', '4'), ('20', '500', '13', '4');
INSERT INTO `lignecommandefourni` (`Idlignecommandefourni`, `Quantiteproduit`, `Idproduit`, `Idcommandefourni`) VALUES ('21', '500', '18', '5'), ('22', '500', '5', '5'), ('23', '500', '20', '5'), ('24', '500', '17', '5'), ('25', '500', '29', '5'), ('26', '500', '3', '5'), ('27', '500', '10', '5');



