angular.module('sw_spotter.controllers', [])


.controller('MenuCtrl', function($scope, $controller, $interval) {
    
    
  $controller('AppCtrl', {$scope: $scope}); //This works
  $scope.menuObj = {};
  $scope.menuObj.trackPosition = false;
  
  $scope.alertTrackingStatus = function(){
      console.log($scope.menuObj);
      alert($scope.menuObj);
  };
    
})
     
.controller('AppCtrl', function($scope, $controller, $ionicModal, $interval, $cordovaGeolocation, $timeout, $http, $state) {

  $controller('VehicleCtrl', {$scope: $scope}); //This works
  $controller('CasesCtrl', {$scope: $scope}); //This works
  var stopUpdateLocation;
  $scope.startLocationUpdater = function() {
      
      
    // Don't start a new fight if we are already fighting
    if ( angular.isDefined(stopUpdateLocation) ) return;

          stopUpdateLocation = $interval(function() {
              
              console.log(window.localStorage['jwt']);
              $scope.updateVehiclePosition();
              
          }, 10000);
  };

  $scope.stopLocationUpdater = function() {
          if (angular.isDefined(stop)) {
            $interval.cancel(stop);
            stop = undefined;
          }
  };
  
  
  //wait for position to be tracked
  //when tracked->initLocationUpdater
  if(typeof $scope.updatePositionInited == 'undefined')
    $scope.updatePositionInited = false;
  var stopWatchPosition = $scope.$watch('position',function(position) {
      
      if ($scope.updatePositionInited )return;
      if(position) {
        $scope.updatePositionInited = true;
        console.log('initLocationUpdater');
        $scope.startLocationUpdater();
        stopWatchPosition();
      }
  });
  
  //userdata
  $scope.loginData = {
      token:window.localStorage['jwt']
  };

  $scope.position = {};

  $scope.apiURL = 'https://app.sea-watch.org/admin/public/';

  $scope.initModal = function(cb){
    // Create the login modal that we will use later
    $ionicModal.fromTemplateUrl('templates/login.html', {
      scope: $scope
    }).then(function(modal) {
      $scope.modal = modal;
      cb();
    });
  }

  // Triggered in the login modal to close it
  $scope.closeLogin = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.login = function() {
    $scope.initModal(function(){


      $scope.modal.show();

    });
  };


  var urlBase = 'http://app.sea-watch.org/admin/public/api/';
  $scope.init = function(){
      
      console.log('INIT INIT INIT');


    //init positionwatch
    var watch = $cordovaGeolocation.watchPosition({
      timeout : 10000,
      enableHighAccuracy: true // may cause errors if true
    });

    watch.then(
      null,
      function(err) {
        // error
      },
      function(position) {
        console.log('position tracked:');
        console.log(position.coords);
        $scope.position = position;
    });

    if(typeof $scope.loginData.token === 'undefined'){
      console.log('not logged in');
      $scope.login();
    }else{
      console.log('logged in');
        //check if token needs to be refreshed
                $http({
                method: 'POST',
                url: urlBase+'user/token',
                headers: {
                  'Content-Type': 'application/json',
                  Authorization: 'Bearer '+window.localStorage['jwt']
                },
                data: {session_token: 1337, position:'fuck you'}
            }).then(function(response) {
                if(!response.data.error){
                  console.log(response);
                  window.localStorage['jwt'] = response.data.token;
                  console.log('token updated');
                }else{
                    console.log(response.error);
                }
            }, function(error) {
                console.log('Some error occured during the authentification with stored token:');
                console.log(error);
              
                $scope.login();
            });
    }
  };



  // Perform the login action when the user submits the login form
  $scope.doLogin = function() {
      
    
    console.log('Doing login', $scope.loginData);
    $http({
      url: urlBase+'user/auth',
      method: 'POST',
      data: $scope.loginData
    }).then(function(response) {
        if(!response.data.error){
          console.log(response);
          window.localStorage['jwt'] = response.data.token;
          window.localStorage['user'] = response.data.user_id;
          $scope.closeLogin();
          $state.go('app.overview');
        }else{
            alert(response.error);
        }
    }, function(error) {
      alert(error.data);
    });
           

    
  };
})



