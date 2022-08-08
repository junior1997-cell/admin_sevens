$("#form-pago-credito").validate({
  ignore: '.select2-input, .select2-focusser',
  rules: {
    fecha_pago_c:{required: true},
    monto_pago_c:{required: true},
  },
  messages: {
    fecha_pago_c: {required: "Campo requerido",},
    monto_pago_c: {required: "Campo requerido",},
  },
      
  errorElement: "span",

  errorPlacement: function (error, element) {
    error.addClass("invalid-feedback");
    element.closest(".form-group").append(error);
  },

  highlight: function (element, errorClass, validClass) {
    $(element).addClass("is-invalid").removeClass("is-valid");
  },

  unhighlight: function (element, errorClass, validClass) {
    $(element).removeClass("is-invalid").addClass("is-valid");     
  },

  submitHandler: function (e) {
    $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
    guardar_y_editar_pago_credito(e);    
  }
});