<div class="modal fade" id="yClientsModal" tabindex="-1" role="dialog" aria-labelledby="yClientsModal"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="mt-0 mb-0 modal-title">[[%yclients_model_title]]</h2>
                <button type="button" class="close {if $modx->context->key == 'web'}closeInfo{/if}" data-dismiss="modal"
                        aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="yClientsForm">
                    <input type="hidden" name="service" value="yclients">
                    <input type="hidden" name="action" value="activity">
                    <input type="hidden" name="activity" class="timetable-activity" value="">
                    <input type="hidden" name="staff" class="timetable-staff" value="">
                    <input type="hidden" name="schedule" class="timetable-schedule" value="">
                    <input type="hidden" name="date" class="timetable-date" value="">


                    Занятие: <b class="timetable-title"></b><br>
                    Инструктор: <b class="timetable-staffname"></b><br>
                    Время записи: <b class="timetable-date-html"></b><br><br>


                    <div class="form-group">
                        <input type="text" class="form-control" name="fullname" placeholder="[[%yclients_name]]">
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" class="form-control" placeholder="[[%yclients_phone]]">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="[[%yclients_email]]">
                    </div>


                    [[-

                    <div class="form-column required">
                        <input type="text" class="form-control" name="fullname" placeholder="[[%yclients_name]]">
                    </div>

                    <div class="form-column required">
                        <input type="tel" name="phone" class="form-control" placeholder="[[%yclients_phone]]">
                    </div>
                    <div class="form-column">
                        <input type="text" class="form-control" name="email" placeholder="[[%yclients_email]]">
                    </div>

                    ]]
                    <div class="clearfix"></div>
                    <button class="btn btn-success">[[%yclients_btn_submit]]</button>
                    <input type="hidden" class="success2" data-toggle="modal" data-target="#infoModal">
                    <div class="time_errors d-none"></div>
                </form>
            </div>
        </div>
    </div>
</div>
