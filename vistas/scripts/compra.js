var tabla;
var tablamateriales;
//Requejo99@
//Función que se ejecuta al inicio
function init() {
  
  listar();

  fecha_actual();

  //Cargamos los items al select cliente
	$.post("../ajax/compra.php?op=selectProveedor", function(r){
    $("#idproveedor").html(r);
	});

  $("#mCompra").addClass("active");

  // $("#lUsuario").addClass("active");

  $("#guardar_registro_compras").on("click", function (e) { 
    $("#submit-form-compras").submit(); 
    console.log('registrando');
  });
  $("#guardar_registro_proveedor").on("click", function (e) { 
    $("#submit-form-proveedor").submit(); 
    console.log('registrando');
  });

  //Initialize Select2 Elements
  $("#idproveedor").select2({
    theme: "bootstrap4",
    placeholder: "Selecione trabajador",
    allowClear: true,
  });
  
  //Initialize Select2 Elements
  $("#tipo_comprovante").select2({
    theme: "bootstrap4",
    placeholder: "Selecione Comprobante",
    allowClear: true,
  });

  $("#idproveedor").val("null").trigger("change");
  $("#tipo_comprovante").val("Factura").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();   
}

function fecha_actual() {
  //Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
  console.log(today);
  $('#fecha_compra').val(today);
}

//Función limpiar
function limpiar() {
  //Mostramos los selectProveedor
	$.post("../ajax/compra.php?op=selectProveedor", function(r){
    $("#idproveedor").html(r);
	});

  $("#idusuario").val("");
  $("#trabajador_c").html("Trabajador");
  $("#idproveedor").val("null").trigger("change"); 
  $("#tipo_comprovante").val("Factura").trigger("change"); 

 // $("#fecha_compra").val("");
  $("#serie_comprovante").val("");  
  $("#descripcion").val("");  

  $("#total_venta").val("");
	$(".filas").remove();
	$("#total").html("0");

}

function ver_form_add() {
  $("#tabla-compra").hide();
  $("#agregar_compras").show();
  $("#regresar").show();
  $("#btn_agregar").hide();
  $("#guardar_registro_compras").hide(); 
  listarmateriales();
}

function regresar() {
  $("#regresar").hide();
  $("#tabla-compra").show();
  $("#agregar_compras").hide();
  $("#btn_agregar").show();
  limpiar();
}

//Función Listar
function listar() {

  tabla=$('#tabla-usuarios').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/usuario.php?op=listar',
        type : "get",
        dataType : "json",						
        error: function(e){
          console.log(e.responseText);	
        }
      },
    "language": {
      "lengthMenu": "Mostrar : _MENU_ registros",
      "buttons": {
        "copyTitle": "Tabla Copiada",
        "copySuccess": {
          _: '%d líneas copiadas',
          1: '1 línea copiada'
        }
      }
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardaryeditar(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#tabla-compra").hide();
  $("#agregar_compras").show();
  var formData = new FormData($("#form-compras")[0]);

  $.ajax({
    url: "../ajax/usuario.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
             
      if (datos == 'ok') {

				toastr.success('Usuario registrado correctamente')				 

	      tabla.ajax.reload();
         
				limpiar();

        $("#modal-agregar-usuario").modal("hide");

			}else{

				toastr.error(datos)
			}
    },
  });
}

function mostrar(idusuario) {
  $("#trabajador").val("").trigger("change"); 
  $("#trabajador_c").html("(Nuevo) Trabajador");
  $("#cargando-1-fomulario").hide();
  $("#cargando-2-fomulario").show();

  $("#modal-agregar-usuario").modal("show")

  $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);   

    $("#cargando-1-fomulario").show();
    $("#cargando-2-fomulario").hide();
    
    $("#trabajador_old").val(data.idtrabajador); 
    $("#cargo").val(data.cargo).trigger("change"); 
    $("#login").val(data.login);
    $("#password-old").val(data.password);
    $("#idusuario").val(data.idusuario);

    if (data.imagen != "") {

			$("#foto2_i").attr("src", "../dist/img/usuarios/" + data.imagen);

			$("#foto2_actual").val(data.imagen);
		}
  });

  $.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function (r) {

    $("#permisos").html(r);
  });
}

