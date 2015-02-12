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
use Ximdex\Services\NodeType as NodetypeService;

ModulesManager::file('/inc/model/XlyreCatalog.php', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendatadcat.php', 'xlyre');

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

        if (!$this->_existDcatChannel()) {
            error_log("No existe");
            $this->_createDcatChannel();
        }
        
        return $catalog->add();
    }

    

    /**
     * Check if Xlyre RNGs already exist
     */
    private function _existRNGs() {
        $node = new Node($this->nodeID);
        $projectNode = new Node($node->getProject());
        $schemasFolder = $projectNode->getChildren(NodetypeService::TEMPLATE_VIEW_FOLDER);
        if ($schemasFolder) {
            // Check if RNGs do not exist yet
            $node_search = new Node();
            $template_dcat = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s AND IdParent = %s", array("rng-dcat.xml", NodetypeService::RNG_VISUAL_TEMPLATE, $schemasFolder[0]), MONO);
            $template_catalog = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s AND IdParent=%s", array("rng-catalog.xml", NodetypeService::RNG_VISUAL_TEMPLATE, $schemasFolder[0]), MONO);
            $template_dataset = $node_search->find('IdNode', "Name = %s AND IdNodeType = %sAND IdParent=%s", array("rng-dataset.xml", NodetypeService::RNG_VISUAL_TEMPLATE, $schemasFolder[0]), MONO);
            return (($template_dcat && $template_catalog) && $template_dataset) ? true : false;
        }
        else {
            return false;
        }
    }

    /**
     * Load RNGs for Xlyre (catalog and dataset) when the user has the 1st interaction with the module
     */
    private function _loadRngs($parentID){
        $nodeParent = new Node($parentID);
        $idProject = $nodeParent->getProject();
        $nodeProject = new Node($idProject);
        $idSchemesFolder = $nodeProject->getChildren(NodetypeService::TEMPLATE_VIEW_FOLDER);
        $path = "/modules/xlyre/resources/schemas/";
        $this->buildDefaultRngFromPath($idSchemesFolder[0], $path);
    }



    /**
     * Check if RDF channel already exists
    */
    private function _existDcatChannel() {
        $node_search = new Node();
        $rdf_channel = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s", array("dcat", NodetypeService::CHANNEL), MONO);
        return ($rdf_channel) ? true : false;
    }


    /**
     * Create RDF Channel for DCAT
     */
    private function _createDcatChannel() {
        $node_parent = new Node();
        $result = $node_parent->find("IdNode", "Name = %s", array('Channel manager'), MONO);

        $idNode = $result[0];
        $name = "dcat";
        $extension = "rdf";
        $description = "DCAT Channel";
        $renderMode = "ximdex";

        $nodeType = new NodeType();
        $nodeType->SetByName('Channel');

        $node = new Node();
        // $complexName = sprintf("%s.%s", $name, $extension);
        // Control uniqueness of tupla, channel, format.
        $result = $node->CreateNode($name, $idNode, $nodeType->get('IdNodeType'), NULL, $name, $extension, NULL, $description, '', $renderMode);

        if ($result > 0) return true;
        else return false;
    }
    
    /**
     * Create a Catalog metadata node for the current Catalog.
     */
    public static function buildCatalogXml($idCatalog) {
        XMD_Log::info("buildCatalogXml - idCatalog:$idCatalog");
        $ok = true;
        $catalog = new XlyreCatalog($idCatalog);
        $catalog_node = new Node($idCatalog);

        $nodeLanguages = $catalog_node->getProperty('language', true);

        foreach ($nodeLanguages as $idlang) {
        XMD_Log::info("buildCatalogXml - idlang:$idlang");
            $language = new Language($idlang);
            $nodename = $catalog->get('Identifier');
            $nodename_search = $nodename."-id".$language->get("IsoName");
            unset($language);
            $node = new Node();
            $result = $node->find('IdNode', "IdParent = %s && IdNodeType = %s && Name = %s", array($idCatalog, XlyreOpenDataSectionMetadata::IDNODETYPE, $nodename_search), MONO);
            unset($node);
            if ($result) {
                #Update
                $node = new Node($result[0]);
                $node->update();
                $node->setContent($catalog->ToXml($idlang));
            }
            else {
                #Create
                $ch = new Channel();
                $html_ch = $ch->find('IdChannel', "name = %s", array('html'), MONO);
                $csv_ch = $ch->find('IdChannel', "name = %s", array('csv'), MONO);
                $nt = new NodeType(XlyreOpenDataSectionMetadata::IDNODETYPE);
                $node_search = new Node();
                $template_val = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s", array("rng-catalog.xml", NodetypeService::RNG_VISUAL_TEMPLATE), MONO);
                if ($template_val) {
                    $data = array(
                        'NODETYPENAME' => $nt->get('Name'),
                        'NAME' => $nodename_search,
                        'PARENTID' => $idCatalog,
                        'FORCENEW' => true,
                        "CHILDRENS" => array (
                            array ("NODETYPENAME" => "VISUALTEMPLATE", "ID" => $template_val[0]),
                            array ("NODETYPENAME" => "CHANNEL", "ID" => $html_ch[0]),
                            array ("NODETYPENAME" => "CHANNEL", "ID" => $csv_ch[0]),
                            array ("NODETYPENAME" => "LANGUAGE", "ID" => $idlang),
                        )
                    );
                    $nodetopublish = new XlyreBaseIO();
                    $nodeid = $nodetopublish->build($data);
                    if ($nodeid) {
                        $node = new Node($nodeid);
                        $node->setContent($catalog->ToXml($idlang));
                    }
                    else {
                        #do something when it fails
                        $ok = false;
                    }
                }
                else {
                    $ok = false;
                }
            }
        }

        # Publish all datasets
        $datasets = $catalog_node->GetChildren(XlyreOpenDataset::IDNODETYPE);
        XMD_Log::info("buildCatalogXml - datasets: " . print_r($datasets,true));
        if ($datasets) {
            foreach ($datasets as $dataset) {
                XMD_Log::info("buildCatalogXml - this dataset: $dataset");
                $ok = $ok && XlyreOpenDataSet::buildDatasetXml($dataset);
                
            }
        }

        $ok = self::buildCatalogDcatXml($idCatalog) && $ok;

        return $ok;    
    }
    
    private static function buildCatalogDcatXml($idcatalog) {
        $catalog = new XlyreCatalog($idcatalog);
        $catalog_node = new Node($idcatalog);

        $nodeLanguages = $catalog_node->getProperty('language', true);

        // Get first language as default language (DCAT does not need any language)
        // but Ximdex publication proccess needs one
        if ($nodeLanguages) {
            $idlang = $nodeLanguages[0];
            $language = new Language($idlang);
            $nodename = $catalog->get('Identifier');
            $nodename_search = $nodename."-dcat";
            unset($language);
            $node = new Node();
            $result = $node->find('IdNode', "IdParent = %s && IdNodeType = %s", array($idcatalog, XlyreOpenDataDCAT::IDNODETYPE), MONO);
            unset($node);
            if ($result) {
                #Update
                $node = new Node($result[0]);
                $node->update();
                $node->setContent($catalog->ToRdf());
                $ok = true;
            }
            else {
                #Create
                $ch = new Channel();
                $dcat_ch = $ch->find('IdChannel', "name = %s", array('dcat'), MONO);
                $nt = new NodeType(XlyreOpenDataDCAT::IDNODETYPE);
                $node_search = new Node();
                $template_val = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s", array("rng-dcat.xml", NodetypeService::RNG_VISUAL_TEMPLATE), MONO);
                if ($template_val) {
                    $data = array(
                        'NODETYPENAME' => $nt->get('Name'),
                        'NAME' => $nodename_search,
                        'PARENTID' => $idcatalog,
                        'FORCENEW' => true,
                        "CHILDRENS" => array (
                            array ("NODETYPENAME" => "VISUALTEMPLATE", "ID" => $template_val[0]),
                            array ("NODETYPENAME" => "CHANNEL", "ID" => $dcat_ch[0]),
                            array ("NODETYPENAME" => "LANGUAGE", "ID" => $idlang),
                        )
                    );
                    $nodetopublish = new XlyreBaseIO();
                    $nodeid = $nodetopublish->build($data);
                    if ($nodeid) {
                        $node = new Node($nodeid);
                        $node->setContent($catalog->ToRdf());
                        $ok = true;
                    }
                    else {
                        #do something when it fails
                        $ok = false;
                    }
                }
                else {
                    $ok = false;
                }
            }
        }

        return $ok;
    }
}

?>