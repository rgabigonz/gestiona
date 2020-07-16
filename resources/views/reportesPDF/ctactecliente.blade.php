<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Cuenta Corriente</title>
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
            <div id="datos">
                <p id="encabezado">
                    <b>{{ $ctacte_clientes[0]['nombre_cliente'] }}</b>
                </p>
            </div>
            <div id="nven">

            </div>
        </header>
        <br>
        <section>

        </section>
        <br>
        <br>
        <footer>

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