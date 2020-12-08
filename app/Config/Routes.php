<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->group('auth', function ($routes) {
	$routes->post('', 'AuthController::login');
	$routes->post('forgotpass', 'AuthController::forgot_pass');
	$routes->get('forgotpass/(:segment)', 'AuthController::verif_forgot_pass/$1');
	$routes->get('profile', 'AuthController::profile', ['filter' => 'authapi']);
	$routes->put('profile', 'AuthController::update_profile', ['filter' => 'authapi']);
	$routes->put('changepass', 'AuthController::change_pass', ['filter' => 'authapi']);
});

$routes->group('distributor', ['filter' => 'distributor', 'namespace' => 'App\Controllers\Distributor'], function ($routes) {
	$routes->get('', 'DistributorController::index');
	$routes->put('', 'DistributorController::updateDistributor');
	$routes->get('penjualan', 'PenjualanDistributorController');
	$routes->get('dashboard', 'DashboardDistributorController');
	$routes->group('barang', function ($routes) {
		// $routes->get('', 'BarangDistributorController::index');
		// $routes->get('(:segment)', 'BarangDistributorController::show/$1');
		// $routes->post('', 'BarangDistributorController::create');
		// $routes->put('(:segment)', 'BarangDistributorController::update/$1');
		// $routes->delete('(:segment)', 'BarangDistributorController::delete/$1');
		$routes->get('static', 'BarangDistributorController::static');
		$routes->get('(:segment)/riwayatstok', 'BarangDistributorController::riwayatstok/$1');
		$routes->post('(:segment)/updatestok', 'BarangDistributorController::updatestok/$1');
	});
	$routes->resource('barang', ['only' => ['index', 'show', 'update', 'delete', 'create'], 'controller' => 'BarangDistributorController']);

	$routes->resource('jenis', ['only' => ['index', 'update', 'delete', 'create'], 'controller' => 'JenisBarangController']);
	$routes->resource('ukuran', ['only' => ['index', 'update', 'delete', 'create'], 'controller' => 'UkuranBarangController']);
	$routes->group('toko', function ($routes) {
		$routes->resource('barang', ['only' => ['index', 'show'], 'controller' => 'BarangTokoDistributorController']);
	});
	$routes->resource('toko', ['only' => ['index', 'create', 'delete'], 'controller' => 'TokoDistributorController']);
	// $routes->group('toko', function ($routes) {
	// 	$routes->get('', 'TokoDistributorController::index');
	// 	$routes->post('', 'TokoDistributorController::create');
	// 	$routes->delete('(:segment)', 'TokoDistributorController::delete/$1');
	// });
	$routes->group('transaksi', function ($routes) {
		// $routes->get('', 'TransaksiPenjualanDistributorController::index');
		// $routes->post('', 'TransaksiPenjualanDistributorController::create');
		$routes->post('(:segment)/pelunasan', 'TransaksiPenjualanDistributorController::pelunasan/$1');
	});
	$routes->resource('transaksi', ['only' => ['index', 'create'], 'controller' => 'TransaksiPenjualanDistributorController']);
});

$routes->group('toko', ['namespace' => 'App\Controllers\Toko'], function ($routes) {
	$routes->group('barang', function ($routes) {
		$routes->get('', 'BarangTokoController::index', ['filter' => 'pemiliktokoandkaryawan']);
		$routes->get('(:num)', 'BarangTokoController::show/$1', ['filter' => 'pemiliktokoandkaryawan']);
		$routes->put('(:segment)', 'BarangTokoController::update/$1', ['filter' => 'pemiliktoko']);
	});
	$routes->group('karyawan', function ($routes) {
		$routes->get('', 'KaryawanController::index', ['filter' => 'pemiliktoko']);
		$routes->post('', 'KaryawanController::create', ['filter' => 'pemiliktoko']);
		$routes->delete('(:segment)', 'KaryawanController::delete/$1', ['filter' => 'pemiliktoko']);
	});
	$routes->resource('transaksi', ['filter' => 'pemiliktokoandkaryawan', 'only' => ['index', 'create'], 'controller' => 'TransaksiPenjualanTokoController']);
	$routes->get('penjualan', 'PenjualanTokoController', ['filter' => 'pemiliktokoandkaryawan']);
	$routes->get('dashboard', 'DashboardTokoController', ['filter' => 'pemiliktokoandkaryawan']);
	$routes->get('tanggungan', 'TanggunganTokoController::index', ['filter' => 'pemiliktoko']);
	$routes->put('token', 'TokoController::updatetoken', ['filter' => 'pemiliktoko']);
	$routes->get('', 'TokoController::index', ['filter' => 'pemiliktokoandkaryawan']);
	$routes->put('', 'TokoController::updatetoko', ['filter' => 'pemiliktoko']);
});

$routes->group('job', function ($routes) {
	$routes->get('send_mail', 'CronJobController::sendMail');
	$routes->get('send_notif_utang', 'CronJobController::sendNotifUtang');
});


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
