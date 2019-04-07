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
                  <div>Pedido {{ notas_pedido_id_edicion }}</div>
                </div>
                <!-- /.col -->
              </div>

              <br>
              <!-- info row -->

              <div class="row invoice-info">
                <div class="col-sm-2 invoice-col">
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
              </div>
              <!-- info row -->

              <div class="row invoice-info">
                <div class="col-sm-2 invoice-col">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <input v-model="codigo_cliente" type="number" name="codigo_cliente" ref="codigo_cliente"
                                @keydown ="keyMonitor" class="form-control form-control-sm" :disabled="modoEdicion ? true : false">
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">
                    <div class="form-group">
                        <input v-model="cliente.nombre" type="text" name="nombre_cliente" class="form-control form-control-sm" disabled>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <div class="form-group">
                        <input v-model="cliente.correo_electronico" type="text" name="correo_cliente" class="form-control form-control-sm" disabled>
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
                        <input v-model="cliente.direccion" type="text" name="direccion_cliente" class="form-control form-control-sm" disabled>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <div class="form-group">
                        <input v-model="cliente.telefono" type="text" name="telefono_cliente" class="form-control form-control-sm" disabled>
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
                                        <input v-model="codigo_producto" type="number" name="codigo_producto" ref="codigo_producto"
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
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaNotaPedido()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <button v-if="modoEdicion" type="button" class="btn btn-success float-right" @click="creaNotaPedido()">
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
                modoEdicion: false,
                notas_pedido_id_edicion: 0,
                fecha_nota_pedido: new Date(),
                formato_fecha_nota_pedido: "dd-MM-yyyy",
                es: es,
//                pedidos: {},
                cliente: {},
                codigo_cliente: '',
                items: [],
                producto: {},
                codigo_producto: '',
                cantidad_producto: 0,
                nombre_producto: '',
                precio_producto: 0,
                nota_pedido: {},
                nota_pedido_detalle: {}
            }
        },
        methods: {
            keyMonitor(event) {
                let origenInput = event.target.name;
                let origenKey = event.key || String.fromCharCode(event.keyCode);

                switch(origenInput) {
                    case 'codigo_cliente':
                        switch(origenKey) {
                            case 'Enter':
                            case 'Tab':  
                                this.cargarCliente(this.codigo_cliente);
                                break;
                            default:
                                //code block
                        } 
                        break;
                    case 'codigo_producto':
                        switch(origenKey) {
                            case 'Enter':
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
            cargarCliente(cCod) {
                let me = this;                
                var url = 'api/cliente/devuelveDatosCliente/'+cCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.cliente = response.datoCliente;
                }).catch((error) => {
                    me.cliente = {};
                    me.codigo_cliente = '';

                    //Levo el foco al codigo de cliente
                    this.$nextTick(() => {
                        this.$refs.codigo_cliente.focus();
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
            agregaProducto() {
                if (this.codigo_producto > 0 && this.cantidad_producto > 0 && this.precio_producto > 0) {
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
            creaNotaPedido() {
                this.$Progress.start();
                
                axios.post('api/notaPedido', {
                    codigo_cliente: this.codigo_cliente, 
                    fecha_nota_pedido: this.fecha_nota_pedido,
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
            cargarNotaPedido(npCod) {
                let me = this;                
                var url = 'api/notaPedido/devuelveNotaPedido/'+npCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.nota_pedido = response.datoNotaPedido;
                    me.nota_pedido_detalle = response.datoNotaPedidoD;

                    //Datos Nota Pedido
                    me.codigo_cliente = me.nota_pedido.cliente_id;
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

            if(this.notas_pedido_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarNotaPedido(this.notas_pedido_id_edicion);
            }
        }
    }
</script>