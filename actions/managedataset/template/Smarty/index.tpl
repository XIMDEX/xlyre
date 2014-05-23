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


<form id='mdfdts_form' name="mdfdts" novalidate ng-cloak
    ng-controller="XLyreManagedatasetCtrl" 
    xim-init-options='{$options}'>
    <div class="action_header">
            <h2>#/submitLabel/#</h2>  
    </div>
    <div class="message slide-item" ng-show="submitMessages.length" ng-class="{literal}{'message-success': submitStatus == 'success', 'message-error': submitStatus == 'error'}{/literal}">
        <p class="icon" ng-repeat="message in submitMessages">
            #/message.message/#
        </p>
    </div>

    <div class="action_content dataset_action">
        <div class="col2-3 left dataset_data">
            <div class="dataset icon input">
        
                <input type="text" name="name" id="name" maxlength="100" class="validable not_empty full-size" placeholder="{t}Name of your dataset{/t}"
                    ng-model="dataset.name"
                    required xim-alphanumeric>
            </div>

            <h3 class="headline"><span>{t}Metadata{/t}</span></h3>
            <div class="editable_data col1_2">
                <select name="" id="" class="language_selector js_language_selector" 
                        ng-model="selectedLanguage"
                        ng-init="selectedLanguage = defaultLanguage"
                        ng-show="activeLanguages">
                    <option value="" 
                        ng-selected="!dataset.languages[selectedLanguage]" ng-disabled="!!selectedLanguage">
                        {t}Select a language{/t}
                    </option>
                    <option 
                        ng-repeat="(id, label) in dataset.languages" 
                        ng-disabled="!label" ng-if="!!label"
                        value="#/id/#">
                        #/label/#
                    </option>
                </select>

                <div class="empty_state">
                    <span class="title icon language">{t}No language selected{/t}</span>
                    <span>{t}Select at less one in the Dataset Info box at the right{/t}</span>
                </div>
               
                <div class="js_form_sections">
                    <div class="js_form_section" id="language_selector_#/l.IdLanguage/#"
                        ng-repeat="l in languages"
                        ng-init="dataset.languages_dataset[l.IdLanguage] = dataset.languages_dataset[l.IdLanguage] || {}"
                        ng-show="selectedLanguage == l.IdLanguage && dataset.languages[l.IdLanguage]">
                        <p>
                            <label for="languages_dataset[#/l.IdLanguage/#][title]" class="label_title">{t}Dataset title{/t}</label>
                            <input name="languages_dataset[#/l.IdLanguage/#][title]" id="languages_dataset[#/l.IdLanguage/#][description]" type="text" class="full_size"
                                ng-model="dataset.languages_dataset[l.IdLanguage].title">
                        </p>
                        <p>
                            <label for="languages_dataset[#/l.IdLanguage/#][description]"  class="label_title">{t}Dataset description{/t}</label>
                            <textarea name="languages_dataset[#/l.IdLanguage/#][description]" id="languages_dataset[#/l.IdLanguage/#][description]" cols="30" rows="9" class="full_size"
                                ng-model="dataset.languages_dataset[l.IdLanguage].description">
                            </textarea>
                        </p> 
                    </div>
                </div>
            </div>
            <div class="non_editable_data col1_2">
                
                <label for="theme_label"  class="label_title">{t}Theme{/t}</label>
                <select class="not_empty full_size" name="theme" id="theme" 
                    ng-model="dataset.theme"
                    ng-init="dataset.theme = dataset.theme || options.themes[0].id"
                    ng-options="theme.id as theme.name for theme in options.themes">
                </select>
            
                <label for="periodicity_label"  class="label_title">{t}Periodicity (in months){/t}</label>
                <select class="not_empty full_size" name="periodicity" id="periodicity" 
                    ng-model="dataset.periodicity"
                    ng-init="dataset.periodicity = dataset.periodicity || options.periodicities[0].id"
                    ng-options="periodicity.id as periodicity.name for periodicity in options.periodicities">
                </select>
            
                <label for="license_label"  class="label_title">{t}License{/t}</label>
                <select class="not_empty full_size" name="license" id="license" 
                    ng-model="dataset.license"
                    ng-init="dataset.license = dataset.license || options.licenses[0].id"
                    ng-options="license.id as license.name for license in options.licenses">
                </select>
                
                <label for="spatial_label"  class="label_title">{t}Spatial{/t}</label>
                <select class="not_empty full_size" name="spatial" id="spatial" 
                    ng-model="dataset.spatial"
                    ng-init="dataset.spatial = dataset.spatial || options.spatials[0].id"
                    ng-options="spatial.id as spatial.name for spatial in options.spatials">
                </select>
            </div>
        </div>

        <div class="col1-3 right dataset_info">
            <h4>{t}Dataset info{/t}</h4>
            <div class="languages-available"><h3>{t}Languages{/t}</h3>
                <div class="languages-section"
                    ng-repeat="language in languages">
                    <input name='languages[]' type='checkbox' id='#/language.IdLanguage/#' class="hidden-focus" 
                        ng-model="dataset.languages[language.IdLanguage]" 
                        ng-true-value="#/language.Name/#" 
                        ng-false-value=""/>
                    <label  for="#/language.IdLanguage/#" class="icon checkbox-label reduced_label">#/language.Name/#</label>
                </div>
                <p ng-if="!languages.length">{t}There are no languages associated to this catalog{/t}.</p>
            </div>

            <div class="reference_url">
                <h3>
                    <label for="reference">{t}More Info Url{/t}</label>
                </h3>
                <span class="reference_tooltip" data-tooltip="#/reference/#">
                    <input type="url" name="reference" id="reference" maxlength="50" class="full_size validable" placeholder="{t}Reference{/t}"
                        ng-model="dataset.reference">
                </span>
           
            </div>

            <div class="creation_date" ng-if="dataset.id">
                <h3>{t}Creation date{/t}</h3>
                <p>#/dataset.issued*1000 | date:'dd-MM-yyyy hh:mm:ss'/#</p>
            </div>
            <div class="modification_date" ng-if="dataset.id">
                <h3>{t}Modification date{/t}</h3>
                <p>#/dataset.modified*1000 | date:'dd-MM-yyyy hh:mm:ss'/#</p>
            </div>
            <div class="publicator" ng-if="dataset.id">
                <h3>{t}Publicated by{/t}</h3>
                <p>#/dataset.publisher/#</p>
            </div>
            
         
        </div>
        
        <button class="submit-button" 
            ng-click="submitForm(mdfdts, dataset)"
            xim-button
            xim-label="submitLabel"
            xim-state = "submitStatus"
            xim-disabled = "mdfdts.$invalid || mdfdts.$pristine">
            {t}Update{/t}
        </button>
          
        <div class="distributions"
            ng-show="dataset.id">
            <h3 class="headline"><span>{t}Distributions{/t}</span></h3>     
            <button type="button" class="add-button" id="new-distribution"
                ng-click="newDistribution()">
                {t}Add distribution{/t}
            </button>
            <xlyre-distribution ng-repeat="distribution in distributions"
                    xim-distribution="distribution"
                    xim-default-language="defaultLanguage"
                    xim-active-languages="dataset.languages"
                    xim-nodeid="dataset.id"> 
            </xlyre-distribution>
                
        </div> 
        <div class="tags" 
            ng-show="dataset.id">
            <h3 class="headline"><span>{t}Tags{/t}</span></h3>     
            <ul class="xim-tagsinput-list">
                <li class="xim-tagsinput-tag icon xim-tagsinput-type-#/tag.namespace.nemo/#" ng-repeat="tag in tags">
                    <span class="xim-tagsinput-text" data-tooltip="#/tag.namespace.uri/#">
                    #/tag.Name/#
                    </span>
                    <a ng-href="#/tag.namespace.uri/#" class="ontology_link" target="_blank">#/tag.namespace.type/#</a>
                </li>
            </ul>
            <button type="button" class="add-button" id="manage-tags" 
                ng-click="$emit('openAction',{literal}{name:'Manage Tags',nodeid:dataset.id,command:'setmetadata',module:'ximTAGS'}{/literal})" >
                {t}Add more tags{/t}
            </button>
        </div>
    </div>
</form>
