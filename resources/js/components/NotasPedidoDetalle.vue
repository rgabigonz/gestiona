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
                    <i class="fa fa-globe"></i> Agro Proyecciones SRL
                  </h4>
                  <div>Nota de Venta N° {{ anio_id }} - {{ anio_actual }}</div>
                </div>
                <!-- /.col -->
              </div>

              <br>

              <div v-if="errors.length" class="alert alert-danger" role="alert">
                <b>Se produjeron los siguientes errores:</b>
                <ul>
                    <li v-for="error in errors" :key="error.id">{{ error }}</li>
                </ul>
              </div>
              
              <br>              

              <!-- Datos NV row -->
              <div class="card">
                <div class="card-header border-light bg-secondary">Datos Nota de Venta</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_nota_pedido" name="fecha_nota_pedido" :language="es" 
                                        :format="formato_fecha_nota_pedido" inputClass="form-control form-control-sm" placeholder="Fecha" 
                                        :disabled="modoEdicion ? true : false">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-4 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="numero_factura" type="text" name="numero_factura" placeholder="Numero Factura"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

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

              <!-- Cliente row -->
              <div class="card">
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
                            <label class="control-label">CUIT</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="cliente.numero_documento" type="text" name="numero_documento" class="form-control form-control-sm" disabled>
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
                                        <option v-for="lvendedor_venta in lvendedores_venta" :key="lvendedor_venta.id" :value="lvendedor_venta.id">{{ lvendedor_venta.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                    </div>
                </div>

              </div>
              <!-- /.row -->

              <!-- Productos row -->
              <div class="card">
                <div class="card-header border-light bg-dark">Productos</div>
                <div class="card-body">              
                    <div class="row invoice-info">
                        <div class="col-12 invoice-col">
                            <table class="table table-striped table-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th style="width:40%">Producto</th>
                                        <th style="width:10%">Cantidad</th>
                                        <th style="width:10%">Precio</th>
                                        <th style="width:10%">Flete</th>
                                        <th style="width:10%">C. Venta</th>
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
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>
                                        </td>
                                        <!-- /.col -->

                                        <td class="invoice-col">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <input v-model="flete_producto" type="number" name="flete_producto"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>
                                        </td>
                                        <!-- /.col -->

                                        <td class="invoice-col">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <input v-model="comision_venta_producto" type="number" name="comision_venta_producto"
                                                        @keydown ="keyMonitor" class="form-control form-control-sm">
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
                                        <td>${{ calculaPUnitario(item) | currency }}</td>
                                        <td>
                                            <b>${{ calculaSTotal(item) | currency }}</b>
                                        </td>
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
                                        <td><b>Total sin IVA:</b></td>
                                        <td><b>${{ total_sinIVA | currency }}</b></td>
                                    </tr>
                                    <tr>
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
                                        <td><b>IVA 21%:</b></td>
                                        <td><b>${{ total21 | currency }}</b></td>
                                    </tr>                                                                                                            
                                    <tr>
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
                    <!-- /.row -->
                </div>
              </div>

            </div>
            <!-- /.invoice -->

          </div>
          <!-- /.col -->

        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaNotaPedido()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <button v-if="modoEdicion && estado == 'PE'" type="button" class="btn btn-success float-right" @click="actualizaNotaPedido()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link to="/notaspedido" class="btn btn-primary float-right" style="margin-right: 5px;">
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
                //Lista de Seleccion clientes y productos
                lclientes: {},
                lproductos: {},
                lvendedores_venta: {},
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
                //Lista de Seleccion clientes y productos   

                // Errores
                errors: [],

                modoEdicion: false,
                estado: '',
                notas_pedido_id_edicion: 0,
                fecha_nota_pedido: new Date(),
                formato_fecha_nota_pedido: "dd-MM-yyyy",
                es: es,
                cliente: {},
                codigo_cliente: 0,
                codigo_vendedor_venta: 0,
                anio_id: 0,
                anio_actual: 0,
                items: [],
                producto: {},
                codigo_producto: 0,
                cantidad_producto: 0,
                nombre_producto: '',
                descripcion_producto: '',
                precio_producto: 0,
                iva_producto: 0,
                flete_producto: 0,
                comision_venta_producto: 0,
                numero_factura: '',
                lugar_entrega: '',
                total_iva_21: 0,
                total_iva_105: 0,                
                nota_pedido: {},
                nota_pedido_detalle: {}
            }
        },
        methods: {
            focusInput(inputRef) {
                // $refs is an object that holds the DOM references to your inputs
                this.$nextTick(function(){
                    this.$refs[inputRef].focus();
                });                
            },
            keyMonitor(event) {
                let origenInput = event.target.name;
                let origenKey = event.key || String.fromCharCode(event.keyCode);

                switch(origenInput) {
                    case 'comision_venta_producto':
                        switch(origenKey) {
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

            // Cargo combos de seleccion
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

            //Cargo datos individuales
            cargarCliente(cCod) {
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

                    this.focusInput('codigo_producto');

                    /*toast({
                        type: 'error',
                        title: 'Codigo de producto no encontrado!'
                    });     */                   
                });
            },

            // Operaciones con productos
            agregaProducto() {
                if (this.cantidad_producto > 0 && this.precio_producto > 0) {

                    if (this.existeProducto(parseInt(this.codigo_producto)) === false) {
                        this.items.push({ cod: parseInt(this.codigo_producto), 
                                        descripcion: this.nombre_producto, 
                                        descripcion_larga: this.descripcion_producto, 
                                        cantidad: this.cantidad_producto, 
                                        precio: this.precio_producto,
                                        flete: this.flete_producto,
                                        comision_venta: this.comision_venta_producto,
                                        alicuota_iva: this.iva_producto
                        });
                    }
                    this.codigo_producto = 0;
                    this.cantidad_producto = 0;
                    this.nombre_producto = '';
                    this.descripcion_producto = '';
                    this.precio_producto = 0;
                    this.iva_producto = 0;
                    this.flete_producto = 0;
                    this.comision_venta_producto = 0;

                } /*else {
                    toast({
                        type: 'error',
                        title: 'Debe ingresar producto, cantidad y precio'
                    });                      
                }*/
            },
            removerProducto(index) {
                this.items.splice(index, 1);
            },
            existeProducto(pCod) {
                this.focusInput('codigo_producto');

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].cod === pCod) {
                        this.items[i].cantidad = parseInt(this.items[i].cantidad) + parseInt(this.cantidad_producto);
                        return true;
                    }
                }
                return false;
            },

            // Operaciones con NP Proveedores (OC)
            validaNV() {
                var resultado = false;

                if (this.codigo_cliente && this.items.length) {
                    resultado = true;
                }

                this.errors = [];

                if (this.codigo_cliente == 0) {
                    this.errors.push('Debe ingresar un cliente');
                }
                if (this.items.length == 0) {
                    this.errors.push('Debe ingresar al menos un producto');
                }

                return resultado;
            },

            // Operaciones con NV
            creaNotaPedido() {
                this.$Progress.start();
                
                if (this.validaNV()) {
                    axios.post('api/notaPedido', {
                        codigo_cliente: this.codigo_cliente, 
                        codigo_vendedor_venta: this.codigo_vendedor_venta,
                        fecha_nota_pedido: this.fecha_nota_pedido,
                        numero_factura: this.numero_factura,
                        lugar_entrega: this.lugar_entrega,
                        total_pedido: this.total_conIVA,
                        total_pedido_siniva: this.total_sinIVA,
                        total_pedido_21: this.total_iva_21,
                        total_pedido_105: this.total_iva_105,
                        items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se genero el pedido correctamente!'
                        });
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });

                } else {
                    toast({
                        type: 'error',
                        title: 'Verificar errores'
                    });
                }
                
                this.$Progress.finish();
                
            },
            actualizaNotaPedido() {
                this.$Progress.start();
                
                axios.put('api/notaPedido/'+this.notas_pedido_id_edicion, {
                    codigo_cliente: this.codigo_cliente, 
                    codigo_vendedor_venta: this.codigo_vendedor_venta,
                    fecha_nota_pedido: this.fecha_nota_pedido,
                    numero_factura: this.numero_factura,
                    lugar_entrega: this.lugar_entrega,
                    total_pedido: this.total_conIVA,
                    total_pedido_siniva: this.total_sinIVA,
                    total_pedido_21: this.total_iva_21,
                    total_pedido_105: this.total_iva_105,
                    items: this.items})
                .then(() => {
                    Fire.$emit('AfterAction');
                    toast({
                        type: 'success',
                        title: 'Se actualizo el pedido correctamente!'
                    });
                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            cargarNotaPedido(npCod) {
                let me = this;                
                var url = 'api/notaPedido/devuelveNotaPedido/'+npCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.nota_pedido = response.datoNotaPedido;
                    me.nota_pedido_detalle = response.datoNotaPedidoD;

                    //Datos Nota Pedido
                    me.codigo_cliente = me.nota_pedido.cliente_id;

                    if (me.nota_pedido.vendedor_venta_id != null) {
                        me.codigo_vendedor_venta = me.nota_pedido.vendedor_venta_id;
                    } else {
                        me.codigo_vendedor_venta = 0;
                    }

                    me.fecha_nota_pedido = new Date(me.nota_pedido.fecha);
                    me.fecha_nota_pedido = me.fecha_nota_pedido.setDate(me.fecha_nota_pedido.getDate() + 1);
                    me.numero_factura = me.nota_pedido.numero_factura;
                    me.lugar_entrega = me.nota_pedido.lugar_entrega;
                    me.estado = me.nota_pedido.estado;

                    //Datos Orden Compra
                    me.anio_id = this.nota_pedido.anio_id;
                    me.anio_actual = this.nota_pedido.anio_actual;

                    me.cargarCliente(me.codigo_cliente);

                    for (var i = 0; i < me.nota_pedido_detalle.length; i++) {
                        me.items.push({ cod: me.nota_pedido_detalle[i].producto_id, 
                                          descripcion: me.nota_pedido_detalle[i].nombre_producto, 
                                          descripcion_larga: me.nota_pedido_detalle[i].descripcion_producto, 
                                          cantidad: me.nota_pedido_detalle[i].cantidad, 
                                          precio: me.nota_pedido_detalle[i].precio,
                                          flete: me.nota_pedido_detalle[i].flete,
                                          comision_venta: me.nota_pedido_detalle[i].comision_venta,
                                          alicuota_iva: me.nota_pedido_detalle[i].alicuota_iva
                        });
                    }

                }).catch((error) => {
                    me.nota_pedido = {};
                    me.nota_pedido_detalle = {};
                });
            },
            calculaPUnitario(item) {
                return (parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta));
            },
            calculaSTotal(item) {
                return ((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta)) * item.cantidad);
            },
            calculaIVA(item) {
                if (item.iva == 21) 
                    return (((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta)) * item.cantidad) * 1.21) - 
                            ((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta)) * item.cantidad);
                else if (item.iva == 10.5)
                    return (((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta)) * item.cantidad) * 1.105) - 
                            ((parseFloat(item.precio) + parseFloat(item.flete) + parseFloat(item.comision_venta)) * item.cantidad);
            }
        },
        computed: {
            subtotal_iva_producto() {
                var lTotal_Adicionales = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto);
                var lTotal = parseFloat(this.precio_producto) + parseFloat(lTotal_Adicionales);

                if (this.iva_producto == 21)
                    return ((this.cantidad_producto * lTotal * 1.21) - (this.cantidad_producto * lTotal));
                else
                    return ((this.cantidad_producto * lTotal * 1.105) - (this.cantidad_producto * lTotal));
            },
            subtotal_producto() {
                var lTotal_Adicionales = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto);
                var lTotal = parseFloat(this.precio_producto) + parseFloat(lTotal_Adicionales);
                return (this.cantidad_producto * lTotal);
            },
            unitario_producto() {
                var lTotal = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto);
                return (parseFloat(this.precio_producto) + parseFloat(lTotal));
            },
            total_sinIVA() {
                return this.items.reduce(
                    (acc, item) => acc + ((parseFloat(item.flete) + parseFloat(item.comision_venta) + parseFloat(item.precio)) * parseFloat(item.cantidad)),
                    0
                );
            },
            total105() {
                var lTotal_Adicionales = 0;
                var lTotal = 0;
                var lCantidad = 0;

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].alicuota_iva == '10.50') {
                        lTotal_Adicionales = (parseFloat(this.items[i].flete) + parseFloat(this.items[i].comision_venta));
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
                        lTotal_Adicionales = (parseFloat(this.items[i].flete) + parseFloat(this.items[i].comision_venta));
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
                    lTotal_Adicionales = (parseFloat(this.items[i].flete) + parseFloat(this.items[i].comision_venta));
                    lTotal += (parseFloat(this.items[i].precio) + lTotal_Adicionales) * parseFloat(this.items[i].cantidad);
                }  
                
                return parseFloat(this.total_iva_21 + this.total_iva_105 + lTotal);
            }           
        },
        created() {
            this.notas_pedido_id_edicion = this.$route.params.notaspedidoId;
            this.cargaClientes();
            this.cargaProductos();
            this.cargaVendedores();

            if(this.notas_pedido_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarNotaPedido(this.notas_pedido_id_edicion);
            }
        }
    }
</script>