@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'help' => null,
    'options' => [],
    'multiple' => false,
    'class' => '',
    'id' => null
])

@php
    $id = $id ?? $name;
    $value = old($name, $value);
    $hasError = $errors->has($name);
    $inputClass = 'form-control' . ($hasError ? ' is-invalid' : '') . ($class ? ' ' . $class : '');
@endphp

<div class="form-group">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    
    @switch($type)
        @case('select')
            <select 
                name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
                id="{{ $id }}" 
                class="{{ $inputClass }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $multiple ? 'multiple' : '' }}
                {{ $attributes }}
            >
                @if(!$multiple && !$required)
                    <option value="">{{ $placeholder ?? 'Select an option' }}</option>
                @endif
                @foreach($options as $optionValue => $optionLabel)
                    <option 
                        value="{{ $optionValue }}" 
                        {{ (is_array($value) ? in_array($optionValue, $value) : $value == $optionValue) ? 'selected' : '' }}
                    >
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
            @break
            
        @case('textarea')
            <textarea 
                name="{{ $name }}" 
                id="{{ $id }}" 
                class="{{ $inputClass }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes }}
            >{{ $value }}</textarea>
            @break
            
        @case('checkbox')
            <div class="form-check">
                <input 
                    type="checkbox" 
                    name="{{ $name }}" 
                    id="{{ $id }}" 
                    class="form-check-input{{ $hasError ? ' is-invalid' : '' }}"
                    value="1"
                    {{ $value ? 'checked' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                    {{ $attributes }}
                >
                @if($label)
                    <label class="form-check-label" for="{{ $id }}">
                        {{ $label }}
                    </label>
                @endif
            </div>
            @break
            
        @case('radio')
            @foreach($options as $optionValue => $optionLabel)
                <div class="form-check">
                    <input 
                        type="radio" 
                        name="{{ $name }}" 
                        id="{{ $id }}_{{ $optionValue }}" 
                        class="form-check-input{{ $hasError ? ' is-invalid' : '' }}"
                        value="{{ $optionValue }}"
                        {{ $value == $optionValue ? 'checked' : '' }}
                        {{ $disabled ? 'disabled' : '' }}
                        {{ $attributes }}
                    >
                    <label class="form-check-label" for="{{ $id }}_{{ $optionValue }}">
                        {{ $optionLabel }}
                    </label>
                </div>
            @endforeach
            @break
            
        @case('file')
            <input 
                type="file" 
                name="{{ $name }}" 
                id="{{ $id }}" 
                class="{{ $inputClass }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $multiple ? 'multiple' : '' }}
                {{ $attributes }}
            >
            @break
            
        @default
            <input 
                type="{{ $type }}" 
                name="{{ $name }}" 
                id="{{ $id }}" 
                class="{{ $inputClass }}"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes }}
            >
    @endswitch
    
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>