



$('#btn_submit').click(function(event)
{
	//event.preventDefault();

	var dt = parseInt( $('#diastotales').val() );
	
	//Se verifica si se selecciono el tipo de incapacidad
    if ($('#tipoIncap').val().trim() === '') {
    	
    	swal({
    		  title: "<small>Por favor seleccionar el Tipo de Incapacidad!</small>",
    		  html: true,
    		  confirmButtonText: "Ok!"
    		}, function(){ setTimeout(function(){ $("#tipoIncap").focus(); }, 200); }
    		);
        
        
     //Se verifica si se selecciono sucursal   
    } else if ($('#sucursal').val().trim() === '') { 
    
    	swal({
  		  title: "<small>Por favor seleccione una Sucursal!</small>",
  		  html: true,
  		  confirmButtonText: "Ok!"
  		}, function(){ setTimeout(function(){ $("#sucursal").focus(); }, 200); }
  		);
    	
        
     	//Se verifica si se selecciono eps   
        } else if ($('#eps').val().trim() === '') {
    	
        	swal({
        		  title: "<small>Por favor seleccione una Eps!</small>",
        		  html: true,
        		  confirmButtonText: "Ok!"
        		}, function(){ setTimeout(function(){ $("#eps").focus(); }, 200); }
        		);
        	
        	
        //Se verifica si se selecciono Asociado   
        } else if ($('#cedula').val().trim() === '') { 
	
    	swal({
    		  title: "<small>Por favor seleccione un Asociado!</small>",
    		  html: true,
    		  confirmButtonText: "Ok!"
    		}, function(){ setTimeout(function(){ $("#cedula").focus(); }, 200); }
    		);
    	
    	
    	//Se verifica si se selecciono Diagnostico   
        } else if ($('#diagnostico').val().trim() === '') { 
        	
        	swal({
        		  title: "<small>Por favor seleccione un Diagnostico!</small>",
        		  html: true,
        		  confirmButtonText: "Ok!"
        		}, function(){ setTimeout(function(){ $("#diagnostico").focus(); }, 200); }
        		);
        	
        	
        	 //Se verifica si se digito numero de incapacidad   
		} else if ($('#nincapacidad').val().trim() === '') { 
			
			swal({
				  title: "<small>Por favor digite numero de incapacidad!</small>",
				  html: true,
				  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ $("#nincapacidad").focus(); }, 200); }
				);
			
			
			//Se verifica si se selecciono fecha inicial   
		} else if ($('#fechainicial').val().trim() === '') { 
			
			swal({
				  title: "<small>Por favor seleccione un fecha inicial!</small>",
				  html: true,
				  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ $("#fechainicial").focus(); }, 200); }
				);
	
			
			//Se verifica formato fecha inicial   
		} else if ( $('#fechainicial').val().trim() !== '' && !isValidDate($('#fechainicial').val().trim()) ) { 
			
			swal({
				  title: "<small>La fecha inicial no es valida. (Y-m-d)</small>",
				  html: true,
				  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ $("#fechainicial").focus(); }, 200); }
				);
	
			
			//Se verifica si se selecciono fecha final   
		} else if ($('#fechafinal').val().trim() === '') { 
			
			swal({
				  title: "<small>Por favor seleccione un fecha final!</small>",
				  html: true,
				  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ $("#fechafinal").focus(); }, 200); }
				);
	
			
			
			//Se verifica formato fecha final   
		} else if ( $('#fechafinal').val().trim() !== '' && !isValidDate($('#fechafinal').val().trim()) ) { 
			
			swal({
				  title: "<small>La fecha final no es valida. (Y-m-d)</small>",
				  html: true,
				  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ $("#fechafinal").focus(); }, 200); }
				);
	
			
			
			//Se verifica cantidad de dias
		} else if ( dt <= 0 ) {
			
			swal({
				  title: "<small>Por favor verificar las fechas, no se ha calculado ningun dia de incapacidad.</small>",
				  html: true,
				  confirmButtonText: "Ok!"
				}
				);
	
			
			
		}
    
    	// Si todo esta OK!
		else {
			return true;
		}

	/*
	var valid_mail =/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
	var valid_tel =/^\d{1,10}$/;

	if ($('#name').val()=='')
	{
		$('#popup_alert').html('Falta Nombre.');
		$('#msg_profile').addClass('popup_show');
		return false;
	}
	else if ($('#email').val()=='' || !valid_mail.test($('#email').val()))
	{
		$('#popup_alert').html('Falta correo electrónico o formato inválido.');
		$('#msg_profile').addClass('popup_show');
		return false;
	}
	else if ($('#tel').val()=='' || !valid_tel.test($('#tel').val()))
	{
		$('#popup_alert').html('Falta Teléfono o formato inválido.');
		$('#msg_profile').addClass('popup_show');
		return false;
	}
	else if ($('#mensaje').val()=='')
	{
		$('#popup_alert').html('Falta Mensaje.');
		$('#msg_profile').addClass('popup_show');
		return false;
	}
	else
	{
		$('#form_add_incap').submit();
	}
	*/
	return false;
});


