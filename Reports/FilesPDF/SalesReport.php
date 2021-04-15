<?php 
	include "../../ConfigApp/Conexion.php";

	$cityFilter = $_GET['city'];
	$agentName = $_GET['agentName'];
	$conexion = new Conexion();
	$conexion->abrirConexion();
	$queryData = array();
	$queryToExecute = "";
	$titleReport = "";

	$hoy = getdate();
	$day = $hoy["mday"];
	$month = $hoy["mon"];
	$year = $hoy["year"];
	$dateLabel = "Fecha: " . $day . "/" . $month . "/" . $year;

	$rep = array("|");
	$cityFilter = str_replace($rep, " ", $cityFilter);
	$agentName = str_replace($rep, " ", $agentName);


	if ($cityFilter == "all") {
		$titleReport = "Reporte General de Ventas";
		$queryToExecute = " SELECT p.city, p.address, pt.description, p.code_folio codeFolio,  
							CONCAT(peo2.name, ' ', peo2.apaterno, ' ', peo2.amaterno) costumerName, 
							CONCAT(peo1.name, ' ', peo1.apaterno) agentName, CONCAT(p.price, ' $') priceCatastral,  
							CONCAT(o.offer_price, ' $') offerPrice, CONCAT(s.sale_price, ' $') salePrice,
							SUBSTRING(o.created_at FROM 1 FOR 10) offerDate,  SUBSTRING(s.created_at FROM 1 FOR 10) saleDate
							FROM sales s INNER JOIN offers o ON s.offer_id = o.offer_id 
							INNER JOIN products p ON p.id_product = o.id_product 
							INNER JOIN costumers c ON c.costumer_id = o.costumer_id 
							INNER JOIN product_types pt ON pt.id_product_type = p.id_product_type 
							INNER JOIN users u ON u.user_id = s.user_id  
							INNER JOIN people AS peo1 ON peo1.people_id = u.people_id  
							INNER JOIN people AS peo2 ON peo2.people_id = c.people_id 
							WHERE 1 = 1 ";
		if (!empty($agentName)) {
			$queryToExecute = $queryToExecute . " AND ( CONCAT_WS(' ', peo1.name, peo1.apaterno) LIKE '%" . $agentName . "%' ) ";
		}
		$queryToExecute = $queryToExecute . " ORDER BY s.created_at DESC ";

	} else {
		$titleReport = "Reporte de Ventas " . $cityFilter;
		$queryToExecute = " SELECT p.city, p.address, pt.description, p.code_folio codeFolio,  
							CONCAT(peo2.name, ' ', peo2.apaterno, ' ', peo2.amaterno) costumerName, 
							CONCAT(peo1.name, ' ', peo1.apaterno) agentName, CONCAT(p.price, ' $') priceCatastral,  
							CONCAT(o.offer_price, ' $') offerPrice, CONCAT(s.sale_price, ' $') salePrice,
							SUBSTRING(o.created_at FROM 1 FOR 10) offerDate,  SUBSTRING(s.created_at FROM 1 FOR 10) saleDate
							FROM sales s INNER JOIN offers o ON s.offer_id = o.offer_id 
							INNER JOIN products p ON p.id_product = o.id_product 
							INNER JOIN costumers c ON c.costumer_id = o.costumer_id 
							INNER JOIN product_types pt ON pt.id_product_type = p.id_product_type 
							INNER JOIN users u ON u.user_id = s.user_id  
							INNER JOIN people AS peo1 ON peo1.people_id = u.people_id  
							INNER JOIN people AS peo2 ON peo2.people_id = c.people_id 
							WHERE p.city = '" . $cityFilter . "'";
		if (!empty($agentName)) {
			$queryToExecute = $queryToExecute . " AND ( CONCAT_WS(' ', peo1.name, peo1.apaterno) LIKE '%" . $agentName . "%' ) ";
		}
		$queryToExecute = $queryToExecute . " ORDER BY s.created_at DESC ";
	}

	try {
		$fetchData = $conexion->obtenerConexion()->prepare($queryToExecute);
		$fetchData->execute();
		$cont = 0;
    	while($row=$fetchData->fetch(PDO::FETCH_ASSOC)){
        	$queryData[$cont] = $row;
        	$cont = $cont + 1;
    	}

		$conexion->cerrarConexion();

	} catch (Exception $ex) {
		print "Error: " . $ex -> getMessage() . "<br>";
		$conexion->cerrarConexion();
		die();
	}

 ?>

 <!DOCTYPE html>
 <html lang="es">
 <head>
 	<meta charset="utf-8" />
 	<style type="text/css">
 		table {
   			width: 100%;
   			border-color: black;
   			-moz-border-radius:10px;
    		-webkit-border-radius:10px;
    		border-radius:10px;
		}
		th {
			background: #0d335d;
			color: white;
		}
		th, td {
			text-align: center;
			border-color: black;
		}
		.text-title {
			font-family: sans-serif;
		}
		img {
			float: right;
		}
		caption {
			font-weight: 250%;
			margin-bottom: 15px;
		}
 		
 	</style>
 	<title>Reportes</title>
 </head>
 <body>

 	<img src="../../Files/Images/casa01.jpg" width="170" height="170" />

 	<h1 class="text-title">DEMO PROGRAMACION 3</h1>
 	<h2 class="text-title"><?php echo $titleReport; ?></h2>
	<h3 class="text-title"><?php echo "Registros Encontrados: " . sizeof($queryData); ?><h3/> 
	<h4><?php echo $dateLabel; ?></h4>
	
 	<table border="1">
 		<caption class="text-title title-table">Lista de Ventas Realizadas</caption>
 		<tr>
 			<th>Ciudad</th>
			<th>Direccion</th>
			<th>Tipo</th>
			<th>Folio</th>
			<th>Cliente</th>
			<th>Agente</th>
			<th>Precio Catastral</th>
			<th>Precio de Oferta</th>
			<th>Precio de Venta</th>
			<th>Fecha de Oferta</th>
			<th>Fecha de Venta</th>
 		</tr>

 		<?php 
			foreach ($queryData as $key => $value):
				?>
				<tr>
		<?php 
				foreach ($value as $k => $val):
	 	?>

	 				<td><?php echo $val; ?></td>

	 	<?php 
 		 		endforeach;
 		?>
 		 		</tr>
 		<?php
			endforeach;
	  	?>

 	</table>
 </body>
 </html>