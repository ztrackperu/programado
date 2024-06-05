<?php
require_once 'config.php';
require_once 'conexion.php';
class PermisosModel{
    private $pdo, $con;
    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }

    public function getPermiso($permiso, $id_usuario)
    {
        $consult = $this->pdo->prepare("SELECT id_permiso FROM detalle_permisos WHERE id_permiso = ? AND id_usuario = ?");
        $consult->execute([$permiso, $id_usuario]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }

    public function saveControl($nombre_empresa, $fecha )
    {
        $consult = $this->pdo->prepare("INSERT INTO control (nombre_dispositivo, fecha_creacion) VALUES (?,?)");
        return $consult->execute([$nombre_empresa, $fecha]);
    }
    // aqui va las consultas con la tabla en vivo 
    public function existeListaContral()
    {
        $consult = $this->pdo->prepare("SELECT *  FROM control_dispositivos WHERE   estado_control = 1");
        $consult->execute();
        return $consult->fetchAll(PDO::FETCH_ASSOC) ;
    }
    
    public function consultar_codigo_alarma($dispositivo,$codigo_alarma)
    {
        $consult = $this->pdo->prepare("SELECT count(*)   FROM alarmas_pendientes WHERE  estado_alarma = 1 and dispositivo =? and codigo_nombre_alarma =? ");
        $consult->execute([$dispositivo,$codigo_alarma]);
        return $consult->fetch(PDO::FETCH_ASSOC) ;
    }
    public function consultarDispositivo($telemetria_id )
    {
        $consult = $this->pdo->prepare("SELECT descripcionC,empresa_id,set_point,temp_supply_1,return_air, evaporation_coil,ambient_air,relative_humidity, power_state ,extra_1 FROM contenedores WHERE telemetria_id = ? AND estado = 1");
        $consult->execute([$telemetria_id]);
        return $consult->fetch(PDO::FETCH_ASSOC);
    }
    public function guardar_alarma($dispositivo,$telemetria_id,$codigo_nombre_alarma)
    {
        $consult = $this->pdo->prepare("INSERT INTO alarmas_pendientes (dispositivo, telemetria_id,codigo_nombre_alarma) VALUES (?,?,?)");
        return $consult->execute([$dispositivo,$telemetria_id,$codigo_nombre_alarma]);

    }

    

}

?>