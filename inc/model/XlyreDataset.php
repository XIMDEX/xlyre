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

ModulesManager::file('/inc/model/orm/XlyreDataset_ORM.class.php', 'xlyre');
ModulesManager::file('/inc/model/orm/XlyreThemes.class.php', 'xlyre');
ModulesManager::file('/inc/model/orm/XlyrePeriodicities.class.php', 'xlyre');
ModulesManager::file('/inc/model/orm/XlyreSpatials.class.php', 'xlyre');

class XlyreDataset extends XlyreDataset_ORM {
    
    /**
     * Get all the datasets for a Catalog
     * @param  int $IdCatalog Catalog id.
     * @return array Array with dataset object for every dataset in the current Catalog.
     */
    public function getByCatalog($IdCatalog){
    	$result = array();
		$datasets = $this->find("IdDataset", "IdCatalog=%s", array($IdCatalog), MONO);
    	foreach ($datasets as $idDataset) {
    		$result[$idDataset] = new XlyreDataset($idDataset);
    	}
    	return $result;
    }


    /**
     * Export dataset info to its XML format
     * @return string A string that contains XML file
     */
    public function ToXml() {
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->validateOnParse = true;
        $xml->formatOutput = true;
        
        $dataset = $xml->createElement('dataset');
        $dataset_identifier = $xml->createElement('identifier', $this->Identifier);
        $theme = new XlyreThemes($this->Theme);
        $dataset_theme = $xml->createElement('theme', $theme->Get('Name'));
        $periodicity = new XlyrePeriodicities($this->Periodicity);
        $dataset_periodicity = $xml->createElement('periodicity', $periodicity->Get('Name'));
        $spatial = new XlyreSpatials($this->Spatial);
        $dataset_spatial = $xml->createElement('spatial', $spatial->Get('Name'));
        $user = new User($this->Publisher);
        $dataset_publisher = $xml->createElement('publisher', $user->Get('Name'));
        $format = _('m-d-Y');
        $dataset_issued = $xml->createElement('issued', date($format, $this->Issued));
        $dataset_modified = $xml->createElement('modified', date($format, $this->Modified));
        $dataset_reference = $xml->createElement('reference', $this->Reference);
        
        $dataset->appendChild($dataset_identifier);
        $dataset->appendChild($dataset_theme);
        $dataset->appendChild($dataset_periodicity);
        $dataset->appendChild($dataset_spatial);
        $dataset->appendChild($dataset_publisher);
        $dataset->appendChild($dataset_issued);
        $dataset->appendChild($dataset_modified);
        $dataset->appendChild($dataset_reference);
        $xml->appendChild($dataset);

        return $xml->saveXML($xml->documentElement);
    }

}
?>
