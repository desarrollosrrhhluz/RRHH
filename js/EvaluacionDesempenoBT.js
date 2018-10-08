var x;
x=$(document);
x.ready(inicializarEventos);


//************************************************* 
function inicializarEventos(){ 

$("#DivEvaluar").empty();

cedula_trabajador=$("#cedula_trabajadorEvaluacion").val();


	op="filtroBusquedaCedula3";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&cedula_trabajador=" + cedula_trabajador,
                beforeSend: function (datos) {
                 
                },
                success: function (datos) {
				
			
						//$("#DivGrupo").show();
                    $("#destino").html(datos).fadeIn("slow");
                   // $("#resultadosConsultaVacantesCedula").hide();
					$("#btn_comfirma_evaluacion").click(botonEventoVentana);
				
                }
            });

        return false;
    

 /*$('#form_BusquedaCedulaEvaluacion').submit(function () {
	 
	$("#DivEvaluar").empty();

cedula_trabajador=$("#cedula_trabajadorEvaluacion").attr("value");
$("#cedula_trabajadorCrecaluzAux").attr("value",cedula_trabajador);
	
	op="filtroBusquedaCedula3";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&cedula_trabajador=" + cedula_trabajador,
                beforeSend: function (datos) {
                  //  $("#resultadosConsultaVacantesCedula").show();
                   // var x = $("#resultadosConsultaVacantesCedula");
                   // x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				
						//$("#DivGrupo").show();
                    $("#destino").html(datos).fadeIn("slow");
                   // $("#resultadosConsultaVacantesCedula").hide();
					
				
                }
            });

        return false;
    });*/

}
//***************************************************************

function MostrarInstrucciones(datos,idproceso)
{
op="MostrarInstruccionces";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&cedula=" + datos+ "&idprocesoevaluacion=" +idproceso,
                beforeSend: function (datos) {
                 
                },
                success: function (datos) {
					
				
					$("#destino").empty();
						$("#DivEvaluar").empty();
					
                    $("#DivEvaluar").html(datos).fadeIn("slow");
                }
            });
}


function IniciarEvaluacion()
{
	
	op="IniciarEvaluacionEtica";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
						
				$("#DivEvaluar").empty();
                $("#DivEvaluar").html(datos).fadeIn("slow");
					
$('#form_1_evalu').submit(function () {
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;
	$(".etica").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="EticaUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				
			IniciarEvaluacionResponsabilidad();
				
                }
            });

        return false;
    });
	
                }
            });

}

	function IniciarEvaluacionResponsabilidad()
	{
				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacionResponsabilidad";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
						$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_Respon').submit(function () {
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;
	$(".Responsabilidad").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="ResponsabilidadUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				$("#resultadosConsultaEvaluacion").hide();
			//alert(datos);
				IniciarEvaluacionLiderazgo();
                }
            });

        return false;
    });
							
		}
	});	
}
	
	
	function IniciarEvaluacionLiderazgo()
	{
				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacionLiderazgo";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
						$("#DivEvaluar").empty();
					
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_Liderazgo').submit(function () {
	
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;

	$(".liderazgo").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="LiderazgoUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				
				$("#resultadosConsultaEvaluacion").hide();
				IniciarEvaluacionAutonomia();
                }
            });

        return false;
    });

				}
				 });	
	}
	

function IniciarEvaluacionAutonomia()
	{
				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacionAutonomia";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
						$("#DivEvaluar").empty();
					
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_Autonomia').submit(function () {
	
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;

	$(".Autonomia").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="AutonomiaUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				$("#resultadosConsultaEvaluacion").hide();
				IniciarEvaluacionCalidadymejoracontinua();
                }
            });

        return false;
    });
				}
				 });	
	}
	
	
	
	
	function IniciarEvaluacionCalidadymejoracontinua()
	{
				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacionCalidadymejoracontinua";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
						$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_Calidadymejoracontinua').submit(function () {
	
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;

	$(".Calidadymejoracontinua").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="CalidadymejoracontinuaUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				IniciarEvaluacionGestionLogrodeObjetivos();
				$("#resultadosConsultaEvaluacion").hide();
                }
            });

        return false;
    });
				}
				 });	
	}




