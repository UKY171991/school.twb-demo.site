<div class="clock-widget-topbar">
    <div class="clock-time-topbar">
        <i class="fas fa-clock mr-1"></i>
        <span id="topbarTimeDisplay">--:--:--</span>
    </div>
    <div class="clock-date-topbar" id="topbarClockDate">--</div>
</div>

<style>
    .clock-widget-topbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        color: #fff;
        text-align: center;
        min-width: 180px;
        margin-right: 0.75rem;
        transition: all 0.3s ease;
    }
    .clock-widget-topbar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .clock-time-topbar {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 0.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .clock-date-topbar {
        font-size: 0.65rem;
        opacity: 0.9;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
</style>

<script>
    function updateTopbarClock() {
        const now = new Date();
        
        // Format time
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        
        // Convert to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12;
        hours = String(hours).padStart(2, '0');
        
        const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
        
        // Format date
        const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
        const dateString = now.toLocaleDateString('en-US', options);
        
        // Update DOM
        const timeElement = document.getElementById('topbarTimeDisplay');
        const dateElement = document.getElementById('topbarClockDate');
        
        if (timeElement) timeElement.textContent = timeString;
        if (dateElement) dateElement.textContent = dateString;
    }
    
    // Update clock immediately and then every second
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            updateTopbarClock();
            setInterval(updateTopbarClock, 1000);
        });
    } else {
        updateTopbarClock();
        setInterval(updateTopbarClock, 1000);
    }
</script>
