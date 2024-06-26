var chart_linea ; var chart_linea_ ;
var chart_barras; var chart_barras_;
var pieChart;
var color_char_pie = ['text-danger','text-success','text-warning','text-info','text-primary','text-indigo',]
//Función que se ejecuta al inicio
function init() {

  $("#bloc_LogisticaAdquisiciones").addClass("menu-open");

  $("#bloc_Compras").addClass("menu-open bg-color-191f24");

  $("#mLogisticaAdquisiciones").addClass("active");

	$("#mCompra").addClass("active bg-primary");

  $("#lChartCompraInsumo").addClass("active");

  box_content_reporte(localStorage.getItem("nube_idproyecto"));
  //chart_linea_barra(localStorage.getItem("nube_idproyecto"));

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  var anio_actual = moment().format('YYYY');
  lista_select2(`../ajax/chart_compra_insumo.php?op=anios_select2&nube_idproyecto=${localStorage.getItem("nube_idproyecto")}`, '#year_filtro', anio_actual);

  // ══════════════════════════════════════ INITIALIZE SELECT2 ══════════════════════════════════════

  $("#year_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro Año", allowClear: false, });
  $("#month_filtro").select2({ theme: "bootstrap4", placeholder: "Filtro Mes", allowClear: true, });

  $("#month_filtro").val("null").trigger("change");

  // Formato para telefono
  $("[data-mask]").inputmask();
}

// ::::::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C H A R T :::::::::::::::::::::::::::::::::::::::::::::

