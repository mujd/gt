function personaSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"rut": $("#txtRut").val(),
		"nombre": $("#txtNombre").val(),
		"apellidoPaterno": $("#txtApellidoPaterno").val(),
		"apellidoMaterno": $("#txtApellidoMaterno").val(),
		"correo": $("#txtCorreo").val(),
		"perfilId": $("#cmbPerfil").val(),
		"empresas": []
	}
	$("#ulEmpresa").find("input[type='checkbox']:checked").each(function () {
		datos.empresas.push($(this).attr("id"))
	});
	return datos;
}

function personaLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='personaEditar(this);'><td>" + this.id + "</td><td>" + this.rut + "</td><td><input type='hidden' value='" + this.nombre + "@" + this.apellido_paterno + "@" + this.apellido_materno + "'>" + this.nombre + " " + this.apellido_paterno + " " + this.apellido_materno + "</td><td>" + this.correo + "</td><td>" + this.perfil_id + "</td></tr>");
	});
}

function personaLlenaCombo(oData) {
	$("#cmbPerfil option").remove();
	$("#cmbPerfil").append("<option value=''>:: Seleccione Perfil ::</option>");
	$(oData).each(function() {
		$("#cmbPerfil").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function personaLimpiar(personaTipo) {
	$("#txtNombre").val("");
	$("#txtApellidoPaterno").val("");
	$("#txtApellidoMaterno").val("");
	$("#txtCorreo").val("");
	$("#cmbPerfil").val("");
	$("#txtID").val("");
	ajaxGet(urlApi + "/perfil", personaLlenaCombo);
	if(personaTipo) {
		personaEmpresaMostrar();
		$("#txtRut").val("");
		$("#txtRut").focus();
	}
}

function personaAgregar() {
	personaLimpiar(true);
}

function personaListar() {
	ajaxGet(urlApi + "/persona", personaLlenaTabla);

}

function personaEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtRut").val($(oFila).find("td")[1].innerHTML);
	$("#txtNombre").val($($(oFila).find("td")[2]).find("input")[0].value.split("@")[0]);
	$("#txtApellidoPaterno").val($($(oFila).find("td")[2]).find("input")[0].value.split("@")[1]);
	$("#txtApellidoMaterno").val($($(oFila).find("td")[2]).find("input")[0].value.split("@")[2]);
	$("#txtCorreo").val($(oFila).find("td")[3].innerHTML);
	$("#cmbPerfil").val($(oFila).find("td")[4].innerHTML);
	personaEmpresaMostrar();
}

function personaRegistrar() {
	var datos = personaSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/persona/" + datos.id, datos, personaListar);
	} else {
		ajaxPost(urlApi + "/persona", datos, personaListar);
	}
	personaAgregar();
}

function personaEliminar() {
	var datos = personaSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/persona/" + datos.id, personaListar);
		personaAgregar();
	}
}

function personaCargar() {
	$("#divContenido").load("persona.html", function() {
		personaListar();
		personaLimpiar(true);
		$(".titulo").html("Persona");
		$("nav.desplegable").css("left", "-250px");
	});
}

/* Empresa */

 function personaEmpresaMostrar() { // Solicita a la API las empresas (todas)
	ajaxGet(urlApi + "/empresa", personaEmpresaDesplegar);
}

function personaEmpresaDesplegar(oData) { // Despliega lAs empresas solicitadas a la API
	$("#ulEmpresa li").remove();
	$(oData).each(function() {
		$("#ulEmpresa").append("<li><input type='checkbox' id='" + this.id + "'>&nbsp;" + this.razon_social + " </li>");
	});
	if($("#txtID").val() != "") {
		ajaxGet(urlApi + "/empresa/persona/" + $("#txtID").val(), personaEmpresaDesplegarAsignadas);
	}
}

function personaEmpresaDesplegarAsignadas(oData) {
	$(oData).each(function() {
		id = $(this)[0].empresa_id;
		$("#ulEmpresa li").each(function() {
			if($(this).find("input")[0].id == id) {
				$(this).find("input")[0].checked = true;
			}
		});
	});
}
