-- xlyre module SQL constructor

-- New Nodetypes
INSERT INTO `NodeTypes` (`IdNodeType`, `Name`, `Class`, `Icon`, `Description`, `IsRenderizable`, `HasFSEntity`, `CanAttachGroups`, `IsSection`, `IsFolder`, `IsVirtualFolder`, `IsPlainFile`, `IsStructuredDocument`, `IsPublicable`, `CanDenyDeletion`, `System`, `Module`) VALUES (4000, 'OpenDataSection', 'sectionnode', 'folder_xlyre.png', 'Open Data Section', 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, NULL);
INSERT INTO `SectionTypes` ( `idSectionType` , `sectionType` , `idNodeType`  , `module` ) VALUES (4, 'OpenData Section', 4000, 'xlyre');
INSERT INTO `NodeAllowedContents` VALUES (NULL,5014,4000,0);
INSERT INTO `Channels` (`IdChannel`,`Name`,`Description`,`DefaultExtension`,`Format`,`Filter`,`RenderMode`) VALUES (NULL,'xlyre','OpenData channel','xml',NULL,NULL,'ximdex');
