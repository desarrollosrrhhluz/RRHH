var x;
var nextinput = 1;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){
	
	
    $("#monto_1").keyup(function () {
	
	$("#TotalRemuAnual").val("");
	$("#totalRemuneraciontribuUT").val("");
	
	});


    $("#DESGRAVAMENUNICOvariante1").keyup(function () {
	
	$(this).val($(this).val().replace(/[^0-9\.]/g, ""));

	$("#DESGRAVAMENUNICOCampo2").val("");
	$("#TotalDESGRAVAMENUNICOCampo2").val("");
	$("#totalimpuestogravable2").val("");
	

	});
	
	
	$("#DESGRAVAMENUNICOvariante1").focusout(function() {
    
	if($("#DESGRAVAMENUNICOvariante1").val()=="")
	{
		
	$("#DESGRAVAMENUNICOvariante1").val("0");
	
	}
	
  });
	
	
	//**
    $("#DESGRAVAMENUNICOvariante2").keyup(function () {
	
	$(this).val($(this).val().replace(/[^0-9\.]/g, ""));
	$("#DESGRAVAMENUNICOCampo2").val("");
	$("#TotalDESGRAVAMENUNICOCampo2").val("");
	$("#totalimpuestogravable2").val("");

	
	
	});
	
	
	
	$("#DESGRAVAMENUNICOvariante2").focusout(function() {
    
	if($("#DESGRAVAMENUNICOvariante2").val()=="")
	{
	$("#DESGRAVAMENUNICOvariante2").val("0")	
	}
	
  });
	

	//**
    $("#DESGRAVAMENUNICOvariante3").keyup(function () {
	$(this).val($(this).val().replace(/[^0-9\.]/g, ""));
	
	$("#DESGRAVAMENUNICOCampo2").val("");
	$("#TotalDESGRAVAMENUNICOCampo2").val("");
	$("#totalimpuestogravable2").val("");

	});
	
	
	
	$("#DESGRAVAMENUNICOvariante3").focusout(function() {
    
	if($("#DESGRAVAMENUNICOvariante3").val()=="")
	{
	$("#DESGRAVAMENUNICOvariante3").val("0")	
	}
	
  });
	
	
	
    $("#DESGRAVAMENUNICOvariante4").keyup(function () {
	$(this).val($(this).val().replace(/[^0-9\.]/g, ""));
	
	$("#DESGRAVAMENUNICOCampo2").val("");
	$("#TotalDESGRAVAMENUNICOCampo2").val("");
	$("#totalimpuestogravable2").val("");
	


	});
	
	
	
	$("#DESGRAVAMENUNICOvariante4").focusout(function() {
    
	if($("#DESGRAVAMENUNICOvariante4").val()=="")
	{
	$("#DESGRAVAMENUNICOvariante4").val("0")	
	}
	
  });


	/**/
    $("#cargafamiliarimput").keyup(function () {
		
	$(this).val($(this).val().replace(/[^0-9\.]/g, ""));
	
	$("#totalrebajas").val("");
	
	$("#ImpuestoEstimadoaRetener").val("");
	
	$("#totalmensualretenerinput").val("");

	});
	
	
	$("#cargafamiliarimput").focusout(function() {
    
	if($("#cargafamiliarimput").val()=="")
	{
	$("#cargafamiliarimput").val("0");
	$("#TotalUt2").val("0");
	
	}
	
  });

	
	
	//**//**//
    $("#impuestoretenidosimput").keyup(function () {
		
	$(this).val($(this).val().replace(/[^0-9\.]/g, ""));
	
	$("#totalrebajas").val("");
	
	$("#ImpuestoEstimadoaRetener").val("");
	
	$("#totalmensualretenerinput").val("");

	});
	
	
	$("#impuestoretenidosimput").focusout(function() {
    
	if($("#impuestoretenidosimput").val()=="")
	{
	$("#impuestoretenidosimput").val("0");
	$("#TotalUt3").val("0");	
	}
	
  });
	
	

//**Radio Button evaluacion Vacante observacion , Interface principal**//

    $("#desgravamenesgroup1,#desgravamenesgroup2").change(function () {

        var RadioValorEvaluacion = $('[name="desgravamenesgroup"]:checked').val();

        if (RadioValorEvaluacion == '1') {

		$("#divDESGRAVAMENDefecto").fadeIn("slow");
		$("#divDESGRAVAMENVarios").fadeOut("slow");

	}
		
        if (RadioValorEvaluacion == '2') {
			
	if($('#totalRemuneraciontribuUT').val()==""){
	
	alert("DEBE CALCULAR PRIMERO");
	
    }else{
	
	$("#RemuneracionesUTCampodesgra2").val($('#totalRemuneraciontribuUT').val());
    $("#divDESGRAVAMENVarios").fadeIn("slow");
    $("#divDESGRAVAMENDefecto").fadeOut("slow");

}

        }
    });	



$('#form_REMUNERACIONESTIMADAENUT').submit(function () {

	var TotalMonto=0;
	nombarticu = new Array();
		
for(var f=1;f<=nextinput;f++)
{
aux4='#monto_'+f;

TotalMonto=TotalMonto+parseFloat($(aux4).val());

}
	

$("#TotalRemuAnual").val(TotalMonto.toFixed(2));
 

 var TotalUT=((parseFloat($("#TotalRemuAnual").val()))/(parseFloat($("#valorUT").val())));
$("#totalRemuneraciontribuUT").val(TotalUT.toFixed(2))



        return false;
    });
	
	
	
	$( "#cargafamiliarimput" ).keyup(function() {
  
  $( "#TotalUt2" ).val("");

	var auxcampo2=(parseFloat($("#cargafamiliarimput").val()))*(parseFloat("10"));

	$( "#TotalUt2" ).val(auxcampo2);

});




$( "#impuestoretenidosimput" ).keyup(function() {
  
  $( "#TotalUt3" ).val("");

	var auxcampo3=(parseFloat($("#impuestoretenidosimput").val()))/(parseFloat($("#valorUT").val()));

	$( "#TotalUt3" ).val(auxcampo3.toFixed(2));

});

	

	
}

