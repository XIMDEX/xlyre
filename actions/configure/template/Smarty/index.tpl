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

<form method="post" name="formulario" id='formulario' action='{$action_url}'>
	<div class="action_header">
		<h2>{t}Xlyre Settings{/t}</h2>
		<fieldset class="buttons-form">
			{button label="Save" class="validate focus  btn main_action"}
		</fieldset>
	</div>
	<div class="action_content">
        <fieldset>
            <div class="input-select">
                <label class="label_title" for="theme">{t}Themes{/t}</label>
                <input type="text" name="theme" id="theme" maxlength="100" class="cajaxg validable not_empty full-size" value="{$themes}">
            </div>
    		<div class="input-select">
                <label class="label_title" for="periodicity">{t}Periodicities{/t}</label>
                <input type="text" name="periodicity" id="periodicity" maxlength="100" class="cajaxg validable not_empty full-size" value="{$periodicities}">
            </div>
            <div class="input-select">
                <label class="label_title" for="license">{t}Licenses{/t}</label>
                <input type="text" name="license" id="license" maxlength="100" class="cajaxg validable not_empty full-size" value="{$xlyrelicenses}">
                <small class="right">{t}This field uses Ximdex links{/t}</small>
            </div>
            <div class="input-select">
                <label class="label_title" for="spatial">{t}Spatials{/t}</label>
                <input type="text" name="spatial" id="spatial" maxlength="100" class="cajaxg validable not_empty full-size" value="{$spatials}">
            </div>
            <small class="right">(*) {t}Please enter comma separated values in all fields{/t}</small>
        </fieldset>
	</div>

</form>
