
var array_doc = [];

//Función que se ejecuta al inicio
function init() {

  $("#bloc_Tecnico").addClass("menu-open");

  $("#mTecnico").addClass("active");

  $("#lAlmacen").addClass("active bg-primary");


  idproyecto = localStorage.getItem("nube_idproyecto");

  tabla_almacen();
  // Formato para telefono
  $("[data-mask]").inputmask();

}


function tabla_almacen() {
  $('.data_table_body').html('');
  var codigoHTML="";
  $.post("../ajax/almacen.php?op=tabla_almacen", { 'id_proyecto': idproyecto }, function (e, status) {

    e = JSON.parse(e); console.log(e);

    if (e.status == true) {

      e.data.forEach((val, key) => {
        kn = key+1
        codigoHTML = codigoHTML.concat(`
          <tr>
          <td rowspan="2">${kn}</td>
          <td rowspan="2">${val.idproducto}</td>
          <td class="text_producto" rowspan="2">${val.nombre_producto}</td>
          <td rowspan="2">${val.abreviacion}</td>
          <td rowspan="2">100</td>
          <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
        </tr>
        <tr>
          <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
        </tr>`);

      });
      $('.data_table_body').html(codigoHTML); // Agregar el contenido 




    } else {
      ver_errores(e);
    }
  }).fail(function (e) { ver_errores(e); });
}



init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