//mostrar datos proveedor pago
function box_content_reporte(idnubeproyecto) {

  $(".cant_proveedores_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');
  $(".cant_producto_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');
  $(".cant_insumos_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');
  $(".cant_activo_fijo_box").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>');

  $.post("../ajax/chart_compra_insumo.php?op=box_content_reporte", { 'idnubeproyecto': idnubeproyecto }, function (e, status) {

    e = JSON.parse(e);   //console.log(e);

    if (e.status == true) {
      $(".cant_proveedores_box").html(e.data.cant_proveedores);
      $(".cant_producto_box").html(e.data.cant_producto);
      $(".cant_insumos_box").html(e.data.cant_insumo);
      $(".cant_activo_fijo_box").html(e.data.cant_activo_fijo);
    } else {
      ver_errores(e);
    }    
  }).fail( function(e) { ver_errores(e); } );
}

function chart_linea_barra(idnubeproyecto) {
  'use strict'

  $('.cant_ft_aceptadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.cant_ft_rechazadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.cant_ft_eliminadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
  $('.cant_ft_rechazadas_eliminadas').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  $('.progress_ft_aceptadas').css({ width: `0%`, });
  $('.progress_ft_rechazadas').css({ width: `0%`, });
  $('.progress_ft_eliminadas').css({ width: `0%`, });
  $('.progress_ft_rechazadas_eliminadas').css({ width: `0%`, });
  $('.progress_monto_pagado').css({ width: `0%`, });
  $('.progress_monto_no_pagado').css({ width: `0%`, });

  var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };

  var mode = 'index'; var intersect = true;

  var idnubeproyecto = localStorage.getItem("nube_idproyecto");
  var year_filtro = $("#year_filtro").select2("val");
  var month_filtro = $("#month_filtro").select2("val");
  var dias_por_mes =cant_dias_mes(year_filtro, month_filtro);
  
  $.post("../ajax/chart_compra_insumo.php?op=chart_linea", { 'idnubeproyecto': idnubeproyecto , 'year_filtro': year_filtro, 'month_filtro':month_filtro, 'dias_por_mes':dias_por_mes }, function (e, status) {
    e = JSON.parse(e);   console.log(e);
    if (e.status == true) {
      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T   S E G U N: MATERIAL COMBUSTIBLE EQUIPOS ::::::::::::::::::::::::::::::::::::
      $('.total_material').html(`${formato_miles(e.data.total_material)}`);
      $('.total_combustible').html(`${formato_miles(e.data.total_combustible)}`);
      $('.total_equipo').html(`${formato_miles(e.data.total_equipo)}`);
      var total_material = (e.data.total_material/e.data.factura_total_gasto)*100;
      var total_combustible = (e.data.total_combustible/e.data.factura_total_gasto)*100;
      var total_equipo = (e.data.total_equipo/e.data.factura_total_gasto)*100;
      if (total_material < 0) {
        $('.total_material_p').html(`<i class="fa-solid fa-caret-down"></i> ${formato_miles(total_material)}%`).removeClass('text-success text-danger').addClass('text-danger');
      } else {
        $('.total_material_p').html(`<i class="fas fa-caret-up"></i> ${formato_miles(total_material)}%`).removeClass('text-success text-danger').addClass('text-success');
      }
      if (total_combustible < 0) {
        $('.total_combustible_p').html(`<i class="fa-solid fa-caret-down"></i> ${formato_miles(total_combustible)}%`).removeClass('text-success text-danger').addClass('text-danger');
      } else {
        $('.total_combustible_p').html(`<i class="fas fa-caret-up"></i> ${formato_miles(total_combustible)}%`).removeClass('text-success text-danger').addClass('text-success');
      }
      if (total_equipo < 0) {
        $('.total_equipo_p').html(`<i class="fa-solid fa-caret-down"></i> ${formato_miles(total_equipo)}%`).removeClass('text-success text-danger').addClass('text-danger');
      } else {
        $('.total_equipo_p').html(`<i class="fas fa-caret-up"></i> ${formato_miles(total_equipo)}%`).removeClass('text-success text-danger').addClass('text-success');
      }      
      
      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T    P R O G R E S ::::::::::::::::::::::::::::::::::::
      $('.cant_ft_aceptadas').html(`<b>${e.data.factura_aceptadas}</b>/${e.data.factura_total}`);
      $('.cant_ft_rechazadas').html(`<b>${e.data.factura_rechazadas}</b>/${e.data.factura_total}`);
      $('.cant_ft_eliminadas').html(`<b>${e.data.factura_eliminadas}</b>/${e.data.factura_total}`);
      $('.cant_ft_rechazadas_eliminadas').html(`<b>${e.data.factura_rechazadas_eliminadas}</b>/${e.data.factura_total}`);
      var aceptadas = (e.data.factura_aceptadas/e.data.factura_total)*100;
      var rechazadas = (e.data.factura_rechazadas/e.data.factura_total)*100;
      var eliminadas = (e.data.factura_eliminadas/e.data.factura_total)*100;
      var rechazadas_eliminadas = (e.data.factura_rechazadas_eliminadas/e.data.factura_total)*100;
      $('.progress_ft_aceptadas').css({ width: `${aceptadas.toFixed(2)}%`, });
      $('.progress_ft_rechazadas').css({ width: `${rechazadas.toFixed(2)}%`, });
      $('.progress_ft_eliminadas').css({ width: `${eliminadas.toFixed(2)}%`, });
      $('.progress_ft_rechazadas_eliminadas').css({ width: `${rechazadas_eliminadas.toFixed(2)}%`, });

      $('.monto_pagado').html(`<b> ${formato_miles(e.data.factura_total_pago)}</b>/ ${formato_miles(e.data.factura_total_gasto)}`);
      var no_pagado = e.data.factura_total_gasto - e.data.factura_total_pago;
      $('.monto_no_pagado').html(`<b> ${ formato_miles(no_pagado)}</b>/  ${formato_miles(e.data.factura_total_gasto)}`);
      var monto_pagado = (e.data.factura_total_pago/e.data.factura_total_gasto)*100;
      var monto_no_pagado = (no_pagado/e.data.factura_total_gasto)*100;
      $('.progress_monto_pagado').css({ width: `${monto_pagado.toFixed(2)}%`, });
      $('.progress_monto_no_pagado').css({ width: `${monto_no_pagado.toFixed(2)}%`, });

      // :::::::::::::::::::::::::::::::::::::::::::: C H A R T   L I N E A ::::::::::::::::::::::::::::::::::::
      
      var $chart_linea = document.getElementById('visitors-chart');
      if (chart_linea) {  chart_linea.destroy();  } 
      // eslint-disable-next-line no-unused-vars
      chart_linea = new Chart($chart_linea, {
        data: {
          labels: mes_o_dia(year_filtro, month_filtro),
          datasets: [
            {
              type: 'line', data: e.data.total_gasto, 
              backgroundColor: 'transparent', borderColor: '#007bff',
              pointBorderColor: '#007bff', pointBackgroundColor: '#007bff',
              fill: false, label: 'Compras', tension: 0.4
              // pointHoverBackgroundColor: '#007bff',
              // pointHoverBorderColor    : '#007bff'
            },
            {
              type: 'line',
              data: e.data.total_deposito,
              backgroundColor: 'tansparent', borderColor: '#ced4da',
              pointBorderColor: '#ced4da', pointBackgroundColor: '#ced4da',
              fill: false, label: 'Pago', tension: 0.5
              // pointHoverBackgroundColor: '#ced4da',
              // pointHoverBorderColor    : '#ced4da'
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: true, },          
          plugins: {
            tooltip: { enabled: true, mode: 'index',  intersect: intersect,
              callbacks: {              
                label: function( e) {                      
                  let label = e.dataset.label || '';
                  if (label) { label += ': ';  }
                  if (e.parsed.y !== null) { label += new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(e.parsed.y); }
                  return label;                  
                },      
                footer: function( e) { 
                  let sum = 0;
                  e.forEach(function(tooltipItem) { sum += tooltipItem.parsed.y;  });
                  return 'Sum: ' +  new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(sum);
                },        
              } 
            },
            hover: { mode: mode, intersect: intersect },
            legend: { display: true,  },
          },          
         
          scales: {            
            y: {              
              display: true,
              beginAtZero: true,
              gridLines: { display: false, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({ beginAtZero: true, suggestedMax: 200, callback: function (value, index, values) { return  Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');  }, }, ticksStyle)
            },
            x: {               
              display: true, 
              gridLines: { display: false, },
              ticks: ticksStyle
            }
          }
        }
      });

      // ::::::::::::::::::::::::::::::::::::::::::::  C H A R T  B A R R A S   ::::::::::::::::::::::::::::::::::::
      var chart_barras_ = $('#sales-chart');
      if (chart_barras) {  chart_barras.destroy();  }
      // eslint-disable-next-line no-unused-vars
      chart_barras = new Chart(chart_barras_, {
        type: 'bar',
        data: {
          labels: mes_o_dia(year_filtro, month_filtro),
          datasets: [
            { backgroundColor: '#007bff', borderColor: '#007bff', data: e.data.total_gasto, label: 'Compras', },
            { backgroundColor: '#ced4da', borderColor: '#ced4da', data: e.data.total_deposito, label: 'Pago', }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltip: {  mode: mode, intersect: intersect, 
            callbacks: {              
              label: function(tooltipItems, data) {     
                console.log(tooltipItems);   console.log(data);             
                return `${data.datasets[tooltipItems.datasetIndex]['label']}: S/ ` +  Number(tooltipItems.yLabel).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ;
              },          
            }
          },
          hover: { mode: mode, intersect: intersect },
          legend: { display: true  },
          scales: {
            y: {
              // display: false,
              gridLines: { display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
              ticks: $.extend({
                beginAtZero: true,
                // Include a dollar sign in the ticks
                callback: function (value) { if (value >= 1000) { value /= 1000; value += 'k'; } return 'S/.' + value; }
              }, ticksStyle)
            },
            x: {
              display: true,
              gridLines: { display: false },
              ticks: ticksStyle
            }
          }
        }
      });

      // :::::::::::::::::::::::::::::::::::::::::::: P R O D U C T O S   M A S   V E N D I D O S ::::::::::::::::::::::::::::::::::::
      var productos_mas_vendidos = ""; var colores_leyenda = "";
      e.data.productos_mas_vendidos.forEach((key, indice) => {
        colores_leyenda = colores_leyenda.concat(`<li><i class="fas fa-circle ${color_char_pie[indice]}"></i> ${key.producto}</li>`);
        productos_mas_vendidos = productos_mas_vendidos.concat(`
          <tr>
            <td class="py-1">              
              <div class="user-block">
                <img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/material/img_perfil/${key.imagen}" alt="user image" onerror="this.src='../dist/svg/404-v2.svg';" onclick="ver_perfil('../dist/docs/material/img_perfil/${key.imagen}', '${encodeHtml(key.producto)}');" data-toggle="tooltip" data-original-title="Ver imagen">
                <span class="username"><p class="mb-0 font-size-14px" >${key.producto}</p></span>
                <span class="description">${key.descripcion}</span>
              </div>
            </td>
            <td class="py-1 text-right">S/ ${formato_miles(key.precio_referencial)}</td>
            <td class="py-1">              
              ${formato_miles(key.cantidad_vendida)}
            </td>
            <td class="py-1 text-right">
              <a href="resumen_insumos.php" class="text-muted"> <i class="fas fa-search"></i> </a>
            </td>
          </tr>
        `);
      });

      $('.leyenda-pai-productos-mas-usados').html(colores_leyenda);
      $('#body_productos_mas_vendidos').html(productos_mas_vendidos);
      $('[data-toggle="tooltip"]').tooltip();
       
      // :::::::::::::::::::::::::::::::::::::::::::: PIE CHART - P R O D U C T O S   M A S   V E N D I D O S ::::::::::::::::::::::::::::::::::::

      // Get context with jQuery - using jQuery's .get() method.       
      const ctx = document.getElementById('chart_pie_productos_mas_usadoss').getContext('2d');
      if (pieChart) {  pieChart.destroy();  }
      pieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels:  e.data.producto_mas_vendido_nombre,
          datasets: [
            {
              data: e.data.producto_mas_vendido_cantidad,
              backgroundColor: ['#dc3545', '#00a65a', '#f39c12', '#09a5be', '#007bff', '#2d1582']
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false, }, 
          plugins: {
            tooltip: { enabled: true, mode: 'index',  intersect: false,
              callbacks: {              
                label: function( e) {                  
                  return  new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(e.parsed);                  
                },      
                footer: function( e) { 
                  let label = Math.round(e[0].parsed / e[0].dataset.data.map(Number).reduce((a, b) => a + b, 0) * 100) + '%';  return 'Porcentaje: ' +  label;
                },        
              } 
            },
            legend: {  display: true, position:'right'   },          
            datalabel: {
              formatter: (value, context) => { console.log(value);
                  var sum = context.dataset.data.reduce((a, b) => a + b);
                  var percentage = (value * 100 / sum).toFixed(2) + "%";
                  return percentage;
              },
              color: 'white', // Color del texto
              display: true
            }
          },
             
        }
      });      

    } else {
      ver_errores(e);
    }
  });  
}

$(".btn-download-cpxm-png").on('click', function () {
  var chart = document.getElementById('visitors-chart');  
  chart.toBlob(function(blob) {// Obtén el lienzo como Blob    
    var link = document.createElement('a');// Crea un enlace de descarga
    link.href = window.URL.createObjectURL(blob);
    link.download = 'grafico_lineas.png'; // Nombre del archivo a descargar
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
});

$(".btn-download-cpxm-xlsx").on('click', function () {  
  var chartData = chart_linea.data; console.log(chartData);// Obtén los datos del gráfico   
  var wb = XLSX.utils.book_new();// Crea un nuevo libro de trabajo (workbook)  
  // Formatea los datos para json_to_sheet
  var formattedData = chartData.labels.map(function(label, index) { return { label: label, data: chartData.datasets[0].data[index] }; });
  var ws = XLSX.utils.json_to_sheet(formattedData, { header: ['label', 'data'] });  // Crea una hoja de trabajo (worksheet) con los datos del gráfico
  XLSX.utils.book_append_sheet(wb, ws, 'Gráfico');// Añade la hoja de trabajo al libro de trabajo  
  XLSX.writeFile(wb, 'grafico_lineas.xlsx');// Descarga el archivo XLSX
});

$(".btn-download-pmu-png").on('click', function () {
  var chart = document.getElementById('chart_pie_productos_mas_usadoss');  
  chart.toBlob(function(blob) {// Obtén el lienzo como Blob    
    var link = document.createElement('a');// Crea un enlace de descarga
    link.href = window.URL.createObjectURL(blob);
    link.download = 'grafico_torta.png'; // Nombre del archivo a descargar
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
});

init();

function mes_o_dia(data_anio, data_mes) {
  
  if (data_anio == null || data_anio == '' || data_mes == null || data_mes == "") {
    return [ 'ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AUG', 'SEP.', 'OCT.', 'NOV.', 'DIC.'];
  } else {
    var array_cant_dias = [];
    var cant_dias = cant_dias_mes(data_anio, data_mes);
    for (var dia = 1; dia <= cant_dias; dia++) { array_cant_dias.push(dia);  }
    return array_cant_dias;
  } 
}

function ver_perfil(file, nombre) {
  $('.foto-insumo').html(nombre);
  $(".tooltip").removeClass("show").addClass("hidde");
  $("#modal-ver-perfil-insumo").modal("show");
  $('#perfil-insumo').html(`<center><img class="img-thumbnail" src="${file}" onerror="this.src='../dist/svg/404-v2.svg';" alt="Perfil" width="100%"></center>`);
}

function export_excel(name_tabla, nombre_excel_file = $('title').text(), nombre_excel_hoja = 'Detalle') {
   
  $tabla = document.querySelector(name_tabla);
  let tableExport = new TableExport($tabla, {
    exportButtons: false, // No queremos botones
    filename: nombre_excel_file, //Nombre del archivo de Excel
    sheetname: nombre_excel_hoja, //Título de la hoja
  });
  let datos = tableExport.getExportData(); console.log(datos);

  if (name_tabla == '#tbla_productos_mas_vendidos') {    
    let preferenciasDocumento = datos.tbla_productos_mas_vendidos.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
  }
  
}