<template>
    <div class="container-fluid">
        <div class="row mt-5">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fa fa-globe"></i> Empresa VENTAS, Inc.
                  </h4>
                  <div>Orden de compra {{ orenes_compra_id_edicion }}</div>
                </div>
                <!-- /.col -->
              </div>

              <br>
              <!-- info row -->

              <div class="row invoice-info">
                <div class="col-sm-2 invoice-col">
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

                <div class="col-sm-3 invoice-col">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <select class="form-control" v-model="tipo">
                                <option value="SN">Tipo...</option>
                                <option value="PR">Propia</option>
                                <option value="CL">Cliente</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.col -->

                <div class="col-sm-3 invoice-col">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <select class="form-control" v-model="codigo_deposito">
                                <option value=0>Deposito...</option>
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
                                <option value=0>Forma Pago...</option>
                                <option v-for="lformaspago in lformaspagos" :key="lformaspago.id" :value="lformaspago.id">{{ lformaspago.descripcion }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.col -->

              </div>
              <!-- info row -->

              <div class="row invoice-info">
                <div class="col-sm-2 invoice-col">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <input v-model="codigo_proveedor" type="number" name="codigo_proveedor" ref="codigo_proveedor" placeholder="Proveedor (F2)"
                                @keydown ="keyMonitor" class="form-control form-control-sm" :disabled="modoEdicion ? true : false">
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">
                    <div class="form-group">
                        <input v-model="proveedor.nombre" type="text" name="nombre_proveedor" class="form-control form-control-sm" disabled>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <div class="form-group">
                        <input v-model="proveedor.correo_electronico" type="text" name="correo_proveedor" class="form-control form-control-sm" disabled>
                    </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-2 invoice-col">
                </div>
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">
                    <div class="form-group">
                        <input v-model="proveedor.direccion" type="text" name="direccion_proveedor" class="form-control form-control-sm" disabled>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <div class="form-group">
                        <input v-model="proveedor.telefono" type="text" name="telefono_proveedor" class="form-control form-control-sm" disabled>
                    </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td style="width: 15%" class="col-sm-2 invoice-col">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <input v-model="codigo_producto" type="number" name="codigo_producto" ref="codigo_producto" placeholder="Producto (F2)"
                                            @keydown ="keyMonitor" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </td>
                            <!-- /.col -->
                            <td style="width: 55%" class="col-sm-6 invoice-col">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <input v-model="nombre_producto" type="text" name="nombre_producto"
                                               class="form-control form-control-sm" disabled>
                                    </div>
                                </div>
                            </td>
                            <!-- /.col -->                            
                            <td style="width: 10%" class="col-sm-1 invoice-col">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <input v-model="cantidad_producto" type="number" name="cantidad_producto"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                            </td>
                            <!-- /.col -->
                            <td style="width: 10%" class="col-sm-1 invoice-col">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <input v-model="precio_producto" type="number" name="precio_producto"
                                               @keydown ="keyMonitor" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </td>
                            <!-- /.col -->
                            <td style="width: 10%" class="col-sm-1 invoice-col">
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
                            <td>{{ item.cod}}</td>
                            <td>{{ item.descripcion}}</td>
                            <td>{{ item.cantidad }}</td>
                            <td>${{ item.precio }}</td>
                            <td>${{ item.precio * item.cantidad | currency }}</td>
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
              <!-- /.row -->
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaOrdenCompra()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <button v-if="modoEdicion" type="button" class="btn btn-success float-right" @click="actualizaOrdenCompra()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link to="/ordenescompra" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fa fa-hand-point-left fa-fw"></i>Volver
                </router-link>                
            </div>
        </div>   

        <!-- Modal Seleccion de Proveedores-->
        <div class="modal fade" id="ventanaLProveedores" tabindex="-1" role="dialog" aria-labelledby="ventanaLProveedoresLabel" aria-hidden="true">
            <div style="min-width: 45%" class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ventanaLProveedoresLabel">Lista de Proveedores</h5>
                        <button type="button" class="close" @click="cerrarLProveedores()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-4">
                                <select class="form-control" v-model="sCriterio">
                                    <option value="nombre">Nombre</option>
                                </select>
                            </div>
                            <div class="col col-md-8">
                                <input v-model="sBuscar" @keyup.enter="cargarProveedores(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                            </div>
                        </div>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 8%">#</th>
                                    <th style="width: 82%">Nombre</th>
                                    <th style="width: 10%"></th>
                                </tr>
                                <tr v-for="proveedor in proveedores" :key="proveedor.id">
                                    <td>{{ proveedor.id }}</td>
                                    <td>{{ proveedor.nombre }}</td>
                                    <td>
                                        <a href="#" @click="seleccionaProveedor(proveedor.id)">
                                            <i class="fas fa-check-square green"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cerrarLProveedores()">Cerrar</button>
                    </div> 
                </div>
            </div>
        </div>
        <!-- Modal Seleccion de Proveedores-->

        <!-- Modal Seleccion de Productos-->
        <div class="modal fade" id="ventanaLProductos" tabindex="-1" role="dialog" aria-labelledby="ventanaLProductosLabel" aria-hidden="true">
            <div style="min-width: 45%" class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ventanaLProductosLabel">Lista de Productos</h5>
                        <button type="button" class="close" @click="cerrarLProductos()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-4">
                                <select class="form-control" v-model="sCriterio">
                                    <option value="nombre">Nombre</option>
                                </select>
                            </div>
                            <div class="col col-md-8">
                                <input v-model="sBuscar" @keyup.enter="cargarProductos(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                            </div>
                        </div>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 8%">#</th>
                                    <th style="width: 82%">Nombre</th>
                                    <th style="width: 10%"></th>
                                </tr>
                                <tr v-for="producto in productos" :key="producto.id">
                                    <td>{{ producto.id }}</td>
                                    <td>{{ producto.nombre }}</td>
                                    <td>
                                        <a href="#" @click="seleccionaProducto(producto.id)">
                                            <i class="fas fa-check-square green"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cerrarLProductos()">Cerrar</button>
                    </div> 
                </div>
            </div>
        </div>
        <!-- Modal Seleccion de Productos-->

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
                //Lista de Seleccion proveedores y productos
                proveedores: {},
                productos: {},
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
                //Lista de Seleccion proveedores y productos

                modoEdicion: false,
                orenes_compra_id_edicion: 0,
                fecha_orden_compra: new Date(),
                formato_fecha_orden_compra: "dd-MM-yyyy",
                es: es,
                proveedor: {},
                ldepositos: {},
                lformaspagos: {},
                codigo_proveedor: '',
                tipo: 'SN',
                codigo_deposito: 0,
                codigo_formapago: 0,
                items: [],
                producto: {},
                codigo_producto: '',
                cantidad_producto: 0,
                nombre_producto: '',
                precio_producto: 0,
                orden_compra: {},
                orden_compra_detalle: {}
            }
        },
        methods: {
            keyMonitor(event) {
                let origenInput = event.target.name;
                let origenKey = event.key || String.fromCharCode(event.keyCode);

                switch(origenInput) {
                    case 'codigo_proveedor':
                        switch(origenKey) {
                            case 'Enter':
                            case 'F2':
                                this.mostrarLProveedores();
                                break;
                            case 'Tab':  
                                this.cargarProveedor(this.codigo_proveedor);
                                break;
                            default:
                                //code block
                        } 
                        break;
                    case 'codigo_producto':
                        switch(origenKey) {
                            case 'Enter':
                            case 'F2':
                                this.mostrarLProductos();
                                break;
                            case 'Tab':    
                                this.cargarProducto(this.codigo_producto);
                                break;
                            default:
                                //code block
                        } 
                        break;
                    case 'precio_producto':
                        switch(origenKey) {
                            //case 'Enter':
                            case 'Tab':    
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

            //INICIO Lista de Seleccion proveedores
            mostrarLProveedores() {
                this.cargarProveedores(1, this.sBuscar, this.sCriterio);
                $('#ventanaLProveedores').modal('show');
            },
            cerrarLProveedores() {
                $('#ventanaLProveedores').modal('hide');
            },
            cargarProveedores(page, buscar, criterio) {
                let me = this;                
                var url = 'api/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.proveedores = response.proveedores.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            seleccionaProveedor(proveedor) {
                this.codigo_proveedor = proveedor;
                this.cargarProveedor(proveedor);
                this.cerrarLProveedores();
            },
            //FIN Lista de Seleccion proveedores

            //INICIO Lista de Seleccion productos
            mostrarLProductos() {
                this.cargarProductos(1, this.sBuscar, this.sCriterio);
                $('#ventanaLProductos').modal('show');
            },
            cerrarLProductos() {
                $('#ventanaLProductos').modal('hide');
            },
            cargarProductos(page, buscar, criterio) {
                let me = this;                
                var url = 'api/producto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.productos = response.productos.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            seleccionaProducto(producto) {
                this.codigo_producto = producto;
                this.cargarProducto(producto);
                this.cerrarLProductos();
            },
            //FIN Lista de Seleccion productos

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
                    me.codigo_producto = '';
                    me.nombre_producto = '';

                    //Levo el foco al codigo de producto
                    this.$nextTick(() => {
                        this.$refs.codigo_producto.focus();
                    });
                });
            },
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
            agregaProducto() {
                if (this.cantidad_producto > 0 && this.precio_producto > 0) {
                    if (this.existeProducto(parseInt(this.codigo_producto)) === false) {
                        this.items.push({ cod: parseInt(this.codigo_producto), 
                                        descripcion: this.producto.nombre, 
                                        cantidad: this.cantidad_producto, 
                                        precio: this.precio_producto 
                        });
                    }
                }

                this.codigo_producto = '';
                this.cantidad_producto = 0;
                this.nombre_producto = '';
                this.precio_producto = 0;
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
            creaOrdenCompra() {
                this.$Progress.start();
                
                axios.post('api/ordenCompra', {
                    codigo_proveedor: this.codigo_proveedor, 
                    codigo_deposito: this.codigo_deposito, 
                    codigo_forma_pago: this.codigo_formapago, 
                    fecha_orden_compra: this.fecha_orden_compra,
                    tipo: this.tipo,
                    total_orden: this.total,
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

                this.$Progress.finish();
            },
            actualizaOrdenCompra() {
                this.$Progress.start();
                
                axios.put('api/ordenCompra/'+this.orenes_compra_id_edicion, {
                    codigo_proveedor: this.codigo_cliente, 
                    fecha_orden_compra: this.fecha_orden_compra,
                    total_orden: this.total,
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
                    me.tipo = me.orden_compra.tipo;
                    me.codigo_proveedor = me.orden_compra.proveedor_id;
                    me.codigo_deposito = me.orden_compra.deposito_id;
                    me.codigo_formapago = me.orden_compra.formapago_id;
                    me.fecha_orden_compra = new Date(me.orden_compra.fecha);
                    me.fecha_orden_compra = me.fecha_orden_compra.setDate(me.fecha_orden_compra.getDate() + 1);

                    me.cargarProveedor(me.codigo_proveedor);

                    for (var i = 0; i < me.orden_compra_detalle.length; i++) {
                        me.items.push({ cod: me.orden_compra_detalle[i].producto_id, 
                                          descripcion: me.orden_compra_detalle[i].nombre_producto, 
                                          cantidad: me.orden_compra_detalle[i].cantidad, 
                                          precio: me.orden_compra_detalle[i].precio 
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
                return this.cantidad_producto * this.precio_producto;
            },
            total() {
                return this.items.reduce(
                    (acc, item) => acc + item.precio * item.cantidad,
                    0
                );
            }            
        },
        created() {
            this.cargaDepositos();
            this.cargaFormasPago();

            this.orenes_compra_id_edicion = this.$route.params.ordenescompraId;

            if(this.orenes_compra_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarOrdenCompra(this.orenes_compra_id_edicion);
            }
        }
    }
</script>