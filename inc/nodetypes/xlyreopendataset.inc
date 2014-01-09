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

class XlyreOpenDataSet extends FolderNode{

	const IDNODETYPE = 4001;

	/**
	 * Specific delete for the current nodetype.
	 * It must delete Dataset.	 
	 * @return  boolean True if delete something.
	 */
	public function deleteNode(){
		parent::deleteNode();

		//Delete Langs
		$relMetaLang = new XlyreRelMetaLangs();
		$results = $relMetaLang->find("IdRel", "IdNode=%s", array($this->nodeID), MONO);
		foreach ($results as $idRel) {
			$objectToDelete = new XlyreRelMetaLangs($idRel);
			$objectToDelete->delete();
		}

		//Delete Tags
		$relMetaLang = new XlyreRelMetaTags();
		$results = $relMetaLang->find("IdRel", "IdNode=%s", array($this->nodeID), MONO);
		foreach ($results as $idRel) {
			$objectToDelete = new XlyreRelMetaTags($idRel);
			$objectToDelete->delete();
		}

		//Delete dataset
		$dataset = new XlyreDataset($this->nodeID);
		return $dataset->delete();
	}

	/**
	 * Create node for the current nodetype.
	 * It must create the Dataset.
	 */		
	public function createNode($name, $parentID, $nodeTypeID, $stateID=null,$subfolders=array(), $theme, $periodicity, $license, $spatial, $reference) {

		//Adding a new Dataset
		$dataset = new XlyreDataset();

		//Settings properties for the catalog.
		$dataset->set("IdDataset", $this->nodeID);
		$dataset->set("IdCatalog", $parentID);
		$dataset->set("Identifier", $name);
		$dataset->set("Theme", $theme);
		$dataset->set("License", $license);
		$dataset->set("Issued", time());
		$dataset->set("Modified", time());
		$dataset->set("Publisher", XSession::get('userID'));
		$dataset->set("Periodicity", $periodicity);
		$dataset->set("Spatial", $spatial);
		$dataset->set("Reference", $reference);

		return $dataset->add();
	}

	/**
	 * Update node.
	 */
	public function updateNode($idnode, $name, $theme, $periodicity, $license, $spatial, $reference) {
		//Adding a new Dataset
		$dataset = new XlyreDataset();

		//Settings properties for the catalog.
		$dataset->set("IdDataset", $idnode);
		$dataset->set("Identifier", $name);
		$dataset->set("Theme", $theme);
		$dataset->set("License", $license);
		$dataset->set("Modified", time());
		$dataset->set("Publisher", XSession::get('userID'));
		$dataset->set("Periodicity", $periodicity);
		$dataset->set("Spatial", $spatial);
		$dataset->set("Reference", $reference);

		return $dataset->update();
	}


}

?>