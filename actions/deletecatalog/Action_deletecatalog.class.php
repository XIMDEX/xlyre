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



ModulesManager::file('/inc/sync/SynchroFacade.class.php');
ModulesManager::file('/actions/browser3/inc/GenericDatasource.class.php');
ModulesManager::file('/actions/deletedataset/Action_deletedataset.class.php', "xlyre");
ModulesManager::file('/inc/nodetypes/xlyreopendataset.php', 'xlyre');


class Action_deletecatalog extends ActionAbstract {

	function index () {

		$formType = "simple";

		$nodes = $this->request->getParam("nodes");
		$nodes = GenericDatasource::normalizeEntities($nodes);
		if (count($nodes) == 1) {
			$idNode = $this->request->getParam('nodeid');
		}
		$node = new Node($idNode);
		$datasets = $node->getChildren(XlyreOpenDataset::IDNODETYPE);
		$dtsList = array();
		if ($datasets) {
			foreach ($datasets as $dataset) {
				$tmpNode = new Node($dataset);
				$dtsList[] = array('id' => $dataset, "name" => $tmpNode->get("Name"));
			}
		}

		/*
		 * TODO:
		 * We need to get all dependencies (datasts and distributions)
		 * and inform to the user that those dependencies will be removed 
		*/

		// Add default core css for delete elements
		$this->addCss('/actions/deletenode/resources/css/style.css');

		$values = array(
			'id_node' => $idNode,
			'nameNode' => $node->get('Name'),
			'formType' => $formType,
			'dtsList' => $dtsList,
			'go_method' => 'delete_catalog',
		);

		$this->render($values, null, 'default-3.0.tpl');
	}

	function delete_catalog() {

		$idNode	= $this->request->getParam("nodeid");
		$node = new Node($idNode);

		$datasets = $node->getChildren(XlyreOpenDataset::IDNODETYPE);

		$action_deletedataset = new Action_deletedataset();

		if ($datasets) {
			foreach ($datasets as $dataset) {
				if (! $action_deletedataset->delete_dataset($dataset)) {
					$this->messages->add(_("An error occurred while deleting dataset"), MSG_TYPE_ERROR);
				}
				else {
					$this->messages->add(_("The dataset were successfully deleted"), MSG_TYPE_NOTICE);
				}
			}
		}

		// Deleting publication tasks
		$sync = new SynchroFacade();
		$sync->deleteAllTasksByNode($idNode);

		$node = new Node($idNode);
		$parentID = $node->get('IdParent');
		$node->delete();
		$err = NULL;
		if($node->numErr) {
			$err = _("An error occurred while deleting:");
			$err .= '<br>' . $node->get('IdNode') . " " . $node->GetPath() . '<br>' . _("Error message: ") . $node->msgErr . "<br><br>";
		}

		if (strlen($err)) {
			$this->messages->add($err, MSG_TYPE_ERROR);
		} else {
			$this->messages->add(_("The catalog were successfully deleted"), MSG_TYPE_NOTICE);
		}
			
		$values = array(
			'messages' => $this->messages->messages,
			'action_with_no_return' => true,
            'parentID' => $parentID
		);

		$this->sendJSON($values);
	}

}
?>
