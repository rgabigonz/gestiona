<template>
    <div class="container-fluid">
        <div class="row mt-5">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                    <h4>Stock Productos</h4>
                </div>
                <!-- /.col -->
              </div>

              <br>

              <!-- Deposito row -->
              <div class="card">
                <div class="card-header border-light bg-success">Filtros</div>
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-sm-5 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <select class="form-control" v-model="codigo_deposito" @change="consultarStock(codigo_deposito, codigo_producto)">
                                        <option value=0>Deposito...</option>
                                        <option v-for="ldeposito in ldepositos" :key="ldeposito.id" :value="ldeposito.id">{{ ldeposito.descripcion }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-5 invoice-col">
                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <select class="form-control" v-model="codigo_producto" @change="consultarStock(codigo_deposito, codigo_producto)">
                                        <option value=0>Producto...</option>
                                        <option v-for="lproducto in lproductos" :key="lproducto.id" :value="lproducto.id">{{ lproducto.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-2 invoice-col">
                            <div class="form-group">
                                <button type="button" class="btn btn-warning float-right" @click="consultarStock(codigo_deposito, codigo_producto)">
                                    <i class="fa fa-save fa-fw"></i> Actualizar
                                </button>                                
                            </div>
                        </div>
                        <!-- /.col -->
                        
                    </div>
                </div>

              </div>
              <!-- /.row -->

              <!-- Depositos row -->
              <div v-for="stock_deposito in stock_depositos" :key="stock_deposito.id" class="card">
                <div class="card-header border-light bg-primary"><b>{{ stock_deposito.descripcion }}</b></div>
                <div class="card-body">

                    <!-- Productos row -->
                    <div class="card" v-if="codigo_deposito > 0">
                        <div class="card-header border-light bg-danger">Stock - Productos</div>
                        <div class="card-body">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width:60%">Producto</th>                                
                                        <th style="width:40%">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr v-for="stock_producto in filtroProducto(stock_deposito.id)" :key="stock_producto.id">
                                        <td>{{ stock_producto.nombre_producto }}</td>
                                        <td>{{ stock_producto.cantidad }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td style="width:100%">
                                    <h4 class="text-right"><b>Total Articulos: {{ stock_producto_total(stock_deposito.id) }}</b></h4>
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
    export default {
        data() {
            return {
                //Lista de Seleccion depositos y productos
                ldepositos: {},
                lproductos: {},

                codigo_deposito: 0,
                codigo_producto: 0,
                deposito: {},
                stock_depositos: {},
                stock_producto: {},                
            }
        },
        methods: {
            filtroProducto(cDeposito) {
                return this.stock_producto.filter(function(producto) {
                    return producto.deposito_id == cDeposito;
                })
            },            
            consultarStock() {
                let me = this;    

                var url = 'api/stockproducto/devuelveStock?deposito=' + me.codigo_deposito
                                                                      + '&producto=' + me.codigo_producto
                axios.get(url).then(data => {
                    var response = data.data;
                    me.stock_producto = response.stock_producto;
                    me.stock_depositos = response.stock_depositos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            // Cargo combos de seleccion            
            cargaDepositos() {
                let me = this;                
                var url = 'api/deposito/cargaDepositos';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.ldepositos = response.ldepositos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            cargaProductos() {
                let me = this;                
                var url = 'api/producto/cargaProductos';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lproductos = response.productos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            stock_producto_total(cDeposito) {
                var lTotalProductos = 0;

                for (var i = 0; i < this.stock_producto.length; i++) {
                    if(this.stock_producto[i].deposito_id == cDeposito) 
                        lTotalProductos += parseFloat(this.stock_producto[i].cantidad);
                }  

                if (!lTotalProductos)
                    lTotalProductos = 0;

                return parseFloat(lTotalProductos);
            }
        },
        created() {
            this.cargaDepositos();
            this.cargaProductos();
            this.consultarStock();
        }
    }
</script>