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
                  <div>Nota de Credito N° {{ punto_venta }} - {{ numero_nota_credito }}</div>
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
                <div class="card-header border-light bg-secondary">Datos Nota de Credito</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_nota_credito" name="fecha_nota_credito" :language="es" 
                                        :format="formato_fecha_nota_credito" inputClass="form-control form-control-sm" placeholder="Fecha" 
                                        :disabled="modoEdicion ? true : false">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-6 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="precio_dolar_manual" type="number" name="precio_dolar_manual" placeholder="Dolar" step=".1"
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
                <div class="card-header border-light bg-dark">Items Nota de Credito</div>
                <div class="card-body">              
                    <div class="row invoice-info">
                        <div class="col-12 invoice-col">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width:55%">Concepto</th>
                                    <th style="width:40%">Importe</th>
                                    <th style="width:5%"></th>
                                </tr>
                                <tr>
                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" v-model="codigo_concepto">
                                                    <option value=0>Concepto...</option>
                                                    <option v-for="lconcepto in lconceptos" :key="lconcepto.id" :value="{ id: lconcepto.id, text: lconcepto.descripcion }">{{ lconcepto.descripcion }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="importe_item_nota_credito" type="number" name="importe_item_nota_credito"  step=".1"
                                                    @keydown ="keyMonitor" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- /.col -->

                                </tr>                            
                            </thead>
                            <tbody>
                                <tr class="item" v-for="(item, index) in items" :key="item.cod">
                                    <td>{{ item.codigo_concepto_descripcion }}</td>
                                    <td>${{ item.importe }}</td>
                                    <td>
                                        <a href="#" @click="removerItemNotaCredito(index)">
                                            <i class="fa fa-trash-alt red"></i>
                                        </a>                            
                                    </td>
                                </tr>
                                <tr>
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
                                <textarea v-model="observacion" type="text" name="observacion" placeholder="Observacion"
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
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaNotaCredito()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <button v-if="modoEdicion" type="button" class="btn btn-success float-right" @click="actualizaNotaCredito()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link to="/notascredito" class="btn btn-primary float-right" style="margin-right: 5px;">
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
                lconceptos: {},

                // Errores
                errors: [],

                // Variables
                modoEdicion: false,

                fecha_nota_credito: new Date(),
                formato_fecha_nota_credito: "dd-MM-yyyy",
                es: es,

                estado: '',
                observacion:'',                
                nota_credito_id_edicion: 0,
                codigo_cliente: 0,
                punto_venta: '',
                numero_nota_credito: 0,
                codigo_concepto: 0,
                importe_item_nota_credito: 0,
                precio_dolar_manual: 0,

                //Objetos 
                items: [],                
                cliente: {},
                nota_credito: {},
                nota_credito_detalle: {}
            }
        },
        methods: {
            focusInput(inputRef) {
                this.$nextTick(function(){
                    this.$refs[inputRef].focus();
                });                
            },
            keyMonitor(event) {
                let origenInput = event.target.name;
                let origenKey = event.key || String.fromCharCode(event.keyCode);

                switch(origenInput) {
                    case 'importe_item_nota_credito':
                        switch(origenKey) {
                            case 'Tab':    
                                this.agregaItemNotaCredito();
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
            cargaConceptos() {
                let me = this;                
                var url = 'api/concepto/cargaConceptosNC';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lconceptos = response.conceptos;
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

            // Operaciones con conceptos
            agregaItemNotaCredito() {
                if (this.importe_item_nota_credito > 0) {

                    if (this.existeItemNotaCredito(this.codigo_concepto.id, this.importe_item_nota_credito) === false) {
                        this.items.push({concepto_id: this.codigo_concepto.id, 
                                        codigo_concepto_descripcion: this.codigo_concepto.text, 
                                        importe: this.importe_item_nota_credito
                        });
                    }
                    this.codigo_concepto = 0;
                    this.importe_item_nota_credito = 0;

                }
            },
            removerItemNotaCredito(index) {
                this.items.splice(index, 1);
            },
            existeItemNotaCredito(cpt_id, imp) {
                //this.focusInput('codigo_concepto');

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].concepto_id === cpt_id) {
                            this.items[i].importe = imp;
                            return true;
                    }
                }
                return false;
            },

            // Operaciones con ND
            validaNotaCredito() {
                var resultado = false;

                if (this.codigo_cliente && this.items.length) {
                    resultado = true;
                }

                this.errors = [];

                if (this.codigo_cliente == 0) {
                    this.errors.push('Debe ingresar un cliente');
                }
                if (this.items.length == 0) {
                    this.errors.push('Debe ingresar al menos un item de la nota de credito');
                }

                return resultado;
            },
            creaNotaCredito() {
                this.$Progress.start();
                
                if (this.validaNotaCredito()) {
                    axios.post('api/notacredito', {
                        codigo_cliente: this.codigo_cliente, 
                        fecha_nota_credito: this.fecha_nota_credito,
                        total_nota_credito: this.total,
                        obs: this.observacion,
                        precio_dolar_manual: this.precio_dolar_manual,
                        items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se genero la nota de credito correctamente!'
                        });
                        this.$router.push('/notascredito');
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
            actualizaNotaCredito() {
                this.$Progress.start();
                
                if (this.validaNotaCredito()) {
                    axios.put('api/notacredito/'+this.nota_credito_id_edicion, {
                            total_nota_credito: this.total,
                            obs: this.observacion,
                            precio_dolar_manual: this.precio_dolar_manual,
                            items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se actualizo la nota de credito correctamente!'
                        });
                        this.$router.push('/notascredito');
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
                }
                this.$Progress.finish();
            },
            cargarNotaCredito(ncCod) {
                let me = this;                
                var url = 'api/notacredito/devuelveNotaCredito/'+ncCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.nota_credito = response.datoNotaCredito;
                    me.nota_credito_detalle = response.datoNotaCreditoD;

                    //Datos ND
                    me.codigo_cliente = me.nota_credito[0].cliente_id;

                    me.fecha_nota_credito = new Date(me.nota_credito[0].fecha);
                    me.fecha_nota_credito = me.fecha_nota_credito.setDate(me.fecha_nota_credito.getDate() + 1);

                    me.estado = me.nota_credito[0].estado;

                    me.numero_nota_credito = me.nota_credito[0].numero_nota_credito;
                    me.punto_venta = me.nota_credito[0].punto_venta;

                    me.cargarCliente(me.codigo_cliente);

                    me.observacion = me.nota_credito[0].obs;
                    me.precio_dolar_manual = me.nota_credito[0].precio_dolar_manual;

                    for (var i = 0; i < me.nota_credito_detalle.length; i++) {

                        me.items.push({ concepto_id: me.nota_credito_detalle[i].concepto_id, 
                                        codigo_concepto_descripcion: me.nota_credito_detalle[i].descripcion_concepto,
                                        importe: me.nota_credito_detalle[i].importe
                        });
                    }

                }).catch((error) => {
                    me.nota_credito = {};
                    me.nota_credito_detalle = {};
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
            this.nota_credito_id_edicion = this.$route.params.notacreditoId;

            this.cargaClientes();
            this.cargaConceptos();

            if(this.nota_credito_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarNotaCredito(this.nota_credito_id_edicion);
            }
        }
    }
</script>