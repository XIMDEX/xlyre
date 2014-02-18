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


ModulesManager::file('/inc/nodetypes/xlyreopendataset.inc', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendatasection.inc', 'xlyre');
ModulesManager::file('/inc/io/XlyreBaseIOConstants.class.php', "xlyre");
ModulesManager::file('/inc/model/XlyreCatalog.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreDataset.php', 'xlyre');
ModulesManager::file('/actions/workflow_forward/Action_workflow_forward.class.php');


class Action_publish extends Action_workflow_forward {

	 function index () {
        parent::index();
	 }


    protected function sendToPublish($idNode, $up, $down, $markEnd, $republish, $structure, $deepLevel, $sendNotifications, $notificableUsers, $idState, $texttosend){
        $node = new Node($idNode);
        $nt = $node->GetNodeType();
        if ($nt == XlyreOpenDataSection::IDNODETYPE) {
            $ok = $this->publish_catalog($idNode);
        }
        elseif ($nt == XlyreOpenDataset::IDNODETYPE) {
            $ok = $this->publish_dataset($idNode);
        }
        if ($ok) {
            parent::sendToPublish($idNode, $up, $down, $markEnd, $republish, $structure, $deepLevel, $sendNotifications, $notificableUsers, $idState, $texttosend);
        }
        else {
            $this->messages->add(_('There was an error while publishing nodes'), MSG_TYPE_ERROR);
            $values = array(
                'action_with_no_return' => 1,
                'messages' => $this->messages->messages
            );
            $this->render($values, NULL, 'messages.tpl');
        }
    }


	function publish_catalog($idNode = 0) {
        $idcatalog = $idNode;
        $catalog = new XlyreCatalog($idcatalog);

		$data = new DataFactory($this->request->getParam("nodeid"));
		$retid = $data->SetContent($catalog->ToXml());
		if ($retid > 0) {
			#The version was created sucessfully
			$this->messages->add(htmlentities($catalog->ToXml(true)), MSG_TYPE_NOTICE);
		}
		else {
			$this->messages->add(_('There was an error while creating a data version for this catalog'), MSG_TYPE_ERROR);
		}

		$values = array(
        		'action_with_no_return' => 1,
            	'messages' => $this->messages->messages
        	);

        $this->render($values, NULL, 'messages.tpl');
	}


	function publish_dataset($idNode = 0) {
        $iddataset = $idNode;
		$dataset = new XlyreDataset($iddataset);
        $xlrml = new XlyreRelMetaLangs();
        $i18n_dataset_values = $xlrml->find('IdLanguage', "IdNode = %s", array($idNode), MONO);
        foreach ($i18n_dataset_values as $i18n_value) {
            $language = new Language($i18n_value);
            $nodename = $dataset->get('Identifier');
            $nodename_search = $dataset->get('Identifier')."-id".$language->get("IsoName");
            unset($language);
            $node = new Node();
            $result = $node->find('IdNode', "IdParent = %s && IdNodeType = %s && Name = %s", array($iddataset, NodetypeService::XML_DOCUMENT, $nodename_search), MONO);
            unset($node);
            if ($result) {
                #Update
                $node = new Node($result[0]);
                $node->update();
                $node->setContent($dataset->ToXml($i18n_value));
                $ok = true;
            }
            else {
                #Create
                $ch = new Channel();
                $html_ch = $ch->find('IdChannel', "name = %s", array('html'), MONO);
                $nt = new NodeType(NodetypeService::XML_DOCUMENT);
                $node_search = new Node();
                $template_val = $node_search->find('IdNode', "Name = %s AND IdNodeType = %s", array("rng-dataset.xml", NodetypeService::RNG_VISUAL_TEMPLATE), MONO);
                if ($template_val) {
                    $data = array(
                        'NODETYPENAME' => $nt->get('Name'),
                        'NAME' => $nodename,
                        'PARENTID' => $iddataset,
                        'FORCENEW' => true,
                        "CHILDRENS" => array (
                            array ("NODETYPENAME" => "VISUALTEMPLATE", "ID" => $template_val[0]),
                            array ("NODETYPENAME" => "CHANNEL", "ID" => $html_ch[0]),
                            array ("NODETYPENAME" => "LANGUAGE", "ID" => $i18n_value),
                        )
                    );
                    $nodetopublish = new baseIO();
                    $nodeid = $nodetopublish->build($data);
                    if ($nodeid) {
                        $node = new Node($nodeid);
                        $node->setContent($dataset->ToXml($i18n_value));
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
