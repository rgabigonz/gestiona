<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Nota de Venta</title>
		<style>
			body {
				/*position: relative;*/
				/*width: 16cm;  */
				/*height: 29.7cm; */
				/*margin: 0 auto; */
				/*color: #555555;*/
				/*background: #FFFFFF; */
				font-family: Arial, sans-serif; 
				font-size: 14px;
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
				padding: 20px;
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
				text-align: center; 
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
					Fecha de Pedido: {{ date('d-m-Y', strtotime($datoNotaPedido[0]['fecha'])) }}
					<br>Nota de Venta N° {{ $datoNotaPedido[0]['anio_id'] }} - {{ $datoNotaPedido[0]['anio_actual'] }}
				</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="nvcliente">
                    <thead>                        
                        <tr>
                            <th id="nv">Cliente</th>
							<th id="nv">Distribuidor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
								<p id="cliente">Sr(a). {{ $datoNotaPedido[0]['nombre_cliente'] }}<br>
												CUIT: {{ $datoNotaPedido[0]['numero_documento'] }}
								</p>
							</th>
                            <th>
								<p id="cliente">{{ $datoNotaPedido[0]['nombre_vendedor_venta'] }}</p>
							</th>
                        </tr>
                    </tbody>
                </table>
            </div>
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
							<th>Flete</th>
							<th>C. Venta</th>
							<th>P. Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datoNotaPedidoD as $det)
                        <tr>
							<td><b>{{ $det->nombre_producto }}</b> / {{ $det->descripcion_producto }}</td>
                            <td>{{ $det->cantidad }}</td>							
                            <td>$ {{ number_format($det->precio, 2, ',', '.') }}</td>
							<td>$ {{ number_format($det->flete, 2, ',', '.') }}</td>
							<td>$ {{ number_format($det->comision_venta, 2, ',', '.') }}</td>
							<td>$ {{ number_format($det->flete + $det->comision_venta + $det->precio, 2, ',', '.') }}</td>
							<td>$ {{ number_format(($det->flete + $det->comision_venta + $det->precio) * $det->cantidad, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
						<tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							<th></th>
                            <th>Total sin IVA: </th>
                            <td>$ {{ number_format($datoNotaPedido[0]['total_siniva'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							<th></th>
                            <th>IVA 10,5%: </th>
                            <td>$ {{ number_format($datoNotaPedido[0]['total_iva105'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							<th></th>
                            <th>IVA 21%: </th>
                            <td>$ {{ number_format($datoNotaPedido[0]['total_iva21'], 2, ',', '.') }}</td>
                        </tr>												
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							<th></th>
                            <th>TOTAL</th>
                            <td>$ {{ number_format($datoNotaPedido[0]['total'], 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>		
	</body>
</html>