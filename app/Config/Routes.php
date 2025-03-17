<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Route Antrian BPJS
// $routes->get('/', 'Home::index');
$routes->get('/bpjs', 'AntrianController::index');
$routes->get('display', 'AntrianController::display');
$routes->get('antrian/hari-ini', 'AntrianController::getAntrianHariIni');
$routes->get('antrian/antrianSekarang', 'AntrianController::getAntrianSekarang');
$routes->get('antrian/racikan', 'AntrianController::getAntrianRacikan');
$routes->post('antrian/proses/(:segment)', 'AntrianController::updateProses/$1');
$routes->post('antrian/call/(:segment)', 'AntrianController::callAntrian/$1');
$routes->post('antrian/done/(:segment)', 'AntrianController::doneAntrian/$1');
$routes->post('antrian/recall', 'AntrianController::recallAntrian');
$routes->post('antrian/nextAntrian', 'AntrianController::nextAntrian');
$routes->post('antrian/doneList/(:segment)', 'AntrianController::doneList/$1');
$routes->get('antrian/antrianAktif', 'AntrianController::getAntrianSekarang');
$routes->get('antrian/prosesRacikan', 'AntrianController::getProsesRacikan');

// Route Antrian Eksekutif
$routes->get('/eksekutif', 'EksekutifController::index');
$routes->get('display-ekse', 'EksekutifController::displayEkse');
$routes->get('antrian/hari-ini-ekse', 'EksekutifController::getAntrianHariIniEkse');
$routes->get('antrian/antrianSekarang-ekse', 'EksekutifController::getAntrianSekarangEkse');
$routes->get('antrian/racikan-ekse', 'EksekutifController::getAntrianRacikanEkse');
$routes->post('antrian/proses-ekse/(:segment)', 'EksekutifController::updateProsesEkse/$1');
$routes->post('antrian/call-ekse/(:segment)', 'EksekutifController::callAntrianEkse/$1');
$routes->post('antrian/done-ekse/(:segment)', 'EksekutifController::doneAntrianEkse/$1');
$routes->post('antrian/recall-ekse', 'EksekutifController::recallAntrianEkse');
$routes->post('antrian/nextAntrian-ekse', 'EksekutifController::nextAntrianEkse');
$routes->post('antrian/doneList-ekse/(:segment)', 'EksekutifController::doneListEkse/$1');
$routes->get('antrian/antrianAktif-ekse', 'EksekutifController::getAntrianSekarangEkse');
$routes->get('antrian/prosesRacikan-ekse', 'EksekutifController::getProsesRacikanEkse');