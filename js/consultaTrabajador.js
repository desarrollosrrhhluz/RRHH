var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 
$('#form_consulta').submit(buscar);
$("#img_ayuda").click(function(){
	$('#myAyuda').modal('show');
	
	});
	
}

function buscar(){
	  var values = $("#form_consulta").serialize();
		 $.ajax({
                url: 'consultaTrabajador.php',
                type: "POST",
                data:values,
                beforeSend: showRequestDP2 ,
                success: showResponseDP2,
				dataType: 'html'
            });
	
        return false;
	
	}

function showRequestDP2(datos){
	/*var valor= $("#valor").val();
	
	return false;*/
	}

function showResponseDP2(datos){
	$('#destino').html(datos);
	$(".datper").click(datos_personales);
	$(".datcargo").click(datos_cargo);
	
	$('#tabla_p').tablePagination({ currPage : 1, 
              ignoreRows : $('tbody tr:odd', $('#menuTable2')),
              optionsForRows : [10,15,20],
              rowsPerPage : 10,
              firstArrow : (new Image()).src="app_images/sprevious.png",
              prevArrow : (new Image()).src="app_images/previous.png",
              lastArrow : (new Image()).src="app_images/snext.png",
              nextArrow : (new Image()).src="app_images/next.png"});

	}
	
function datos_personales(){
	var sel =$(this).attr("id");
	
	$.post("consultaTrabajador.php",{ op:"datosPersonales", id:sel },function(data){
		
		$("#contenido_datos_personales").html(data);
	     $('#datos_personales').modal('show');
	
	})
	
	}
	
function datos_cargo(){
	var sel =$(this).attr("id");
	
	$.post("consultaTrabajador.php",{ op:"datosCargo", id:sel },function(data){
		
		$("#contenido_datos_cargo").html(data);
		$('#datos_del_cargo').modal('show');
		})
	}