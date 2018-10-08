var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos()
{ 
$("#dialog").hide();
$(".link_sistema").click(ejecutaLoad);
$("#terminar").click(terminar);
setInterval("revive()",600000);

 

}

//**********************************************
function revive(){
	//alert("revive");
	$.post("sesion.php",function(datos) { });
	
	}

//**********************************************
function ejecutaLoad(){
	var  sel = $(this).attr("id"); 
/*if(sel=="AplicacionRendicionCestaForm.php"){
  


                $(document).bind("idle.idleTimer", function () {                   
                location.href="ControlAccesoForm.php";     	 
								                                    
                });
             $.idleTimer(600000);


}*/
	$('#contenedor').load(sel);  
	}
//************************



function terminar(){
	
	var textResal="¿Desea terminar su sesión?";
  var textCont="- -";
 Boxy.confirm("<div id='image'><img src='images/Gnome-System-Log-Out-32.png' width='44' height='38'/></div><div id='text'><b>"+textResal+"</b><br>"+textCont+"</div>", function() {$.post("./ControlAcceso.php",{op:"terminar" },function(datos) {location.href="ControlAccesoForm.php";}); }, {title: 'Atencion'});
    return false;	
	}


	


