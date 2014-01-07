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

ModulesManager::file('/inc/io/XlyreBaseIO.class.php','xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendataset.inc', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendatasection.inc', 'xlyre');
ModulesManager::file('/inc/io/XlyreBaseIOConstants.class.php', "xlyre");


class Action_managedataset extends ActionAbstract {

	function index(){
		$this->loadResources();

        $idNode = $this->request->getParam("nodeid");
        $node = new Node($idNode);
        $nt = $node->GetNodeType();
        if ($nt == XlyreOpenDataSection::IDNODETYPE) {
            $this->loadValues($values);
            $values['go_method'] = 'createdataset';
            $values['title'] = 'Create Dataset';
            $values['button'] = 'Create';
        }
        elseif ($nt == XlyreOpenDataset::IDNODETYPE) {
            $this->loadValues($values, $idNode);
            $values['go_method'] = 'updatedataset';
            $values['title'] = 'Edit Dataset';
            $values['button'] = 'Update';
        }
        $idNode = $node->get('IdNode');

        // Getting channels
        $channel = new Channel();
        $channels = $channel->getChannelsForNode($idNode);
    
        // Getting languages
        $language = new Language();
        $languages = $language->getLanguagesForNode($idNode);

        $values['id_node'] = $idNode;
        $values['channels'] = $channels;
        $values['languages'] = $languages;
        
        $this->render($values, null, 'default-3.0.tpl');
	}


	function createdataset() {

        $parentID = $this->request->getParam('nodeid');
        $name = $this->request->getParam('name');

        $nt = new NodeType(XlyreOpenDataSet::IDNODETYPE);
        $ntName = $nt->get('Name');

        $data = array(
            'NODETYPENAME' => $ntName,
            'NAME' => $name,
            'PARENTID' => $parentID,
            'THEME' => $this->request->getParam('theme'),
            'PERIODICITY' => $this->request->getParam('periodicity'),
            'LICENSE' => $this->request->getParam('license'),
            'SPATIAL' => $this->request->getParam('spatial'),
            'REFERENCE' => $this->request->getParam('reference')
            );

        $baseio = new XlyreBaseIO();
        $id = $baseio->build($data);

		if (!($id > 0)) {
            $this->messages->mergeMessages($baseio->messages);
            $this->messages->add(_('Operation could not be successfully completed'), MSG_TYPE_ERROR);
        }
        else {
            $this->reloadNode($parentID);
            $this->messages->add(sprintf(_('%s has been successfully created'), $name), MSG_TYPE_NOTICE);
        }

        $values = array(
                'action_with_no_return' => $id > 0,
                'messages' => $this->messages->messages
        );

        $this->render($values, NULL, 'messages.tpl');

    }


    function updatedataset() {
        $nodeID = $this->request->getParam('nodeid');
        $name = $this->request->getParam('name');

        $nt = new NodeType(XlyreOpenDataSet::IDNODETYPE);
        $ntName = $nt->get('Name');

        $data = array(
            'NODETYPENAME' => $ntName,
            'NAME' => $name,
            'IDNODE' => $nodeID,
            'THEME' => $this->request->getParam('theme'),
            'PERIODICITY' => $this->request->getParam('periodicity'),
            'LICENSE' => $this->request->getParam('license'),
            'SPATIAL' => $this->request->getParam('spatial'),
            'REFERENCE' => $this->request->getParam('reference')
            );

        $baseio = new XlyreBaseIO();
        $result = $baseio->updateNode($data, "XLYREOPENDATASET");

        if (!($result > 0)) {
            $this->messages->mergeMessages($baseio->messages);
            $this->messages->add(_('Operation could not be successfully completed'), MSG_TYPE_ERROR);
        }
        else {
            $node = new Node($nodeID);
            $this->reloadNode($node->get("IdParent"));
            $this->messages->add(sprintf(_('%s has been successfully updated'), $name), MSG_TYPE_NOTICE);
        }

        $values = array(
                'action_with_no_return' => $result > 0,
                'messages' => $this->messages->messages
        );

        $this->render($values, NULL, 'messages.tpl');

    }


	function loadResources() {
        $this->addJs('/modules/xlyre/actions/managedataset/resources/js/index.js');
        $this->addCss('/modules/xlyre/actions/managedataset/resources/css/style.css');
    }


    function loadValues(&$values, $idNode = 0) {
        
        #Default values for selectors
        $values['themes'] = array('Undefined', '111-XXX', '222-YYY', '333-ZZZ');
        $values['periodicities'] = array(0, 3, 6, 12);
        $values['licenses'] = array('Undefined', 'Creative Commons', 'Open Data license');
        $values['spatials'] = array('Undefined', 'Natial', 'Regional', 'Local');

        if ($idNode > 0) {
            $dsmeta = new XlyreDataset($idNode);
            $values['name'] = $dsmeta->get("Identifier");
            $values['theme'] = $dsmeta->get("Theme");
            $values['periodicity'] = $dsmeta->get("Periodicity");
            $values['license'] = $dsmeta->get("License");
            $values['spatial'] = $dsmeta->get("Spatial");
            $values['reference'] = $dsmeta->get("Reference");
        }
        else {
            $values['name'] = "";
            $values['theme'] = "";
            $values['periodicity'] = "";
            $values['license'] = "";
            $values['spatial'] = "";
            $values['reference'] = "";
        }
    }


	function _getDescription($nodetype) {
        switch($nodetype){
            case "4001": return "A dataset should be for a single data in several formats.";
        }
    }


}

?>