function analizarFechas() {

   var fechaDesde = cambiarFormatoFecha(document.getElementById('fechainicial').value);
   var fechaHasta = cambiarFormatoFecha(document.getElementById('fechafinal').value);
   var tipoIncap = $( "#tipoIncap option:selected" ).val();
   var diasx = 0;
   var esProrroga = false;
   document.getElementById('diastotales').value=0;
   document.getElementById('diasempresa').value=0;
   document.getElementById('diaseps').value=0;
   document.getElementById('diasarl').value=0;
   
   //alert(fechaDesde);
   
    if ( fechaDesde.length > 0 && fechaHasta.length > 0 ) {
       if ( validaFechaDDMMAAAA(fechaDesde) && validaFechaDDMMAAAA(fechaDesde) ) {
            if (validarFechaMayorQue(fechaDesde, fechaHasta)) {
                
                diasx = diasEntreFechas(fechaDesde, fechaHasta);
                document.getElementById('diastotales').value=diasx;
                
                $("select[id*=prorroga]").each(function(){
                    if ( $(this).val() != "" ) {
                        esProrroga = true;
                        alert("ES PRORROGA");
                    }
                });
                
                if (tipoIncap.length > 0) {
                    if(tipoIncap == 1) { // General
                        if(esProrroga){
                            document.getElementById('diasempresa').value=0;
                            document.getElementById('diaseps').value=diasx;
                            document.getElementById('diasarl').value=0;
                        } else {
                            if(diasx > 2){
                                document.getElementById('diasempresa').value=2;
                                document.getElementById('diaseps').value=diasx-2;
                                document.getElementById('diasarl').value=0;
                            }
                            else{
                                document.getElementById('diasempresa').value=diasx;
                                document.getElementById('diaseps').value=0;
                                document.getElementById('diasarl').value=0;
                            }
                        }
                        
                        
                
                    }
                    
                    if(tipoIncap == 2 || tipoIncap == 3) { // Maternidad - Paternidad
                        document.getElementById('diasempresa').value=0;
                        document.getElementById('diaseps').value=diasx;
                        document.getElementById('diasarl').value=0;
                    }
                    
                    if(tipoIncap == 4) { // ARL
                        if(esProrroga){
                            document.getElementById('diasempresa').value=0;
                            document.getElementById('diaseps').value=0;
                            document.getElementById('diasarl').value=diasx;
                        } else {
                            document.getElementById('diasempresa').value=1;
                            document.getElementById('diaseps').value=0;
                            document.getElementById('diasarl').value=diasx-1;
                        }
                        
                    }
                }
            }
       }
   }
   
}

function cambiarFormatoFecha( fecha ){
	ano = fecha.substr(0,4);
	mes = fecha.substr(5,2);
	dia = fecha.substr(8,2);
	//alert(dia+'/'+mes+'/'+ano);
	return dia+'/'+mes+'/'+ano;
}


