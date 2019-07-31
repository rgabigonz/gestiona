<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-light">Busqueda
                    <div class="card-tools">
                        <button class="btn btn-secondary" @click="cargarCheques(1, sBuscar, sCriterio)"><i class="fas fa-search fa-fw"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-4">
                            <select class="form-control" v-model="sCriterio">
                                <option value="numero_cheque">N° Cheque</option>
                                <!-- <option value="estado_cheque">Estado</option>
                                <option value="fecha_cobro_cheque">Fecha de Cobro</option> -->
                            </select>
                        </div>
                        <div class="col col-md-8">
                            <input v-model="sBuscar" @keyup.enter="cargarCheques(1, sBuscar, sCriterio)" type="text" class="form-control" placeholder="Dato a buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-info mb-3">
              <div class="card-header border-light">
                <h3 class="card-title">Lista de Cheques</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm">
                    <tbody style="font-size: 14px;">
                        <tr>
                            <th style="width: 25%">Cliente</th>
                            <th style="width: 12%">N° Recibo</th>
                            <th style="width: 12%">N° Cheque</th>
                            <th style="width: 13%">Importe</th>
                            <th style="width: 13%">Fecha Cobro</th>
                            <th style="width: 15%">Estado</th>
                            <th style="width: 10%"></th>
                        </tr>
                        <tr v-for="cheque in cheques" :key="cheque.id">
                            <td>{{ cheque.nombre_cliente }}</td>
                            <td>{{ cheque.referencia_talonario }}</td>
                            <td>{{ cheque.numero_cheque }}</td>
                            <td>${{ cheque.importe }}</td>
                            <td>{{ cheque.fecha_cobro_cheque | formatDate}}</td>
                            <td>
                                <div v-if="cheque.estado_cheque == 'PE'">
                                    <span class="badge badge-info">En cartera</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-success">Entregado</span>
                                </div>
                            </td>                            
                            <td>
                                <a href="#" @click="editarModal(cheque)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                <!-- <a href="#" v-if="cheque.estado_cheque == 'PE'" @click="cobrarCheque(cheque.id)">
                                    <i class="fas fa-dollar-sign green"></i>
                                </a> -->
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
                        <h5 v-show="modoEdicion" class="modal-title" id="ventanaModalLabel">Editar Cheque</h5>
                        <button type="button" class="close"  @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="actualizaCheque()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label" for="numero_cheque"><i class="fa fa-bell-o"></i>N° Cheque</label>
                                        <h4>{{ form.numero_cheque }}</h4>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label" for="fecha_cobro_cheque"><i class="fa fa-bell-o"></i>Fecha Cobro</label>
                                        <h4>{{ form.fecha_cobro_cheque | formatDate }}</h4>
                                    </div>   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label" for="nombre_cliente"><i class="fa fa-bell-o"></i>Cliente</label>
                                        <h4>{{ form.nombre_cliente }}</h4>
                                    </div>  
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label" for="importe_cheque"><i class="fa fa-bell-o"></i>Importe</label>
                                        <h4>${{ form.importe_cheque }}</h4>
                                    </div>  
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="proveedor_id"><i class="fa fa-bell-o"></i>Destinatario (Proveedor)</label>
                                        <select class="form-control" v-model="form.proveedor_id" :disabled="form.estado_cheque != 'PE' ? true : false">
                                            <option value=0>Seleccionar...</option>
                                            <option v-for="lproveedor in lproveedores" :key="lproveedor.id" :value="lproveedor.id">{{ lproveedor.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div> 

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                            <button v-show="modoEdicion && form.estado_cheque == 'PE'" type="submit" class="btn btn-success">Cobrado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Datepicker from "vuejs-datepicker/dist/vuejs-datepicker.esm.js";
    import {en, es} from 'vuejs-datepicker/dist/locale';

    export default {
        components: {
            Datepicker
        },
        data() {
            return {
                modoEdicion: false,
                cheques: {},
                lproveedores: {},
                form: new Form({
                    id: 0,
                    numero_cheque: '',
                    nombre_cliente: '',
                    importe_cheque: 0,
                    estado_cheque: '',
                    fecha_cobro_cheque: '',
                    proveedor_id: 0
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
                sCriterio: 'numero_cheque',
                sBuscar: '',
                formato_fecha_cheque: "dd-MM-yyyy",
                es: es
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
                this.cargarCheques(page, buscar, criterio);
            },
            editarModal(cheque) {
                this.modoEdicion = true;
                this.form.reset();
                
                $('#ventanaModal').modal('show');
                this.form.fill(cheque);

                if(!cheque.proveedor_id)
                    this.form.proveedor_id = 0;
            },
            cerrarModal() {
                this.form.errors.clear();
                this.form.reset();
                $('#ventanaModal').modal('hide');
            },
            cargarCheques(page, buscar, criterio) {
                let me = this;                
                var url = 'api/cheque?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(data => {
                    var response = data.data;
                    me.cheques = response.cheques.data;
                    me.pagination = response.pagination;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            },
            validaDestinatario() {
                var resultado = false;

                if (this.form.proveedor_id) {
                    resultado = true;
                }

                return resultado;
            },
            actualizaCheque() {
                this.$Progress.start();
                
                if (this.validaDestinatario()) {                
                    this.form.put('api/cheque/cobrarCheque/'+this.form.id)
                    .then(() => {
                        Fire.$emit('AfterAction');
                        this.cerrarModal();
                        toast({
                            type: 'success',
                            title: 'Se actualizaron los datos correctamente!'
                        });
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
                } else {
                    toast({
                        type: 'error',
                        title: 'Debe ingresar un destinatario!'
                    });                    
                }

                this.$Progress.finish();
            }/*,
            cobrarCheque(id) {
                swal({
                    title: 'Esta seguro?',
                    //text: "Activar Condicion de Pago!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, ENTREGADO',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        this.form.put('api/cheque/cobrarCheque/'+id)
                        .then(() => {
                            swal(
                                'ENTREGADO!',
                                'El cheque a sido Entregado.',
                                'success'
                            );
                            Fire.$emit('AfterAction');
                        })
                    }
                })
                .catch(() => {
                    swal('Fallo!', 'Hubo un error al procesar la transaccion.', 'warning');                    
                });
            }*/,
            cargaProveedores() {
                let me = this;                
                var url = 'api/proveedorsimple/cargaProveedores';
                axios.get(url).then(data => {
                    var response = data.data;
                    me.lproveedores = response.proveedores;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        swal('Error!', 'La sesion ha caducado.', 'warning');
                    }
                });
            }
        },
        created() {
            this.cargaProveedores();

            this.cargarCheques(1, this.sBuscar, this.sCriterio);
            Fire.$on('AfterAction', () => {
                this.cargarCheques(1, this.sBuscar, this.sCriterio);
            });
        }
    }
</script>