//*********************************************************

function AgregarCampos(){

nextinput++;

campo = '<tr id="culumna_'+nextinput+'" ><th><input id="empresa_'+nextinput+'" name="empresa_'+nextinput+'" type="text" class="form-control"  value=""  required="required" placeholder=""  title=""/></th><th><input id="monto_'+nextinput+'" name="monto_'+nextinput+'" type="text" class="form-control" required="required" placeholder=""  pattern="[0-9]+([\.|,][0-9]+)?"  title="Solo números y puntos permitidos" /></th><th></th></tr>'; 

$("#tablaMontosEmpresa").append(campo);


    $('#monto_'+nextinput+'').keyup(function () {
	$("#TotalRemuAnual").val("");
	$("#totalRemuneraciontribuUT").val("");
	
	});
	

}


function EliminarCampos(){

if(nextinput=="1")
{

}else{
// Eliminamos la ultima columna
 $("#tablaMontosEmpresa tr:last").remove();
 
nextinput--;
	
}



}



function CalcularRemuneracionAnualUt()
{
	var TotalMonto=0;
	nombarticu = new Array();
		
for(var f=1;f<=nextinput;f++)
{
aux4='#monto_'+f;

TotalMonto=TotalMonto+parseFloat($(aux4).val());

}
	
	

 $("#TotalRemuAnual").val(TotalMonto.toFixed(2));

 var TotalUT=((parseFloat($("#TotalRemuAnual").val()))/(parseFloat($("#valorUT").val())));

$("#totalRemuneraciontribuUT").val(TotalUT.toFixed(2))


	
}





