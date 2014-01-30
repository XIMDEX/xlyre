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
ModulesManager::file('/inc/nodetypes/xlyreopendistribution.inc', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendataset.inc', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendatasection.inc', 'xlyre');
ModulesManager::file('/inc/io/XlyreBaseIOConstants.class.php', "xlyre");
ModulesManager::file('/inc/model/XlyreDistribution.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreRelMetaLangs.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreThemes.php', 'xlyre');
ModulesManager::file('/inc/model/XlyrePeriodicities.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreSpatials.php', 'xlyre');


class Action_managedataset extends ActionAbstract {

	function index(){
		$this->loadResources();

        // Getting channels
        $channel = new Channel();
        $channels = $channel->getChannelsForNode($idNode);
        $values['channels'] = $channels;

        // Getting languages
        $language = new Language();
        $languages = $language->getLanguagesForNode($idNode);
        $values['languages'] = $languages;

        $idNode = $this->request->getParam("nodeid");
        $node = new Node($idNode);
        $idNode = $node->get('IdNode');
        $nt = $node->GetNodeType();
        if ($nt == XlyreOpenDataSection::IDNODETYPE) {
            $this->loadValues($values);
            $values['go_method'] = 'createdataset';
            $values['title'] = 'Create Dataset';
            $values['button'] = 'Create';
            $values['id_catalog'] = $idNode;
        }
        elseif ($nt == XlyreOpenDataset::IDNODETYPE) {
            $this->loadValues($values, $idNode);
            $values['go_method'] = 'updatedataset';
            $values['title'] = 'Edit Dataset';
            $values['button'] = 'Update';
            $values['id_dataset'] = $idNode;
        }        
        $this->render($values, null, 'default-3.0.tpl');
	}


	function createdataset() {

        $parentID = $this->request->getParam('IDParent');
        $name = $this->request->getParam('name');

        $nt = new NodeType(XlyreOpenDataSet::IDNODETYPE);
        $data = array(
            'NODETYPENAME' => $nt->get('Name'),
            'NAME' => $name,
            'PARENTID' => $parentID,
            'THEME' => $this->request->getParam('theme'),
            'PERIODICITY' => $this->request->getParam('periodicity'),
            'LICENSE' => $this->request->getParam('license'),
            'SPATIAL' => $this->request->getParam('spatial'),
            'REFERENCE' => $this->request->getParam('reference'),
            'LANGUAGES' => $this->request->getParam('languages_dataset')
        );

        $baseio = new XlyreBaseIO();
        $iddataset = $baseio->build($data);

		if (!($iddataset > 0)) {
            $this->messages->mergeMessages($baseio->messages);
            $this->messages->add(_('Operation could not be successfully completed'), MSG_TYPE_ERROR);
        }
        else {
            //Adding title and description based on languages
            foreach ($this->request->getParam('languages_dataset') as $key => $value) {
                if (!is_null($this->request->getParam('languages'))) {
                    if (in_array($key, $this->request->getParam('languages'))) {
                        $xlrml = new XlyreRelMetaLangs();
                        $xlrml->set('IdNode', $iddataset);
                        $xlrml->set('IdLanguage', $key);
                        $xlrml->set('Title', $value['title']);
                        $xlrml->set('Description', $value['description']);
                        $xlrml->add();
                    }
                }
            }

            // Add dummy distribution for testing
            $nt = new NodeType(XlyreOpenDistribution::IDNODETYPE);
            $data_dist = array(
                'NODETYPENAME' => $nt->get('Name'),
                'NAME' => $this->_generateRandomString(),
                'PARENTID' => $iddataset,
                'FILENAME' => 'data.csv'

            );
            $baseio = new XlyreBaseIO();
            $iddist = $baseio->build($data_dist);
            if (!($iddist > 0)) {
                $this->messages->mergeMessages($baseio->messages);
                $this->messages->add(_('Operation could not be successfully completed'), MSG_TYPE_ERROR);
            }
            else {
                $this->messages->add(sprintf(_('%s has been successfully created'), $name), MSG_TYPE_NOTICE);
            }
            $this->reloadNode($parentID);

        }

        $dataset = new XlyreDataset($iddataset);
        $format = _('m-d-Y H:i:s');
        $values = array(
                'dataset' => array(
                    'id' => $iddataset,
                    'issued' => date($format, $dataset->Get('Issued')),
                    'modified' => date($format, $dataset->Get('Modified')),
                ),
                'action_with_no_return' => $iddataset > 0,
                'messages' => $this->messages->messages
        );

        $this->sendJSON($values);

    }


