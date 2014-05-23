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
if (angular.module('ximdex').notRegistred('XLyreManagedatasetCtrl')){
    angular.module('ximdex')
        .controllerProvider.register('XLyreManagedatasetCtrl', ['$scope', '$attrs', 'xBackend', 'xTranslate', '$timeout', function($scope, $attrs, xBackend, xTranslate, $timeout){
            
            $scope.selectedLanguages = {};
            
            if ($attrs.ximInitOptions) {
                $scope.options = angular.fromJson($attrs.ximInitOptions);

                $scope.dataset = $scope.options.dataset;
                $scope.languages = $scope.options.languages;
                $scope.distributions = $scope.options.distributions;
                $scope.tags = $scope.options.tags;
                $scope.defaultLanguage = $scope.options.defaultLanguage;   
            }
            
            $scope.$watch('dataset.languages', function(languages, oldLanguages){
                $scope.activeLanguages = 0;
                for (key in languages) {
                    if (languages[key] != '') {
                        $scope.activeLanguages++;
                        $scope.defaultLanguage = $scope.defaultLanguage || key;
                    } else {
                        if ($scope.selectedLanguage === key) $scope.selectedLanguage = "";
                    }
                }
            }, true);

            $scope.$watch('method', function(newValue){
                if (newValue == 'createdataset') {
                    $scope.submitLabel = xTranslate('xlyre.actions.managedataset.dataset.create');
                } else if (newValue == 'updatedataset') {
                    $scope.submitLabel = xTranslate('xlyre.actions.managedataset.dataset.update');
                }
            });

            $scope.method = $scope.options.go_method;

            $scope.newDistribution = function(){
                $scope.distributions = $scope.distributions || [];
                var newDistribution = {}
                newDistribution.languages = {}
                for (var i = 0; i < $scope.languages.length; i++)  {
                    newDistribution.languages[$scope.languages[i].IdLanguage] = '';
               }
               $scope.distributions.unshift(newDistribution); 
            }
            
            $scope.submitForm = function(form, dataset){
                if (form.$valid) {    
                    var formData = angular.copy(dataset);
                    $scope.submitStatus = 'submitting';
                    formData.languages = [];
                    for (var language in dataset.languages) {
                        if (!!dataset.languages[language]) {
                            formData.languages.push(language);
                        }
                    }
                    xBackend.sendFormData(formData, {action: $scope.options.action, method: $scope.method, id: dataset.id, IDParent: dataset.IDParent}, function(data){
                        if (!dataset.id && data && data.dataset && data.dataset.id) {   
                            $scope.method = 'updatedataset';
                            dataset.id = data.dataset.id;
                            
                            $scope.setActionNode(dataset.id);//Bad practice but needed at the moment to update browser window action
                            
                            dataset.issued = data.dataset.issued;
                            dataset.modified = data.dataset.modified;
                            form.$setPristine();
                        }
                        if (data && data.messages && data.messages.length > 0) {
                            $scope.submitStatus = 'success';
                            $scope.submitMessages = data.messages;
                            $timeout(function(){
                                $scope.submitMessages = null;
                            }, 4000);
                        }
                        if (data && data.errors && data.errors.length > 0) {
                            $scope.submitStatus = 'error';
                            $scope.submitMessages = data.errors;
                            $timeout(function(){
                                $scope.submitMessages = null;
                            }, 4000);    
                        }
                    });
                }  
            }
        }]);
    angular.module('ximdex').registerItem('XLyreManagedatasetCtrl');
}