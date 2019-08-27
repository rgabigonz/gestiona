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
                  <div>Nota de Debito N° {{ punto_venta }} - {{ numero_nota_debito }}</div>
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
                <div class="card-header border-light bg-secondary">Datos Nota de Debito</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-12 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_nota_debito" name="fecha_nota_debito" :language="es" 
                                        :format="formato_fecha_nota_debito" inputClass="form-control form-control-sm" placeholder="Fecha" 
                                        :disabled="modoEdicion ? true : false">
                                    </datepicker>
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
                <div class="card-header border-light bg-dark">Items Nota de Debito</div>
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
                                                <input v-model="importe_item_nota_debito" type="number" name="importe_item_nota_debito"  step=".1"
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
                                        <a href="#" @click="removerItemNotaDebito(index)">
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
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaNotaDebito()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <button v-if="modoEdicion" type="button" class="btn btn-success float-right" @click="actualizaNotaDebito()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link to="/notasdebito" class="btn btn-primary float-right" style="margin-right: 5px;">
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

                fecha_nota_debito: new Date(),
                formato_fecha_nota_debito: "dd-MM-yyyy",
                es: es,

                estado: '',
                observacion:'',                
                nota_debito_id_edicion: 0,
                codigo_cliente: 0,
                punto_venta: '',
                numero_nota_debito: 0,                
                codigo_concepto: 0,
                importe_item_nota_debito: 0,

                //Objetos 
                items: [],                
                cliente: {},
                nota_debito: {},
                nota_debito_detalle: {}
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
                    case 'importe_item_nota_debito':
                        switch(origenKey) {
                            case 'Tab':    
                                this.agregaItemNotaDebito();
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
                var url = 'api/concepto/cargaConceptosND';
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
            agregaItemNotaDebito() {
                if (this.importe_item_nota_debito > 0) {

                    if (this.existeItemNotaDebito(this.codigo_concepto.id, this.importe_item_nota_debito) === false) {
                        this.items.push({concepto_id: this.codigo_concepto.id, 
                                        codigo_concepto_descripcion: this.codigo_concepto.text, 
                                        importe: this.importe_item_nota_debito
                        });
                    }
                    this.codigo_concepto = 0;
                    this.importe_item_nota_debito = 0;

                }
            },
            removerItemNotaDebito(index) {
                this.items.splice(index, 1);
            },
            existeItemNotaDebito(cpt_id, imp) {
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
            validaNotaDebito() {
                var resultado = false;

                if (this.codigo_cliente && this.items.length) {
                    resultado = true;
                }

                this.errors = [];

                if (this.codigo_cliente == 0) {
                    this.errors.push('Debe ingresar un cliente');
                }
                if (this.items.length == 0) {
                    this.errors.push('Debe ingresar al menos un item de la nota de debito');
                }

                return resultado;
            },
            creaNotaDebito() {
                this.$Progress.start();
                
                if (this.validaNotaDebito()) {
                    axios.post('api/notadebito', {
                        codigo_cliente: this.codigo_cliente, 
                        fecha_nota_debito: this.fecha_nota_debito,
                        total_nota_debito: this.total,
                        obs: this.observacion,
                        items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se genero la nota de debito correctamente!'
                        });
                        this.$router.push('/notasdebito');
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
            actualizaNotaDebito() {
                this.$Progress.start();
                
                if (this.validaNotaDebito()) {
                    axios.put('api/notadebito/'+this.nota_debito_id_edicion, {
                            total_nota_debito: this.total,
                            obs: this.observacion,
                            items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se actualizo la nota de debito correctamente!'
                        });
                        this.$router.push('/notasdebito');
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
                }
                this.$Progress.finish();
            },
            cargarNotaDebito(ndCod) {
                let me = this;                
                var url = 'api/notadebito/devuelveNotaDebito/'+ndCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.nota_debito = response.datoNotaDebito;
                    me.nota_debito_detalle = response.datoNotaDebitoD;

                    //Datos ND
                    me.codigo_cliente = me.nota_debito[0].cliente_id;

                    me.fecha_nota_debito = new Date(me.nota_debito[0].fecha);
                    me.fecha_nota_debito = me.fecha_nota_debito.setDate(me.fecha_nota_debito.getDate() + 1);

                    me.estado = me.nota_debito[0].estado;

                    me.numero_nota_debito = me.nota_debito[0].numero_nota_debito;
                    me.punto_venta = me.nota_debito[0].punto_venta;

                    me.cargarCliente(me.codigo_cliente);

                    me.observacion = me.nota_debito[0].obs;

                    for (var i = 0; i < me.nota_debito_detalle.length; i++) {

                        me.items.push({ concepto_id: me.nota_debito_detalle[i].concepto_id, 
                                        codigo_concepto_descripcion: me.nota_debito_detalle[i].descripcion_concepto,
                                        importe: me.nota_debito_detalle[i].importe
                        });
                    }

                }).catch((error) => {
                    me.nota_debito = {};
                    me.nota_debito_detalle = {};
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
            this.nota_debito_id_edicion = this.$route.params.notadebitoId;

            this.cargaClientes();
            this.cargaConceptos();

            if(this.nota_debito_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarNotaDebito(this.nota_debito_id_edicion);
            }
        }
    }
</script>