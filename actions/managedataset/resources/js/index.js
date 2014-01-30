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
                    // REALLY REALLY BAD PRACTIVE JUS A HACK TO DEAL WITH THE TREE
                    reloadNode: function(nodeId) {
                        $window.jQuery('li#treeview-nodeid-'+nodeId)
                            .closest('div.xim-treeview-container')
                            .treeview('refresh', nodeId);
                    }    
                }
        }]);

        // ximdexModule.factory('xNotifications', ['$window', function($window) { 
        //     return {
        //         notify: function(notice, class) {       
        //         }    
        //     }
        // }]);

        //CONTROLLER
        ximdexModule
            .controller('XDistibution', ['$scope', '$attrs', 'xBackend', '$timeout', function($scope, $attrs, xBackend, $timeout){

                $scope.selectedLanguages = {};
                $scope.submitUrl = $attrs.action;

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
            .controller('XUploader', ['$scope', '$attrs', function($scope, $attrs){
                $scope.fileUploaderOptions = {
                    url: $attrs.xUploadUrl
                };
                $scope.uploadDistribution = function (metadata, file) {
                    console.log("Uploading", metadata, file);
                    if(metadata && file) {
                        file.$formData(metadata);
                        file.$submit()
                            .success(function(data){
                                console.log("File Upladed", data);
                            });
                    }
                }
        }]);  
        
        //DIRECTIVES
        // ximdexModule
        //     .directive('xButton', function () {
        //         return {
        //             replace: true,
        //             template: '<button type="submit" class="button ladda-button" data-style="slide-up" data-size="xs" ng-disabled="btnDisabled">'+
        //                     '<span class="ladda-label">{{btnLabel}}</span>'+
        //                 '</button>',
        //             controller: ['$scope', '$attrs', '$parse', function ($scope, $attrs, $parse){
        //                 console.log("controlling directive");
        //                 $scope.btnLabel = "fede";
        //                 if ($attrs.xProgress) {
        //                     var fn = $parse($attrs.progress);
        //                     var update = function(){
        //                         var progress = fn($scope);
        //                         if (!progress || !progress.total)
        //                             return
        //                         $scope.num = progress.loaded/progress.total;
        //                     }
        //                     update();
        //                     $scope.$watch($attrs.xProgress+'.loaded', function(newValue, oldValue){
        //                         update();
        //                     });
        //                 }
        //             }],
        //             link: function(scope, element, attrs){
        //                 console.log("linking directive");
        //                 var loader = $window.Ladda.create(element[0]);

        //                 scope.$watch(attrs.xLabel, function(newValue, oldValue) {
        //                     console.log("WATHCING LABELS", newValue);
        //                     if(oldValue != newValue){
        //                         $scope.btnLabel = newValue
        //                     }
        //                 });

        //                 if (attrs.xProgress){
        //                     scope.$watch('num', function(newValue, oldValue){
        //                         if (oldValue != newValue)
        //                             loader.setProgress(newValue)
        //                     });
        //                 }
        //                 if (attrs.xState){
        //                     scope.$watch(attrs.xState, function(newValue, oldValue) {
        //                         if(oldValue != newValue){
        //                             switch(newValue){
        //                                 case 'submitting':
        //                                     loader.start();
        //                                     break;
        //                                 case 'succes':
        //                                     loader:stop();
        //                                     break;
        //                                 case 'error':
        //                                     loader:stop();
        //                                     break;
                                    
        //                             }
        //                         }
        //                     });
        //                 }
        //             }
        //         };
        // });

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

