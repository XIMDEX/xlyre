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
        .controllerProvider.register('XLyreDatasetCtrl', ['$scope', '$attrs', 'xBackend', 'xTranslate', '$timeout', function($scope, $attrs, xBackend, xTranslate, $timeout){
            $scope.selectedLanguages = {};
            $scope.languages = angular.fromJson($attrs.ximLanguages);

            if ($attrs.ximDistributions)
                    $scope.distributions = angular.fromJson($attrs.ximDistributions);
            if ($attrs.ximTags)
                    $scope.tags = angular.fromJson($attrs.ximTags);
            
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
                        if (!!dataset.languages[language]) {
                            formData.languages.push(language);
                        }
                    }
                    xBackend.sendFormData(formData, {action: $attrs.ximAction, method: $scope.method, id: dataset.id, IDParent: dataset.IDParent}, function(data){ 
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
    angular.module('ximdex').registerItem('XLyreDatasetCtrl');
    
    angular.module('ximdex')
        .compileProvider.directive('xlyreDistribution', ['$window', 'xTranslate', function ($window, xTranslate) {
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

                    $scope.uploadButtonLabel = xTranslate('xlyre.actions.managedataset.distribution.upload');
                    $scope.addFileLabel = xTranslate('xlyre.actions.managedataset.distribution.attach');
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
                        var formData = {
                            languages: angular.toJson(distribution.languages),
                            id: distribution.id

                        };
                        // Note: Adding distribution.id in formData because it is not being sent over action url
                        xBackend.sendFormData(formData,
                            {
                                action: "managedataset",
                                method: "updateDistribution",
                                module: "xlyre",
                                id: distribution.id
                            },
                            function(data){
                                if (data && data.errors) {
                                    showErrorMessage(data.errors[0]);
                                }
                                else {
                                    $scope.editing = false;
                                }
                            }

                        );
                    }

                    var uploadDistribution = function(distribution, file){
                        $scope.uploadButtonLabel = xTranslate('xlyre.actions.managedataset.distribution.uploading');
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
                                module:'xlyre', 
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
                                        $scope.uploadButtonLabel = xTranslate('xlyre.actions.managedataset.distribution.save');
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
                            $scope.defaultTitle = $scope.getDefaultTitle(languages);
                    }, true);

                    $scope.getDefaultTitle = function(languages){
                        var title;
                        if (languages[$scope.defaultLanguage]){
                            title = languages[$scope.defaultLanguage];
                        } else {
                            for (langTitle in languages){
                                if (languages[langTitle]) {
                                    title = languages[langTitle];
                                }
                            }
                        }
                        return title;
                    }

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
                            }, xTranslate('xlyre.actions.managedataset.distribution.delete_warn'));//TODO: Write a translation service and filter
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
                            $scope.uploadButtonLabel = xTranslate('xlyre.actions.managedataset.distribution.save');
                        } else if ($scope.distribution.id) {;
                            if ($scope.backupDistribution)    
                                $scope.distribution = $scope.backupDistribution;
                            $scope.editing = false;
                            $scope.dist_form.$setPristine();
                            $scope.queue = [];
                        } else {
                            $scope.deleted = true;    
                        }
                    }

                    $scope.$watch('queue.length', function(newVal, oldVal){
                        if (newVal){
                            $scope.uploadButtonLabel = xTranslate('xlyre.actions.managedataset.distribution.upload');
                        }
                    });    
                }]
            }
        }]);
    angular.module('ximdex').registerItem('xlyreDistribution');
}
//Start angular compile and binding
X.actionLoaded(function(event, fn, params) {
    var scope = X.angularTools.initView(params.context, params.tabId);
    scope.setActionNode = function(nodeId){
        params.actionView.setNode(nodeId);
    };
    $(document).on('nodemodified', function(e, nodeId){
        if (params.actionView.nodes.length == 1 && params.actionView.nodes[0] === nodeId) {   
            scope.$destroy();
            scope = null;
            params.actionView.reloadAction();
        }
    });    
});
