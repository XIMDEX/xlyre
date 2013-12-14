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



ModulesManager::file('/inc/modules/Module.class.php');
ModulesManager::file('/inc/io/BaseIO.class.php');
ModulesManager::file('/inc/cli/CliParser.class.php');
ModulesManager::file('/inc/cli/CliReader.class.php');
ModulesManager::file('/inc/model/RelRolesActions.class.php');
ModulesManager::file('/inc/nodetypes/channelnode.inc');
ModulesManager::file('/inc/model/channel.inc');
ModulesManager::file('/inc/model/node.inc');
ModulesManager::file('/inc/utils.inc');
ModulesManager::file('/inc/model/orm/RelRolesStates_ORM.class.php');

class Module_xlyre extends Module {
    
	function Module_xlyre() {
		// Call Module constructor.
            	parent::Module('xlyre', dirname(__FILE__));
	}

        //Function which installs the module
	function install() {
		echo "\nModule xlyre will be installed.\n";
               	$this->loadConstructorSQL("xlyre.constructor.sql");
		/*$name = "xlyre";
                $extension = "xml";
                $complexName = sprintf("%s.%s", $name, $extension);
                $description = "OpenData channel";
                $renderMode = "ximdex";

                $nodeType = new NodeType();
                $nodeType->SetByName('Channel');

		$node = new Node();
		$idNode = $node->CreateNode($complexName, 9,$nodeType->GetID(), NULL);

		$ch=new Channel($idNode);
		$result=$ch->CreateNewChannel($name, $extension, NULL, $description, $idNode, NULL, $renderMode);
                if ($result > 0) {
                        echo "Channel has been succesfully created\n";
                }
		else{
			echo "There was a problem creating the xlyre channel\n";
		}*/

                parent::install();
	}
        
        function uninstall(){
		echo "\nModule xlyre will be uninstalled!.\n";
               	$this->loadDestructorSQL("xlyre.destructor.sql");
                parent::uninstall();
        }
}
?>
