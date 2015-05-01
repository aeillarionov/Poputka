</div>
                    </div>
                  </div>
                  
                  
                  <!-- Add Trip Tab -->
                  <div class="content" id="add_trip">
                    <dl class="sub-nav">
                    	<dd onclick="$('#list_view').toggleClass('active'); $('#add_trip').toggleClass('active');"><a href="#"><i class="fa fa-arrow-left"></i>&nbsp;Назад</a></dd>
                    </dl>
                    <!--<h5><i class="fa fa-road"></i>&nbsp;&nbsp;Данные о поездке </h5>--!>
                    <form action="add_route" method="post" onsubmit="">
                      <!--fieldset>
                        <legend>Данные о поездке</legend-->
                        <!-- Start point -->

                        <div class="row collapse">
                          <div class="small-3 large-2 columns">
                            <span class="prefix">Из:</span>
                          </div>
                          <div class="small-6 large-8 columns">
                            <input id="startPlacemarkValue" type="text" placeholder="Откуда еду" name="dep_addr">
                            <input type="hidden" name="departureCoord" id="departureCoord">
                            <!--small class="error">Необходимо указать начальную точку</small-->
                          </div>
                          <div class="small-3 large-2 columns">
                            <a class="button postfix" onClick="searchStartPlacemarkByForm()"><i class="fa fa-search"></i></a>
                          </div>
                        </div>

                        <!-- Finish point -->
                        <div class="row collapse">
                          <div class="small-3 large-2 columns">
                            <span class="prefix">В:</span>
                          </div>
                          <div class="small-6 large-8 columns">
                            <input id="finishPlacemarkValue" type="text" placeholder="Куда еду" name="des_addr">
                            <input type="hidden" name="destinationCoord" id="destinationCoord">
                          </div>
                          <div class="small-3 large-2 columns">
                            <a class="button postfix" onClick="searchFinishPlacemarkByForm()"><i class="fa fa-search"></i></a>
                          </div>
                        </div>

                        <!-- Date -->
                        <div class="row collapse">
                          <div class="small-3 large-2 columns">
                            <span class="prefix"><i class="fa fa-calendar"></i></span>
                          </div>
                          <div class="small-9 large-10 columns">
                            <input id="startDate" name="startDate" type="text" placeholder="ДД.ММ.ГГГГ">
                          </div>
                        </div>

                        <!-- Time -->
                        <div class="row collapse">
                          <div class="small-2 columns">
                            <span class="prefix">C:</i> </span>
                          </div>
                          <div class="small-4 columns">
                            <input id="startTime" name="startTime" type="text" placeholder="ЧЧ:ММ">
                          </div>
                          <div class="small-2 columns">
                            <span class="prefix">По:</span>
                          </div>
                          <div class="small-4 columns">
                            <input id="finishTime" name="finishTime" type="text" placeholder="ЧЧ:ММ">
                          </div>
                        </div>

                        <!-- Frequency -->
                        <div class="row collapse frequency">
                          <div class="small-6 columns text-center"> 
                          <label>Один раз
                            <div class="switch tiny ">
                              <input id="isOneoffRadioSwitch" type="radio" checked name="frequencySwitchGroup">
                              <label for="isOneoffRadioSwitch"></label>
                            </div>
                          </label>
                        </div>
                        <div class="small-6 columns text-center">
                          <label>Регулярно
                            <div class="switch tiny ">
                              <input id="isRegularRadioSwitch" type="radio" name="frequencySwitchGroup">
                              <label for="isRegularRadioSwitch"></label>
                            </div>
                          </label>
                        </div>
                      </div>
                      <!-- Week choice -->
                      <div class="row collapse weekdayChoice">
                        <div class="small-12 columns text-center"> 
                          <!-- Radius Button Group -->
                          <ul class="button-group radius even-7 ">
                            <li><a id="day1" class="button tiny" onclick="handleDays(this)">Пн</a></li>
                            <li><a id="day2" class="button tiny" onclick="handleDays(this)">Вт</a></li>
                            <li><a id="day3" class="button tiny" onclick="handleDays(this)">Ср</a></li>
                            <li><a id="day4" class="button tiny" onclick="handleDays(this)">Чт</a></li>
                            <li><a id="day5" class="button tiny" onclick="handleDays(this)">Пт</a></li>
                            <li><a id="day6" class="button tiny" onclick="handleDays(this)">Сб</a></li>
                            <li><a id="day7" class="button tiny" onclick="handleDays(this)">Вс</a></li>
                          </ul>
                          <input type="hidden" name="regularDays" id="regularDays" value="0">
                        </div>
                      </div>

                      <!-- Passengers -->
                      <div class="row collapse passangers">
                        <div class="small-12 columns text-center">
                          <!--label for="right-label" class="inline-label">Свобоных мест-->
                            <div class="row collapse">
                              <div class="small-6 columns ">
                                <span class="prefix"> Свободные места: </span>
                              </div>
                              <div class="small-6 columns">
                                <select id="passangers_quantity" name="passangers_quantity">
                                  <option value="1" selected="selected">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                </select>
                              </div>
                            </div>
                          <!--/label-->
                        </div>
                      </div>

                      <!-- Comment -->
                      <div class="row collapse">
                        <div class="large-12 columns text-center">
                          <label>Доп. информация
                            <textarea id="additional_info" name="extra" placeholder="Введите при необходимости"></textarea>
                          </label>
                        </div>
                      </div>

                      <!-- Submit -->
                      <div class="row collapse">
                        <div class="small-12 columns text-center">
                          <button id="submit_pick_request" class="expand button success submit_btn"> <i class="fa fa-car"></i> Добавить поездку </button>
                        </div>
                      </div>

                      <!--/fieldset-->
                    </form>
                  </div>
                </div> <!-- /tabs-content -->
              </div>
            </div>
            
          </div>

          <!-- Map placeholder -->
          <div class="small-8 columns">
            <div id="ymap"></div>
          </div>

          

          <!-- Matches placeholder >
          <div class="small-3 columns panel">
            <!-- Matches list >
            <div class="row">
              <div class="small-12 columns text-center">
                <h4>Вам по пути с:</h4>
              </div>
            </div>
            <!--Taxi Banner placeholder>
            <img style="width:100%" src="http://placehold.it/300x500">
          </div-->
          

        </div>



    <!--a class="exit-off-canvas"></a>

    </div>
  </div-->