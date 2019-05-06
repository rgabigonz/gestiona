<template>
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Draggable Events</h4>
              </div>
              <div class="card-body">
                <!-- the events -->
                <div id="external-events">
                  <div class="external-event bg-success ui-draggable ui-draggable-handle" style="position: relative;">Lunch</div>
                  <div class="external-event bg-warning ui-draggable ui-draggable-handle" style="position: relative;">Go home</div>
                  <div class="external-event bg-info ui-draggable ui-draggable-handle" style="position: relative;">Do homework</div>
                  <div class="external-event bg-primary ui-draggable ui-draggable-handle" style="position: relative;">Work on UI design</div>
                  <div class="external-event bg-danger ui-draggable ui-draggable-handle" style="position: relative;">Sleep tight</div>
                  <div class="checkbox">
                    <label for="drop-remove">
                      <input id="drop-remove" type="checkbox">
                      remove after drop
                    </label>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /. box -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Create Event</h3>
              </div>
              <div class="card-body">
                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                  <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                  <ul class="fc-color-picker" id="color-chooser">
                    <li><a class="text-primary" href="#"><i class="fa fa-square"></i></a></li>
                    <li><a class="text-warning" href="#"><i class="fa fa-square"></i></a></li>
                    <li><a class="text-success" href="#"><i class="fa fa-square"></i></a></li>
                    <li><a class="text-danger" href="#"><i class="fa fa-square"></i></a></li>
                    <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                  </ul>
                </div>
                <!-- /btn-group -->
                <div class="input-group">
                  <input id="new-event" class="form-control" placeholder="Event Title" type="text">

                  <div class="input-group-append">
                    <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                  </div>
                  <!-- /btn-group -->
                </div>
                <!-- /input-group -->
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar" class="fc fc-ltr fc-unthemed"><div class="fc-toolbar"><div class="fc-left"><div class="fc-button-group"><button type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left"><span class="fc-icon fc-icon-left-single-arrow"></span></button><button type="button" class="fc-next-button fc-button fc-state-default fc-corner-right"><span class="fc-icon fc-icon-right-single-arrow"></span></button></div><button type="button" class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right fc-state-disabled" disabled="disabled">today</button></div><div class="fc-right"><div class="fc-button-group"><button type="button" class="fc-month-button fc-button fc-state-default fc-corner-left fc-state-active">month</button><button type="button" class="fc-agendaWeek-button fc-button fc-state-default">week</button><button type="button" class="fc-agendaDay-button fc-button fc-state-default fc-corner-right">day</button></div></div><div class="fc-center"><h2>May 2019</h2></div><div class="fc-clear"></div></div><div class="fc-view-container" style=""><div class="fc-view fc-month-view fc-basic-view" style=""><table><thead><tr><td class="fc-widget-header"><div class="fc-row fc-widget-header"><table><thead><tr><th class="fc-day-header fc-widget-header fc-sun">Sun</th><th class="fc-day-header fc-widget-header fc-mon">Mon</th><th class="fc-day-header fc-widget-header fc-tue">Tue</th><th class="fc-day-header fc-widget-header fc-wed">Wed</th><th class="fc-day-header fc-widget-header fc-thu">Thu</th><th class="fc-day-header fc-widget-header fc-fri">Fri</th><th class="fc-day-header fc-widget-header fc-sat">Sat</th></tr></thead></table></div></td></tr></thead><tbody><tr><td class="fc-widget-content"><div class="fc-day-grid-container" style=""><div class="fc-day-grid"><div class="fc-row fc-week fc-widget-content" style="height: 115px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-other-month fc-past" data-date="2019-04-28"></td><td class="fc-day fc-widget-content fc-mon fc-other-month fc-past" data-date="2019-04-29"></td><td class="fc-day fc-widget-content fc-tue fc-other-month fc-past" data-date="2019-04-30"></td><td class="fc-day fc-widget-content fc-wed fc-past" data-date="2019-05-01"></td><td class="fc-day fc-widget-content fc-thu fc-past" data-date="2019-05-02"></td><td class="fc-day fc-widget-content fc-fri fc-past" data-date="2019-05-03"></td><td class="fc-day fc-widget-content fc-sat fc-past" data-date="2019-05-04"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-number fc-sun fc-other-month fc-past" data-date="2019-04-28">28</td><td class="fc-day-number fc-mon fc-other-month fc-past" data-date="2019-04-29">29</td><td class="fc-day-number fc-tue fc-other-month fc-past" data-date="2019-04-30">30</td><td class="fc-day-number fc-wed fc-past" data-date="2019-05-01">1</td><td class="fc-day-number fc-thu fc-past" data-date="2019-05-02">2</td><td class="fc-day-number fc-fri fc-past" data-date="2019-05-03">3</td><td class="fc-day-number fc-sat fc-past" data-date="2019-05-04">4</td></tr></thead><tbody><tr><td rowspan="2"></td><td rowspan="2"></td><td rowspan="2"></td><td class="fc-event-container" colspan="3"><a class="fc-day-grid-event fc-event fc-start fc-end fc-draggable" style="background-color:#f39c12;border-color:#f39c12"><div class="fc-content"><span class="fc-time">12a</span> <span class="fc-title">Long Event</span></div></a></td><td rowspan="2"></td></tr><tr><td class="fc-event-container"><a class="fc-day-grid-event fc-event fc-start fc-end fc-draggable" style="background-color:#f56954;border-color:#f56954"><div class="fc-content"><span class="fc-time">12a</span> <span class="fc-title">All Day Event</span></div></a></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 115px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-past" data-date="2019-05-05"></td><td class="fc-day fc-widget-content fc-mon fc-today fc-state-highlight" data-date="2019-05-06"></td><td class="fc-day fc-widget-content fc-tue fc-future" data-date="2019-05-07"></td><td class="fc-day fc-widget-content fc-wed fc-future" data-date="2019-05-08"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-05-09"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-05-10"></td><td class="fc-day fc-widget-content fc-sat fc-future" data-date="2019-05-11"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-number fc-sun fc-past" data-date="2019-05-05">5</td><td class="fc-day-number fc-mon fc-today fc-state-highlight" data-date="2019-05-06">6</td><td class="fc-day-number fc-tue fc-future" data-date="2019-05-07">7</td><td class="fc-day-number fc-wed fc-future" data-date="2019-05-08">8</td><td class="fc-day-number fc-thu fc-future" data-date="2019-05-09">9</td><td class="fc-day-number fc-fri fc-future" data-date="2019-05-10">10</td><td class="fc-day-number fc-sat fc-future" data-date="2019-05-11">11</td></tr></thead><tbody><tr><td rowspan="2"></td><td class="fc-event-container"><a class="fc-day-grid-event fc-event fc-start fc-end fc-draggable" style="background-color:#0073b7;border-color:#0073b7"><div class="fc-content"><span class="fc-time">10:30a</span> <span class="fc-title">Meeting</span></div></a></td><td class="fc-event-container" rowspan="2"><a class="fc-day-grid-event fc-event fc-start fc-end fc-draggable" style="background-color:#00a65a;border-color:#00a65a"><div class="fc-content"><span class="fc-time">7p</span> <span class="fc-title">Birthday Party</span></div></a></td><td rowspan="2"></td><td rowspan="2"></td><td rowspan="2"></td><td rowspan="2"></td></tr><tr><td class="fc-event-container"><a class="fc-day-grid-event fc-event fc-start fc-end fc-draggable" style="background-color:#00c0ef;border-color:#00c0ef"><div class="fc-content"><span class="fc-time">12p</span> <span class="fc-title">Lunch</span></div></a></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 115px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-future" data-date="2019-05-12"></td><td class="fc-day fc-widget-content fc-mon fc-future" data-date="2019-05-13"></td><td class="fc-day fc-widget-content fc-tue fc-future" data-date="2019-05-14"></td><td class="fc-day fc-widget-content fc-wed fc-future" data-date="2019-05-15"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-05-16"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-05-17"></td><td class="fc-day fc-widget-content fc-sat fc-future" data-date="2019-05-18"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-number fc-sun fc-future" data-date="2019-05-12">12</td><td class="fc-day-number fc-mon fc-future" data-date="2019-05-13">13</td><td class="fc-day-number fc-tue fc-future" data-date="2019-05-14">14</td><td class="fc-day-number fc-wed fc-future" data-date="2019-05-15">15</td><td class="fc-day-number fc-thu fc-future" data-date="2019-05-16">16</td><td class="fc-day-number fc-fri fc-future" data-date="2019-05-17">17</td><td class="fc-day-number fc-sat fc-future" data-date="2019-05-18">18</td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 115px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-future" data-date="2019-05-19"></td><td class="fc-day fc-widget-content fc-mon fc-future" data-date="2019-05-20"></td><td class="fc-day fc-widget-content fc-tue fc-future" data-date="2019-05-21"></td><td class="fc-day fc-widget-content fc-wed fc-future" data-date="2019-05-22"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-05-23"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-05-24"></td><td class="fc-day fc-widget-content fc-sat fc-future" data-date="2019-05-25"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-number fc-sun fc-future" data-date="2019-05-19">19</td><td class="fc-day-number fc-mon fc-future" data-date="2019-05-20">20</td><td class="fc-day-number fc-tue fc-future" data-date="2019-05-21">21</td><td class="fc-day-number fc-wed fc-future" data-date="2019-05-22">22</td><td class="fc-day-number fc-thu fc-future" data-date="2019-05-23">23</td><td class="fc-day-number fc-fri fc-future" data-date="2019-05-24">24</td><td class="fc-day-number fc-sat fc-future" data-date="2019-05-25">25</td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 115px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-future" data-date="2019-05-26"></td><td class="fc-day fc-widget-content fc-mon fc-future" data-date="2019-05-27"></td><td class="fc-day fc-widget-content fc-tue fc-future" data-date="2019-05-28"></td><td class="fc-day fc-widget-content fc-wed fc-future" data-date="2019-05-29"></td><td class="fc-day fc-widget-content fc-thu fc-future" data-date="2019-05-30"></td><td class="fc-day fc-widget-content fc-fri fc-future" data-date="2019-05-31"></td><td class="fc-day fc-widget-content fc-sat fc-other-month fc-future" data-date="2019-06-01"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-number fc-sun fc-future" data-date="2019-05-26">26</td><td class="fc-day-number fc-mon fc-future" data-date="2019-05-27">27</td><td class="fc-day-number fc-tue fc-future" data-date="2019-05-28">28</td><td class="fc-day-number fc-wed fc-future" data-date="2019-05-29">29</td><td class="fc-day-number fc-thu fc-future" data-date="2019-05-30">30</td><td class="fc-day-number fc-fri fc-future" data-date="2019-05-31">31</td><td class="fc-day-number fc-sat fc-other-month fc-future" data-date="2019-06-01">1</td></tr></thead><tbody><tr><td></td><td></td><td class="fc-event-container"><a class="fc-day-grid-event fc-event fc-start fc-end fc-draggable" href="http://google.com/" style="background-color:#3c8dbc;border-color:#3c8dbc"><div class="fc-content"><span class="fc-time">12a</span> <span class="fc-title">Click for Google</span></div></a></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div><div class="fc-row fc-week fc-widget-content" style="height: 120px;"><div class="fc-bg"><table><tbody><tr><td class="fc-day fc-widget-content fc-sun fc-other-month fc-future" data-date="2019-06-02"></td><td class="fc-day fc-widget-content fc-mon fc-other-month fc-future" data-date="2019-06-03"></td><td class="fc-day fc-widget-content fc-tue fc-other-month fc-future" data-date="2019-06-04"></td><td class="fc-day fc-widget-content fc-wed fc-other-month fc-future" data-date="2019-06-05"></td><td class="fc-day fc-widget-content fc-thu fc-other-month fc-future" data-date="2019-06-06"></td><td class="fc-day fc-widget-content fc-fri fc-other-month fc-future" data-date="2019-06-07"></td><td class="fc-day fc-widget-content fc-sat fc-other-month fc-future" data-date="2019-06-08"></td></tr></tbody></table></div><div class="fc-content-skeleton"><table><thead><tr><td class="fc-day-number fc-sun fc-other-month fc-future" data-date="2019-06-02">2</td><td class="fc-day-number fc-mon fc-other-month fc-future" data-date="2019-06-03">3</td><td class="fc-day-number fc-tue fc-other-month fc-future" data-date="2019-06-04">4</td><td class="fc-day-number fc-wed fc-other-month fc-future" data-date="2019-06-05">5</td><td class="fc-day-number fc-thu fc-other-month fc-future" data-date="2019-06-06">6</td><td class="fc-day-number fc-fri fc-other-month fc-future" data-date="2019-06-07">7</td><td class="fc-day-number fc-sat fc-other-month fc-future" data-date="2019-06-08">8</td></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div></div></div></div></td></tr></tbody></table></div></div></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /. box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    <div><div class="container-fluid">
            <div class="row docs-premium-template">
                            <div class="col-sm-12 col-md-6">
                    <div class="card">
                        <div class="card-body p-2">
                            <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                                PREMIUM TEMPLATE
                            </h4>
                            <div class="media">
                                <div class="media-left">
                                    <a href="https://www.creative-tim.com/product/material-dashboard-pro-angular2?affiliate_id=97705" class="ad-click-event">
                                        <img src="/uploads/images/free_templates/creative-tim-material-angular.png" alt="Material Dashboard Pro" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                    </a>
                                </div>
                                <div class="media-body ml-3">
                                    <div class="clearfix">
                                        <p class="pull-right">
                                            <a href="https://www.creative-tim.com/product/material-dashboard-pro-angular2?affiliate_id=97705" class="btn btn-success btn-sm ad-click-event">
                                                LEARN MORE
                                            </a>
                                        </p>

                                        <h4 style="margin-top: 0">Material Dashboard Pro ─ $59</h4>

                                        <p class="mb-1">Angular 2 Premium Material Bootstrap Admin with a fresh, new design inspired by Google's Material Design</p>
                                        <p style="margin-bottom: 0">
                                            <i class="fa fa-shopping-cart margin-r5"></i> 853+ purchases
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                            <div class="col-sm-12 col-md-6">
                    <div class="card">
                        <div class="card-body p-2">
                            <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                                PREMIUM TEMPLATE
                            </h4>
                            <div class="media">
                                <div class="media-left">
                                    <a href="https://www.creative-tim.com/product/now-ui-kit-pro?affiliate_id=97705" class="ad-click-event">
                                        <img src="/uploads/images/free_templates/now_ui_kit.jpg" alt="Now UI Kit" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                    </a>
                                </div>
                                <div class="media-body ml-3">
                                    <div class="clearfix">
                                        <p class="pull-right">
                                            <a href="https://www.creative-tim.com/product/now-ui-kit-pro?affiliate_id=97705" class="btn btn-success btn-sm ad-click-event">
                                                LEARN MORE
                                            </a>
                                        </p>

                                        <h4 style="margin-top: 0">Now UI Kit ─ $69</h4>

                                        <p class="mb-1">A beautiful Bootstrap 4 UI kit featuring over 1000 components, 34 sections and 11 example pages</p>
                                        <p style="margin-bottom: 0">
                                            <i class="fa fa-shopping-cart margin-r5"></i> 858+ purchases
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
    </div>
</div></section>
</template>

<script>
    export default {
        
    }
</script>        