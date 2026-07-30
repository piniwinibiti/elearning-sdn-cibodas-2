@props(['messages' => [], 'name' => null])

@php
    $messages = $messages ?: ($name ? $errors->get($name) : []);
@endphp

@if (! empty($messages))
    <ul {{ $attributes->merge(['class' => 'mt-1.5 space-y-1 text-sm text-red-600 dark:text-red-400']) }}
        role="alert"
        @if($name) id="{{ $name }}-error" @endif>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
