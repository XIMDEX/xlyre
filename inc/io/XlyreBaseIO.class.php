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
 *  @version $Revision: 8703 $
 */
ModulesManager::file('/inc/io/BaseIOConstants.php');
ModulesManager::file('/inc/io/BaseIO.class.php');
ModulesManager::file('/inc/io/XlyreBaseIOConstants.class.php', "xlyre");


/**
 * BaseIO for the Xlyre module.
 * All about create new nodes is here.
 */
class XlyreBaseIO extends BaseIO{

	/**
	 * Creates an object desribed in data array
	 *
	 * @param array $data Data of the object to create
	 * @param int $idUser Optional param, if it is not specified, the identifier is obtained from the session user identifier
	 * @return identifier of the inserted node or a state specifying why it was not inserted
	 */
	protected function createNode($data, $metaType, $nodeTypeClass, $nodeTypeName){
		if (array_key_exists($nodeTypeClass, XlyreBaseIOConstants::$metaTypesArray)) {
			$metaTypesArray = XlyreBaseIOConstants::$metaTypesArray;
			$metaType = $metaTypesArray[$nodeTypeClass];
		}
		$instance = new Node();
		switch ($metaType) {
			case 'OPENDATASECTION':
				$idNode = $instance->CreateNode($data['NAME'], $data['PARENTID'], XlyreOpenDataSection::IDNODETYPE, NULL, array(false));
				break;
			case 'OPENDATASET':
				$idNode = $instance->CreateNode($data['NAME'], $data['PARENTID'], XlyreOpenDataSet::IDNODETYPE, NULL, array(false), $data["THEME"], $data["PERIODICITY"], $data["LICENSE"], $data["SPATIAL"], $data["REFERENCE"]);
				break;
			case 'OPENDISTRIBUTION':
				$idNode = $instance->CreateNode($data['NAME'], $data['PARENTID'], XlyreOpenDistribution::IDNODETYPE, NULL,array(false), $data["FILENAME"], $data["FILESIZE"]);
				if ($idNode) {
					// Creating the phisical file
					$node = new Node($idNode);
					$node->setContent(FsUtils::file_get_contents($data['TMPSRC']));
				}
				break;
			case "XMLDOCUMENTNODE":			
				$idNode = parent::createNode($data, $metaType, $nodeTypeClass, $nodeTypeName);
				break;
			default:
				break;
		}
		if (!($idNode > 0)) {
			return ERROR_INCORRECT_DATA;
		}
		return $idNode;
	}


	/**
	 * Updates an object desribed in data array
	 *
	 * @param array $data Data of the object to update
	 * @param int $idUser Optional param, if it is not specified, the identifier is obtained from the session user identifier
	 * @return identifier of the updated node or a state specifying why it was not updated
	 */
	public function updateNode($data, $nodeTypeClass){
		if (array_key_exists($nodeTypeClass, XlyreBaseIOConstants::$metaTypesArray)) {
			$metaTypesArray = XlyreBaseIOConstants::$metaTypesArray;
			$metaType = $metaTypesArray[$nodeTypeClass];
		}

		switch ($metaType) {
			case 'OPENDATASECTION':
				# code...
				break;
			case 'OPENDATASET':
				$nodeDataset = new Node($data["IDNODE"]);
				$nodeDataset->set("Name", $data['NAME']);
				$ok = $nodeDataset->update();
				if ($ok) {
					$idNode = $nodeDataset->class->updateNode($data["IDNODE"], $data['NAME'], $data["THEME"], $data["PERIODICITY"], $data["LICENSE"], $data["SPATIAL"], $data["REFERENCE"]);
					if (!($idNode > 0)) {
						return ERROR_INCORRECT_DATA;
					}
					return $idNode;
				}
				else {
					return ERROR_INCORRECT_DATA;
				}
				break;
			case 'OPENDISTRIBUTION':
				$nodeDistribution = new Node($data["IDNODE"]);

				// We delete old file before uploading new one
				if (!is_null($data['TMPSRC'])) {
					$absPath = Config::GetValue("AppRoot") . Config::GetValue("NodeRoot");
					$deletablePath = $nodeDistribution->class->GetPathList();
					FsUtils::delete($absPath . $deletablePath);
				}

				$nodeDistribution->set("Name", $data['NAME']);
				$ok = $nodeDistribution->update();
				if ($ok) {
					$idNode = $nodeDistribution->class->updateNode($data["IDNODE"], $data['NAME'], $data["FILENAME"], $data["FILESIZE"]);
					if (!($idNode > 0)) {
						return ERROR_INCORRECT_DATA;
					}
					else {
						if (!is_null($data['TMPSRC'])) {
							// Updating the phisical file
							$nodeDistribution->setContent(FsUtils::file_get_contents($data['TMPSRC']));
						}
					}
					return $idNode;
				}
				else {
					return ERROR_INCORRECT_DATA;
				}
				break;
			default:
				# code...
				break;
		}
	}


}

?>

