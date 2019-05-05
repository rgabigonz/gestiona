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
                                <select class="form-control" v-model="codigo_cliente">
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
                                <select class="form-control" v-model="codigo_vendedor_venta">
                                    <option value=0>x Venta...</option>
                                    <option v-for="lvendedor_gestion in lvendedores_gestion" :key="lvendedor_gestion.id" :value="lvendedor_gestion.id">{{ lvendedor_gestion.nombre }}</option>
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
                                    <th style="width:12%">Importe</th>
                                    <th style="width:12%">Flete</th>
                                    <th style="width:12%">C. Venta</th>
                                    <th style="width:12%">C. Gestion</th>
                                    <th style="width:12%">Subtotal</th>
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
                                                    @keydown ="keyMonitor" class="form-control form-control-sm" :disabled="tipo != 'CL' ? true : false">
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
                                                <input v-model="subtotal_producto" type="text" name="subtotal_producto" 
                                                    class="form-control form-control-sm" disabled>
                                            </div>
                                        </div>
                                    </td>                        
                                    <!-- /.col -->

                                </tr>                            
                            </thead>
                            <tbody>
                                <tr class="item" v-for="(item, index) in items" :key="item.cod">
                                    <td>{{ item.descripcion}}</td>
                                    <td>{{ item.cantidad }}</td>
                                    <td>${{ item.precio }}</td>
                                    <td>${{ item.flete }}</td>
                                    <td>${{ item.comision_venta }}</td>
                                    <td>${{ item.comision_gestion }}</td>
                                    <td>${{ ((item.precio * item.cantidad) + parseFloat(item.flete) + parseFloat(item.comision_venta) + parseFloat(item.comision_gestion)) | currency }}</td>
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
                                    <td>Total:</td>
                                    <td>${{ total | currency }}</td>
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
                <button v-if="modoEdicion && estado == 'PE'" type="button" class="btn btn-success float-right" @click="actualizaOrdenCompra()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link to="/ordenescompra" class="btn btn-primary float-right" style="margin-right: 5px;">
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
                offset: 3,
                sCriterio: 'nombre',
                sBuscar: '',

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
                codigo_cliente: 0,
                codigo_vendedor_venta: 0,
                codigo_vendedor_gestion: 0,
                anio_id: 0,
                anio_actual: 0,
                codigo_producto: 0,
                cantidad_producto: 0,
                nombre_producto: '',
                obs_producto: '',
                precio_producto: 0,
                flete_producto: 0,
                comision_venta_producto: 0,
                comision_gestion_producto: 0,
                numero_negocio: '',
                observacion: '',

                // Objetos de datos
                proveedor: {},
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
            },
            cargarProducto(pCod) {
                let me = this;

                var url = 'api/producto/devuelveDatosProducto/'+pCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.producto = response.datoProducto;
                    me.nombre_producto = me.producto.nombre;
                }).catch((error) => {
                    me.producto = {};
                    me.codigo_producto = 0;
                    me.nombre_producto = '';

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
                                        obs: this.obs_producto, 
                                        flete: this.flete_producto, 
                                        comision_venta: this.comision_venta_producto, 
                                        comision_gestion: this.comision_gestion_producto, 
                                        cantidad: this.cantidad_producto, 
                                        precio: this.precio_producto 
                        });
                    }
                }

                this.codigo_producto = 0;
                this.cantidad_producto = 0;
                this.nombre_producto = '';
                this.obs_producto = '';
                this.precio_producto = 0;
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
                var resultado = true;

                if (this.tipo != 'CL') {
                    if (this.codigo_proveedor == 0 || this.items.length == 0) {
                        resultado = false;
                    }
                }
                else {
                    if (this.codigo_proveedor == 0 || this.items.length == 0 || 
                        this.codigo_cliente == 0 || this.codigo_vendedor_venta == 0 || this.codigo_vendedor_gestion == 0) {
                        resultado = false;
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
                        total_orden: this.total,
                        numero_negocio: this.numero_negocio,
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
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
                }
                else {
                    if (this.tipo != 'CL') 
                        swal('Error!', 'Debe ingresar un proveedor y al menos un producto', 'error');
                    else  
                        swal('Error!', 'Debe ingresar un proveedor, al menos un producto, el cliente y los vendedores', 'error');                    
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
                    total_orden: this.total,
                    numero_negocio: this.numero_negocio,
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
                    me.codigo_vendedor_venta = me.orden_compra.vendedor_venta_id;
                    me.codigo_vendedor_gestion = me.orden_compra.vendedor_gestion_id;

                    me.cargarProveedor(me.codigo_proveedor);

                    for (var i = 0; i < me.orden_compra_detalle.length; i++) {
                        me.items.push({ cod: me.orden_compra_detalle[i].producto_id, 
                                          descripcion: me.orden_compra_detalle[i].nombre_producto, 
                                          obs: me.orden_compra_detalle[i].obs, 
                                          cantidad: me.orden_compra_detalle[i].cantidad, 
                                          precio: me.orden_compra_detalle[i].precio, 
                                          flete: me.orden_compra_detalle[i].flete, 
                                          comision_venta: me.orden_compra_detalle[i].comision_venta, 
                                          comision_gestion: me.orden_compra_detalle[i].comision_gestion
                        });
                    }

                }).catch((error) => {
                    me.orden_compra = {};
                    me.orden_compra_detalle = {};
                });
            }
        },
        computed: {
            subtotal_producto() {
                var lTotal = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto) + parseFloat(this.comision_gestion_producto);
                return ((this.cantidad_producto * this.precio_producto) + parseFloat(lTotal));
            },
            total() {
                return this.items.reduce(
                    (acc, item) => acc + (parseFloat(item.flete) + parseFloat(item.comision_venta) + parseFloat(item.comision_gestion) + (item.precio * item.cantidad)),
                    0
                );
            }            
        },
        created() {
            this.cargaDepositos();
            this.cargaFormasPago();
            this.cargaCondicionesPago();
            this.cargaClientes();
            this.cargaVendedores();
            this.cargaProveedores();
            this.cargaProductos();

            this.orenes_compra_id_edicion = this.$route.params.ordenescompraId;

            if(this.orenes_compra_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarOrdenCompra(this.orenes_compra_id_edicion);
            }
        }
    }
</script>