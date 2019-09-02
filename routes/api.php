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
    // Ruta Dashboard
    Route::get('/dashboard', 'Api\DashboardController');
    
    // Rutas Tipos Documentos
    Route::get('/tipodocumento/cargaTD', 'Api\TipoDocumentoController@cargaTD');

    // Rutas Tipos Productos
    Route::get('/tipoproducto/cargaTP', 'Api\TipoProductoController@cargaTP');

    // Rutas Condiciones Pago
    Route::get('/condicionpago/cargaCP', 'Api\CondicionPagoController@cargaCP');

    // Rutas Depositos
    Route::get('/deposito/cargaDepositos', 'Api\DepositoController@cargaDepositos');

    Route::apiResources([
        'deposito' => 'Api\DepositoController'
    ]);

    Route::put('/deposito/desactivar/{deposito}', 'Api\DepositoController@desactivar');
    Route::put('/deposito/activar/{deposito}', 'Api\DepositoController@activar');

    // Rutas Bancos
    Route::get('/banco/cargaBancos', 'Api\BancoController@cargaBancos');

    Route::apiResources([
        'banco' => 'Api\BancoController'
    ]);

    Route::put('/banco/desactivar/{banco}', 'Api\BancoController@desactivar');
    Route::put('/banco/activar/{banco}', 'Api\BancoController@activar');

    // Rutas Vendedores
    Route::get('/vendedor/cargaVendedores', 'Api\VendedorController@cargaVendedores');
    Route::get('/vendedor/devuelveDatosVendedor/{vendedor}', 'Api\VendedorController@devuelveDatosVendedor');

    Route::apiResources([
        'vendedor' => 'Api\VendedorController'
    ]);

    Route::put('/vendedor/desactivar/{vendedor}', 'Api\VendedorController@desactivar');
    Route::put('/vendedor/activar/{vendedor}', 'Api\VendedorController@activar');

    // Rutas Formas Pago
    Route::get('/formapago/cargaFormasPago', 'Api\FormaPagoController@cargaFormasPago');

    Route::apiResources([
        'formapago' => 'Api\FormaPagoController'
    ]);

    Route::put('/formapago/desactivar/{formapago}', 'Api\FormaPagoController@desactivar');
    Route::put('/formapago/activar/{formapago}', 'Api\FormaPagoController@activar');

    // Rutas Condiciones Pago
    Route::get('/condicionpago/cargaCondicionesPago', 'Api\CondicionPagoController@cargaCondicionesPago');

    Route::apiResources([
        'condicionpago' => 'Api\CondicionPagoController'
    ]);

    Route::put('/condicionpago/desactivar/{condicionpago}', 'Api\CondicionPagoController@desactivar');
    Route::put('/condicionpago/activar/{condicionpago}', 'Api\CondicionPagoController@activar');

    // Rutas Formas Venta
    Route::apiResources([
        'formaventa' => 'Api\FormaVentaController'
    ]);

    Route::put('/formaventa/desactivar/{formaventa}', 'Api\FormaVentaController@desactivar');
    Route::put('/formaventa/activar/{formaventa}', 'Api\FormaVentaController@activar');

    // Rutas Productos
    Route::get('/producto/cargaProductos', 'Api\ProductoController@cargaProductos');
    Route::get('/producto/devuelveDatosProducto/{producto}', 'Api\ProductoController@devuelveDatosProducto');

    Route::apiResources([
        'producto' => 'Api\ProductoController'
    ]);

    // Rutas Conceptos
    Route::get('/concepto/cargaConceptosND', 'Api\ConceptoController@cargaConceptosND');
    Route::get('/concepto/cargaConceptosNC', 'Api\ConceptoController@cargaConceptosNC');
    Route::get('/concepto/devuelveDatosConcepto/{concepto}', 'Api\ConceptoController@devuelveDatosConcepto');

    Route::apiResources([
        'concepto' => 'Api\ConceptoController'
    ]);

    Route::put('/concepto/desactivar/{concepto}', 'Api\ConceptoController@desactivar');
    Route::put('/concepto/activar/{concepto}', 'Api\ConceptoController@activar');

    // Rutas Clientes
    Route::get('/cliente/cargaClientes', 'Api\ClienteController@cargaClientes');
    Route::get('/cliente/devuelveDatosCliente/{cliente}', 'Api\ClienteController@devuelveDatosCliente');

    Route::apiResources([
        'cliente' => 'Api\ClienteController'
    ]);

    Route::put('/cliente/desactivar/{cliente}', 'Api\ClienteController@desactivar');
    Route::put('/cliente/activar/{cliente}', 'Api\ClienteController@activar');

    // Rutas Proveedores
    Route::get('/proveedor/cargaProveedores', 'Api\ProveedorController@cargaProveedores');
    Route::get('/proveedor/devuelveDatosProveedor/{proveedor}', 'Api\ProveedorController@devuelveDatosProveedor');

    Route::apiResources([
        'proveedor' => 'Api\ProveedorController'
    ]);

    Route::put('/proveedor/desactivar/{proveedor}', 'Api\ProveedorController@desactivar');
    Route::put('/proveedor/activar/{proveedor}', 'Api\ProveedorController@activar');

    // Rutas Proveedores - Simple
    Route::get('/proveedorsimple/cargaProveedores', 'Api\ProveedorSimpleController@cargaProveedores');
    Route::get('/proveedorsimple/devuelveDatosProveedor/{proveedor}', 'Api\ProveedorSimpleController@devuelveDatosProveedor');

    Route::apiResources([
        'proveedorsimple' => 'Api\ProveedorSimpleController'
    ]);

    Route::put('/proveedorsimple/desactivar/{proveedor}', 'Api\ProveedorSimpleController@desactivar');
    Route::put('/proveedorsimple/activar/{proveedor}', 'Api\ProveedorSimpleController@activar');

    //Rutas Notas Pedidos
    Route::get('/notaPedido/NotaPedidoPDF/{nota_pedido}','Api\NotaPedidoController@NotaPedidoPDF');
    Route::get('/notaPedido/devuelveNotaPedido/{nota_pedido}', 'Api\NotaPedidoController@devuelveNotaPedido');
    Route::put('/notaPedido/anulaNotaPedido/{nota_pedido}', 'Api\NotaPedidoController@anulaNotaPedido');
    Route::put('/notaPedido/confirmaNotaPedido/{nota_pedido}', 'Api\NotaPedidoController@confirmaNotaPedido');

    Route::apiResources([
        'notaPedido' => 'Api\NotaPedidoController'
    ]);

    //Rutas Ordenes Compra
    Route::get('/ordenCompra/NotaPedidoProveedorPDF/{orden_compra}','Api\OrdenCompraController@NotaPedidoProveedorPDF');
    Route::get('/ordenCompra/devuelveOrdenCompra/{orden_compra}', 'Api\OrdenCompraController@devuelveOrdenCompra');
    Route::put('/ordenCompra/anulaOrdenCompra/{orden_compra}', 'Api\OrdenCompraController@anulaOrdenCompra');
    Route::put('/ordenCompra/confirmaOrdenCompra/{orden_compra}', 'Api\OrdenCompraController@confirmaOrdenCompra');

    Route::apiResources([
        'ordenCompra' => 'Api\OrdenCompraController'
    ]);    

    //Rutas Recibos
    //Route::get('/recibo/ReciboPDF/{recibo}','Api\ReciboController@ReciboPDF');
    Route::get('/recibo/devuelveRecibo/{recibo}', 'Api\ReciboController@devuelveRecibo');
    Route::put('/recibo/anulaRecibo/{recibo}', 'Api\ReciboController@anulaRecibo');
    Route::put('/recibo/confirmaRecibo/{recibo}', 'Api\ReciboController@confirmaRecibo');

    Route::apiResources([
        'recibo' => 'Api\ReciboController'
    ]);

    //Rutas Notas Debito
    Route::get('/notadebito/devuelveNotaDebito/{notadebito}', 'Api\NotaDebitoController@devuelveNotaDebito');
    Route::put('/notadebito/anulaNotaDebito/{notadebito}', 'Api\NotaDebitoController@anulaNotaDebito');
    Route::put('/notadebito/confirmaNotaDebito/{notadebito}', 'Api\NotaDebitoController@confirmaNotaDebito');

    Route::apiResources([
        'notadebito' => 'Api\NotaDebitoController'
    ]);

    //Rutas Notas Credito
    Route::get('/notacredito/devuelveNotaCredito/{notacredito}', 'Api\NotaCreditoController@devuelveNotaCredito');
    Route::put('/notacredito/anulaNotaCredito/{notacredito}', 'Api\NotaCreditoController@anulaNotaCredito');
    Route::put('/notacredito/confirmaNotaCredito/{notacredito}', 'Api\NotaCreditoController@confirmaNotaCredito');

    Route::apiResources([
        'notacredito' => 'Api\NotaCreditoController'
    ]);

    //Rutas Consultas cheques
    Route::apiResources([
        'cheque' => 'Api\ConsultaChequesController'
    ]);    

    Route::put('/cheque/cobrarCheque/{item}', 'Api\ConsultaChequesController@cobrarCheque');
    Route::put('/cheque/dolarCheque/{item}/dolar/{dolar}', 'Api\ConsultaChequesController@dolarCheque');

    //Rutas Cotizaciones
    Route::get('/cotizacion/devuelveCotizacion/','Api\CotizacionController@devuelveCotizacion');

    //Rutas Cta Cte
    Route::get('/ctactecliente/devuelveCtaCte/','Api\CtaCteController@devuelveCtaCte');

    //Rutas Stock
    Route::get('/stockproducto/devuelveStock/','Api\StockProductoController@devuelveStock');    
});