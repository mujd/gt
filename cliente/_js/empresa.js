function empresaSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"rut": $("#txtRut").val(),
		"giro": $("#txtGiro").val(),
		"razonSocial": $("#txtRazonSocial").val(),
		"direccion": $("#txtDireccion").val(),
    "comuna": $("#txtComuna").val(),
		"telefono": $("#txtTelefono").val()
	}
	return datos;
}

function empresaLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='empresaEditar(this)'><td>" + this.id + "</td><td>" + this.rut + "</td><td>" + this.giro + "</td><td>" + this.razon_social + "</td><td>" + this.direccion + "</td><td>" + this.comuna + "</td><td>" + this.telefono + "</td></tr>");
	});
}

function empresaLimpiar(tipo) {
	$("#txtGiro").val("");
	$("#txtRazonSocial").val("");
	$("#txtDireccion").val("");
  $("#txtComuna").val("");
	$("#txtTelefono").val("");
	$("#txtID").val("");
	if(tipo) {
		$("#txtRut").val("");
		$("#txtRut").focus();
	}
}

function empresaAgregar() {
	empresaLimpiar(true);
}

function empresaListar() {
	ajaxGet(urlApi + "/empresa", empresaLlenaTabla);
}

function empresaEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtRut").val($(oFila).find("td")[1].innerHTML);
  $("#txtGiro").val($(oFila).find("td")[2].innerHTML);
	$("#txtRazonSocial").val($(oFila).find("td")[3].innerHTML);
	$("#txtDireccion").val($(oFila).find("td")[4].innerHTML);
  $("#txtComuna").val($(oFila).find("td")[5].innerHTML);
	$("#txtTelefono").val($(oFila).find("td")[6].innerHTML);
}

function empresaRegistrar() {
	var datos = empresaSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/empresa/" + datos.id, datos, empresaListar);
	} else {
		ajaxPost(urlApi + "/empresa", datos, empresaListar);
	}
	empresaAgregar();
}

function empresaEliminar() {
	var datos = empresaSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/empresa/" + datos.id, empresaListar);
		empresaAgregar();
	}
}

function empresaCargar() {
	$("#divContenido").load("empresa.html", function() {
		empresaListar();
		empresaLimpiar(true);
		$(".titulo").html("Empresa");
		$("nav.desplegable").css("left", "-250px");
	});
}
