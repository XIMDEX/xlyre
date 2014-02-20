-- xlyre module SQL constructor

-- New Nodetypes
LOCK TABLES `NodeTypes` WRITE;
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4000, 'OpenDataSection', 'xlyreopendatasection', 'catalog_xlyre.png', 'Open Data Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, "xlyre");
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4001, 'OpenDataDataset', 'xlyreopendataset', 'dataset_xlyre.png', 'Open Data Dataset', 1, 1, 1, 1, 0, 0, 0, 0, 1, 0, 0, "xlyre");
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4002, 'OpenDataDistribution', 'xlyreopendistribution', 'binary_file.png', 'Open Data Distribution', 1, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, "xlyre");
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4003, 'OpenDataConfig', 'root', 'modulesconfig.png', 'Xlyre Configuration', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, NULL);
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4004, 'OpenDataSectionMetadata', 'xlyreopendatasectionmetadata', 'catalog_xlyre.png', 'Open Data Section to Publish', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, "xlyre");
 INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4005, 'OpenDataDatasetMetadata', 'xlyreopendatasetmetadata', 'dataset_xlyre.png', 'Open Data Dataset to Publish', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, "xlyre");
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4001, 'OpenDataDatasetSection', 'sectionnode', 'folder_xlyre.png', 'Dataset Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4002, 'OpenDataDataset', 'foldernode', 'folder_xlyre.png', 'Dataset', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4003, 'OpenDataCatalogSection', 'sectionnode', 'folder_xlyre.png', 'Open Data Catalog Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4004, 'OpenDataCatalog', 'foldernode', 'folder_xlyre.png', 'Open Data Catalog', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
UNLOCK TABLES;


-- New Nodes
LOCK TABLES `Nodes` WRITE;
INSERT INTO `Nodes` (`IdNode`, `IdParent`, `IdNodeType`, `Name`, `IdState`, `BlockTime`, `BlockUser`, `CreationDate`, `ModificationDate`, `Description`, `SharedWorkflow`, `Path`) VALUES (14, 2, 4003, 'Xlyre manager', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/Ximdex/Control center');
UNLOCK TABLES;

-- Ximdex Path for Settings
LOCK TABLES `FastTraverse` WRITE;
INSERT INTO FastTraverse VALUES (1,14,2);
INSERT INTO FastTraverse VALUES (2,14,1);
INSERT INTO FastTraverse VALUES (14,14,0);
UNLOCK TABLES;


-- SectionTypes
LOCK TABLES `SectionTypes` WRITE;
INSERT INTO `SectionTypes` ( `idSectionType` , `sectionType` , `idNodeType`  , `module` ) VALUES (3, 'OpenData Section', 4000, 'xlyre');
UNLOCK TABLES;


-- Allowed Contents
LOCK TABLES `NodeAllowedContents` WRITE;
INSERT INTO `NodeAllowedContents` VALUES (NULL,5014,4000,0);
insert into `NodeAllowedContents` VALUES (NULL,5015,4000,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4000,4001,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4000,4004,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4000,5032,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4001,4002,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4001,4005,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4001,5032,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,5002,4003,1);



-- INSERT INTO `NodeAllowedContents` VALUES (NULL,4001,4002,0);
-- INSERT INTO `NodeAllowedContents` VALUES (NULL,4000,4003,0);
-- INSERT INTO `NodeAllowedContents` VALUES (NULL,4003,4004,0);
UNLOCK TABLES;


-- Default Contents
LOCK TABLES `NodeDefaultContents` WRITE;
INSERT INTO `NodeDefaultContents` VALUES (NULL,4000,4001,'Dataset',NULL,NULL);
INSERT INTO `NodeDefaultContents` VALUES (NULL,5002,4003,'Xlyre config',NULL,NULL);
-- INSERT INTO `NodeDefaultContents` VALUES (NULL,4000,4001,'RawData',NULL,NULL);
-- INSERT INTO `NodeDefaultContents` VALUES (NULL,4000,4003,'catalogs',NULL,NULL);
UNLOCK TABLES;


-- This table will be part of Ximdex Core in the future
-- DROP TABLE IF EXISTS `Namespaces`;
-- CREATE TABLE IF NOT EXISTS `Namespaces` (
--  `IdNS` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
--  `Name` varchar(100) NOT NULL,
--  `idParent` int(11) unsigned DEFAULT NULL, 
--  PRIMARY KEY (`IdNS`),
--  UNIQUE KEY `Name` (`Name`),
--  FULLTEXT KEY `Name_2` (`Name`)
-- ) ENGINE=MyISAM  COMMENT='Ximdex Name Spaces' AUTO_INCREMENT=1;
-- Default Rows
-- INSERT INTO `Namespaces` VALUES (NULL,'Xlyre',NULL);


-- Actions
LOCK TABLES `Actions` WRITE;
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7501, 4000 ,"Delete Catalog", "deletecatalog", "delete_section.png","It deletes the current Open Data Catalog", 97, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7502,  4000,"Create New Dataset", "managedataset", "change_next_state.png","It creates a new Dataset", 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7503,  4001,"Edit Dataset", "managedataset", "change_next_state.png","It edits the current Dataset", 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7504,  4001,"Delete Dataset", "deletedataset", "delete_section.png","It deletes the current Dataset", 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7505,  4003,"Configure Xlyre", "configure", "modulesconfig.png","It configures Xlyre module", 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7506,  4000,"Publish Catalog", "publish", "change_next_state.png","It publishes the current Catalog", 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7507,  4001,"Publish Dataset", "publish", "change_next_state.png","It publishes the current Dataset", 99, 'xlyre', 0, NULL);
INSERT INTO `Actions` (`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`) VALUES (7508, 4000, "Modify properties", "manageproperties", "xix.png", "Modify properties", 80);

