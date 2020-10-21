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
                  <div>Orden de Pago N° {{ punto_venta }} - {{ numero_ordenpago }}</div>
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

              <!-- Datos OP row -->
              <div class="card">
                <div class="card-header border-light bg-secondary">Datos Orden de Pago</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_ordenpago" name="fecha_ordenpago" :language="es" 
                                        :format="formato_fecha_ordenpago" inputClass="form-control form-control-sm" placeholder="Fecha" 
                                        :disabled="modoEdicion ? true : false">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <!--<div class="col-sm-3 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="precio_dolar_manual" type="number" name="precio_dolar_manual" placeholder="Dolar" step=".1"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>-->
                        <!-- /.col -->                        

                        <!--<div class="col-sm-6 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="referencia_talonario" type="text" name="referencia_talonario" placeholder="N° Recibo Talonario"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>-->
                        <!-- /.col -->

                    </div>
                </div>
              </div>

              <!-- Cliente row -->
              <div class="card">
                <div class="card-header border-light bg-success">Datos proveedor</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
                            <label class="control-label">Proveedor</label>
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

                        <div class="col-sm-6 invoice-col">
                            <label class="control-label">CUIT</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input v-model="proveedor.numero_documento" type="text" name="numero_documento" class="form-control form-control-sm" disabled>
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
                <div class="card-header border-light bg-dark">Items Orden de Pago</div>
                <div class="card-body">              
                    <div class="row invoice-info">
                        <div class="col-12 invoice-col">
                        <table class="table table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th style="width:80%">Tipo Pago</th>
                                    <!--<th style="width:30%">Banco</th>
                                    <th style="width:10%">Fecha Cobro</th>
                                    <th style="width:15%">N° Cheque</th>-->
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
                                    
                                    <!--<td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" v-model="codigo_banco" :disabled="tipo_pago.id != 'CH' ? true : false">
                                                    <option value=0>Banco...</option>
                                                    <option v-for="lbanco in lbancos" :key="lbanco.id" :value="{ id: lbanco.id, text: lbanco.nombre }">{{ lbanco.nombre }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>-->
                                    <!-- /.col -->

                                    <!--<td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="fecha_cobro_cheque" type="date" name="fecha_cobro_cheque"
                                                    class="form-control form-control-sm" :disabled="tipo_pago.id != 'CH' ? true : false">
                                            </div>
                                        </div>
                                    </td>-->
                                    <!-- /.col -->

                                    <!--<td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="numero_cheque" type="number" name="numero_cheque"
                                                    class="form-control form-control-sm" :disabled="tipo_pago.id != 'CH' ? true : false">
                                            </div>
                                        </div>
                                    </td>-->
                                    <!-- /.col -->

                                    <td class="invoice-col">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input v-model="importe_item_ordenpago" type="number" name="importe_item_ordenpago"  step=".1"
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
                                    <!--<td>{{ item.codigo_banco_nombre }}</td>
                                    <td>{{ item.fecha_cobro_cheque | formatDate }}</td>
                                    <td>{{ item.numero_cheque }}</td>-->
                                    <td>${{ item.importe }}</td>
                                    <td>
                                        <a href="#" @click="removerItemOrdenPago(index)">
                                            <i class="fa fa-trash-alt red"></i>
                                        </a>                            
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>Total:</td>
                                    <td>${{ total | currency }}</td>
                                    <!--<td>U$S{{ total_dolares | currency }}</td>-->
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
                <button v-if="!modoEdicion" type="button" class="btn btn-success float-right" @click="creaOrdenPago()">
                    <i class="fa fa-save fa-fw"></i> Guardar
                </button>
                <button v-if="modoEdicion" type="button" class="btn btn-success float-right" @click="actualizaOrdenPago()">
                    <i class="fa fa-save fa-fw"></i> Modificar
                </button>
                <router-link v-if="sBuscarOPD" :to="{ name: 'ordenespago', params: { sBuscar: sBuscarOPD, sCriterio: sCriterioOPD }}" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fa fa-hand-point-left fa-fw"></i>Volver
                </router-link>
                <router-link v-else to="/ordenespago" class="btn btn-primary float-right" style="margin-right: 5px;">
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
                    {id: 'DP', nombre: 'DEPOSITO PROPIO'},
                    {id: 'D3', nombre: 'DEPOSITO DE TERCERO'},
                    {id: 'TP', nombre: 'TRANSFERENCIA PROPIA'},
                    {id: 'FC', nombre: 'FACTURA DE COMISIONES'},
                    {id: 'DD', nombre: 'DEPOSITO DE CHEQUE AL DIA'},
                    {id: 'DF', nombre: 'DEPOSITO DE CHEQUE A LA FECHA'},
                    {id: 'CR', nombre: 'CUENTA RECAUDADORA'}
                ],                
                //Lista de Seleccion proveedores y productos
                lproveedores: {},
                lbancos: {},
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                sCriterioOPD: '',
                sBuscarOPD: '',
                offset: 3,
                //Lista de Seleccion clientes y productos   

                // Errores
                errors: [],

                modoEdicion: false,
                estado: '',
                ordenpago_id_edicion: 0,
                fecha_ordenpago: new Date(),
                formato_fecha_ordenpago: "dd-MM-yyyy",
                es: es,
                proveedor: {},
                codigo_proveedor: 0,
                punto_venta: '',
                numero_ordenpago: 0,
                items: [],
                tipo_pago: '',
                //codigo_banco: 0,
                //fecha_cobro_cheque: '',
                //numero_cheque: 0,
                importe_item_ordenpago: 0,
                observacion:'',
                //referencia_talonario: '',
                //precio_dolar_manual: 0,
                ordenpago: {},
                ordenpago_detalle: {}
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
                    case 'importe_item_ordenpago':
                        switch(origenKey) {
                            case 'Tab':    
                                this.agregaItemOrdenPago();
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

            //Cargo datos individuales
            cargarProveedor(pCod) {
                let me = this;
                var url = 'api/proveedor/devuelveDatosProveedor/'+pCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.proveedor = response.datoProveedor;
                }).catch((error) => {
                    me.proveedor = {};
                    me.codigo_proveedor = '';

                    this.focusInput('codigo_proveedor');
                });
            },

            // Operaciones con productos
            agregaItemOrdenPago() {
                if (this.importe_item_ordenpago > 0) {

                    if (this.existeItemOrdenPago(this.tipo_pago.id, this.importe_item_ordenpago) === false) {
                        this.items.push({tipo_pago: this.tipo_pago.id, 
                                        tipo_pago_descripcion: this.tipo_pago.text, 
                                        importe: this.importe_item_ordenpago
                        });
                    }
                    this.tipo_pago = '';
                    this.importe_item_ordenpago = 0;

                } /*else {
                    toast({
                        type: 'error',
                        title: 'Debe ingresar producto, cantidad y precio'
                    });                      
                }*/
            },
            removerItemOrdenPago(index) {
                this.items.splice(index, 1);
            },
            existeItemOrdenPago(tp_id, imp) {
                this.focusInput('tipo_pago');

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].tipo_pago === tp_id) {
                            this.items[i].importe = imp;
                            return true;
                    }
                }
                return false;
            },

            // Operaciones con NP Proveedores (OC)
            validaOrdenPago() {
                var resultado = false;

                if (this.codigo_proveedor && this.items.length) {
                    resultado = true;
                }

                this.errors = [];

                if (this.codigo_proveedor == 0) {
                    this.errors.push('Debe ingresar un proveedor');
                }
                if (this.items.length == 0) {
                    this.errors.push('Debe ingresar al menos un item de orden de pago');
                }

                return resultado;
            },

            // Operaciones con Recibo
            creaOrdenPago() {
                this.$Progress.start();
                
                if (this.validaOrdenPago()) {
                    axios.post('api/ordenpago', {
                        codigo_proveedor: this.codigo_proveedor, 
                        fecha_ordenpago: this.fecha_ordenpago,
                        total_ordenpago: this.total,
                        obs: this.observacion,
                        items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se genero la orden de pago correctamente!'
                        });
                        if(this.sBuscarRED)
                            this.$router.push('/ordenespago/'+this.sBuscarOPD+'/'+this.sCriterioOPD);
                        else
                            this.$router.push('/ordenespago');
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
            actualizaOrdenPago() {
                this.$Progress.start();
                
                if (this.validaOrdenPago()) {
                    axios.put('api/ordenpago/'+this.ordenpago_id_edicion, {
                            total_ordenpago: this.total,
                            obs: this.observacion,
                            items: this.items})
                    .then(() => {
                        Fire.$emit('AfterAction');
                        toast({
                            type: 'success',
                            title: 'Se actualizo la orden de pago correctamente!'
                        });
                        if(this.sBuscarRED)
                            this.$router.push('/ordenespago/'+this.sBuscarOPD+'/'+this.sCriterioOPD);
                        else
                            this.$router.push('/ordenespago');
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
                }
                this.$Progress.finish();
            },
            cargarOrdenPago(opCod) {
                let me = this;                
                var url = 'api/ordenpago/devuelveOrdenPago/'+opCod;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.ordenpago = response.datoOrdenPago;
                    me.ordenpago_detalle = response.datoOrdenPagoD;

                    //Datos Recibo
                    me.codigo_proveedor = me.ordenpago[0].proveedor_id;

                    me.fecha_ordenpago = new Date(me.ordenpago[0].fecha);
                    me.fecha_ordenpago = me.fecha_ordenpago.setDate(me.fecha_ordenpago.getDate() + 1);

                    me.estado = me.ordenpago[0].estado;

                    me.numero_ordenpago = me.ordenpago[0].id;
                    me.punto_venta = me.ordenpago[0].punto_venta;
                    
                    me.observacion = me.ordenpago[0].obs;

                    me.cargarProveedor(me.codigo_proveedor);

                    for (var i = 0; i < me.ordenpago_detalle.length; i++) {

                        if (me.ordenpago_detalle[i].tipo_pago == 'DP' )
                            me.tipo_pago_descripcion = 'DEPOSITO PROPIO';
                        if (me.ordenpago_detalle[i].tipo_pago == 'D3' )
                            me.tipo_pago_descripcion = 'DEPOSITO DE TERCERO';
                        if (me.ordenpago_detalle[i].tipo_pago == 'TP' )
                            me.tipo_pago_descripcion = 'TRANSFERENCIA PROPIA';
                        if (me.ordenpago_detalle[i].tipo_pago == 'FC' )
                            me.tipo_pago_descripcion = 'FACTURA DE COMISIONE';
                        if (me.ordenpago_detalle[i].tipo_pago == 'DD' )
                            me.tipo_pago_descripcion = 'DEPOSITO DE CHEQUE AL DIA';
                        if (me.ordenpago_detalle[i].tipo_pago == 'DF' )
                            me.tipo_pago_descripcion = 'DEPOSITO DE CHEQUE A LA FECHA';
                        if (me.ordenpago_detalle[i].tipo_pago == 'CR' )
                            me.tipo_pago_descripcion = 'CUENTA RECAUDADORA';

                        me.items.push({ tipo_pago: me.ordenpago_detalle[i].tipo_pago, 
                                        tipo_pago_descripcion: me.tipo_pago_descripcion,
                                        importe: me.ordenpago_detalle[i].importe
                        });
                    }

                }).catch((error) => {
                    me.ordenpago = {};
                    me.ordenpago_detalle = {};
                });
            }
        },
        computed: {
            total() {
                var lTotal = 0;

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].tipo_pago != 'ED') {
                        lTotal += (parseFloat(this.items[i].importe));
                    }
                }  
                
                return parseFloat(lTotal);
            },
            total_dolares() {
                var lTotalDolares = 0;

                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i].tipo_pago == 'ED') {
                        lTotalDolares += (parseFloat(this.items[i].importe));
                    }
                }  
                
                return parseFloat(lTotalDolares);
            }
        },
        created() {
            this.ordenpago_id_edicion = this.$route.params.ordenpagoId;

            if (this.$route.params.sBuscarOPD) {
                this.sBuscarOPD = this.$route.params.sBuscarOPD
                this.sCriterioOPD = this.$route.params.sCriterioOPD
            }

            this.cargaProveedores();

            if(this.ordenpago_id_edicion > 0) {
                this.modoEdicion = true;
                this.cargarOrdenPago(this.ordenpago_id_edicion);
            }
        }
    }
</script>