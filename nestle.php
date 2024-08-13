<?php
//include 'conexion.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
require_once 'permisos.php';

$api = new PermisosModel();
// tomamos fecha actual
$fecha_actual =date("Y-m-d H:i:s");

require '../correo/prueba_correo1.php';
$corr = new CorreoModel();

$actual_convertido =strtotime($fecha_actual);

$r=$api->existeListaContral();

function procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual)
{
    $api = new PermisosModel();
    $corr = new CorreoModel();
    $a2 = $api->consultar_codigo_alarma($dispositivo,$codigo_alarma);
    if($a2['count(*)']==0){
        $za = $api->guardar_alarma($dispositivo,$telemetria_id,$codigo_alarma);
        if($za){
            $corr->envio_correo2($fecha_actual,$telemetria_id,$codigo_alarma);
        }
    }
}
//procesar_correo("ZGRU-PRUEBA",4393,"B2",$fecha_actual);
foreach($r as $refer){
?>
<p><?= $refer['nombre_contenedor'] ?> </p>
<?php
$dispositivo= $refer['nombre_contenedor'] ;
$telemetria_id = $refer['telemetria_id']; 

$ultima_conexion = strtotime($refer['ultimo_dato']);

$off_2 = strtotime("+2 hours",$ultima_conexion);
$off_3 = strtotime("+3 hours",$ultima_conexion);
$off_4 = strtotime("+4 hours",$ultima_conexion);
$off_6 = strtotime("+6 hours",$ultima_conexion);
$off_8 = strtotime("+8 hours",$ultima_conexion);
$off_10 = strtotime("+10 hours",$ultima_conexion);
$off_12 = strtotime("+12 hours",$ultima_conexion);

if($off_2 >$actual_convertido){
    
    if($refer['estado_on']!=$refer['estado_off']){
        //echo "aqui en 2";
        $estado_off = $refer['estado_off'];
        $ultimo_off =strtotime($estado_off);

        $estado_on = strtotime($refer['estado_on']);

        $inicio_2 = strtotime("+2 hours",$estado_on);
        $inicio_3 = strtotime("+3 hours",$estado_on);
        $inicio_4 = strtotime("+4 hours",$estado_on);
        $inicio_6 = strtotime("+6 hours",$estado_on);
        $inicio_8 = strtotime("+8 hours",$estado_on);
        $inicio_10 = strtotime("+10 hours",$estado_on);
        $inicio_12 = strtotime("+12 hours",$estado_on);

        if($inicio_2 >$ultimo_off){
            // apagado tolerable
            echo "apagado tolerable";
 

        } else if($inicio_3 >$ultimo_off){
            echo " mas de 2 con tramas off ";
            $codigo_alarma = "B2";
                     
            $a2 = $api->consultar_codigo_alarma($dispositivo,$codigo_alarma);
            echo $a2['count(*)'];
            if($a2['count(*)']==0){
                //echo " sin registros";
                //consultar datos del dispositivo
                $za = $api->guardar_alarma($dispositivo,$telemetria_id,$codigo_alarma);
                if($za){
                    $corr->envio_correo2($fecha_actual,$telemetria_id,$codigo_alarma);

                }
                

                
                //$datosD = $api->consultarDispositivo($telemetria_id );
                //echo "fecha reciente de conexion : ".$fecha_actual." buena";
                //$excel_p = datos($fecha_actual,$telemetria_id);
                 
                //echo print_r($datosD);
                //echo print_r($excel_p);


                //envio_correo2($fechaFin,$telemetria_id,$codigo_alarma)
                // enviar correo


                //registrar en tabla de alarmas_pendientes
                

            }

        } else if($inicio_4 >$ultimo_off){
            echo " mas de 3 con tramas off ";
            $codigo_alarma = "B3";  
            procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual);              
        }else if($inicio_6 >$ultimo_off){
            echo " mas de 4 con tramas off ";
            $codigo_alarma = "B4";  
            procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual);   

        }else if($inicio_8 >$ultimo_off){
            echo " mas de 6 con tramas off ";
            $codigo_alarma = "B6";  
            procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual);   

        }else if($inicio_10 >$ultimo_off){
            echo " mas de 8 con tramas off ";
            $codigo_alarma = "B8";  
            procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual);   

        }else if($inicio_12 >$ultimo_off){
            echo " mas de 10 con tramas off ";
            $codigo_alarma = "B10";  
            procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual);   

        }else{
            echo " mas de 12 con tramas off ";
            $codigo_alarma = "B12";  
            procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual);   

        }   

    }else{
                   // evaluar desfase de temperatura 
                   //echo " entro aqui";
                   if($refer['temp_ok']!=$refer['temp_fail']){

                   $temp_fail = $refer['temp_fail'];
                   $ultimo_temp =strtotime($temp_fail);
           
                   $temp_ok = strtotime($refer['temp_ok']);
           
                   $temp_2 = strtotime("+2 hours",$temp_ok);
                   $temp_3 = strtotime("+3 hours",$temp_ok);
                   $temp_4 = strtotime("+4 hours",$temp_ok);
                   $temp_6 = strtotime("+6 hours",$temp_ok);
                   $temp_8 = strtotime("+8 hours",$temp_ok);
                   $temp_10 = strtotime("+10 hours",$temp_ok);
                   $temp_12 = strtotime("+12 hours",$temp_ok);
       
                   if($temp_2>$ultimo_temp){
                       //desfase de temperatura tolerable
                       echo "tiempo de desfase de temperatura tolerable";
       
                   }else if($temp_3>$ultimo_temp){
                       echo " mas de 2 horas con desfase de temperatura ";
                       $codigo_alarma = "C2";  
                       procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual);   
       
                   }else if($temp_4>$ultimo_temp){
                       echo " mas de 3 horas con desfase de temperatura ";
                       $codigo_alarma = "C3";  
                       procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 
       
                   }else if($temp_6>$ultimo_temp){
                       echo " mas de 4 horas con desfase de temperatura ";
                       $codigo_alarma = "C4";  
                       procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 
       
                   }else if($temp_8>$ultimo_temp){
                       echo " mas de 6 horas con desfase de temperatura ";
                       $codigo_alarma = "C6";  
                       procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 
       
                   }else if($temp_10>$ultimo_temp){
                       echo " mas de 8 horas con desfase de temperatura ";
                       $codigo_alarma = "C8";  
                       procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 
       
                   }else if($temp_12>$ultimo_temp){
                       echo " mas de 10 horas con desfase de temperatura ";
                       $codigo_alarma = "C10";  
                       procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 
       
                   }else{
                       echo " mas de 12 horas con desfase de temperatura ";
                       $codigo_alarma = "C12";  
                       procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 
                   }

                }
    }

}else if($off_3 >$actual_convertido){
    echo " mas de 2 horas sin conexion ";
    $codigo_alarma = "A2";  
    procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 

}else if($off_4 >$actual_convertido){
    echo " mas de 3 horas sin conexion ";
    $codigo_alarma = "A3";  
    procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 

}else if($off_6 >$actual_convertido){
    echo " mas de 4 horas sin conexion ";
    $codigo_alarma = "A4";  
    procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 

}else if($off_8 >$actual_convertido){
    echo " mas de 6 horas sin conexion ";
    $codigo_alarma = "A6";  
    procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 

}else if($off_10 >$actual_convertido){
    echo " mas de 8 horas sin conexion ";
    $codigo_alarma = "A8";  
    procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 

}else if($off_12 >$actual_convertido){
    echo " mas de 10 horas sin conexion ";
    $codigo_alarma = "A10";  
    procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 

}else{
    echo " mas de 12 horas sin conexion ";
    $codigo_alarma = "A12";  
    procesar_correo($dispositivo,$telemetria_id,$codigo_alarma,$fecha_actual); 

}

}
//$datos =$r;
//echo json_encode($datos);
?>


