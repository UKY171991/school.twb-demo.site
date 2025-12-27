@php
    $schools = \App\Models\School::active()->get();
    $currentSchool = $currentSchool ?? null;
@endphp

@if($schools->count() > 1)
<div class="navbar-nav ml-auto">
    <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="schoolDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-school"></i>
            <span class="d-none d-md-inline">
                {{ $currentSchool ? $currentSchool->name : 'Select School' }}
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="schoolDropdown">
            <h6 class="dropdown-header">
                <i class="fas fa-building"></i> Switch School
            </h6>
            <div class="dropdown-divider"></div>
            @foreach($schools as $school)
                <a class="dropdown-item {{ $currentSchool && $currentSchool->id == $school->id ? 'active' : '' }}" 
                   href="#" onclick="switchSchool({{ $school->id }})">
                    <i class="fas fa-school mr-2"></i>
                    {{ $school->name }}
                    @if($currentSchool && $currentSchool->id == $school->id)
                        <i class="fas fa-check text-success float-right mt-1"></i>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- School Switch Form (Hidden) -->
<form id="schoolSwitchForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="school_id" id="schoolIdInput">
</form>

<script>
function switchSchool(schoolId) {
    // Show loading indicator
    const dropdown = document.getElementById('schoolDropdown');
    const originalText = dropdown.innerHTML;
    dropdown.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Switching...';
    
    // Submit form to switch school
    fetch(`/schools/${schoolId}/switch`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to reflect the school change
            window.location.reload();
        } else {
            // Restore original text on error
            dropdown.innerHTML = originalText;
            alert('Error switching school. Please try again.');
        }
    })
    .catch(error => {
        // Restore original text on error
        dropdown.innerHTML = originalText;
        console.error('Error:', error);
        alert('Error switching school. Please try again.');
    });
}
</script>
@endif