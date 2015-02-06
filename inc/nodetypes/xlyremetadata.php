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
use Ximdex\Modules\Config;

ModulesManager::file('/inc/model/XlyreCatalog.php', 'xlyre');
ModulesManager::file('/inc/nodetypes/structureddocument.php');

class XlyreMetadata extends AbstractStructuredDocument {

    function GetPublishedPath($channelID = NULL, $addNodeName = null) {
        if (!\App::getValue("PublishPathFormat")) {
            return parent::GetPublishedPath($channelID, $addNodeName);
        }
        $path = "";
        $publishPathFormat = \App::getValue("PublishPathFormat");
        $idNode = $this->parent->get('IdNode');
        $structuredDocument = new structureddocument($idNode);
        $idLanguage = $structuredDocument->get("IdLanguage");
        $language = new Language($idLanguage);

        switch ($publishPathFormat) {
            case 'prefix':
                $path = parent::GetPublishedPath($channelID, $addNodeName);
                $path = "/" . $language->get("IsoName") . $path;
                break;
            case 'suffix':
                $path = parent::GetPublishedPath($channelID) . "/" . $language->get("IsoName");
                if ($addNodeName) {
                    $path = $path . "/" . $this->GetPublishedNodeName($channelID);
                }
                # code...
                break;

            default:
                $path = parent::GetPublishedPath($channelID, $addNodeName);
                break;
        }

        return $path;
    }

    function GetPublishedNodeName($idChannel) {
        $channel = new Channel($idChannel);

        $publishableName = "";

        if (in_array(\App::getValue("PublishPathFormat"), array("prefix", "suffix"))) {
            $publishableName = "index" . "." . $channel->get("DefaultExtension");
        } else {
            $publishableName = parent::GetPublishedNodeName($idChannel);
        }

        return $publishableName;
    }

    function getPathToDeep() {
        if (in_array(\App::getValue("PublishPathFormat"), array("prefix", "suffix"))) {
            return 1;
        }
        return 2;
    }

}
