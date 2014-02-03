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
        var ximdexModule = angular.module('ximdex', ['blueimp.fileupload']);
        
        ximdexModule.config(function($interpolateProvider) {
                $interpolateProvider.startSymbol('[[');
                $interpolateProvider.endSymbol(']]');
        });

        //TODO: Load global services and directives globally
        //SERVICES 
        ximdexModule
            .factory('xBackend', ['$http', '$rootScope', 'xTree', function($http, $rootScope, xTree) {
                return {
                    sendFormData: function(formData, url, callback){
                        $http({
                                method  : 'POST',
                                url     : url,
                                data    : $.param(formData),  // pass in data as strings
                                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
                        }).success(function(data) {         
                                if (formData.IDParent)
                                    xTree.reloadNode(formData.IDParent);
                                callback(data);
                        });
                    }
                }
        }]);
        
        ximdexModule
            .factory('xTree', ['$window', '$rootScope', function($window, $rootScope) { 
                return {
                    // REALLY REALLY BAD PRACTIVE JUST A HACK TO DEAL WITH THE TREE
                    reloadNode: function(nodeId) {
                        $window.jQuery('li#treeview-nodeid-'+nodeId)
                            .closest('div.xim-treeview-container')
                            .treeview('refresh', nodeId);
                    }    
                }
        }]);

        //CONTROLLER
        ximdexModule
            .controller('XDistibution', ['$scope', '$attrs', 'xBackend', '$timeout', function($scope, $attrs, xBackend, $timeout){

                $scope.selectedLanguages = {};
                $scope.submitUrl = $attrs.action;

                $scope.$watch('dataset.languages', function(languages, oldLanguages){
                    $scope.activeLanguages = 0;
                    for (key in languages) {
                        if (languages[key] != '')
                            $scope.activeLanguages++;
                    }
                }, true);

                $scope.submitForm = function(form, dataset){
                    var formData = angular.copy(dataset);
                    formData.languages = []
                    for (var language in dataset.languages) {
                        if (language) {
                            formData.languages.push(language);
                        }
                    }
                    console.log($scope.submitUrl);

                    xBackend.sendFormData(formData, $scope.submitUrl, function(data){ 
                        if (!dataset.id && data.dataset.id) {
                            dataset.id = data.dataset.id;
                            dataset.issued = data.dataset.issued;
                            dataset.modified = data.dataset.modified;
                            $scope.submitUrl = $scope.submitUrl.replace('createdataset', 'updatedataset');
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

        ximdexModule
            .controller('XUploader', ['$scope', '$attrs', '$timeout',function($scope, $attrs, $timeout){
                var progressCallback = function (event, data) {
                    $scope.$apply(function(){
                        $scope.uploadProgress = parseInt(data.loaded / data.total * 100, 10);
                    });
                }
                $scope.fileUploaderOptions = {
                    url: $scope.submitUrl.replace("updatedataset", 'addDistribution'),
                    progress: progressCallback
                };
                $scope.uploadButtonLabel = 'Save Distribution';
                $scope.addFileLabel = 'Atach File';

                $scope.uploadDistribution = function (metadata, file) {
                    console.log("Uploading", metadata, file);
                    if(metadata && file) {
                        $scope.uploadButtonLabel = "Uploading";
                        $scope.uploadProgress = 0;
                        var formData = []
                        formData.push({
                            name: 'languages',
                            value: angular.toJson(metadata)
                        });
                        file.$formData(formData);
                        // var url = $scope.submitUrl.replace("updatedataset", 'addDistribution');
                        // for (key in metadata) {
                        //     url+='&languages['+key+']='+metadata[key];
                        // }
                        // console.log(url);
                        // $scope.fileUploaderOptions = {
                        //     url: url,
                        //     progress: progressCallback
                        // };
                        file.$submit()
                            .success(function(data){
                                $scope.uploadButtonLabel = "Done";
                                $scope.newDistributions = $scope.newDistributions || [];
                                $scope.newDistributions.unshift({
                                    name: file.name,
                                    format: file.type,
                                    size: file.size,
                                    created: file.lastModifiedDate,
                                    modified: file.lastModifiedDate,
                                    languages: angular.copy(metadata)
                                });
                                $scope.uploadButtonLabel = "Save Distribution";
                                $scope.$parent.newDistribution = null;
                                $scope.queue = [];
                        });
                    }
                }
        }]);  
        
        //DIRECTIVES
        ximdexModule
            .directive('ximButton', ['$window', function ($window) {
                return {
                    replace: true,
                    scope: {
                        state: '=ximState',
                        disabled: '=ximDisabled',
                        label: '=ximLabel',
                        progress: '=ximProgress'
                    },
                    template: '<button type="button" class="button ladda-button" data-style="slide-up" data-size="xs" ng-disabled="disabled">'+
                            '<span class="ladda-label">[[label]]</span>'+
                        '</button>',
                    controller: ['$scope', '$attrs', function ($scope, $attrs){
                        console.log("controlling directive");

                    }],
                    linking: function (scope, element, attrs) {
                        console.log("controlling directive");
                        var loader = $window.Ladda.create(element[0]);
                        scope.$watch('state', function(newState, oldState){
                            console.log(newState);
                            switch (newState) {
            

                            }
                        });
                    },
                }
        }]);

        

        // ximdexModule.directive('xVlidate', function () {
        //     return {
        //         template: '<div></div>',
        //         transclude: 'element',
        //         restrict: 'A',
        //         link: function postLink(scope, element, attrs) {
        //             element.text('this is the directita directive');
        //         }
        //     };
        // });


        //INITIALIZE ANGULAR APP
        angular.bootstrap(fn('form'), ['ximdex']);
});

