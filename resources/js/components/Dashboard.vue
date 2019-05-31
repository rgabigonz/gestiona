<template>
<main class="main">
    <div class="container-fluid">
        <div class="card card-info">
            <!-- <div class="card-header">
                
            </div> -->
            <div class="car-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BAR CHART -->
                        <div class="card card-success">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 alert alert-success" role="alert">
                                        <h3>DOLAR OFICIAL (BCRA) al: {{  cotizacion_oficial['d'] | formatDate}} / ${{  cotizacion_oficial['v'] }}</h3>
                                    </div>
                                    <div v-if="!cotizacion_oficial['d']" class="col-6 alert alert-info" role="alert">
                                        <h3>Consultando cotizacion...</h3>
                                    </div>
                                    <!-- <div v-if="cotizacion_oficial['d']" class="col-3 alert alert-info" role="alert">
                                        <h3>Boton Refrescar</h3>
                                    </div>
                                    <div v-if="cotizacion_oficial['d']" class="col-3 alert alert-info" role="alert">
                                        <h3>Boton Guardar</h3>
                                    </div> -->
                                </div>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->   
                    </div>
                </div>     

                <div class="row">
                    <div class="col-md-6">
                        <!-- BAR CHART -->
                        <div class="card card-success">

                            <div class="card-header">
                                <h3 class="card-title">Compras</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="compras" style="height: 352px; width: 605px;" width="605" height="352"></canvas>
                                </div>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->   
                    </div>
                    <div class="col-md-6">
                        <!-- BAR CHART -->
                        <div class="card card-success">

                            <div class="card-header">
                                <h3 class="card-title">Ventas</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="ventas" style="height: 352px; width: 605px;" width="605" height="352"></canvas>
                                </div>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->                            
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
</template>
<script>
    export default {
        data (){
            return {
                varCompra: null,
                charCompra: null,
                compras:[],
                varTotalCompra:[],
                varMesCompra:[], 
                
                varVenta: null,
                charVenta: null,
                ventas:[],
                varTotalVenta:[],
                varMesVenta:[],
                cotizacion_oficial: {}
            }
        },
        methods : {
            getCompras(){
                let me = this;
                var url = 'api/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.compras = respuesta.compras;
                    //cargamos los datos del chart
                    me.loadCompras();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentas(){
                let me = this;
                var url = 'api/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.ventas = respuesta.ventas;
                    //cargamos los datos del chart
                    me.loadVentas();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getCotizacion(){
                let me = this;
                var url = 'api/cotizacion/devuelveCotizacion';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.cotizacion_oficial = respuesta.cotizacion_oficial;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            loadCompras(){
                let me = this;
                me.compras.map(function(x){
                    me.varMesCompra.push(x.mes);
                    me.varTotalCompra.push(x.total);
                });
                me.varCompra = document.getElementById('compras').getContext('2d');

                me.charCompra = new Chart(me.varCompra, {
                    type: 'bar',
                    data: {
                        labels: me.varMesCompra,
                        datasets: [{
                            label: 'Compras x mes',
                            data: me.varTotalCompra,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadVentas(){
                let me = this;
                me.ventas.map(function(x){
                    me.varMesVenta.push(x.mes);
                    me.varTotalVenta.push(x.total);
                });
                me.varVenta = document.getElementById('ventas').getContext('2d');

                me.charVenta = new Chart(me.varVenta, {
                    type: 'bar',
                    data: {
                        labels: me.varMesVenta,
                        datasets: [{
                            label: 'Ventas x mes',
                            data: me.varTotalVenta,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            }
        },
        mounted() {
            this.getCompras();
            this.getVentas();
            this.getCotizacion();
        }
    }
</script>
