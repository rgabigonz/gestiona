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
                                    <select class="form-control" v-model="codigo_cliente" @change="consultarCtaCte">
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
                                <button type="button" class="btn btn-warning float-right" @click="consultarCtaCte">
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
              <div v-for="cta_cte_cliente in cta_cte_clientes_ordenado" :key="cta_cte_cliente.cCliente" class="card">
                <div class="card-header border-light bg-primary"><b>{{ cta_cte_cliente.nCliente }}</b></div>
                <div class="card-body">

                    <!-- Debitos row -->
                    <div class="card" v-if="codigo_cliente > 0">
                        <div class="card-header border-light bg-danger">Cuenta Corriente - Deudas</div>
                        <div class="card-body">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width:55%">Nota de Venta Cliente</th>                                
                                        <th style="width:20%">Fecha</th>
                                        <th style="width:20%">Importe</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="cta_cte_nota_venta in filtroNV(cta_cte_cliente.cCliente)" :key="cta_cte_nota_venta.id">
                                        <td>{{ cta_cte_nota_venta.anio_id }} - {{ cta_cte_nota_venta.anio_actual }}</td>
                                        <td>{{ cta_cte_nota_venta.fecha | formatDate }}</td>
                                        <td>${{ cta_cte_nota_venta.total }}</td>
                                        <td>
                                            <a href="#" @click="editarModal('NV', cta_cte_nota_venta.id)">
                                                <i class="fa fa-eye"></i>
                                            </a>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total: ${{ total_nv(cta_cte_cliente.cCliente) | currency }}</b></td>
                                    </tr>                            
                                </tbody>
                            </table>

                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width:55%">Nota de Debito Cliente</th>                                
                                        <th style="width:20%">Fecha</th>
                                        <th style="width:20%">Importe</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="cta_cte_nota_debito in filtroND(cta_cte_cliente.cCliente)" :key="cta_cte_nota_debito.id">
                                        <td>{{ cta_cte_nota_debito.punto_venta }} - {{ cta_cte_nota_debito.numero_nota_debito }}</td>
                                        <td>{{ cta_cte_nota_debito.fecha | formatDate }}</td>
                                        <td v-if="cta_cte_nota_debito.precio_dolar_manual && cta_cte_nota_debito.precio_dolar_manual > 0" >${{ (cta_cte_nota_debito.total / cta_cte_nota_debito.precio_dolar_manual) | currency }}</td>
                                        <td v-else>$0</td>
                                        <td>
                                            <a href="#" @click="editarModal('ND', cta_cte_nota_debito.id)">
                                                <i class="fa fa-eye"></i>
                                            </a>                                            
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total: ${{ total_nd(cta_cte_cliente.cCliente) | currency }}</b></td>
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
                                        <th style="width:55%">Nota de Credito Cliente</th>                                
                                        <th style="width:20%">Fecha</th>
                                        <th style="width:20%">Importe</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="cta_cte_nota_credito in filtroNC(cta_cte_cliente.cCliente)" :key="cta_cte_nota_credito.id">
                                        <td>{{ cta_cte_nota_credito.punto_venta }} - {{ cta_cte_nota_credito.numero_nota_credito }}</td>
                                        <td>{{ cta_cte_nota_credito.fecha | formatDate }}</td>
                                        <td v-if="cta_cte_nota_credito.precio_dolar_manual && cta_cte_nota_credito.precio_dolar_manual > 0" >${{ (cta_cte_nota_credito.total / cta_cte_nota_credito.precio_dolar_manual) | currency }}</td>
                                        <td v-else>$0</td>                                        
                                        <td>
                                            <a href="#" @click="editarModal('NC', cta_cte_nota_credito.id)">
                                                <i class="fa fa-eye"></i>
                                            </a>                                            
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total: ${{ total_nc(cta_cte_cliente.cCliente) | currency }}</b></td>
                                    </tr>                            
                                </tbody>
                            </table>                               

                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Recibos Cliente</th>
                                        <th style="width:15%">Fecha</th>
                                        <th style="width:15%">Importe($)</th>
                                        <th style="width:15%">Importe(U$S)</th>
                                        <th style="width:10%">Dolar(Rec)</th>
                                        <th style="width:20%">Total(U$S)</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="cta_cte_recibo in filtroRec(cta_cte_cliente.cCliente)" :key="cta_cte_recibo.id">
                                        <td>{{ cta_cte_recibo.punto_venta }} - {{ cta_cte_recibo.numero_recibo }}</td>
                                        <td>{{ cta_cte_recibo.fecha | formatDate }}</td>
                                        <td>${{ cta_cte_recibo.total }}</td>
                                        <td>${{ cta_cte_recibo.total_dolares }}</td>
                                        <td>${{ cta_cte_recibo.precio_dolar_manual }}</td>
                                        <td>${{ total_recibosDolaresRecibo(cta_cte_recibo.id, cta_cte_recibo.precio_dolar_manual) | currency }}</td>
                                        <td>
                                            <a href="#" @click="editarModal('RC', cta_cte_recibo.id)">
                                                <i class="fa fa-eye"></i>
                                            </a>                                            
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Total($):</b></td>                                        
                                        <td><b>${{ total_recibos(cta_cte_cliente.cCliente) | currency }}</b></td>
                                        <td></td>
                                        <td><b>Total(U$S):</b></td>
                                        <td><b>${{ total_recibosDolares(cta_cte_cliente.cCliente) | currency }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td style="width:100%">
                                    <h4 class="text-right"><b>Saldo(U$S): {{ cta_cte_cliente.totCliente | currency }}</b></h4>
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

        <!-- Modal -->
        <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="ventanaModalLabel" aria-hidden="true">
            <div style="min-width: 45%" class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ventanaModalLabel">Detalle Comprobante</h5>
                        <button type="button" class="close"  @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo Comprobante</label>
                                    <h4>{{ tipo_comprobante }}</h4>
                                </div>
                            </div>
                            <div class="col col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Numero Comprobante</label>
                                    <div>{{ anio_id_comprobante }} - {{ anio_actual_comprobante }}</div>
                                </div>
                            </div>
                            <div class="col col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Fecha Comprobante</label>
                                    <div class="input-group input-group-sm">
                                        <datepicker :bootstrap-styling="true" v-model="fecha_comprobante" name="fecha_comprobante" :language="es" 
                                            :format="formato_fecha_comprobante" inputClass="form-control form-control-sm" placeholder="Fecha">
                                        </datepicker>
                                    </div>                                    
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <table class="table table-striped table-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th style="width:40%">Detalle</th>
                                        <th style="width:10%">Cantidad</th>
                                        <th style="width:10%">Importe</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px;">
                                    <tr class="item" v-for="(item) in items_comprobante" :key="item.cod">
                                        <td><b>{{ item.descripcion }}</b> / {{ item.descripcion_larga}}</td>
                                        <td>{{ item.cantidad }}</td>
                                        <td>${{ item.precio }}</td>
                                    </tr>
                                </tbody>                                
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
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
                usa_fecha: false,
                codigo_cliente: 0,
                cliente: {},
                cta_cte_notas_venta: {},
                cta_cte_notas_debito: {},     
                cta_cte_notas_credito: {},           
                cta_cte_recibos: {},
                cta_cte_recibos_detalle: {},
                cta_cte_clientes: {},
                cta_cte_clientes_ordenado: [],
                comprobante: {},
                comprobante_detalle: {},
                items_comprobante: [],
                fecha_comprobante: new Date(),
                formato_fecha_comprobante: "dd-MM-yyyy",
                anio_id_comprobante: 0,
                anio_actual_comprobante: 0,
                tipo_comprobante: ''

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
            filtroNC(cCliente) {
                return this.cta_cte_notas_credito.filter(function(nc) {
                    return nc.cliente_id == cCliente;
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
                    me.cta_cte_recibos_detalle = response.ctacte_recibos_detalle;
                    me.cta_cte_notas_venta = response.ctacte_notas_venta;
                    me.cta_cte_notas_debito = response.ctacte_notas_debito;
                    me.cta_cte_notas_credito = response.ctacte_notas_credito;
                    me.cta_cte_clientes = response.ctacte_clientes;

                    this.cta_cte_clientes_ordenado = [];
                    
                    for (var i_ordenado = 0; i_ordenado < this.cta_cte_clientes.length; i_ordenado++) {
                        var lTotalNV = 0;
                        var lTotalND = 0;
                        var lTotalNC = 0;
                        var lTotalRecibo = 0;
                        var lTotalCliente = 0;

                        for (var i = 0; i < this.cta_cte_notas_venta.length; i++) {
                            if(this.cta_cte_notas_venta[i].cliente_id == this.cta_cte_clientes[i_ordenado].id) 
                                lTotalNV += parseFloat(this.cta_cte_notas_venta[i].total);
                        }

                        for (var i = 0; i < this.cta_cte_notas_debito.length; i++) {
                            if(this.cta_cte_notas_debito[i].cliente_id == this.cta_cte_clientes[i_ordenado].id) 
                                if (this.cta_cte_notas_debito[i].precio_dolar_manual && this.cta_cte_notas_debito[i].precio_dolar_manual > 0) {
                                    lTotalND += parseFloat(this.cta_cte_notas_debito[i].total) / parseFloat(this.cta_cte_notas_debito[i].precio_dolar_manual);
                                }                    
                        }  

                        // Nuevo
                        lTotalRecibo = parseFloat(this.total_recibosDolares(this.cta_cte_clientes[i_ordenado].id));
                        // Nuevo

                        for (var i = 0; i < this.cta_cte_notas_credito.length; i++) {
                            if(this.cta_cte_notas_credito[i].cliente_id == this.cta_cte_clientes[i_ordenado].id) 
                                if (this.cta_cte_notas_credito[i].precio_dolar_manual && this.cta_cte_notas_credito[i].precio_dolar_manual > 0) {
                                    lTotalNC += parseFloat(this.cta_cte_notas_credito[i].total) / parseFloat(this.cta_cte_notas_credito[i].precio_dolar_manual);
                                }                    
                        } 

                        if (!lTotalRecibo)
                            lTotalRecibo = 0;

                        lTotalCliente = parseFloat((lTotalNV + lTotalND) - (lTotalRecibo + lTotalNC));

                        this.cta_cte_clientes_ordenado.push({ cCliente: this.cta_cte_clientes[i_ordenado].id, 
                                                            nCliente: this.cta_cte_clientes[i_ordenado].nombre, 
                                                            totCliente: lTotalCliente});
                    }

                    this.cta_cte_clientes_ordenado.sort(function (a, b) {
                        if (a.totCliente < b.totCliente) {
                            return 1;
                        }
                        if (a.totCliente > b.totCliente) {
                            return -1;
                        }
                        return 0;
                    });


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
                var lTotalDolaresD = 0;

                for (var i = 0; i < this.cta_cte_recibos.length; i++) {
                    if(this.cta_cte_recibos[i].cliente_id == cCliente) {
                        lTotalDolaresD = 0;

                        for (var j = 0; j < this.cta_cte_recibos_detalle.length; j++) {
                            if(this.cta_cte_recibos_detalle[j].recibo_id == this.cta_cte_recibos[i].id) {
                                if (this.cta_cte_recibos_detalle[j].tipo_pago == 'CH') {
                                    if (this.cta_cte_recibos_detalle[j].precio_dolar_cheque > 0 && this.cta_cte_recibos_detalle[j].precio_dolar_cheque) {
                                        lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe) / parseFloat(this.cta_cte_recibos_detalle[j].precio_dolar_cheque);
                                    } else {
                                        if (this.cta_cte_recibos[i].precio_dolar_manual > 0 && this.cta_cte_recibos[i].precio_dolar_manual) 
                                            lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe) / parseFloat(this.cta_cte_recibos[i].precio_dolar_manual);
                                    }
                                } else if (this.cta_cte_recibos_detalle[j].tipo_pago == 'ED') {
                                    lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe);                                    
                                } else {
                                    if (this.cta_cte_recibos[i].precio_dolar_manual > 0 && this.cta_cte_recibos[i].precio_dolar_manual) 
                                        lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe) / parseFloat(this.cta_cte_recibos[i].precio_dolar_manual);
                                }
                            }
                        }

                        lTotalDolares += lTotalDolaresD;
                    }
                }  
                
                if (lTotalDolares)
                    return parseFloat(lTotalDolares);
                else    
                    return 0;
            },
            total_recibosDolaresRecibo(cRecibo, pDolarManual) {
                var lTotalDolaresD = 0;

                for (var j = 0; j < this.cta_cte_recibos_detalle.length; j++) {
                    if(this.cta_cte_recibos_detalle[j].recibo_id == cRecibo) {
                        if (this.cta_cte_recibos_detalle[j].tipo_pago == 'CH') {
                            if (this.cta_cte_recibos_detalle[j].precio_dolar_cheque > 0 && this.cta_cte_recibos_detalle[j].precio_dolar_cheque) {
                                lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe) / parseFloat(this.cta_cte_recibos_detalle[j].precio_dolar_cheque);
                            } else {
                                if (pDolarManual > 0 && pDolarManual) 
                                    lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe) / parseFloat(pDolarManual);
                            }
                        } else if (this.cta_cte_recibos_detalle[j].tipo_pago == 'ED') {
                            lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe);
                        } else {
                            if (pDolarManual > 0 && pDolarManual) 
                                lTotalDolaresD += parseFloat(this.cta_cte_recibos_detalle[j].importe) / parseFloat(pDolarManual);
                        }
                    }
                }
                if (lTotalDolaresD)
                    return parseFloat(lTotalDolaresD);
                else    
                    return 0;
            },
            saldo_ctacte(cCliente) {
                var lTotalNV = 0;
                var lTotalND = 0;
                var lTotalNC = 0;
                var lTotalRecibo = 0;

                for (var i = 0; i < this.cta_cte_notas_venta.length; i++) {
                    if(this.cta_cte_notas_venta[i].cliente_id == cCliente) 
                        lTotalNV += parseFloat(this.cta_cte_notas_venta[i].total);
                }

                for (var i = 0; i < this.cta_cte_notas_debito.length; i++) {
                    if(this.cta_cte_notas_debito[i].cliente_id == cCliente) 
                        if (this.cta_cte_notas_debito[i].precio_dolar_manual && this.cta_cte_notas_debito[i].precio_dolar_manual > 0) {
                            lTotalND += parseFloat(this.cta_cte_notas_debito[i].total) / parseFloat(this.cta_cte_notas_debito[i].precio_dolar_manual);
                        }                    
                }  

                // Nuevo
                lTotalRecibo = parseFloat(this.total_recibosDolares(cCliente));
                // Nuevo

                for (var i = 0; i < this.cta_cte_notas_credito.length; i++) {
                    if(this.cta_cte_notas_credito[i].cliente_id == cCliente) 
                        if (this.cta_cte_notas_credito[i].precio_dolar_manual && this.cta_cte_notas_credito[i].precio_dolar_manual > 0) {
                            lTotalNC += parseFloat(this.cta_cte_notas_credito[i].total) / parseFloat(this.cta_cte_notas_credito[i].precio_dolar_manual);
                        }                    
                } 

                if (!lTotalRecibo)
                    lTotalRecibo = 0;

                return parseFloat((lTotalNV + lTotalND) - (lTotalRecibo + lTotalNC));
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
            },
            total_nc(cCliente) {
                var lTotal = 0;

                for (var i = 0; i < this.cta_cte_notas_credito.length; i++) {
                    if(this.cta_cte_notas_credito[i].cliente_id == cCliente) 
                        if (this.cta_cte_notas_credito[i].precio_dolar_manual && this.cta_cte_notas_credito[i].precio_dolar_manual > 0) {
                            lTotal += parseFloat(this.cta_cte_notas_credito[i].total) / parseFloat(this.cta_cte_notas_credito[i].precio_dolar_manual);
                        }
                }  
              
                if (lTotal)
                    return parseFloat(lTotal);
                else    
                    return 0;
            },
            cerrarModal() {
                $('#ventanaModal').modal('hide');
            },
            editarModal(ptipoComprobante, pnumeroComprobante) {
                this.traeComprobante(ptipoComprobante, pnumeroComprobante);

                $('#ventanaModal').modal('show');
            },
            traeComprobante(ptipoComprobante, pnumeroComprobante) {
                var url = '';
                let me = this;

                switch(ptipoComprobante) {
                    case 'NV':
                        url = 'api/notaPedido/devuelveNotaPedido/'+pnumeroComprobante; 
                        me.comprobante = {};
                        me.comprobante_detalle = {};
                        me.items_comprobante = [];

                        axios.get(url).then(data => {
                            var response = data.data;
                            me.comprobante = response.datoNotaPedido;
                            me.comprobante_detalle = response.datoNotaPedidoD;

                            me.fecha_comprobante = new Date(me.comprobante.fecha);
                            me.fecha_comprobante = me.fecha_comprobante.setDate(me.fecha_comprobante.getDate() + 1);
                            me.anio_id_comprobante = me.comprobante.anio_id;
                            me.anio_actual_comprobante = me.comprobante.anio_actual;
                            me.tipo_comprobante = 'NV';

                            for (var i = 0; i < me.comprobante_detalle.length; i++) {
                                me.items_comprobante.push({ cod: me.comprobante_detalle[i].producto_id, 
                                                            descripcion: me.comprobante_detalle[i].nombre_producto, 
                                                            descripcion_larga: me.comprobante_detalle[i].descripcion_producto, 
                                                            cantidad: me.comprobante_detalle[i].cantidad, 
                                                            precio: me.comprobante_detalle[i].precio,
                                });
                            }

                        }).catch((error) => {
                            me.comprobante = {};
                            me.comprobante_detalle = {};
                            me.items_comprobante = [];
                        });

                        break;
                    case 'ND':
                        url = 'api/notadebito/devuelveNotaDebito/'+pnumeroComprobante;
                        me.comprobante = {};
                        me.comprobante_detalle = {};
                        me.items_comprobante = [];

                        axios.get(url).then(data => {
                            var response = data.data;
                            var precio_dolar = 1;

                            me.comprobante = response.datoNotaDebito;
                            me.comprobante_detalle = response.datoNotaDebitoD;

                            me.fecha_comprobante = new Date(me.comprobante[0].fecha);
                            me.fecha_comprobante = me.fecha_comprobante.setDate(me.fecha_comprobante.getDate() + 1);
                            me.anio_id_comprobante = me.comprobante[0].punto_venta;
                            me.anio_actual_comprobante = me.comprobante[0].numero_nota_debito;
                            me.tipo_comprobante = 'ND';

                            if (me.comprobante[0].precio_dolar_manual && me.comprobante[0].precio_dolar_manual > 0) {
                                precio_dolar = me.comprobante[0].precio_dolar_manual;
                            }

                            for (var i = 0; i < me.comprobante_detalle.length; i++) {
                                me.items_comprobante.push({ cod: me.comprobante_detalle[i].concepto_id, 
                                                            descripcion: me.comprobante_detalle[i].descripcion_concepto, 
                                                            descripcion_larga: '',
                                                            cantidad: 1, 
                                                            precio: (parseFloat(me.comprobante_detalle[i].importe) / parseFloat(precio_dolar)),
                                });
                            }

                        }).catch((error) => {
                            me.comprobante = {};
                            me.comprobante_detalle = {};
                            me.items_comprobante = [];
                        });

                        break;                        
                    case 'NC':
                        url = 'api/notacredito/devuelveNotaCredito/'+pnumeroComprobante;
                        me.comprobante = {};
                        me.comprobante_detalle = {};
                        me.items_comprobante = [];

                        axios.get(url).then(data => {
                            var response = data.data;
                            var precio_dolar = 1;

                            me.comprobante = response.datoNotaCredito;
                            me.comprobante_detalle = response.datoNotaCreditoD;

                            me.fecha_comprobante = new Date(me.comprobante[0].fecha);
                            me.fecha_comprobante = me.fecha_comprobante.setDate(me.fecha_comprobante.getDate() + 1);
                            me.anio_id_comprobante = me.comprobante[0].punto_venta;
                            me.anio_actual_comprobante = me.comprobante[0].numero_nota_credito;
                            me.tipo_comprobante = 'NC';

                            if (me.comprobante[0].precio_dolar_manual && me.comprobante[0].precio_dolar_manual > 0) {
                                precio_dolar = me.comprobante[0].precio_dolar_manual;
                            }

                            for (var i = 0; i < me.comprobante_detalle.length; i++) {
                                me.items_comprobante.push({ cod: me.comprobante_detalle[i].concepto_id, 
                                                            descripcion: me.comprobante_detalle[i].descripcion_concepto, 
                                                            descripcion_larga: '',
                                                            cantidad: 1, 
                                                            precio: (parseFloat(me.comprobante_detalle[i].importe) / parseFloat(precio_dolar)),
                                });
                            }

                        }).catch((error) => {
                            me.comprobante = {};
                            me.comprobante_detalle = {};
                            me.items_comprobante = [];
                        });

                        break;                        
                    case 'RC':
                        url = 'api/recibo/devuelveRecibo/'+pnumeroComprobante;
                        me.comprobante = {};
                        me.comprobante_detalle = {};
                        me.items_comprobante = [];

                        axios.get(url).then(data => {
                            var response = data.data;
                            var precio_dolar = 1;
                            var tipo_pago = '';

                            me.comprobante = response.datoRecibo;
                            me.comprobante_detalle = response.datoReciboD;

                            me.fecha_comprobante = new Date(me.comprobante[0].fecha);
                            me.fecha_comprobante = me.fecha_comprobante.setDate(me.fecha_comprobante.getDate() + 1);
                            me.anio_id_comprobante = me.comprobante[0].punto_venta;
                            me.anio_actual_comprobante = me.comprobante[0].numero_recibo;
                            me.tipo_comprobante = 'REC';

                            if (me.comprobante[0].precio_dolar_manual && me.comprobante[0].precio_dolar_manual > 0) {
                                precio_dolar = me.comprobante[0].precio_dolar_manual;
                            }

                            for (var i = 0; i < me.comprobante_detalle.length; i++) {
                                switch(me.comprobante_detalle[i].tipo_pago) {
                                    case 'EF':                                
                                        tipo_pago = 'EFECTIVO';
                                    break;
                                    case 'ED':                                
                                        tipo_pago = 'EFECTIVO DOLARES';
                                    break;  
                                    case 'CH':                                
                                        tipo_pago = 'CHEQUE';
                                    break;  
                                    case 'RE':                                
                                        tipo_pago = 'RETENCIONES';
                                    break;    
                                    case 'TT':                                
                                        tipo_pago = 'TRANSFERENCIA CTA TERCERO';
                                    break;  
                                    case 'TP':                                
                                        tipo_pago = 'TRANSFERENCIA CTA PROPIA';
                                    break;                                                                                                                                                                               
                                    default:
                                }
                                
                                me.items_comprobante.push({ cod: me.comprobante_detalle[i].concepto_id, 
                                                            descripcion: tipo_pago, 
                                                            descripcion_larga: '',
                                                            cantidad: 1, 
                                                            precio: (parseFloat(me.comprobante_detalle[i].importe) / parseFloat(precio_dolar)),
                                });
                            }

                        }).catch((error) => {
                            me.comprobante = {};
                            me.comprobante_detalle = {};
                            me.items_comprobante = [];
                        });

                        break;                        
                    default:
                        //code block
                }    
                
                axios.get(url).then(data => {
                    var response = data.data;

                    switch(ptipoComprobante) {
                        case 'NV':
                            this.comprobante = response.datoNotaPedido;
                            this.comprobante_detalle = response.datoNotaPedidoD;
                            break;
                        case 'ND':
                            this.comprobante = response.datoNotaDebito;
                            this.comprobante_detalle = response.datoNotaDebitoD;
                            break;                        
                        case 'NC':
                            this.comprobante = response.datoNotaCredito;
                            this.comprobante_detalle = response.datoNotaCreditoD;
                            break;                        
                        case 'RC':
                            this.comprobante = response.datoRecibo;
                            this.comprobante_detalle = response.datoReciboD;
                            break;                        
                        default:
                            //code block
                    }                     

                    //Datos Recibo
                    /*me.codigo_cliente = me.recibo[0].cliente_id;

                    me.fecha_recibo = new Date(me.recibo[0].fecha);
                    me.fecha_recibo = me.fecha_recibo.setDate(me.fecha_recibo.getDate() + 1);

                    me.referencia_talonario = me.recibo[0].referencia_talonario;
                    me.precio_dolar_manual = me.recibo[0].precio_dolar_manual;

                    me.estado = me.recibo[0].estado;

                    me.numero_recibo = me.recibo[0].numero_recibo;
                    me.punto_venta = me.recibo[0].punto_venta;

                    me.cargarCliente(me.codigo_cliente);

                    for (var i = 0; i < me.recibo_detalle.length; i++) {

                        if (me.recibo_detalle[i].tipo_pago == 'CH' )
                            me.tipo_pago_descripcion = 'CHEQUE';
                        if (me.recibo_detalle[i].tipo_pago == 'EF' )
                            me.tipo_pago_descripcion = 'EFECTIVO';
                        if (me.recibo_detalle[i].tipo_pago == 'ED' )
                            me.tipo_pago_descripcion = 'EFECTIVO DOLARES';

                        me.items.push({ tipo_pago: me.recibo_detalle[i].tipo_pago, 
                                        tipo_pago_descripcion: me.tipo_pago_descripcion,
                                        banco_id: me.recibo_detalle[i].banco_id, 
                                        codigo_banco_nombre: me.recibo_detalle[i].nombre_banco,
                                        fecha_cobro_cheque: me.recibo_detalle[i].fecha_cobro_cheque,
                                        numero_cheque: me.recibo_detalle[i].numero_cheque,
                                        importe: me.recibo_detalle[i].importe
                        });
                    }*/

                }).catch((error) => {
                    me.recibo = {};
                    me.recibo_detalle = {};
                });                
            }
        },
        created() {
            this.cargaClientes();
            this.consultarCtaCte();
        }
    }
</script>