function validarFechaMayorQue(fechaInicial,fechaFinal)
{

        valuesStart=fechaInicial.split("/");

        valuesEnd=fechaFinal.split("/");



        // Verificamos que la fecha no sea posterior a la actual

        var dateStart=new Date(valuesStart[2],(valuesStart[1]-1),valuesStart[0]);

        var dateEnd=new Date(valuesEnd[2],(valuesEnd[1]-1),valuesEnd[0]);

        if( dateStart > dateEnd ) {

            return 0;

        }

        return 1;

    }

function validaFechaDDMMAAAA(fecha){
var dtCh= "/";
var minYear=1900;
var maxYear=2100;
function isInteger(s){
	var i;
	for (i = 0; i < s.length; i++){
		var c = s.charAt(i);
		if (((c < "0") || (c > "9"))) return false;
	}
	return true;
}
function stripCharsInBag(s, bag){
	var i;
	var returnString = "";
	for (i = 0; i < s.length; i++){
		var c = s.charAt(i);
		if (bag.indexOf(c) == -1) returnString += c;
	}
	return returnString;
}
function daysInFebruary (year){
	return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
	}
	return this
}
function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strDay=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		return false
	}
	return true
}
if(isDate(fecha)){
	return true;
}else{
	return false;
}
    

}


function diasEntreFechas(f1,f2){
    var aFecha1 = f1.split('/'); 
    var aFecha2 = f2.split('/'); 
    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]); 
    var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]); 
    var dif = fFecha2 - fFecha1;
    var dias = (Math.floor(dif / (1000 * 60 * 60 * 24))+1); 
    return dias;
}



function isValidDate(dateString)
{
	//alert(dateString);
	dateString = dateString.replace(/-/g, "/");
	//alert(dateString);
    // revisar el patrón
    //if(!/^\d{4}\-\d{1,2}\-\d{1,2}$/.test(dateString))
    //  return false;

    // convertir los numeros a enteros
    var parts = dateString.split("/");
    var day = parseInt(parts[2], 10);
    var month = parseInt(parts[1], 10);
    var year = parseInt(parts[0], 10);

    // Revisar los rangos de año y mes
    if( (year < 1000) || (year > 3000) || (month == 0) || (month > 12) )
        return false;

    var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Ajustar para los años bisiestos
    if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;

    // Revisar el rango del dia
    return day > 0 && day <= monthLength[month - 1];
    
};

function validarEntero(valor){
	//intento convertir a entero.
	//si era un entero no le afecta, si no lo era lo intenta convertir
	valor = parseInt(valor)

	//Compruebo si es un valor numérico
	if (isNaN(valor)) {
		//entonces (no es numero) devuelvo el valor cadena vacia
		return "";
	}else{
		//En caso contrario (Si era un número) devuelvo el valor
		return valor;
	}
}









criterios = 0;
criteriosArray0 = new Array();
//criteriosPersonas = 0;




