                <div class="caseBox distress vehicleBox" style="background:#{{$vehicle->marker_color}}" data-id="{{$vehicle->id}}">
                    <div class="front">
                            <header>
                                <span class="time">{{$vehicle->updated_at()}}</span>
                                <div class="status">
                                    <span class="source" style="font-size:8px">{{$vehicle->title}}</span>
                                </div>
                                <div class="case_settings">
                                        <a href="#"><i class="zmdi zmdi-settings"></i></a>
                                </div>
                            </header>
                            <div class="map" id="vehiclemap_{{$vehicle->id}}"></div>
                            <div class="content" style="min-height:0;">
                                <table class="table">


                                    <?php
                                    
                                    $case_vars = array('title','id','type','sat_number');

                                    foreach($case_vars AS $case_var){
                                        ?>
                                        <tr>
                                            <td>{{$case_var}}</td>
                                            <td>{{$vehicle->$case_var}}</td>
                                        </tr>
                                        <?php
                                    } ?>
                                </table>
                            </div>
                    </div>
                    <div class="back" style="display:none">
                        <header>
                                <span class="time">{{\Carbon\Carbon::createFromTimeStamp(strtotime($vehicle->last_tracked))->diffForHumans()}}</span>
                                <div class="status">
                                    <span class="id" style="font-size:8px">{{$vehicle->id}}</span>
                                    <span class="source">{{$vehicle->type}}</span>
                                </div>
                                <div class="case_settings">
                                        <a href="#"><i class="zmdi zmdi-settings"></i></a>
                                </div>
                        </header>
                    </div>
                    <div class="editCase content" style="display:none; padding:0 30px;">
                    </div>
                </div>
                <!--<script>swApp.addMiniMap({{$vehicle->id}}, 'map_{{$vehicle->id}}');</script>-->