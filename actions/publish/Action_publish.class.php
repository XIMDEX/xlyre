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


class Action_publish extends ActionAbstract {

	function index () {

		$idNode = $this->request->getParam("nodeid");
        $node = new Node($idNode);
        $values['nameNode'] = $node->get('Name');
        $nt = $node->GetNodeType();
        if ($nt == XlyreOpenDataSection::IDNODETYPE) {
            $values['go_method'] = 'publish_catalog';
            $values['title'] = 'Publish Catalog';
        }
        elseif ($nt == XlyreOpenDataset::IDNODETYPE) {
            $values['go_method'] = 'publish_dataset';
            $values['title'] = 'Publish Dataset';
        }
        $childrens = $node->GetChildren();
		$list = array();
		if ($childrens) {
			foreach ($childrens as $children) {
				$tmpNode = new Node($children);
				$list[] = array('id' => $children, "name" => $tmpNode->get("Name"));
			}
		}
        $values['id_node'] = $idNode;
        $values['list'] = $list;

		// Add default core css for delete elements
		$this->addCss('/actions/deletenode/resources/css/style.css');

		$this->render($values, null, 'default-3.0.tpl');
	}


	function publish_catalog() {
		$catalog = new XlyreCatalog($this->request->getParam("nodeid"));
		$this->messages->add(htmlentities($catalog->ToXml(true)), MSG_TYPE_NOTICE);
		$values = array(
        	'action_with_no_return' => 1,
            'messages' => $this->messages->messages
        );
        $this->render($values, NULL, 'messages.tpl');
	}


	function publish_dataset() {
		$dataset = new XlyreDataset($this->request->getParam("nodeid"));
		$this->messages->add(htmlentities($dataset->ToXml(true)), MSG_TYPE_NOTICE);
		$values = array(
        	'action_with_no_return' => 1,
            'messages' => $this->messages->messages
        );
        $this->render($values, NULL, 'messages.tpl');
	}


}
?>
