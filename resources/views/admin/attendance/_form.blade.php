<div class="form-group">
    <label for="status">Status</label>
    <select class="form-control" id="status" name="status" required>
        <option value="present" {{ (isset($attendance) && $attendance->status == 'present') ? 'selected' : '' }}>Present</option>
        <option value="absent" {{ (isset($attendance) && $attendance->status == 'absent') ? 'selected' : '' }}>Absent</option>
        <option value="late" {{ (isset($attendance) && $attendance->status == 'late') ? 'selected' : '' }}>Late</option>
        <option value="excused" {{ (isset($attendance) && $attendance->status == 'excused') ? 'selected' : '' }}>Excused</option>
    </select>
</div>

<div class="form-group">
    <label for="remarks">Remarks</label>
    <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks', $attendance->remarks ?? '') }}</textarea>
</div>
