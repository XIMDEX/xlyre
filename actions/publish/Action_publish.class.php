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
ModulesManager::file('/inc/nodetypes/xlyreopendatadcat.inc', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendatasectionmetadata.inc', 'xlyre');
ModulesManager::file('/inc/nodetypes/xlyreopendatasetmetadata.inc', 'xlyre');
ModulesManager::file('/inc/io/XlyreBaseIOConstants.class.php', "xlyre");
ModulesManager::file('/inc/io/XlyreBaseIO.class.php', "xlyre");
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
            $ok = XlyreOpenDataSection::buildCatalogXml($idNode);
        }
        elseif ($nt == XlyreOpenDataset::IDNODETYPE) {
            $ok = XlyreOpenDataSet::buildDatasetXml($idNode);
        }
        if ($ok) {
            parent::sendToPublish($idNode, $up, $down, $markEnd, $republish, $structure, $deepLevel, $sendNotifications, $notificableUsers, $idState, $texttosend);
        }
        else {
            $this->messages->add(_('There was an error while publishing nodes'), MSG_TYPE_ERROR);
            $values = array(
                'action_with_no_return' => 1,
                'messages' => $this->messages->messages,
            );
            $this->sendJSON($values);
        }
    }
}
?>
