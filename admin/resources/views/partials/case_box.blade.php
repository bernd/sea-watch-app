<?php


$closedCase = '';
if($emergency_case->closed){
    $closedCase = 'closed';
}

?>
                <div class="caseBox {{$emergency_case->boat_status}} {{$closedCase}} type_{{$emergency_case->source_type}} oparea_{{$emergency_case->operation_area}} caseBox_{{$emergency_case->id}}" data-id="{{$emergency_case->id}}">
                    <div class="front">
                            <header>
                                <span class="time">{{$emergency_case->updated_at()}}</span>
                                 <span class="connection_type">
                                <?php 
                                if($emergency_case->first_location())
                                    echo $emergency_case->first_location()->connection_type;
                                ?></span>
                                <div class="status"> 
                                    {{['need_help'=>'Need Help','critical_target'=>'Critical','confirmed_target'=>'Confirmed','possible_target'=>'Possible Target','attended'=>'Attended','closed'=>'Closed'][$emergency_case->boat_status]}}
                                    <span class="id" style="font-size:8px">{{$emergency_case->id}}</span>
                                    <span class="source">
                                        
                                        
                                        <?php
                                        $source_names = array('refugee'=>'Refugee', 'spotter_app'=>'Spotter', 'plane_app'=>'Plane', 'create_case_form'=>'Web', ''=>'not defined');
                                        if(isset($emergency_case->source_type)){
                                            ?>
                                            {{$source_names[$emergency_case->source_type]}}
                                            <?php
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="case_settings">
                                        <a href="#"><i class="zmdi zmdi-settings"></i></a>
                                </div>
                            </header>
                            <div class="map" id="map_{{$emergency_case->id}}"></div>
                            <div class="content">
                                <!--{{ URL::to('cases/get_involved/'.$emergency_case->id) }}-->
                                <a href="#" data-id="{{$emergency_case->id}}" class="btn btn-sm pull-left get-involved">Get Involved</a>
                                <table class="table">


                                    <?php
                                    $case_vars = array('id','closing_reason','boat_status','boat_condition','boat_type','other_involved','engine_working','passenger_count','women_on_board','children_on_board','disabled_on_board','additional_informations',/*'spotting_distance','spotting_direction','picture'*/'operation_area');

                                    foreach($case_vars AS $case_var){
                                        if(!$emergency_case->close||($emergency_case->close == true && $case_var='closing_reason')&&$emergency_case->$case_var){
                                        ?>
                                        <tr>
                                            <td>{{$emergency_case->translateColumnName($case_var)}}</td>
                                            <td>{{$emergency_case->$case_var}}</td>
                                        </tr>
                                        <?php
                                        }
                                    } ?>
                                    <tr>
                                        <td>Involved</td>
                                        <td>
                                            <ul class="involvedList">
                                                
                                                <?php
                                                foreach ($emergency_case->involved_users() as $user){ ?>
                                                    <li><?php echo $user ?></li>
                                                <?php } ?>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                    </div>
                    <div class="back" style="display:none">
                        <header>

                                <span class="time">{{\Carbon\Carbon::createFromTimeStamp(strtotime($emergency_case->created_at))->diffForHumans()}}</span>
                                <span class="connection_type">
                                <?php 
                                if($emergency_case->first_location())
                                    echo $emergency_case->first_location()->connection_type;
                                ?></span>

                                <span class="status">
                                    {{['need_help'=>'Need Help','critical_target'=>'Critical','confirmed_target'=>'Confirmed','possible_target'=>'Possible Target','attended'=>'Attended','closed'=>'Closed'][$emergency_case->boat_status]}}
                                </span>
                                <span class="source">Refugee</span>
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
                                                    <input type="text" aria-label="Schreibe einen Text…" data-id="<?php echo $emergency_case->id;?>">
                                                    <button type="button">Senden</button>
                                                </form>
                                            </div>
                                        </div>
                            </div>
                    </div>
                    <div class="editCase content" style="display:none; padding:0 30px;">
                    </div>
                </div>