//Función para desactivar registros
function desactivar(idusuario) {
  Swal.fire({
    title: "¿Está Seguro de  Desactivar  el Usuario?",
    text: "Este usuario no podrá ingresar al sistema!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, desactivar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function (e) {
        if (e == 'ok') {

          Swal.fire("Desactivado!", "Tu usuario ha sido Desactivado.", "success");		 
  
          tabla.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });   
}

//Función para activar registros
function activar(idusuario) {

  Swal.fire({

    title: "¿Está Seguro de  Activar  el Usuario?",
    text: "Este usuario tendra acceso al sistema",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, activar!",

  }).then((result) => {

    if (result.isConfirmed) {

      $.post("../ajax/usuario.php?op=activar", { idusuario: idusuario }, function (e) {

        if (e == 'ok') {

          Swal.fire("Activado!", "Tu usuario ha sido activado.", "success");		 
  
          tabla.ajax.reload();
          
        }else{
  
          Swal.fire("Error!", e, "error");
        }
      });      
    }
  });      
}
/**===============================
 * ======================================
 * =========
 */
//Función ListarArticulos
function listarmateriales()
{
	tablamateriales=$('#tblamateriales').dataTable(
	{
		responsive: true,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: [		          
		            
		        ],
		"ajax":
				{
					url: '../ajax/compra.php?op=listarMaterialescompra',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
var cont=0;
var detalles=0;

function agregarDetalle(idproducto,nombre,precio_unitario,img) {
  var stock=5
  var cantidad=1;
  var descuento=0;

  if (idproducto!=""){

    // $('.producto_'+idproducto).addClass('producto_selecionado');
      if ( $('.producto_'+idproducto).hasClass('producto_selecionado') ) {

        toastr.success('Material: '+nombre+ ' agregado !!');
        var cant_producto = $('.producto_'+idproducto).val();
        var sub_total = parseInt(cant_producto,10) + 1;
        $('.producto_'+idproducto).val(sub_total );
        modificarSubototales();
      } else {          
      
        var subtotal=cantidad*precio_unitario;
        var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td>'+
          '<input type="hidden" name="idproducto[]" value="'+idproducto+'">'+
          '<div class="user-block">'+
            '<img class="profile-user-img img-responsive img-circle" src="../dist/img/materiales/'+img+'" alt="user image">'+
            '<span class="username"><p style="margin-bottom: 0px !important;">'+nombre+'</p></span>'+
          '</div>'+
        '</td>'+
        '<td><input onkeyup="modificarSubototales()" onchange="modificarSubototales()" class="producto_'+idproducto+' producto_selecionado" type="number" name="cantidad[]" id="cantidad[]" min="1" value="'+cantidad+'"></td>'+
        '<td><input type="number" name="precio_unitario[]" id="precio_unitario[]" value="'+precio_unitario+'" onkeyup="modificarSubototales()" onchange="modificarSubototales()"></td>'+
        '<td><input type="number" name="descuento[]" value="'+descuento+'" onkeyup="modificarSubototales()" onchange="modificarSubototales()"></td>'+
        '<td class="text-right"><span class="text-right" name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
        '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fas fa-sync"></i></button></td>'+
        '</tr>';
        cont++;
        detalles=detalles+1;
        $('#detalles').append(fila);
        modificarSubototales();
        toastr.success('Material: '+nombre+ ' agregado !!')
      }
   
  } else {
    // alert("Error al ingresar el detalle, revisar los datos del artículo");
    toastr.error('Error al ingresar el detalle, revisar los datos del material.')
  }
}

function evaluar(){
  if (detalles>0)
  {
    $("#guardar_registro_compras").show();
  }
  else
  {
    $("#guardar_registro_compras").hide(); 
    cont=0;
  }
}
function modificarSubototales() {
  var cant = document.getElementsByName("cantidad[]");
  var prec = document.getElementsByName("precio_unitario[]");
  var desc = document.getElementsByName("descuento[]");
  var sub = document.getElementsByName("subtotal");

  for (var i = 0; i <cant.length; i++) {
    var inpC=cant[i];
    var inpP=prec[i];
    var inpD=desc[i];
    var inpS=sub[i];

    inpS.value=(inpC.value * inpP.value)-inpD.value;
    document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
  }
  calcularTotales();
  toastr.success('Precio Actualizado !!!');

}

function calcularTotales(){
  var sub = document.getElementsByName("subtotal");
  var total = 0.0;

  for (var i = 0; i <sub.length; i++) {
  total += document.getElementsByName("subtotal")[i].value;
}
$("#total").html("S/. " + total);
  $("#total_venta").val(total);
  evaluar();
}

function eliminarDetalle(indice){
  $("#fila" + indice).remove();
  calcularTotales();
  detalles=detalles-1;
  evaluar();
  toastr.warning('Material removido.'); 
}



init();

$(function () {

  $.validator.setDefaults({

    submitHandler: function (e) {
        guardaryeditar(e);
        //console.log('factura 22222');

    },
  });

  $("#form-compras").validate({
    rules: {
      idproveedor: { required: true},
      tipo_comprovante: {required: true },
      serie_comprovante: {minlength: 1},
      descripcion: {minlength: 1},
      fecha_compra: {minlength: 1}
      // terms: { required: true },

    },
    messages: {
      idproveedor: {
        required: "Por favor debe seleccionar un proveedor."
      },
      tipo_comprovante: {
        required: "Por favor debe tipo de comprobante."
      },
      serie_comprovante: {
        minlength:"mayor a un caracter"
      },
      descripcion: {
        minlength:"mayor a un caracter"
      },
      fecha_compra: {
        minlength:"mayor a un caracter"
      }
    },
    
    errorElement: "span",

    errorPlacement: function (error, element) {

      error.addClass("invalid-feedback");

      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {

      $(element).addClass("is-invalid");
    },

    unhighlight: function (element, errorClass, validClass) {
    
    },
  });
});

