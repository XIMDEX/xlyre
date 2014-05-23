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
ModulesManager::file('/inc/model/Namespaces.class.php');
ModulesManager::file('/inc/helper/String.class.php');


class Action_managedataset extends ActionAbstract {

	function index(){
		$this->loadResources();

        $idNode = $this->request->getParam("nodeid");
        $options = array();
        // Getting channels
        $channel = new Channel();
        $channels = $channel->getChannelsForNode($idNode);
        $options['channels'] = $channels;

        // Getting languages
        $language = new Language();
        $languages = $language->getLanguagesForNode($idNode);
        $options['languages'] = $languages;

        $node = new Node($idNode);
        $nt = $node->GetNodeType();
        if ($nt == XlyreOpenDataSection::IDNODETYPE) {
            $this->loadValues($options);
            $options['base_url'] = Config::getValue('UrlRoot');
            $options['go_method'] = 'createdataset';
            $options['action'] = 'managedataset';
            $values['title'] = 'Create Dataset';
            $values['button'] = 'Create';
            $options['dataset']['IDParent'] = $idNode;
        }
        elseif ($nt == XlyreOpenDataset::IDNODETYPE) {
            $this->loadValues($options, $idNode);
            $options['base_url'] = Config::getValue('UrlRoot');
            $options['go_method'] = 'updatedataset';
            $options['action'] = 'managedataset';
            $values['title'] = 'Edit Dataset';
            $values['button'] = 'Update';
            $options['dataset']['id'] = $idNode;
        }
        $values['options'] = json_encode($options);
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
            'REFERENCE' => $this->request->getParam('reference')
        );

        $baseio = new XlyreBaseIO();
        $iddataset = $baseio->build($data);

        $errors = array();
        $messages = array();

		if (!($iddataset > 0)) {
            $this->messages->mergeMessages($baseio->messages);
            // $this->messages->add(_('Operation could not be successfully completed'), MSG_TYPE_ERROR);
            $errors[] = array('message' => _('Operation could not be successfully completed'), 'type' => MSG_TYPE_ERROR);
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
            $messages[] = array('message' => sprintf(_('%s has been successfully created'), $name), 'type' => MSG_TYPE_NOTICE);
            $this->reloadNode($parentID);
        }

        $dataset = new XlyreDataset($iddataset);
        $values = array(
                'dataset' => array(
                    'id' => $iddataset,
                    'issued' => $dataset->Get('Issued'),
                    'modified' => $dataset->Get('Modified'),
                ),
                'messages' => $messages,
                'errors' => $errors
        );