UNLOCK TABLES;

-- Roles for these actions
LOCK TABLES `RelRolesActions` WRITE;
INSERT INTO RelRolesActions VALUES (NULL,201,7501,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7502,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7503,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7504,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7505,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7506,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7507,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7508,0,1,3);
UNLOCK TABLES;

-- XlyreCatalog Table
DROP TABLE IF EXISTS `XlyreCatalog`;
CREATE TABLE `XlyreCatalog` (
  `IdCatalog` int(11) unsigned NOT NULL COMMENT 'Ximdex NodeId',
  `Identifier` varchar(100) NOT NULL,
  `Theme` varchar(100) NOT NULL DEFAULT '',
  `Issued` int(12) unsigned NOT NULL DEFAULT '0',
  `Modified` int(12) unsigned NOT NULL DEFAULT '0',
  `Publisher` int(12) unsigned NOT NULL DEFAULT '0' COMMENT 'Ximdex User',
  `License` varchar(100) NOT NULL DEFAULT '',
  `Spatial` varchar(100) NOT NULL DEFAULT '',
  `Homepage` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`IdCatalog`),
  UNIQUE KEY `Identifier` (`Identifier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Catalog - XLyre Module';

-- XlyreDataset Table
DROP TABLE IF EXISTS `XlyreDataset`;
CREATE TABLE `XlyreDataset` (
  `IdDataset` int(11) unsigned NOT NULL COMMENT 'Ximdex NodeId',
  `IdCatalog` int(11) unsigned NOT NULL,
  `Identifier` varchar(100) NOT NULL,
  `Theme` int(11) unsigned NOT NULL,
  `Issued` int(12) unsigned NOT NULL DEFAULT '0',
  `Modified` int(12) unsigned NOT NULL DEFAULT '0',
  `Publisher` int(12) unsigned NOT NULL DEFAULT '0' COMMENT 'Ximdex User',
  `Periodicity` int(11) unsigned NOT NULL COMMENT 'Units in Month',
  `License` int(11) unsigned NOT NULL,
  `Spatial` int(11) unsigned NOT NULL,
  `Reference` varchar(255) NOT NULL DEFAULT '' COMMENT 'Reference webpage for more information about the dataset',
  PRIMARY KEY (`IdDataset`),
  UNIQUE KEY `Identifier` (`Identifier`),
  KEY `IdCatalog` (`IdCatalog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Dataset - XLyre Module';

-- XlyreDistribution table
DROP TABLE IF EXISTS `XlyreDistribution`;
CREATE TABLE `XlyreDistribution` (
  `IdDistribution` int(11) unsigned NOT NULL COMMENT 'Ximdex NodeId',
  `IdDataset` int(11) unsigned NOT NULL,
  `AccessURL` varchar(255) NOT NULL DEFAULT '',
  `Identifier` varchar(100) NOT NULL,
  `Filename` varchar(100) NOT NULL COMMENT 'Distribution Filename',
  `Issued` int(12) unsigned NOT NULL DEFAULT '0',
  `Modified` int(12) unsigned NOT NULL DEFAULT '0',
  `MediaType` varchar(50) DEFAULT '',
  `ByteSize` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`IdDistribution`),
  UNIQUE KEY `Identifier` (`Identifier`),
  KEY `IdDataset` (`IdDataset`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Distribution - XLyre Module';

-- XlyreRelMetaLangs Table - it implements I18n for title and description in XLyre objects
DROP TABLE IF EXISTS `XlyreRelMetaLangs`;
CREATE TABLE `XlyreRelMetaLangs` (
  `IdRel` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `IdNode` int(11) unsigned NOT NULL COMMENT 'NodeIds for XLyre elements',
  `IdLanguage` int(11) unsigned NOT NULL COMMENT 'Language Id on Ximdex',
  `Title` varchar(255) DEFAULT '',
  `Description` varchar(500) DEFAULT '',
  PRIMARY KEY (`IdRel`),
  KEY `IdNode` (`IdNode`),
  KEY `IdLanguage` (`IdLanguage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='I18n fields for Catalog, Dataset and Distribution - XLyre Module';

-- XlyreRelMetaTags Table - it implements tags for XLyre objects (datasets)
DROP TABLE IF EXISTS `XlyreRelMetaTags`;
CREATE TABLE `XlyreRelMetaTags` (
  `IdRel` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `IdNode` int(11) unsigned NOT NULL COMMENT 'NodeIds for XLyre elements',
  `IdTag` int(11) unsigned NOT NULL COMMENT 'Tag Id on Ximdex',
  PRIMARY KEY (`IdRel`),
  KEY `IdNode` (`IdNode`),
  KEY `IdTag` (`IdTag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Tags for Dataset (it could also be tags for Catalog and Distribution) - XLyre Module';

-- XlyreThemes Table - it implements themes for XLyre
DROP TABLE IF EXISTS `XlyreThemes`;
CREATE TABLE `XlyreThemes` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Id` (`Id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Themes that can be selected from Dataset UI - XLyre Module';

INSERT INTO `XlyreThemes` VALUES (NULL, 'Ayuda');
INSERT INTO `XlyreThemes` VALUES (NULL, 'Normativa');
INSERT INTO `XlyreThemes` VALUES (NULL, 'Contratación');
INSERT INTO `XlyreThemes` VALUES (NULL, 'Estadísticas');
INSERT INTO `XlyreThemes` VALUES (NULL, 'Información sectorial');
INSERT INTO `XlyreThemes` VALUES (NULL, 'Formación');

-- XlyrePeriodicities Table - it implements periodicities for XLyre
DROP TABLE IF EXISTS `XlyrePeriodicities`;
CREATE TABLE `XlyrePeriodicities` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Id` (`Id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Periodicities that can be selected from Dataset UI - XLyre Module';

INSERT INTO `XlyrePeriodicities` VALUES (NULL, '1');
INSERT INTO `XlyrePeriodicities` VALUES (NULL, '3');
INSERT INTO `XlyrePeriodicities` VALUES (NULL, '6');
INSERT INTO `XlyrePeriodicities` VALUES (NULL, '12');

-- XlyreSpatials Table - it implements spatials for XLyre
DROP TABLE IF EXISTS `XlyreSpatials`;
CREATE TABLE `XlyreSpatials` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Id` (`Id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Spatials that can be selected from Dataset UI - XLyre Module';

INSERT INTO `XlyreSpatials` VALUES (NULL, 'Estatal');
INSERT INTO `XlyreSpatials` VALUES (NULL, 'Autonómico');
INSERT INTO `XlyreSpatials` VALUES (NULL, 'Provincial');
INSERT INTO `XlyreSpatials` VALUES (NULL, 'Local');