<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-light">Busqueda
                    <div class="card-tools">
                        <button class="btn btn-secondary" @click="cargarMovimientos(1, sBuscar, sCriterio)"><i class="fas fa-search fa-fw"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-4">
                            <select class="form-control" v-model="sCriterio">
                                <option value="producto.nombre">Producto</option>
                                <option value="deposito.descripcion">Deposito</option>
                            </select>
                        </div>
                        <div class="col col-md-8">
                            <input v-model="sBuscar" @keyup.enter="cargarMovimientos(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-info mb-3">
              <div class="card-header border-light">
                <h3 class="card-title">Movimientos de Stock</h3>
                <div class="card-tools">
                    <button class="btn btn-success" @click="nuevoModal()"><i class="fas fa-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 35%">Producto</th>
                            <th style="width: 35%">Deposito</th>
                            <th style="width: 13%">Fecha</th>                            
                            <th style="width: 8%">Estado</th>
                            <th style="width: 9%"></th>
                        </tr>
                        <tr v-for="movimiento in movimientos" :key="movimiento.id">
                            <td>{{ movimiento.producto_nombre }}</td>
                            <td>{{ movimiento.descripcion_deposito }}</td>
                            <td>{{ movimiento.fecha }}</td>
                            <td>
                                <div v-if="movimiento.estado == 'A'">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">No Activo</span>
                                </div>
                            </td>                            
                            <td>
                                <a href="#" @click="editarModal(movimiento)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                <a href="#" v-if="movimiento.estado == 'A'" @click="desactivaMovimiento(movimiento.id)">
                                    <i class="fa fa-trash-alt red"></i>
                                </a>
                                <a href="#" v-else @click="activaMovimiento(movimiento.id)">
                                    <i class="fa fa-check green"></i>
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

        <!-- Modal -->
        <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="ventanaModalLabel" aria-hidden="true">
            <div style="min-width: 35%" class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!modoEdicion" class="modal-title" id="ventanaModalLabel">Agregar Producto</h5>
                        <h5 v-show="modoEdicion" class="modal-title" id="ventanaModalLabel">Editar Producto</h5>
                        <button type="button" class="close"  @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="modoEdicion ? actualizaProducto() : creaProducto()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label" for="nombre"><i class="fa fa-bell-o"></i>Nombre</label>
                                <input v-model="form.nombre" type="text" name="nombre" placeholder="Ingrese un nombre"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('nombre') }">
                                <has-error :form="form" field="nombre"></has-error>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="tipo_producto"><i class="fa fa-bell-o"></i>Tipo de Producto</label>
                                <select v-model="form.tipo_producto" name="tipo_producto" class="form-control" :class="{ 'is-invalid': form.errors.has('tipo_producto') }">
                                    <option v-for="ltipo_producto in oTipos_Producto" :key="ltipo_producto.id" :value="ltipo_producto.id">{{ ltipo_producto.descripcion }}</option>
                                </select>
                                <has-error :form="form" field="tipo_producto"></has-error>
                            </div>   

                            <div class="form-group">
                                <label class="control-label" for="descripcion"><i class="fa fa-bell-o"></i>Descripcion</label>
                                <textarea v-model="form.descripcion" type="text" name="descripcion" placeholder="Ingrese una descripcion"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion') }"></textarea>
                                <has-error :form="form" field="descripcion"></has-error>
                            </div> 

                            <div class="row">
                                <div class="col col-md-4">
                                  <div class="form-group">
                                      <label class="control-label" for="stk_min"><i class="fa fa-bell-o"></i>Stock Minimo</label>
                                      <input v-model="form.stk_min" type="number" name="stk_min" min="0" value="0" step=".01"
                                          class="form-control" :class="{ 'is-invalid': form.errors.has('stk_min') }">
                                      <has-error :form="form" field="stk_min"></has-error>
                                  </div>
                                </div>
                                <div class="col col-md-4">
                                  <div class="form-group">
                                    <label class="control-label" for="stk_max"><i class="fa fa-bell-o"></i>Stock maximo</label>
                                      <input v-model="form.stk_max" type="number" name="stk_max"  min="0" value="0" step=".01"
                                          class="form-control" :class="{ 'is-invalid': form.errors.has('stk_max') }">
                                      <has-error :form="form" field="stk_max"></has-error>
                                  </div>
                                </div>
                                <div class="col col-md-4">
                                  <div class="form-group">
                                    <label class="control-label" for="stk_actual"><i class="fa fa-bell-o"></i>Stock actual</label>
                                      <input v-model="form.stk_actual" type="number" name="stk_actual" disabled class="form-control">
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button> <!--data-dismiss="modal" -->
                            <button v-show="modoEdicion" type="submit" class="btn btn-success">Actualizar</button>
                            <button v-show="!modoEdicion" type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                modoEdicion: false,
                movimientos: {},
                oProductos: {},
                oDepositos: {},
                form: new Form({
                    id: 0,
                    producto_id: 0,
                    deposito_id: 0,
                    cantidad: 0,
                    descripcion: '',
                    tipo: 1,
                }),
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                offset: 3,
                sCriterio: 'producto.nombre',
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
                this.cargarMovimientos(page, buscar, criterio);
            },
            nuevoModal() {
                this.fCargar_TP();
                this.modoEdicion = false;
                this.form.reset();
                $('#ventanaModal').modal('show');
            },
            editarModal(producto) {
                this.fCargar_TP();
                this.modoEdicion = true;
                this.form.reset();
                $('#ventanaModal').modal('show');
                this.form.fill(producto);
            },
            cerrarModal() {
                this.form.errors.clear();
                this.form.reset();
                $('#ventanaModal').modal('hide');
            },
            cargarMovimientos(page, buscar, criterio) {
                let me = this;                
                var url = 'api/movimientostock?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.movimientos = response.movimientosstock.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    //console.log(error.response.status);
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            fCargar_TP() {
                let lMe = this;                
                var lUrl = 'api/tipoproducto/cargaTP';
                axios.get(lUrl).then(data => {
                    var lResponse = data.data;
                    lMe.oTipos_Producto = lResponse.tiposProducto;
                }).catch((error) => {
                    if (error.lResponse.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            actualizaProducto() {
                this.$Progress.start();
                
                this.form.put('api/producto/'+this.form.id)
                .then(() => {
                    Fire.$emit('AfterAction');
                    this.cerrarModal();
                    toast({
                        type: 'success',
                        title: 'Se actualizaron los datos correctamente!'
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            creaProducto() {
                this.$Progress.start();
                
                this.form.post('api/producto')
                .then(() => {
                    Fire.$emit('AfterAction');
                    this.cerrarModal();
                    toast({
                        type: 'success',
                        title: 'Se creo el producto correctamente!'
                    });

                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            activaProducto(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Activar Producto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, ACTIVAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.form.put('api/producto/activar/'+id)
                        .then(() => {
                            swal(
                                'ACTIVADO!',
                                'El registro a sido Activado.',
                                'success'
                            );
                            Fire.$emit('AfterAction');
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            },
            desactivaProducto(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Desactivar Producto!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, DESACTIVAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.form.put('api/producto/desactivar/'+id)
                        .then(() => {
                            swal(
                                'DESACTIVADO!',
                                'El registro a sido Desactivado.',
                                'success'
                            );
                            Fire.$emit('AfterAction');
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            }
        },
        created() {
            this.cargarMovimientos(1, this.sBuscar, this.sCriterio);
            Fire.$on('AfterAction', () => {
                this.cargarMovimientos(1, this.sBuscar, this.sCriterio);
            });
        }
    }
</script>