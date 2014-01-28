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

class XlyreDistribution extends XlyreDistribution_ORM {
    
    /**
     * Export distribution info to its XML format
     * @return string A string that contains XML file
     */
    public function ToXml() {
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->validateOnParse = true;
        $xml->formatOutput = true;
        
        $distribution = $xml->createElement('distribution');
        $distribution_identifier = $xml->createElement('identifier', $this->Identifier);
        $distribution_filename = $xml->createElement('filename', $this->Filename);
        $format = _('m-d-Y');
        $distribution_issued = $xml->createElement('issued', date($format, $this->Issued));
        $distribution_modified = $xml->createElement('modified', date($format, $this->Modified));
        $distribution_mediatype = $xml->createElement('mediatype', $this->MediaType);
        $distribution_bytesize = $xml->createElement('bytesize', $this->ByteSize);
        
        $distribution->appendChild($distribution_identifier);
        $distribution->appendChild($distribution_filename);
        $distribution->appendChild($distribution_issued);
        $distribution->appendChild($distribution_modified);
        $distribution->appendChild($distribution_mediatype);
        $distribution->appendChild($distribution_bytesize);
        $xml->appendChild($distribution);

        return $xml->saveXML($xml->documentElement);
    }
    
}
?>
