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

class XlyreOpenDataSection extends SectionNode{

	const IDNODETYPE = 4000;


	/**
	 * Specific delete for the current nodetype.
	 * It must delete Catalog.	 
	 * @return  boolean True if delete something.
	 */
	public function deleteNode(){
		parent::deleteNode();
		$catalog = new XlyreCatalog($this->nodeID);
		return $catalog->delete();
	}

	/**
	 * Create node for the current nodetype
	 * It must create the catalog
	 */		
	public function createNode($name = NULL, $parentID = NULL, $nodeTypeID = NULL){

		//Adding a new Catalog
		$catalog = new XlyreCatalog();
		

		//Settings properties for the catalog.
		$catalog->set("IdCatalog", $this->nodeID);
		$catalog->set("Identifier", $name);
		$catalog->set("Theme", "NULL");
		$catalog->set("License", "NULL");
		$catalog->set("Spatial", "NULL");
		$catalog->set("Homepage", "NULL");
		$time = time();
		$catalog->set("Modified",$time);
		$catalog->set("Issued",$time);

		if (!$this->_existRNGs()) {
			$this->_loadRngs($parentID);
		}
		
		return $catalog->add();
	}


	/**
	 * Check if Xlyre RNGs already exist
	 */
	private function _existRNGs() {
		// Check if RNGs do not exist yet
		$node_search = new Node();
		$template_catalog = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s", array("rng-catalog.xml", NodetypeService::RNG_VISUAL_TEMPLATE), MONO);
        $template_dataset = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s", array("rng-dataset.xml", NodetypeService::RNG_VISUAL_TEMPLATE), MONO);
        return ($template_catalog && $template_dataset) ? true : false;
	}

	/**
	 * Load RNGs for Xlyre (catalog and dataset) when the user has the 1st interaction with the module
	 */
	private function _loadRngs($parentID){

		error_log(print_r($parentID, true));

    	$nodeParent = new Node($parentID);
		$idProject = $nodeParent->getProject();
		$nodeProject = new Node($idProject);
		$idSchemesFolder = $nodeProject->getChildren(NodetypeService::TEMPLATE_VIEW_FOLDER);
		$path = "/modules/xlyre/resources/schemas/";
		$this->buildDefaultRngFromPath($idSchemesFolder[0], $path);
	}

}

?>