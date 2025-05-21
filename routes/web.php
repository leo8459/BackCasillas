<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CasillaController;

Route::apiResource('/alquileres','AlquilereController');  //editar agragar eliminar listar apiresource

Route::group(['prefix'=>'api'],function(){
    Route::post('/login','UserController@login');//solo para logear
    Route::get('/ver1/{seccionId}','CasillaController@obtenercasillas');//solo para logear
    Route::get('/ver2/{seccionId}', 'CasillaController@obtenerInformacionAlquileres');
    Route::get('/fecha/{alquilerId}', 'AlquilereController@verificarFechaPorVencer');
    Route::get('/ver3/{busquedaid}','CasillaController@busquedas');//solo para logear
    Route::get('/todas-las-casillas','CasillaController@obtenerTodasLasCasillas');


    Route::get('/dashboard','DashboardController@patito');//solo para logear
    Route::get('/reportes/alquileres/{alquilere}', 'AlquilereController@pdf');
    Route::get('/todas-las-casillas','CasillaController@obtenerTodasLasCasillas');

    Route::post('/reservar-casillas', 'AlquilereController@reservarCasillas');
    Route::post('update-all-to-ocupadas', 'AlquilereController@updateAllToOcupadas');
    Route::post('update-casillas-seleccionadas','AlquilereController@updateCasillasSeleccionadas');
    Route::post('/login3','CajeroController@login3');//solo para logear
    Route::get('/ver3/{busquedaid}','CasillaController@busquedas');//solo para logear

    Route::get('/ver1/{seccionId}','CasillaController@obtenercasillas');//solo para logear
    Route::get('/ver2/{seccionId}', 'CasillaController@obtenerInformacionAlquileres');
    Route::get('/fecha/{alquilerId}', 'AlquilereController@verificarFechaPorVencer');
    Route::get('/reportes/alquileres/{alquilere}', 'AlquilereController@pdf');


    Route::apiResource('/users','UserController');
    Route::apiResource('/categorias','CategoriaController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/secciones','SeccioneController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/clientes','ClienteController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/casillas','CasillaController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/alquileres','AlquilereController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/precios','PrecioController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/cajeros','CajeroController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/eventos','EventosController');  //editar agragar eliminar listar apiresource

});





Route::group(['prefix'=>'cliente'],function(){
    Route::post('/login2','ClienteController@login2');//solo para logear
    Route::apiResource('/casillas','CasillaController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/alquileres','AlquilereController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/llaves','LlavesController');  //editar agragar eliminar listar apiresource
    Route::get('/ver1/{seccionId}','CasillaController@obtenercasillas');//solo para logear
    Route::get('/ver3/{busquedaid}','CasillaController@busquedas');//solo para logear
    Route::get('/ver2/{seccionId}', 'CasillaController@obtenerInformacionAlquileres');
    Route::get('/fecha/{alquilerId}', 'AlquilereController@verificarFechaPorVencer');
    Route::get('/reportes/alquileres/{alquilere}', 'AlquilereController@pdf');
    Route::apiResource('/paquetes','PaquetesController');  //editar agragar eliminar listar apiresource


});





Route::group(['prefix'=>'cajero'],function(){
    Route::apiResource('/users','UserController');
    Route::apiResource('/categorias','CategoriaController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/secciones','SeccioneController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/clientes','ClienteController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/casillas','CasillaController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/alquileres','AlquilereController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/precios','PrecioController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/cajeros','CajeroController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/llaves','LlavesController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/reservas','ReservaController');  //editar agragar eliminar listar apiresource
    Route::apiResource('/paquetes','PaquetesController');  //editar agragar eliminar listar apiresource

    Route::get('/todas-las-casillas','CasillaController@obtenerTodasLasCasillas');

    Route::post('/reservar-casillas', 'AlquilereController@reservarCasillas');
    Route::post('update-all-to-ocupadas', 'AlquilereController@updateAllToOcupadas');
    Route::post('update-casillas-seleccionadas','AlquilereController@updateCasillasSeleccionadas');
    Route::post('/login3','CajeroController@login3');//solo para logear


    Route::get('/buscar-casilla/{nombre}', 'AlquilereController@buscarPorCasilla');




    Route::get('/ver3/{busquedaid}','CasillaController@busquedas');//solo para logear

    Route::get('/ver1/{seccionId}','CasillaController@obtenercasillas');//solo para logear
    Route::get('/ver2/{seccionId}', 'CasillaController@obtenerInformacionAlquileres');
    Route::get('/fecha/{alquilerId}', 'AlquilereController@verificarFechaPorVencer');
    Route::get('/reportes/alquileres/{alquilere}', 'AlquilereController@pdf');
    Route::post('/backup-laravel', 'CasillaController@backupLaravel');

// En routes/web.php o routes/api.php
Route::get('/ocupadas', 'AlquilereController@getCasillasOcupadas');
Route::get('/libres','AlquilereController@getCasillasLibres');
Route::get('/correspondencia', 'AlquilereController@getCasillasConCorrespondencia');
Route::get('/mantenimiento', 'AlquilereController@getCasillasMantenimiento');
Route::get('/vencidas', 'AlquilereController@getCasillasVencidas');
Route::get('/reservadas','AlquilereController@getCasillasReservadas');
Route::put('alquileres/{codigo}/eliminar-paquete-id', 'AlquilereController@eliminarPaqueteId');


    Route::get('/dashboard','DashboardController@patito');//solo para logear
    
});








Route::get('/', function () {
    return view('welcome');
  
});

