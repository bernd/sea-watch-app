<!-- Setze das bitte als Bootstrap Modal um: http://getbootstrap.com/javascript/#modals -->

<style>
#createCaseBox {
    display:none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0,0,0,0.8);
    z-index: 9999999;
}

#createCaseBox form{
}
</style>

<div class="modal" tabindex="-1" role="dialog" id="createCaseBox">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#createCaseBox').hide()"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create Case</h4>
      </div>
      <div class="modal-body">
<form name="myForm" method="post" action="api/cases/create" class="my-form controller" id="createCaseForm">
      <!--<input name="input" ng-model="case.lastLocation.lat">-->

            {!! csrf_field() !!}
          <div class="row">
            <div class="form-group col-md-6">
              <label for="boat_status">Boat status</label>
              <select class="form-control" name="boat_status" id="boat_status" ng-model="case.boat_status">
                          <option value="distress">Distress</option>
                          <option value="rescue_in_progress">Rescue in progress</option>
                          <option value="rescued">Rescued</option>
                          <option value="on_land">On land</option>
              </select>
              <input type="hidden" name="source_type" value="create_case_form"/>
            </div>
            <div class="form-group col-md-6">
              <label for="boat_condition">Condition</label>
              <select class="form-control" name="boat_condition" id="boat_condition" ng-model="case.boat_condition">
                          <option value="unknown">Unknown</option>
                          <option value="good">Good</option>
                          <option value="bad">Bad</option>
                          <option value="sinking">Sinking</option>
                          <option value="people_in_water">People in water</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="boat_type">Boat Type</label>
              <select class="form-control" name="boat_type" id="boat_type" ng-model="case.boat_type">
                          <option value="rubber">Rubber</option>
                          <option value="wood">Wood</option>
                          <option value="steel">Steel</option>
                          <option value="other">Other</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="passenger_count">How many people?</label>
              <input class="form-control" name="passenger_count" type="text" placeholder="How many people?" id="passenger_count" ng-model="case.passenger_count">
            </div>
            <div class="form-group col-md-6">
              <label for="passenger_count">How many women?</label>
              <input class="form-control" name="women_on_board" type="text" placeholder="How many woman?" id="women_on_board" ng-model="case.women_on_board">
            </div>
            <div class="form-group col-md-6">
              <label for="passenger_count">How many children?</label>
              <input class="form-control" name="children_on_board" type="text" placeholder="How many children?" id="children_on_board" ng-model="case.children_on_board">
            </div>
            <div class="form-group col-md-6">
              <label for="passenger_count">How many disabled persons?</label>
              <input class="form-control" name="disabled_on_board" type="text" placeholder="How many disabled?" id="disabled_on_board" ng-model="case.disabled_on_board">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6 form-inline">
              <div class="checkbox">
                <label>
                  Other Organisations Involved?
                  <input type="checkbox" ng-model="case.case.other_involved">
                </label>
              </div>
            </div>

            <div class="form-group form-inline col-md-6">
              <div class="checkbox">
                <label>
                  Engine Working?
                  <input type="checkbox" ng-model="case.case.engine_working"> 
                </label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="additional_informations">Additional Information</label>
            <input class="form-control" type="text" placeholder="Additional informations" name="additional_informations" id="additional_informations" ng-model="case.additional_informations">
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="lat">Lat</label>
              <input class="form-control" type="text" placeholder="Lat" name="lat" id="lat" ng-model="case.lastLocation.lat">
            </div>

            <div class="form-group col-md-6">
              <label for="lon">Lon</label>
              <input class="form-control" type="text" placeholder="Lon" name="lon" id="lon" ng-model="case.lastLocation.lon" >
            </div>
          </div>

          <!--<div class="item item-input-inset">
            <label class="item-input-wrapper">
              Picture of situation:
            </label>
            <button class="button icon ion-image" ng-click="takePicture()"/></button>
            <button class="button icon ion-camera" ng-click="takePicture()"/></button>
          </div>-->

        <button type="submit" class="btn btn-block btn-primary" value="Create Case">Create Case</button>
    </form>
</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->