/*********************************/
//********* Busqueda *************/
/*********************************/
function addCriteria () {
	
	id = $( "#criterios" ).val();
	
	var aleatorio = Math.floor(Math.random() * (9999999 - 1000000)) + 1000000;
	
	switch (id) { 
	   	case "0":  // Buscar por persona
	   		if ( criteriosArray0 == undefined ) {
	   			criteriosArray0.push(0);
	   		} else {
	   			criteriosArray0.push(criteriosArray0.length);
	   		}
	   		//criteriosPersonas++;
	   		
	   		var criterioPersona = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><select name="cedula'+aleatorio+'" id="cedula'+aleatorio+'" style="width&#x3A;100&#x25;&#x3B;"><option value="">Buscar Nombre...</option></select><span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioPersona); 
	   		
	   		$("#cedula"+aleatorio).select2({
	  			
	   			minimumInputLength: 2,
	  		   
	  		    ajax: {
	  		        url: url_search_aso,
	  		        dataType: "json",
	  		        type: "POST",
	  		        data: function (params) {

	  		            var queryParameters = {
	  		                term: params.term
	  		            }
	  		            return queryParameters;
	  		        },
	  		        
	    		      processResults: function (data) {
	    	  		        return {
	    	  		            results: $.map(data, function (item) {
	    	  		                return {
	    	  		                    text: item.completeName,
	    	  		                    id: item.id
	    	  		                }
	    	  		            })
	    	  		        };
	    	  		    }
	  		    }
	  		});
	   		
	   		 
	   		
	      	break;
	   	case "1":   // Buscar por Sucursal
	   		
	   		var index, len;
	   		var criterioSucursal = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><select name="sucursal'+aleatorio+'" id="sucursal'+aleatorio+'" style="width&#x3A;100&#x25;&#x3B;"><option value="">Sucursal...</option>';
	   		for (index = 0, len = sucursales.length; index < len; ++index) {
	   			criterioSucursal = criterioSucursal + '<option value="'+sucursales_id[index]+'">'+sucursales[index]+'</option>';
	   		}
	   		criterioSucursal = criterioSucursal + '</select><span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioSucursal); 
	   		
	   		$('#sucursal'+aleatorio).select2();
	   		
	      	break;
	      	
	   	case "2":  // Buscar por Eps 
	   		
	   		var index, len;
	   		var criterioEps = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><select name="eps'+aleatorio+'" id="eps'+aleatorio+'" style="width&#x3A;100&#x25;&#x3B;"><option value="">Eps...</option>';
	   		for (index = 0, len = epss.length; index < len; ++index) {
	   			criterioEps = criterioEps + '<option value="'+epss_id[index]+'">'+epss[index]+'</option>';
	   		}
	   		criterioEps = criterioEps + '</select><span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioEps); 
	   		
	   		$('#eps'+aleatorio).select2();
	   		
	      	break;
	      	
	   	case "3":  // Buscar por No Incapacidad 
	   		
	   		var criterioNoIncap = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><input name="noincap'+aleatorio+'" id="noincap'+aleatorio+'" placeholder="No. Incap" >';
	   		
	   		criterioNoIncap = criterioNoIncap + '<span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioNoIncap); 
	   		
	   		
	      	break;
	      	
	   	case "4":  // Buscar por Tipo Incapacidad
	   		
	   		var index, len;
	   		var criterioTipoIncap = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><select name="tipoincap'+aleatorio+'" id="tipoincap'+aleatorio+'" style="width&#x3A;100&#x25;&#x3B;"><option value="">Tipo Incap...</option>';
	   		for (index = 0, len = tipoIncapacidad.length; index < len; ++index) {
	   			criterioTipoIncap = criterioTipoIncap + '<option value="'+tipoIncapacidad_id[index]+'">'+tipoIncapacidad[index]+'</option>';
	   		}
	   		criterioTipoIncap = criterioTipoIncap + '</select><span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioTipoIncap); 
	   		
	   		$('#tipoincap'+aleatorio).select2();
	   		
	      	break;
	      	
	   	case "5":  // Buscar por Diagnostico
	   		
	   		
	   		
	   		var criterioDiagnosticio = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><select name="diagnostico'+aleatorio+'" id="diagnostico'+aleatorio+'" style="width&#x3A;100&#x25;&#x3B;"><option value="">Buscar Diagnostico...</option></select><span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioDiagnosticio); 
	   		
	   		$("#diagnostico"+aleatorio).select2({
	  			
	   			minimumInputLength: 2,
	  		   
	  		    ajax: {
	  		        url: url_search_dg,
	  		        dataType: "json",
	  		        type: "POST",
	  		        data: function (params) {

	  		            var queryParameters = {
	  		                term: params.term
	  		            }
	  		            return queryParameters;
	  		        },
	  		        
	    		      processResults: function (data) {
	    	  		        return {
	    	  		            results: $.map(data, function (item) {
	    	  		                return {
	    	  		                    text: item.completeName,
	    	  		                    id: item.id
	    	  		                }
	    	  		            })
	    	  		        };
	    	  		    }
	  		    }
	  		});
	   		
	   		 
	   		
	      	break;
	      	
	   	case "6":  // Buscar por Fecha Inicial 
	   		
	   		var criterioFecIni = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><input name="fecini'+aleatorio+'" id="fecini'+aleatorio+'" placeholder="Fecha Inicial" title="Fecha Inicial" type="date" size="8" >';
	   		
	   		criterioFecIni = criterioFecIni + '<select id="condicion'+aleatorio+'" name="condicion'+aleatorio+'"><option value="=" title="Es Igual">=</option><option value=">" title="Mayor Que">></option><option value="<" title="Menor Que"><</option><option value=">=" title="Mayor o Igual">>=</option><option value="<=" title="Menor o Igual"><=</option><option value="<>" title="Diferente"><></option></select>';
	   		
	   		criterioFecIni = criterioFecIni + '<span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioFecIni); 
	   		
	   		$('#'+"fecini"+aleatorio).datetimepicker({  format: 'DD/MM/YYYY' });
	   		
	      	break;
	      	

	   	case "7":  // Buscar por Fecha Final 
	   		
	   		var criterioFecFin = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><input name="fecfin'+aleatorio+'" id="fecfin'+aleatorio+'" placeholder="Fecha Final" title="Fecha Final" type="date" size="8">';
	   		
	   		criterioFecFin = criterioFecFin + '<select id="condicion'+aleatorio+'" name="condicion'+aleatorio+'"><option value="=" title="Es Igual">=</option><option value=">" title="Mayor Que">></option><option value="<" title="Menor Que"><</option><option value=">=" title="Mayor o Igual">>=</option><option value="<=" title="Menor o Igual"><=</option><option value="<>" title="Diferente"><></option></select>';
	   		
	   		criterioFecFin = criterioFecFin + '<span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioFecFin); 
	   		
	   		$('#'+"fecfin"+aleatorio).datetimepicker({  format: 'DD/MM/YYYY' });
	   		
	      	break;
	      	

	   	case "8":  // Buscar por Dias Totales
	   		
	   		var criterioDiaTot = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><input name="diatot'+aleatorio+'" id="diatot'+aleatorio+'" placeholder="Dias Totales" title="Dias Totales" type="numeric" size="6" >';
	   		
	   		criterioDiaTot = criterioDiaTot + '<select id="condicion'+aleatorio+'" name="condicion'+aleatorio+'"><option value="=" title="Es Igual">=</option><option value=">" title="Mayor Que">></option><option value="<" title="Menor Que"><</option><option value=">=" title="Mayor o Igual">>=</option><option value="<=" title="Menor o Igual"><=</option><option value="<>" title="Diferente"><></option></select>';
	   		
	   		criterioDiaTot = criterioDiaTot + '<span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioDiaTot); 
	   		
	   		
	      	break;
	      	
	   	
	   	case "9":  // Buscar por Dias Empresa
	   		
	   		var criterioDiaEmp = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><input name="diaemp'+aleatorio+'" id="diaemp'+aleatorio+'" placeholder="Dias Empresa" title="Dias Empresa" type="numeric" size="6" >';
	   		
	   		criterioDiaEmp = criterioDiaEmp + '<select id="condicion'+aleatorio+'" name="condicion'+aleatorio+'"><option value="=" title="Es Igual">=</option><option value=">" title="Mayor Que">></option><option value="<" title="Menor Que"><</option><option value=">=" title="Mayor o Igual">>=</option><option value="<=" title="Menor o Igual"><=</option><option value="<>" title="Diferente"><></option></select>';
	   		
	   		criterioDiaEmp = criterioDiaEmp + '<span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioDiaEmp); 
	   		
	   		
	      	break;
	      	
	   	
	   	case "10":  // Buscar por Dias Eps
	   		
	   		var criterioDiaEps = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><input name="diaeps'+aleatorio+'" id="diaeps'+aleatorio+'" placeholder="Dias Eps" title="Dias Eps" type="numeric" size="6">';
	   		
	   		criterioDiaEps = criterioDiaEps + '<select id="condicion'+aleatorio+'" name="condicion'+aleatorio+'"><option value="=" title="Es Igual">=</option><option value=">" title="Mayor Que">></option><option value="<" title="Menor Que"><</option><option value=">=" title="Mayor o Igual">>=</option><option value="<=" title="Menor o Igual"><=</option><option value="<>" title="Diferente"><></option></select>';
	   		
	   		criterioDiaEps = criterioDiaEps + '<span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioDiaEps); 
	   		
	   		
	      	break;
	      	
	   	
	   	case "11":  // Buscar por Dias Arl
	   		
	   		var criterioDiaArl = '<div class="col-md-2" id="criterio'+aleatorio+'" style="white-space: pre;"><input name="diaarl'+aleatorio+'" id="diaarl'+aleatorio+'" placeholder="Dias ARL" title="Dias ARL" type="numeric" size="6">';
	   		
	   		criterioDiaArl = criterioDiaArl + '<select id="condicion'+aleatorio+'" name="condicion'+aleatorio+'"><option value="=" title="Es Igual">=</option><option value=">" title="Mayor Que">></option><option value="<" title="Menor Que"><</option><option value=">=" title="Mayor o Igual">>=</option><option value="<=" title="Menor o Igual"><=</option><option value="<>" title="Diferente"><></option></select>';
	   		
	   		criterioDiaArl = criterioDiaArl + '<span onclick="$( \'#criterio'+aleatorio+'\' ).remove(); return false;"><img src="/img/incap/delete.png" width="20px" style="cursor:pointer;" title="Quitar Criterio" /></span></div>';
	   		
	   		$("#formbusqueda").append(criterioDiaArl); 
	   		
	   		
	      	break;
	      	  	
	      	
		default: 
	   		swal({
				  title: "<small>Debes seleccionar un criterio de busqueda!</small>",
				  html: true,
				  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ $("#criterios").focus(); }, 200); }
			);
	
		
	} // End Switch
	
	
	$("#criterios").val(""); // Reiniciar Select
		
	
	
}

