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


<form method="post" id='mdfdts_form' name="mdfdts" action="{$base_url}"
    ng-controller="XLyreDatasetCtrl" 
    ng-init="dataset.IDParent = '{$id_catalog}'; dataset.id = '{$id_dataset}'; defaultLanguage = '{$default_language}'"
    ng-cloak
    xim-languages='{$json_languages}'
    xim-distributions='{$json_distributions}'
    xim-method="{$go_method}"
    xim-action="{$action}"
    novalidate>
    <div class="action_header" ng-hide="submitMessages.length">
            <h2>[[submitLabel]]</h2>  
    </div>
    <div class="message" ng-show="submitMessages.length">
        <p class="ui-state-primary ui-corner-all msg-info">
            [[submitMessages]]
        </p>
    </div>
    <div class="action_content dataset_action">
        <div class="col2-3 left dataset_data">
            <div class="dataset icon input">
        
                <input type="text" name="name" id="name" maxlength="100" class="validable not_empty full-size" placeholder="{t}Name of your dataset{/t}"
                    ng-model="dataset.name"
                    ng-init="dataset.name = '{$name}'"
                    required xim-alphanumeric>
            </div>

            <h3>{t}Metadata{/t}</h3>
            <div class="editable_data col1_2">
                
                <div>
                    <select name="" id="" class="language_selector js_language_selector" 
                            ng-model="selectedLanguage"
                            ng-show="activeLanguages">
                        <option value="" 
                            ng-selected="!dataset.languages[selectedLanguage]">
                            Select a language
                        </option>
                        <option 
                            ng-repeat="(id, label) in dataset.languages" 
                            ng-disabled="!label" 
                            value="[[id]]">
                            [[label]]
                        </option>
                    </select>
                </div>
                <div class="js_form_sections">
                    {foreach from=$languages item=l}
                        <div class="js_form_section" id="language_selector_{$l.IdLanguage}" 
                            ng-show="selectedLanguage == {$l.IdLanguage} && dataset.languages.{$l.IdLanguage}">
                            <label for="languages_dataset[{$l.IdLanguage}][title]" class="label_title">{t}Dataset title{/t}</label>
                            <input name="languages_dataset[{$l.IdLanguage}][title]" type="text" class="full_size"
                                ng-model="dataset.languages_dataset.{$l.IdLanguage}.title"
                                ng-init="dataset.languages_dataset.{$l.IdLanguage}.title = '{$languages_dataset[$l.IdLanguage].title}'">
                            <label for="languages_dataset[{$l.IdLanguage}][description]"  class="label_title">{t}Dataset description{/t}</label>
                            <textarea name="languages_dataset[{$l.IdLanguage}][description]" id="" cols="30" rows="9" class="full_size"
                                ng-model="dataset.languages_dataset.{$l.IdLanguage}.description"
                                ng-init="dataset.languages_dataset.{$l.IdLanguage}.description = '{$languages_dataset[$l.IdLanguage].description}'">
                                
                            </textarea>
                        </div>
                    {/foreach}
                </div>
            </div>
            <div class="non_editable_data col1_2">
                <p>
                    <label for="theme_label"  class="label_title">{t}Theme{/t}</label>
                    <select class="not_empty full_size" name="theme" id="theme" 
                        ng-model="dataset.theme"
                        ng-init="dataset.theme = '{$theme}' || '{$themes[0].id|gettext}'">
                        {foreach from=$themes item=t}
                            <option value='{$t.id|gettext}'>{$t.name|gettext}</option>
                        {/foreach}
                    </select>
                </p>
                <p>
                    <label for="periodicity_label"  class="label_title">{t}Periodicity{/t}</label>
                    <select class="not_empty full_size" name="periodicity" id="periodicity"
                        ng-model="dataset.periodicity"
                        ng-init="dataset.periodicity = '{$periodicity}' || '{$periodicities[0].id|gettext}'">
                        {foreach from=$periodicities item=p}
                            <option value='{$p.id|gettext}'>{$p.name|gettext}</option>
                        {/foreach}
                    </select>
                </p>
                <p>
                    <label for="license_label"  class="label_title">{t}License{/t}</label>
                    <select class="not_empty full_size" name="license" id="license"
                        ng-model="dataset.license"
                        ng-init="dataset.license = '{$license}' || '{$licenses[0].id|gettext}'">
                        {foreach from=$licenses item=l}
                            <option value='{$l.id|gettext}'>{$l.name|gettext}</option>
                        {/foreach}
                    </select>
                </p>
                <label for="spatial_label"  class="label_title">{t}Spatial{/t}</label>
                <select class="not_empty full_size" name="spatial" id="spatial"
                    ng-model="dataset.spatial"
                    ng-init="dataset.spatial = '{$spatial}' || '{$spatials[0].id|gettext}'">
                    {foreach from=$spatials item=s}
                        <option value='{$s.id|gettext}'>{$s.name|gettext}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="col1-3 right dataset_info">
            <h4>{t}Dataset info{/t}</h4>
               <div class="channel_selection">
                <h3>{t}Channels{/t}</h3>
                {if count($channels) > 0}
                    {foreach from=$channels item=channel}
                        <div class="channel-section">
                            <input name='channels[]' type='checkbox' value='{$channel.IdChannel}' class="hidden-focus" id="{$channel.IdChannel}"/>
                            <label  class="icon checkbox-label reduced_label"  for="{$channel.IdChannel}">{$channel.Description|gettext}</label>
                        </div>
                    {/foreach}
                    
                {else}
                    <p>{t}There are no channels associated to this catalog{/t}</p>
                {/if}
            </div>
            <div class="languages-available"><h3>{t}Languages{/t}</h3>
                {if count($languages) > 0}
                    {foreach from=$languages item=language}
                        <div class="languages-section">
                            {if $language.Checked == true}
                                <input name='languages[]' type='checkbox' value='{$language.IdLanguage}'  id='{$language.IdLanguage}' class="hidden-focus" 
                                    ng-init="dataset.languages.{$language.IdLanguage} = '{$language.Name|gettext}'" ng-model="dataset.languages.{$language.IdLanguage}" 
                                    ng-true-value="{$language.Name|gettext}" 
                                    ng-false-value=""/>
                            {else}
                                <input name='languages[]' type='checkbox' value='{$language.IdLanguage}'  id='{$language.IdLanguage}' class="hidden-focus" 
                                    ng-model="dataset.languages.{$language.IdLanguage}" 
                                    ng-true-value="{$language.Name|gettext}" 
                                    ng-false-value=""/>
                            {/if}
                            <label  for="{$language.IdLanguage}" class="icon checkbox-label reduced_label">{$language.Name|gettext}</label>
                        </div>
                    {/foreach}
                {else}
                    <p>{t}There are no languages associated to this catalog{/t}</p>
                {/if}
            </div>

            <div class="reference_url">
                <h3>
                    <label for="reference">{t}Url más información{/t}</label>
                </h3>
            <input type="url" name="reference" id="reference" maxlength="50" class="full_size validable not_empty" placeholder="{t}Reference{/t}"
                ng-model="dataset.reference"
                ng-init="dataset.reference='{$reference}'" required>
            </div>

            <div class="creation_date"
                ng-init="dataset.issued = '{$issued}'">
                <h3>{t}Creation date{/t}</h3>
                <p>[[dataset.issued]]</p>
            </div>
            <div class="modification_date"
                ng-init="dataset.modified = '{$modified}'">
                <h3>{t}Modification date{/t}</h3>
                <p>[[dataset.modified]]</p>
            </div>
            <div class="publicator">
                <h3>{t}Publicated by{/t}</h3>
                <p>{$publisher}</p>
            </div>
            
         
        </div>
        
        <button class="submit-button" 
            ng-click="submitForm(mdfdts, dataset)"
            xim-button
            xim-label="submitLabel"
            xim-state = "submitStatus"
            xim-disabled = "mdfdts.$invalid || mdfdts.$pristine">
            Update
        </button>
          
        <div class="distributions"
            ng-show="dataset.id">
            <h3>{t}Distributions{/t}</h3>     
            <button type="button" class="add-button" id="new-distribution"
                ng-click="newDistribution()">
                {t}Add distribution{/t}
            </button>
            <xlyre-distribution ng-repeat="distribution in distributions"
                    xim-distribution="distribution"
                    xim-default-language="{$default_language}"
                    xim-active-languages="dataset.languages"
                    xim-nodeid="dataset.id"> 
            </xlyre-distribution>
                
    </div> 

    <!-- {foreach from=$distributions item=d name=distributions}
        
            <ul>
                {foreach from=$languages item=l}
                    <li>DISTRO LOC TITLE: {$d.languages[$l.IdLanguage]}</li>
                {/foreach}
            </ul>
        

    {/foreach} -->

    </div>            
</form>