function IniciarEvaluacionGestionLogrodeObjetivos()
	{
				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacionGestionLogrodeObjetivos";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
						$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_GestionLogrodeObjetivos').submit(function () {
	
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;

	$(".GestionLogrodeObjetivos").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="GestionLogrodeObjetivosUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				IniciarEvaluacioncomunicacionEficaz();
				$("#resultadosConsultaEvaluacion").hide();
                }
            });

        return false;
    });
				}
				 });	
	}


function IniciarEvaluacioncomunicacionEficaz()
	{
				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacioncomunicacionEficaz";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
						$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_comunicacionEficaz').submit(function () {
	
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;

	$(".comunicacionEficaz").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="comunicacionEficazUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				IniciarEvaluacionPensamientoEstrategico();
				$("#resultadosConsultaEvaluacion").hide();
                }
            });

        return false;
    });
				}
				 });	
	}
	
	function IniciarEvaluacionPensamientoEstrategico()
	{
				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacionPensamientoEstrategico";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
						$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_PensamientoEstrategico').submit(function () {
	
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;

	$(".PensamientoEstrategico").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="PensamientoEstrategicoUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				IniciarEvaluacionTomadeDecisiones();
				$("#resultadosConsultaEvaluacion").hide();
                }
            });

        return false;
    });
				}
				 });	
	}
	
	
	
function IniciarEvaluacionTomadeDecisiones()
	{

				$("#resultadosConsultaEvaluacion").hide();
				
				op="IniciarEvaluacionTomadeDecisiones";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                
                },
                success: function (datos) {
					
					$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					// formulario 
					
					$('#form_1_TomadeDecisiones').submit(function () {
	
	
	 idponderacion= new Array();
     idcomportamiento = new Array();
	
var contador=0;

	$(".TomadeDecisiones").each(
		function(index, value) {
			
 if($($(this)).is(':checked')) {  
        
//alert("id_ponderacion"+$($(this)).val()+"nombre"+$($(this)).attr("name"));

idponderacion[contador]=$($(this)).val();
var aux=$($(this)).attr("name").replace("grupo_",""); 
idcomportamiento[contador]=aux;
contador++;
		
		
        } else {  
           // alert("No está activado");  
        }  

		}
	);

	op="TomadeDecisionesUpdate";
	
	var arv1 = idponderacion.toString();
	var arv2 = idcomportamiento.toString();
	var id_desempeno=$("#id_desempeno").attr("value");
	

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&idponderacion=" +arv1+ "&idcomportamiento=" + arv2+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                  $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
				
			
					
				op="Camposfinales";

	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op+ "&cedula=" + datos+ "&id_desempeno=" + id_desempeno,
                beforeSend: function (datos) {
                 
                },
                success: function (datos) {
					
				
					
					$("#resultadosConsultaEvaluacion").hide();
					$("#destino").empty();
						$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
			
					
					$('#form_opcionesfinales').submit(function () {

	/*
	
	$('#myModal').modal('show');
	
	
	$("#btn_comfirma_evaluacion").click(function(){
		////////////////////////////////////////
		/*$('#myModal').modal('hide');
		///////////////////////////////////////


			   op="CulminaEvaluacion";
	
	var oportunidadmejora=$("#oportunidadmejora").attr("value");
		var accioneseval=$("#accioneseval").attr("value");
			var recomendacionGlobal=$("#recomendacionGlobal").attr("value");
				var observacionevaluador=$("#observacionevaluador").attr("value");
				var proximaevaluacionstatus=$("#proximaevaluacionstatus").attr("value");
				

		 $.ajax({
                 url: 'EvaluacionDesempenoBT.php',
                type: "POST",
            data: "op=" + op+ "&oportunidadmejora=" +oportunidadmejora+ "&accioneseval=" + accioneseval+ "&recomendacionGlobal=" + recomendacionGlobal+ "&observacionevaluador=" + observacionevaluador+ "&proximaevaluacionstatus=" + proximaevaluacionstatus,
                beforeSend: function (datos) {
          $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
$("#resultadosConsultaEvaluacion").hide();

	
	
	
	
	  window.open("PlanillaEvaluacionPDF.php?oportunidadmejora=" +oportunidadmejora+"&accioneseval="+accioneseval+"&observacionevaluador="+observacionevaluador+"&proximaevaluacionstatus="+proximaevaluacionstatus+"&recomendacionGlobal="+recomendacionGlobal);
	$("#DivEvaluar").empty();
  //window.open("_admonPersonal/PlanillaEvaluacionPDF.php");
    
            }
            });
                        	
												  
});*/
	
	$('#myModal').modal('show');
	
	
	


	
	//$('#Evaluaciondialogo2').dialog("destroy");
   /* $("#Evaluaciondialogo2").html("<div align='center'><br/>Desea terminar la evaluaci\u00f3n?</div>");
	
    $('#Evaluaciondialogo2').dialog({
		title: 'RAC',
        width: 250,
        height: 180,
		
        buttons: {
			Cancel: function () {
                $(this).dialog("close");
            },
            "Terminar": function () {

			   op="CulminaEvaluacion";
	
	var oportunidadmejora=$("#oportunidadmejora").attr("value");
		var accioneseval=$("#accioneseval").attr("value");
			var recomendacionGlobal=$("#recomendacionGlobal").attr("value");
				var observacionevaluador=$("#observacionevaluador").attr("value");
				var proximaevaluacionstatus=$("#proximaevaluacionstatus").attr("value");
				

		 $.ajax({
                 url: 'EvaluacionDesempenoBT.php',
                type: "POST",
            data: "op=" + op+ "&oportunidadmejora=" +oportunidadmejora+ "&accioneseval=" + accioneseval+ "&recomendacionGlobal=" + recomendacionGlobal+ "&observacionevaluador=" + observacionevaluador+ "&proximaevaluacionstatus=" + proximaevaluacionstatus,
                beforeSend: function (datos) {
          $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
$("#resultadosConsultaEvaluacion").hide();

	$('#Evaluaciondialogo2').dialog("close");
	
	
	
	  window.open("PlanillaEvaluacionPDF.php?oportunidadmejora=" +oportunidadmejora+"&accioneseval="+accioneseval+"&observacionevaluador="+observacionevaluador+"&proximaevaluacionstatus="+proximaevaluacionstatus+"&recomendacionGlobal="+recomendacionGlobal);
	$("#DivEvaluar").empty();
  //window.open("_admonPersonal/PlanillaEvaluacionPDF.php");
                },
				 Cancel: function () {
                $(this).dialog("close");
            }
            });
                        },
						
						
                    }
                });
	
		
					//
				*/
					return false;
				  });	
					

                }
            });

                }
            });

        return false;
    });
				}
				 });	
	}
	
