<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group( function () {
    // Rutas Depositos
    Route::apiResources([
        'deposito' => 'Api\DepositoController'
    ]);

    Route::put('/deposito/desactivar/{deposito}', 'Api\DepositoController@desactivar');
    Route::put('/deposito/activar/{deposito}', 'Api\DepositoController@activar');

    // Rutas Formas Pago
    Route::apiResources([
        'formapago' => 'Api\FormaPagoController'
    ]);

    Route::put('/formapago/desactivar/{formapago}', 'Api\FormaPagoController@desactivar');
    Route::put('/formapago/activar/{formapago}', 'Api\FormaPagoController@activar');

    // Rutas Formas Venta
    Route::apiResources([
        'formaventa' => 'Api\FormaVentaController'
    ]);

    Route::put('/formaventa/desactivar/{formaventa}', 'Api\FormaVentaController@desactivar');
    Route::put('/formaventa/activar/{formaventa}', 'Api\FormaVentaController@activar');

    // Rutas Productos
    Route::get('/producto/devuelveDatosProducto/{producto}', 'Api\ProductoController@devuelveDatosProducto');

    Route::apiResources([
        'producto' => 'Api\ProductoController'
    ]);

    Route::put('/producto/desactivar/{producto}', 'Api\ProductoController@desactivar');
    Route::put('/producto/activar/{producto}', 'Api\ProductoController@activar');

    // Rutas Clientes
    Route::get('/cliente/cargaTD', 'Api\ClienteController@cargaTD');
    Route::get('/cliente/devuelveDatosCliente/{cliente}', 'Api\ClienteController@devuelveDatosCliente');

    Route::apiResources([
        'cliente' => 'Api\ClienteController'
    ]);

    Route::put('/cliente/desactivar/{cliente}', 'Api\ClienteController@desactivar');
    Route::put('/cliente/activar/{cliente}', 'Api\ClienteController@activar');

    // Rutas Proveedores
    Route::get('/proveedor/cargaTD', 'Api\ProveedorController@cargaTD');
    Route::get('/proveedor/devuelveDatosProveedor/{proveedor}', 'Api\ProveedorController@devuelveDatosProveedor');

    Route::apiResources([
        'proveedor' => 'Api\ProveedorController'
    ]);

    Route::put('/proveedor/desactivar/{proveedor}', 'Api\ProveedorController@desactivar');
    Route::put('/proveedor/activar/{proveedor}', 'Api\ProveedorController@activar');

    //Rutas Notas Pedidos
    Route::get('/notaPedido/devuelveNotaPedido/{nota_pedido}', 'Api\NotaPedidoController@devuelveNotaPedido');
    Route::put('/notaPedido/anulaNotaPedido/{nota_pedido}', 'Api\NotaPedidoController@anulaNotaPedido');
    Route::put('/notaPedido/confirmaNotaPedido/{nota_pedido}', 'Api\NotaPedidoController@confirmaNotaPedido');

    Route::apiResources(['notaPedido' => 'Api\NotaPedidoController']);
});