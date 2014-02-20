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

ModulesManager::file('/inc/model/orm/XlyreDistribution_ORM.class.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreRelMetaLangs.php', 'xlyre');

class XlyreDistribution extends XlyreDistribution_ORM {

    /**
     * Export distribution info to its XML format
     * @param integer $language A integer value that indicates the language fields to export
     * @param boolean $exportdomdoc A boolean value that indicates if the result is string or XML string
     * @return string A string that contains XML file
     */
    public function ToXml($language = 0, $exportdomdoc = false) {
        $format = _('m-d-Y');
        $stringxml = "<distribution>";
        $stringxml .= "<identifier>$this->Identifier</identifier>";
        $stringxml .= "<filename>$this->Filename</filename>";
        $issued_date = date($format, $this->Issued);
        $stringxml .= "<issued>$issued_date</issued>";
        $modified_date = date($format, $this->Modified);
        $stringxml .= "<modified>$modified_date</modified>";
        $stringxml .= "<mediatype>$this->MediaType</mediatype>";
        $stringxml .= "<bytesize>$this->ByteSize</bytesize>";
        $stringxml .= "<language>";
        $xlrml = new XlyreRelMetaLangs();
        $language_distribution = $xlrml->find('Title, Description', "IdNode = %s AND IdLanguage = %s", array($this->IdDistribution, $language), MULTI);
        foreach ($language_distribution as $ld) {
            $lang = new Language($language);
            $lang_iso = $lang->Get('IsoName');
            $title = $ld['Title'];
            $stringxml .= "<id>$lang_iso</id>";
            $stringxml .= "<title>$title</title>";
        }
        $stringxml .= "</language>";
        $stringxml .= "</distribution>";
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
