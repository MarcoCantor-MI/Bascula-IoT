<?php
require 'credentials.inc'; 
$id = 1;

echo '
<!DOCTYPE html>
<html>
	<head>
		<title>Proyecto bascula</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- CSS -->
		<link rel="stylesheet" href="./bulma.min.css">
		<link rel="stylesheet" href="./styleteclado.css">
	</head>
	<body>
		<div class="container is-fullhd px-3 has-text-centered">
        <h1 class="is-size-4 is-size-4-mobile has-text-weight-bold text pt-3 pb-3">Proyecto bascula</h1> 
        
        ';
	
	$query1 = 'SELECT * FROM bascula WHERE id_bascula = '.$id.'';
        //echo $query1;
        $result1 = mysqli_query($link,$query1);
        if($result1 == false || $result1 == NULL){
	echo 'No se encontró la bascula';
        } else {
		$query2 = 'SELECT * FROM ventas WHERE bascula = '.$id.'';
		//echo $query2;
		$result2=mysqli_query($link,$query2);
		echo '<h1 class="is-size-4 is-size-4-mobile has-text-weight-bold text pt-3 pb-3">Ventas de '.$id.'</h1> ';
		if($result2){
			while($res2=$result2->fetch_array(MYSQLI_ASSOC)){
				echo'
				<p> Producto: '.$res2['nombre_producto'].' Precio: '.$res2['precio'].'  Peso: '.$res2['peso'].' Total:  '.$res2['total'].' fecha y hora: '.$res2['fecha_hora'].'
				</p>';                    
			}
		}
		else{
			echo '<p>Error al obtener ventas: '.mysqli_error($link).'</p>';
		}
            }
            
echo '
	<div>
		<h1 class="is-size-4 is-size-4-mobile has-text-weight-bold text pt-3 pb-3">Agregar venta</h1> 
		<form id="ventas" action="#" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal column is-three-fifths is-offset-one-fifth">
				<label class="label text px-3">Producto</label>
				<input class="input is-normal" type="text" name="nombre_producto">
				<label class="label text px-3">Precio</label>
				<input class="input is-normal" type="text" name="precio">
				<button type="submit" class="button is-info" name="submit" form="ventas">Agregar</button>
			</div>
		</form>    
        </div>
	<script>
	    document.addEventListener("DOMContentLoaded", function () {
		fetch("tecladoalfanumerico.html")
		    .then(response => response.text())
		    .then(data => {
			let container = document.createElement("div");
			container.innerHTML = data;
			document.body.appendChild(container);
		    });
		let activeInput=null; //variable para almacenar el input activo

		function showKeyboard(inputField) {
		    let keyboard = document.getElementById("keyboard");
		    keyboard.style.display = "grid";
		    activeInput=inputField; // Guardamos el campo activo
		}
		
		function addNumber(num){
			activeInput.value +=num;
		}

		function addLetter(letter) {
			activeInput.value +=letter;
		}

		function clearDisplay() {
			activeInput.value ="";
		}

		function deleteLast() {
			activeInput.value=activeInput.value.slice(0,-1);
		}

		// Activar teclado cuando se selecciona un input
		document.querySelectorAll("input").forEach(input => {
		    input.addEventListener("focus", function () {
			showKeyboard(this);
		    });
		});

		// Ocultar teclado si se hace clic fuera
		document.addEventListener("click", function (event) {
		    let keyboard = document.getElementById("keyboard");
		    if (!keyboard.contains(event.target) && !event.target.matches("input")) {
			keyboard.style.display = "none";
		    }
		});
		
		// Delegar eventos a los botones del teclado
		document.addEventListener("click", function (event) {
		    if (event.target.tagName === "BUTTON" && event.target.parentElement.id === "keyboard") {
			let letter = event.target.innerText;
			if (letter === "←") {
			    deleteLast();
			} else if (letter === "C") {
			    clearDisplay();
			} else {
			    addLetter(letter);
			}
		    }
		});
	    });
	</script>

</body>
</html>';

if( isset($_POST['submit'])){
	$nombre_producto = $_POST['nombre_producto'];
	$precio = $_POST['precio'];
	$peso=shell_exec('./comunicacion_usb.sh');
	//echo $peso;
	//echo "<pre>$peso</pre>";
	//echo "El valor obtenido es: $peso";
	//echo shell_exec('./comunicacion_usb');
	$peso=trim($peso);
	$peso=floatval($peso);
	$total=$peso*$precio;
	//echo "total: $total";
	$query3="INSERT INTO ventas(nombre_producto, peso, precio, total, bascula) VALUES ('".$_POST['nombre_producto']."', ".$peso.", '".$_POST['precio']."', ".$total.", '".$id."')";
	echo $query3;
	if(mysqli_query($link, $query3)) {
		echo '<script language="JavaScript">alert("Tu venta se registró correctamente! Gracias!");</script>';
		echo '<script language="javascript">window.location="./pruebabascula.php"</script>';
	}else{
		echo '<div><h2>Error: '.$query3.'<br>'.mysqli_error($link).'<div><h2>';
	}
}
?>
