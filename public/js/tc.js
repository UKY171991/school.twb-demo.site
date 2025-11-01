$(document).ready(function() {
    // Load today's schedule
    loadTodaySchedule();
    
    function loadTodaySchedule() {
        $.ajax({
            url: '/ajax/teacher/schedule/today',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const schedule = response.data.schedule;
                    let scheduleHtml = '<div class="table-responsive"><table class="table table-striped">';
                    scheduleHtml += '<thead><tr><th>Time</th><th>Class</th><th>Subject</th><th>Room</th></tr></thead><tbody>';
                    
                    for (let time in schedule) {
                        const item = schedule[time];
                        scheduleHtml += `<tr>
                            <td>${time}</td>
                            <td>${item.class}</td>
                            <td>${item.subject}</td>
                            <td>${item.room}</td>
                        </tr>`;
                    }
                    
                    scheduleHtml += '</tbody></table></div>';
                    $('#today-schedule').html(scheduleHtml);
                } else {
                    $('#today-schedule').html('<div class="alert alert-info">No schedule available for today.</div>');
                }
            },
            error: function() {
                $('#today-schedule').html('<div class="alert alert-danger">Failed to load schedule.</div>');
            }
        });
    }
});