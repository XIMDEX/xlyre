-- xlyre module SQL destructor

-- Deleting Xlyre NodeTypes
DELETE FROM NodeTypes where IdNodeType in (4000,4001,4002,4003,4004,4005,4006);

-- Deleting Xlyre Nodes
DELETE FROM Nodes where IdNode in (11000);

-- Deleting Xlyre FastTraverse
DELETE FROM FastTraverse where IdChild in (11000);

-- Deleting Xlyre SectionType
DELETE FROM SectionTypes where idSectionType=3;

-- Deleting NodeAllowedContents for all Xlyre nodetypes
DELETE FROM NodeAllowedContents where NodeType in (4000,4001,4002,4003);
DELETE FROM NodeAllowedContents where IdNodeType in (4000,4001);

-- Deleting all Xlyre Nodes
DELETE FROM Nodes where Idnodetype in (4000,4001,4002,4003,4004,4005,4006);

-- Deleting NodeDefaultContents for all Xlyre nodetypes
DELETE FROM NodeDefaultContents where IdNodeType in (4000);
DELETE FROM NodeDefaultContents where NodeType in (4003);

-- Deleting all Xlyre Actions
DELETE FROM Actions where IdAction in (7701,7702,7703,7704,7705,7706,7707,7708,7709);

-- Deleting all RelRolesActions (Permissions) for Xlyre Actions
DELETE FROM RelRolesActions where IdAction in (7701,7702,7703,7704,7705,7706,7707,7708,7709);

-- Deleting all tables and their content related exclusively with Xlyre
DROP TABLE IF EXISTS `XlyreCatalog`;
DROP TABLE IF EXISTS `XlyreDataset`;
DROP TABLE IF EXISTS `XlyreDistribution`;
DROP TABLE IF EXISTS `XlyreRelMetaLangs`;
DROP TABLE IF EXISTS `XlyreRelMetaTags`;
DROP TABLE IF EXISTS `XlyreThemes`;
DROP TABLE IF EXISTS `XlyrePeriodicities`;
DROP TABLE IF EXISTS `XlyreSpatials`;

