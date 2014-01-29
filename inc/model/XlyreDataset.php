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
ModulesManager::file('/inc/model/XlyreDistribution.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreThemes.php', 'xlyre');
ModulesManager::file('/inc/model/XlyrePeriodicities.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreSpatials.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreRelMetaLangs.php', 'xlyre');

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
     * @param boolean $exportdomdoc A boolean value that indicates if the result is string or XML string
     * @return string A string that contains XML file
     */
    public function ToXml($exportdomdoc = false) {
        $format = _('m-d-Y');
        $stringxml = "<dataset>";
        $stringxml .= "<identifier>$this->Identifier</identifier>";

        $theme = new XlyreThemes($this->Theme);
        $theme_name = $theme->Get('Name');
        $stringxml .= "<theme>$theme_name</theme>";
        $periodicity = new XlyrePeriodicities($this->Periodicity);
        $periodicity_name = $periodicity->Get('Name');
        $stringxml .= "<periodicity>$periodicity_name</periodicity>";
        $spatial = new XlyreSpatials($this->Spatial);
        $spatial_name = $spatial->Get('Name');
        $stringxml .= "<spatial>$spatial_name</spatial>";
        $user = new User($this->Publisher);
        $user_name = $user->Get('Name');
        $stringxml .= "<publisher>$user_name</publisher>";
        $issued_date = date($format, $this->Issued);
        $stringxml .= "<issued>$issued_date</issued>";
        $modified_date = date($format, $this->Modified);
        $stringxml .= "<modified>$modified_date</modified>";
        $stringxml .= "<reference>$this->Reference</reference>";

        $stringxml .= "<languages>";
        $xlrml = new XlyreRelMetaLangs();
        $languages_dataset = $xlrml->find('Title, Description, IdLanguage', "IdNode = %s", array($this->IdDataset), MULTI);
        foreach ($languages_dataset as $ld) {
            $lang = new Language($ld['IdLanguage']);
            $lang_iso = $lang->Get('IsoName');
            $title = $ld['Title'];
            $description = $ld['Description'];
            $stringxml .= "<language>";
            $stringxml .= "<id>$lang_iso</id>";
            $stringxml .= "<title>$title</title>";
            $stringxml .= "<description>$description</description>";
            $stringxml .= "</language>";
        }
        $stringxml .= "</languages>";

        $stringxml .= "<distributions>";
        $node = new Node($this->IdDataset);
        $distributions = $node->GetChildren();
        foreach ($distributions as $value) {
            $dist = new XlyreDistribution($value);
            $stringxml .= $dist->ToXml();
        }
        $stringxml .= "</distributions>";
        $stringxml .= "</dataset>";

        if ($exportdomdoc) {
            $doc = new DOMDocument();
            $doc->loadXML($stringxml);
            return $doc->saveXML();
        }
        else {
            return $stringxml;
        }
    }

}
?>
