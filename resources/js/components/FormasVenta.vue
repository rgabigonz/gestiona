<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-light">Busqueda
                    <div class="card-tools">
                        <button class="btn btn-secondary" @click="cargarFormasVenta(1, sBuscar, sCriterio)"><i class="fas fa-search fa-fw"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-4">
                            <select class="form-control" v-model="sCriterio">
                                <option value="descripcion">Descripcion</option>
                            </select>
                        </div>
                        <div class="col col-md-8">
                            <input v-model="sBuscar" @keyup.enter="cargarFormasVenta(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-info mb-3">
              <div class="card-header border-light">
                <h3 class="card-title">Lista de Formas de Venta</h3>
                <div class="card-tools">
                    <button class="btn btn-success" @click="nuevoModal()"><i class="fas fa-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 8%">#</th>
                            <th style="width: 75%">Descripcion</th>
                            <th style="width: 8%">Estado</th>
                            <th style="width: 9%"></th>
                        </tr>
                        <tr v-for="forma_venta in formas_venta" :key="forma_venta.id">
                            <td>{{ forma_venta.id }}</td>
                            <td>{{ forma_venta.descripcion }}</td>
                            <td>
                                <div v-if="forma_venta.estado == 'A'">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">No Activo</span>
                                </div>
                            </td>                            
                            <td>
                                <a href="#" @click="editarModal(forma_venta)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                <a href="#" v-if="forma_venta.estado == 'A'" @click="desactivaFormaVenta(forma_venta.id)">
                                    <i class="fa fa-trash-alt red"></i>
                                </a>
                                <a href="#" v-else @click="activaFormaVenta(forma_venta.id)">
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
                        <h5 v-show="!modoEdicion" class="modal-title" id="ventanaModalLabel">Agregar Forma de Venta</h5>
                        <h5 v-show="modoEdicion" class="modal-title" id="ventanaModalLabel">Editar Forma de Venta</h5>
                        <button type="button" class="close"  @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="modoEdicion ? actualizaFormaVenta() : creaFormaVenta()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label" for="descripcion"><i class="fa fa-bell-o"></i>Descripcion</label>
                                <textarea v-model="form.descripcion" type="text" name="descripcion" placeholder="Ingrese una descripcion"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('descripcion') }"></textarea>
                                <has-error :form="form" field="descripcion"></has-error>
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
                formas_venta: {},
                form: new Form({
                    id: 0,
                    descripcion: ''
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
                sCriterio: 'descripcion',
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
                this.cargarFormasVenta(page, buscar, criterio);
            },
            nuevoModal() {
                this.modoEdicion = false;
                this.form.reset();
                $('#ventanaModal').modal('show');
            },
            editarModal(forma_venta) {
                this.modoEdicion = true;
                this.form.reset();
                $('#ventanaModal').modal('show');
                this.form.fill(forma_venta);
            },
            cerrarModal() {
                this.form.errors.clear();
                this.form.reset();
                $('#ventanaModal').modal('hide');
            },
            cargarFormasVenta(page, buscar, criterio) {
                let me = this;                
                var url = 'api/formaventa?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.formas_venta = response.formas_venta.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    //console.log(error.response.status);
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            actualizaFormaVenta() {
                this.$Progress.start();
                
                this.form.put('api/formaventa/'+this.form.id)
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
            creaFormaVenta() {
                this.$Progress.start();
                
                this.form.post('api/formaventa')
                .then(() => {
                    Fire.$emit('AfterAction');
                    this.cerrarModal();
                    toast({
                        type: 'success',
                        title: 'Se creo la forma de venta correctamente!'
                    });

                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            activaFormaVenta(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Activar Forma de Venta!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, ACTIVAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.form.put('api/formaventa/activar/'+id)
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
            desactivaFormaVenta(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Desactivar Forma de Venta!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, DESACTIVAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.form.put('api/formaventa/desactivar/'+id)
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
            this.cargarFormasVenta(1, this.sBuscar, this.sCriterio);
            Fire.$on('AfterAction', () => {
                this.cargarFormasVenta(1, this.sBuscar, this.sCriterio);
            });
        }
    }
</script>