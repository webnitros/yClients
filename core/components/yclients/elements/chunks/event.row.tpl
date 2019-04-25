<div class="col-md-3">
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title pt-0 mt-0">{$service.title}</h4>
            <h6 class="card-subtitle mb-2 text-muted">{$time}</h6>
            <h6 class="card-subtitle mb-2 text-muted">[[%yclients_number_places]]: <b>{$places}</b>/{$capacity}</h6>

            <hr>

            <div class="text-center">
                <p class="card-text">
                    [[+staff.specialization:stripString=`Slide&FIT`]]<br>
                    <b title="[[%yclients_staff_name]]">{$staff.name}</b>
                </p>
                <button class="btn btn-register " data-modification="{$date_modification}" data-staff="{$staff_id}"
                        data-title="{$service.title}" data-staffname="{$staff.name}" data-date="{$date}" data-schedule="{$service.id}"
                        data-activity="{$id}">
                    [[%yclients_btn_order]]
                </button>
            </div>
        </div>
    </div>
</div>