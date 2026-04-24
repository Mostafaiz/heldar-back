@props(['title' => '', 'route', 'id' => ''])
@use('Diglactic\Breadcrumbs\Breadcrumbs')

<div class="title-section">
    <h1 class="large-title">{{ $title }}</h1>
    <h3 class="bread-crumb">{{ Breadcrumbs::render($route, $id) }}</h3>
</div>