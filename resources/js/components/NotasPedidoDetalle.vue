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
                  <div>Pedido {{ notas_pedido_id_edicion }}</div>
                </div>
                <!-- /.col -->
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

                        <div class="col-sm-8 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="numero_factura" type="text" name="numero_factura" placeholder="Numero Factura"
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

                        <div class="col-sm-8 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="cliente.direccion" type="text" name="direccion_cliente" class="form-control form-control-sm" disabled>
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
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td class="col-sm-4 invoice-col">
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
                                    
                                    <td class="col-sm-2 invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="cantidad_producto" type="number" name="cantidad_producto"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="col-sm-3 invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="precio_producto" type="number" name="precio_producto"
                                                    @keydown ="keyMonitor" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="col-sm-3 invoice-col">
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
                <button v-if="modoEdicion" type="button" class="btn btn-success float-right" @click="actualizaNotaPedido()">
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

                modoEdicion: false,
                notas_pedido_id_edicion: 0,
                fecha_nota_pedido: new Date(),
                formato_fecha_nota_pedido: "dd-MM-yyyy",
                es: es,
                cliente: {},
                codigo_cliente: 0,
                items: [],
                producto: {},
                codigo_producto: 0,
                cantidad_producto: 0,
                nombre_producto: '',
                precio_producto: 0,
                numero_factura: '',
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
                    case 'precio_producto':
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
                    me.nombre_producto = me.producto.nombre;
                }).catch((error) => {
                    me.producto = {};
                    me.codigo_producto = 0;
                    me.nombre_producto = '';

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
                                        cantidad: this.cantidad_producto, 
                                        precio: this.precio_producto 
                        });
                    }
                    this.codigo_producto = 0;
                    this.cantidad_producto = 0;
                    this.nombre_producto = '';
                    this.precio_producto = 0;    

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

            // Operaciones con NV
            creaNotaPedido() {
                this.$Progress.start();
                
                axios.post('api/notaPedido', {
                    codigo_cliente: this.codigo_cliente, 
                    fecha_nota_pedido: this.fecha_nota_pedido,
                    numero_factura: this.numero_factura,
                    total_pedido: this.total,
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

                this.$Progress.finish();
            },
            actualizaNotaPedido() {
                this.$Progress.start();
                
                axios.put('api/notaPedido/'+this.notas_pedido_id_edicion, {
                    codigo_cliente: this.codigo_cliente, 
                    fecha_nota_pedido: this.fecha_nota_pedido,
                    numero_factura: this.numero_factura,
                    total_pedido: this.total,
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
                    me.fecha_nota_pedido = new Date(me.nota_pedido.fecha);
                    me.fecha_nota_pedido = me.fecha_nota_pedido.setDate(me.fecha_nota_pedido.getDate() + 1);
                    me.numero_factura = me.nota_pedido.numero_factura;

                    me.cargarCliente(me.codigo_cliente);

                    for (var i = 0; i < me.nota_pedido_detalle.length; i++) {
                        me.items.push({ cod: me.nota_pedido_detalle[i].producto_id, 
                                          descripcion: me.nota_pedido_detalle[i].nombre_producto, 
                                          cantidad: me.nota_pedido_detalle[i].cantidad, 
                                          precio: me.nota_pedido_detalle[i].precio 
                        });
                    }

                }).catch((error) => {
                    me.nota_pedido = {};
                    me.nota_pedido_detalle = {};
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
            this.notas_pedido_id_edicion = this.$route.params.notaspedidoId;
            this.cargaClientes();
            this.cargaProductos();

            if(this.notas_pedido_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarNotaPedido(this.notas_pedido_id_edicion);
            }
        }
    }
</script>