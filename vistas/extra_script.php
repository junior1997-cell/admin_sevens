<script>
  if (localStorage.getItem("nube_idproyecto")) {

    console.log("id proyecto actual: " + localStorage.getItem("nube_idproyecto"));

    $("#ver-proyecto").html(`<i class="fas fa-tools"></i> <p class="d-inline-block hide-max-width-1080px">Proyecto:</p> ${localStorage.getItem('nube_nombre_proyecto')}`);

    $(".ver-otros-modulos-1").show();

    $(tabla).ready(function () {
      $('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
    });   

  } else {
    $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');

    $(".ver-otros-modulos-1").hide();
  }
</script>