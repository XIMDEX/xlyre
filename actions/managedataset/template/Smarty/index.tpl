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
        <form method="post" id='mdfdts_form' name="formulario" action='{$action_url}'>
                <input type="hidden" name="nodeid" value="{$id_node}">
                <input type="hidden" name="id" value="{if (!$dataset.id)}{$id_dataset}{else}none{/if}">
                <div class="action_header">
                        <h2>{t}{$title}{/t}</h2>
                        <fieldset class="buttons-form">
                            {button label="{t}{$button}{/t}" class='validate btn main_action' }<!--message="Would you like to add this dataset?"-->
                        </fieldset>
                </div>

                <div class="action_content">

                        <div class="folder-name folder-normal icon input-select">
                        <input type="text" name="name" id="name" maxlength="100" class="cajaxg validable not_empty full-size" placeholder="{t}Name of your dataset{/t}" value="{$name}">
                        </div>
                        <label for="theme_label">{t}Theme{/t}</label>
                        <select class="not_empty" name="theme" id="theme">
                            {foreach from=$themes item=t}
                                {if ($t|gettext == $theme)}
                                    <option value='{$t|gettext}' selected>{$t|gettext}</option>
                                {else}
                                    <option value='{$t|gettext}'>{$t|gettext}</option>
                                {/if}
                            {/foreach}
                        </select>
                        <label for="periodicity_label">{t}Periodicity{/t}</label>
                        <select class="not_empty" name="periodicity" id="periodicity">
                            {foreach from=$periodicities item=p}
                                {if ($p|gettext == $periodicity)}
                                    <option value='{$p|gettext}' selected>{$p|gettext}</option>
                                {else}
                                    <option value='{$p|gettext}'>{$p|gettext}</option>
                                {/if}
                            {/foreach}
                        </select>
                        <label for="license_label">{t}License{/t}</label>
                        <select class="not_empty" name="license" id="license">
                            {foreach from=$licenses item=l}
                                {if ($l|gettext == $license)}
                                    <option value='{$l|gettext}' selected>{$l|gettext}</option>
                                {else}
                                    <option value='{$l|gettext}'>{$l|gettext}</option>
                                {/if}
                            {/foreach}
                        </select>
                        <label for="spatial_label">{t}Spatial{/t}</label>
                        <select class="not_empty" name="spatial" id="spatial">
                            {foreach from=$spatials item=s}
                                {if ($s|gettext == $spatial)}
                                    <option value='{$s|gettext}' selected>{$s|gettext}</option>
                                {else}
                                    <option value='{$s|gettext}'>{$s|gettext}</option>
                                {/if}
                            {/foreach}
                        </select>
                        <div class="folder-name folder-normal icon input-select">
                        <input type="text" name="reference" id="reference" maxlength="50" class="caja validable not_empty" placeholder="{t}Reference{/t}" value="{$reference}">
                        </div>
                        
                        {include file="`$_APP_ROOT`/actions/createxmlcontainer/template/Smarty/_ximdoc_languages.tpl"}

                        <div class="col1-3">  

                                {*<h3>{t}Data{/t}</h3>

                                  <uploader />*}

                                </div>

                
                </div>

        </form>
