<div class="col-sm-2 col-md-2 sidebar" {{ (Request::is('/') ? 'style="position:absolute;top:55px;left:0;bottom:0;overflow:auto;"' : '') }}>
                <ul class="nav nav-sidebar">
                  <li><h3>Area</h3></li>
                  @foreach ($operation_areas as $operation_area)
                      <li class="op_area filter" data-class="oparea_<?php echo $operation_area->id;?>" data-id="<?php echo $operation_area->id;?>"><a href="#op_area"><?php echo $operation_area->title;?> <span class="label label-danger pull-right"><?php echo $operation_area->count_open_cases();?></span></a></li>
                  @endforeach
                  <li class="op_area add_op"><a href="{{ URL::to('operation_areas/create') }}"><span class="add_op_title">Add Operation Area</span></a></li>

                  <li><h3>Status</h3></li>
                  <?php
                  foreach(['distress'=>'Distress', 'rescue_in_progress'=>'In progress', 'rescued'=>'Rescued', 'on_land'=>'On Land'] AS $index=>$status){
                  ?>
                    <li class="filter status <?php echo $index;?>" data-class="<?php echo $index;?>"><a href="#"><?php echo $status;?></a></li>
                  <?php
                  }?>
                  <li><h3>Sources</h3></li> 
                  <?php
                  foreach(['app'=>'Refugee app', 'land_operator'=>'Land operator', 'rumors'=>'Rumors'] AS $index=>$source){
                      ?><li class="filter source" data-class="<?php echo $index;?>"><a href="#"><?php echo $source;?></a></li><?php
                  }?>
                  <div class="divider"></div>
                  <li><a href="{{ URL::to('pages/history') }}">History</a></li>
                  <li><a href="{{ URL::to('pages/faq') }}">FAQ</a></li>
                  <li><a href="{{ URL::to('pages/support') }}">Technical support</a></li>
                  <li><a href="{{ URL::to('pages/imprint') }}">Imprint</a></li>
                </ul>
            <div id="olControlDiv"></div>
        </div>