function CalcularDESGRAVAMENUTBton()
{
	
	
	if($('#totalRemuneraciontribuUT').val()==""){
	
	alert("DEBE CALCULAR PRIMERO LAS REMUNERACION ESTIMADA EN UT");
	
}else{
	
	$("#RemuneracionesUTCampodesgra").val($('#totalRemuneraciontribuUT').val());
	


var TotalAux=(parseFloat($("#RemuneracionesUTCampodesgra").val()))-(parseFloat($("#DESGRAVAMENUNICOCampo").val()));
	
	$("#TotalDESGRAVAMENUNICOCampo").val(TotalAux.toFixed(2));
	
	
	if(TotalAux < 1000){
		
	var TasaAux= 6.00;
	var SustraendoUT=0.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
	
	
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
	
		
	}else if(TotalAux > 1000 && TotalAux < 1500){
		
	var TasaAux= 9.00;
	var SustraendoUT=30.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
	
	}else if(TotalAux > 1500 && TotalAux < 2000){

		
	var TasaAux= 12.00;
	var SustraendoUT=75.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
	
	}else if(TotalAux > 2000 && TotalAux < 2500){
		
	var TasaAux= 16.00;
	var SustraendoUT=155.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
		
	}else if(TotalAux > 2500 && TotalAux < 3000){
		
	var TasaAux= 20.00;	
	var SustraendoUT=255.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
		
	}else if(TotalAux > 3000 && TotalAux < 4000){
		
	var TasaAux= 24.00;	
	var SustraendoUT=375.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
		
	}else if(TotalAux > 4000 && TotalAux < 6000){
		
	var TasaAux= 29.00;		
	var SustraendoUT=575.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
	
	}else if(TotalAux > 6000 ){
		
	var TasaAux= 34.00;			
	var SustraendoUT=875.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable").val(TotalPorcentajeTabla2.toFixed(2));
		
	}
	
	
	
	

  }

}

function ATRASDIV1(){
	
	$("#Div2").fadeOut("slow");
$("#Div1").fadeIn("slow");
}

function ATRASDIV2(){
	
	$("#Div3").fadeOut("slow");
$("#Div2").fadeIn("slow");
}



function CalcularDESGRAVAMENUTBton2()
{

	var sumaAux=parseFloat($("#DESGRAVAMENUNICOvariante1").val())+parseFloat($("#DESGRAVAMENUNICOvariante2").val())+parseFloat($("#DESGRAVAMENUNICOvariante3").val())+parseFloat($("#DESGRAVAMENUNICOvariante4").val());
	
	var comvertirAUTSUMA=parseFloat((sumaAux))/parseFloat($("#valorUT").val());
	
	var TotalAux=(parseFloat($("#RemuneracionesUTCampodesgra2").val()))-(parseFloat(comvertirAUTSUMA));

$("#DESGRAVAMENUNICOCampo2").val(comvertirAUTSUMA.toFixed(2));
$("#TotalDESGRAVAMENUNICOCampo2").val(TotalAux.toFixed(2));


	if(TotalAux <= 1000){

	
		
	var TasaAux= 6.00;
	var SustraendoUT=0.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
	
		
	}else if(TotalAux > 1000 && TotalAux <= 1500){
		
	var TasaAux= 9.00;
	var SustraendoUT=30.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
	
	}else if(TotalAux > 1500 && TotalAux <= 2000){
		
	var TasaAux= 12.00;
	var SustraendoUT=75.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
	
	}else if(TotalAux > 2000 && TotalAux <= 2500){
		
	var TasaAux= 16.00;
	var SustraendoUT=155.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
		
	}else if(TotalAux > 2500 && TotalAux <= 3000){
		
	var TasaAux= 20.00;	
	var SustraendoUT=255.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
		
	}else if(TotalAux > 3000 && TotalAux <= 4000){
		
	var TasaAux= 24.00;	
	var SustraendoUT=375.0;
	
var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
		
	}else if(TotalAux > 4000 && TotalAux <= 6000){
		
	var TasaAux= 29.00;		
	var SustraendoUT=575.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
	
	}else if(TotalAux > 6000 ){
		
	var TasaAux= 34.00;			
	var SustraendoUT=875.0;
	
	var TotalPorcentajeTabla=(parseFloat(TotalAux)) * (parseFloat(TasaAux));
	var TotalPorcentajeTabladiv=(parseFloat(TotalPorcentajeTabla)) / (parseFloat("100"));
	var TotalPorcentajeTabla2=(parseFloat(TotalPorcentajeTabladiv))-(parseFloat(SustraendoUT));
		
	$("#totalimpuestogravable2").val(TotalPorcentajeTabla2.toFixed(2));
		
	}



}



