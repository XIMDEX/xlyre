{**
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
 *}

{* Shows form for users who have not general permit to cascade deletion *}
{* It just can be deleted when it has not children and has not dependencies *}
<form method="post" name="formulario" id='formulario' action='{$action_url}'>
	<div class="action_header">
		<h2>{t}Would you like to delete{/t} {$nameNode}?</h2>
		<fieldset class="buttons-form">
			{button label="Delete" class="validate focus  btn main_action" message="You are going to delete this datasets and its distributions. Would you like to continue?"}
		</fieldset>
	</div>
	<div class="message delete-message icon">
		<p>{t}This action cannot be undone{/t}.</p>
	</div>
	<div class="action_content">
		<fieldset>
			<input type="hidden" name="nodeid" value="{$id_node}">
		</fieldset>
	</div>

</form>
