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
                  <div>Recibo N° {{ anio_id }} - {{ anio_actual }}</div>
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
                <div class="card-header border-light bg-secondary">Datos Recibo</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_recibo" name="fecha_recibo" :language="es" 
                                        :format="formato_fecha_recibo" inputClass="form-control form-control-sm" placeholder="Fecha" 
                                        :disabled="modoEdicion ? true : false">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <!-- <div class="col-sm-4 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="numero_factura" type="text" name="numero_factura" placeholder="Numero Factura"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div> -->
                        <!-- /.col -->

                        <!-- <div class="col-sm-4 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="lugar_entrega" type="text" name="lugar_entrega" placeholder="Lugar Entrega"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div> -->
                        <!-- /.col -->

                    </div>
                </div>
              </div>

              <!-- Cliente row -->
              <div class="card">
                <div class="card-header border-light bg-success">Datos Cliente</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
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

                        <div class="col-sm-6 invoice-col">
                            <label class="control-label">CUIT</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="cliente.numero_documento" type="text" name="numero_documento" class="form-control form-control-sm" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                    </div>
                </div>

              </div>
              <!-- /.row -->

              <!-- Items row -->
              <div class="card">
                <div class="card-header border-light bg-dark">Items Recibo</div>
                <div class="card-body">              
                    <div class="row invoice-info">
                        <div class="col-12 invoice-col">
                        <table class="table table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th style="width:30%">Tipo Pago</th>
                                    <th style="width:30%">Banco</th>
                                    <th style="width:10%">Fecha Cheque</th>
                                    <th style="width:15%">N° Cheque</th>
                                    <th style="width:15%">Importe</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" v-model="tipo_pago" ref="tipo_pago" @change="seteaNombreFP($event)">
                                                    <option value="">Forma Pago...</option>
                                                    <option value="CH">CHEQUE</option>
                                                    <option value="CO">CONTADO</option>
                                                </select>                                                    
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->
                                    
                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" v-model="codigo_banco" :disabled="tipo_pago != 'CH' ? true : false">
                                                    <option value=0>Banco...</option>
                                                    <option v-for="lbanco in lbancos" :key="lbanco.id" :value="lbanco.id">{{ lbanco.nombre }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="fecha_cheque" type="date" name="fecha_cheque"
                                                    class="form-control form-control-sm" :disabled="tipo_pago != 'CH' ? true : false">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="numero_cheque" type="number" name="numero_cheque"
                                                    class="form-control form-control-sm" :disabled="tipo_pago != 'CH' ? true : false">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="importe_item_recibo" type="number" name="importe_item_recibo"
                                                    @keydown ="keyMonitor" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                </tr>                            
                            </thead>
                            <tbody>
                                <tr class="item" v-for="(item, index) in items" :key="item.cod">
                                    <td>{{ item.tipo_pago}}</td>
                                    <td>{{ item.banco_id }}</td>
                                    <td>{{ item.fecha_cheque }}</td>
                                    <td>{{ item.numero_cheque }}</td>
                                    <td>${{ item.importe }}</td>
                                    <td>
                                        <a href="#" @click="removerItemRecibo(index)">
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
                lbancos: {},
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
                recibo_id_edicion: 0,
                fecha_recibo: new Date(),
                formato_fecha_recibo: "dd-MM-yyyy",
                es: es,
                cliente: {},
                codigo_cliente: 0,
                anio_id: 0,
                anio_actual: 0,
                items: [],
                tipo_pago: '',
                tipo_pago_descripcion: '',
                codigo_banco: 0,
                codigo_banco_nombre: '',
                fecha_cheque: '',
                numero_cheque: 0,
                importe_item_recibo: 0,
                recibo: {},
                recibo_detalle: {}
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
                    case 'importe_item_recibo':
                        switch(origenKey) {
                            case 'Tab':    
                                this.agregaItemRecibo();
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
            cargaBancos() {
                let me = this;                
                var url = 'api/banco/cargaBancos';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lbancos = response.bancos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },

            //Cargo datos individuales
            seteaNombreFP(e) {
                console.log(e.target.innerText);
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

                    this.focusInput('codigo_cliente');
                });
            },

            // Operaciones con productos
            agregaItemRecibo() {
                if (this.importe_item_recibo > 0) {

                    //if (this.existeProducto(parseInt(this.codigo_producto)) === false) {
                        this.items.push({ tipo_pago: this.tipo_pago, 
                                        tipo_pago_descripcion: this.tipo_pago_descripcion, 
                                        banco_id: this.codigo_banco, 
                                        codigo_banco_nombre: this.codigo_banco_nombre, 
                                        fecha_cheque: this.fecha_cheque, 
                                        numero_cheque: this.numero_cheque,
                                        importe: this.importe_item_recibo
                        });
                    //}
                    this.tipo_pago = 0;
                    this.codigo_banco = 0;
                    this.fecha_cheque = '';
                    this.numero_cheque = 0;
                    this.importe_item_recibo = 0;
                    this.codigo_banco_nombre = '';
                    this.tipo_pago_descripcion = '';

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
                                          cantidad: me.nota_pedido_detalle[i].cantidad, 
                                          precio: me.nota_pedido_detalle[i].precio,
                                          flete: me.nota_pedido_detalle[i].flete,
                                          comision_venta: me.nota_pedido_detalle[i].comision_venta
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
                var lTotal = parseFloat(this.flete_producto) + parseFloat(this.comision_venta_producto);
                return ((this.cantidad_producto * this.precio_producto) + parseFloat(lTotal));
            },
            total() {
                return this.items.reduce(
                    (acc, item) => acc + (parseFloat(item.flete) + parseFloat(item.comision_venta) + (item.precio * item.cantidad)),
                    0
                );
            }            
        },
        created() {
            this.recibo_id_edicion = this.$route.params.reciboId;
            this.cargaClientes();
            this.cargaBancos();

            if(this.notas_pedido_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarNotaPedido(this.notas_pedido_id_edicion);
            }
        }
    }
</script>