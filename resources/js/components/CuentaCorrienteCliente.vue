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
                        <div class="col-sm-4 invoice-col">
                            <label class="control-label">Cliente</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <select class="form-control" v-model="codigo_cliente" @change="consultarCtaCte(codigo_cliente)">
                                        <option value=0>Cliente...</option>
                                        <option v-for="lcliente in lclientes" :key="lcliente.id" :value="lcliente.id">{{ lcliente.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-2 invoice-col">
                            <label class="control-label">Fecha Desde</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_cuenta_corriente_desde" name="fecha_cuenta_corriente_desde" :language="es" 
                                        :format="formato_fecha_cuenta_corriente" inputClass="form-control form-control-sm">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-2 invoice-col">
                            <label class="control-label">Fecha Hasta</label>
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <datepicker :bootstrap-styling="true" v-model="fecha_cuenta_corriente_hasta" name="fecha_cuenta_corriente_hasta" :language="es" 
                                        :format="formato_fecha_cuenta_corriente" inputClass="form-control form-control-sm">
                                    </datepicker>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-2 invoice-col">
                            <label class="control-label">Usar fechas</label>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" v-model="usa_fecha">
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-2 invoice-col">
                            <label class="control-label"></label>
                            <div class="form-group">
                                <button type="button" class="btn btn-warning float-right" @click="consultarCtaCte(codigo_cliente)">
                                    <i class="fa fa-save fa-fw"></i> Actualizar
                                </button>                                
                            </div>
                        </div>
                        <!-- /.col -->
                        
                    </div>
                </div>

              </div>
              <!-- /.row -->

              <!-- Clientes row -->
              <div v-for="cta_cte_cliente in cta_cte_clientes" :key="cta_cte_cliente.id" class="card">
                <div class="card-header border-light bg-primary"><b>{{ cta_cte_cliente.nombre }}</b></div>
                <div class="card-body">

                    <!-- Debitos row -->
                    <div class="card" v-if="codigo_cliente > 0">
                        <div class="card-header border-light bg-danger">Cuenta Corriente - Deudas</div>
                        <div class="card-body">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width:60%">Nota de Venta Cliente</th>                                
                                        <th style="width:20%">Fecha</th>
                                        <th style="width:20%">Importe</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="cta_cte_nota_venta in filtroNV(cta_cte_cliente.id)" :key="cta_cte_nota_venta.id">
                                        <td>{{ cta_cte_nota_venta.anio_id }} - {{ cta_cte_nota_venta.anio_actual }}</td>
                                        <td>{{ cta_cte_nota_venta.fecha | formatDate }}</td>
                                        <td>${{ cta_cte_nota_venta.total }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total: ${{ total_nv(cta_cte_cliente.id) | currency }}</b></td>
                                    </tr>                            
                                </tbody>
                            </table>

                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width:60%">Nota de Debito Cliente</th>                                
                                        <th style="width:20%">Fecha</th>
                                        <th style="width:20%">Importe</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="cta_cte_nota_debito in filtroND(cta_cte_cliente.id)" :key="cta_cte_nota_debito.id">
                                        <td>{{ cta_cte_nota_debito.punto_venta }} - {{ cta_cte_nota_debito.numero_nota_debito }}</td>
                                        <td>{{ cta_cte_nota_debito.fecha | formatDate }}</td>
                                        <td v-if="cta_cte_nota_debito.precio_dolar_manual && cta_cte_nota_debito.precio_dolar_manual > 0" >${{ (cta_cte_nota_debito.total / cta_cte_nota_debito.precio_dolar_manual) | currency }}</td>
                                        <td v-else>$0</td>                                        
                                        <!-- <td>${{ cta_cte_nota_debito.total }}</td> -->
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total: ${{ total_nd(cta_cte_cliente.id) | currency }}</b></td>
                                    </tr>                            
                                </tbody>
                            </table>                            
                        </div>
                    </div>

                    <!-- Creditos row -->
                    <div class="card" v-if="codigo_cliente > 0">
                        <div class="card-header border-light bg-info">Cuenta Corriente - Pagos</div>
                        <div class="card-body">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Recibos Cliente</th>
                                        <th style="width:20%">Fecha</th>
                                        <th style="width:15%">Importe($)</th>
                                        <th style="width:15%">Importe(U$S)</th>
                                        <th style="width:10%">Dolar</th>
                                        <th style="width:20%">Total(U$S)</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="cta_cte_recibo in filtroRec(cta_cte_cliente.id)" :key="cta_cte_recibo.id">
                                        <td>{{ cta_cte_recibo.punto_venta }} - {{ cta_cte_recibo.numero_recibo }}</td>
                                        <td>{{ cta_cte_recibo.fecha | formatDate }}</td>
                                        <td>${{ cta_cte_recibo.total }}</td>
                                        <td>${{ cta_cte_recibo.total_dolares }}</td>
                                        <td>${{ cta_cte_recibo.precio_dolar_manual }}</td>
                                        <td v-if="cta_cte_recibo.precio_dolar_manual && cta_cte_recibo.precio_dolar_manual > 0" >${{ (cta_cte_recibo.total / cta_cte_recibo.precio_dolar_manual) + cta_cte_recibo.total_dolares | currency }}</td>
                                        <td v-else-if="cta_cte_recibo.total_dolares && cta_cte_recibo.total_dolares > 0" >${{ cta_cte_recibo.total_dolares | currency }}</td>                                        
                                        <td v-else>$0</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Total($):</b></td>                                        
                                        <td><b>${{ total_recibos(cta_cte_cliente.id) | currency }}</b></td>
                                        <td></td>
                                        <td><b>Total(U$S):</b></td>
                                        <td><b>${{ total_recibosDolares(cta_cte_cliente.id) | currency }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                    <table class="table table-sm">
                        <tbody>
                            <!-- <thead>
                                <tr>
                                    <th style="width:100%">Saldo(U$S)</th>
                                </tr>
                            </thead>                             -->
                            <tr>
                                <td style="width:100%">
                                    <h4 class="text-right"><b>Saldo(U$S): {{ saldo_ctacte(cta_cte_cliente.id) | currency }}</b></h4>
                                </td>
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
                usa_fecha: false,
                codigo_cliente: 0,
                cliente: {},
                cta_cte_notas_venta: {},
                cta_cte_notas_debito: {},                
                cta_cte_recibos: {},
                cta_cte_clientes: {}
            }
        },
        methods: {
            filtroNV(cCliente) {
                return this.cta_cte_notas_venta.filter(function(nv) {
                    return nv.cliente_id == cCliente;
                })
            },
            filtroND(cCliente) {
                return this.cta_cte_notas_debito.filter(function(nd) {
                    return nd.cliente_id == cCliente;
                })
            },            
            filtroRec(cCliente) {
                return this.cta_cte_recibos.filter(function(rec) {
                    return rec.cliente_id === cCliente;
                })
            },            
            consultarCtaCte() {
                let me = this;    

                var lfechaD = moment(me.fecha_cuenta_corriente_desde).format('YYYY-MM-DD');
                var lfechaH = moment(me.fecha_cuenta_corriente_hasta).format('YYYY-MM-DD');
                var lusaFecha = 0;

                if(me.usa_fecha == true)
                    lusaFecha = 1;

                var url = 'api/ctactecliente/devuelveCtaCte?cliente=' + me.codigo_cliente
                                                                      + '&fechaD=' + lfechaD
                                                                      + '&fechaH=' + lfechaH
                                                                      + '&usaFecha=' + lusaFecha
                axios.get(url).then(data => {
                    var response = data.data;
                    me.cta_cte_recibos = response.ctacte_recibos;
                    me.cta_cte_notas_venta = response.ctacte_notas_venta;
                    me.cta_cte_notas_debito = response.ctacte_notas_debito;
                    me.cta_cte_clientes = response.ctacte_clientes;
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
            },
            total_recibos(cCliente) {
                var lTotal = 0;

                for (var i = 0; i < this.cta_cte_recibos.length; i++) {
                    if(this.cta_cte_recibos[i].cliente_id == cCliente)
                        lTotal += parseFloat(this.cta_cte_recibos[i].total);
                }  
                
                return parseFloat(lTotal);
            },
            total_recibosDolares(cCliente) {
                var lTotalDolares = 0;

                for (var i = 0; i < this.cta_cte_recibos.length; i++) {
                    if(this.cta_cte_recibos[i].cliente_id == cCliente) {
                        if (this.cta_cte_recibos[i].precio_dolar_manual && this.cta_cte_recibos[i].precio_dolar_manual > 0) {
                            lTotalDolares += parseFloat(this.cta_cte_recibos[i].total) / parseFloat(this.cta_cte_recibos[i].precio_dolar_manual);
                        }
                        lTotalDolares += parseFloat(this.cta_cte_recibos[i].total_dolares);
                    }
                }  
                
                if (lTotalDolares)
                    return parseFloat(lTotalDolares);
                else    
                    return 0;
            },
            saldo_ctacte(cCliente) {
                var lTotalNV = 0;
                var lTotalND = 0;
                var lTotalRecibo = 0;

                for (var i = 0; i < this.cta_cte_notas_venta.length; i++) {
                    if(this.cta_cte_notas_venta[i].cliente_id == cCliente) 
                        lTotalNV += parseFloat(this.cta_cte_notas_venta[i].total);
                }

                for (var i = 0; i < this.cta_cte_notas_debito.length; i++) {
                    if(this.cta_cte_notas_debito[i].cliente_id == cCliente) 
                        if (this.cta_cte_notas_debito[i].precio_dolar_manual && this.cta_cte_notas_debito[i].precio_dolar_manual > 0) {
                            lTotalNV += parseFloat(this.cta_cte_notas_debito[i].total) / parseFloat(this.cta_cte_notas_debito[i].precio_dolar_manual);
                        }                    
                        //lTotalNV += parseFloat(this.cta_cte_notas_debito[i].total);
                }  

                for (var i = 0; i < this.cta_cte_recibos.length; i++) {
                    if(this.cta_cte_recibos[i].cliente_id == cCliente) {
                        if (this.cta_cte_recibos[i].precio_dolar_manual && this.cta_cte_recibos[i].precio_dolar_manual > 0) {
                            lTotalRecibo += parseFloat(this.cta_cte_recibos[i].total) / parseFloat(this.cta_cte_recibos[i].precio_dolar_manual);
                        }
                        lTotalRecibo += parseFloat(this.cta_cte_recibos[i].total_dolares);
                    }
                }

                if (!lTotalRecibo)
                    lTotalRecibo = 0;

                return parseFloat((lTotalNV + lTotalND) - lTotalRecibo);
            },
            total_nv(cCliente) {
                var lTotal = 0;

                for (var i = 0; i < this.cta_cte_notas_venta.length; i++) {
                    if(this.cta_cte_notas_venta[i].cliente_id == cCliente) 
                        lTotal += parseFloat(this.cta_cte_notas_venta[i].total);
                }  
                
                return parseFloat(lTotal);
            },
            total_nd(cCliente) {
                var lTotal = 0;

                for (var i = 0; i < this.cta_cte_notas_debito.length; i++) {
                    if(this.cta_cte_notas_debito[i].cliente_id == cCliente) 
                        if (this.cta_cte_notas_debito[i].precio_dolar_manual && this.cta_cte_notas_debito[i].precio_dolar_manual > 0) {
                            lTotal += parseFloat(this.cta_cte_notas_debito[i].total) / parseFloat(this.cta_cte_notas_debito[i].precio_dolar_manual);
                        }
                }  
              
                if (lTotal)
                    return parseFloat(lTotal);
                else    
                    return 0;
            }
        },
        created() {
            this.cargaClientes();
            this.consultarCtaCte();
        }
    }
</script>