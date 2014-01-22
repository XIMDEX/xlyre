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


ModulesManager::file('/inc/model/XlyreThemes.php', 'xlyre');
ModulesManager::file('/inc/model/XlyrePeriodicities.php', 'xlyre');
ModulesManager::file('/inc/model/XlyreSpatials.php', 'xlyre');


class Action_configure extends ActionAbstract {

	function index () {

		$configure_options = $this->_loadValues();
		$values = array(
			'themes' => implode(",", $configure_options['themes']),
			'periodicities' => implode(",", $configure_options['periodicities']),
			'spatials' => implode(",", $configure_options['spatials']),
			'go_method' => 'update',
		);

		$this->render($values, null, 'default-3.0.tpl');
	}



	function update() {		

		$old_options = $this->_loadValues();
		$this->_processField($old_options['themes'], explode(',', $this->request->getParam('themes')), 'theme');
		$this->_processField($old_options['periodicities'], explode(',', $this->request->getParam('periodicities')), 'periodicity');
		$this->_processField($old_options['spatials'], explode(',', $this->request->getParam('spatials')), 'spatial');

		$values = array(
			'messages' => $this->messages->messages,
			'action_with_no_return' => true,
		);

		$this->render($values, NULL, 'messages.tpl');

	}



	private function _processField($old_values, $new_values, $field) {

		foreach ($new_values as $key => $value) {
			$new_values[$key] = trim($value);
		}

		$added_values = array_diff($new_values, $old_values);
		$deleted_values = array_diff($old_values, $new_values);

		foreach ($added_values as $added_value) {
			if (!empty($added_value)) {
				switch ($field) {
					case 'theme':
						$this->_executeDbOperation('XlyreThemes', 'add', $added_value);
						break;
					case 'periodicity':
						$this->_executeDbOperation('XlyrePeriodicities', 'add', $added_value);
						break;
					case 'spatial':
						$this->_executeDbOperation('XlyreSpatials', 'add', $added_value);
						break;
					default:
						break;
				}
			}
		}
		foreach ($deleted_values as $deleted_value) {
			if (!empty($deleted_value)) {
				switch ($field) {
					case 'theme':
						$this->_executeDbOperation('XlyreThemes', 'delete', $deleted_value);
						break;
					case 'periodicity':
						$this->_executeDbOperation('XlyrePeriodicities', 'delete', $deleted_value);
						break;
					case 'spatial':
						$this->_executeDbOperation('XlyreSpatials', 'delete', $deleted_value);
						break;
					default:
						break;
				}
			}
		}

	}



	private function _executeDbOperation($class, $op, $value) {

		switch ($op) {
			case 'add':
				$obj = new $class();
				$obj->set('Name', $value);
				$obj->add();
				break;
			case 'delete':
				$obj = new $class();
				$results = $obj->find("Id", "Name=%s", array($value), MONO);
				if ($results) {
					foreach ($results as $id) {
						$obj->set('Id', $id);
						$obj->delete();
					}
				}
				break;
			default:
				break;
		}

	}



	private function _loadValues() {

		$options = array(
			'themes' => array(),
			'periodicities' => array(),
			'spatials' => array(),
		);
		
		$this->_getValues(new XlyreThemes(), $options['themes']);
		$this->_getValues(new XlyrePeriodicities(), $options['periodicities']);
		$this->_getValues(new XlyreSpatials(), $options['spatials']);

		return $options;
		
	}


	private function _getValues($object, &$partial_options) {
		$values = $object->find('Name', "1 ORDER BY Id", array(), MONO);
		foreach ($values as $value) {
			$partial_options[] = $value;
		}
	}

}
?>
