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
			font-size: 20px;
			}

			section{
			clear: left;
			}

			#cliente{
			text-align: left;
			}

			#nvcliente{
			width: 40%;
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
					<!-- <br>Email:jcarlos.ad7@gmail.com -->
                </p>
            </div>
            <div id="nven">
                <p>Nota de Pedido N° {{ $datoOrdenCompra[0]['anio_id'] }} - {{ $datoOrdenCompra[0]['anio_actual'] }}</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="nvcliente">
                    <thead>                        
                        <tr>
                            <th id="nv">Proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
								<p id="cliente">Sr(a). {{ $datoOrdenCompra[0]['nombre_proveedor'] }}<br>
                            					Dirección: {{ $datoOrdenCompra[0]['direccion_proveedor'] }}<br>
                            					Teléfono: {{ $datoOrdenCompra[0]['telefono_proveedor'] }}<br>
                            					Email: {{ $datoOrdenCompra[0]['email_proveedor'] }}
								</p>
							</th>
                        </tr>
                    </tbody>
                </table>
            </div>
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
								<p id="cliente">Sr(a). {{ $datoOrdenCompra[0]['nombre_cliente'] }}<br>
                            					Dirección: {{ $datoOrdenCompra[0]['direccion_cliente'] }}<br>
                            					Teléfono: {{ $datoOrdenCompra[0]['telefono_cliente'] }}<br>
                            					Email: {{ $datoOrdenCompra[0]['email_cliente'] }}
								</p>
							</th>
                        </tr>
                    </tbody>
                </table>
            </div>            
        </section>		
		<br>
        <section>
            <div>
                <table id="nvarticulo">
                    <thead>
                        <tr id="nv">
                            <th>DESCRIPCION</th>
                            <th>CANT</th>							
                            <th>PRECIO</th>
                            <th>Flete</th>
                            <th>C. Venta</th>
                            <th>C. Gestion</th>
                            <th>SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datoOrdenCompraD as $det)
                        <tr>
                            <td>{{ $det->nombre_producto }}</td>
                            <td>{{ $det->cantidad }}</td>							
                            <td>{{ $det->precio }}</td>
                            <td>{{ $det->flete }}</td>
                            <td>{{ $det->comision_venta }}</td>
                            <td>{{ $det->comision_gestion }}</td>
                            <td>{{ $det->flete + $det->comision_venta + $det->comision_gestion + ($det->cantidad * $det->precio) }}</td>
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
                            <th>TOTAL</th>
                            <td>$ {{ $datoOrdenCompra[0]['total'] }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <!-- <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>		 -->
	</body>
</html>