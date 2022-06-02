

$("#frmAcceso").on('submit',function(e) {

	e.preventDefault();
    $('.login-btn').html('<i class="fas fa-spinner fa-pulse fa-lg"></i> Comprobando...').addClass('disabled');
    logina=$("#logina").val();
    clavea=$("#clavea").val();

    $.post("../ajax/usuario.php?op=verificar",{"logina":logina,"clavea":clavea}, function(e){
        try {
            e = JSON.parse(e); console.log(e);

            if (e.status){
                if (e.data == null ) {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Usuario y/o Password incorrectos',
                        subtitle: 'cerrar',
                        body: 'Ingrese sus credenciales correctamente, o pida al administrador de sistema restablecer sus credenciales.'
                    });
                } else {
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Bienvenido al sistema "Admin Sevens"',
                        subtitle: 'cerrar',
                        body: 'Se inicio sesion correctamente. Te hemos extrañado, estamos muy contentos de tenerte de vuelta.'
                    });
        
                    $(location).attr("href","escritorio.php");
                }
                
            } else {
                ver_errores(e); 
            }
        } catch (error) {
            ver_errores(error); 
        }
        
        $('.login-btn').html('Ingresar').removeClass('disabled');
    });
})