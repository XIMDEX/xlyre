-- xlyre module SQL destructor

-- Deleting New Nodetypes
DELETE FROM NodeTypes where IdNodeType in (4000,4001);
-- DELETE FROM NodeTypes where IdNodeType in (4000,4001,4002,4003,4004);

DELETE FROM SectionTypes where idSectionType=4;

DELETE FROM NodeAllowedContents where NodeType in (4000,4001);
-- DELETE FROM NodeAllowedContents where NodeType in (4000,4001,4002,4003,4004);

-- DELETE FROM Channels where Name='xlyre';

-- DELETE FROM Nodes where Name='xlyre.xml' OR Idnodetype in (4000,4001,4002,4003,4004);

-- DROP TABLE IF EXISTS `Namespaces`;

-- DELETE FROM NodeDefaultContents where IdNodeType in (4000);

DELETE FROM Actions where IdAction in (7500);
-- DELETE FROM Actions where IdAction in (7500,7501,7502,7503);

DELETE FROM RelRolesActions where IdAction in (7500);
-- DELETE FROM RelRolesActions where IdAction in (7500,7501,7502,7503);
