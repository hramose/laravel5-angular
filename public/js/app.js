
var app = angular.module('App', ['ui.router','angular-loading-bar','smart-table'])

//Routes
.config(['$urlRouterProvider','$stateProvider', '$interpolateProvider', function($urlRouterProvider,$stateProvider,$interpolateProvider){

	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');

	$urlRouterProvider.otherwise('/');

	$stateProvider
		.state('home', { 
			url: '/', 
			templateUrl : 'views/home.html',
			controller : 'HomeCtrl'
		}).state('settings', { 
			url: '/settings', 
			templateUrl : 'views/settings.html',
			controller : 'SettingsCtrl'
		});;
	
	/*
	| Get pages from config.js
	| Create a route for each page
	*/
	for (var i=0 ; i < pages.length ; i++){

		var data = pages[i];

		if (data.subMenu !== undefined){
			for (var j=0 ; j < data.subMenu.length ; j++){
				var subData = data.subMenu[j];

				$stateProvider.state(subData.name, { 
					url: subData.url, 
					templateUrl : subData.templateUrl,
					controller : "PageCtrl"
				});
			}

		} else {
			$stateProvider.state(data.name, { 
				url: data.url, 
				templateUrl : data.templateUrl,
				controller : "PageCtrl"
			});
		}
	}

}])

.run(function($rootScope,$location,$http,$window){

	//When route changes check if still logedin
	//if not redirect to the siteUrl which comes from .env file
	$rootScope.$on('$locationChangeStart', function(event) {
		
		$http.get('authCheck').success(function(data){
			if (data === false){
				$window.location.href = siteUrl;
			}
		}).error(function(data){
			$window.location.href = siteUrl;
		})
	});

	$rootScope.appLoadSuccess = 0;
	//Get page roles
	$http.get("roles/app-roles").success(function(data){

		if (data.code == 400){
			$rootScope.appError = 1;
			$rootScope.appErrors = data.errors;
		} else {
			$rootScope.appLoadSuccess = 1;
			$rootScope.user_roles = data[0];
		}
	}).error(function(){
		$rootScope.appError 	= 1;
		$rootScope.appErrors 	= [lang.roles_retrieve_fail];
	});

})

.filter('capitalize', function() {
    return function (value) {
        return value.charAt(0).toUpperCase() + value.slice(1);
    };
});;

app.controller('HomeCtrl', ['$scope','$location', '$http', '$window', function($scope, $location, $http, $window){

	/*
	| Get language object from HomeController.php
	| and pass it into $scope.lang
	| This way we can use this object in any angular template files.
	*/
	$scope.lang 	= lang;

}]);

app.controller('SettingsCtrl', ['$scope','$location', '$http', '$window', function($scope, $location, $http, $window){
	
	$scope.lang 	= lang;

	$scope.error 		= 0;
	$scope.errors 		= [];
	$scope.success 		= 0;
	$scope.successMsg 	= "";

	$scope.settings = {};

	$scope.saveSettings	= function(){

		var s_data 					= {};
		s_data['old'] 				= $scope.settings.old_password;
		s_data['new'] 				= $scope.settings.new_password;
		s_data['new_confirmation'] 	= $scope.settings.new_again;
	
		$http.post('settings/save',s_data).success(function(data){

	    	if (data.code === 400){
	    		$scope.error = 1;
		    	$scope.errors = data.errors[0];
		    	$scope.success 		= 0;
		    	$scope.successMsg 	= "";
	    	} else {
	    		$scope.error 		= 0;
	    		$scope.errors 		= [];
	    		$scope.success 		= 1;
		    	$scope.successMsg 	= lang.settings_success;
	    	}
	    }).error(function(data){
	    	//
	    });
	};

}]);

app.controller('NavController', ['$scope', '$rootScope', function($scope,$rootScope){

	//pages from config.js
	this.routes = pages;
	this.activeLink = 0;
	this.activeSubLink;
}]);


