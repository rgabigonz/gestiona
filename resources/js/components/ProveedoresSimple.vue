<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-light">Busqueda
                    <div class="card-tools">
                        <button class="btn btn-secondary" @click="cargarProveedores(1, sBuscar, sCriterio)"><i class="fas fa-search fa-fw"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-4">
                            <select class="form-control" v-model="sCriterio">
                                <option value="nombre">Nombre</option>
                            </select>
                        </div>
                        <div class="col col-md-8">
                            <input v-model="sBuscar" @keyup.enter="cargarProveedores(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-info mb-3">
              <div class="card-header border-light">
                <h3 class="card-title">Lista de Proveedores (2)</h3>
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
                            <th style="width: 75%">Nombre</th>
                            <th style="width: 8%">Estado</th>
                            <th style="width: 9%"></th>
                        </tr>
                        <tr v-for="proveedor in proveedores" :key="proveedor.id">
                            <td>{{ proveedor.id }}</td>
                            <td>{{ proveedor.nombre }}</td>
                            <td>
                                <div v-if="proveedor.estado == 'A'">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">No Activo</span>
                                </div>
                            </td>                            
                            <td>
                                <a href="#" @click="editarModal(proveedor)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                <a href="#" v-if="proveedor.estado == 'A'" @click="desactivaProveedor(proveedor.id)">
                                    <i class="fa fa-trash-alt red"></i>
                                </a>
                                <a href="#" v-else @click="activaProveedor(proveedor.id)">
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
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!modoEdicion" class="modal-title" id="ventanaModalLabel">Agregar Proveedor</h5>
                        <h5 v-show="modoEdicion" class="modal-title" id="ventanaModalLabel">Editar Proveedor</h5>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="modoEdicion ? actualizaProveedor() : creaProveedor()">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group mb-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-address-book"></i></span>
                                    </div>                                
                                    <input v-model="form.nombre" type="text" name="nombre" placeholder="Nombre"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('nombre') }">
                                    <has-error :form="form" field="nombre"></has-error>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
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
                proveedores: {},
                form: new Form({
                    id: 0,
                    nombre: ''
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
                sCriterio: 'nombre',
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
                this.cargarProveedores(page, buscar, criterio);
            },
            nuevoModal() {
                this.modoEdicion = false;
                this.form.reset();
                $('#ventanaModal').modal('show');
            },
            editarModal(proveedor) {
                this.modoEdicion = true;
                this.form.reset();
                $('#ventanaModal').modal('show');
                this.form.fill(proveedor);
            },
            cerrarModal() {
                this.form.errors.clear();
                this.form.reset();
                $('#ventanaModal').modal('hide');
            },
            cargarProveedores(page, buscar, criterio) {
                let me = this;                
                var url = 'api/proveedorsimple?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.proveedores = response.proveedores.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            actualizaProveedor() {
                this.$Progress.start();
                
                this.form.put('api/proveedorsimple/'+this.form.id)
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
            creaProveedor() {
                this.$Progress.start();
                
                this.form.post('api/proveedorsimple')
                .then(() => {
                    Fire.$emit('AfterAction');
                    this.cerrarModal();
                    toast({
                        type: 'success',
                        title: 'Se creo el proveedor correctamente!'
                    });

                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            activaProveedor(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Activar Cliente!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, ACTIVAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.form.put('api/proveedorsimple/activar/'+id)
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
            desactivaProveedor(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Desactivar Cliente!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, DESACTIVAR',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.form.put('api/proveedorsimple/desactivar/'+id)
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
            this.cargarProveedores(1, this.sBuscar, this.sCriterio);
            Fire.$on('AfterAction', () => {
                this.cargarProveedores(1, this.sBuscar, this.sCriterio);
            });
        }
    }
</script>