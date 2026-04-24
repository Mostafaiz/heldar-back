@props(['title' => '', 'class' => ''])

<h1 class="title {{ $class }}" {{ $attributes }}>@if ($title != '') {{ $title }} @endif</h1>
@if ($slot != '')
    <i class="guid-icon fa-solid fa-info-circle"></i>
    <div class="guid-pannel">
        {{ $slot }}
    </div>
@endif