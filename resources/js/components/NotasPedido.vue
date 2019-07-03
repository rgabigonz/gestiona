<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-light">Busqueda
                    <div class="card-tools">
                        <button class="btn btn-secondary" @click="cargarNotasPedido(1, sBuscar, sCriterio)"><i class="fas fa-search fa-fw"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-4">
                            <select class="form-control" v-model="sCriterio">
                                <option value="notas_pedidos.id">Numero Nota de Venta</option>
                                <option value="clientes.nombre">Cliente</option>
                                <option value="vendedores.nombre">Distribuidor</option>
                            </select>
                        </div>
                        <div class="col col-md-8">
                            <input v-model="sBuscar" @keyup.enter="cargarNotasPedido(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-info mb-3">
              <div class="card-header border-light">
                <h3 class="card-title">Notas de Venta</h3>
                <div class="card-tools">
                    <router-link :to="{ name: 'notaspedidodetalle', params: { notaspedidoId: 0 } }" class="btn btn-success">
                        <i class="fas fa-plus fa-fw"></i>
                    </router-link>                     
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm">
                    <tbody style="font-size: 14px;">
                        <tr>
                            <th style="width: 10%">#</th>
                            <th style="width: 25%">Cliente</th>
                            <th style="width: 25%">Distribuidor</th>
                            <th style="width: 15%">Fecha</th>
                            <th style="width: 15%">Estado</th>
                            <th style="width: 10%"></th>
                        </tr>
                        <tr v-for="nota_pedido in notas_pedidos" :key="nota_pedido.id">
                            <td>{{ nota_pedido.anio_id }} - {{ nota_pedido.anio_actual }}</td>
                            <td>{{ nota_pedido.nombre_cliente }}</td>
                            <td>{{ nota_pedido.nombre_vendedor }}</td>
                            <td>{{ nota_pedido.fecha | formatDate }}</td>
                            <td>
                                <div v-if="nota_pedido.estado == 'PE'">
                                    <span class="badge badge-info">Pendiente</span>
                                </div>
                                <div v-else-if="nota_pedido.estado == 'CO'">
                                    <span class="badge badge-success">Confirmado</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Anulado</span>
                                </div>
                            </td>      
                            <td>
                                <a href="#" v-if="nota_pedido.estado == 'PE'" @click="confirmaPresupuesto(nota_pedido.id)">
                                    <i class="fa fa-check green"></i>
                                </a>
                                <a href="#" v-if="nota_pedido.estado == 'PE'" @click="anulaPresupuesto(nota_pedido.id)">
                                    <i class="fa fa-trash-alt yellow"></i>
                                </a>
                                <router-link :to="{ name: 'notaspedidodetalle', params: { notaspedidoId: nota_pedido.id } }">
                                    <i class="fa fa-table indigo"></i>
                                </router-link>
                                <a href="#" @click="generaNotaPedidoPDF(nota_pedido.id, nota_pedido.anio_id, nota_pedido.anio_actual)">
                                    <i class="fa fa-file-pdf red"></i>
                                </a>                                
                            </td>
                        </tr>
                    </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item" v-if="pagination.current_page > 1">
                      <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, sBuscar, sCriterio)">«</a>
                  </li>
                  <li class="page-item" v-for="page in pageNumber" :key="page" :class="[page == isActived ? 'active': '']">
                      <a class="page-link" href="#" @click.prevent="cambiarPagina(page, sBuscar, sCriterio)" v-text="page"></a>
                  </li>
                  <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                      <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, sBuscar, sCriterio)">»</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-footer -->           
            </div>
            <!-- /.card -->
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                modoEdicion: false,
                notas_pedidos: {},
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                offset: 3,
                sCriterio: 'notas_pedidos.id',
                sBuscar: ''
            }
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pageNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        methods: {
            cambiarPagina(page, buscar, criterio){
                this.pagination.current_page = page;
                this.cargarClientes(page, buscar, criterio);
            },
            cargarNotasPedido(page, buscar, criterio) {
                let me = this;                
                var url = 'api/notaPedido?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.notas_pedidos = response.notas_pedidos.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            anulaPresupuesto(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Desactivar Cliente!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, ANULAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.put('api/notaPedido/anulaNotaPedido/'+id)
                        .then(() => {
                            swal(
                                'ANULADO!',
                                'El PEDIDO a sido ANULADO.',
                                'success'
                            );
                            //Fire.$emit('AfterAction');
                            this.cargarNotasPedido(1, this.sBuscar, this.sCriterio);
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            },
            confirmaPresupuesto(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Desactivar Cliente!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, CONFIRMAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.put('api/notaPedido/confirmaNotaPedido/'+id)
                        .then(() => {
                            swal(
                                'CONFIRMADO!',
                                'El PEDIDO a sido CONFIRMADO.',
                                'success'
                            );
                            this.cargarNotasPedido(1, this.sBuscar, this.sCriterio);
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            },
            generaNotaPedidoPDF(id, anio_id, anio_actual) {
                var urlBE = 'api/notaPedido/NotaPedidoPDF/'+id;
                axios.get(urlBE, {
                    responseType: 'blob'}).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'NV' + anio_id + '-' + anio_actual + '.pdf'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                });
            }
        },
        created() {
            this.cargarNotasPedido(1, this.sBuscar, this.sCriterio);
        }
    }
</script>