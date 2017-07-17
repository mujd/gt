function criterioSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"nombre": $("#txtNombre").val(),
		"actividadClaveId": $("#ulActividadClave").val()
  }
	//$("#ulActividadClave").find("input[type='checkbox']:checked").each(function () {
		//datos.actividadClave.push($(this).attr("id"))
	//});
	return datos;
}

function criterioLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='criterioEditar(this)'><td>" + this.id + "</td><td>" + this.nombre + "</td></tr>");
	});
}

function criterioLimpiar(criterioTipo) {
	$("#txtID").val("");
	if(criterioTipo) {
		criterioActividadClaveMostrar();
		$("#txtNombre").val("");
		$("#txtNombre").focus();
	}
}

function criterioAgregar() {
	criterioLimpiar(true);
}

function criterioListar() {
	ajaxGet(urlApi + "/criterio", criterioLlenaTabla);
}

function criterioEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtNombre").val($(oFila).find("td")[1].innerHTML);
	criterioActividadClaveMostrar();
}

function criterioRegistrar() {
	var datos = criterioSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/criterio/" + datos.id, datos, criterioListar);
	} else {
		ajaxPost(urlApi + "/criterio", datos, criterioListar);
	}
	criterioAgregar();
}

function criterioEliminar() {
	var datos = criterioSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/criterio/" + datos.id, criterioListar);
		criterioAgregar();
	}
}

function criterioCargar() {
	$("#divContenido").load("criterio.html", function() {
		criterioListar();
		criterioLimpiar(true);
		$(".titulo").html("Criterio");
		$("nav.desplegable").css("left", "-200px");
	});
}

/* ActividadClave */

 function criterioActividadClaveMostrar() { // Solicita a la API las actividadeClaves (todas)
	ajaxGet(urlApi + "/actividadClave", criterioActividadClaveDesplegar);
}

function criterioActividadClaveDesplegar(oData) { // Despliega lAs actividadClaves solicitadas a la API
	$("#ulActividadClave li").remove();
	$(oData).each(function() {
		$("#ulActividadClave").append("<li><input type='checkbox' id='" + this.id + "'>&nbsp;" + this.nombre + " </li>");
	});
	if($("#txtID").val() != "") {
		ajaxGet(urlApi + "/criterio/actividadClave/" + $("#txtID").val(), criterioActividadClaveDesplegarAsignadas);
	}
}

function criterioActividadClaveDesplegarAsignadas(oData) {
	$(oData).each(function() {
		id = $(this)[0].actividadClave_id;
		$("#ulActividadClave li").each(function() {
			if($(this).find("input")[0].id == id) {
				$(this).find("input")[0].checked = true;
			}
		});
	});
}