app.controller('PageCtrl', ['$scope', '$http', '$location',  function($scope,$http,$location){

	//Get language object from HomeController.php
	$scope.lang 	= lang;

	//Get logedin username from HomeController.php
    $scope.logedin 	= logedin;

	/*
	| Get current path and search in pages object(config.js)
	*/
	this.currentPath	= $location.path();

	//Get current page object : this.pageObj
	for (var i=0 ; i < pages.length ; i++){
		var data = pages[i];
		if (data.subMenu !== undefined){
			for (var j=0 ; j < data.subMenu.length ; j++){
				var subData = data.subMenu[j];

				if (subData.url === this.currentPath){
					this.pageObj 	= subData;
				}
			}
		} else {
			if (data.url === this.currentPath){
				this.pageObj 	= data;
			}
		}
	}

	//Page settings
	$scope.pages 		= pages;
	$scope.pageTitle	= this.pageObj.title;
	$scope.pageName		= this.pageObj.name;
	$scope.icon			= this.pageObj.icon;
	var prefix			= this.pageObj.prefix;
	var fields			= this.pageObj.fields;

	$scope.add 		= 0;
	$scope.showed	= 0;
	$scope.success 	= 0;
	$scope.edited 	= 0;
	$scope.error 	= 0;

	$scope.getList = function(){
		
		//DONT FORGET
		$scope.add		= 0;
		$scope.error 	= 0;
	    $scope.errors 	= "";

		$http.get(prefix+"/data").success(function(data){
			$scope.itemsByPage 			= 10;
	    	$scope.rowCollection 		= data;
	    	$scope.displayedCollection 	= [].concat($scope.rowCollection);
	    }).error(function(data){
	    	$scope.error = 1;
			$scope.errors = [lang.list_retrieve_error];
	    });
	}

	$scope.getList();

	$scope.show = function(id){
		
		$scope.add		= 1;
		$scope.showed	= 1;
		$scope.reset();

		//Lookup
		for (i=0 ; i<fields.length ; i++){
			var f = fields[i];
			if (f.lookup !== undefined){
				$http.get(f.lookup.url).success(function(data){
					$scope[f.name+"_options"] = data;
				});
			}
		}

		if (id !== undefined){
			$scope.edited 	= 1;
			$scope.editId 	= id;

			$http.get(prefix+'/edit/'+id).success(function(data){
				if (data.code === 400){
					$scope.error = 1;
		    		$scope.errors = [lang.record_not_found];
				} else {
					for (i=0 ; i<fields.length ; i++){
						if (data[0][fields[i].name] == "" && angular.isObject($scope[fields[i].name])){
							$scope[fields[i].name] = {};
						} else {
							//Lookup
							if (fields[i].lookup !== undefined) {
								var f = fields[i];
								$http.get(f.lookup.itemUrl+"/"+data[0][fields[i].name]).success(function(data){
									$scope[f.name] = data[0];
								});
							} else {
								$scope[fields[i].name] = data[0][fields[i].name];
							}
						}
					}
				}
			});
		}
	}

	$scope.edit = function(id){
		
		$scope.add		= 1;
		$scope.showed	= 0;
		$scope.reset();

		//Lookup
		for (i=0 ; i<fields.length ; i++){
			var f = fields[i];
			if (f.lookup !== undefined){
				$http.get(f.lookup.url).success(function(data){
					$scope[f.name+"_options"] = data;
				});
			}
		}

		if (id !== undefined){
			$scope.edited 	= 1;
			$scope.editId 	= id;

			$http.get(prefix+'/edit/'+id).success(function(data){
				if (data.code === 400){
					$scope.error = 1;
		    		$scope.errors = [lang.record_not_found];
				} else {
					for (i=0 ; i<fields.length ; i++){
						if (data[0][fields[i].name] == "" && angular.isObject($scope[fields[i].name])){
							$scope[fields[i].name] = {};
						} else {
							$scope[fields[i].name] = data[0][fields[i].name];
						}
					}
				}
			});
		}
	}


	//Save button
	$scope.save = function(){

		if ($scope.edited === 1){
			
			var formData = {};

			for (i=0 ; i<fields.length ; i++){

				if (fields[i].editable === true){
					var key = fields[i].name;
					var val = $scope[fields[i].name];

					formData[key] = val;
				}
			}

			$http.post(prefix+'/editsave/'+$scope.editId,formData).success(function(data){
		    	if (data.code === 400){
		    		$scope.error = 1;
		    		$scope.errors = data.errors[0];
		    	} else {
		    		$scope.success = 1;
		    		$scope.successMsg = lang.edit_save_success;
		    		$scope.getList();
		    	}
		    	
		    });

		} else {
			var formData = {};

			for (i=0 ; i<fields.length ; i++){

				var key = fields[i].name;
				var val = $scope[fields[i].name];

				formData[key] = val;
			}

			$http.post(prefix+'/create',formData).success(function(data){
		    	if (data.code === 400){
		    		$scope.error = 1;
		    		$scope.errors = data.errors[0];
		    	} else {
		    		$scope.success = 1;
		    		$scope.successMsg = lang.add_save_success;
		    		$scope.getList();
		    	}
		    });
		}
		
	}

	$scope.del = function(id){

		if (id !== undefined){

			$http.post(prefix+'/delete/'+id).success(function(data){
		    	if (data.code === 400){
		    		$scope.error = 1;
		    		$scope.errors = data.errors[0];
		    	} else {
		    		$scope.success = 1;
		    		$scope.successMsg = lang.deleted_success;
		    		$scope.getList();
		    	}
		    });
		}
	}

	$scope.cancel = function(){
		$scope.reset();
		$scope.getList();
	}

	$scope.reset	= function(){
		$scope.edited 		= 0;
		$scope.error 		= 0;
		$scope.successMsg 	= "";
		$scope.success 		= 0;

		for (i=0 ; i<fields.length ; i++){
			var asdas = $scope[fields[i].name];
			if (angular.isObject($scope[fields[i].name])){
				$scope[fields[i].name] = {};
			} else {
				$scope[fields[i].name] = "";
			}
		}
	}
    
}]);


