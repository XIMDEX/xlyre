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

ModulesManager::file('/actions/addsectionnode/Action_addsectionnode.class.php');
ModulesManager::file('/services/NodetypeService.class.php');
ModulesManager::file('/inc/nodetypes/xlyreopendatasection.inc','xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendataset.inc','xlyre');
ModulesManager::file('/inc/io/XlyreBaseIO.class.php','xlyre');

class Action_createcatalog extends Action_addsectionnode {

	function index(){
		$this->loadResources();
		$values=$this->loadValues();
                $values['go_method']='addcatalog';
                $this->render($values, null, 'default-3.0.tpl');
	}

	function addcatalog(){
		$nodeID = $this->request->getParam('nodeid');
		$datasets = $this->request->getParam('datasets');        
		$name = $this->request->getParam('catalogName');
		$langidlst = $this->request->getParam('langidlst');

		$catalog = new Node();

		$data = array(
                    'NODETYPENAME' => 'OpenDataSection',
                    'NAME' => $name,
//                    'SUBFOLDERS' => $lst,
                    'PARENTID' => $nodeID,
                    'FORCENEW' => true
                    );  

        $baseio = new XlyreBaseIO();
        $id = $baseio->build($data);
        
                if ($id > 0) {
                    $nt = new NodeType(XlyreOpenDataSet::IDNODETYPE);
                    foreach ($datasets as $datasetName) {
                        $datasetData = array(
                            'NODETYPENAME' => $nt->get('Name'),
                            'NAME' => $datasetName,
                            'PARENTID' => $id,
                            'THEME' => 0,
                            'PERIODICITY' => 0,
                            'LICENSE' => 0,
                            'SPATIAL' => 0,
                            'REFERENCE' => '',
                        ); 
                        $baseio->build($datasetData); 
                    }

                    /*
			         $aliasLangArray = array();
        	           if($langidlst) {
                	        foreach ($langidlst as $key) {
                        	        $aliasLangArray[$key] = $namelst[$key];
                        	}
                	       }
			

                        $section = new Node($id);
                        if($aliasLangArray) {
                                foreach ($aliasLangArray as $langID => $longName) {
                                $section->SetAliasForLang($langID, $longName);
                                }   
                        }   */


                        // Creating Licenses subfolder in links folder
                        $catalognode = new Node($id);
                        $projectnode = new Node($catalognode->getProject());
                        $folder = $projectnode->getChildren(NodetypeService::LINK_MANAGER);
                        $this->_createLicenseLinksFolder($folder[0]);


                        //$this->reloadNode($nodeID);
                }

		      if (!($id > 0)) {
                        $this->messages->mergeMessages($baseio->messages);
                        $this->messages->add(_('Operation could not be successfully completed'), MSG_TYPE_ERROR);
                }else{
                        $this->messages->add(sprintf(_('%s has been successfully created'), $name), MSG_TYPE_NOTICE);
                }

                $values = array(
                        'action_with_no_return' => $id > 0,
                        'messages' => $this->messages->messages,
                        'nodeID' => $nodeID
                );

                $this->sendJSON($values);
	}

	function loadResources(){
                $this->addJs('/modules/xlyre/actions/createcatalog/resources/js/index.js');
                $this->addCss('/actions/addsectionnode/resources/css/style.css');
                $this->addCss('/modules/xlyre/actions/createcatalog/resources/css/style.css');
        }

	function _getDescription($nodetype){
                switch($nodetype){
                        case "4001": return "A dataset should be for a single data in several formats.";
		}
	}


    private function _createLicenseLinksFolder($links_id) {
        $nodeaux = new Node();
        $linkfolder = $nodeaux->find('IdNode', "idnodetype = %s AND Name = 'Licenses' AND ParentId = %s", array(NodetypeService::LINK_FOLDER, $links_id), MONO);
        if (!$linkfolder) {
            $nodeType = new NodeType();
            $nodeType->SetByName('LinkFolder');
            $folder = new Node();
            $idFolder = $folder->CreateNode('Licenses', $links_id, $nodeType->GetID(), null);
            $this->_createLicenseLinks("ODbL", "http://opendatacommons.org/licenses/odbl/", "Open Data Commons Open Database License (ODbL)", $idFolder);
        }
    }

    private function _createLicenseLinks($link_name, $link_url, $link_description, $idFolder) {
        $data = array(
            'NODETYPENAME' => 'LINK',
            'NAME' => $link_name,
            'PARENTID' => $idFolder,
            'IDSTATE' => 0,
            'CHILDRENS' => array (
                array ('URL' => $link_url),
                array ('DESCRIPTION' => $link_description)
            )
        );    
        $bio = new baseIO();
        $result = $bio->build($data);
    }
    

}

?>
