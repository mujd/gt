function actividadClaveSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"nombre": $("#txtNombre").val(),
		"ucl": []
  }
	$("#ulUcl").find("input[type='checkbox']:checked").each(function () {
		datos.ucl.push($(this).attr("id"))
	});
	return datos;
}

function actividadClaveLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='actividadClaveEditar(this)'><td>" + this.id + "</td><td>" + this.nombre + "</td></tr>");
	});
}

function actividadClaveLimpiar(actividadClaveTipo) {
	$("#txtID").val("");
	if(actividadClaveTipo) {
		actividadClaveUclMostrar();
		$("#txtNombre").val("");
		$("#txtNombre").focus();
	}
}

function actividadClaveAgregar() {
	actividadClaveLimpiar(true);
}

function actividadClaveListar() {
	ajaxGet(urlApi + "/actividadClave", actividadClaveLlenaTabla);
}

function actividadClaveEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtNombre").val($(oFila).find("td")[1].innerHTML);
	$("#txtDefinicion").val($(oFila).find("td")[2].innerHTML);
	actividadClaveUclMostrar();
}

function actividadClaveRegistrar() {
	var datos = actividadClaveSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/actividadClave/" + datos.id, datos, actividadClaveListar);
	} else {
		ajaxPost(urlApi + "/actividadClave", datos, actividadClaveListar);
	}
	actividadClaveAgregar();
}

function actividadClaveEliminar() {
	var datos = actividadClaveSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/actividadClave/" + datos.id, actividadClaveListar);
		actividadClaveAgregar();
	}
}

function actividadClaveCargar() {
	$("#divContenido").load("actividadClave.html", function() {
		actividadClaveListar();
		actividadClaveLimpiar(true);
		$(".titulo").html("Actividad Clave");
		$("nav.desplegable").css("left", "-200px");
	});
}

/* UCL */

 function actividadClaveUclMostrar() { // Solicita a la API las ucls (todas)
	ajaxGet(urlApi + "/ucl", actividadClaveUclDesplegar);
}

function actividadClaveUclDesplegar(oData) { // Despliega lAs ucls solicitadas a la API
	$("#ulUcl li").remove();
	$(oData).each(function() {
		$("#ulUcl").append("<li><input type='checkbox' id='" + this.id + "'>&nbsp;" + this.nombre + " </li>");
	});
	if($("#txtID").val() != "") {
		ajaxGet(urlApi + "/actividadClave/ucl/" + $("#txtID").val(), actividadClaveUclDesplegarAsignadas);
	}
}

function actividadClaveUclDesplegarAsignadas(oData) {
	$(oData).each(function() {
		id = $(this)[0].ucl_id;
		$("#ulUcl li").each(function() {
			if($(this).find("input")[0].id == id) {
				$(this).find("input")[0].checked = true;
			}
		});
	});
}
