<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-light">Busqueda
                    <div class="card-tools">
                        <button class="btn btn-secondary" @click="cargarOrdenesCompra(1, sBuscar, sCriterio)"><i class="fas fa-search fa-fw"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-4">
                            <select class="form-control" v-model="sCriterio">
                                <option value="ordenes_compras.id">Numero Nota de Pedido</option>
                                <option value="proveedores.nombre">Proveedor</option>
                                <option value="clientes.nombre">Cliente</option>
                                <option value="vendedores.nombre">Distribuidor</option>
                                <option value="AP">AGRO PROYECCIONES S.R.L.</option>
                                <option value="ordenes_compras.numero_negocio">Numero de Negocio</option>
                            </select>
                        </div>
                        <div class="col col-md-8">
                            <input v-model="sBuscar" @keyup.enter="cargarOrdenesCompra(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-info mb-3">
              <div class="card-header border-light">
                <h3 class="card-title">Notas de Pedido Proveedor</h3>
                <div class="card-tools">
                    <router-link :to="{ name: 'ordenescompradetalle', params: { ordenescompraId: 0 } }" class="btn btn-success">
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
                            <th style="width: 20%">Proveedor</th>
                            <th style="width: 20%">Cliente</th>
                            <th style="width: 20%">Distribuidor</th>                            
                            <th style="width: 10%">Fecha</th>
                            <th style="width: 10%">Estado</th>
                            <th style="width: 10%"></th>
                        </tr>
                        <tr v-for="orden_compra in ordenes_compras" :key="orden_compra.id">
                            <td>{{ orden_compra.anio_id }} - {{ orden_compra.anio_actual }}</td>
                            <td>{{ orden_compra.nombre_proveedor }}</td>

                            <td v-if="orden_compra.tipo == 'CL'">
                                {{ orden_compra.nombre_cliente }}
                            </td>
                            <td v-else>
                                "AGRO PROYECCIONES S.R.L."
                            </td>

                            <td>{{ orden_compra.nombre_vendedor }}</td>
                            <td>{{ orden_compra.fecha | formatDate }}</td>
                            <td>
                                <div v-if="orden_compra.estado == 'PE'">
                                    <span class="badge badge-info">Pendiente</span>
                                </div>
                                <div v-else-if="orden_compra.estado == 'CO'">
                                    <span class="badge badge-success">Confirmado</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Anulado</span>
                                </div>
                            </td>      
                            <td>
                                <a href="#" v-if="orden_compra.estado == 'PE'" @click="confirmaOrdenCompra(orden_compra.id)">
                                    <i class="fa fa-check green"></i>
                                </a>
                                <a href="#" v-if="orden_compra.estado == 'PE'" @click="anulaOrdenCompra(orden_compra.id)">
                                    <i class="fa fa-trash-alt yellow"></i>
                                </a>
                                <router-link v-if="sBuscar && sCriterio != 'AP'" :to="{ name: 'ordenescompradetalle', params: { ordenescompraId: orden_compra.id, sBuscarOCD: sBuscar, sCriterioOCD: sCriterio } }">
                                    <i class="fa fa-table indigo"></i>
                                </router-link>
                                <router-link v-else-if="sCriterio == 'AP'" :to="{ name: 'ordenescompradetalle', params: { ordenescompraId: orden_compra.id, sBuscarOCD: 'AP', sCriterioOCD: sCriterio } }">
                                    <i class="fa fa-table indigo"></i>
                                </router-link>    
                                <router-link v-else :to="{ name: 'ordenescompradetalle', params: { ordenescompraId: orden_compra.id } }">
                                    <i class="fa fa-table indigo"></i>
                                </router-link>                                                              
                                <a href="#" @click="NotaPedidoProveedorPDF(orden_compra.id, orden_compra.anio_id, orden_compra.anio_actual)">
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
                ordenes_compras: {},
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                offset: 3,
                sCriterio: 'ordenes_compras.id',
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
                this.cargarOrdenesCompra(page, buscar, criterio);
            },
            cargarOrdenesCompra(page, buscar, criterio) {
                let me = this;                
                var url = 'api/ordenCompra?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.ordenes_compras = response.ordenes_compras.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            anulaOrdenCompra(id) {
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
                        axios.put('api/ordenCompra/anulaOrdenCompra/'+id)
                        .then(() => {
                            swal(
                                'ANULADO!',
                                'La ORDEN DE COMPRA a sido ANULADA.',
                                'success'
                            );
                            this.cargarOrdenesCompra(1, this.sBuscar, this.sCriterio);
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            },
            confirmaOrdenCompra(id) {
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
                        axios.put('api/ordenCompra/confirmaOrdenCompra/'+id)
                        .then(() => {
                            swal(
                                'CONFIRMADO!',
                                'La ORDEN DE COMPRA a sido CONFIRMADA.',
                                'success'
                            );
                            //Fire.$emit('AfterAction');
                            this.cargarOrdenesCompra(1, this.sBuscar, this.sCriterio);
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            },
            NotaPedidoProveedorPDF(id, anio_id, anio_actual) {
                var urlBE = 'api/ordenCompra/NotaPedidoProveedorPDF/'+id;
                axios.get(urlBE, {
                    responseType: 'blob'}).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'NP' + anio_id + '-' + anio_actual + '.pdf'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                });
            }
        },
        created() {
            if (this.$route.params.sCriterio == 'AP') {
                this.sCriterio = this.$route.params.sCriterio;
                //this.sBuscar = '';
            }
            else {
                if (this.$route.params.sBuscar) {
                    this.sBuscar = this.$route.params.sBuscar;
                    this.sCriterio = this.$route.params.sCriterio;
                }
            }
            this.cargarOrdenesCompra(1, this.sBuscar, this.sCriterio);
        }
    }
</script>