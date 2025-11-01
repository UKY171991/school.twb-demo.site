@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'color' => 'primary',
    'collapsible' => false,
    'tools' => null,
    'footer' => null
])

<div class="card card-{{ $color }} {{ $collapsible ? 'collapsed-card' : '' }}">
    @if($title || $icon || $tools)
        <div class="card-header">
            <h3 class="card-title">
                @if($icon)
                    <i class="{{ $icon }} mr-2"></i>
                @endif
                {{ $title }}
                @if($subtitle)
                    <small class="text-muted">{{ $subtitle }}</small>
                @endif
            </h3>
            
            @if($tools || $collapsible)
                <div class="card-tools">
                    {!! $tools ?? '' !!}
                    @if($collapsible)
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-{{ $collapsible ? 'plus' : 'minus' }}"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer">
            {!! $footer !!}
        </div>
    @endif
</div>