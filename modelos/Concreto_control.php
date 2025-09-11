<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Concreto_control
{
  
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros
	public function insertar($dosificaciones)	{	
      // Si viene como JSON string, decodificar
    if (is_string($dosificaciones)) {
      $dosificaciones = json_decode($dosificaciones, true);
    }

    // Validar que sea un array
    if (!is_array($dosificaciones)) {
      return ['status' => false, 'message' => 'El parámetro $dosificaciones no es un array válido'];
    }
    
    
    $sql_0="DELETE FROM dosificacion_concreto ";
    $del_dosc = ejecutarConsulta($sql_0); if ( $del_dosc['status'] == false) {return $del_dosc; }

    $ii = 0;
    $compra_detalle_new = "";

    foreach ($dosificaciones as $row) {

      $kg_cm2      = $row['kg_cm2'] ;
      $psi         = $row['psi'] ;
      $mpa         = $row['mpa'] ;
      $cemento     = $row['cemento_bls'] ;
      $arena       = $row['arena_m3'] ;
      $grava       = $row['grava_m3'] ;
      $hormigon    = $row['hormigon_m3'] ;
      $cant_cmt    = $row['cant_cmt'] ;
      $cant_ar     = $row['cant_ar'] ;
      $cant_gr     = $row['cant_gr'] ;

      // ::::::::::: buscando grupo para asignar :::::::::::
      $sql_1 = "INSERT INTO dosificacion_concreto (r_kg_cm2, r_psi, r_mpa, cemento, arena, grava, hormigon, cant_cmt, cant_ar, cant_gr)
      VALUES ('$kg_cm2', '$psi', '$mpa', '$cemento', '$arena', '$grava', '$hormigon', '$cant_cmt', '$cant_ar', '$cant_gr')";

        
      $insert_dosc = ejecutarConsulta($sql_1); if ( $insert_dosc['status'] == false) {return $insert_dosc; } 

    }

    
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'datos_tr' => '' ];

	}

  public function listar_dosificacion_concreto() {
    $sql_2 ="SELECT * FROM dosificacion_concreto";
    return ejecutarConsultaArray($sql_2);    
  }

  /**NUVEL 1 */

  public function insertar_nivel1($idproyectocontrol_concreto,$fecha_concreto,$r_cemento_usado,$descripcion_concreto,$cuadrilla,$hora_inicio,$hora_termino,$duracion_vaciado) {

    $sql_0 ="SELECT CASE WHEN MAX(codigo) IS NULL THEN '0101' -- si no hay registros
                ELSE CONCAT( LPAD(CAST(SUBSTRING(MAX(codigo),1,2) AS UNSIGNED) + 1, 2, '0'), '01' )
              END AS siguiente_codigo
            FROM control_concreto WHERE nivel = 1;";
    $res_ult_cod = ejecutarConsultaSimpleFila($sql_0); if ( $res_ult_cod['status'] == false) {return $res_ult_cod; }
    $siguiente_codigo = $res_ult_cod['data']['siguiente_codigo'];

    $sql = "INSERT INTO control_concreto (idproyecto, prefijo, descripcion, nivel, codigo, drm_fecha, r_cemento_usado,r_cuadrilla,r_hora_inicio,r_hora_termino,r_duracion_vaciado)
                VALUES ( '$idproyectocontrol_concreto', '$descripcion_concreto', '$descripcion_concreto', 1,'$siguiente_codigo', '$fecha_concreto', '$r_cemento_usado',
                '$cuadrilla','$hora_inicio','$hora_termino','$duracion_vaciado' )";


   // var_dump($sql); die();

    return ejecutarConsulta($sql);
    
  }

  public function editar_nivel1($idcontrol_concreto, $idproyectocontrol_concreto,$fecha_concreto,$r_cemento_usado,$descripcion_concreto,$cuadrilla,$hora_inicio,$hora_termino,$duracion_vaciado){


    $sql="UPDATE control_concreto SET 
    idproyecto='$idproyectocontrol_concreto', prefijo='$descripcion_concreto', descripcion='$descripcion_concreto', drm_fecha='$fecha_concreto', 
    r_cemento_usado='$r_cemento_usado', r_cuadrilla='$cuadrilla',r_hora_inicio='$hora_inicio',r_hora_termino='$hora_termino',r_duracion_vaciado='$duracion_vaciado' 
    WHERE idcontrol_concreto='$idcontrol_concreto'";	

    $edit =  ejecutarConsulta($sql);

    $sql_1="SELECT ( SELECT cc1.drm_bolsas_m3 FROM control_concreto AS cc1 WHERE cc1.codigo LIKE CONCAT(cc0.codigo, '%') AND cc1.nivel = '2' LIMIT 1 ) AS drm_bolsas_m3,
            ( SELECT SUM( cc1.e_concreto_proyectado) as total_e_concreto_proyectado FROM control_concreto AS cc1 WHERE cc1.codigo LIKE CONCAT(cc0.codigo, '%') AND cc1.nivel = '2' LIMIT 1 ) AS total_e_concreto_proyectado,
            ( SELECT SUM( cc1.e_cemento_proyectado) as total_e_cemento_proyectado FROM control_concreto AS cc1 WHERE cc1.codigo LIKE CONCAT(cc0.codigo, '%') AND cc1.nivel = '2' LIMIT 1 ) AS total_e_cemento_proyectado
            FROM control_concreto AS cc0 WHERE cc0.idcontrol_concreto = '$idcontrol_concreto' AND cc0.idproyecto = '$idproyectocontrol_concreto';";

    $res_selec_cod = ejecutarConsultaSimpleFila($sql_1); if ( $res_selec_cod['status'] == false) {return $res_selec_cod; }
    $drm_bolsas_m3 = $res_selec_cod['data']['drm_bolsas_m3'];
    $total_e_concreto_proyect = $res_selec_cod['data']['total_e_concreto_proyectado'];
    $total_e_cemento_proyect = $res_selec_cod['data']['total_e_cemento_proyectado'];

    $sql_2="UPDATE control_concreto SET r_concreto_usado = ($r_cemento_usado/ $drm_bolsas_m3), a_desperdicio_concreto = (($r_cemento_usado/ $drm_bolsas_m3) - $total_e_concreto_proyect),
    a_desperdicio_cemento = ($r_cemento_usado - $total_e_cemento_proyect), a_porcentaje_desperdicio = ( ($r_cemento_usado - $total_e_cemento_proyect)/$total_e_cemento_proyect)*100
    WHERE idcontrol_concreto='$idcontrol_concreto'";
    $res_ult_cod = ejecutarConsulta($sql_2); if ( $res_ult_cod['status'] == false) {return $res_ult_cod; }

    return $edit;
     
  }
  

  public function insertar_subnivel($idcontrol_concreto_p_sn,$idproyecto_sn, $prefijo_sn, $codigo_padre_sn, $fecha_sn, $descripcion_sn, $cantidad_sn,
                                  $largo_sn, $ancho_sn, $alto_sn, $altura_vaciado_sn, $calidad_fc_kg_cm2_sn, $bolsas_m3_sn, $piedra_m3_sn,
                                  $arena_m3_sn, $hormigon_m3_sn, $concreto_proyectado_m3_sn, $cemento_proyectado_m3_sn,$dosificacion_sn) {

    // ::::::::::: buscando grupo para asignar :::::::::::
    $sql_1 = "SELECT CASE WHEN MAX(codigo) IS NULL THEN CONCAT( SUBSTRING('$codigo_padre_sn',1,4), '001') -- si no hay registros
                ELSE CONCAT( SUBSTRING('$codigo_padre_sn',1,4), LPAD(CAST(SUBSTRING(MAX(codigo),5,3) AS UNSIGNED) + 1, 3, '0') )
              END AS siguiente_codigo
            FROM control_concreto WHERE nivel = 2 AND prefijo = '$prefijo_sn' and codigo like '$codigo_padre_sn%';";

    $res_ult_cod = ejecutarConsultaSimpleFila($sql_1); if ( $res_ult_cod['status'] == false) {return $res_ult_cod; }
    $siguiente_codigo = $res_ult_cod['data']['siguiente_codigo'];

    $sql_2 ="UPDATE control_concreto SET e_concreto_proyectado = e_concreto_proyectado+$concreto_proyectado_m3_sn WHERE idcontrol_concreto = '$idcontrol_concreto_p_sn' ";
    $upt_concreto = ejecutarConsulta($sql_2); if ( $upt_concreto['status'] == false) {return $upt_concreto; }

    $sql_2_1 ="UPDATE control_concreto SET e_cemento_proyectado = e_cemento_proyectado+$cemento_proyectado_m3_sn WHERE idcontrol_concreto = '$idcontrol_concreto_p_sn' ";
    $upt_cemento = ejecutarConsulta($sql_2_1); if ( $upt_cemento['status'] == false) {return $upt_cemento; }

    $sql = "INSERT INTO control_concreto (idproyecto, prefijo, descripcion, nivel, codigo, drm_fecha, drm_cantidad,
                                        drm_largo, drm_ancho, drm_alto, drm_altura_vaciado, drm_calidad,
                                        drm_bolsas_m3, drm_piedra_m3, drm_arena, drm_hormigon,
                                        e_concreto_proyectado, e_cemento_proyectado,drm_dosificacion)
                VALUES ( '$idproyecto_sn', '$prefijo_sn', '$descripcion_sn', 2,'$siguiente_codigo', '$fecha_sn', '$cantidad_sn',
                        '$largo_sn', '$ancho_sn', '$alto_sn', '$altura_vaciado_sn', '$calidad_fc_kg_cm2_sn',
                        '$bolsas_m3_sn', '$piedra_m3_sn', '$arena_m3_sn', '$hormigon_m3_sn',
                        '$concreto_proyectado_m3_sn', '$cemento_proyectado_m3_sn','$dosificacion_sn')";

    $inst_cont_control = ejecutarConsulta($sql); if ( $inst_cont_control['status'] == false) {return $inst_cont_control; }

    $sql_concreto_u = " UPDATE control_concreto t2
                        JOIN (SELECT ((r1.r_cemento_usado / r2.drm_bolsas_m3)) AS r_concreto_usado, ((r1.r_cemento_usado / r2.drm_bolsas_m3))* r2.drm_hormigon  AS r_hormigon_m3,(((r1.r_cemento_usado / r2.drm_bolsas_m3))-r1.e_concreto_proyectado) AS desperdicio_concreto_m3,
                        (r1.r_cemento_usado-r1.e_cemento_proyectado) AS desperdicio_cemento, ((r1.r_cemento_usado-r1.e_cemento_proyectado)/r1.e_cemento_proyectado)*100 AS porcentaje_desperdicio
                            FROM control_concreto r1
                            JOIN control_concreto r2 ON r1.prefijo = '$prefijo_sn' AND r1.nivel = 1 AND r1.codigo = '$codigo_padre_sn' AND r2.prefijo = '$prefijo_sn' AND r2.nivel = 2 AND r2.codigo LIKE '$codigo_padre_sn%'
                            LIMIT 1 ) subq
                        SET t2.r_concreto_usado = subq.r_concreto_usado, t2.r_hormigon = subq.r_hormigon_m3, t2.a_desperdicio_concreto = subq.desperdicio_concreto_m3, t2.a_desperdicio_cemento = subq.desperdicio_cemento, t2.a_porcentaje_desperdicio = subq.porcentaje_desperdicio
                        WHERE t2.nivel = 1 AND t2.prefijo = '$prefijo_sn' AND t2.codigo = '$codigo_padre_sn'; ";

    $upt_concreto_u = ejecutarConsulta($sql_concreto_u); if ( $upt_concreto_u['status'] == false) {return $upt_concreto_u; }
                                  
    return $inst_cont_control;
    
  }

  public function listar_concreto ($fecha_i_r,$fecha_f_r){
    $sql = "SELECT *FROM control_concreto WHERE drm_fecha BETWEEN '$fecha_i_r' AND '$fecha_f_r'  ORDER BY SUBSTRING(codigo, 1, 4),  LENGTH(codigo), codigo;";
    return ejecutarConsultaArray($sql);
  }


  public function mostrar_concreto($idcontrol_concreto, $nivel ){
    $sql = "SELECT cc.*, CASE WHEN nivel = '2' THEN ( SELECT idcontrol_concreto FROM control_concreto AS cc0 WHERE cc0.codigo = LEFT(cc.codigo, 4) ) ELSE '0' END AS idcontrol_concreto_relacionado_padre, LEFT(cc.codigo, 4) as codigo_padre
    FROM control_concreto AS cc WHERE idcontrol_concreto = '$idcontrol_concreto';";

    return ejecutarConsultaSimpleFila($sql);

  }

  public function editar_subnivel($idcontrol_concreto_sn,$idcontrol_concreto_p_sn, $idproyecto_sn, $prefijo_sn, $codigo_padre_sn, $fecha_sn, $descripcion_sn, $cantidad_sn,
                                $largo_sn, $ancho_sn, $alto_sn, $altura_vaciado_sn, $calidad_fc_kg_cm2_sn, $bolsas_m3_sn, $piedra_m3_sn,
                                $arena_m3_sn, $hormigon_m3_sn, $concreto_proyectado_m3_sn, $cemento_proyectado_m3_sn,$dosificacion_sn){
      
    $sql="UPDATE control_concreto SET 
    idproyecto='$idproyecto_sn', descripcion='$descripcion_sn', drm_fecha='$fecha_sn', drm_cantidad='$cantidad_sn', drm_largo='$largo_sn',
    drm_ancho='$ancho_sn', drm_alto='$alto_sn', drm_altura_vaciado='$altura_vaciado_sn', drm_calidad='$calidad_fc_kg_cm2_sn', drm_bolsas_m3='$bolsas_m3_sn',
    drm_piedra_m3='$piedra_m3_sn', drm_arena='$arena_m3_sn', drm_hormigon='$hormigon_m3_sn', e_concreto_proyectado='$concreto_proyectado_m3_sn',
    e_cemento_proyectado='$cemento_proyectado_m3_sn', drm_dosificacion='$dosificacion_sn'    
    WHERE idcontrol_concreto='$idcontrol_concreto_sn'";	

    $editar = ejecutarConsulta($sql);	 if ( $editar['status'] == false) {return $editar; }	
    
    $sql_2 ="UPDATE control_concreto t
    JOIN ( SELECT SUM(e_concreto_proyectado) AS total, SUBSTRING(codigo, 1, LENGTH('$codigo_padre_sn')) AS padre FROM control_concreto WHERE codigo LIKE CONCAT('$codigo_padre_sn', '%') AND nivel = '2' ) x ON t.codigo = x.padre
    SET t.e_concreto_proyectado = x.total WHERE t.idcontrol_concreto = '$idcontrol_concreto_p_sn';";
    $upt_concreto = ejecutarConsulta($sql_2); if ( $upt_concreto['status'] == false) {return $upt_concreto; }

    $sql_2_1 ="UPDATE control_concreto t JOIN ( SELECT SUM(e_cemento_proyectado) AS total, SUBSTRING(codigo, 1, LENGTH('$codigo_padre_sn')) AS padre FROM control_concreto WHERE codigo LIKE CONCAT('$codigo_padre_sn','%') AND nivel = '2' ) x ON t.codigo = x.padre
    SET t.e_cemento_proyectado = x.total WHERE t.idcontrol_concreto = '$idcontrol_concreto_p_sn';";
    $upt_cemento = ejecutarConsulta($sql_2_1); if ( $upt_cemento['status'] == false) {return $upt_cemento; }

    
    $sql_concreto_u = " UPDATE control_concreto t2
                        JOIN (SELECT ((r1.r_cemento_usado / r2.drm_bolsas_m3)) AS r_concreto_usado, ((r1.r_cemento_usado / r2.drm_bolsas_m3))* r2.drm_hormigon  AS r_hormigon_m3,(((r1.r_cemento_usado / r2.drm_bolsas_m3))-r1.e_concreto_proyectado) AS desperdicio_concreto_m3,
                        (r1.r_cemento_usado-r1.e_cemento_proyectado) AS desperdicio_cemento, ((r1.r_cemento_usado-r1.e_cemento_proyectado)/r1.e_cemento_proyectado)*100 AS porcentaje_desperdicio
                            FROM control_concreto r1
                            JOIN control_concreto r2 ON r1.prefijo = '$prefijo_sn' AND r1.nivel = 1 AND r1.codigo = '$codigo_padre_sn' AND r2.prefijo = '$prefijo_sn' AND r2.nivel = 2 AND r2.codigo LIKE '$codigo_padre_sn%'
                            LIMIT 1 ) subq
                        SET t2.r_concreto_usado = subq.r_concreto_usado, t2.r_hormigon = subq.r_hormigon_m3, t2.a_desperdicio_concreto = subq.desperdicio_concreto_m3, t2.a_desperdicio_cemento = subq.desperdicio_cemento, t2.a_porcentaje_desperdicio = subq.porcentaje_desperdicio
                        WHERE t2.nivel = 1 AND t2.prefijo = '$prefijo_sn' AND t2.codigo = '$codigo_padre_sn'; ";
    
    //var_dump($sql_concreto_u); die();

    $upt_concreto_u = ejecutarConsulta($sql_concreto_u); if ( $upt_concreto_u['status'] == false) {return $upt_concreto_u; }

    return $editar;
  }

  // Data para listar lo bototnes por quincena
  public function listarquincenas($nube_idproyecto) {
    $sql = "SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_valorizacion 
    FROM proyecto as p 
    WHERE p.idproyecto = '$nube_idproyecto' AND p.fecha_inicio != p.fecha_fin";

    return ejecutarConsultaSimpleFila($sql);
  }


  public function eliminar_concreto_control($idcontrol_concreto,$codigo,$nivel){

    if ($nivel=='1') {
      $slq_0 = "DELETE FROM control_concreto WHERE codigo LIKE '$codigo%';";
      return ejecutarConsulta($slq_0);
    }else {
      //eliminamos
      $sql_1 = "DELETE FROM control_concreto WHERE idcontrol_concreto = '$idcontrol_concreto';";     
      $delete_concreto = ejecutarConsulta($sql_1); if ( $delete_concreto['status'] == false) {return $delete_concreto; }

      /**----------------------------------- */

      $sql_select = "SELECT idcontrol_concreto, prefijo, SUBSTRING(codigo, 1, 4) AS codigo_padre FROM control_concreto WHERE codigo = SUBSTRING('$codigo', 1, 4) ;";

      $res_selec_cod = ejecutarConsultaSimpleFila($sql_select); if ( $res_selec_cod['status'] == false) {return $res_selec_cod; };

      $prefijo_sn = $res_selec_cod['data']['prefijo'];
      $codigo_padre_sn = $res_selec_cod['data']['codigo_padre'];
      $idcontrol_concreto_p_sn = $res_selec_cod['data']['idcontrol_concreto'];

      $sql_2 ="UPDATE control_concreto t
      JOIN ( SELECT SUM(e_concreto_proyectado) AS total, SUBSTRING(codigo, 1, LENGTH('$codigo_padre_sn')) AS padre FROM control_concreto WHERE codigo LIKE CONCAT('$codigo_padre_sn', '%') AND nivel = '2' ) x ON t.codigo = x.padre
      SET t.e_concreto_proyectado = x.total WHERE t.idcontrol_concreto = '$idcontrol_concreto_p_sn';";

      $upt_concreto = ejecutarConsulta($sql_2); if ( $upt_concreto['status'] == false) {return $upt_concreto; }

      $sql_2_1 ="UPDATE control_concreto t JOIN ( SELECT SUM(e_cemento_proyectado) AS total, SUBSTRING(codigo, 1, LENGTH('$codigo_padre_sn')) AS padre FROM control_concreto WHERE codigo LIKE CONCAT('$codigo_padre_sn','%') AND nivel = '2' ) x ON t.codigo = x.padre
      SET t.e_cemento_proyectado = x.total WHERE t.idcontrol_concreto = '$idcontrol_concreto_p_sn';";

      $upt_cemento = ejecutarConsulta($sql_2_1); if ( $upt_cemento['status'] == false) {return $upt_cemento; }

      
      $sql_concreto_u = " UPDATE control_concreto t2
                          JOIN (SELECT ((r1.r_cemento_usado / r2.drm_bolsas_m3)) AS r_concreto_usado, ((r1.r_cemento_usado / r2.drm_bolsas_m3))* r2.drm_hormigon  AS r_hormigon_m3,(((r1.r_cemento_usado / r2.drm_bolsas_m3))-r1.e_concreto_proyectado) AS desperdicio_concreto_m3,
                          (r1.r_cemento_usado-r1.e_cemento_proyectado) AS desperdicio_cemento, ((r1.r_cemento_usado-r1.e_cemento_proyectado)/r1.e_cemento_proyectado)*100 AS porcentaje_desperdicio
                              FROM control_concreto r1
                              JOIN control_concreto r2 ON r1.prefijo = '$prefijo_sn' AND r1.nivel = 1 AND r1.codigo = '$codigo_padre_sn' AND r2.prefijo = '$prefijo_sn' AND r2.nivel = 2 AND r2.codigo LIKE '$codigo_padre_sn%'
                              LIMIT 1 ) subq
                          SET t2.r_concreto_usado = subq.r_concreto_usado, t2.r_hormigon = subq.r_hormigon_m3, t2.a_desperdicio_concreto = subq.desperdicio_concreto_m3, t2.a_desperdicio_cemento = subq.desperdicio_cemento, t2.a_porcentaje_desperdicio = subq.porcentaje_desperdicio
                          WHERE t2.nivel = 1 AND t2.prefijo = '$prefijo_sn' AND t2.codigo = '$codigo_padre_sn'; ";

      $upt_concreto_u = ejecutarConsulta($sql_concreto_u); if ( $upt_concreto_u['status'] == false) {return $upt_concreto_u; }


      return $delete_concreto;
    };

  }



}
 
?>
