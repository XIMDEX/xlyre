<?php
/**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 */

ModulesManager::file('/inc/model/XlyreCatalog.php', 'xlyre');
ModulesManager::file('/inc/nodetypes/structureddocument.inc');

class XlyreOpenDataSetMetadata extends AbstractStructuredDocument{

	const IDNODETYPE=4005;

	function GetPublishedPath($channelID = NULL, $addNodeName) {

		$path = parent::GetPublishedPath();
		$idNode = $this->parent->get('IdNode');
		$structuredDocument = new structureddocument($idNode);
		$idLanguage = $structuredDocument->get("IdLanguage");
		$language = new Language($idLanguage);
		return "/".$language->get("IsoName").$path;
	}

	function GetPublishedNodeName($idChannel){

		// Default name for clean urls
        $nodename = "index";
        
        $channel = new Channel($idChannel);
        
        // $posLastMinusCharacter = strrpos($this->parent->getNodeName(), "-");
        // $publishableName = substr($this->parent->getNodeName(), 0, $posLastMinusCharacter);
        // return $publishableName.".".$channel->get("DefaultExtension");
        return $nodename.".".$channel->get("DefaultExtension");
	}

	function getPathToDeep(){
		if (in_array(Config::getValue("PublishPathFormat"), array("prefix","suffix")))
			return 1;
		return 2;
	}
}