///////////////////////
function botonEventoVentana(){
	
		////////////////////////////////////////
		$('#myModal').modal('hide');
		///////////////////////////////////////


			   op="CulminaEvaluacion";
	
	var oportunidadmejora=$("#oportunidadmejora").val();
		var accioneseval=$("#accioneseval").val();
			var recomendacionGlobal=$("#recomendacionGlobal").val();
				var observacionevaluador=$("#observacionevaluador").val();
				var proximaevaluacionstatus=$("#proximaevaluacionstatus").val();
				

		 $.ajax({
                 url: 'EvaluacionDesempenoBT.php',
                type: "POST",
            data: "op=" + op+ "&oportunidadmejora=" +oportunidadmejora+ "&accioneseval=" + accioneseval+ "&recomendacionGlobal=" + recomendacionGlobal+ "&observacionevaluador=" + observacionevaluador+ "&proximaevaluacionstatus=" + proximaevaluacionstatus,
                beforeSend: function (datos) {
          $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
$("#resultadosConsultaEvaluacion").hide();
		
		
		if(datos=="1"){
			CulminarEvaluacion();
		$('#myModalMensajeExtito').modal('show');	
			
		}else{
			
		alert("problemas al realizar la operacion");	
			
		//alert(datos);	
		}
		
		
		
	
	
	
	
	  window.open("PlanillaEvaluacionPDF.php?oportunidadmejora=" +oportunidadmejora+"&accioneseval="+accioneseval+"&observacionevaluador="+observacionevaluador+"&proximaevaluacionstatus="+proximaevaluacionstatus+"&recomendacionGlobal="+recomendacionGlobal);
	$("#DivEvaluar").empty();
  //window.open("_admonPersonal/PlanillaEvaluacionPDF.php");
    
            }
            });
                        	
												  
}



