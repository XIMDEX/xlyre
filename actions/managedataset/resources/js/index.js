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
        //Main ximdex module
        angular.module('ximdex', ['ximdex.common', 'ximdex.vendor', 'xlyre']);
        //Third party modules
        angular.module('ximdex.vendor', ['blueimp.fileupload']);
        //Common modules
        angular.module('ximdex.common', ['ximdex.common.service', 'ximdex.common.directive', 'ximdex.common.filter']);
        angular.module('ximdex.common.directive', []);
        angular.module('ximdex.common.service', []);
        angular.module('ximdex.common.filter', []);
        
        //"Ximdex module" specific modules 
        angular.module('xlyre', []);
        
        //Configure interpolation symbols to work in smarty templates
        angular.module('ximdex')
            .config(function($interpolateProvider) {
                $interpolateProvider.startSymbol('[[');
                $interpolateProvider.endSymbol(']]');
        });

        //TODO: Load global services and directives globally
        //SERVICES 
        angular.module('ximdex.common.service')//Abstraction for server communications. TODO: Expose to client a REST like interface
            .factory('xBackend', ['$http', '$rootScope', 'xTree', 'xUrlHelper', function($http, $rootScope, xTree, xUrlHelper) {
                return {
                    sendFormData: function(formData, params, callback){
                        var actionUrl = xUrlHelper.getAction(params);
                        if (actionUrl) {
                            $http({
                                    method  : 'POST',
                                    url     : actionUrl,
                                    data    : $.param(formData),  // pass in data as strings
                                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
                            }).success(function(data) {         
                                    if (formData.IDParent || formData.id)
                                        $rootScope.$broadcast('nodeModified', formData.IDParent || formData.id);
                                    callback(data);
                            });
                        }  
                    }
                }
        }]);

        angular.module('ximdex.common.service')//Abstraction for server communications. TODO: Expose to client a REST like interface
            .factory('xUrlHelper', ['$window', function($window) {
                return {
                    baseUrl: function() {
                        return $window.X.restUrl;
                    },
                    getAction: function(params){
                        var timestamp = new Date().getTime();
                        var actionUrl = this.baseUrl()+'?noCacheVar='+timestamp+'&action='+params.action+'&method='+params.method;
                        if (params.id) {
                            actionUrl+='&nodeid='+params.id+'&nodes[]='+params.id;
                        } else if (params.IDParent) {
                            actionUrl+='&nodeid='+params.IDParent+'&nodes[]='+params.IDParent;
                        }
                        return actionUrl;
                    }
                }
        }]);
        
        angular.module('ximdex.common.service')
            .factory('xTree', ['$window', '$rootScope', function($window, $rootScope) { 
                //Listen for node modification events to update the tree
                $rootScope.$on('nodeModified', function(event, nodeId){
                    $window.jQuery('li#treeview-nodeid-'+nodeId)
                        .closest('div.xim-treeview-container')
                        .treeview('refresh', nodeId);
                });
        }]);

        //CONTROLLER
        angular.module('xlyre')
            .controller('XDistibution', ['$scope', '$attrs', 'xBackend', '$timeout', function($scope, $attrs, xBackend, $timeout){

                $scope.selectedLanguages = {};
                
                $scope.$watch('dataset.languages', function(languages, oldLanguages){
                    $scope.activeLanguages = 0;
                    $scope.defaultLanguage = null;
                    for (key in languages) {
                        if (languages[key] != '')
                            $scope.activeLanguages++;
                            $scope.defaultLanguage = $scope.defaultLanguage || key;
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
                    console.log("que submito", dataset);
                    xBackend.sendFormData(formData, {action: $attrs.ximAction, method: $attrs.ximMethod, id: dataset.id, IDParent: dataset.IDParent}, function(data){ 
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

        angular.module('xlyre')
            .controller('XLyreUploader', ['$scope', '$attrs', 'xUrlHelper', '$timeout', function($scope, $attrs, xUrlHelper, $timeout){
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
                                    console.log("Recieved data", data);
                                    $scope.uploadButtonLabel = "Done";
                                    $scope.newDistributions = $scope.newDistributions || [];
                                    $scope.newDistributions.unshift(data.distribution);
                                    $scope.uploadButtonLabel = "Save Distribution";
                                    $scope.$parent.newDistribution = null;
                                    $scope.queue = [];
                            });
                        });
                    }
                }
        }]);  
        
        //DIRECTIVES
        angular.module('ximdex.common.directive')
            .directive('ximButton', ['$window', function ($window) {
                return {
                    replace: true,
                    scope: {
                        state: '=ximState',
                        disabled: '=ximDisabled',
                        label: '=ximLabel',
                        progress: '=ximProgress'
                    },
                    restrict: 'A',
                    template: '<button type="button" class="button ladda-button" data-style="slide-up" data-size="xs" ng-disabled="disabled">'+
                            '<span class="ladda-label">[[label]]</span>'+
                        '</button>',
                    link: function postLink(scope, element, attrs) {
                        var loader = $window.Ladda.create(element[0]);
                        scope.$watch('state', function(newState, oldState){
                            console.log("stating", newState);
                            switch (newState) {
                                case 'submitting':
                                case 'pending':
                                    loader.start();
                                    break;
                                case 'resolved':
                                    loader.stop();
                                    break;
                            }
                        });
                        scope.$watch('progress', function(newValue, oldValue){
                            console.log("progress", newValue);
                            if (oldValue != newValue)
                                loader.setProgress(newValue)
                        });
                    }
                }
        }]);

        //FILTERS
        angular.module('ximdex.common.filter')
            .filter('ximBytes', function(){
                return function(bytes){
                    if (isNaN(parseFloat(bytes)) || !isFinite(bytes))
                        return ''
                    units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'];
                    number = Math.floor(Math.log(bytes) / Math.log(1024));
                    return (bytes / Math.pow(1024, Math.floor(number))).toFixed(2) +' '+ units[number];
                }
        });

        //INITIALIZE ANGULAR APP
        angular.bootstrap(fn('form'), ['ximdex']);
});

