<template>
    <div class="container-fluid">
        <div class="row mt-5">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                    <h4>Cuentas Corrientes</h4>
                </div>
                <!-- /.col -->
              </div>

              <br>

              <!-- Cliente row -->
              <div class="card">
                <div class="card-header border-light bg-success">Filtros</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
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

                        <div class="col-sm-3 invoice-col">
                            <label class="control-label">Fecha Desde</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_cuenta_corriente_desde" name="fecha_cuenta_corriente_desde" :language="es" 
                                        :format="formato_fecha_cuenta_corriente" inputClass="form-control form-control-sm" placeholder="Fecha">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-3 invoice-col">
                            <label class="control-label">Fecha Hasta</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_cuenta_corriente_hasta" name="fecha_cuenta_corriente_hasta" :language="es" 
                                        :format="formato_fecha_cuenta_corriente" inputClass="form-control form-control-sm" placeholder="Fecha">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                    </div>
                </div>

              </div>
              <!-- /.row -->

              <!-- Debitos row -->
              <div class="card">
                <div class="card-header border-light bg-danger">Cuenta Corriente - Deudas</div>
                <div class="card-body">              
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="width:60%">Nota de Venta Cliente</th>                                
                                <th style="width:20%">Fecha</th>
                                <th style="width:20%">Importe</th>
                                <!-- <th style="width:10%"></th> -->
                            </tr>

                        </thead>
                        <tbody style="font-size: 12px">
                            <tr v-for="cta_cte_nota_venta in cta_cte_notas_venta" :key="cta_cte_nota_venta.id">
                                <td>{{ cta_cte_nota_venta.anio_id }} - {{ cta_cte_nota_venta.anio_actual }}</td>
                                <td>{{ cta_cte_nota_venta.fecha | formatDate }}</td>
                                <td>${{ cta_cte_nota_venta.total }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><b>Total: ${{ total_nv | currency }}</b></td>
                            </tr>                            
                        </tbody>
                    </table>
                </div>
              </div>

              <!-- Creditos row -->
              <div class="card">
                <div class="card-header border-light bg-info">Cuenta Corriente - Pagos</div>
                <div class="card-body">              
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="width:20%">Recibos Cliente</th>
                                <th style="width:20%">Fecha</th>
                                <th style="width:20%">Importe($)</th>
                                <th style="width:20%">Dolar</th>
                                <th style="width:20%">Importe(U$S)</th>
                                <!-- <th style="width:10%"></th>                                 -->
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px">
                            <tr v-for="cta_cte_recibo in cta_cte_recibos" :key="cta_cte_recibo.id">
                                <td>{{ cta_cte_recibo.punto_venta }} - {{ cta_cte_recibo.numero_recibo }}</td>
                                <td>{{ cta_cte_recibo.fecha | formatDate }}</td>
                                <td>${{ cta_cte_recibo.total }}</td>
                                <td>${{ cta_cte_recibo.precio_dolar_manual }}</td>
                                <td v-if="cta_cte_recibo.precio_dolar_manual && cta_cte_recibo.precio_dolar_manual > 0" >${{ (cta_cte_recibo.total / cta_cte_recibo.precio_dolar_manual) | currency }}</td>
                                <td v-else>$0</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><b>Total: ${{ total_recibos | currency }}</b></td>
                                <td></td>
                                <td><b>Total: ${{ total_recibosDolares | currency }}</b></td>
                            </tr>
                        </tbody>
                    </table>
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
                <button type="button" class="btn btn-warning float-right" @click="consultarCtaCte()">
                    <i class="fa fa-save fa-fw"></i> Consultar
                </button>
            </div>
        </div>

      </div>
</template>

<script>
    import Datepicker from "vuejs-datepicker/dist/vuejs-datepicker.esm.js";
    import {en, es} from 'vuejs-datepicker/dist/locale';

    import moment from 'moment';

    export default {
        components: {
            Datepicker
        },        
        data() {
            return {
                //Lista de Seleccion clientes y productos
                lclientes: {},

                fecha_cuenta_corriente_desde: new Date(),
                fecha_cuenta_corriente_hasta: new Date(),
                formato_fecha_cuenta_corriente: "dd-MM-yyyy",
                es: es,
                cliente: {},
                codigo_cliente: 0,
                cta_cte_notas_venta: {},
                cta_cte_recibos: {}
            }
        },
        methods: {
            consultarCtaCte() {
                let me = this;         
                var lfechaD = moment(this.fecha_cuenta_corriente_desde).format('YYYY-MM-DD');
                var lfechaH = moment(this.fecha_cuenta_corriente_hasta).format('YYYY-MM-DD');

                var url = 'api/ctactecliente/devuelveCtaCte?cliente=' + this.codigo_cliente + '&fechaD=' + lfechaD + '&fechaH=' + lfechaH
                axios.get(url).then(data => {
                    var response = data.data;
                    me.cta_cte_recibos = response.ctacte_recibos;
                    me.cta_cte_notas_venta = response.ctacte_notas_venta;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
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
            }
        },
        computed: {
            total_recibosDolares() {
                var lTotalDolares = 0;

                for (var i = 0; i < this.cta_cte_recibos.length; i++) {
                    if (this.cta_cte_recibos[i].precio_dolar_manual && this.cta_cte_recibos[i].precio_dolar_manual > 0) {
                        lTotalDolares += parseFloat(this.cta_cte_recibos[i].total) / parseFloat(this.cta_cte_recibos[i].precio_dolar_manual);
                    }
                }  
                
                if (lTotalDolares)
                    return parseFloat(lTotalDolares);
                else    
                    return 0;
            },
            total_recibos() {
                var lTotal = 0;

                for (var i = 0; i < this.cta_cte_recibos.length; i++) {
                    lTotal += parseFloat(this.cta_cte_recibos[i].total);
                }  
                
                return parseFloat(lTotal);
            },
            total_nv() {
                var lTotal = 0;

                for (var i = 0; i < this.cta_cte_notas_venta.length; i++) {
                    lTotal += parseFloat(this.cta_cte_notas_venta[i].total);
                }  
                
                return parseFloat(lTotal);
            }                       
        },
        created() {
            this.cargaClientes();
        }
    }
</script>