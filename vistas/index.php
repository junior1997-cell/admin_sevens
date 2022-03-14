<?php     

    function enrutamiento($tipo) {
        if ($tipo == 'nube') {
            $link_host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/';
        }else{
            if ($tipo == 'local') {
                $link_host = "http://localhost/admin_sevens/";
            }            
        }
        return $link_host;
    }

    $ruta = enrutamiento('local');

    header("Location: $ruta");
?>