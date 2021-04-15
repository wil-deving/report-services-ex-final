<?php 
	include "../../ConfigApp/Conexion.php";

	$cityFilter = $_GET['city'];
	$interestedName = $_GET['intName'];
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
	$interestedName = str_replace($rep, " ", $interestedName);


	if ($cityFilter == "all") {
		$titleReport = "Reporte General de Consultas";
		$queryToExecute = " SELECT *
							FROM  
							(  SELECT COUNT(1) as totalByOffer, p.city, p.address, pt.description,
							p.code_folio AS codeFolio, CONCAT(o.offer_price, ' $') AS offerPrice,
 							peo.name AS interestedName, i.created_at AS inquiryDate
 							FROM inquiries i INNER JOIN offers o ON i.offer_id = o.offer_id 
 							INNER JOIN products p ON p.id_product = o.id_product  
 							INNER JOIN product_types pt ON pt.id_product_type = p.id_product_type 
 							INNER JOIN interested it ON it.interested_id = i.interested_id 
 							INNER JOIN people peo ON peo.people_id = it.people_id 
 							WHERE o.available = 1
 							GROUP BY i.offer_id  
 							HAVING COUNT(1) > 0  ) tmpInq  
							WHERE 1 = 1 ";
		if (!empty($interestedName)) {
			$queryToExecute = $queryToExecute . " AND tmpInq.interestedName LIKE '%" . $interestedName . "%' ";
		}
		$queryToExecute = $queryToExecute . " ORDER BY tmpInq.totalByOffer DESC, tmpInq.inquiryDate DESC ";

	} else {
		$titleReport = "Reporte de Consultas " . $cityFilter;
		$queryToExecute = " SELECT *
							FROM  
							(  SELECT COUNT(1) as totalByOffer, p.city, p.address, pt.description,
							p.code_folio AS codeFolio, CONCAT(o.offer_price, ' $') AS offerPrice,
 							peo.name AS interestedName, i.created_at AS inquiryDate
 							FROM inquiries i INNER JOIN offers o ON i.offer_id = o.offer_id 
 							INNER JOIN products p ON p.id_product = o.id_product  
 							INNER JOIN product_types pt ON pt.id_product_type = p.id_product_type 
 							INNER JOIN interested it ON it.interested_id = i.interested_id 
 							INNER JOIN people peo ON peo.people_id = it.people_id 
 							WHERE o.available = 1
 							GROUP BY i.offer_id  
 							HAVING COUNT(1) > 0  ) tmpInq  
						 	WHERE tmpInq.city = '" . $cityFilter . "'";
		if (!empty($interestedName)) {
			$queryToExecute = $queryToExecute . " AND tmpInq.interestedName LIKE '%" . $interestedName . "%' ";
		}
		$queryToExecute = $queryToExecute . " ORDER BY tmpInq.totalByOffer DESC, tmpInq.inquiryDate DESC ";
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
 		<caption class="text-title title-table">Lista de Consultas Realizadas</caption>
 		<tr>
 			<th>Cantidad de Solicitudes</th>
 			<th>Ciudad</th>
			<th>Direccion</th>
			<th>Tipo</th>
			<th>Folio</th>
			<th>Precio de oferta</th>
			<th>Interesado</th>
			<th>Fecha de consulta</th>
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