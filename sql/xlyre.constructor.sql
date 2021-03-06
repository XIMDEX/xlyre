-- xlyre module SQL constructor

-- New Nodetypes
LOCK TABLES `NodeTypes` WRITE;
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4000, 'OpenDataSection', 'xlyreopendatasection', 'catalog_xlyre.png', 'Open Data Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, 'xlyre');
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4001, 'OpenDataDataset', 'xlyreopendataset', 'dataset_xlyre.png', 'Open Data Dataset', 1, 1, 1, 1, 0, 0, 0, 0, 1, 0, 0, 'xlyre');
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`, `IsHidden`) VALUES (4002, 'OpenDataDistribution', 'xlyreopendistribution', 'binary_file.png', 'Open Data Distribution', 1, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 'xlyre', 1);
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4003, 'OpenDataConfig', 'root', 'modulesconfig.png', 'Xlyre Configuration', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, NULL);
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`, `IsHidden`) VALUES (4004, 'OpenDataSectionMetadata', 'xlyreopendatasectionmetadata', 'catalog_xlyre.png', 'Open Data Section to Publish', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 'xlyre', 1);
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`, `IsHidden`) VALUES (4005, 'OpenDataDatasetMetadata', 'xlyreopendatasetmetadata', 'dataset_xlyre.png', 'Open Data Dataset to Publish', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 'xlyre', 1);
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`, `IsHidden`) VALUES (4006, 'OpenDataDCAT', 'xlyreopendatadcat', 'binary_file.png', 'Open Data DCAT', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 'xlyre', 1);
UNLOCK TABLES;


-- New Nodes
LOCK TABLES `Nodes` WRITE;
INSERT INTO `Nodes` (`IdNode`, `IdParent`, `IdNodeType`, `Name`, `IdState`, `BlockTime`, `BlockUser`, `CreationDate`, `ModificationDate`, `Description`, `SharedWorkflow`, `Path`) VALUES (11000, 2, 4003, 'Xlyre manager', 7, NULL, NULL, NULL, NULL, NULL, NULL, '/Ximdex/Control center');
UNLOCK TABLES;

-- Ximdex Path for Settings
LOCK TABLES `FastTraverse` WRITE;
INSERT INTO FastTraverse VALUES (1, 11000, 2);
INSERT INTO FastTraverse VALUES (2, 11000, 1);
INSERT INTO FastTraverse VALUES (11000, 11000, 0);
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
INSERT INTO `NodeAllowedContents` VALUES (NULL,4000,4006,1);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4001,4002,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4001,4005,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,5002,4003,1);
UNLOCK TABLES;

-- Default Contents
LOCK TABLES `NodeDefaultContents` WRITE;
INSERT INTO `NodeDefaultContents` VALUES (NULL,4000,4001,'Dataset',NULL,NULL);
INSERT INTO `NodeDefaultContents` VALUES (NULL,5002,4003,'Xlyre config',NULL,NULL);
UNLOCK TABLES;

-- Actions
LOCK TABLES `Actions` WRITE;
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7701, 4000 ,'Delete Catalog', 'deletecatalog', 'delete_section.png','It deletes the current Open Data Catalog', 97, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7702,  4000,'Create New Dataset', 'managedataset', 'change_next_state.png','It creates a new Dataset', 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7703,  4001,'Edit Dataset', 'managedataset', 'change_next_state.png','It edits the current Dataset', 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7704,  4001,'Delete Dataset', 'deletedataset', 'delete_section.png','It deletes the current Dataset', 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7705,  4003,'Configure Xlyre', 'configure', 'modulesconfig.png','It configures Xlyre module', 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7706,  4000,'Publish Catalog', 'publish', 'change_next_state.png','It publishes the current Catalog', 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7707,  4001,'Publish Dataset', 'publish', 'change_next_state.png','It publishes the current Dataset', 99, 'xlyre', 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7708, 4000, 'Modify properties', 'manageproperties', 'xix.png', 'Modify properties', 80,'',0,NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7709, 4001, 'Semantic Tags', 'setmetadata', 'change_next_state.png', 'Managing semantic tags related to the current node.',95,'ximTAGS',0,NULL);

UNLOCK TABLES;

-- Roles for these actions
-- TODO: bug with server state = 7
LOCK TABLES `RelRolesActions` WRITE;
INSERT INTO RelRolesActions VALUES (NULL,201,7701,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7702,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7703,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7704,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7705,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7706,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7707,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7708,7,1,NULL);
INSERT INTO RelRolesActions VALUES (NULL,201,7709,7,1,NULL);
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
  `Homepage` varchar(255) DEFAULT '',
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
  `Reference` varchar(255) DEFAULT '' COMMENT 'Reference webpage for more information about the dataset',
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

INSERT INTO `XlyreThemes` VALUES (NULL, 'Foundings');
INSERT INTO `XlyreThemes` VALUES (NULL, 'Laws');
INSERT INTO `XlyreThemes` VALUES (NULL, 'Budgets');

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

INSERT INTO `XlyreSpatials` VALUES (NULL, 'International');
INSERT INTO `XlyreSpatials` VALUES (NULL, 'National');
INSERT INTO `XlyreSpatials` VALUES (NULL, 'Regional');
INSERT INTO `XlyreSpatials` VALUES (NULL, 'Local');
