

<div class="col-sm-2 col-md-2 sidebar" {{ (Request::is('/') ? '' : '') }}>
                <ul class="nav nav-sidebar">
                  
                  @if (Request::is('map')||Request::is('vehicleGrid'))
                  <li><h3>Vehicles</h3></li>
                  @foreach ($vehicles as $vehicle)
                      <li class="vehicle filter" data-id="<?php echo $vehicle->id;?>"><a href="#vehicle"><?php echo $vehicle->title;?> <span class="label label-danger pull-right"><?php //echo $operation_area->count_open_cases();?></span></a></li>
                  @endforeach
                  @endif
                  <li><h3>Area</h3></li>
                  @foreach ($operation_areas as $operation_area)
                      <li class="op_area filter" data-class="oparea_<?php echo $operation_area->id;?>" data-id="<?php echo $operation_area->id;?>"><a href="#op_area"><?php echo $operation_area->title;?> <span class="label label-danger pull-right"><?php echo $operation_area->count_open_cases();?></span></a></li>
                  @endforeach
                  
                  @if (Request::is('map')||Request::is('/'))
                  <li><h3>Status</h3></li>
                  <?php
                  foreach(['distress'=>'Distress', 'rescue_in_progress'=>'In progress', 'rescued'=>'Rescued', 'on_land'=>'On Land'] AS $index=>$status){
                  ?>
                    <li class="filter status <?php echo $index;?>" data-class="<?php echo $index;?>"><a href="#"><?php echo $status;?></a></li>
                  <?php
                  }?>
                  <li><h3>Sources</h3></li> 
                  <?php
                  foreach(['refugee'=>'Refugee app', 'land_operator'=>'Land operator', 'rumors'=>'Rumors'] AS $index=>$source){
                      ?><li class="filter source" data-class="type_<?php echo $index;?>"><a href="#"><?php echo $source;?></a></li><?php
                  }?>
                  @endif
                  
                  <!--
                  <div class="divider"></div>
                  <li><a href="{{ URL::to('pages/history') }}">History</a></li>
                  <li><a href="{{ URL::to('pages/faq') }}">FAQ</a></li>
                  <li><a href="{{ URL::to('pages/support') }}">Technical support</a></li>
                  <li><a href="{{ URL::to('pages/imprint') }}">Imprint</a></li>-->
                </ul>
            <div id="olControlDiv"></div>
        </div>
<audio id="bing" controls preload="none" style="display:none">
    <source src="{{ URL::to('js/bing.mp3') }}" type="audio/mpeg">
    <source src="{{ URL::to('js/bing.ogg') }}" type="audio/ogg">
</audio>