    function updatedataset() {
        $nodeID =$this->request->getParam('id');
        $name = $this->request->getParam('name');

        $nt = new NodeType(XlyreOpenDataSet::IDNODETYPE);
        $data = array(
            'NODETYPENAME' => $nt->get('Name'),
            'NAME' => $name,
            'IDNODE' => $nodeID,
            'THEME' => $this->request->getParam('theme'),
            'PERIODICITY' => $this->request->getParam('periodicity'),
            'LICENSE' => $this->request->getParam('license'),
            'SPATIAL' => $this->request->getParam('spatial'),
            'REFERENCE' => $this->request->getParam('reference'),
            'LANGUAGES' => $this->request->getParam('languages')
            );

        $baseio = new XlyreBaseIO();
        $result = $baseio->updateNode($data, "XLYREOPENDATASET");

        if (!($result > 0)) {
            $this->messages->mergeMessages($baseio->messages);
            $this->messages->add(_('Operation could not be successfully completed'), MSG_TYPE_ERROR);
        }
        else {
            $xlrml = new XlyreRelMetaLangs();
            $i18n_dataset_values = $xlrml->find('IdLanguage', "IdNode = %s", array($nodeID), MONO);
            //Updating title and description based on languages
            foreach ($this->request->getParam('languages_dataset') as $key => $value) {
                if (in_array($key, $this->request->getParam('languages'))) {
                    if (in_array($key, $i18n_dataset_values)) {
                        #Update language
                        $rel = $xlrml->find('IdRel', "IdNode = %s AND IdLanguage = %s", array($nodeID, $key), MONO);
                        $xlrml_update = new XlyreRelMetaLangs($rel[0]);
                        $xlrml_update->set('Title', $value['title']);
                        $xlrml_update->set('Description', $value['description']);
                        $xlrml_update->update();
                        unset($i18n_dataset_values[array_search($key, $i18n_dataset_values)]);
                    }
                    else {
                        #Add language
                        $xlrml_add = new XlyreRelMetaLangs();
                        $xlrml_add->set('IdNode', $nodeID);
                        $xlrml_add->set('IdLanguage', $key);
                        $xlrml_add->set('Title', $value['title']);
                        $xlrml_add->set('Description', $value['description']);
                        $xlrml_add->add();
                    }
                }
            }
            foreach ($i18n_dataset_values as $key => $value) {
                #Delete language
                $rel = $xlrml->find('IdRel', "IdNode = %s AND IdLanguage = %s", array($nodeID, $value), MONO);
                $xlrml_delete = new XlyreRelMetaLangs($rel[0]);
                $xlrml_delete->delete();
            }

            $node = new Node($nodeID);
            $this->reloadNode($node->get("IdParent"));
            $this->messages->add(sprintf(_('%s has been successfully updated'), $name), MSG_TYPE_NOTICE);
        }

        $values = array(
                'action_with_no_return' => $result > 0,
                'messages' => $this->messages->messages
        );

        $this->sendJSON($values);

    }


	function loadResources() {
        $this->addJs('/modules/xlyre/actions/managedataset/resources/js/index.js');
        $this->addCss('/modules/xlyre/actions/managedataset/resources/css/style.css');
    }


    function loadValues(&$values, $idNode = 0) {
        
        #Default values for selectors
        $this->_getValues(new XlyreThemes(), $values['themes']);
        $this->_getValues(new XlyrePeriodicities(), $values['periodicities']);
        $this->_getValues(new XlyreSpatials(), $values['spatials']);

        $node = new Node();
        $linkfolder = $node->find('IdNode', "idnodetype = 5048 AND Name = 'Licenses'", array(), MONO);
        if ($linkfolder) {
            $links = $node->find('IdNode, Name', "idparent = %s", array($linkfolder[0]), MULTI);
            foreach ($links as $key => $link) {
                $values['licenses'][$key]['id'] = $link['IdNode'];
                $values['licenses'][$key]['name'] = $link['Name'];
            }
        }

        if ($idNode > 0) {
            $dsmeta = new XlyreDataset($idNode);
            $values['name'] = $dsmeta->get("Identifier");
            $values['theme'] = $dsmeta->get("Theme");
            $values['periodicity'] = $dsmeta->get("Periodicity");
            $values['license'] = $dsmeta->get("License");
            $values['spatial'] = $dsmeta->get("Spatial");
            $values['reference'] = $dsmeta->get("Reference");
            $format = _('m-d-Y H:i:s');
            $values['issued'] = date($format, $dsmeta->get("Issued"));
            $values['modified'] = date($format, $dsmeta->get("Modified"));
            $user = new User($dsmeta->get('Publisher'));
            $values['publisher'] = $user->Get('Name');
            $xlrml = new XlyreRelMetaLangs();
            $languages_dataset = $xlrml->find('Title, Description, IdLanguage', "IdNode = %s", array($idNode), MULTI);
            foreach ($languages_dataset as $ld) {
                $values['languages_dataset'][$ld['IdLanguage']]['title'] = $ld['Title'];
                $values['languages_dataset'][$ld['IdLanguage']]['description'] = $ld['Description'];
            }
            for($i=0; $i<sizeof($values['languages']); $i++) {
                $values['languages'][$i]['Checked'] = in_array($values['languages'][$i]['IdLanguage'], array_keys($values['languages_dataset'])) ? TRUE : FALSE;
            }
        }
        else {
            $values['name'] = "";
            $values['theme'] = "";
            $values['periodicity'] = "";
            $values['license'] = "";
            $values['spatial'] = "";
            $values['reference'] = "";
            $values['issued'] = "--/--/--";
            $values['modified'] = "--/--/--";
            $user = new User(XSession::get('userID'));
            $values['publisher'] = $user->Get('Name');
            $values['languages_dataset'] = array();
            for($i=0; $i<sizeof($values['languages']); $i++) {
                $values['languages'][$i]['Checked'] = FALSE;
            }
        }
    }



    private function _getValues($object, &$partial_options) {
        $values = $object->find('Id, Name', "1 ORDER BY Id", array(), MULTI);
        foreach ($values as $key => $value) {
            $partial_options[$key]['id'] = $value['Id'];
            $partial_options[$key]['name'] = $value['Name'];
        }
    }



	private function _getDescription($nodetype) {
        switch($nodetype){
            case "4001": return "A dataset should be for a single data in several formats.";
        }
    }



    private function _generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }


}

?>
