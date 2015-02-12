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
ModulesManager::file('/inc/model/XlyreDataset.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreRelMetaLangs.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreRelMetaTags.php', 'xlyre');

class XlyreOpenDataSet extends FolderNode {

    const IDNODETYPE = 4001;

    /**
     * Specific delete for the current nodetype.
     * It must delete Dataset.	 
     * @return  boolean True if delete something.
     */
    public function deleteNode() {
        parent::deleteNode();

        //Delete Langs
        $relMetaLang = new XlyreRelMetaLangs();
        $results = $relMetaLang->find("IdRel", "IdNode=%s", array($this->nodeID), MONO);
        if ($results) {
            foreach ($results as $idRel) {
                $objectToDelete = new XlyreRelMetaLangs($idRel);
                $objectToDelete->delete();
            }
        }

        //Delete dataset
        $dataset = new XlyreDataset($this->nodeID);
        return $dataset->delete();
    }

    /**
     * Create node for the current nodetype.
     * It must create the Dataset.
     */
    public function createNode($name, $parentID, $nodeTypeID, $stateID = null, $subfolders = array(), $theme, $periodicity, $license, $spatial, $reference) {

        //Adding a new Dataset
        $dataset = new XlyreDataset();

        //Settings properties for the dataset.
        $dataset->set("IdDataset", $this->nodeID);
        $dataset->set("IdCatalog", $parentID);
        $dataset->set("Identifier", $name);
        $dataset->set("Theme", $theme);
        $dataset->set("License", $license);
        $dataset->set("Issued", time());
        $dataset->set("Modified", time());
        $dataset->set("Publisher", \Ximdex\Utils\Session::get('userID'));
        $dataset->set("Periodicity", $periodicity);
        $dataset->set("Spatial", $spatial);
        $dataset->set("Reference", $reference);

        return $dataset->add();
    }

    /**
     * Update node.
     */
    public function updateNode($idnode, $name, $theme, $periodicity, $license, $spatial, $reference) {
        //Updating the Dataset
        $dataset = new XlyreDataset($idnode);

        //Settings properties for the dataset.
        $dataset->set("Identifier", $name);
        $dataset->set("Theme", $theme);
        $dataset->set("License", $license);
        $dataset->set("Modified", time());
        $dataset->set("Publisher", \Ximdex\Utils\Session::get('userID'));
        $dataset->set("Periodicity", $periodicity);
        $dataset->set("Spatial", $spatial);
        $dataset->set("Reference", $reference);

        return $dataset->update();
    }

    /**
     * Required method for publishing.
     * Return the full name for the current node when is published.
     * @param int $channel Channel id
     */
    function GetPublishedNodeName($channel = null) {

        $fileName = $this->parent->GetNodeName();

        return $fileName;
    }

    public function GetPublishedPath($channeId, $addFolder) {
        return "/CatalogoOpenData";
    }

    /**
     * Required method for publishing.
     * Return available channels for the current node.
     * @return  array Channels id
     */
    public function getChannels() {
        //TODO: Change query
        $query = sprintf("SELECT idChannel FROM Channels limit 1");

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

    public static function buildDatasetXml($idDataset) {
        $ok = false;
        $dataset = new XlyreDataset($idDataset);
        $xlrml = new XlyreRelMetaLangs();
        $i18n_dataset_values = $xlrml->find('IdLanguage', "IdNode = %s", array($idDataset), MONO);
        foreach ($i18n_dataset_values as $i18n_value) {
            $language = new Language($i18n_value);
            $nodename = $dataset->get('Identifier');
            $nodename_search = $nodename . "-id" . $language->get("IsoName");
            unset($language);
            $node = new Node();
            $result = $node->find('IdNode', "IdParent = %s && IdNodeType = %s && Name = %s", array($idDataset, XlyreOpenDataSetMetadata::IDNODETYPE, $nodename_search), MONO);
            unset($node);
            if ($result) {
                #Update
                $node = new Node($result[0]);
                $node->update();
                $node->setContent($dataset->ToXml($i18n_value));
                $ok = true;
            } else {
                #Create
                $ch = new Channel();
                $html_ch = $ch->find('IdChannel', "name = %s", array('html'), MONO);
                $nt = new NodeType(XlyreOpenDataSetMetadata::IDNODETYPE);
                $node_search = new Node();
                $template_val = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s", array("rng-dataset.xml", \Ximdex\Services\NodeType::RNG_VISUAL_TEMPLATE), MONO);
                if ($template_val) {
                    $data = array(
                        'NODETYPENAME' => $nt->get('Name'),
                        'NAME' => $nodename_search,
                        'PARENTID' => $idDataset,
                        'FORCENEW' => true,
                        "CHILDRENS" => array(
                            array("NODETYPENAME" => "VISUALTEMPLATE", "ID" => $template_val[0]),
                            array("NODETYPENAME" => "CHANNEL", "ID" => $html_ch[0]),
                            array("NODETYPENAME" => "LANGUAGE", "ID" => $i18n_value),
                        )
                    );
                    $nodetopublish = new XlyreBaseIO();
                    $nodeid = $nodetopublish->build($data);
                    if ($nodeid) {
                        $node = new Node($nodeid);
                        $node->setContent($dataset->ToXml($i18n_value));
                        $ok = true;
                    } else {
                        XMD_Log::error("not valid node id");
                        #do something when it fails
                        $ok = false;
                    }
                } else {
                    XMD_Log::error("not valid template");
                    $ok = false;
                }
            }
        }
        return $ok;
    }

}
