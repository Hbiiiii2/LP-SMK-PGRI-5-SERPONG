<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public routes
$routes->get('/', 'Home::index');
$routes->get('/sitemap.xml', 'Sitemap::index');

// News
$routes->get('/news', 'News::index');
$routes->get('/news/search', 'News::search');
$routes->get('/news/(:any)', 'News::detail/$1');
$routes->post('/news/(:any)/comment', 'News::comment/$1');

// Downloads
$routes->get('/downloads', 'Downloads::index');

// Contact
$routes->get('/contact', 'Contact::index');
$routes->post('/contact/send', 'Contact::send');

// SPMB Online
$routes->get('/spmb', 'Spmb::index');
$routes->post('/spmb/submit', 'Spmb::submit');

// Multi-page frontend (university-theme) — must be before catch-all
$routes->get('/profile', 'Site::profile');
$routes->get('/program', 'Site::program');
$routes->get('/program/(:segment)', 'Site::programDetail/$1');
$routes->get('/teachers', 'Site::teachers');
$routes->get('/achievements', 'Site::achievements');
$routes->get('/extracurriculars', 'Site::extracurriculars');
$routes->get('/testimonials', 'Site::testimonials');
$routes->get('/gallery', 'Site::gallery');
$routes->get('/agenda', 'Site::agenda');
$routes->get('/faq', 'Site::faq');

// Auth
$routes->get('/login', 'Admin\Auth::login');
$routes->post('/login/attempt', 'Admin\Auth::attempt');
$routes->post('/logout', 'Admin\Auth::logout');

// Admin downloads
$routes->presenter('/admin/downloads', [
    'controller' => 'Admin\Downloads',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/downloads/delete/(:num)', 'Admin\Downloads::delete/$1');
$routes->post('/admin/downloads/reorder', 'Admin\Downloads::reorder');

// Admin dashboard
$routes->get('/admin', 'Admin\Dashboard::index');
$routes->get('/admin/dashboard', 'Admin\Dashboard::index');

// Admin settings
$routes->get('/admin/settings', 'Admin\Settings::index');
$routes->post('/admin/settings/save', 'Admin\Settings::save');

// Admin CRUD
$routes->post('/admin/news/quick-update/(:num)', 'Admin\News::quickUpdate/$1');
$routes->presenter('/admin/news', [
    'controller' => 'Admin\News',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/news/delete/(:num)', 'Admin\News::delete/$1');
$routes->presenter('/admin/teachers', [
    'controller' => 'Admin\Teachers',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/teachers/delete/(:num)', 'Admin\Teachers::delete/$1');
$routes->post('/admin/teachers/reorder', 'Admin\Teachers::reorder');
$routes->presenter('/admin/programs', [
    'controller' => 'Admin\Programs',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/programs/delete/(:num)', 'Admin\Programs::delete/$1');
$routes->presenter('/admin/extracurriculars', [
    'controller' => 'Admin\Extracurriculars',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/extracurriculars/delete/(:num)', 'Admin\Extracurriculars::delete/$1');
$routes->presenter('/admin/achievements', [
    'controller' => 'Admin\Achievements',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/achievements/delete/(:num)', 'Admin\Achievements::delete/$1');
$routes->presenter('/admin/testimonials', [
    'controller' => 'Admin\Testimonials',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/testimonials/delete/(:num)', 'Admin\Testimonials::delete/$1');
$routes->presenter('/admin/events', [
    'controller' => 'Admin\Events',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/events/delete/(:num)', 'Admin\Events::delete/$1');
$routes->presenter('/admin/gallery', [
    'controller' => 'Admin\Galleries',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/gallery/delete/(:num)', 'Admin\Galleries::delete/$1');
$routes->post('/admin/gallery/reorder', 'Admin\Galleries::reorder');
$routes->post('/admin/faq/reorder', 'Admin\Faq::reorder');
$routes->post('/admin/testimonials/reorder', 'Admin\Testimonials::reorder');
$routes->post('/admin/extracurriculars/reorder', 'Admin\Extracurriculars::reorder');
$routes->post('/admin/achievements/reorder', 'Admin\Achievements::reorder');
$routes->post('/admin/programs/reorder', 'Admin\Programs::reorder');
$routes->presenter('/admin/faq', [
    'controller' => 'Admin\Faq',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/faq/delete/(:num)', 'Admin\Faq::delete/$1');
$routes->presenter('/admin/users', [
    'controller' => 'Admin\Users',
    'placeholder' => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/users/delete/(:num)', 'Admin\Users::delete/$1');

// Admin uploads
$routes->get('/admin/uploads', 'Admin\Uploads::index');
$routes->post('/admin/uploads/delete', 'Admin\Uploads::delete');

// Admin profile
$routes->get('/admin/profile', 'Admin\Profile::index');
$routes->post('/admin/profile/save', 'Admin\Profile::update');

// Admin messages
$routes->get('/admin/messages', 'Admin\Messages::index');
$routes->get('/admin/messages/(:num)', 'Admin\Messages::show/$1');
$routes->post('/admin/messages/delete/(:num)', 'Admin\Messages::delete/$1');
$routes->post('/admin/messages/read/(:num)', 'Admin\Messages::markRead/$1');
$routes->post('/admin/messages/unread/(:num)', 'Admin\Messages::markUnread/$1');
$routes->post('/admin/messages/bulk-delete', 'Admin\Messages::bulkDelete');

// Admin SPMB
$routes->get('/admin/spmb/export', 'Admin\Spmb::exportCsv');
$routes->get('/admin/spmb/send-wa/(:num)', 'Admin\Spmb::sendWa/$1');
$routes->post('/admin/spmb/verify/(:num)', 'Admin\Spmb::verify/$1');
$routes->post('/admin/spmb/delete/(:num)', 'Admin\Spmb::delete/$1');
$routes->presenter('/admin/spmb', [
    'controller'  => 'Admin\Spmb',
    'placeholder' => '(:num)',
    'only'        => ['index', 'show', 'new', 'create', 'edit', 'update', 'delete'],
]);

// Admin comments
$routes->get('/admin/comments', 'Admin\Comments::index');
$routes->post('/admin/comments/approve/(:num)', 'Admin\Comments::approve/$1');
$routes->post('/admin/comments/reject/(:num)', 'Admin\Comments::reject/$1');
$routes->post('/admin/comments/delete/(:num)', 'Admin\Comments::delete/$1');
$routes->post('/admin/comments/bulk', 'Admin\Comments::bulk');

// Admin pages
$routes->presenter('/admin/pages', [
    'controller'   => 'Admin\Pages',
    'placeholder'  => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/pages/delete/(:num)', 'Admin\Pages::delete/$1');

// Admin menus
$routes->presenter('/admin/menus', [
    'controller'   => 'Admin\Menus',
    'placeholder'  => '(:num)',
    'only'         => ['index', 'new', 'create', 'edit', 'update', 'delete'],
]);
$routes->post('/admin/menus/delete/(:num)', 'Admin\Menus::delete/$1');
$routes->post('/admin/menus/reorder', 'Admin\Menus::reorder');

// Public pages (catch-all — must be last)
$routes->get('/(:any)', 'Pages::view/$1');
