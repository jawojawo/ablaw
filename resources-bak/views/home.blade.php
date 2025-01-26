@extends('layout.app')

@section('main')
    <div class="container py-4">

        <div class="d-flex">
            <div id="sideCalendar" class="me-4" style="width: 300px;  overflow-y: auto;">

                <div class="card shadow-sm card shadow-sm mb-4">
                    <div class="card-header text-bg-primary  text-center">
                        <h5 id="selectedMonth" class="mb-0">Select Month</h5>
                    </div>
                    <div id="MonthDetails">
                        <div class="hearings">
                            <div class="card-header justify-content-between d-flex">
                                <span class="title">Hearings</span>
                                <span class="total"></span>
                            </div>
                            <div class="table-con">
                                <table class="table table-bordered table-striped mb-0"></table>
                            </div>
                        </div>
                        <div class="billings">
                            <div class="card-header  justify-content-between d-flex">
                                <span class="title">Billings</span>
                                <span class="total"></span>
                            </div>
                            <div class="table-con">
                                <table class="table table-bordered table-striped mb-0"></table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm card shadow-sm ">
                    <div class="card-header text-bg-primary text-center">
                        <h5 id="selectedDate" class="mb-0">Select a date</h5>
                    </div>
                    <div id="eventDetails">
                        <div class="hearings">
                            <div class="card-header justify-content-between d-flex">
                                <span class="title">Hearings</span>
                                <span class="total"></span>
                            </div>
                            <div class="table-con">
                                <table class="table table-bordered table-striped mb-0"></table>
                            </div>
                        </div>
                        <div class="billings">
                            <div class="card-header  justify-content-between d-flex">
                                <span class="title">Billings</span>
                                <span class="total"></span>
                            </div>
                            <div class="table-con">
                                <table class="table table-bordered table-striped mb-0"></table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="flex-1">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            selectDate({

                date: new Date(),
                dateStr: "2024-11-12"

            })

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                showNonCurrentDates: false,
                dateClick: function(info) {
                    $("#calendar").find('table td').removeClass('t-primary')
                    $(info.dayEl).closest('td').addClass('t-primary');
                    selectDate(info)
                },
                // events: [{
                //         title: 'Second Event',
                //         start: '2024-11-13T12:30:00',
                //         end: '2024-11-13T13:30:00'
                //     },
                //     {
                //         title: 'Second Event',
                //         start: '2024-11-13T12:30:00',
                //         end: '2024-11-13T13:30:00'
                //     },
                //     {
                //         title: 'Second Event',
                //         start: '2024-11-13T12:30:00',
                //         end: '2024-11-13T13:30:00'
                //     },
                //     {
                //         title: 'Second Event',
                //         start: '2024-11-13T12:30:00',
                //         end: '2024-11-13T13:30:00'
                //     },
                //     {
                //         title: 'Second Event',
                //         start: '2024-11-13T12:30:00',
                //         end: '2024-11-13T13:30:00'
                //     },
                //     {
                //         title: 'Second Event',
                //         start: '2024-11-13T12:30:00',
                //         end: '2024-11-13T13:30:00'
                //     },

                //     {
                //         title: 'Event 2',
                //         start: '2024-11-13',
                //     },

                // ],
                // eventContent: function(arg) {
                //     // <span class="badge  text-bg-warning ">Settled</span>
                //     let eventEl = document.createElement('span');
                //     eventEl.innerHTML = arg.event.title;
                //     eventEl.classList.add('badge', 'text-bg-warning')
                //     return {
                //         domNodes: [eventEl]
                //     };
                // }
            });
            calendar.render();
            //         calendar.addEvent({
            //     title: 'hearings',
            //     start: '2020-08-08T12:30:00',
            //     end: '2020-08-08T13:30:00'
            //   });
            function selectDate(info) {
                $("#selectedDate").text(window.formatDate(info.date))
                $("#selectedMonth").text(window.formatMonth(info.date))
                $.ajax({
                    url: '{{ route('dateInfo', '') }}/' + info.dateStr,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            hearings = response.data.hearings;
                            billings = response.data.billings;
                            $('#eventDetails .hearings .total').text(hearings.total)
                            $('#eventDetails .billings .total').text(billings.total)
                            setTable($('#eventDetails .hearings table'), hearings.data,
                                'hearings')
                            setTable($('#eventDetails .billings table'), billings.data,
                                'billings')
                        }
                    },
                    error: function(xhr) {
                        window.toastError(xhr);
                    }
                });
            }

            function setTable(container, data, type) {
                // Clear previous content
                container.empty();

                if (data.length === 0) {
                    container.append('<caption class="text-center">No entries found.</caption>');
                    return;
                }
                date = (type == 'billings') ? 'Due Date' : 'Date'
                // Append table headers (customize these based on data structure)
                container.append(`
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>${date}</th>
            </tr>
        </thead>
        <tbody></tbody>
    `);

                // Populate table rows
                const tbody = container.find('tbody');
                data.forEach(item => {
                    date = date = (type == 'billings') ? item.due_date : item.hearing_date
                    tbody.append(`
            <tr>
                <td>${item.id}</td>
                <td>${item.title}</td>
                <td>${window.formatTime(date)}</td>
            </tr>
        `);
                });
            }

        });
    </script>
@endsection
