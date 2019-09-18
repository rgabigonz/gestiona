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
                                <div v-if="movimiento.estado == 'PE'">
                                    <span class="badge badge-info">Pendiente</span>
                                </div>
                                <div v-else-if="movimiento.estado == 'CO'">
                                    <span class="badge badge-success">Confirmado</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Anulado</span>
                                </div>                                
                            </td>                            
                            <td>
                                <a href="#" @click="editarModal(movimiento)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                <a href="#" v-if="movimiento.estado == 'PE'" @click="confirmaMovimiento(movimiento.id)">
                                    <i class="fa fa-check green"></i>
                                </a>
                                <a href="#" v-if="movimiento.estado == 'PE'" @click="anulaMovimiento(movimiento.id)">
                                    <i class="fa fa-trash-alt yellow"></i>
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
            <div style="min-width: 45%" class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!modoEdicion" class="modal-title" id="ventanaModalLabel">Hacer Movimiento</h5>
                        <h5 v-show="modoEdicion" class="modal-title" id="ventanaModalLabel">Editar Movimiento</h5>
                        <button type="button" class="close"  @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="modoEdicion ? actualizaMovimiento() : creaMovimiento()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label" for="deposito_id"><i class="fa fa-bell-o"></i>Deposito</label>
                                <select v-model="form.deposito_id" name="deposito_id" class="form-control" :class="{ 'is-invalid': form.errors.has('deposito_id') }">
                                    <option value=0>Seleccionar...</option>
                                    <option v-for="ldeposito in oDepositos" :key="ldeposito.id" :value="ldeposito.id">{{ ldeposito.descripcion }}</option>
                                </select>
                                <has-error :form="form" field="deposito_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="producto_id"><i class="fa fa-bell-o"></i>Producto</label>
                                <select v-model="form.producto_id" name="producto_id" class="form-control" :class="{ 'is-invalid': form.errors.has('producto_id') }">
                                    <option value=0>Seleccionar...</option>
                                    <option v-for="lproducto in oProductos" :key="lproducto.id" :value="lproducto.id">{{ lproducto.nombre }}</option>
                                </select>
                                <has-error :form="form" field="producto_id"></has-error>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="cantidad"><i class="fa fa-bell-o"></i>Cantidad</label>
                                        <input v-model="form.cantidad" type="number" name="cantidad" min="0" value="0" step=".01"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('cantidad') }">
                                        <has-error :form="form" field="cantidad"></has-error>
                                    </div>
                                </div>
                                <div class="col col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="tipo"><i class="fa fa-bell-o"></i>Tipo de Movimiento</label>
                                        <select v-model="form.tipo" name="tipo" class="form-control" :class="{ 'is-invalid': form.errors.has('tipo') }">
                                            <option v-for="ltipo_movimiento in ltipos_movimientos" :key="ltipo_movimiento.id" :value="ltipo_movimiento.id">{{ ltipo_movimiento.nombre }}</option>
                                        </select>
                                        <has-error :form="form" field="tipo"></has-error>
                                    </div>   
                                </div>
                            </div>
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
                ltipos_movimientos: [
                    {id: 'I', nombre: 'INGRESO'},
                    {id: 'E', nombre: 'EGRESO'}
                ],                
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
                    tipo: 'I',
                    tipo_documento: 'MI',
                    documento_id: 0
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
                this.cargaDepositos();
                this.cargaProductos();

                this.modoEdicion = false;
                this.form.reset();
                $('#ventanaModal').modal('show');
            },
            editarModal(movimiento) {
                this.cargaDepositos();
                this.cargaProductos();

                this.modoEdicion = true;
                this.form.reset();
                $('#ventanaModal').modal('show');
                this.form.fill(movimiento);
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
            cargaDepositos() {
                let me = this;                
                var url = 'api/deposito/cargaDepositos';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.oDepositos = response.ldepositos;
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
                    me.oProductos = response.productos;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },            
            actualizaMovimiento() {
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
            creaMovimiento() {
                this.$Progress.start();
                
                this.form.post('api/movimientostock')
                .then(() => {
                    Fire.$emit('AfterAction');
                    this.cerrarModal();
                    toast({
                        type: 'success',
                        title: 'Se creo el movimiento correctamente!'
                    });

                })
                .catch(() => {
                    this.$Progress.fail();
                });

                this.$Progress.finish();
            },
            anulaMovimiento(id) {
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
                        axios.put('api/recibo/anulaRecibo/'+id)
                        .then(() => {
                            swal(
                                'ANULADO!',
                                'El RECIBO a sido ANULADO.',
                                'success'
                            );
                            //Fire.$emit('AfterAction');
                            this.cargarRecibos(1, this.sBuscar, this.sCriterio);
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            },
            confirmaMovimiento(id) {
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
                        axios.put('api/movimientostock/confirmaMovimientoStock/'+id)
                        .then(() => {
                            swal(
                                'CONFIRMADO!',
                                'El MOVIMIENTO DE STOCK a sido CONFIRMADO.',
                                'success'
                            );
                            this.cargarMovimientos(1, this.sBuscar, this.sCriterio);
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