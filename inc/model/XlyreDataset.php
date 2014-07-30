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

ModulesManager::file('/inc/nodetypes/linknode.inc');
ModulesManager::file('/inc/model/orm/XlyreDataset_ORM.class.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreDistribution.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreThemes.php', 'xlyre');
ModulesManager::file('/inc/model/XlyrePeriodicities.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreSpatials.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreRelMetaLangs.php', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendistribution.inc', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendatasetmetadata.inc', 'xlyre');
ModulesManager::file('/inc/RelTagsNodes.inc', 'ximTAGS');

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
     * @param integer $language A integer value that indicates the language fields to export
     * @param boolean $exportdomdoc A boolean value that indicates if the result is string or XML string
     * @return string A string that contains XML file
     */
    public function ToXml($language = 0, $exportdomdoc = false) {
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
        $licensenode = new Node($this->License);
        $licensename = $licensenode->Get('Name');
        $licenselink = new Link($this->License);
        $licenseurl = $licenselink->Get('Url');
        $stringxml .= "<license><name>".$licensename."</name><url>".$licenseurl."</url></license>";
        $stringxml .= "<reference>$this->Reference</reference>";

        $stringxml .= "<language>";
        $xlrml = new XlyreRelMetaLangs();
        $language_dataset = $xlrml->find('Title, Description', "IdNode = %s AND IdLanguage = %s", array($this->IdDataset, $language), MULTI);
        foreach ($language_dataset as $ld) {
            $lang = new Language($language);
            $lang_iso = $lang->Get('IsoName');
            $title = $ld['Title'];
            $description = $ld['Description'];
            $stringxml .= "<id>$lang_iso</id>";
            $stringxml .= "<title>$title</title>";
            $stringxml .= "<description>$description</description>";
        }
        $stringxml .= "</language>";

        $stringxml .= "<distributions>";
        $node = new Node($this->IdDataset);
        $distributions = $node->GetChildren(XlyreOpenDistribution::IDNODETYPE);
        foreach ($distributions as $value) {
            $dist = new XlyreDistribution($value);
            $stringxml .= $dist->ToXml($language);
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


    /**
     * Export dataset info to its XML format (reduced)
     * @param integer $language A integer value that indicates the language fields to export
     * @param boolean $exportdomdoc A boolean value that indicates if the result is string or XML string
     * @return string A string that contains XML file
     */
    public function ToXmlReduced($language = 0, $exportdomdoc = false) {
        
        $stringxml = "<dataset>";
        $stringxml .= "<id>$this->IdDataset</id>";
        $stringxml .= "<identifier>$this->Identifier</identifier>";
        $stringxml .= "<reference>$this->Reference</reference>";
        
        $theme = new XlyreThemes($this->Theme);
        $theme_name = $theme->Get('Name');
        $stringxml .= "<theme>$theme_name</theme>";
        $stringxml .= $this->getTags();
        
        $stringxml .= "<language>";
        
        $xlrml = new XlyreRelMetaLangs();
        $language_dataset = $xlrml->find('Title, Description', "IdNode = %s AND IdLanguage = %s", array($this->IdDataset, $language), MULTI);
        foreach ($language_dataset as $ld) {
            $lang = new Language($language);
            $lang_iso = $lang->Get('IsoName');
            $title = $ld['Title'];
            $description = $ld['Description'];
            $stringxml .= "<id>$lang_iso</id>";
            $stringxml .= "<title>$title</title>";
            $stringxml .= "<description>$description</description>";
        }
        $stringxml .= "</language>";

        $stringxml .= "<formats>";
        $node = new Node($this->IdDataset);
        $distributions = $node->GetChildren(XlyreOpenDistribution::IDNODETYPE);
        foreach ($distributions as $value) {
            $dist = new XlyreDistribution($value);
            $stringxml .= "<format>".$dist->get("MediaType")."</format>";
        }
        $stringxml .= "</formats>";
        $stringxml .= "</dataset>";

        if ($exportdomdoc) {
            $doc = new DOMDocument();
            $doc->loadXML($stringxml);
            return $doc->saveXML();
        }
        else {
            error_log($stringxml);
            return $stringxml;
        }
    }



    /**
     * Export dataset info to its RDF format
     * @return string A string that contains RDF/XML file
     */
    public function ToRdf() {
        
        $node = new Node($this->IdDataset);

        $childrens = $node->GetChildren(XlyreOpenDataSetMetadata::IDNODETYPE);
        $defaultPublicationChannel = $this->getDefaultPublicationChannel();
        $stringrdf = "<dcat:dataset>";
        $stringrdf .= "<dcat:Dataset rdf:about='@@@RMximdex.pathtoabs(".$childrens[0].",".$defaultPublicationChannel.")@@@'>";

        $stringrdf .= "<dct:identifier>@@@RMximdex.pathtoabs(".$childrens[0].",".$defaultPublicationChannel.")@@@</dct:identifier>";

        $xlrml = new XlyreRelMetaLangs();
        $language_dataset = $xlrml->find('IdLanguage, Title, Description', "IdNode = %s", array($this->IdDataset), MULTI);
        foreach ($language_dataset as $ld) {
            $lang = new Language($ld['IdLanguage']);
            $stringrdf .= "<dc:language>".$lang->Get('IsoName')."</dc:language>";
            $stringrdf .= "<dct:title xml:lang='".$lang->Get('IsoName')."'>".$ld['Title']."</dct:title>";
            $stringrdf .= "<dct:description xml:lang='".$lang->Get('IsoName')."'>".$ld['Description']."</dct:description>";
        }

        // <dcat:theme rdf:resource="link_to_vocabulary_resource_term" />
        $theme = new XlyreThemes($this->Theme);
        $stringrdf .= "<dcat:theme><rdf:value>".$theme->Get('Name')."</rdf:value></dcat:theme>";
        // <dct:publisher rdf:resource="link_to_resource" />
        $user = new User($this->Publisher);
        $stringrdf .= "<dct:publisher><rdf:value>".$user->Get('Name')."</rdf:value></dct:publisher>";

        $licenselink = new Link($this->License);
        $stringrdf .= "<dct:license rdf:resource='".$licenselink->get('Url')."' />";
        
        // <dct:spatial rdf:resource="link_to_vocabulary_resource_term" />
        $spatial = new XlyreSpatials($this->Spatial);
        $stringrdf .= "<dct:spatial><rdf:value>".$spatial->Get('Name')."</rdf:value></dct:spatial>";


        $format = 'Y-m-d\TH:i:s\Z';
        $stringrdf .= "<dct:issued rdf:datatype='http://www.w3.org/2001/XMLSchema#dateTime'>".date($format, $this->Issued)."</dct:issued>";
        $stringrdf .= "<dct:modified rdf:datatype='http://www.w3.org/2001/XMLSchema#dateTime'>".date($format, $this->Modified)."</dct:modified>";

        
        $periodicity = new XlyrePeriodicities($this->Periodicity);
        $periodicity_value = floatval($periodicity->Get('Name'))/12;
        $stringrdf .= "<dct:accrualPeriodicity>";
        $stringrdf .= "<dct:Frequency>";
        $stringrdf .= "<rdfs:label>Every ".$periodicity_value." years</rdfs:label>";
        $stringrdf .= "<rdf:value>";
        $stringrdf .= "<time:DurationDescription>";
        $stringrdf .= "<rdfs:label>".$periodicity_value." years</rdfs:label>";
        $stringrdf .= "<time:years rdf:datatype='http://www.w3.org/2001/XMLSchema#decimal'>".$periodicity_value." years</time:years>";
        $stringrdf .= "</time:DurationDescription>";
        $stringrdf .= "</rdf:value>";
        $stringrdf .= "</dct:Frequency>";
        $stringrdf .= "</dct:accrualPeriodicity>";

        // <dct:temporal>
        //     <time:Interval>
        //         <rdf:type rdf:resource="http://purl.org/dc/terms/PeriodOfTime" />
        //         <time:hasBeginning>
        //             <time:Instant>
        //                 <time:inXSDDateTime rdf:datatype="http://www.w3.org/2001/XMLSchema#dateTime">2013-01-01T00:00:00</time:inXSDDateTime>
        //             </time:Instant>
        //         </time:hasBeginning>
        //         <time:hasEnd>
        //             <time:Instant>
        //                 <time:inXSDDateTime rdf:datatype="http://www.w3.org/2001/XMLSchema#dateTime">2013-12-31T00:00:00</time:inXSDDateTime>
        //             </time:Instant>
        //         </time:hasEnd>
        //     </time:Interval>
        // </dct:temporal>
        
        $stringrdf .= "<dct:references rdf:resource='".$this->Reference."' />";
        $stringrdf .= "<dct:conformsTo rdf:resource='".$licenselink->get('Url')."' />";

        $node = new Node($this->IdDataset);
        $distributions = $node->GetChildren(XlyreOpenDistribution::IDNODETYPE);
        foreach ($distributions as $value) {
            $dist = new XlyreDistribution($value);
            $stringrdf .= $dist->ToRdf();
        }

        $stringrdf .= "</dcat:Dataset>";
        $stringrdf .= "</dcat:dataset>";

        return $stringrdf;

    }

    private function getDefaultPublicationChannel(){
        
        return 10001;

    }

    private function getTags() {
        $result = "";
        if(ModulesManager::isEnabled('ximTAGS')){
            $rtn= new RelTagsNodes();
            $nodeTags=$rtn->getTags($this->IdDataset);
            if(!empty($nodeTags)){
                foreach($nodeTags as $tag){
                    $xtags.=$tag['Name'].",";
                }
            }
            $xtags=substr_replace($xtags,"",-1);
            
        }
        $result = "<tags>".utf8_decode($xtags)."</tags>";
        return $result;
    }

}
?>
