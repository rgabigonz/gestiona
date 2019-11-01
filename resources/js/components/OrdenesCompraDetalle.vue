<template>
    <div class="container-fluid">
        <div class="row mt-5">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- Titulo row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fa fa-globe"></i> Agro Proyecciones SRL
                  </h4>
                  <div>Nota de Pedido N° {{ anio_id }} - {{ anio_actual }}</div>
                </div>
                <!-- /.col -->

              </div>
              <!-- /.row -->

              <br>

              <div v-if="errors.length" class="alert alert-danger" role="alert">
                <b>Se produjeron los siguientes errores:</b>
                <ul>
                    <li v-for="error in errors" :key="error.id">{{ error }}</li>
                </ul>
              </div>
              
              <br>              
              <!-- Datos NP row -->
              <div class="card">
                <div class="card-header border-light bg-secondary">Datos Nota de Pedido - Proveedor</div>
                <div class="card-body">
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <datepicker :bootstrap-styling="true" v-model="fecha_orden_compra" name="fecha_orden_compra" :language="es" 
                                    :format="formato_fecha_orden_compra" inputClass="form-control form-control-sm" placeholder="Fecha" 
                                    :disabled="modoEdicion ? true : false">
                                </datepicker>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input v-model="numero_negocio" type="text" name="numero_negocio" placeholder="Numero Negocio"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="tipo" :disabled="modoEdicion ? true : false">
                                    <option value="">Tipo...</option>
                                    <option value="PR">PROPIA</option>
                                    <option value="CL">CLIENTE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                </div>
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="codigo_deposito">
                                    <option value="">Deposito...</option>
                                    <option v-for="ldeposito in ldepositos" :key="ldeposito.id" :value="ldeposito.id">{{ ldeposito.descripcion }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="codigo_formapago">
                                    <option value="">Forma Pago...</option>
                                    <option v-for="lformaspago in lformaspagos" :key="lformaspago.id" :value="lformaspago.id">{{ lformaspago.descripcion }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="codigo_condicionpago">
                                    <option value="">Condicion Pago...</option>
                                    <option v-for="lcondicionespago in lcondicionespagos" :key="lcondicionespago.id" :value="lcondicionespago.id">{{ lcondicionespago.descripcion }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                </div>

                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input v-model="lugar_entrega" type="text" name="lugar_entrega" placeholder="Lugar Entrega"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                </div>
                </div>
              </div>

              <!-- Proveedor row -->
              <div class="card">
                <div class="card-header border-light bg-info">Datos Proveedor</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <select class="form-control" v-model="codigo_proveedor" @change="cargarProveedor(codigo_proveedor)">
                                        <option value=0>Proveedor...</option>
                                        <option v-for="lproveedor in lproveedores" :key="lproveedor.id" :value="lproveedor.id">{{ lproveedor.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-8 invoice-col">
                            <div class="form-group">
                                <input v-model="proveedor.direccion" type="text" name="direccion_proveedor" class="form-control form-control-sm" disabled>
                            </div>
                        </div>
                        <!-- /.col -->

                    </div>
                    <!-- /.row -->

                </div>
              </div>

              <!-- Cliente y Vendedores row -->
              <div v-if="tipo == 'CL'" class="card">
                <div class="card-header border-light bg-success">Datos Cliente - Distribuidores</div>
                <div class="card-body">
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <label class="control-label">Cliente</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="codigo_cliente" @change="cargarCliente(codigo_cliente)">
                                    <option value=0>Cliente...</option>
                                    <option v-for="lcliente in lclientes" :key="lcliente.id" :value="lcliente.id">{{ lcliente.nombre }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <label class="control-label">Comision de venta</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="codigo_vendedor_venta" @change="cargarVendedor(codigo_vendedor_venta)">
                                    <option value=0>x Venta...</option>
                                    <option v-for="lvendedor_venta in lvendedores_venta" :key="lvendedor_venta.id" :value="lvendedor_venta.id">{{ lvendedor_venta.nombre }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <div class="form-group">
                            <label class="control-label">Comision de gestion</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="codigo_vendedor_gestion">
                                    <option value=0>x Gestion...</option>
                                    <option v-for="lvendedor_gestion in lvendedores_gestion" :key="lvendedor_gestion.id" :value="lvendedor_gestion.id">{{ lvendedor_gestion.nombre }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                </div>
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <label class="control-label">CUIT</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input v-model="cliente.numero_documento" type="text" name="numero_documento" class="form-control form-control-sm" disabled>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <label class="control-label">CUIT</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input v-model="vendedor.numero_documento" type="text" name="numero_documento_cg" class="form-control form-control-sm" disabled>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->

                </div>
                </div>
              </div>
              <!-- /.row -->

              <!-- Tabla de productos row -->
              <div class="card">
                <div class="card-header border-light bg-dark">Productos</div>
                <div class="card-body">              
                    <div class="row invoice-info">
                        <div class="col-12 invoice-col">
                        <table class="table table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th style="width:30%">Producto</th>
                                    <th style="width:10%">Cantidad</th>
                                    <th style="width:10%">Precio</th>
                                    <th style="width:10%">Flete</th>
                                    <th style="width:10%">C. Venta</th>
                                    <th style="width:10%">C. Gestion</th>
                                    <th style="width:10%">P. Unitario</th>
                                    <th style="width:10%">Subtotal</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" v-model="codigo_producto" ref="codigo_producto" @change="cargarProducto(codigo_producto)">
                                                    <option value=0>Producto...</option>
                                                    <option v-for="lproducto in lproductos" :key="lproducto.id" :value="lproducto.id">{{ lproducto.nombre }}</option>
                                                </select>                                                    
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="descripcion_producto" type="text" name="descripcion_producto" class="form-control form-control-sm" disabled>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="cantidad_producto" type="number" name="cantidad_producto"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="precio_producto" type="number" name="precio_producto"
                                                    @keydown ="keyMonitor" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="flete_producto" type="number" name="flete_producto"
                                                    @keydown ="keyMonitor" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="comision_venta_producto" type="number" name="comision_venta_producto"
                                                    @keydown ="keyMonitor" class="form-control form-control-sm" :disabled="tipo != 'CL' ? true : false">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="comision_gestion_producto" type="number" name="comision_gestion_producto"
                                                    @keydown ="keyMonitor" class="form-control form-control-sm" :disabled="tipo != 'CL' ? true : false">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="unitario_producto" type="text" name="unitario_producto" 
                                                    class="form-control form-control-sm" disabled>
                                            </div>
                                        </div>
                                    </td>                        
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="subtotal_producto" type="text" name="subtotal_producto" 
                                                    class="form-control form-control-sm" disabled>
                                            </div>
                                        </div>
                                    </td>                        
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="subtotal_iva_producto" type="hidden" name="subtotal_iva_producto" 
                                                    class="form-control form-control-sm" disabled>
                                            </div>
                                        </div>
                                    </td>                        
                                    <!-- /.col -->

                                </tr>
                            </thead>
                            <tbody style="font-size: 12px;">
                                <tr class="item" v-for="(item, index) in items" :key="item.cod">
                                    <td><b>{{ item.descripcion }}</b> / {{ item.descripcion_larga}}</td>
                                    <td>{{ item.cantidad }}</td>
                                    <td>${{ item.precio }}</td>
                                    <td>${{ item.flete }}</td>
                                    <td>${{ item.comision_venta }}</td>
                                    <td>${{ item.comision_gestion }}</td>
                                    <td>${{ calculaPUnitario(item) | currency }}</td>
                                    <td>
                                        <b>${{ calculaSTotal(item) | currency }}</b>
                                    </td>
                                    <!-- <td>
                                        <b>${{ calculaIVA(item) | currency }}</b>
                                    </td> -->
                                    <td>
                                        <a href="#" @click="removerProducto(index)">
                                            <i class="fa fa-trash-alt red"></i>
                                        </a>                            
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Total sin IVA:</b></td>
                                    <td><b>${{ total_sinIVA | currency }}</b></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>IVA 10,5%:</b></td>
                                    <td><b>${{ total105 | currency }}</b></td>
                                </tr>    
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>IVA 21%:</b></td>
                                    <td><b>${{ total21 | currency }}</b></td>
                                </tr>                                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Total:</b></td>
                                    <td><b>${{ total_conIVA | currency }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <!-- /.col -->

                    </div>
                </div>
              </div>
              <!-- /.row -->

              <!-- Observaciones row -->
              <div class="row">
                  <div class="col col-md-12">
                      <div class="form-group">
                          <textarea v-model="observacion" type="text" name="descripcion" placeholder="Observacion"
                              class="form-control"></textarea>
                      </div>
                  </div>
              </div>
              <!-- /.row -->

            </div>
            <!-- /.invoice -->

          </div>
          <!-- /.col -->

        </div>
        <!-- /.row -->

        <!-- Botones row - this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaOrdenCompra()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <!-- <button v-if="modoEdicion && estado == 'PE'" type="button" class="btn btn-success float-right" @click="actualizaOrdenCompra()"> -->
                <button v-if="modoEdicion" type="button" class="btn btn-success float-right" @click="actualizaOrdenCompra()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link v-if="sBuscarOCD" :to="{ name: 'ordenescompra', params: { sBuscar: sBuscarOCD, sCriterio: sCriterioOCD }}" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fa fa-hand-point-left fa-fw"></i>Volver
                </router-link>                  
                <router-link v-else to="/ordenescompra" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fa fa-hand-point-left fa-fw"></i>Volver
                </router-link>                
            </div>
        </div>   
      </div>
</template>

<script>
    import Datepicker from "vuejs-datepicker/dist/vuejs-datepicker.esm.js";
    import {en, es} from 'vuejs-datepicker/dist/locale';

    export default {
        components: {
            Datepicker
        },        
        data() {
            return {
                // Paginacion
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                sCriterioNPD: '',
                sBuscarNPD: '',

                offset: 3,
                sCriterio: 'nombre',
                sBuscar: '',

                // Errores
                errors: [],
                
                // Otros
                fecha_orden_compra: new Date(),
                formato_fecha_orden_compra: "dd-MM-yyyy",
                es: es,

                // Objetos de lista de seleccion
                ldepositos: {},
                lformaspagos: {},
                lcondicionespagos: {},
                lclientes: {},
                lproveedores: {},
                lproductos: {},
                lvendedores_venta: {},
                lvendedores_gestion: {},
                codigo_proveedor: 0,
                
                // Variables contenedoras de datos
                modoEdicion: false,
                orenes_compra_id_edicion: 0,
                tipo: '',
                estado: '',
                codigo_deposito: '',
                codigo_formapago: '',
                codigo_condicionpago: '',
                cliente: {},
                codigo_cliente: 0,
                codigo_vendedor_venta: 0,
                codigo_vendedor_gestion: 0,
                anio_id: 0,
                anio_actual: 0,
                codigo_producto: 0,
                cantidad_producto: 0,
                nombre_producto: '',
                descripcion_producto: '',
                obs_producto: '',
                precio_producto: 0,
                iva_producto: 0,
                flete_producto: 0,
                comision_venta_producto: 0,
                comision_gestion_producto: 0,
                numero_negocio: '',
                lugar_entrega: '',
                observacion: '',
                total_iva_21: 0,
                total_iva_105: 0,

                // Objetos de datos
                proveedor: {},
                vendedor: {},
                items: [],
                producto: {},
                orden_compra: {},
                orden_compra_detalle: {}
            }
        },
        methods: {
            // Monitor de teclas
            keyMonitor(event) {
                let origenInput = event.target.name;
                let origenKey = event.key || String.fromCharCode(event.keyCode);

                switch(origenInput) {
                    case 'precio_producto':
                        switch(origenKey) {
                            case 'Tab':  
                                if (this.tipo != 'CL')  
                                    this.agregaProducto();
                                break;
                            default:
                                //code block
                        } 
                        break;
                    case 'comision_gestion_producto':
                        switch(origenKey) {
                            case 'Tab':  
                                if (this.tipo == 'CL')  
                                    this.agregaProducto();
                                break;
                            default:
                                //code block
                        } 
                        break;                        
                    default:
                        //code block
                } 
            },

            // Cargar detalles seleccionados
            cargarProveedor(cCod) {
                let me = this;                
                var url = 'api/proveedor/devuelveDatosProveedor/'+cCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.proveedor = response.datoProveedor;
                }).catch((error) => {
                    me.proveedor = {};
                    me.codigo_proveedor = '';

                    //Levo el foco al codigo de proveedor
                    this.$nextTick(() => {
                        this.$refs.codigo_proveedor.focus();
                    });
                });
            },cargarCliente(cCod) {
                let me = this;
                var url = 'api/cliente/devuelveDatosCliente/'+cCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.cliente = response.datoCliente;
                }).catch((error) => {
                    me.cliente = {};
                    me.codigo_cliente = '';

                    this.focusInput('codigo_cliente');
                });
            },cargarVendedor(vCod) {
                let me = this;
                var url = 'api/vendedor/devuelveDatosVendedor/'+vCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.vendedor = response.datoVendedor;
                }).catch((error) => {
                    me.vendedor = {};
                    me.codigo_vendedor_venta = '';

                    this.focusInput('codigo_vendedor_venta');
                });
            },
            cargarProducto(pCod) {
                let me = this;

                var url = 'api/producto/devuelveDatosProducto/'+pCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.producto = response.datoProducto;
                    me.nombre_producto = me.producto[0].nombre;
                    me.descripcion_producto = me.producto[0].descripcion;
                    me.iva_producto = me.producto[0].iva_producto;
                }).catch((error) => {
                    me.producto = {};
                    me.codigo_producto = 0;
                    me.nombre_producto = '';
                    me.descripcion_producto = '';
                    me.iva_producto = 0;

                    //Levo el foco al codigo de producto
                    this.$nextTick(() => {
                        this.$refs.codigo_producto.focus();
                    });
                });
            },

            // Cargas de datos de listas            
            cargaDepositos() {
                let me = this;                
                var url = 'api/deposito/cargaDepositos';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.ldepositos = response.ldepositos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            cargaFormasPago() {
                let me = this;                
                var url = 'api/formapago/cargaFormasPago';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lformaspagos = response.lformaspagos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            cargaCondicionesPago() {
                let me = this;                
                var url = 'api/condicionpago/cargaCP';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lcondicionespagos = response.condicionesPago;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            cargaClientes() {
                let me = this;                
                var url = 'api/cliente/cargaClientes';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lclientes = response.clientes;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            cargaVendedores() {
                let me = this;                
                var url = 'api/vendedor/cargaVendedores';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lvendedores_venta = response.vendedores;
                    me.lvendedores_gestion = response.vendedores;
                    me.codigo_vendedor_gestion = response.vendedor_gestion_defecto[0].id;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            cargaProveedores() {
                let me = this;                
                var url = 'api/proveedor/cargaProveedores';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lproveedores = response.proveedores;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            cargaProductos() {
                let me = this;                
                var url = 'api/producto/cargaProductos';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lproductos = response.productos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },

            // Operaciones con productos
            agregaProducto() {
                if (this.cantidad_producto > 0 && this.precio_producto > 0) {
                    if (this.existeProducto(parseInt(this.codigo_producto)) === false) {
                        this.items.push({ cod: parseInt(this.codigo_producto), 
                                        descripcion: this.nombre_producto, 
                                        descripcion_larga: this.descripcion_producto, 
                                        obs: this.obs_producto, 
                                        flete: this.flete_producto, 
                                        comision_venta: this.comision_venta_producto, 
                                        comision_gestion: this.comision_gestion_producto, 
                                        cantidad: this.cantidad_producto, 
                                        precio: this.precio_producto,
                                        alicuota_iva: this.iva_producto 
                        });
                    }
                }

                this.codigo_producto = 0;
                this.cantidad_producto = 0;
                this.nombre_producto = '';
                this.descripcion_producto = '';
                this.obs_producto = '';
                this.precio_producto = 0;
                this.iva_producto = 0;
                this.flete_producto = 0;
                this.comision_venta_producto = 0;
                this.comision_gestion_producto = 0;
            },
            removerProducto(index) {
                this.items.splice(index, 1);
            },
            existeProducto(pCod) {
                //Levo el foco al codigo de producto
                this.$nextTick(() => {
                    this.$refs.codigo_producto.focus();
                });

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].cod === pCod) {
                        this.items[i].cantidad = parseInt(this.items[i].cantidad) + parseInt(this.cantidad_producto);
                        return true;
                    }
                }

                return false;
            },

            // Operaciones con NP Proveedores (OC)
            validaNP() {
                var resultado = false;

                if (this.tipo != 'CL') {
                    if (this.codigo_proveedor && this.items.length) {
                        resultado = true;
                    }

                    this.errors = [];

                    if (this.codigo_proveedor == 0) {
                        this.errors.push('Debe ingresar un proveedor');
                    }
                    if (this.items.length == 0) {
                        this.errors.push('Debe ingresar al menos un producto');
                    }
                } else {
                    if (this.codigo_proveedor && this.codigo_cliente && 
                        this.codigo_vendedor_gestion && this.items.length) {
                        resultado = true;
                    }

                    this.errors = [];

                    if (this.codigo_proveedor == 0) {
                        this.errors.push('Debe ingresar un proveedor');
                    }
                    if (this.codigo_cliente == 0) {
                        this.errors.push('Debe ingresar un cliente');
                    }
                    /*if (this.codigo_vendedor_venta == 0) {
                        this.errors.push('Debe ingresar un distribuidor de comision de venta');
                    }*/
                    if (this.codigo_vendedor_gestion == 0) {
                        this.errors.push('Debe ingresar un distribuidor de comision de gestion');
                    }
                    if (this.items.length == 0) {
                        this.errors.push('Debe ingresar al menos un producto');
                    }
                }

                return resultado;
            },
            creaOrdenCompra() {
                this.$Progress.start();
                
                if (this.validaNP()) {
                    axios.post('api/ordenCompra', {
                        codigo_proveedor: this.codigo_proveedor, 
                        codigo_deposito: this.codigo_deposito, 
                        codigo_forma_pago: this.codigo_formapago, 
                        codigo_condicion_pago: this.codigo_condicionpago, 
                        fecha_orden_compra: this.fecha_orden_compra,
                        tipo: this.tipo,
                        total_orden: this.total_conIVA,
                        total_orden_siniva: this.total_sinIVA,
                        total_orden_21: this.total_iva_21,
                        total_orden_105: this.total_iva_105,
                        numero_negocio: this.numero_negocio,
                        lugar_entrega: this.lugar_entrega,
                        obs: this.observacion,
                        codigo_cliente: this.codigo_cliente, 
                        codigo_vendedor_venta: this.codigo_vendedor_venta, 
                        codigo_vendedor_gestion: this.codigo_vendedor_gestion, 
                        items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se genero la orden de compra correctamente!'
                        });

                        if(this.sBuscarOCD)
                            this.$router.push('/ordenescompra/'+this.sBuscarOCD+'/'+this.sCriterioOCD);
                        else
                            this.$router.push('/ordenescompra');

                        
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
                }
                else {
                    toast({
                        type: 'error',
                        title: 'Verificar errores'
                    });
                }

                this.$Progress.finish();
            },
            actualizaOrdenCompra() {
                this.$Progress.start();
                
                axios.put('api/ordenCompra/'+this.orenes_compra_id_edicion, {
                    codigo_proveedor: this.codigo_proveedor, 
                    codigo_deposito: this.codigo_deposito, 
                    codigo_forma_pago: this.codigo_formapago, 
                    codigo_condicion_pago: this.codigo_condicionpago, 
                    fecha_orden_compra: this.fecha_orden_compra,
                    tipo: this.tipo,
                    total_orden: this.total_conIVA,
                    total_orden_siniva: this.total_sinIVA,
                    total_orden_21: this.total_iva_21,
                    total_orden_105: this.total_iva_105,
                    numero_negocio: this.numero_negocio,
                    lugar_entrega: this.lugar_entrega,
                    obs: this.observacion,
                    codigo_cliente: this.codigo_cliente, 
                    codigo_vendedor_venta: this.codigo_vendedor_venta, 
                    codigo_vendedor_gestion: this.codigo_vendedor_gestion, 
                    items: this.items})
                .then(() => {
                    Fire.$emit('AfterAction');
                    toast({
                        type: 'success',
                        title: 'Se actualizo la orden de compra correctamente!'
                    });

                    if(this.sBuscarOCD)
                        this.$router.push('/ordenescompra/'+this.sBuscarOCD+'/'+this.sCriterioOCD);
                    else
                        this.$router.push('/ordenescompra');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            cargarOrdenCompra(ocCod) {
                let me = this;                
                var url = 'api/ordenCompra/devuelveOrdenCompra/'+ocCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.orden_compra = response.datoOrdenCompra;
                    me.orden_compra_detalle = response.datoOrdenCompraD;

                    //Datos Orden Compra
                    me.anio_id = this.orden_compra.anio_id;
                    me.anio_actual = this.orden_compra.anio_actual;

                    me.tipo = me.orden_compra.tipo;
                    me.estado = me.orden_compra.estado;
                    me.codigo_proveedor = me.orden_compra.proveedor_id;
                    me.fecha_orden_compra = new Date(me.orden_compra.fecha);
                    me.fecha_orden_compra = me.fecha_orden_compra.setDate(me.fecha_orden_compra.getDate() + 1);
                    me.numero_negocio = me.orden_compra.numero_negocio;
                    me.lugar_entrega = me.orden_compra.lugar_entrega;
                    me.observacion = me.orden_compra.obs;
                    
                    // Carcar valores nulos
                    if (!me.orden_compra.condicionpago_id) 
                        me.codigo_condicionpago = '';
                    else
                        me.codigo_condicionpago = me.orden_compra.condicionpago_id;

                    if (!me.orden_compra.deposito_id) 
                        me.codigo_deposito = '';
                    else
                        me.codigo_deposito = me.orden_compra.deposito_id;

                    if (!me.orden_compra.formapago_id) 
                        me.codigo_formapago = '';
                    else
                        me.codigo_formapago = me.orden_compra.formapago_id;

                    me.codigo_cliente = me.orden_compra.cliente_id;

                    if (!me.orden_compra.vendedor_venta_id) {
                        me.codigo_vendedor_venta = 0;
                    }
                    else {
                        me.codigo_vendedor_venta = me.orden_compra.vendedor_venta_id;
                        me.cargarVendedor(me.codigo_vendedor_venta);
                    }

                    me.codigo_vendedor_gestion = me.orden_compra.vendedor_gestion_id;
                    
                    me.cargarCliente(me.codigo_cliente);
                    me.cargarProveedor(me.codigo_proveedor);

                    for (var i = 0; i < me.orden_compra_detalle.length; i++) {
                        me.items.push({ cod: me.orden_compra_detalle[i].producto_id, 
                                          descripcion: me.orden_compra_detalle[i].nombre_producto, 
                                          descripcion_larga: me.orden_compra_detalle[i].descripcion_producto, 
                                          obs: me.orden_compra_detalle[i].obs, 
                                          cantidad: me.orden_compra_detalle[i].cantidad, 
                                          precio: me.orden_compra_detalle[i].precio, 
                                          flete: me.orden_compra_detalle[i].flete, 
                                          comision_venta: me.orden_compra_detalle[i].comision_venta, 
                                          comision_gestion: me.orden_compra_detalle[i].comision_gestion,
                                          alicuota_iva: me.orden_compra_detalle[i].alicuota_iva
                        });
                    }

                }).catch((error) => {
                    me.orden_compra = {};
                    me.orden_compra_detalle = {};
                });
            },
            calculaPUnitario(item) {
                return (parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta) + parseFloat(item.comision_gestion));
            },
            calculaSTotal(item) {
                return ((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta) + parseFloat(item.comision_gestion)) * item.cantidad);
            },
            calculaIVA(item) {
                if (item.iva == 21) 
                    return (((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta) + 
                              parseFloat(item.comision_gestion)) * item.cantidad) * 1.21) - 
                            ((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta) + 
                              parseFloat(item.comision_gestion)) * item.cantidad);
                else if (item.iva == 10.5)
                    return (((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta) + 
                              parseFloat(item.comision_gestion)) * item.cantidad) * 1.105) - 
                            ((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta) + 
                              parseFloat(item.comision_gestion)) * item.cantidad);
            }
        },
        computed: {
            subtotal_iva_producto() {
                var lTotal_Adicionales = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto) + parseFloat(this.comision_gestion_producto);
                var lTotal = parseFloat(this.precio_producto) + parseFloat(lTotal_Adicionales);

                if (this.iva_producto == 21)
                    return ((this.cantidad_producto * lTotal * 1.21) - (this.cantidad_producto * lTotal));
                else
                    return ((this.cantidad_producto * lTotal * 1.105) - (this.cantidad_producto * lTotal));
            },
            subtotal_producto() {
                var lTotal_Adicionales = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto) + parseFloat(this.comision_gestion_producto);
                var lTotal = parseFloat(this.precio_producto) + parseFloat(lTotal_Adicionales);
                return (this.cantidad_producto * lTotal);
            },
            unitario_producto() {
                var lTotal = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto) + parseFloat(this.comision_gestion_producto);
                return (parseFloat(this.precio_producto) + parseFloat(lTotal));
            },
            total_sinIVA() {
                return this.items.reduce(
                    (acc, item) => acc + ((parseFloat(item.flete) + parseFloat(item.comision_venta) + parseFloat(item.comision_gestion) + parseFloat(item.precio)) * parseFloat(item.cantidad)),
                    0
                );
            },
            total105() {
                var lTotal_Adicionales = 0;
                var lTotal = 0;
                var lCantidad = 0;

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].alicuota_iva == '10.50') {
                        lTotal_Adicionales = (parseFloat(this.items[i].flete) + parseFloat(this.items[i].comision_venta) + 
                                             parseFloat(this.items[i].comision_gestion));

                        lTotal += (parseFloat(this.items[i].precio) + lTotal_Adicionales) * parseFloat(this.items[i].cantidad);
                    }
                }  

                this.total_iva_105 = parseFloat((lTotal * 1.105) - (lTotal));
                return parseFloat((lTotal * 1.105) - (lTotal));
            },
            total21() {
                var lTotal_Adicionales = 0;
                var lTotal = 0;

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].alicuota_iva == '21.00') {
                        lTotal_Adicionales = (parseFloat(this.items[i].flete) + parseFloat(this.items[i].comision_venta) + 
                                             parseFloat(this.items[i].comision_gestion));

                        lTotal += (parseFloat(this.items[i].precio) + lTotal_Adicionales) * parseFloat(this.items[i].cantidad);
                    }
                }

                this.total_iva_21 = parseFloat((lTotal * 1.21) - (lTotal));
                return parseFloat((lTotal * 1.21) - (lTotal));
            },
            total_conIVA() {
                var lTotal_Adicionales = 0;
                var lTotal = 0;

                for (var i = 0; i < this.items.length; i++) {
                    lTotal_Adicionales = (parseFloat(this.items[i].flete) + parseFloat(this.items[i].comision_venta) + 
                                            parseFloat(this.items[i].comision_gestion));

                    lTotal += (parseFloat(this.items[i].precio) + lTotal_Adicionales) * parseFloat(this.items[i].cantidad);
                }  
                
                return parseFloat(this.total_iva_21 + this.total_iva_105 + lTotal);
            }
        },
        created() {
            this.orenes_compra_id_edicion = this.$route.params.ordenescompraId;

            if (this.$route.params.sBuscarOCD) {
                this.sBuscarOCD = this.$route.params.sBuscarOCD
                this.sCriterioOCD = this.$route.params.sCriterioOCD
            }

            this.cargaDepositos();
            this.cargaFormasPago();
            this.cargaCondicionesPago();
            this.cargaClientes();
            this.cargaVendedores();
            this.cargaProveedores();
            this.cargaProductos();

            if(this.orenes_compra_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarOrdenCompra(this.orenes_compra_id_edicion);
            }
        }
    }
</script>