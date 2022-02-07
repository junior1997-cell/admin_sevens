var tabla;

//Función que se ejecuta al inicio
function init() {

 // $("#idproyecto").val(localStorage.getItem('nube_idproyecto'));
 listar_r_compras(localStorage.getItem('nube_idproyecto'));
 listar_r_serv_maquinaria(localStorage.getItem('nube_idproyecto'));
 listar_r_serv_equipos(localStorage.getItem('nube_idproyecto'));
  //Activamos el "aside"
  $("#mOtroServicio").addClass("active");

  // Formato para telefono
  $("[data-mask]").inputmask();

}

function listar_r_compras(idproyecto) {
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0;

  $("#compras").html("");
  $("#monto_compras").html("");  
  $("#pago_compras").html("");  
  $("#saldo_compras").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_compras", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  //console.log(data);  

    data.forEach((value,index)=>{

      if (value.monto_pago_total!=null) {
        calculando_sldo=parseFloat(value.monto_total)-parseFloat(value.monto_pago_total);
        validando_pago=parseFloat(value.monto_pago_total);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.fecha_compra}</span></td>
          <td class="bg-color-b4bdbe47  clas_pading">${value.descripcion==""?'---':value.descripcion}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(parseFloat(value.monto_total).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.monto_total);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#compras").append(compras);

    });

      $("#monto_compras").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_compras").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_compras").html(formato_miles(t_saldo.toFixed(2)));  
     
  });
}

function listar_r_serv_maquinaria(idproyecto) {
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0;

  $("#serv_maquinas").html("");
  $("#monto_serv_maq").html("");  
  $("#pago_serv_maq").html("");  
  $("#saldo_serv_maq").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_serv_maquinaria", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    data.forEach((value,index)=>{

      if (value.monto_pag_ser_maq!=null) {
        calculando_sldo=parseFloat(value.costo_parcial)-parseFloat(value.monto_pag_ser_maq);
        validando_pago=parseFloat(value.monto_pag_ser_maq);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${value.maquina}<span style="font-size: 13px; color: red;"> ${value.cantidad_veces} veces</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-sm" onclick="ver_detalle(${value.idmaquinaria},${value.idproyecto})"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.costo_parcial);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#serv_maquinas").append(compras);

    });

      $("#monto_serv_maq").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_serv_maq").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_serv_maq").html(formato_miles(t_saldo.toFixed(2)));  
     
  });
}

function listar_r_serv_equipos(idproyecto) {
  var compras=''; var t_monto=0; var t_pagos=0; var t_saldo=0; var calculando_sldo=0; var validando_pago=0;

  $("#serv_equipos").html("");
  $("#monto_serv_equi").html("");  
  $("#pago_serv_equi").html("");  
  $("#saldo_serv_equi").html("");

  $.post("../ajax/resumen_general.php?op=listar_r_serv_equipos", { idproyecto: idproyecto }, function (data, status) {

    data = JSON.parse(data);  console.log(data);  

    data.forEach((value,index)=>{

      if (value.monto_pag_ser_maq!=null) {
        calculando_sldo=parseFloat(value.costo_parcial)-parseFloat(value.monto_pag_ser_maq);
        validando_pago=parseFloat(value.monto_pag_ser_maq);
      } else {
        calculando_sldo=0;
        validando_pago=0;
      }

      compras=`<tr>
          <td class="bg-color-b4bdbe47  text-center clas_pading">${index+1}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${value.maquina}<span style="font-size: 13px; color: red;"> ${value.cantidad_veces} veces</span></td>
          <td class="bg-color-b4bdbe47  clas_pading"><span>${value.proveedor}</span></td>
          <td class="bg-color-b4bdbe47 text-center clas_pading"><button class="btn btn-info btn-sm" onclick="ver_detalle(${value.idmaquinaria},${value.idproyecto})"><i class="fa fa-eye"></i></button></td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(parseFloat(value.costo_parcial).toFixed(2))}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(validando_pago.toFixed(2))}</td>
          <td class="bg-color-b4bdbe47  clas_pading">${formato_miles(calculando_sldo.toFixed(2))}</td>
      </tr>`;
      t_monto=t_monto+parseFloat(value.costo_parcial);
      t_pagos=t_pagos+parseFloat(validando_pago);
      t_saldo=t_saldo+parseFloat(calculando_sldo);

      $("#serv_equipos").append(compras);

    });

      $("#monto_serv_equi").html(formato_miles(t_monto.toFixed(2)));  
      $("#pago_serv_equi").html(formato_miles(t_pagos.toFixed(2)));  
      $("#saldo_serv_equi").html(formato_miles(t_saldo.toFixed(2)));  
     
  });
}
//Función detalles por maquina-equipo
function ver_detalle(idmaquinaria,idproyecto,unidad_medida) {

  $("#modal_ver_detalle_maq_equ").modal('show');
  var hideen_colums;
  //console.log('lis_deta '+idproyecto,idmaquinaria,unidad_medida);
  $("#tabla_principal").hide();
  $("#tabla_detalles").show();
  $("#btn-agregar").hide();
  $("#btn-regresar").show();
  $("#btn-pagar").hide();
  if (unidad_medida=='Hora') {
    hideen_colums=[];
    
  }else{
    hideen_colums=[
      {
          "targets": [ 3 ],
          "visible": false,
          "searchable": false
      },
      {
          "targets": [ 4 ],
          "visible": false,
          "searchable": false
      },
      {
          "targets": [ 5 ],
          "visible": false,
          "searchable": false
      }
  ]

  }
 // console.log(hideen_colums);
  tabla2=$('#tabla-detalle-m').dataTable({
    "responsive": true,
    "lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5','pdf', "colvis"],
    "ajax":{
        url: '../ajax/resumen_general.php?op=ver_detalle_maquina&idmaquinaria='+idmaquinaria+'&idproyecto='+idproyecto,
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
    "order": [[ 0, "desc" ]],//Ordenar (columna,orden)
    "columnDefs": hideen_colums
  }).DataTable();
  
}
init();

function formato_miles(num) {
  if (!num || num == 'NaN') return '-';
  if (num == 'Infinity') return '&#x221e;';
  num = num.toString().replace(/\$|\,/g, '');
  if (isNaN(num))
      num = "0";
  sign = (num == (num = Math.abs(num)));
  num = Math.floor(num * 100 + 0.50000000001);
  cents = num % 100;
  num = Math.floor(num / 100).toString();
  if (cents < 10)
      cents = "0" + cents;
  for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
      num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
  return (((sign) ? '' : '-') + num + '.' + cents);
}


