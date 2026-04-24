<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('manager.dashboard.index', function (BreadcrumbTrail $trail) {
    $trail->push(__('breadcrumbs.dashboard'), route('manager.dashboard.index'));
});

// Categories Index
Breadcrumbs::for('manager.categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.categories'), route('manager.categories.index'));
});

// Categories Create
Breadcrumbs::for('manager.categories.create', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.create'), route('manager.categories.create'));
});

// Categories Edit
Breadcrumbs::for('manager.categories.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('manager.categories.index');
    $trail->push(__('breadcrumbs.edit'), route('manager.categories.edit', $id));
});

// Products Index
Breadcrumbs::for('manager.products.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.products'), route('manager.products.index'));
});

// Products Create
Breadcrumbs::for('manager.products.create', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.create'), route('manager.products.create'));
});

// Products Create
Breadcrumbs::for('manager.products.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.edit'), route('manager.products.edit', $id));
});

// Colors Index
Breadcrumbs::for('manager.colors.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.colors'), route('manager.colors.index'));
});

// Attributes Index
Breadcrumbs::for('manager.attributes.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.attributes'), route('manager.attributes.index'));
});

// Sizes Index
Breadcrumbs::for('manager.sizes.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.sizes'), route('manager.sizes.index'));
});

// Guarantees Index
Breadcrumbs::for('manager.guarantees.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.guarantees'), route('manager.guarantees.index'));
});

// Insurances Index
Breadcrumbs::for('manager.insurances.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.products.index');
    $trail->push(__('breadcrumbs.insurances'), route('manager.insurances.index'));
});

// Shipping Index
Breadcrumbs::for('manager.shipping.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.shipping'), route('manager.shipping.index'));
});

// Gallery Index
Breadcrumbs::for('manager.gallery.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.gallery'), route('manager.gallery.index'));
});

// Users (Managers) Index
Breadcrumbs::for('manager.users.managers.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.managers'), route('manager.users.managers.index'));
});

Breadcrumbs::for('manager.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.users'), route('manager.users.index'));
});

Breadcrumbs::for('manager.demands.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.demands'), route('manager.demands.index'));
});

Breadcrumbs::for('manager.transactions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.transactions'), route('manager.transactions.index'));
});

Breadcrumbs::for('manager.slider', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.slider'), route('manager.slider'));
});

Breadcrumbs::for('manager.debit-cards.index', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.debit-cards'), route('manager.debit-cards.index'));
});

Breadcrumbs::for('manager.settings', function (BreadcrumbTrail $trail) {
    $trail->parent('manager.dashboard.index');
    $trail->push(__('breadcrumbs.settings'), route('manager.settings'));
});
