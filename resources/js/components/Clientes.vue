<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-light">Busqueda
                    <div class="card-tools">
                        <button class="btn btn-secondary" @click="fCargar_Clientes(1, vCadena_a_Buscar, vCriterio_Busqueda)"><i class="fas fa-search fa-fw"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-4">
                            <select class="form-control" v-model="vCriterio_Busqueda">
                                <option value="nombre">Nombre</option>
                                <option value="numero_documento">Documento</option>
                            </select>
                        </div>
                        <div class="col col-md-8">
                            <input v-model="vCadena_a_Buscar" @keyup.enter="fCargar_Clientes(1, vCadena_a_Buscar, vCriterio_Busqueda)" type="text" class="form-control" placeholder="Dato a buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-info mb-3">
              <div class="card-header border-light">
                <h3 class="card-title">Lista de Clientes</h3>
                <div class="card-tools">
                    <button class="btn btn-success" @click="fNuevo_Modal()"><i class="fas fa-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 8%">#</th>
                            <th style="width: 30%">Nombre</th>
                            <th style="width: 45%">Direccion</th>
                            <th style="width: 8%">Estado</th>
                            <th style="width: 9%"></th>
                        </tr>
                        <tr v-for="lcliente in oClientes" :key="lcliente.id">
                            <td>{{ lcliente.id }}</td>
                            <td>{{ lcliente.nombre }}</td>
                            <td>{{ lcliente.direccion }}</td>
                            <td>
                                <div v-if="lcliente.estado == 'A'">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">No Activo</span>
                                </div>
                            </td>                            
                            <td>
                                <a href="#" @click="fEditar_Modal(lcliente)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                <a href="#" v-if="lcliente.estado == 'A'" @click="fDesactiva_Cliente(lcliente.id)">
                                    <i class="fa fa-trash-alt red"></i>
                                </a>
                                <a href="#" v-else @click="fActiva_Cliente(lcliente.id)">
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
                      <a class="page-link" href="#" @click.prevent="fCambiar_Pagina(pagination.current_page - 1, vCadena_a_Buscar, vCriterio_Busqueda)">«</a>
                  </li>
                  <li class="page-item" v-for="page in pageNumber" :key="page" :class="[page == isActived ? 'active': '']">
                      <a class="page-link" href="#" @click.prevent="fCambiar_Pagina(page, vCadena_a_Buscar, vCriterio_Busqueda)" v-text="page"></a>
                  </li>
                  <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                      <a class="page-link" href="#" @click.prevent="fCambiar_Pagina(pagination.current_page + 1, vCadena_a_Buscar, vCriterio_Busqueda)">»</a>
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
                        <h5 v-show="!vModo_Edicion" class="modal-title" id="ventanaModalLabel">Agregar Cliente</h5>
                        <h5 v-show="vModo_Edicion" class="modal-title" id="ventanaModalLabel">Editar Cliente</h5>
                        <button type="button" class="close" @click="fCerrar_Modal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="vModo_Edicion ? fActualiza_Cliente() : fCrea_Cliente()">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-address-book"></i></span>
                                    </div>                                
                                    <input v-model="form.nombre" type="text" name="nombre" placeholder="Nombre"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('nombre') }">
                                    <has-error :form="form" field="nombre"></has-error>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                                    </div>                                
                                    <textarea v-model="form.direccion" type="text" name="direccion" placeholder="Direccion"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('direccion') }"></textarea>
                                    <has-error :form="form" field="direccion"></has-error>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>                                
                                    <input v-model="form.correo_electronico" type="email" name="correo_electronico" placeholder="Correo electronico"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('correo_electronico') }">
                                    <has-error :form="form" field="correo_electronico"></has-error>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>                                
                                    <input v-model="form.telefono" type="text" name="telefono" placeholder="Telefono"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('telefono') }">
                                    <has-error :form="form" field="telefono"></has-error>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-list-ol"></i></span>
                                            </div>
                                            <select v-model="form.tipo_documento" name="tipo_documento" class="form-control" :class="{ 'is-invalid': form.errors.has('tipo_documento') }">
                                                <option v-for="ltipo_documento in oTipos_Documento" :key="ltipo_documento.id" :value="ltipo_documento.id">{{ ltipo_documento.descripcion }}</option>
                                            </select>
                                            <has-error :form="form" field="tipo_documento"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-md-6">
                                  <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-address-card"></i></span>
                                            </div>
                                            <input v-model="form.numero_documento" name="numero_documento" placeholder="Numero documento"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('numero_documento') }">
                                            <has-error :form="form" field="numero_documento"></has-error>
                                        </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="fCerrar_Modal()">Cerrar</button>
                            <button v-show="vModo_Edicion" type="submit" class="btn btn-success">Actualizar</button>
                            <button v-show="!vModo_Edicion" type="submit" class="btn btn-primary">Crear</button>
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
                vModo_Edicion: false,
                oClientes: {},
                oTipos_Documento: {},
                form: new Form({
                    id: 0,
                    nombre: '',
                    direccion: '',
                    correo_electronico: '',
                    telefono: '',
                    tipo_documento: 1,
                    numero_documento: ''
                }),
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                vOffset: 3,
                vCriterio_Busqueda: 'nombre',
                vCadena_a_Buscar: ''
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

                var from = this.pagination.current_page - this.vOffset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.vOffset * 2);
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
            fCambiar_Pagina(lPage, lBuscar, lCriterio){
                this.pagination.current_page = lPage;
                this.fCargar_Clientes(lPage, lBuscar, lCriterio);
            },
            fNuevo_Modal() {
                this.fCargar_TD();
                this.vModo_Edicion = false;
                this.form.reset();
                $('#ventanaModal').modal('show');
            },
            fEditar_Modal(lCliente) {
                this.fCargar_TD();
                this.vModo_Edicion = true;
                this.form.reset();
                $('#ventanaModal').modal('show');
                this.form.fill(lCliente);
            },
            fCerrar_Modal() {
                this.form.errors.clear();
                this.form.reset();
                $('#ventanaModal').modal('hide');
            },
            fCargar_Clientes(lPage, lBuscar, lCriterio) {
                let lMe = this;                
                var lUrl = 'api/cliente?page=' + lPage + '&buscar=' + lBuscar + '&criterio=' + lCriterio;
                axios.get(lUrl).then(data => {
                    var lResponse = data.data;
                    lMe.oClientes = lResponse.clientes.data;
                    lMe.pagination = lResponse.pagination;
                }).catch((error) => {
                    if (error.lResponse.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            fCargar_TD() {
                let lMe = this;                
                var lUrl = 'api/tipodocumento/cargaTD';
                axios.get(lUrl).then(data => {
                    var lResponse = data.data;
                    lMe.oTipos_Documento = lResponse.tiposDocumento;
                }).catch((error) => {
                    if (error.lResponse.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            fActualiza_Cliente() {
                this.$Progress.start();
                
                this.form.put('api/cliente/'+this.form.id)
                .then(() => {
                    Fire.$emit('AfterAction');
                    this.fCerrar_Modal();
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
            fCrea_Cliente() {
                this.$Progress.start();
                
                this.form.post('api/cliente')
                .then(() => {
                    Fire.$emit('AfterAction');
                    this.fCerrar_Modal();
                    toast({
                        type: 'success',
                        title: 'Se creo el cliente correctamente!'
                    });

                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            fActiva_Cliente(lId) {
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
                        this.form.put('api/cliente/activar/'+lId)
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
            fDesactiva_Cliente(lId) {
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
                        this.form.put('api/cliente/desactivar/'+lId)
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
            this.fCargar_Clientes(1, this.vCadena_a_Buscar, this.vCriterio_Busqueda);
            Fire.$on('AfterAction', () => {
                this.fCargar_Clientes(1, this.vCadena_a_Buscar, this.vCriterio_Busqueda);
            });
        }
    }
</script>