function Calculartotalrebajas(){
	
	
	var RadioValorEvaluacion = $('[name="desgravamenesgroup"]:checked').val();

        if (RadioValorEvaluacion == '1') {


	var auxcampo1=$("#TotalUt1").val();

	var auxcampo2=(parseFloat($("#cargafamiliarimput").val()))*(parseFloat("10"));
	
	var auxcampo3=(parseFloat($("#impuestoretenidosimput").val()))/(parseFloat($("#valorUT").val()));

	var suma=(parseFloat(auxcampo1))+(parseFloat(auxcampo2))+(parseFloat(auxcampo3));
	
	$("#totalrebajas").val(suma.toFixed(2));
	
	
	/////////////////Total Mensual a Retener////////////////////
	
	
	var restaAUX=(parseFloat($("#totalimpuestogravable").val()))-(parseFloat($("#totalrebajas").val()));
	
	$("#ImpuestoEstimadoaRetener").val(restaAUX.toFixed(2));
	
	
	
	var totalmensualretenerinputaux=(parseFloat(restaAUX))/(parseFloat($("#totalRemuneraciontribuUT").val()));
	
	var TotalMontomensualretenetauxfin=(parseFloat(totalmensualretenerinputaux))*(parseFloat(100));
	
	$("#totalmensualretenerinput").val(TotalMontomensualretenetauxfin.toFixed(2));


			}
			
		
        if (RadioValorEvaluacion == '2') {
			
			
			var auxcampo1=$("#TotalUt1").val();

	var auxcampo2=(parseFloat($("#cargafamiliarimput").val()))*(parseFloat("10"));
	
	var auxcampo3=(parseFloat($("#impuestoretenidosimput").val()))/(parseFloat($("#valorUT").val()));

	var suma=(parseFloat(auxcampo1))+(parseFloat(auxcampo2))+(parseFloat(auxcampo3));
	
	$("#totalrebajas").val(suma.toFixed(2));
	
	
	/////////////////Total Mensual a Retener////////////////////
	
	
	
	var restaAUX=(parseFloat($("#totalimpuestogravable2").val()))-(parseFloat($("#totalrebajas").val()));
	
	$("#ImpuestoEstimadoaRetener").val(restaAUX.toFixed(2));
	
	
	
	var totalmensualretenerinputaux=(parseFloat(restaAUX))/(parseFloat($("#totalRemuneraciontribuUT").val()));
	
	var TotalMontomensualretenetauxfin=(parseFloat(totalmensualretenerinputaux))*(parseFloat(100));
	
	$("#totalmensualretenerinput").val(TotalMontomensualretenetauxfin.toFixed(2));
			
			
			
	
       }
	


	
	
	
	
	

}




function myModalARIMSJ2() {
	


$('#myModalARIMSJ2').modal('show');
	
	
	
	
	}	