/////////////////////////	
	
	
	function IniciarCamposFinales()
	{
		
		op="Camposfinales";
	
	 $.ajax({
                url: 'EvaluacionDesempenoBT.php',
                type: "POST",
                data: "op=" + op,
                beforeSend: function (datos) {
                 
                },
                success: function (datos) {
					$("#resultadosConsultaEvaluacion").hide();
					$("#destino").empty();
						$("#DivEvaluar").empty();
						
                    $("#DivEvaluar").html(datos).fadeIn("slow");
					
					//
					
					$('#form_opcionesfinales').submit(function () {
	
	

	$('#Evaluaciondialogo2').dialog("destroy");
    $("#Evaluaciondialogo2").html("<div align='center'><br/>Desea terminar la evaluaci\u00f3n?</div>");
	
    $('#Evaluaciondialogo2').dialog({
		title: 'RAC',
        width: 250,
        height: 180,
		 
        buttons: {
			Cancel: function () {
                $(this).dialog("close");
            },
            "Terminar": function () {
               
			   
			   op="CulminaEvaluacion";
	
	var oportunidadmejora=$("#oportunidadmejora").attr("value");
		var accioneseval=$("#accioneseval").attr("value");
			var recomendacionGlobal=$("#recomendacionGlobal").attr("value");
				var observacionevaluador=$("#observacionevaluador").attr("value");
					var proximaevaluacionstatus=$("#proximaevaluacionstatus").attr("value");
				

				
		 $.ajax({
                 url: 'EvaluacionDesempenoBT.php',
                type: "POST",
            data: "op=" + op+ "&oportunidadmejora=" +oportunidadmejora+ "&accioneseval=" + accioneseval+ "&recomendacionGlobal=" + recomendacionGlobal+ "&observacionevaluador=" + observacionevaluador+ "&proximaevaluacionstatus=" + proximaevaluacionstatus,
                beforeSend: function (datos) {
          $("#resultadosConsultaEvaluacion").show();
                    var x = $("#resultadosConsultaEvaluacion");
                    x.html('<img src="images/cargando.gif">');
                },
                success: function (datos) {
$("#resultadosConsultaEvaluacion").hide();

	$('#Evaluaciondialogo2').dialog("close");
	
	alert(datos+"AQUII");
	

                },
				 Cancel: function () {
                $(this).dialog("close");
            }
            });

                        }
		}
						
                      });
					return false;
				  });	
					

                }
            });
	}
	
	
	
function TareasEvaluado()
{

$("#EvaluacionTareas").html("<form id='FormTareasEvaluado'  name='FormTareasEvaluado' autocomplete='off' ><div  ALIGN='center' style='font-size:12px;'><div id='products'><h1 class='ui-widget-header'>Cargos</h1><div id='catalog'><h2><a href='#'>T-Shirts</a> </h2><div><ul><li>Lolcat Shirt</li><li>Cheezeburger Shirt</li><li>Buckit Shirt</li></ul></div><h2><a href='#'>Bags</a></h2><div><ul <li>Zebra Striped</li><li>Black Leather</li><li>Alligator Leather</li></ul></div><h2><a href='#'>Gadgets</a></h2><div><ul><li>iPhon </li><li>iPod</li><li>iPad</li></ul></div></div></div><div id='cart'><h1 class='ui-widget-header'>Tareas</h1><div class='ui-widget-content'><ol><li class='placeholder'>Add your items here</li></ol></div></div></div></form>");
 
 $( "#catalog" ).accordion();
$( "#catalog li" ).draggable({
appendTo: "body",
helper: "clone"
});
$( "#cart ol" ).droppable({
activeClass: "ui-state-default",
hoverClass: "ui-state-hover",
accept: ":not(.ui-sortable-helper)",
drop: function( event, ui ) {
$( this ).find( ".placeholder" ).remove();
$( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );
}
}).sortable({
items: "li:not(.placeholder)",
sort: function() {
// gets added unintentionally by droppable interacting with sortable
// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
$( this ).removeClass( "ui-state-default" );
}
});

    $('#EvaluacionTareas').dialog({
        height: 620,
        width: 750,
        title: 'Selecci\u00f3n tareas'
    });
	
		
}

function CulminarEvaluacion()
{
	
	inicializarEventos();
	
	
}
	
/***********************************************************/	