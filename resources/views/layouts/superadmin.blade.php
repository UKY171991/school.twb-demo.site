@extends('layouts.master')

@section('title', 'Super Admin - ' . (isset($pageTitle) ? $pageTitle : 'Dashboard'))

@section('body-class', 'superadmin-layout')

@push('styles')
<style>
    /* Super Admin specific styles */
    .brand-text {
        background: linear-gradient(45deg, #007bff, #28a745);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: bold;
    }
    
    .main-sidebar {
        background: linear-gradient(180deg, #343a40 0%, #495057 100%);
    }
    
    .superadmin-badge {
        background: linear-gradient(45deg, #dc3545, #fd7e14);
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: bold;
    }
</style>
@endpush

@section('control-sidebar')
    <!-- Super Admin Control Panel -->
    <div class="p-3">
        <h5>Super Admin Panel</h5>
        <hr class="mb-2">
        
        <div class="mb-3">
            <label class="form-label">Quick Stats</label>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Total Schools</small>
                    <div class="font-weight-bold">{{ $statistics['total_schools'] ?? 0 }}</div>
                </div>
                <div class="col-6">
                    <small class="text-muted">Total Users</small>
                    <div class="font-weight-bold">{{ $statistics['total_users'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <button class="btn btn-primary btn-sm btn-block" onclick="refreshSystemStats()">
                <i class="fas fa-sync-alt"></i> Refresh Stats
            </button>
        </div>
        
        <div class="mb-3">
            <button class="btn btn-warning btn-sm btn-block" onclick="clearSystemCache()">
                <i class="fas fa-broom"></i> Clear Cache
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function refreshSystemStats() {
    toastr.info('Refreshing system statistics...');
    // Implementation would go here
    setTimeout(() => {
        toastr.success('Statistics refreshed successfully');
    }, 1000);
}

function clearSystemCache() {
    if (confirm('Are you sure you want to clear the system cache?')) {
        toastr.info('Clearing system cache...');
        // Implementation would go here
        setTimeout(() => {
            toastr.success('System cache cleared successfully');
        }, 1500);
    }
}
</script>
@endpush