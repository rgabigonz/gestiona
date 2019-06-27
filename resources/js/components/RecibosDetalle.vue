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
                  <div>Recibo N° {{ punto_venta }} - {{ numero_recibo }}</div>
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
                        <div class="col-sm-6 invoice-col">
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

                    <div class="col-sm-6 invoice-col">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input v-model="referencia_talonario" type="text" name="referencia_talonario" placeholder="N° Recibo Talonario"
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
                <div class="card-header border-light bg-success">Datos Cliente</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
                            <label class="control-label">Cliente</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <select class="form-control" v-model="codigo_cliente" @change="cargarCliente(codigo_cliente)"
                                            :disabled="modoEdicion ? true : false">
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
                                    <th style="width:25%">Tipo Pago</th>
                                    <th style="width:30%">Banco</th>
                                    <th style="width:10%">Fecha Cobro</th>
                                    <th style="width:15%">N° Cheque</th>
                                    <th style="width:15%">Importe</th>
                                    <th style="width:5%"></th>
                                </tr>
                                <tr>
                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" v-model="tipo_pago" ref="tipo_pago">
                                                    <option value="">Forma Pago...</option>
                                                    <option v-for="ltipos_pago in ltipos_pagos" :key="ltipos_pago.id" :value="{ id: ltipos_pago.id, text: ltipos_pago.nombre }">{{ ltipos_pago.nombre }}</option>
                                                </select>                                                    
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->
                                    
                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" v-model="codigo_banco" :disabled="tipo_pago.id != 'CH' ? true : false">
                                                    <option value=0>Banco...</option>
                                                    <option v-for="lbanco in lbancos" :key="lbanco.id" :value="{ id: lbanco.id, text: lbanco.nombre }">{{ lbanco.nombre }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="fecha_cobro_cheque" type="date" name="fecha_cobro_cheque"
                                                    class="form-control form-control-sm" :disabled="tipo_pago.id != 'CH' ? true : false">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="numero_cheque" type="number" name="numero_cheque"
                                                    class="form-control form-control-sm" :disabled="tipo_pago.id != 'CH' ? true : false">
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
                                    <td>{{ item.tipo_pago_descripcion}}</td>
                                    <td>{{ item.codigo_banco_nombre }}</td>
                                    <td>{{ item.fecha_cobro_cheque | formatDate }}</td>
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
                                    <td>Total:</td>
                                    <td>${{ total | currency }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <!-- /.col -->

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
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaRecibo()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <button v-if="modoEdicion && estado == 'PE'" type="button" class="btn btn-success float-right" @click="actualizaRecibo()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link to="/recibos" class="btn btn-primary float-right" style="margin-right: 5px;">
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
                ltipos_pagos: [
                    {id: 'CH', nombre: 'CHEQUE'},
                    {id: 'EF', nombre: 'EFECTIVO'}
                ],                
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
                punto_venta: '',
                numero_recibo: 0,
                items: [],
                tipo_pago: '',
                codigo_banco: 0,
                fecha_cobro_cheque: '',
                numero_cheque: 0,
                importe_item_recibo: 0,
                observacion:'',
                referencia_talonario: '',
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

                    if (this.existeItemRecibo(this.tipo_pago.id, this.codigo_banco.id, this.fecha_cobro_cheque, 
                                            this.numero_cheque, this.importe_item_recibo) === false) {
                        this.items.push({tipo_pago: this.tipo_pago.id, 
                                        tipo_pago_descripcion: this.tipo_pago.text, 
                                        banco_id: this.codigo_banco.id, 
                                        codigo_banco_nombre: this.codigo_banco.text, 
                                        fecha_cobro_cheque: this.fecha_cobro_cheque, 
                                        numero_cheque: this.numero_cheque,
                                        importe: this.importe_item_recibo
                        });
                    }
                    this.tipo_pago = '';
                    this.codigo_banco = 0;
                    this.fecha_cobro_cheque = '';
                    this.numero_cheque = 0;
                    this.importe_item_recibo = 0;

                } /*else {
                    toast({
                        type: 'error',
                        title: 'Debe ingresar producto, cantidad y precio'
                    });                      
                }*/
            },
            removerItemRecibo(index) {
                this.items.splice(index, 1);
            },
            existeItemRecibo(tp_id, bco_id, fec_ch, num_ch, imp) {
                this.focusInput('tipo_pago');
                console.log(tp_id + bco_id + fec_ch + num_ch + imp);
                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].tipo_pago === tp_id && this.items[i].banco_id === bco_id && 
                        this.items[i].numero_cheque === num_ch ) {
                            this.items[i].fecha_cobro_cheque = fec_ch;
                            this.items[i].importe = imp;
                            return true;
                    }
                }
                return false;
            },

            // Operaciones con NP Proveedores (OC)
            validaRecibo() {
                var resultado = false;

                if (this.codigo_cliente && this.items.length) {
                    resultado = true;
                }

                this.errors = [];

                if (this.codigo_cliente == 0) {
                    this.errors.push('Debe ingresar un cliente');
                }
                if (this.items.length == 0) {
                    this.errors.push('Debe ingresar al menos un item de recibo');
                }

                return resultado;
            },

            // Operaciones con Recibo
            creaRecibo() {
                this.$Progress.start();
                
                if (this.validaRecibo()) {
                    axios.post('api/recibo', {
                        codigo_cliente: this.codigo_cliente, 
                        fecha_recibo: this.fecha_recibo,
                        total_recibo: this.total,
                        obs: this.observacion,
                        referencia_talonario: this.referencia_talonario,
                        items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se genero el recibo correctamente!'
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
            actualizaRecibo() {
                this.$Progress.start();
                
                if (this.validaRecibo()) {
                    axios.put('api/recibo/'+this.recibo_id_edicion, {
                            total_recibo: this.total,
                            obs: this.observacion,
                            referencia_talonario: this.referencia_talonario,
                            items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se actualizo el recibo correctamente!'
                        });
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
                }
                this.$Progress.finish();
            },
            cargarRecibo(rCod) {
                let me = this;                
                var url = 'api/recibo/devuelveRecibo/'+rCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.recibo = response.datoRecibo;
                    me.recibo_detalle = response.datoReciboD;

                    //Datos Recibo
                    me.codigo_cliente = me.recibo[0].cliente_id;

                    me.fecha_recibo = new Date(me.recibo[0].fecha);
                    me.fecha_recibo = me.fecha_recibo.setDate(me.fecha_recibo.getDate() + 1);

                    me.referencia_talonario = me.recibo[0].referencia_talonario;

                    me.estado = me.recibo[0].estado;

                    me.numero_recibo = me.recibo[0].numero_recibo;
                    me.punto_venta = me.recibo[0].punto_venta;

                    me.cargarCliente(me.codigo_cliente);

                    for (var i = 0; i < me.recibo_detalle.length; i++) {

                        if (me.recibo_detalle[i].tipo_pago == 'CH' )
                            me.tipo_pago_descripcion = 'CHEQUE';
                        if (me.recibo_detalle[i].tipo_pago == 'EF' )
                            me.tipo_pago_descripcion = 'EFECTIVO';

                        me.items.push({ tipo_pago: me.recibo_detalle[i].tipo_pago, 
                                        tipo_pago_descripcion: me.tipo_pago_descripcion,
                                        banco_id: me.recibo_detalle[i].banco_id, 
                                        codigo_banco_nombre: me.recibo_detalle[i].nombre_banco,
                                        fecha_cobro_cheque: me.recibo_detalle[i].fecha_cobro_cheque,
                                        numero_cheque: me.recibo_detalle[i].numero_cheque,
                                        importe: me.recibo_detalle[i].importe
                        });
                    }

                }).catch((error) => {
                    me.recibo = {};
                    me.recibo_detalle = {};
                });
            }
        },
        computed: {
            total() {
                return this.items.reduce(
                    (acc, item) => acc + (parseFloat(item.importe)),
                    0
                );
            }            
        },
        created() {
            this.recibo_id_edicion = this.$route.params.reciboId;
            this.cargaClientes();
            this.cargaBancos();

            if(this.recibo_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarRecibo(this.recibo_id_edicion);
            }
        }
    }
</script>