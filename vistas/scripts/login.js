

$("#frmAcceso").on('submit',function(e) {
    $('.login-btn').html('<i class="fas fa-spinner fa-pulse fa-lg"></i> Comprobando...').removeClass('btn-outline-warning').addClass('btn-info disabled');
	e.preventDefault();
    
    logina=$("#logina").val();
    clavea=$("#clavea").val();

    $.post("../ajax/usuario.php?op=verificar",{"logina":logina,"clavea":clavea}, function(e){
        try {
            e = JSON.parse(e); console.log(e);

            setTimeout(validar_response(e), 1000);
            
        } catch (error) {
            $('.login-btn').html('Ingresar').removeClass('disabled btn-info').addClass('btn-outline-warning');
            ver_errores(error);             
        }
    });
})

function validar_response(e) {
    if (e.status){
        if (e.data == null ) {
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Usuario y/o Password incorrectos',
                subtitle: 'cerrar',
                body: 'Ingrese sus credenciales correctamente, o pida al administrador de sistema restablecer sus credenciales.'
            });
            $('.login-btn').html('Ingresar').removeClass('disabled btn-info').addClass('btn-outline-warning');
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
        $('.login-btn').html('Ingresar').removeClass('disabled btn-info').addClass('btn-outline-warning');
        ver_errores(e); 
    }
}