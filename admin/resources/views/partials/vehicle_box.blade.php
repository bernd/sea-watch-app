                <div class="caseBox vehicleBox" data-id="{{$vehicle->id}}">
                    <div class="front">
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
                            <div class="map" id="vehiclemap_{{$vehicle->id}}"></div>
                            <div class="content">
                                <!--{{ URL::to('cases/get_involved/'.$vehicle->id) }}-->
                                <a href="#" data-id="{{$vehicle->id}}" class="btn btn-sm pull-left get-involved">Get Involved</a>
                                <a href="#" data-id="{{$vehicle->id}}" class="btn btn-sm pull-right show-messages"><?php echo $vehicle->count_messages(); ?></a>
                                <table class="table">


                                    <?php
                                    
                                    $case_vars = array('id','type','sat_number');

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
                            <div class="content messenger">
                                       <div class="messenger__chat container__large">
                                            <!--<div class="user_1 message">
                                                <p>Hi, here is Sea-Watch!
                                                Wir suchen nun ein Rettungsteam. Bitte bleibe ruhig und schließe diese App nicht. Kannst du uns sagen wie viele Leute ihr auf dem Boot seid und wie eure Lage aktuell ist.</p>
                                            </div>
                                             <div class="user_2 message">
                                                <p>we need help, please rescue, we are 40 people in small boat, children, womans </p>
                                            </div>
                                            <div class="chat_status_notification">
                                                <p class="meta">Your internet is slow. The App now use "SMS-MODE".</p>
                                            </div>

                                            <div class="user_2 message sms_mode">
                                               <p class="lonlat">LON: <span class="lon">15.92828</span> · LAT: <span class="lat">17.34454</span></p>
                                               <p>Hi, please help! we are sinking.</p>
                                            </div>-->
                                        </div>
                                        <div class="messenger__form">
                                            <a class="close_chat" href="#"><i class="zmdi zmdi-arrow-left"></i></a>
                                            <div class="form_inline">
                                                <form>
                                                    <input type="text" aria-label="Schreibe einen Text…" data-id="<?php echo $vehicle->id;?>">
                                                    <button type="button">Senden</button>
                                                </form>
                                            </div>
                                        </div>
                            </div>
                    </div>
                    <div class="editCase content" style="display:none; padding:0 30px;">
                    </div>
                </div>
                <!--<script>swApp.addMiniMap({{$vehicle->id}}, 'map_{{$vehicle->id}}');</script>-->