function myModalARIMSJ1() {
	


$('#myModalARIMSJ1').modal('show');
	
	
	
	
	}	
	
	
	
	function myModalARIMSJ0() {
	


$('#myModalARIMSJ0').modal('show');
	
	
	
	
	}	
	
	
	
	function CalculoFinalPDF(){
	
	
	if($('#totalmensualretenerinput').val() != "" )
	{
	$('#myModalMSJGUARDAR').modal('show');
	
	}else{
		
		alert("PARA CONTINUAR DEBE PRIMERO CALCULAR MONTO MENSUAL A RETENER (PORCENTUAL) PRESIONANDO EL BOT\u00d3N (CALCULAR)");
	}

	
}
	
	
	function botonEventoVentanaARIGUARDAR(){
		
		
		 var RadioValorEvaluacion = $('[name="desgravamenesgroup"]:checked').val();
		 
		 var empresa="";
		 var montos="";
		
		 
for(var f=1;f<=nextinput;f++)
{
	
aux4='#monto_'+f;
aux5='#empresa_'+f+'';

empresa+=$(aux5).val()+"-";
montos+=$(aux4).val()+"-";
	


}
		 



    if (RadioValorEvaluacion == '1') {
			
			

			$.ajax({
                url: 'ARIBT.php',
                type: "POST",
                 data:  "op=GuardarCalculoARI"+ "&totalRemuneraciontribuUT=" +$("#totalRemuneraciontribuUT").val()+ "&totalimpuestogravable=" +$('#totalimpuestogravable').val()+ "&totalrebajas=" +$('#totalrebajas').val()+ "&ImpuestoEstimadoaRetener=" +$('#ImpuestoEstimadoaRetener').val()+ "&totalmensualretenerinput=" +$('#totalmensualretenerinput').val()+ "&cargafamiliarimput=" +$('#cargafamiliarimput').val()+ "&impuestoretenidosimput=" +$('#impuestoretenidosimput').val()+ "&desgravamen=" +$('#DESGRAVAMENUNICOCampo').val()+ "&empresa=" +empresa+ "&montos=" +montos+ "&valorUT=" +$('#valorUT').val(),
                beforeSend: function (datos) {
					
				$("#cargadiv").show();
                var x = $("#cargadiv");
                x.html('<img src="images/cargando.gif">');
				
				$("#btn_comfirma_evaluacion").attr("disabled", true);
					
					},
                success: function (datos) {
	

				$("#cargadiv").hide();
				
				
				$("#btn_comfirma_evaluacion").attr("disabled", false);
				
         			////////////////////////////////////////
					$('#myModalMSJGUARDAR').modal('hide');
					///////////////////////////////////////

		            if(datos=="1"){

					alert("Operaci\u00f3n realizada con \u00c9xito");
					
					$("#Div3").fadeOut("slow");
				

					tieneARI();				
	}

					if(datos=="0"){
						
					alert("Problemas al realizar la operaci\u00f3n");
					
					}
					
					
					if(datos=="2"){
						
					alert("No puede realizar una nueva ESTIMACI\u00d3N DE INGRESOS ANUALES ya que tiene una anterior registrada y est\u00e1 en proceso de evaluaci\u00f3n.");
					
					}
					

			}
     });
			



			}
			
		
		
        if (RadioValorEvaluacion == '2') {
			
		
		
		$.ajax({
                url: 'ARIBT.php',
                type: "POST",
                 data:  "op=GuardarCalculoARI"+ "&totalRemuneraciontribuUT=" +$("#totalRemuneraciontribuUT").val()+ "&totalimpuestogravable=" +$('#totalimpuestogravable2').val()+ "&totalrebajas=" +$('#totalrebajas').val()+ "&ImpuestoEstimadoaRetener=" +$('#ImpuestoEstimadoaRetener').val()+ "&totalmensualretenerinput=" +$('#totalmensualretenerinput').val()+ "&cargafamiliarimput=" +$('#cargafamiliarimput').val()+ "&impuestoretenidosimput=" +$('#impuestoretenidosimput').val()+ "&desgravamen=" +$('#DESGRAVAMENUNICOCampo2').val()+ "&empresa=" +empresa+ "&montos=" +montos+ "&valorUT=" +$('#valorUT').val(),
                beforeSend: function (datos) {
					
				$("#cargadiv").show();
                var x = $("#cargadiv");
                x.html('<img src="images/cargando.gif">');
					
					},
                success: function (datos) {

			 

				$("#cargadiv").hide();
				
         			////////////////////////////////////////
					$('#myModalMSJGUARDAR').modal('hide');
					///////////////////////////////////////

		            if(datos=="1"){

					alert("Operaci\u00f3n realizada con \u00c9xito");
					
					$("#Div3").fadeOut("slow");
				
//window.open("CalculoARIPDF.php?totalRemuneraciontribuUT=" + $("#totalRemuneraciontribuUT").val()+"&totalimpuestogravable="+$("#totalimpuestogravable2").val()+ "&totalrebajas=" +$('#totalrebajas').val()+ "&ImpuestoEstimadoaRetener=" +$('#ImpuestoEstimadoaRetener').val()+ "&totalmensualretenerinput=" +$('#totalmensualretenerinput').val()+ "&cargafamiliarimput=" +$('#cargafamiliarimput').val()+ "&impuestoretenidosimput=" +$('#impuestoretenidosimput').val()+ "&desgravamen=" +$('#DESGRAVAMENUNICOCampo2').val()+ "&empresa=" +empresa+ "&montos=" +montos+ "&valorUT=" +$('#valorUT').val());


//$("#divInfo").html("<div class='alert alert-info' role='alert'><p style=' font-size:12px;'>A realizado el cálculo de Estimación De Ingresos Anuales exitosamente para imprimir la constancia de nuevo presione la imagen a continuación <a id='pdfPlanilla' target='framename' > <img src='images/page_white_acrobat.png' title='Imprimir Constancia' alt='Imprimir Constancia' /></a></p></div>");
					tieneARI();

					
					}

					if(datos=="0"){

					alert("Problemas al realizar la operaci\u00f3n");
					
				}

			}
     });
	
  }

}


