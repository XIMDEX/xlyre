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

ModulesManager::file('/inc/nodetypes/filenode.inc');
ModulesManager::file('/inc/model/XlyreDistribution.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreRelMetaLangs.php', 'xlyre');

class XlyreOpenDistribution extends FileNode {

    const IDNODETYPE = 4002;

    /**
     * Specific delete for the current nodetype.
     * It must delete Distribution.
     * @return  boolean True if delete something.
     */
    public function deleteNode(){
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

        //Delete distribution
        $distribution = new XlyreDistribution($this->nodeID);
        return $distribution->delete();
    }

    /**
     * Create node for the current nodetype.
     * It must create the Distribution.
     */     
    public function createNode($name, $parentID, $nodeTypeID, $stateID=null,$subfolders=array(), $filename) {

        //Adding a new Distribution
        $distribution = new XlyreDistribution();

        //Settings properties for the distribution
        $distribution->set("IdDistribution", $this->nodeID);
        $distribution->set("IdDataset", $parentID);
        $distribution->set("AccessURL", $name);
        $distribution->set("Identifier", $name);
        $distribution->set("Filename", $filename);
        $distribution->set("Issued", time());
        $distribution->set("Modified", time());
        $filenameparts = explode('.', $filename);
        $distribution->set("MediaType", $filenameparts[sizeof($filenameparts)-1]);
        $bs = file_exists($filename) ? filesize($filename) : 0;
        $distribution->set("ByteSize", $bs);
        // $this->setContent(file_get_contents($filename)):

        return $distribution->add();
    }

    /**
     * Update node.
     */
    public function updateNode($idnode, $name, $filename) {
        //Updating the Distribution
        $distribution = new XlyreDistribution($idnode);

        //Settings properties for the distribution.
        $distribution->set("AccessURL", $name);
        $distribution->set("Identifier", $name);
        $distribution->set("Filename", $filename);
        $distribution->set("Issued", time());
        $distribution->set("Modified", time());
        $filenameparts = explode('.', $filename);
        $distribution->set("MediaType", $filenameparts[sizeof($filenameparts)-1]);
        $distribution->set("ByteSize", filesize($filename));

        return $distribution->update();
    }


}

?>