<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
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
$routes->group('auth', function($routes)
{
	$routes->post('', 'AuthController::login');
	$routes->post('forgotpass', 'AuthController::forgot_pass');
	$routes->get('forgotpass/(:segment)', 'AuthController::verif_forgot_pass/$1');
	$routes->get('profile','AuthController::profile',['filter' => 'authapi']);
	$routes->put('profile','AuthController::update_profile',['filter' => 'authapi']);
	$routes->put('changepass','AuthController::change_pass',['filter' => 'authapi']);
});

$routes->group('distributor', function($routes)
{
	$routes->get('','DistributorController::index',['filter' => 'distributor']);
	$routes->put('','DistributorController::updateDistributor',['filter' => 'distributor']);
	$routes->group('barang', function($routes)
	{
		$routes->get('','BarangDistributorController::index',['filter' => 'distributor']);
		$routes->post('','BarangDistributorController::create',['filter' => 'distributor']);
		$routes->get('(:segment)/riwayatstok','BarangDistributorController::riwayatstok/$1',['filter' => 'distributor']);
		$routes->post('(:segment)/updatestok','BarangDistributorController::updatestok/$1',['filter' => 'distributor']);
		$routes->put('(:segment)','BarangDistributorController::update/$1',['filter' => 'distributor']);
		$routes->delete('(:segment)','BarangDistributorController::delete/$1',['filter' => 'distributor']);
	});

	$routes->group('toko', function($routes)
	{
		$routes->get('','TokoDistributorController::index',['filter' => 'distributor']);
		$routes->post('','TokoDistributorController::create',['filter' => 'distributor']);
		$routes->delete('(:segment)','TokoDistributorController::delete/$1',['filter' => 'distributor']);
	});
	$routes->group('transaksi', function($routes)
	{
		$routes->get('','TransaksiPenjualanDistributorController::index',['filter' => 'distributor']);
		$routes->post('','TransaksiPenjualanDistributorController::create',['filter' => 'distributor']);
		$routes->post('(:segment)/pelunasan','TransaksiPenjualanDistributorController::pelunasan/$1',['filter' => 'distributor']);
	});
	
});

$routes->group('toko', function($routes)
{
	$routes->get('','TokoController::index',['filter' => 'pemiliktokoandkaryawan']);
	$routes->put('','TokoController::updatetoko',['filter' => 'pemiliktoko']);
	$routes->group('karyawan', function($routes)
	{
		$routes->get('','KaryawanController::index',['filter' => 'pemiliktoko']);
		$routes->post('','KaryawanController::create',['filter' => 'pemiliktoko']);
		$routes->delete('(:segment)','KaryawanController::delete/$1',['filter' => 'pemiliktoko']);
	});
	$routes->group('barang', function($routes)
	{
		$routes->get('','BarangTokoController::index',['filter' => 'pemiliktokoandkaryawan']);
		$routes->put('(:segment)','BarangTokoController::update/$1',['filter' => 'pemiliktoko']);
		$routes->delete('(:segment)','BarangTokoController::delete/$1',['filter' => 'pemiliktoko']);
	});
});

$routes->group('job', function($routes)
{
	$routes->get('send_mail', 'CronJobController::sendMail');
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
