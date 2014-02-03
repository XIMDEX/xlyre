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


<form method="post" id='mdfdts_form' name="mdfdts" action='{$action_url}' 
    ng-controller="XDistibution" 
    ng-init="dataset.IDParent = '{$id_catalog}'; dataset.id = '{$id_dataset}';"
    ng-cloak>
    <div class="action_header">
            <h2>{t}{$title}{/t}</h2>  
    </div>
    <div class="message" ng-show="submitMessages.length">
        <p class="ui-state-primary ui-corner-all msg-info" ng-repeat="message in submitMessages">
            [[message.message]]
        </p>
    </div>
    <div class="action_content dataset_action">
        <div class="col2-3 left dataset_data">
            <div class="dataset icon input">
        
                <input type="text" name="name" id="name" maxlength="100" class="validable not_empty full-size" placeholder="{t}Name of your dataset{/t}"
                    ng-model="dataset.name"
                    ng-init="dataset.name = '{$name}'">
            </div>

            <h3>{t}Metadata{/t}</h3>
            <div class="editable_data col1_2">
                
                <div>
                    <select name="" id="" class="language_selector js_language_selector" 
                            ng-model="selectedLanguage">
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
            <input type="text" name="reference" id="reference" maxlength="50" class="full_size validable not_empty" placeholder="{t}Reference{/t}"
                ng-model="dataset.reference"
                ng-init="dataset.reference='{$reference}'">
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
            
          
    <div class="distributions" ng-show="dataset.id">
        <h3>{t}Distributions{/t}</h3>
        <button type="button" class="add-button" id="new-distribution"
            ng-click="newDistribution = {}"
            ng-hide="newDistribution">
            {t}Add distribution{/t}
        </button>
        <div class="new distributions" 
            ng-controller="XLyreUploader"
            file-upload="fileUploaderOptions">
            <div class="row-item distribution_item new_distribution_item"
                ng-show="newDistribution">
                <div class="translated_items expanded">
                    <ul>
                        {foreach from=$languages item=l}
                            <li class="translate_item" ng-show="dataset.languages.{$l.IdLanguage}">
                                <input type="text" placeholder="Distribution title"  value="Distribution title"
                                    ng-model="newDistribution['{$l.IdLanguage}']">
                                <span class="language-label">{$l.Name}</span>
                            </li>
                        {/foreach}
                    </ul>
                </div>
                <span type="button" class="upload-button">
                    <span>[[queue[queue.length-1].name || 'Attach file']]</span>
                    <input name="file" type="file" multi="false" class="xim-uploader"/>
                </span>
                <button class="save-button" 
                    ng-click="uploadDistribution(newDistribution, queue[queue.length-1])"
                    xim-button
                    xim-label="uploadButtonLabel"
                    xim-disabled = "allowSave"
                    xim-progress = "uploadProgress"
                    xim-state = "queue[queue.length-1].$state()">
                    Save Distribution
                </button>
            </div>
            <div class="row-item distribution_item" ng-repeat="distribution in newDistributions">
                <div class="translated_items">
                    <div class="default_title"
                        ng-hide="showAllTitles">
                        [[ distribution.languages[defaultLanguage] ]]
                    </div>
                    <ul ng-show="showAllTitles">
                        {foreach from=$languages item=l}
                            <li class="translate_item" ng-show="dataset.languages.{$l.IdLanguage}">
                                <input type="text" placeholder="Distribution title"  value="[[distribution.languages.{$l.IdLanguage}]]"><span class="language-label">{$l.Name}</span>
                            </li>
                        {/foreach}
                    </ul>
                    <div class="title_language icon toggle"
                        ng-click="showAllTitles = !showAllTitles"
                        ng-show="activeLanguages">
                        <span ng-hide="showAllTitles">Default language</span>
                    </div>
                </div>
                <div class="distribution_actions">
                    <span class="file">
                        <span class="file_name">[[distribution.name]]</span>
                        <button type="button" class="name_uploader"></button>
                        <a href="#" class="download_link"></a>
                    </span>
                    <span class="format_file">
                        <span class="label_title">{t}Format{/t}</span>
                        .[[distribution.format]]
                    </span>
                    <span class="size_file">
                        <span class="label_title">{t}Size{/t}</span>
                        [[distribution.size | ximBytes]]
                    </span>
                    <span class="creation_date">
                        <span class="label_title">{t}Creation date{/t}</span>
                        [[distribution.issued | date:'dd/MM/yyyy']]
                    </span>
                    <span class="modified_date">
                        <span class="label_title">{t}Modification date {/t}</span>
                        [[distribution.modified | date:'dd/MM/yyyy']]
                    </span>
                </div>
            </div>
        </div>


         <div class="row-item distribution_item">
            <div class="translated_items">
                <div class="default_title"
                    ng-hide="showAllTitles">
                    Default distribution title</div>
                <ul ng-show="showAllTitles">
                    {foreach from=$languages item=l}
                        <li class="translate_item" ng-show="dataset.languages.{$l.IdLanguage}">
                            <input type="text" placeholder="Distribution title"  value="Distribution title"><span class="language-label">{$l.Name}</span>
                        </li>
                    {/foreach}
                </ul>
                <div class="title_language icon toggle"
                    ng-click="showAllTitles = !showAllTitles"
                    ng-show="activeLanguages">
                    <span ng-hide="showAllTitles">Default language</span>
                </div>
            </div>
            <div class="distribution_actions">
                <span class="file">
                    <span class="file_name">File name</span>
                    <button type="button" class="name_uploader"></button>
                    <a href="#" class="download_link"></a>
                </span>
                <span class="format_file">
                    <span class="label_title">{t}Format{/t}</span>
                    .CSV
                </span>
                <span class="size_file">
                    <span class="label_title">{t}Size{/t}</span>
                    16.3k
                </span>
                <span class="creation_date">
                    <span class="label_title">{t}Creation date{/t}</span>
                    09/10/2014
                </span>
                <span class="modified_date">
                    <span class="label_title">{t}Modification date {/t}</span>
                    09/10/2014
                </span>
            </div>
        </div>
    </div> 

    
    </div>
    <fieldset class="buttons-form positioned_btn">
        <button type="button" class="submit-button" ng-click="submitForm(mdfdts, dataset)">Update</button>
    </fieldset>                
    
    <!-- <div style="position: absolute; z-index:99999; bottom: 0; right: 0;">
        <ul>
            <li ng-repeat="(key, value) in dataset">
                <span>[[key]]:</span><span>[[value]]</span>
            </li>
        </ul>
    </div> -->
</form>
