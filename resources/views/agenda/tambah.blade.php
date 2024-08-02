
@extends('agenda.components.main')

@section('content')
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Calendar View -->
    <nav class="justify-between px-4 py-3 text-black border border-gray-200 rounded-lg sm:flex sm:px-5 bg-gradient-to-r from-cyan-500 to-blue-500 focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 dark:border-gray-700 text-lg" aria-label="Breadcrumb">
        <ol class="inline-flex items-center mb-3 space-x-1 md:space-x-2 rtl:space-x-reverse sm:mb-0">
            <li>
                <div class="flex items-center">
                    <a href="{{ route('agenda.index') }}" class="ms-1 text-2xl font-semibold text-white">Tambah Agenda</a>
                </div>
            </li>
        </ol>
    </nav>

    <div id="calendar" style="max-width: 1200px; margin: 20px auto;"></div>

    <!-- Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="form-group">
                            <label for="eventTitle">Title</label>
                            <input type="text" class="form-control" id="eventTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="eventStart">Start</label>
                            <input type="datetime-local" class="form-control" id="eventStart" name="start" required>
                        </div>
                        <div class="form-group">
                            <label for="eventEnd">End</label>
                            <input type="datetime-local" class="form-control" id="eventEnd" name="end">
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="eventAllDay" name="all_day">
                                <label class="form-check-label" for="eventAllDay">All Day</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="eventLocation">Location</label>
                            <input type="text" class="form-control" id="eventLocation" name="location">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: '{{ route('agenda.events.get') }}', // Fetch events from server
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            navLinks: true, // Click day/week names to navigate views
            businessHours: true, // Display business hours
            editable: true,
            selectable: true,
            selectMirror: true,
            select: function(arg) {
                // Open the modal when a date is selected
                $('#eventTitle').val('');
                $('#eventStart').val(formatDate(arg.start));
                $('#eventEnd').val(arg.end ? formatDate(arg.end) : '');
                $('#eventAllDay').prop('checked', arg.allDay);
                $('#eventLocation').val('');
                $('#eventModal').modal('show');

                // Handle form submission
                $('#eventForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    fetch('{{ route('agenda.events.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            title: $('#eventTitle').val(),
                            start: $('#eventStart').val(),
                            end: $('#eventEnd').val(),
                            all_day: $('#eventAllDay').is(':checked'),
                            location: $('#eventLocation').val()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            alert('Error adding event: ' + JSON.stringify(data.errors));
                        } else {
                            calendar.addEvent({
                                id: data.id,
                                title: data.title,
                                start: data.start,
                                end: data.end,
                                allDay: data.all_day,
                                location: data.location
                            });
                            $('#eventModal').modal('hide');
                        }
                    })
                    .catch(error => {
                        alert('Error adding event: ' + error.message);
                    });
                });
            },
            eventClick: function(arg) {
                if (confirm('Are you sure you want to delete this event?')) {
                    fetch('{{ route('agenda.events.destroy', ':id') }}'.replace(':id', arg.event.id), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE',
                            id: arg.event.id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            alert('Error deleting event: ' + JSON.stringify(data.errors));
                        } else {
                            arg.event.remove();
                            alert('Event deleted successfully.');
                        }
                    })
                    .catch(error => {
                        alert('Error deleting event: ' + error.message);
                    });
                }
            }
        });

        calendar.render();

        function formatDate(date) {
            return date.toISOString().slice(0, 19).replace('T', ' ');
        }
    });
    </script>
@endsection