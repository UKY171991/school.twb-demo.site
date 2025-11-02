<!-- Widget Configuration Modal -->
<div class="modal fade" id="widgetConfigModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-cogs mr-2"></i>
                    Customize Dashboard
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Drag and drop widgets to customize your dashboard layout.</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Available Widgets</h5>
                        <div id="available-widgets" class="widget-container">
                            <div class="widget-item" data-widget="enrollment-trends">
                                <i class="fas fa-chart-line"></i>
                                <span>Enrollment Trends</span>
                            </div>
                            <div class="widget-item" data-widget="class-performance">
                                <i class="fas fa-chart-bar"></i>
                                <span>Class Performance</span>
                            </div>
                            <div class="widget-item" data-widget="teacher-workload">
                                <i class="fas fa-users"></i>
                                <span>Teacher Workload</span>
                            </div>
                            <div class="widget-item" data-widget="attendance-summary">
                                <i class="fas fa-calendar-check"></i>
                                <span>Attendance Summary</span>
                            </div>
                            <div class="widget-item" data-widget="recent-activities">
                                <i class="fas fa-history"></i>
                                <span>Recent Activities</span>
                            </div>
                            <div class="widget-item" data-widget="upcoming-events">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Upcoming Events</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Active Widgets</h5>
                        <div id="active-widgets" class="widget-container">
                            @foreach($dashboardWidgets ?? [] as $widget)
                                <div class="widget-item active" data-widget="{{ $widget->widget_type }}" data-position="{{ $widget->position }}">
                                    <i class="fas fa-{{ $widget->widget_type === 'enrollment-trends' ? 'chart-line' : 'chart-bar' }}"></i>
                                    <span>{{ ucwords(str_replace('-', ' ', $widget->widget_type)) }}</span>
                                    <button type="button" class="btn btn-sm btn-danger remove-widget">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveWidgetConfig">Save Configuration</button>
            </div>
        </div>
    </div>
</div>

<style>
.widget-container {
    min-height: 200px;
    border: 2px dashed #ddd;
    border-radius: 5px;
    padding: 10px;
}

.widget-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
    cursor: move;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
}

.widget-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.widget-item.active {
    background: #d4edda;
    border-color: #c3e6cb;
}

.widget-item i {
    margin-right: 10px;
    color: #007bff;
}

.widget-item .remove-widget {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.widget-item:hover .remove-widget {
    opacity: 1;
}

.widget-container.drag-over {
    border-color: #007bff;
    background-color: #f8f9ff;
}
</style>

<script>
$(document).ready(function() {
    // Make widgets draggable
    $('.widget-item').draggable({
        helper: 'clone',
        revert: 'invalid',
        cursor: 'move'
    });
    
    // Make containers droppable
    $('.widget-container').droppable({
        accept: '.widget-item',
        over: function() {
            $(this).addClass('drag-over');
        },
        out: function() {
            $(this).removeClass('drag-over');
        },
        drop: function(event, ui) {
            $(this).removeClass('drag-over');
            
            const widget = ui.draggable.clone();
            const targetContainer = $(this);
            
            if (targetContainer.attr('id') === 'active-widgets') {
                widget.addClass('active');
                widget.append('<button type="button" class="btn btn-sm btn-danger remove-widget"><i class="fas fa-times"></i></button>');
            } else {
                widget.removeClass('active');
                widget.find('.remove-widget').remove();
            }
            
            targetContainer.append(widget);
            
            // Remove original if moving from active to available
            if (ui.draggable.hasClass('active') && targetContainer.attr('id') === 'available-widgets') {
                ui.draggable.remove();
            }
        }
    });
    
    // Remove widget functionality
    $(document).on('click', '.remove-widget', function() {
        $(this).closest('.widget-item').remove();
    });
    
    // Save widget configuration
    $('#saveWidgetConfig').click(function() {
        const activeWidgets = [];
        $('#active-widgets .widget-item').each(function(index) {
            activeWidgets.push({
                widget_type: $(this).data('widget'),
                position: index + 1,
                is_active: true
            });
        });
        
        $.ajax({
            url: '{{ route("admin.dashboard.save-widgets") }}',
            method: 'POST',
            data: {
                widgets: activeWidgets,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#widgetConfigModal').modal('hide');
                    showSuccess('Dashboard configuration saved successfully');
                    // Reload page to show new configuration
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showError('Failed to save configuration');
                }
            },
            error: function() {
                showError('Error saving configuration');
            }
        });
    });
});
</script>