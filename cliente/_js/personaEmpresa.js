function personaEmpresaSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"empresaId": $("#cmbEmpresa").val(),
		"personaId": $("#cmbPersona").val()
	}
	return datos;
}

function personaEmpresaLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='personaEmpresaEditar(this)'><td>" + this.id + "</td><td>" + this.empresa_id + "</td><td>" + this.persona_id + "</td></tr>");
	});
}

function personaEmpresaLlenaOtroCombo(oData) {
	$("#cmbEmpresa option").remove();
	$("#cmbEmpresa").append("<option value=''>:: Seleccione Empresa ::</option>");
	$(oData).each(function() {
		$("#cmbEmpresa").append("<option value='" + this.id + "'>" + this.razon_social + "</option>");
	});
}

function personaEmpresaLlenaCombo(oData) {
	$("#cmbPersona option").remove();
	$("#cmbPersona").append("<option value=''>:: Seleccione Persona ::</option>");
	$(oData).each(function() {
		$("#cmbPersona").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function personaEmpresaLimpiar(personaEmpresaTipo) {
	$("#cmbPersona").val("");
	$("#txtID").val("");
	ajaxGet(urlApi + "/empresa", personaEmpresaLlenaOtroCombo);
  ajaxGet(urlApi + "/persona", personaEmpresaLlenaCombo);
	if(personaEmpresaTipo) {
		$("#cmbEmpresa").val("");
		$("#cmbEmpresa").focus();
	}
}

function personaEmpresaAgregar() {
	personaEmpresaLimpiar(true);
}

function personaEmpresaListar() {
	ajaxGet(urlApi + "/personaEmpresa", personaEmpresaLlenaTabla);
}

function personaEmpresaEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#cmbEmpresa").val($(oFila).find("td")[1].innerHTML);
	$("#cmbPersona").val($(oFila).find("td")[2].innerHTML);
}

function personaEmpresaRegistrar() {
	var datos = personaEmpresaSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/personaEmpresa/" + datos.id, datos, personaEmpresaListar);
	} else {
		ajaxPost(urlApi + "/personaEmpresa", datos, personaEmpresaListar);
	}
	personaEmpresaAgregar();
}

function personaEmpresaEliminar() {
	var datos = personaEmpresaSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/personaEmpresa/" + datos.id, personaEmpresaListar);
		personaEmpresaAgregar();
	}
}

function personaEmpresaCargar() {
	$("#divContenido").load("personaEmpresa.html", function() {
		personaEmpresaListar();
		personaEmpresaLimpiar(true);
		$(".titulo").html("Persona Empresa");
		$("nav.desplegable").css("left", "-200px");
	});
}