.controller('CasesCtrl',['$scope', 'dataService', '$controller', function ($scope, dataFactory, $controller) {

  $scope.cases;

  //check if cases still need to be loaded
  //so that the request isnt sent several times
  if(typeof $scope.loadCases == 'undefined')
    $scope.loadCases = true;

  $scope.getCases = function(cb) {

        $scope.loadCases = false;
        dataFactory.getCases()
            .success(function (result) {
                $scope.cases = result.data.emergency_cases;
                console.log($scope.cases);
                if(typeof cb === 'function'){
                  cb();
                }
            })
            .error(function (error) {
              if(error !== null)
                $scope.status = 'Unable to load customer data: ' + error.message;
            });
  };
  if($scope.loadCases){
    console.log('init Loading');
    $scope.getCases();
  }
}]).

controller('CreateCaseCtr',function($scope, $controller, Camera, dataService){


  $scope.createCase = function(){


        $controller('AppCtrl', {$scope: $scope});
        $scope.case = {};
        //add source type to scope
        $scope.case.source_type = 'spotter_app';
        $scope.case.location_data = {accuracy:$scope.position.coords.accuracy,altitude:$scope.position.coords.altitude,latitude:$scope.position.coords.latitude, longitude:$scope.position.coords.longitude};
        dataService.createCase({params:$scope.case})
            .success(function (result) {

                //push result
                console.log(result);
                if(typeof cb === 'function'){
                  cb();
                }
            })
            .error(function (error) {
              if(error !== null)
                $scope.status = 'Unable to load customer data: ' + error.message;
            });

          console.log($scope.case);
  }
})

.controller('CaseCtrl', function($scope, $stateParams,$controller, Camera, dataService) {

  $controller('CasesCtrl', {$scope: $scope});


  $scope.getlastLocation = function(case_id){

    var case_data = {};
    angular.forEach($scope.cases, function(case_values, key) {
      if(case_values.id == case_id){
        case_data = case_values;
      }
    });
    return case_data.locations[case_data.locations.length-1];
  };


  var case_id = 4;

  //there must be a better way...
  if(typeof $stateParams.caseId !== 'undefined')
    var case_id = $stateParams.caseId;


    //wait for cases to be loaded
    var stopWatching = $scope.$watch('cases',function(cases) {
      if(cases) {
          console.log('cases loaded!');


          //$scope.cases[case_id].lastLocation = $scope.getlastLocation(case_id);
          angular.forEach($scope.cases, function(case_values, key) {
            if(case_values.id == case_id){
               $scope.case = case_values;
            }
          });
          $scope.case.lastLocation = $scope.getlastLocation(case_id+1);




         stopWatching();
      }
    });







  $scope.takePicture = function() {
    console.log('$scope.takePicture() initted, if app crashes => no browser support html5 cam');
    Camera.getPicture().then(function(imageURI) {
      console.log(imageURI);
    }, function(err) {
      console.err(err);
    });
  }

  $scope.createCase = function(){

        //add source type to scope
        $scope.case.source_type = 'spotter_app';
        $scope.case.location_data = {accuracy:$scope.position.coords.accuracy,altitude:$scope.position.coords.altitude,latitude:$scope.position.coords.latitude, longitude:$scope.position.coords.longitude};
        dataService.createCase({params:$scope.case})
            .success(function (result) {

                //push result
                console.log(result);
                if(typeof cb === 'function'){
                  cb();
                }
            })
            .error(function (error) {
              if(error !== null)
                $scope.status = 'Unable to load customer data: ' + error.message;
            });

    console.log($scope.case);
  }
});