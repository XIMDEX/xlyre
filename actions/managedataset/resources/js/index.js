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
 if (angular.module('ximdex.module.xlyre')._invokeQueue.length == 0){
    console.log("Registering xlyre angyular module");
    angular.module('ximdex.module.xlyre', []);
    angular.module('ximdex.module.xlyre')
        .controller('XLyreDatasetCtrl', ['$scope', '$attrs', 'xBackend', '$timeout', function($scope, $attrs, xBackend, $timeout){

            $scope.selectedLanguages = {};
            //$scope.languages = angular.fromJson($scope.languages);
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
                var formData = angular.copy(dataset);
                formData.languages = [];
                for (var language in dataset.languages) {
                    if (language) {
                        formData.languages.push(language);
                    }
                }
                xBackend.sendFormData(formData, {action: $attrs.ximAction, method: $attrs.ximMethod, id: dataset.id, IDParent: dataset.IDParent}, function(data){ 
                    console.log("Recieved data: ", data);
                    $attrs.ximMethod = 'updatedataset';
                    if (!dataset.id && data && data.dataset && data.dataset.id) {
                        dataset.id = data.dataset.id;
                        dataset.issued = data.dataset.issued;
                        dataset.modified = data.dataset.modified;
                        $attrs.ximMethod = 'updatedataset';
                    }
                    if (data && data.messages) {
                        $scope.submitMessages = data.messages;
                        $timeout(function(){
                            $scope.submitMessages = null;
                        }, 4000);
                    }
                });
                    
            }
        }]);

    angular.module('ximdex.module.xlyre')
        .directive('xlyreDistribution', ['$window', function ($window) {
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
                controller: ['$scope', '$element', '$attrs', '$transclude', '$http', '$timeout', 'xUrlHelper',function($scope, $element, $attrs, $transclude, $http, $timeout, xUrlHelper){

                    $scope.uploadButtonLabel = 'Save Distribution';
                    $scope.addFileLabel = 'Atach File';
                    $scope.uploadState = 'pending';
                    if (!$scope.distribution.id) 
                        $scope.edit = true
                    $scope.distribution.languages = $scope.distribution.languages || []
                    var progressCallback = function (event, data) {
                        $scope.$apply(function(){
                            $scope.uploadProgress = parseInt(data.loaded / data.total * 100, 10);
                        });
                    }

                    var updateDistributionMetadata = function (distribution) {
                        console.log("updating metadata ", distribution);
                    }

                    var uploadDistribution = function(distribution, file){
                        $scope.uploadButtonLabel = "Uploading";
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
                            url: xUrlHelper.getAction({action:'managedataset', method:method || 'addDistribution', IDParent: $attrs.ximNodeid}),
                            progress: progressCallback
                        };
                        console.log(formData, file);
                        $timeout(function(){
                            file.$submit()
                                .success(function(data){
                                    if (data && data.distribution) {
                                        console.log("DISTRIBUYA", distribution);
                                        $scope.distribution = data.distribution;
                                        $scope.edit = false;
                                    }
                                    $scope.uploadButtonLabel = "Done";
                                    $scope.uploadButtonLabel = "Save Distribution";
                                    $scope.$parent.newDistribution = null;
                                    $scope.queue = [];
                            });
                        });
        
                    }

                    $scope.$watch('distribution.languages',function(languages){
                        if (languages)
                            $scope.defaultTitle = languages[$scope.defaultLanguage];
                    }, true);

                    $scope.$watch('edit',function(newValue, oldValue){
                        if (newValue != oldValue) {
                            $scope.queue = [];
                            if (newValue) {
                                $scope.originalDistribution = angular.copy($scope.distribution);
                            } else {
                                if (!$scope.distribution.id) {
                                    $scope.deleted = true;
                                } else if ($scope.originalDistribution){
                                    $scope.distribution = $scope.originalDistribution;
                                }
                            }
                        }   
                    });

                    $scope.saveDistribution = function (distribution, file) {
                        console.log("Saving", distribution, file);
                        if (file) { 
                            uploadDistribution(distribution, file)
                        } else if (distribution && distribution.id) {
                            updateDistributionMetadata(distribution);
                        }
                    }
                    $scope.deleteDistribution = function (distribution) {
                        if(distribution && distribution.id) {
                           $scope.deleted = true;
                           $http.post(xUrlHelper.getAction({action:'managedataset', method:'deleteDistribution', id: distribution.id}), {id:distribution.id}).success(function(data){
                                if (data && data.message) {
                                    $scope.deleted = true;
                                }
                           });
                        }
                    }    
                }],
                link: function postLink(scope, element, attrs) {
                    //console.log('LINKING', attrs.ximDefaultLanguage);
                }
            }
        }]);
}
//Start angular compile and binding
X.actionLoaded(function(event, fn, params) {
    X.angularTools.initView(params.context, params.tabId);    
});
