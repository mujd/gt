function perfilCompetenciaConductualSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"perfilId": $("#cmbPerfil").val(),
		"competenciaConductualId": $("#cmbCompetenciaConductual").val()
	}
	return datos;
}

function perfilCompetenciaConductualLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='perfilCompetenciaConductualEditar(this)'><td>" + this.id + "</td><td>" + this.perfil_id + "</td><td>" + this.competencia_conductual_id + "</td></tr>");
	});
}

function perfilCompetenciaConductualLlenaOtroCombo(oData) {
	$("#cmbPerfil option").remove();
	$("#cmbPerfil").append("<option value=''>:: Seleccione Perfil ::</option>");
	$(oData).each(function() {
		$("#cmbPerfil").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function perfilCompetenciaConductualLlenaCombo(oData) {
	$("#cmbCompetenciaConductual option").remove();
	$("#cmbCompetenciaConductual").append("<option value=''>:: Seleccione C. Conductual ::</option>");
	$(oData).each(function() {
		$("#cmbCompetenciaConductual").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function perfilCompetenciaConductualLimpiar(perfilCompetenciaConductualTipo) {
	$("#cmbCompetenciaConductual").val("");
	$("#txtID").val("");
	ajaxGet(urlApi + "/perfil", perfilCompetenciaConductualLlenaOtroCombo);
  ajaxGet(urlApi + "/competenciaConductual", perfilCompetenciaConductualLlenaCombo);
	if(perfilCompetenciaConductualTipo) {
		$("#cmbPerfil").val("");
		$("#cmbPerfil").focus();
	}
}

function perfilCompetenciaConductualAgregar() {
	perfilCompetenciaConductualLimpiar(true);
}

function perfilCompetenciaConductualListar() {
	ajaxGet(urlApi + "/perfilCompetenciaConductual", perfilCompetenciaConductualLlenaTabla);
}

function perfilCompetenciaConductualEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#cmbPerfil").val($(oFila).find("td")[1].innerHTML);
	$("#cmbCompetenciaConductual").val($(oFila).find("td")[2].innerHTML);
}

function perfilCompetenciaConductualRegistrar() {
	var datos = perfilCompetenciaConductualSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/perfilCompetenciaConductual/" + datos.id, datos, perfilCompetenciaConductualListar);
	} else {
		ajaxPost(urlApi + "/perfilCompetenciaConductual", datos, perfilCompetenciaConductualListar);
	}
	perfilCompetenciaConductualAgregar();
}

function perfilCompetenciaConductualEliminar() {
	var datos = perfilCompetenciaConductualSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/perfilCompetenciaConductual/" + datos.id, perfilCompetenciaConductualListar);
		perfilCompetenciaConductualAgregar();
	}
}

function perfilCompetenciaConductualCargar() {
	$("#divContenido").load("perfilCompetenciaConductual.html", function() {
		perfilCompetenciaConductualListar();
		perfilCompetenciaConductualLimpiar(true);
		$(".titulo").html("Perfil Competencia Conductual");
		$("nav.desplegable").css("left", "-200px");
	});
}
