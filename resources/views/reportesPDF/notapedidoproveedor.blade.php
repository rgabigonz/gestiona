<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Nota de Pedido</title>
		<style>
			body {
				/*position: relative;*/
				/*width: 16cm;  */
				/*height: 29.7cm; */
				/*margin: 0 auto; */
				/*color: #555555;*/
				/*background: #FFFFFF; */
				font-family: Arial, sans-serif; 
				font-size: 15px;
				/*font-family: SourceSansPro;*/
			}

			#logo{
				float: left;
				margin-top: 1%;
				margin-left: 2%;
				margin-right: 2%;
			}

			#imagen{
				width: 100px;
			}

			#datos{
				float: left;
				margin-top: 0%;
				margin-left: 0%;
				margin-right: 0%;
				/*text-align: justify;*/
			}

			#encabezado{
				text-align: left;
				/* margin-left: 10%;
				margin-right: 35%; */
				font-size: 15px;
			}

			#firma{
				text-align: center;
				/* margin-left: 10%;
				margin-right: 35%; */
				font-size: 10px;
			}

			#nven{
				/*position: relative;*/
				float: right;
				margin-top: 2%;
				margin-left: 2%;
				margin-right: 2%;
				font-size: 15px;
			}

			section{
				clear: left;
			}

			#cliente{
				text-align: left;
			}

			#nvcliente{
				width: 100%;
				border-collapse: collapse;
				border-spacing: 0;
				margin-bottom: 15px;
			}

			#fac, #fv, #nv{
				color: #FFFFFF;
				font-size: 15px;
			}

			#nvcliente thead{
				padding: 50px;
				background: #2183E3;
				text-align: left;
				border-bottom: 1px solid #FFFFFF;  
			}

			#facvendedor{
				width: 100%;
				border-collapse: collapse;
				border-spacing: 0;
				margin-bottom: 15px;
			}

			#facvendedor thead{
				padding: 20px;
				background: #2183E3;
				text-align: center;
				border-bottom: 1px solid #FFFFFF;  
			}

			#nvarticulo{
				width: 100%;
				border-collapse: collapse;
				border-spacing: 0;
				margin-bottom: 15px;
			}

			#nvarticulo thead{
				padding: 20px;
				background: #2183E3;
				/* text-align: center; */
				border-bottom: 1px solid #FFFFFF;  
			}

			#gracias{
				text-align: left; 
			}

	</style>		
	</head>
	<body>
        <header>
            <!-- <div id="logo">
                <img src="img/logo2.png" alt="incanatoIT" id="imagen">
            </div> -->
            <div id="datos">
                <p id="encabezado">
                    <b>Agro Proyecciones SRL</b>
					<br>Ituzaingo 72 (4400), Salta, Argentina
					<br>Celular:(+54)93875088902
					<br>CUIT: 30-71424172-5
                </p>
            </div>
            <div id="nven">
                <p>
					Fecha de Pedido: {{ date('d-m-Y', strtotime($datoOrdenCompra[0]['fecha'])) }}
					<br>Nota de Pedido N° {{ $datoOrdenCompra[0]['anio_id'] }} - {{ $datoOrdenCompra[0]['anio_actual'] }}
				</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="nvcliente">
                    <thead>                        
                        <tr>
                            <th id="nv">Proveedor</th>
                            <th id="nv">Lugar de Entrega</th>							
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
								<p id="cliente">{{ $datoOrdenCompra[0]['nombre_proveedor'] }}<!-- <br>
                            					Dirección: {{ $datoOrdenCompra[0]['direccion_proveedor'] }}<br>
                            					Teléfono: {{ $datoOrdenCompra[0]['telefono_proveedor'] }}<br>
                            					Email: {{ $datoOrdenCompra[0]['email_proveedor'] }} -->
								</p>
							</th>
							<th>
								<p id="cliente">{{ $datoOrdenCompra[0]['lugar_entrega'] }}
								</p>
							</th>							
                        </tr>
                    </tbody>
                </table>
            </div>

			@if ($datoOrdenCompra[0]['tipo'] == 'CL')
            	<div>
					<table id="nvcliente">
						<thead>                        
							<tr>
								<th id="nv">Cliente</th>
								<th id="nv">Forma de Pago</th>
								<th id="nv">Condicion de Pago</th>								
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									<p id="cliente">{{ $datoOrdenCompra[0]['nombre_cliente'] }}<br>
													CUIT: {{ $datoOrdenCompra[0]['numero_documento'] }}
									</p>
								</th>
								<th>
									<p id="cliente">{{ $datoOrdenCompra[0]['descripcion_forma_pago'] }}<br></p>
								</th>
								<th>
									<p id="cliente">{{ $datoOrdenCompra[0]['descripcion_condicion_pago'] }}<br></p>
								</th>								
							</tr>
						</tbody>
					</table>
					<table id="nvcliente">
						<thead>                        
							<tr>
								<th id="nv">Comision de Venta</th>
								<th id="nv">Comision de Gestion</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									<p id="cliente">{{ $datoOrdenCompra[0]['nombre_vendedor_venta'] }}<br>
													CUIT: {{ $datoOrdenCompra[0]['numero_documento_vendedor_venta'] }}
									</p>
								</th>
								<th>
									<p id="cliente">{{ $datoOrdenCompra[0]['nombre_vendedor_gestion'] }}<br></p>
								</th>
							</tr>
						</tbody>
					</table>
				</div>					
			@endif

			@if ($datoOrdenCompra[0]['tipo'] == 'PR')
            	<div>
					<table id="nvcliente">
						<thead>                        
							<tr>
								<th id="nv">Cliente</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									<p id="cliente">AGRO PROYECCIONES S.R.L.<br>
													CUIT: 30-71424172-5
									</p>
								</th>
							</tr>
						</tbody>
					</table>
				</div>					
			@endif

        </section>		
		<br>
        <section>
            <div>
                <table id="nvarticulo" style="font-size: 12px;">
                    <thead>
                        <tr id="nv">
                            <th>Producto</th>
                            <th>Cantidad</th>							
                            <th>Precio</th>
							@if ($datoOrdenCompra[0]['tipo'] == 'CL')
								<th>Flete</th>
								<th>C. Venta</th>
								<th>C. Gestion</th>
							@endif
							<th>P. Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datoOrdenCompraD as $det)
                        <tr>
                            <td><b>{{ $det->nombre_producto }}</b> / {{ $det->descripcion_producto }}</td>
                            <td>{{ $det->cantidad }}</td>							
                            <td>$ {{ number_format($det->precio, 2, ',', '.') }}</td>
							@if ($datoOrdenCompra[0]['tipo'] == 'CL')
								<td>$ {{ number_format($det->flete, 2, ',', '.') }}</td>
								<td>$ {{ number_format($det->comision_venta, 2, ',', '.') }}</td>
								<td>$ {{ number_format($det->comision_gestion, 2, ',', '.') }}</td>
							@endif
							<td>$ {{ number_format($det->flete + $det->comision_venta + $det->comision_gestion + $det->precio, 2, ',', '.') }}</td>
                            <td>$ {{ number_format(($det->flete + $det->comision_venta + $det->comision_gestion + $det->precio) * $det->cantidad, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
							@if ($datoOrdenCompra[0]['tipo'] == 'CL')
								<th></th>
								<th></th>
								<th></th>
							@endif
							<th></th>
                            <th>Total sin IVA: </th>
                            <td>$ {{ number_format($datoOrdenCompra[0]['total_siniva'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
							@if ($datoOrdenCompra[0]['tipo'] == 'CL')
								<th></th>
								<th></th>
								<th></th>
							@endif
							<th></th>
                            <th>IVA 10,5%: </th>
                            <td>$ {{ number_format($datoOrdenCompra[0]['total_iva105'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
							@if ($datoOrdenCompra[0]['tipo'] == 'CL')
								<th></th>
								<th></th>
								<th></th>
							@endif
							<th></th>
                            <th>IVA 21%: </th>
                            <td>$ {{ number_format($datoOrdenCompra[0]['total_iva21'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
							@if ($datoOrdenCompra[0]['tipo'] == 'CL')
								<th></th>
								<th></th>
								<th></th>
							@endif
							<th></th>
                            <th>Total: </th>
                            <td>$ {{ number_format($datoOrdenCompra[0]['total'], 2, ',', '.') }}</td>
                        </tr>																		
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p>Observaciones: <b>{{ $datoOrdenCompra[0]['obs'] }}</b></p>
            </div>
        </footer>
		<div>&nbsp;</div>
		<div>&nbsp;</div>
		<div style="border: none; border-top: 1px solid #000000;; padding: 2px; padding-bottom: -4px; text-align: center; border-bottom: none;">&nbsp;</div>

		<div id="firma">
				<b>Ing. Agronomo Diego Collado</b>
				<br>Celular:(+54)93875088902
        </div>		
	</body>
</html>