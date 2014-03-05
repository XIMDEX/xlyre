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

class XlyreOpenDataDCAT extends AbstractStructuredDocument{

    const IDNODETYPE = 4006;

    function GetPublishedPath($channelID = NULL, $addNodeName=null) {

        if (!Config::exists("PublishPathFormat"))
            return parent::GetPublishedPath($channelID, $addNodeName);
        $path = "";
        $publishPathFormat = Config::getValue("PublishPathFormat");
        $idNode = $this->parent->get('IdNode');
        $structuredDocument = new structureddocument($idNode);
        $idLanguage = $structuredDocument->get("IdLanguage");
        $language = new Language($idLanguage);

        switch ($publishPathFormat) {
            case 'prefix':
                # Continue
            case 'suffix':
                $path = parent::GetPublishedPath($channelID)."/";
                if ($addNodeName)   
                    $path = $path."/".$this->GetPublishedNodeName($channelID);
                break;
            default:
                $path = parent::GetPublishedPath($channelID, $addNodeName);
                break;
        }       
        
        return $path;
    }

    function GetPublishedNodeName($idChannel){

        $channel = new Channel($idChannel);

        $publishableName = "";

        if (Config::exists("PublishPathFormat") && 
            in_array(Config::getValue("PublishPathFormat"), array("prefix","suffix"))){
            $publishableName = "dcat.".$channel->get("DefaultExtension");
        }else{
            $publishableName = parent::GetPublishedNodeName($idChannel);
        }
        
        return $publishableName;
    }

    function getPathToDeep(){
        if (in_array(Config::getValue("PublishPathFormat"), array("prefix","suffix")))
            return 1;
        return 2;
    }

    /**
     * Required method for publishing.
     * Return available channel for DCAT
     * @return  array Channels id
     */
    public function getChannels(){
        //TODO: Change query
        $query = sprintf("SELECT idChannel FROM Channels where name = 'dcat'");

        $this->dbObj->Query($query);

        if ($this->dbObj->numErr) {
            $this->parent->SetError(5);
        }

        $out = NULL;
        while (!$this->dbObj->EOF) {
            $out[] = $this->dbObj->GetValue("idChannel");
            $this->dbObj->Next();
        }

        return $out;
    }

}