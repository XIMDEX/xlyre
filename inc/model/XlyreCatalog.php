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
ModulesManager::file('/inc/nodetypes/xlyreopendatasectionmetadata.php', 'xlyre');

class XlyreCatalog extends XlyreCatalog_ORM {

    /**
     * Get all datasets for current Catalog
     * @return array Array with dataset object for every dataset in the current Catalog.
     */
    public function getDatasets() {
        $result = array();
        if ($this->get("IdCatalog")) {
            $dataset = new XlyreDataset();
            return $dataset->getByCatalog($this->get("IdCatalog"));
        }
        return $result;
    }

    /**
     * Export catalog info to its XML format
     * @param integer $language A integer value that indicates the language fields to export
     * @param boolean $exportdomdoc A boolean value that indicates if the result is string or XML string
     * @return string A string that contains XML file
     */
    public function ToXml($language = 0, $exportdomdoc = false) {
        $format = _('m-d-Y');
        $stringxml = "<catalog>";
        $stringxml .= "<identifier>" . $this->Identifier . "</identifier>";
        $issued_date = date($format, $this->Issued);
        $stringxml .= "<issued>$issued_date</issued>";
        $modified_date = date($format, $this->Modified);
        $stringxml .= "<modified>$modified_date</modified>";
        $stringxml .= "<datasets>";
        $node = new Node($this->IdCatalog);
        $datasets = $node->GetChildren(XlyreOpenDataset::IDNODETYPE);
        foreach ($datasets as $value) {
            $dataset = new XlyreDataset($value);
            $stringxml .= $dataset->ToXmlReduced($language);
        }
        $stringxml .= "</datasets>";
        $stringxml .= "</catalog>";
        if ($exportdomdoc) {
            $doc = new DOMDocument();
            $doc->loadXML($stringxml);
            return $doc->saveXML();
        } else {
            return $stringxml;
        }
    }

    /**
     * Export catalog info to its RDF format
     * @return string A string that contains RDF/XML file
     */
    public function ToRdf() {
        $node = new Node($this->IdCatalog);

        $childrens = $node->GetChildren(XlyreOpenDataSectionMetadata::IDNODETYPE);
        $defaultPublicationChannel = $this->getDefaultPublicationChannel();

        $stringrdf = "<rdf:RDF";
        $stringrdf .= " xmlns:time='http://www.w3.org/2006/time#'";
        $stringrdf .= " xmlns:dct='http://purl.org/dc/terms/'";
        $stringrdf .= " xmlns:dc='http://purl.org/dc/elements/1.1/'";
        $stringrdf .= " xmlns:dcat='http://www.w3.org/ns/dcat#'";
        $stringrdf .= " xmlns:foaf='http://xmlns.com/foaf/0.1/'";
        $stringrdf .= " xmlns:xsd='http://www.w3.org/2001/XMLSchema#'";
        $stringrdf .= " xmlns:rdfs='http://www.w3.org/2000/01/rdf-schema#'";
        $stringrdf .= " xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>";

        $stringrdf .= "<dcat:Catalog rdf:about='@@@RMximdex.pathtoabs(" . $childrens[0] . "," . $defaultPublicationChannel . ")@@@'>";

        $stringrdf .= "<dct:identifier>@@@RMximdex.pathtoabs(" . $childrens[0] . "," . $defaultPublicationChannel . ")@@@</dct:identifier>";

        # Add these lines for each language (not implemented for catalog yet)
        // <dct:title xml:lang='es'>Title</dct:title>
        // <dct:description xml:lang='es'>Decription</dct:description>
        // <dc:language>IsoName</dc:language>
        # Add these props (not implemented for catalog yet)
        // <dct:publisher rdf:resource="http://" />
        // <dct:extent>
        //     <dct:SizeOrDuration>
        //         <rdf:value rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger">
        //             74
        //         </rdf:value>
        //     </dct:SizeOrDuration>
        // </dct:extent>
        // <dct:spatial rdf:resource="http://datos.gob.es/recurso/sector-publico/territorio/pais/Espana" />
        // <dcat:themeTaxonomy rdf:resource="http://datos.gob.es/kos/sector-publico/sector/" />
        // <dct:license rdf:resource="https://sede.minetur.gob.es/es-ES/Paginas/aviso.aspx#uso" />

        $format = 'Y-m-d\TH:i:s\Z';
        $stringrdf .= "<dct:issued rdf:datatype='http://www.w3.org/2001/XMLSchema#dateTime'>" . date($format, $this->Issued) . "</dct:issued>";
        $stringrdf .= "<dct:modified rdf:datatype='http://www.w3.org/2001/XMLSchema#dateTime'>" . date($format, $this->Modified) . "</dct:modified>";
        $stringrdf .= "<foaf:homepage rdf:resource='@@@RMximdex.sectionpathabs(" . $this->IdCatalog . ")@@@' />";

        $node = new Node($this->IdCatalog);
        $datasets = $node->GetChildren(XlyreOpenDataset::IDNODETYPE);
        foreach ($datasets as $value) {
            $dataset = new XlyreDataset($value);
            $stringrdf .= $dataset->ToRdf();
        }

        $stringrdf .= "</dcat:Catalog>";
        $stringrdf .= "</rdf:RDF>";

        return $stringrdf;
    }

    private function getDefaultPublicationChannel() {
        return 10001;
    }

}
