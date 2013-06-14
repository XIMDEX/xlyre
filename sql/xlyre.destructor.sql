-- xlyre module SQL constructor

-- Deleting New Nodetypes
DELETE FROM NodeTypes where IdNodeType=4000;
DELETE FROM SectionTypes where idSectionType=4;
DELETE FROM NodeAllowedContents where NodeType=4000;
DELETE FROM Channels where Name='xlyre';
