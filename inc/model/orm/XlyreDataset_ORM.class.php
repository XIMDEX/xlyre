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



ModulesManager::file('/inc/helper/GenericData.class.php');


class XlyreDataset_ORM extends GenericData   {
    var $_idField = 'IdDataset';
    var $_table = 'XlyreDataset';

    var $_metaData = array(
                'IdDataset' => array('type' => 'int(11)', 'not_null' => 'true', 'primary_key' => 'true'),
                'IdCatalog' => array('type' => 'int(11)', 'not_null' => 'true'),
                'Identifier' => array('type' => 'varchar(100)', 'not_null' => 'true'),
                'Theme' => array('type' => 'int(11)', 'not_null' => 'true'),
                'Issued' => array('type' => 'int(12)', 'not_null' => 'true'),
                'Modified' => array('type' => 'int(12)', 'not_null' => 'true'),
                'Publisher' => array('type' => 'int(12)', 'not_null' => 'true'),
                'Periodicity' => array('type' => 'int(11)', 'not_null' => 'true'),
                'License' => array('type' => 'int(11)', 'not_null' => 'true'),
                'Spatial' => array('type' => 'int(11)', 'not_null' => 'true'),
                'Reference' => array('type' => 'varchar(255)', 'not_null' => 'true')
                );
    var $_uniqueConstraints = array('Identifier');
    var $_indexes = array('IdDataset', 'IdCatalog');

    var $IdDataset;
    var $IdCatalog;
    var $Identifier;
    var $Theme;
    var $Issued = 0;
    var $Modified = 0;
    var $Publisher = 0;
    var $Periodicity;
    var $License;
    var $Spatial;
    var $Reference = '';

}
?>
