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
        ximdexModule.factory('xBackend', ['$http', '$rootScope', 'xTree', function($http, $rootScope, xTree) {
                return {
                        sendFormData: function(formData, url, callback){
                                console.log("Sending data", $.param(formData));
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
        
        ximdexModule.factory('xTree', ['$window', '$rootScope', function($window, $rootScope) { 
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
                        console.log("Recieved data", formData.nodeid);  
                        if (!dataset.id && data.IDDataset) {
                            dataset.id = data.IDDataset;
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
                console.log("XUPLOADER INITIALIZEd");
                $scope.fileUploaderOptions = {};
                
        }]);  
        
        //DIRECTIVES
        // ximdexModule.directive('xButton', function () {
        //     return {
        //         template: '<div></div>',
        //         restrict: 'A',
        //         link: function postLink(scope, element, attrs) {
        //             element.text('this is the directita directive');
        //         }
        //     };
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

