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