function buscar(){
	
	if ( $("#formbusqueda").find(':input').length > 0 ) {
		
		if ( validarCriterios() ) {
			
//alert($("#formbusqueda").serialize());
			$.ajax({
  		        url: url_search_incap,
  		        //dataType: "json",
  		        type: "POST",
  		        data: $("#formbusqueda").serialize(),
  		        success: function (data) { //processResults
			    	
		  		      $("#incapacidadesEncontradas").html(data);
		  		}
  		    });
		}
		
	} else {
		
		swal({
			  title: "<small>Debes agregar un criterio de busqueda!</small>",
			  html: true,
			  confirmButtonText: "Ok!"
			}, function(){ setTimeout(function(){ $("#criterios").focus(); }, 200); }
		);
		
	}
	
}

function exportarReporte() {
	
	if ( $("#formbusqueda").find(':input').length > 0 ) {
		
		if ( validarCriterios() ) {
			
			alert( $("#formbusqueda").serialize() + '&reporte=' + $("#select_reporte").val() );
			$.ajax({
  		        url: url_get_report,
  		        //dataType: "json",
  		        type: "POST",
  		        data: $("#formbusqueda").serialize() + '&reporte=' + $("#select_reporte").val(),
  		        success: function (data) { //processResults
			    	
		  		      //$("#incapacidadesEncontradas").html(data);
  		        	window.open('data:application/vnd.ms-excel,' + data);
		  		}
  		    });
		}
		
	} else {
		
		swal({
			  title: "<small>Debes agregar un criterio de busqueda!</small>",
			  html: true,
			  confirmButtonText: "Ok!"
			}, function(){ setTimeout(function(){ $("#criterios").focus(); }, 200); }
		);
		
	}
}