function SIGUIENTE1(){
	
	if($("#totalRemuneraciontribuUT").val() != "" )
	{
		
/*if( $("#TotalRemuAnual").val() >= 127000 )
{*/
$("#Div1").fadeOut("slow");
$("#Div2").fadeIn("slow");

/*}else{
	
	alert("SUS REMUNERACIONES DEBEN SER IGUALES O SUPERIORES A MIL UNIDADES TRIBUTARIAS ANUALES");
	
}*/


	}else{
		
		alert("PARA CONTINUAR DEBE PRIMERO CALCULAR LA REMUNERACI\u00d3N ESTIMADA EN UT PRESIONANDO EL BOT\u00d3N (CALCULAR)");
		
	}
}


function SIGUIENTE2(){
	
	if($("#totalimpuestogravable").val() != "" )
	{
		
$("#Div2").fadeOut("slow");
$("#Div3").fadeIn("slow");

	}else{
		
	alert("PARA CONTINUAR DEBE PRIMERO CALCULAR EL TOTAL DE IMPUESTO DEL A\u00d1O GRAVABLE PRESIONANDO EL BOT\u00d3N (CALCULAR)");
		
	}
}


function SIGUIENTE3(){
	
	if($("#totalimpuestogravable2").val() != "" )
	{
$("#Div2").fadeOut("slow");
$("#Div3").fadeIn("slow");
	}else{
	alert("PARA CONTINUAR DEBE PRIMERO CALCULAR EL TOTAL DE IMPUESTO DEL A\u00d1O GRAVABLE PRESIONANDO EL BOT\u00d3N (CALCULAR)");
	}
}


function tieneARI(){
$.ajax({
	url: 'ARIBT.php',
	type: 'POST',

	data: {op: 'tieneARI'},
})
.done(function(data) {
	$("#divInfo").html(data);
})
.fail(function() {
	console.log("error");
});


}

//***************************************************************
