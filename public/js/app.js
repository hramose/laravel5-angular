
var app = angular.module('App', ['ui.router','angular-loading-bar'])

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
		});

	
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
					controller : 'HomeCtrl'
				});
			}

		} else {
			$stateProvider.state(data.name, { 
				url: data.url, 
				templateUrl : data.templateUrl,
				controller : 'HomeCtrl'
			});
		}
		
		
	}

}]);

app.controller('HomeCtrl', ['$scope','$location', '$http', '$window', function($scope, $location, $http, $window){

	/*
	| Get language object from HomeController.php
	| and pass it into $scope.lang
	| This way we can use this object in any angular template files.
	*/
	$scope.lang 	= lang;
	
	/*
	| Get pages from config.js
	| Look to the current location and search for same path in pages object
	| Pass the result to the scope so we can use it in ui-view
	*/
	var currentPath	= $location.path();
	var currentPageArr;

	for (var i=0 ; i < pages.length ; i++){
		if (pages[i].url === currentPath){
			currentPageArr = pages[i];
		}
	}
	$scope.page	= currentPageArr;


	$scope.$on('$locationChangeStart', function(event) {
	    
	    $http.post('authCheck')
			.error(function(){
		    	$window.location.href = siteUrl;
		    });
	});

}]);


app.controller('NavController', ['$scope', function($scope){

	//pages from config.js
	this.routes = pages;
	this.activeLink = 0;
	this.activeSubLink;
}]);