        $this->sendJSON($values);
    }


    function updatedataset() {
        $errors = array();
        $messages = array();
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
            'REFERENCE' => $this->request->getParam('reference')
            );

        $baseio = new XlyreBaseIO();
        $result = $baseio->updateNode($data, "XLYREOPENDATASET");

        if (!($result > 0)) {
            $errors[] = array('message' => _('Operation could not be successfully completed'), 'type' => MSG_TYPE_ERROR);
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
            foreach ($i18n_dataset_values as $value) {
                #Delete language
                $rel = $xlrml->find('IdRel', "IdNode = %s AND IdLanguage = %s", array($nodeID, $value), MONO);
                $xlrml_delete = new XlyreRelMetaLangs($rel[0]);
                $xlrml_delete->delete();
            }

            $node = new Node($nodeID);
            $this->reloadNode($node->get("IdParent"));
            $messages[] = array('message' => sprintf(_('%s has been successfully updated'), $name), 'type' => MSG_TYPE_NOTICE);
        }

        $values = array(
                'errors' => $errors,
                'messages' => $messages
        );

        $this->sendJSON($values);

    }


    //Note: This method only works for browsers supporting sendAsBinary()
    function addDistribution() {
        $values = array();
        $filename = $_FILES['file']['name'];
        if (isset($_FILES)) {
            $values['distribution']['file'] = String::normalize($filename);

            $extension = strtolower(end(explode('.',$values['distribution']['file'])));
            $values['distribution']['format'] = $extension;
            $values['distribution']['size'] = $_FILES['file']['size'];
            $values['distribution']['issued'] = time();
            $values['distribution']['modified'] = time();

            $nt = new NodeType(XlyreOpenDistribution::IDNODETYPE);
            $data_dist = array(
                'NODETYPENAME' => $nt->get('Name'),
                'NAME' => $values['distribution']['file'],
                'PARENTID' => $this->request->getParam('nodeid'),
                'FILENAME' => $values['distribution']['file'],
                'FILESIZE' => $values['distribution']['size'],
                'TMPSRC' => $_FILES['file']['tmp_name'],
            );
            $baseio = new XlyreBaseIO();
            $iddist = $baseio->build($data_dist);

            if (isset($_POST['languages'])) {
                $array_langs = json_decode($_POST['languages'], true);
                if (is_array($array_langs)) {
                    foreach ($array_langs as $key => $value) {
                        if ($value != '') {
                            // Save values in object to return
                            $values['distribution']['languages'][$key] = $value;
                            // Add i18n for title field
                            if ($iddist > 0) {
                                $xlrml = new XlyreRelMetaLangs();
                                $xlrml->set('IdNode', $iddist);
                                $xlrml->set('IdLanguage', $key);
                                $xlrml->set('Title', $value);
                                $xlrml->add();
                            }
                        }
                    }
                }
            }

            if (!($iddist > 0)) {
                $values['errors'][] = _('Operation could not be successfully completed.');
                // $values['errors'][] = $baseio->messages();
            }
            else {
                $values['messages'] = _('The distribution was uploaded sucesfully.');
                $values['distribution']['id'] = $iddist;
            }
        }
        else {
            $values['errors'][] = _("There is no file to upload. Please try again.");
        }
        $this->sendJSON($values);
    }



    function updateDistribution() {
        $values = array();
        // $dist_id = $this->request->getParam('nodeid');
        $dist_id = trim($_POST['id'], '\"');

        $distribution = new XlyreDistribution($dist_id);
        $values['distribution']['id'] = $dist_id;
        $values['distribution']['issued'] = $distribution->get('Issued');
        $values['distribution']['modified'] = time();

        if (isset($_FILES['file'])) {
            $filename = $_FILES['file']['name'];
            $values['distribution']['file'] = String::normalize($filename);
            $extension = strtolower(end(explode('.',$values['distribution']['file'])));
            $values['distribution']['format'] = $extension;
            $values['distribution']['size'] = $_FILES['file']['size'];
            $name = $_FILES['file']['name'];
            $filename = $_FILES['file']['name'];
            $filesize = $_FILES['file']['size'];
            $tmpfile = $_FILES['file']['tmp_name'];
        }
        else {
            $values['distribution']['file'] = $distribution->get('Filename');
            $values['distribution']['format'] = $distribution->get('MediaType');;
            $values['distribution']['size'] = $distribution->get('ByteSize');
            $name = $distribution->get('Identifier');
            $filename = NULL;
            $filesize = NULL;
            $tmpfile = NULL;
        }

        $nt = new NodeType(XlyreOpenDistribution::IDNODETYPE);
        $data_dist = array(
            'NODETYPENAME' => $nt->get('Name'),
            'IDNODE' => $dist_id,
            'NAME' => $name,
            'FILENAME' => $filename,
            'FILESIZE' => $filesize,
            'TMPSRC' => $tmpfile,
        );

        $baseio = new XlyreBaseIO();
        $result = $baseio->updateNode($data_dist, "XLYREOPENDISTRIBUTION");

        if (isset($_POST['languages'])) {
            $i18n_distribution_new = json_decode($_POST['languages'], true);
            $xlrml = new XlyreRelMetaLangs();
            $i18n_distribution_old = $xlrml->find('IdLanguage', "IdNode = %s", array($dist_id), MONO);
            //Updating title based on languages
            foreach ($i18n_distribution_new as $key => $value) {
                if (in_array($key, $i18n_distribution_old)) {
                    #Update language
                    $rel = $xlrml->find('IdRel', "IdNode = %s AND IdLanguage = %s", array($dist_id, $key), MONO);
                    $xlrml_update = new XlyreRelMetaLangs($rel[0]);
                    $xlrml_update->set('Title', $value);
                    $xlrml_update->update();
                    unset($i18n_distribution_old[array_search($key, $i18n_distribution_old)]);
                }
                else {
                    #Add language
                    $xlrml_add = new XlyreRelMetaLangs();
                    $xlrml_add->set('IdNode', $dist_id);
                    $xlrml_add->set('IdLanguage', $key);
                    $xlrml_add->set('Title', $value);
                    $xlrml_add->add();
                }
            }
            foreach ($i18n_distribution_old as $value) {
                #Delete language
                $rel = $xlrml->find('IdRel', "IdNode = %s AND IdLanguage = %s", array($dist_id, $value), MONO);
                $xlrml_delete = new XlyreRelMetaLangs($rel[0]);
                $xlrml_delete->delete();
            }
        }

        if (!($result > 0)) {
            $values['errors'][] = _('Operation could not be successfully completed.');
        }
        else {
            $values['messages'] = _('The distribution was uploaded sucesfully.');
        }
        
        $this->sendJSON($values);
    }


    function deleteDistribution() {
        $values = array();
        $dist_id = $this->request->getParam('nodeid');
        if ($dist_id) {
            $dist = new Node($dist_id);
            $result = $dist->delete();
            if ($result != 0) {
                $values['messages'] = _("The distribution was deleted sucesfully.");
            }
            else {
                $values['errors'][] = _("The distribution could not be delete. Please try again.");
            }
        }
        else {
            $values['errors'][] = _("There was a problem conecting with the server. Please try again.");
        }
        $this->sendJSON($values);
    }


	function loadResources() {
        $this->addJs('/modules/xlyre/actions/managedataset/resources/js/xlyreDistribution.js');
        $this->addJs('/modules/xlyre/actions/managedataset/resources/js/XManagedatasetCtrl.js');
        $this->addJs('/modules/xlyre/actions/managedataset/resources/js/init.js');
        $this->addCss('/modules/xlyre/actions/managedataset/resources/css/style.css');
        $this->addCss('/xmd/style/jquery/ximdex_theme/widgets/tagsinput/tagsinput.css');
    }


    function loadValues(&$values, $idNode = 0) {
        #Default values for selectors
        $this->_getValues(new XlyreThemes(), $values['themes']);
        $this->_getValues(new XlyrePeriodicities(), $values['periodicities']);
        $this->_getValues(new XlyreSpatials(), $values['spatials']);
        $values['defaultLanguage'] = $values['languages'][0]['IdLanguage'];
        $node = new Node();
        $linkfolder = $node->find('IdNode', "idnodetype = 5048 AND Name = 'Licenses'", array(), MONO);
        if ($linkfolder) {
            $links = $node->find('IdNode, Name', "idparent = %s", array($linkfolder[0]), MULTI);
            foreach ($links as $key => $link) {
                $values['licenses'][$key]['id'] = $link['IdNode'];
                $values['licenses'][$key]['name'] = $link['Name'];
            }
        }
        $dataset = array();
        if ($idNode > 0) {
            $dsmeta = new XlyreDataset($idNode);
            $dataset['name'] = $dsmeta->get("Identifier");
            $dataset['theme'] = $dsmeta->get("Theme");
            $dataset['periodicity'] = $dsmeta->get("Periodicity");
            $dataset['license'] = $dsmeta->get("License");
            $dataset['spatial'] = $dsmeta->get("Spatial");
            $dataset['reference'] = $dsmeta->get("Reference");
            $dataset['issued'] = $dsmeta->get("Issued");
            $dataset['modified'] = $dsmeta->get("Modified");
            $user = new User($dsmeta->get('Publisher'));
            $dataset['publisher'] = $user->Get('Name');
            $xlrml = new XlyreRelMetaLangs();
            $languages_dataset = $xlrml->find('Title, Description, IdLanguage', "IdNode = %s", array($idNode), MULTI);
            foreach ($languages_dataset as $ld) {
                $dataset['languages_dataset'][$ld['IdLanguage']]['title'] = $ld['Title'];
                $dataset['languages_dataset'][$ld['IdLanguage']]['description'] = $ld['Description'];
            }
            $dataset['languages'] = array();
            for($i=0; $i<sizeof($values['languages']); $i++) {
                $dataset['languages'][$values['languages'][$i]['IdLanguage']] = in_array($values['languages'][$i]['IdLanguage'], array_keys($dataset['languages_dataset'])) ? $values['languages'][$i]['Name'] : '';
            }
            $dataset['IDParent'] = $dsmeta->getParent();
            // Get Distributions

            $node = new Node($idNode);
            $distributions = $node->GetChildren(XlyreOpenDistribution::IDNODETYPE);
            $dstList = array();
            if ($distributions) {
                foreach ($distributions as $distribution) {
                    $languages_distribution = $xlrml->find('Title, IdLanguage', "IdNode = %s", array($distribution), MULTI);
                    $languages_dist_array = array();
                    foreach ($languages_distribution as $ld) {
                        $languages_dist_array[$ld['IdLanguage']] = $ld['Title'];
                    }
                    $distro = new XlyreDistribution($distribution);
                    $dstList[] = array(
                        "id" => $distribution,
                        "file" => $distro->get("Filename"),
                        "format" => $distro->get("MediaType"),
                        "size" => $distro->get("ByteSize"),
                        "issued" => $distro->get("Issued"),
                        "modified" => $distro->get("Modified"),
                        "languages" => $languages_dist_array,
                    );
                }
            }
            $relTags = new RelTagsNodes();
            $tags = $relTags->getTags($idNode);
            
            foreach ($tags as $key => $tag) {
                $namespace = new Namespaces($tag['IdNamespace']);
                $tags[$key]['namespace'] = $namespace;
            }

            $values["tags"] = $tags;
            $values['distributions'] = $dstList;
        }
        else {
            $user = new User(XSession::get('userID'));
            $dataset['publisher'] = $user->Get('Name');
            $dataset['languages_dataset'] = array();
            $dataset['languages'] = array();
            for($i=0; $i<sizeof($values['languages']); $i++) {
                $dataset['languages'][$values['languages'][$i]['IdLanguage']] = '';
                $dataset['languages_dataset'][$values['languages'][$i]['IdLanguage']] = array('title' => '', 'description' => '');
            }
            $dataset['distributions'] = array();
        }
        $values['dataset'] = $dataset;
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


}

?>
