

<div class="col-sm-2 col-md-2 sidebar" {{ (Request::is('/') ? '' : '') }}>
                <ul class="nav nav-sidebar">
                  
                  @if (Request::is('map')||Request::is('vehicleGrid'))
                  <li><h3>Vehicles</h3></li>
                  @foreach ($vehicles as $vehicle)
                      <li class="vehicle filter" data-id="<?php echo $vehicle->id;?>"><a href="#vehicle"><?php echo $vehicle->title;?> <span class="label label-danger pull-right"><?php //echo $operation_area->count_open_cases();?></span></a></li>
                  @endforeach
                  <li class="vehicle filter active all all_vehicles "><a href="#vehicle">All <span class="label label-danger pull-right"></span></a></li>
                  @endif
                  
                  @if (Request::is('map')||Request::is('/'))
                  <hr>
                  <li><h3>Status</h3></li>
                  <?php
                  foreach(['need_help'=>'Need Help','critical_target'=>'Critical','confirmed_target'=>'Confirmed','possible_target'=>'Possible Target','attended'=>'Attended','closed'=>'Closed'] AS $index=>$status){
                  ?>

                    <li class="filter status <?php echo $index;?>" data-class="<?php echo $index;?>"><a href="#"><?php echo $status;?></a></li>
                  <?php
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