function validarCriterios() {
	
	var retornar = true;
	
	// Validar Personas
	$("select[id*=cedula]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes Seleccionar una persona, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select2('open');  }, 200); }
			);
			retornar = false;
		}
	});

	// Validar Sucursal
	$("select[id*=sucursal]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes Seleccionar una sucursal, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select2('open');  }, 200); }
			);
			retornar = false;
		}
	});

	// Validar Eps
	$("select[id*=eps]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes Seleccionar una Eps, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select2('open');  }, 200); }
			);
			retornar = false;
		}
	});
	
	
	// Validar No Incapacidad
	$("input[id*=noincap]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar el n&uacute;mero de la incapacidad, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).focus();  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/^[0-9a-zA-Z._\-\s]+$/) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar un n&uacute;mero de incapacidad permitido, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});
	

	// Validar Tipo Incapacidad
	$("select[id*=tipoincap]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes seleccionar el tipo de incapacidad, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select2('open');  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/^[0-9]+$/) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes selecccionar un tipo de incapacidad permitido, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});
	

	// Validar Diagnostico
	$("select[id*=diagnostico]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes seleccionar el diagnostico de incapacidad, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select2('open');  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/^[0-9]+$/) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes selecccionar un diagnostico de incapacidad permitido, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});


	// Validar Fecha Inicial
	$("input[id*=fecini]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes seleccionar fecha de inicio de incapacidad, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).focus();  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/(\d{2}\/\d{2}\/\d{4})/gm) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes selecccionar una fecha de incapacidad permitida (dd/mm/aaaa), o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});


	// Validar Fecha Final
	$("input[id*=fecfin]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes seleccionar fecha final de incapacidad, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).focus();  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/(\d{2}\/\d{2}\/\d{4})/gm) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes selecccionar una fecha de incapacidad permitida (dd/mm/aaaa), o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});


	// Validar Dias Totales
	$("input[id*=diatot]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar los dias totales de incapacidad, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).focus();  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/^[0-9]+$/) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar una cantidad de dias de incapacidad permitida, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});
	

	// Validar Dias Empresa
	$("input[id*=diaemp]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar los dias empresa, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).focus();  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/^[0-9]+$/) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar una cantidad de dias de empresa permitida, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});
	

	// Validar Dias Eps
	$("input[id*=diaeps]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar los dias eps, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).focus();  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/^[0-9]+$/) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar una cantidad de dias de eps permitida, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});
	

	// Validar Dias Arl
	$("input[id*=diaarl]").each(function(){
		if ( $(this).val() == "" && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar los dias ARL, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).focus();  }, 200); }
			);
			retornar = false;
		}
		
		if ( !$(this).val().match(/^[0-9]+$/) && retornar) {
			var idElement = $(this).attr('id');
			swal({
				  title: "<small>Debes digitar una cantidad de dias de ARL permitida, o quitar este criterio de busqueda.</small>",
				  html: true,  confirmButtonText: "Ok!"
				}, function(){ setTimeout(function(){ 	$('#' + idElement).select();  }, 200); }
			);
			retornar = false;
		}
	});
	
	
	
	
	return retornar;
}



function existeFecha(fecha){
    var fechaf = fecha.split("/");
    var day = fechaf[0];
    var month = fechaf[1];
    var year = fechaf[2];
    var date = new Date(year,month,'0');
    if((day-0)>(date.getDate()-0)){
          return false;
    }
    return true;
}


function verIncap(id) {
	
	$.ajax({
        url: url_view_incap,
        type: "POST",
        data: "id="+id,
        success: function (data) { //processResults
        	$("#view_incap").html(data);
        	$( "#view_incap" ).dialog( "open" );
        	//alert(data);
	      //$("#incapacidadesEncontradas").html(data);
        }
    });
	//alert(id);
	
}

