-- xlyre module SQL constructor

-- New Nodetypes
LOCK TABLES `NodeTypes` WRITE;
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4000, 'OpenDataSection', 'sectionnode', 'folder_xlyre.png', 'Open Data Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4001, 'OpenDataDataset', 'foldernode', 'folder_xlyre.png', 'Dataset', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4001, 'OpenDataDatasetSection', 'sectionnode', 'folder_xlyre.png', 'Dataset Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4002, 'OpenDataDataset', 'foldernode', 'folder_xlyre.png', 'Dataset', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4003, 'OpenDataCatalogSection', 'sectionnode', 'folder_xlyre.png', 'Open Data Catalog Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
-- INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4004, 'OpenDataCatalog', 'foldernode', 'folder_xlyre.png', 'Open Data Catalog', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
UNLOCK TABLES;


-- SectionTypes
LOCK TABLES `SectionTypes` WRITE;
INSERT INTO `SectionTypes` ( `idSectionType` , `sectionType` , `idNodeType`  , `module` ) VALUES (4, 'OpenData Section', 4000, 'xlyre');
UNLOCK TABLES;


-- Allowed Contents
LOCK TABLES `NodeAllowedContents` WRITE;
INSERT INTO `NodeAllowedContents` VALUES (NULL,5014,4000,0);
INSERT INTO `NodeAllowedContents` VALUES (NULL,4000,4001,0);
-- INSERT INTO `NodeAllowedContents` VALUES (NULL,4001,4002,0);
-- INSERT INTO `NodeAllowedContents` VALUES (NULL,4000,4003,0);
-- INSERT INTO `NodeAllowedContents` VALUES (NULL,4003,4004,0);
UNLOCK TABLES;


-- Default Contents
LOCK TABLES `NodeDefaultContents` WRITE;
INSERT INTO `NodeDefaultContents` VALUES (NULL,4000,4001,'Dataset',NULL,NULL);
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
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7501, 4000 ,"Create New Dataset", "addfoldernode", "change_next_state.png","Creates a new Dataset", 99, NULL, 0, NULL);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7502, 4000 ,"Delete Catalog", "deletenode", "delete_section.png","Deletes the current Open Data Catalog", 97, NULL, 0, NULL);
-- INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7501, 4001 ,"Create New Dataset", "addfoldernode", "change_next_state.png","", 99, NULL, 0, NULL);
-- INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7502, 4001 ,"Upload a new dataset", "fileupload", "change_next_state.png","", 99, NULL, 0, NULL);
-- INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`,`Multiple`, `Params`) VALUES ( 7503, 4003 ,"Create New Catalog", "addfoldernode", "change_next_state.png","", 99, NULL, 0, NULL);
UNLOCK TABLES;

-- Roles for these actions
LOCK TABLES `RelRolesActions` WRITE;
INSERT INTO RelRolesActions VALUES (NULL,201,7501,0,1,3);
INSERT INTO RelRolesActions VALUES (NULL,201,7502,0,1,3);
-- INSERT INTO RelRolesActions VALUES (NULL,201,7503,0,1,3);
UNLOCK TABLES;

-- XlyreCatalog Table
DROP TABLE IF EXISTS `XlyreCatalog`;
CREATE TABLE `XlyreCatalog` (
  `IdCatalog` int(11) unsigned NOT NULL COMMENT 'Ximdex NodeId',
  `Identifier` varchar(100) NOT NULL,
  `Theme` varchar(100) NOT NULL DEFAULT '',
  `Issued` int(12) unsigned DEFAULT '0',
  `Modified` int(12) unsigned DEFAULT '0',
  `Publisher` int(12) unsigned NOT NULL DEFAULT '0' COMMENT 'Ximdex User',
  `License` varchar(100) NOT nULL DEFAULT '',
  `Spatial` varchar(100) NOT NULL DEFAULT '',
  `Homepage` varchar(255) NOT NULL DEFAULT 'http://www.example.com',
  PRIMARY KEY (`IdCatalog`),
  UNIQUE KEY `Identifier` (`Identifier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Catalog - XLyre Module';

-- XlyreDataset Table
DROP TABLE IF EXISTS `XlyreDataset`;
CREATE TABLE `XlyreDataset` (
  `IdDataset` int(11) unsigned NOT NULL COMMENT 'Ximdex NodeId',
  `IdCatalog` int(11) unsigned NOT NULL,
  `Identifier` varchar(100) NOT NULL,
  `Theme` varchar(100) NOT NULL DEFAULT '',
  `Issued` int(12) unsigned DEFAULT '0',
  `Modified` int(12) unsigned DEFAULT '0',
  `Publisher` int(12) unsigned NOT NULL DEFAULT '0' COMMENT 'Ximdex User',
  `Periodicity` mediumint(6) unsigned NOT NULL DEFAULT '12' COMMENT 'Units in Month',
  `License` varchar(100) NOT nULL DEFAULT '',
  `Spatial` varchar(100) NOT NULL DEFAULT '',
  `Reference` varchar(255) NOT NULL DEFAULT 'http://www.example.com' COMMENT 'Reference webpage for more information about the dataset',
  PRIMARY KEY (`IdDataset`),
  UNIQUE KEY `Identifier` (`Identifier`),
  KEY `IdCatalog` (`IdCatalog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Dataset - XLyre Module';

-- XlyreDistribution table
DROP TABLE IF EXISTS `XlyreDistribution`;
CREATE TABLE `XlyreDistribution` (
  `IdDistribution` int(11) unsigned NOT NULL COMMENT 'Ximdex NodeId',
  `IdDataset` int(11) unsigned NOT NULL,
  `AccessURL` varchar(255) NOT NULL DEFAULT 'http://example.com',
  `Identifier` varchar(255) NOT NULL,
  `Filename` varchar(100) NOT NULL COMMENT 'Distribution Filename',
  `Issued` int(12) unsigned DEFAULT '0',
  `Modified` int(12) unsigned DEFAULT '0',
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

