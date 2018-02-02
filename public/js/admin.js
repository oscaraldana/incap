/**
 * Funcion del boton nuevo diagnostico
 **/
$(function () {
    $("#nuevo_diagnostico").click(function () {

        var data = Array();
        data.push({name: 'action', value: 'nuevo'});
        $.get(url_new_dg, data)
                .done(function (respuesta) {
                    $('#myModalLabel').html("Nuevo Diagnostico");
                    $('#html_contentPop').html(respuesta);
                    //startParsley('frm_detail', 'notificationsForm');
       
                });
        $('#modalDiagnostico').modal(
                {keyboard: false, backdrop: 'static', show: true}
        );

    });
});

function buscarDiagnostico(){
    
    var param = $("#buscarDiagnostico").val();
    
    if( param != "" ){
        var parametros = {
                "diagnostico" : param
        };
        $.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url:   url_search_dg, //archivo que recibe la peticion
                type:  'post', //método de envio
                beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                        $("#container").html(response);
                }
        });
    }
    
}