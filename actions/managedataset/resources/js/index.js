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
 if (angular.module('ximdex').notRegistred('XLyreDatasetCtrl')){
    angular.module('ximdex')
        .controllerProvider.register('XLyreDatasetCtrl', ['$scope', '$attrs', 'xBackend', '$timeout', function($scope, $attrs, xBackend, $timeout){
            $scope.selectedLanguages = {};
            $scope.languages = angular.fromJson($attrs.ximLanguages);
            if ($attrs.ximDistributions)
                    $scope.distributions = angular.fromJson($attrs.ximDistributions);
            
            $scope.$watch('dataset.languages', function(languages, oldLanguages){
                $scope.activeLanguages = 0;
                for (key in languages) {
                    if (languages[key] != '')
                        $scope.activeLanguages++;
                        $scope.defaultLanguage = $scope.defaultLanguage || key;
                }
            }, true);

            $scope.$watch('method', function(newValue){
                if (newValue == 'createdataset') {
                    $scope.submitLabel = "Create Dataset";
                } else if (newValue == 'updatedataset') {
                    $scope.submitLabel = "Update Dataset";
                }
            });

            $scope.method = $attrs.ximMethod;

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
                        if (language) {
                            formData.languages.push(language);
                        }
                    }
                    xBackend.sendFormData(formData, {action: $attrs.ximAction, method: $scope.method, id: dataset.id, IDParent: dataset.IDParent}, function(data){ 
                        if (!dataset.id && data && data.dataset && data.dataset.id) {
                            $scope.method = 'updatedataset';
                            dataset.id = data.dataset.id;
                            dataset.issued = data.dataset.issued;
                            dataset.modified = data.dataset.modified;
                        }
                        if (data && data.messages) {
                            $scope.submitStatus = 'success';
                            $scope.submitMessages = data.messages;
                            $timeout(function(){
                                $scope.submitMessages = null;
                            }, 4000);
                        }
                    });
                }  
            }
        }]);
    angular.module('ximdex').registerItem('XLyreDatasetCtrl');
    
    angular.module('ximdex')
        .compileProvider.directive('xlyreDistribution', ['$window', function ($window) {
            return {
                replace: true,
                scope: {
                    distribution: '=ximDistribution',
                    defaultLanguage: '@ximDefaultLanguage',
                    activeLanguages: '=ximActiveLanguages',
                    languages: '=ximLanguages',
                    IDParent: '=ximNodeid'
                },
                restrict: 'E',
                templateUrl : 'modules/xlyre/actions/managedataset/template/Angular/xlyreDistribution.html',
                controller: ['$scope', '$element', '$attrs', '$transclude', '$http', '$timeout', 'xUrlHelper', 'xDialog', 'xBackend', function($scope, $element, $attrs, $transclude, $http, $timeout, xUrlHelper, xDialog, xBackend){

                    $scope.uploadButtonLabel = _('Save Distribution');
                    $scope.addFileLabel = _('Atach File');
                    $scope.uploadState = 'pending';
                    $scope.distribution.languages = $scope.distribution.languages || []
                    if (!$scope.distribution.id) 
                        $scope.editing = true
                    
                    var progressCallback = function (event, data) {
                        $scope.$apply(function(){
                            $scope.uploadProgress = parseInt(data.loaded / data.total * 100, 10);
                        });
                    }

                    var updateDistributionMetadata = function (distribution) {
                        $http.post(xUrlHelper.getAction({action:'managedataset', method:'updateDistribution', module:'xlyre', id: distribution.id}), {languages: angular.toJson(distribution.languages)}).success(function(data){
                            if (data) {
                                if (data.errors){
                                    shoErrorMessage(data.errors[0]);  
                                } else {
                                    $scope.editing = false;
                                }
                            }
                        });
                    }

                    var uploadDistribution = function(distribution, file){
                        $scope.uploadButtonLabel = _("Uploading");
                        $scope.uploadProgress = 0;
                        var formData = []
                        if (distribution){
                            if (distribution.languages) {
                                formData.push({
                                    name: 'languages',
                                    value: angular.toJson(distribution.languages)
                                });   
                            }
                            if (distribution.id) {
                                var method = 'updateDistribution';
                                formData.push({
                                    name: 'id',
                                    value: distribution.id
                                });    
                            }
                        }
                        file.$formData(formData);

                        $scope.fileUploaderOptions = {
                            url: xUrlHelper.getAction({
                                action:'managedataset', 
                                method:method || 'addDistribution', 
                                IDParent: $scope.IDParent
                            }),
                            progress: progressCallback
                        };
                        $timeout(function(){
                            file.$submit()
                                .success(function(data){
                                    if (!data) {
                                        showErrorMessage("Error");
                                    } else if (data.errors) {
                                        showErrorMessage(data.errors[0]);
                                    } else if (data.distribution) {
                                        angular.extend($scope.distribution, data.distribution);
                                        $scope.editing = false;
                                        $scope.uploadButtonLabel = _('Save Distribution');
                                    }
                                    $scope.queue = [];
                            });
                        });
        
                    }
                    var showErrorMessage = function(txt){
                        $scope.errorMsg = txt;
                        $timeout(function(){
                           $scope.errorMsg = null; 
                        }, 4000);
                    }

                    $scope.$watch('distribution.languages',function(languages){
                        if (languages)
                            $scope.defaultTitle = languages[$scope.defaultLanguage];
                    }, true);

                    $scope.saveDistribution = function (distribution, file) {
                        if (file) { 
                            uploadDistribution(distribution, file)
                        } else if (distribution && distribution.id) {
                            updateDistributionMetadata(distribution);
                        }
                    }

                    $scope.deleteDistribution = function (distribution) {
                        if(distribution && distribution.id) {
                            xDialog.openConfirmation(function(result){
                                if (result) {
                                    xBackend.sendFormData({id:distribution.id}, {action:'managedataset', method:'deleteDistribution', module:'xlyre', id: distribution.id}, function(data){
                                        if (data && data.errors){
                                            shoErrorMessage(data.errors[0]);  
                                        } else if (data && data.messages) {
                                            $scope.deleted = true;
                                        }
                                    });    
                                }
                            }, _('The distribution and the attached file will be destroyed. Do you want to continue?'));//TODO: Write a translation service and filter
                        }
                    }
                    
                    $scope.editDistribution = function(){
                        if (!$scope.editing) {
                            $scope.backupDistribution = angular.copy($scope.distribution);
                            $scope.editing = true;
                        }
                    }

                    $scope.cancelAction = function(){
                        var file = $scope.queue[$scope.queue.length-1];
                        if (file && (file.$status == 'pending')) {
                            file.$cancel();
                            $scope.uploadButtonLabel = _('Save Distribution');
                        } else if ($scope.distribution.id) {;
                            if ($scope.backupDistribution)    
                                $scope.distribution = $scope.backupDistribution;
                            $scope.editing = false;
                            $scope.dist_form.$setPristine();
                        } else {
                            $scope.deleted = true;    
                        }
                    }    
                }]
            }
        }]);
    angular.module('ximdex').registerItem('xlyreDistribution');
}
//Start angular compile and binding
X.actionLoaded(function(event, fn, params) {
    X.angularTools.initView(params.context, params.tabId);    
});
