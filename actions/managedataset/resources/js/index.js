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
angular.module('ximdex.module.xlyre')
    .controller('XLyreDatasetCtrl', ['$scope', '$attrs', 'xBackend', '$timeout', function($scope, $attrs, xBackend, $timeout){

        $scope.selectedLanguages = {};
        $scope.distributions = [];

        $scope.$watch('dataset.languages', function(languages, oldLanguages){
            $scope.activeLanguages = 0;
            for (key in languages) {
                if (languages[key] != '')
                    $scope.activeLanguages++;
                    $scope.defaultLanguage = $scope.defaultLanguage || key;
            }
        }, true);

        $scope.addDistribution = function(distribution) {
            console.log("ADDINGo", distribution);
            $scope.newDistributions = $scope.newDistributions || [];
            $scope.newDistributions.unshift(distribution);   
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
    .controller('XLyreDistributionCtrl', ['$scope', '$attrs', 'xUrlHelper', '$timeout', function($scope, $attrs, xUrlHelper, $timeout){
        var progressCallback = function (event, data) {
            $scope.$apply(function(){
                $scope.uploadProgress = parseInt(data.loaded / data.total * 100, 10);
            });
        }
        
        $scope.uploadButtonLabel = 'Save Distribution';
        $scope.addFileLabel = 'Atach File';
        $scope.uploadState = 'pending';

        $scope.uploadDistribution = function (metadata, file) {
            
            if(metadata && file) {
                $scope.uploadButtonLabel = "Uploading";
                $scope.uploadProgress = 0;
                
                $scope.fileUploaderOptions = {
                    url: xUrlHelper.getAction({action:'managedataset', method:'addDistribution', IDParent: $attrs.ximNodeid}),
                    progress: progressCallback
                };

                var formData = []
                formData.push({
                    name: 'languages',
                    value: angular.toJson(metadata)
                });
                file.$formData(formData);
                $timeout(function(){
                    file.$submit()
                        .success(function(data){
                            $scope.addDistribution(data.distribution);
                            $scope.uploadButtonLabel = "Done";
                            $scope.uploadButtonLabel = "Save Distribution";
                            $scope.$parent.newDistribution = null;
                            $scope.queue = [];
                    });
                });
            }
        }
    }]);  

//Start angular compile and binding
X.actionLoaded(function(event, fn, params) {
    X.angularTools.initView(params.context, params.tabId);    
});
