var yClients = {
    loading: false,
    form: null,
    model: null,
    schedule: null,
    events: null,
    day: yClientsConfig.today,
    options: {
        form: '#yClientsForm',
        schedule: '#yClientsSchedule',
        events: '#yClientsEvents',
        datepicker: '#yClientsDatepicker',
        modal: '#yClientsModal',
    },
    init: function () {

        this.form = $(this.options.form)
        this.schedule = $(this.options.schedule)
        this.events = $(this.options.events)
        this.modal = $(this.options.modal)

        // Показать модельное окно
        $(document).on('submit', this.options.selector, function (e) {
            e.preventDefault()
            var formData = null
            if (yClients.form) {
                if (yClients.form.length) {
                    formData = yClients.form.serializeArray()
                }
            }
            yClients.send(formData, yClients.callbacks.activity)
            return true
        })

        // Показать модельное окно
        $(document).on('click', this.options.schedule + ' a', function (e) {
            e.preventDefault()
            yClients.resetSchedule()

            yClients.schedule.find('a').removeClass('active')

            $(this).addClass('active')

            yClients.day = $(this).data('day')
            yClients.send({
                action: 'events',
                service: 'yclients',
                date: yClients.day
            }, yClients.callbacks.events)
            return true
        })

        // После запуска окна
        $(document).on('click', '#yClients .btn-register', function (e) {
            var data = $(this).data()
            yClients.form.find('.timetable-title').html(data.title)
            yClients.form.find('.timetable-title').html(data.title)
            yClients.form.find('.timetable-schedule').val(data.schedule)
            yClients.form.find('.timetable-date').val(data.date)
            yClients.form.find('.timetable-date-html').html(data.modification)
            yClients.form.find('.timetable-staff').val(data.staff)
            yClients.form.find('.timetable-staffname').html(data.staffname)
            yClients.form.find('.timetable-activity').val(data.activity)
            yClients.modal.modal('show')
        })

        this.datepicker()
    },
    resetSchedule: function () {
        yClients.schedule.find('li').removeClass('active')
    },
    datepicker: function () {
        var startDate = new Date()
        $(this.options.datepicker).datepicker({
            language: 'ru',
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate: startDate,
            immediateUpdates: true,
            /* beforeShowDay: function (dt) {
                 return available(dt)
             }*/
        }).on('changeDate', function (e) {

            var date = $(this).datepicker('getDate')
            var yyyy = date.getFullYear().toString()
            var mm = (date.getMonth() + 1).toString() // getMonth() is zero-based
            var dd = date.getDate().toString()
            var datetime = (dd[1] ? dd : '0' + dd[0]) + '-' + (mm[1] ? mm : '0' + mm[0]) + '-' + yyyy // padding
            yClients.day = datetime
            yClients.send({
                action: 'events',
                service: 'yclients',
                date: datetime,
            }, yClients.callbacks.events)

        })
        /* function available (date) {
             var dmy = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate()
             if ($.inArray(dmy, yClientsConfig.datesEnabled) != -1) {
                 return date
             } else {
                 return false
             }
         }*/
    },
    callbacks: {
        events: function (response, params) {
            yClients.events.html(response.data)
        },
        activity: function (response, params) {
            if (response.success) {
                yClients.modal.modal('hide')
                yClients.modal.find('.success2').click()

                yClients.send({
                    action: 'events',
                    service: 'yclients',
                    date: yClients.day
                }, yClients.callbacks.events)

            }
        }
    },
    send: function (params, callback) {
        $.post('/', params, function (response) {
            yClients.loading = false

            if (callback && $.isFunction(callback)) {
                callback.call(this, response, params)
            } else {
                /*if (response.success) {
                    yClients.Message.success(response.message)
                } else {
                    yClients.Message.error(response.message, false)
                }*/
            }

        }, 'json')
    },
}
yClients.init()