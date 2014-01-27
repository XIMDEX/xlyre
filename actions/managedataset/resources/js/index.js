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


X.actionLoaded(function(event, fn, params) {

        var form = params.actionView.getForm('mdfdts_form');
        var fm = form.getFormMgr();
        var submit = fn('.validate').get(0);

        var $languageSelect = fn('.js_language_selector');
        var $formSections = fn('.js_form_sections');

        var addLanguage = function(language) {
                var $option = fn('<option value='+language.id+'>'+language.label+'</option>');
        	$languageSelect.append($option);
        	var $formSection = $formSections.find('#language_selector_'+language.id);
        	$formSection.find('input').prop('disabled', false);
        	$formSection.find('textarea').prop('disabled', false);
        }
        var removeLanguage = function(language_id) {
        	var $option = $languageSelect.find('option[value='+language_id+']');
                if ($option.prop('selected')) {
                        $option.prop('selected', false);
                        $languageSelect.change();
                }
                $option.remove();
                var $formSection = $formSections.find('#language_selector_'+language_id);
                $formSection.find('input').prop('disabled', true);
                $formSection.find('textarea').prop('disabled', true);

        }

        $formSections.find('.js_form_section').hide();
        
        fn('.languages-available input[type=checkbox]:checked').each(function(){
                var $language = fn(this);
                var id = $language.attr('id');
                var label = $language.siblings('label').html();
                addLanguage({id: id, label:label});
        });

        fn('.languages-available input[type=checkbox]').change(function(){
                
        	$language = fn(this);
        	if ($language.prop('checked')) {
	        	var id = $language.attr('id');
	        	var label = $language.siblings('label').html();
	        	addLanguage({id: id, label:label});
        	} else {
        		removeLanguage($language.attr('id'));
        	}
        });

        $languageSelect.change(function(){
        	var language_id = fn(this).attr('value');
        	$formSections.find('.js_form_section').hide();
        	if (language_id) {
	        	$formSections.find('#language_selector_'+language_id).show();
	        }
        });
});

