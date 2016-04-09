// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
var app = angular.module('sw_spotter', ['ionic','ngCordova', 'sw_spotter.controllers','angular-jwt'])





.config(function($compileProvider){
  $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|tel):/);
})

//api dataservice
.service('dataService', ['$http', function ($http) {

        var urlBase = 'https://app.sea-watch.org/admin/public/api/';

        this.getCases = function () {
          return $http({
              url: urlBase+'cases/spotter', 
              method: "GET",
              params: {session_token: 1337}
           });
        };

        this.updateVehiclePosition = function (options) {
          return $http({
            method: 'POST',
            url: urlBase+'vehicle/updatePosition',
            headers: {
              'Content-Type': 'application/json',
              Authorization: 'Bearer '+window.localStorage['jwt']
            },
            data: {session_token: 1337, position:options.position}
          });
        };

        this.createCase = function (options){
        console.log(options.params.location_data);
          return $http({
              url: urlBase+'cases/create', 
              method: "POST",
              params: options.params
           });
        };
        this.updateCases = function (options) {
          return $http({
            method: 'POST',
            url: urlBase+'cases/reloadSpotter',
            headers: {
              'Content-Type': 'application/json',
              Authorization: 'Bearer '+window.localStorage['jwt']
            },
            data: {cases:options.cases}
          });
        };

        this.auth = function (options){
          return $http({
              url: urlBase+'user/auth', 
              method: "POST",
              params: options.params
           });
        };

        this.getCustomer = function (id) {
            return $http.get(urlBase + '/' + id);
        };

        this.insertCustomer = function (cust) {
            return $http.post(urlBase, cust);
        };

        this.updateCustomer = function (cust) {
            return $http.put(urlBase + '/' + cust.ID, cust)
        };

        this.deleteCustomer = function (id) {
            return $http.delete(urlBase + '/' + id);
        };

        this.getOrders = function (id) {
            return $http.get(urlBase + '/' + id + '/orders');
        };
    }])


//camera factory
.factory('Camera', ['$q', function($q) {

  return {
    getPicture: function(options) {
      var q = $q.defer();

      navigator.camera.getPicture(function(result) {
        // Do any magic you need
        q.resolve(result);
      }, function(err) {
        q.reject(err);
      }, {
                        quality: 20,
                        destinationType: Camera.DestinationType.DATA_URL
                    });

      return q.promise;
    }
  }
}])
.controller('VehicleCtrl',['$scope', 'dataService', function ($scope, dataFactory) {

  //$scope.cases;
  $scope.updateVehiclePosition = function(cb) {
        dataFactory.updateVehiclePosition({position:{accuracy:currentPosition.coords.accuracy, altitudeAccuracy: currentPosition.coords.altitudeAccuracy, heading: currentPosition.coords.heading,  speed: currentPosition.coords.speed,  latitude: currentPosition.coords.latitude,  longitude: currentPosition.coords.longitude}})
            .success(function (result) {
                console.log(result.data);
                if(typeof cb === 'function'){
                  cb();
                }
            })
            .error(function (error) {
              if(error !== null)
                $scope.status = 'Unable to load customer data: ' + error.message;
            });
  }
}])
.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})
.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

    .state('app', {
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'MenuCtrl'
  })

  .state('app.search', {
    url: '/search',
    views: {
      'menuContent': {
        templateUrl: 'templates/search.html'
      }
    }
  })

  .state('app.overview', {
      url: '/overview',
      views: {
        'menuContent': {
          templateUrl: 'templates/overview.html',
          controller: 'AppCtrl'
        }
      }
    })
    .state('app.cases', {
      url: '/cases',
      views: {
        'menuContent': {
          templateUrl: 'templates/all_cases.html',
          controller: 'CasesCtrl'
        }
      }
    })
  .state('app.single', {
    url: '/cases/:caseId',
    views: {
      'menuContent': {
        templateUrl: 'templates/case.html',
        controller: 'CaseCtrl'
      }
    }
  })
  .state('app.caseChat', {
    url: '/cases/chat/:caseId',
    views: {
      'menuContent': {
        templateUrl: 'templates/case_chat.html',
        controller: 'CaseChatCtrl'
      }
    }
  })
  .state('app.create', {
    url: '/case/create',
    views: {
      'menuContent': {
        templateUrl: 'templates/case_create.html',
        controller: 'CaseCtrl'
      }
    }
  });
  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/overview');
});












function takePicture() {
  navigator.camera.getPicture(function(imageURI) {

    // imageURI is the URL of the image that we can use for
    // an <img> element or backgroundImage.

  }, function(err) {

    // Ruh-roh, something bad happened

  }, {});
}





