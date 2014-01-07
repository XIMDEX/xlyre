-- xlyre module SQL destructor

-- Deleting Xlyre NodeTypes
DELETE FROM NodeTypes where IdNodeType in (4000,4001);

-- Deleting Xlyre SectionType
DELETE FROM SectionTypes where idSectionType=4;

-- Deleting NodeAllowedContents for all Xlyre nodetypes
DELETE FROM NodeAllowedContents where NodeType in (4000,4001);

-- Deleting all Xlyre Nodes
DELETE FROM Nodes where Idnodetype in (4000,4001);

-- Deleting NodeDefaultContents for all Xlyre nodetypes
DELETE FROM NodeDefaultContents where IdNodeType in (4000);

-- Deleting all Xlyre Actions
DELETE FROM Actions where IdAction in (7501,7502,7503,7504);

-- Deleting all RelRolesActions (Permissions) for Xlyre Actions
DELETE FROM RelRolesActions where IdAction in (7501,7502,7503,7504);

-- Deleting all tables and their content related exclusively with Xlyre
DROP TABLE IF EXISTS `XlyreCatalog`;
DROP TABLE IF EXISTS `XlyreDataset`;
DROP TABLE IF EXISTS `XlyreDistribution`;
DROP TABLE IF EXISTS `XlyreRelMetaLangs`;
DROP TABLE IF EXISTS `XlyreRelMetaTags`;

-- DROP TABLE IF EXISTS `Namespaces`;
