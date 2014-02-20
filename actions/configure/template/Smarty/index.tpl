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
			{button label="Update" class="validate focus  btn main_action"}
		</fieldset>
	</div>
	<div class="action_content">
        <fieldset>
            <div class="input-select">
                <label class="label_title" for="themes">{t}Themes{/t}*</label>
                <input type="text" name="themes" id="themes" maxlength="100" class="cajaxg full-size" value="{$themes}">
            </div>
    		<div class="input-select">
                <label class="label_title" for="periodicities">{t}Periodicities (in months){/t}*</label>
                <input type="text" name="periodicities" id="periodicities" maxlength="100" class="cajaxg full-size" value="{$periodicities}">
            </div>
            {*<div class="input-select">
                <label class="label_title" for="licenses">{t}Licenses{/t}</label>
                <input type="text" name="licenses" id="licenses" maxlength="100" class="cajaxg full-size" value="{$licenses}">
            </div>*}
            <div class="input-select">
                <label class="label_title" for="spatials">{t}Spatials{/t}*</label>
                <input type="text" name="spatials" id="spatials" maxlength="100" class="cajaxg full-size" value="{$spatials}">
            </div>
            <strong><small class="right">{t}Licenses can be managed using Ximdex links{/t}.</small></strong>
            <br>
            <br>
            <small class="right">(*) {t}Please, enter comma separated values in all fields{/t}.</small>
        </fieldset>
	</div>

</form>
