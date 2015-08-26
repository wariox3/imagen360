<?php
//http://casamadrugada.net/tutoriales/php/como-almacenar-archivos-imagenes-en-mysql-utilizando-php/
include 'conexion.php';	
$strIdConductor = $_REQUEST['TxtIdConductor'];
$arConductores = $servidor->query("SELECT IdConductor FROM conductores WHERE IdConductor = '" . $strIdConductor . "'");
$arConductor = $arConductores->fetch_assoc();
if ($arConductores->num_rows > 0) {
    //comprobamos si ha ocurrido un error.
    if ( !isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] > 0){
            echo "ha ocurrido un error";
    } else {
            //ahora vamos a verificar si el tipo de archivo es un tipo de imagen permitido.
            //y que el tamano del archivo no exceda los 16MB
            $permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png");
            $limite_kb = 300;

            if (in_array($_FILES['imagen']['type'], $permitidos) && $_FILES['imagen']['size'] <= $limite_kb * 1024){

                    if($strIdConductor) {
                        //este es el archivo temporal
                        $imagen_temporal  = $_FILES['imagen']['tmp_name'];
                        //este es el tipo de archivo
                        $tipo = $_FILES['imagen']['type'];
                        //leer el archivo temporal en binario
                        $fp     = fopen($imagen_temporal, 'r+b');
                        $data = fread($fp, filesize($imagen_temporal));
                        fclose($fp);

                        //escapar los caracteres
                        $data = mysql_escape_string($data);                    
                        $arConductoresImagen = $servidor->query("SELECT IdConductorImagen FROM conductores_imagen WHERE IdConductorImagen = '" . $strIdConductor . "'");
                        $arConductorImagen = $arConductoresImagen->fetch_assoc();
                        if ($arConductoresImagen->num_rows > 0) {
                            $strInsertar = "UPDATE conductores_imagen SET imagen = '$data', tipo_imagen = '$tipo' WHERE IdConductorImagen = '$strIdConductor'";                    
                        } else {
                            $strInsertar = "INSERT INTO conductores_imagen (IdConductorImagen, imagen, tipo_imagen) VALUES ('$strIdConductor','$data', '$tipo')";                    
                        }
                        
                        if ($servidor->query($strInsertar) === TRUE) {
                             echo "La foto ha sido subida satisfactoriamente. <a href='index.php'>Volver</a>";
                        } else {
                            echo "Error: " .$servidor->error . $strInsertar . "<br />";
                        }                                        
                    }
            } else {
                    echo "archivo no permitido, es tipo de archivo prohibido o excede el tamano de $limite_kb Kilobytes";
            }
    }    
} else {
    echo "El conductor " . $strIdConductor . " no existe. <a href='index.php'>Volver</a>";
}
?>