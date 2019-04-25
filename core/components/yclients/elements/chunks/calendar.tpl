<div id="yClients">
    <ul class="nav nav-pills yclientsschedule-nav" id="yClientsSchedule">
        <li class="nav-item">
            <a href="#" class="nav-link active" data-day="today">[[%yclients_today]]</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-day="tomorrow">[[%yclients_tomorrow]]</a>
        </li>
        <li class="ml-2">
            <div href="#3b" data-toggle="tab" class="yclients-calendar">
                <input type="text" id="yClientsDatepicker" placeholder="[[%yclients_change_date]]">
            </div>
        </li>
    </ul>
    {$_modx->runSnippet('yClientsSchedule')}
</div>