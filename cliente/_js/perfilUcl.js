function perfilUclSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"uclId": $("#cmbUcl").val(),
		"perfilId": $("#cmbPerfil").val()
	}
	return datos;
}

function perfilUclLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='perfilUclEditar(this)'><td>" + this.id + "</td><td>" + this.ucl_id + "</td><td>" + this.perfil_id + "</td></tr>");
	});
}

function perfilUclLlenaOtroCombo(oData) {
	$("#cmbUcl option").remove();
	$("#cmbUcl").append("<option value=''>:: Seleccione UCL ::</option>");
	$(oData).each(function() {
		$("#cmbUcl").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function perfilUclLlenaCombo(oData) {
	$("#cmbPerfil option").remove();
	$("#cmbPerfil").append("<option value=''>:: Seleccione Perfil ::</option>");
	$(oData).each(function() {
		$("#cmbPerfil").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function perfilUclLimpiar(perfilUclTipo) {
	$("#cmbPerfil").val("");
	$("#txtID").val("");
	ajaxGet(urlApi + "/ucl", perfilUclLlenaOtroCombo);
  ajaxGet(urlApi + "/perfil", perfilUclLlenaCombo);
	if(perfilUclTipo) {
		$("#cmbUcl").val("");
		$("#cmbUcl").focus();
	}
}

function perfilUclAgregar() {
	perfilUclLimpiar(true);
}

function perfilUclListar() {
	ajaxGet(urlApi + "/perfilUcl", perfilUclLlenaTabla);
}

function perfilUclEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#cmbUcl").val($(oFila).find("td")[1].innerHTML);
	$("#cmbPerfil").val($(oFila).find("td")[2].innerHTML);
}

function perfilUclRegistrar() {
	var datos = perfilUclSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/perfilUcl/" + datos.id, datos, perfilUclListar);
	} else {
		ajaxPost(urlApi + "/perfilUcl", datos, perfilUclListar);
	}
	perfilUclAgregar();
}

function perfilUclEliminar() {
	var datos = perfilUclSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/perfilUcl/" + datos.id, perfilUclListar);
		perfilUclAgregar();
	}
}

function perfilUclCargar() {
	$("#divContenido").load("perfilUcl.html", function() {
		perfilUclListar();
		perfilUclLimpiar(true);
		$(".titulo").html("Perfil UCL");
		$("nav.desplegable").css("left", "-200px");
	});
}
