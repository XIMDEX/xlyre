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
                  
                </div>

                <div class="action_content dataset_action">
                    <div class="col2-3 left dataset_data">
                        <div class="dataset icon input">
                        <input type="text" name="name" id="name" maxlength="100" class="validable not_empty full-size" placeholder="{t}Name of your dataset{/t}" value="{$name}">
                        </div>

                        <h3>Metadatos</h3>
                        <div class="editable_data col1_2">
                            <p>
                                <label for="" class="label_title">{t}Dataset title{/t}</label>
                                <select name="" id="" class="language_selector">
                                    <option value="">Español</option>
                                    <option value="">Inglés</option>
                                    <option value="">Francés</option>
                                </select>
                                <input type="text" class="full_size">
                            </p>
                            <p>
                                <label for=""  class="label_title">{t}Dataset description{/t}</label>
                                <textarea name="" id="" cols="30" rows="9" class="full_size"></textarea>
                            </p>
                        </div>
                        <div class="non_editable_data col1_2">
                            <p>
                                <label for="theme_label"  class="label_title">{t}Theme{/t}</label>
                                <select class="not_empty full_size" name="theme" id="theme">
                                    {foreach from=$themes item=t}
                                        {if ($t.id|gettext == $theme)}
                                            <option value='{$t.id|gettext}' selected>{$t.name|gettext}</option>
                                        {else}
                                            <option value='{$t.id|gettext}'>{$t.name|gettext}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </p>
                            <p>
                                <label for="periodicity_label"  class="label_title">{t}Periodicity{/t}</label>
                                <select class="not_empty full_size" name="periodicity" id="periodicity">
                                    {foreach from=$periodicities item=p}
                                        {if ($p.id|gettext == $periodicity)}
                                            <option value='{$p.id|gettext}' selected>{$p.name|gettext}</option>
                                        {else}
                                            <option value='{$p.id|gettext}'>{$p.name|gettext}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </p>
                            <p>
                                <label for="license_label"  class="label_title">{t}License{/t}</label>
                                <select class="not_empty full_size" name="license" id="license">
                                    {foreach from=$licenses item=l}
                                        {if ($l.id|gettext == $license)}
                                            <option value='{$l.id|gettext}' selected>{$l.name|gettext}</option>
                                        {else}
                                            <option value='{$l.id|gettext}'>{$l.name|gettext}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </p>
                            <label for="spatial_label"  class="label_title">{t}Spatial{/t}</label>
                            <select class="not_empty full_size" name="spatial" id="spatial">
                                {foreach from=$spatials item=s}
                                    {if ($s.id|gettext == $spatial)}
                                        <option value='{$s.id|gettext}' selected>{$s.name|gettext}</option>
                                    {else}
                                        <option value='{$s.id|gettext}'>{$s.name|gettext}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="col1-3 right dataset_info">
                        <h4>{t}Dataset info{/t}</h4>
                           {include file="`$_APP_ROOT`/actions/createxmlcontainer/template/Smarty/_ximdoc_languages.tpl"}                        

                        <div class="reference_url">
                            <h3>
                                <label for="reference">{t}Url más información{/t}</label>
                            </h3>
                        <input type="text" name="reference" id="reference" maxlength="50" class="full_size validable not_empty" placeholder="{t}Reference{/t}" value="{$reference}">
                        </div>

                        <div class="creation_date">
                            <h3>{t}Creation date{/t}</h3>
                            <p>09/01/2014</p>
                        </div>
                        <div class="modification_date">
                            <h3>{t}Modification date{/t}</h3>
                            <p>--/--/--</p>
                        </div>
                        <div class="publicator">
                            <h3>{t}Publicated by $user{/t}</h3>
                            <p>Ximdex</p>
                        </div>
                        
                     
                    </div>
                       
                      

                        <div class="col1-3">  

                                {*<h3>{t}Data{/t}</h3>

                                  <uploader />*}

                                </div>

                
                </div>
      <fieldset class="buttons-form positioned_btn">
                            {button label="{t}{$button}{/t}" class='validate btn main_action' }<!--message="Would you like to add this dataset?"-->
                        </fieldset>                

        </form>
