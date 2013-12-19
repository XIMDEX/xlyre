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

ModulesManager::file('/inc/model/orm/XlyreCatalog_ORM.class.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreDataset.php', 'xlyre');

class XlyreCatalog extends XlyreCatalog_ORM {
    
    // Add methods here
    
    /**
     * Get all datasets for current Catalog
     * @return array Array with dataset object for every dataset in the current Catalog.
     */
    public function getDatasets(){

    	$result = array();
    	if ($this->get("IdCatalog")){
    		$dataset = new XlyreDataset();
    		return $dataset->getByCatalog($this->get("IdCatalog"));    		
    	}

    	return $result;
    }
    
}
?>
