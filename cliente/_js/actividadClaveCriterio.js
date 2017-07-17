function actividadClaveCriterioSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"criterioId": $("#cmbCriterio").val(),
		"actividadClaveId": $("#cmbActividadClave").val()
	}
	return datos;
}

function actividadClaveCriterioLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='actividadClaveCriterioEditar(this)'><td>" + this.id + "</td><td>" + this.criterio_id + "</td><td>" + this.actividad_clave_id + "</td></tr>");
	});
}

function actividadClaveCriterioLlenaOtroCombo(oData) {
	$("#cmbCriterio option").remove();
	$("#cmbCriterio").append("<option value=''>:: Seleccione Criterio ::</option>");
	$(oData).each(function() {
		$("#cmbCriterio").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function actividadClaveCriterioLlenaCombo(oData) {
	$("#cmbActividadClave option").remove();
	$("#cmbActividadClave").append("<option value=''>:: Seleccione Ac. Clave ::</option>");
	$(oData).each(function() {
		$("#cmbActividadClave").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function actividadClaveCriterioLimpiar(actividadClaveCriterioTipo) {
	$("#cmbActividadClave").val("");
	$("#txtID").val("");
	ajaxGet(urlApi + "/criterio", actividadClaveCriterioLlenaOtroCombo);
  ajaxGet(urlApi + "/actividadClave", actividadClaveCriterioLlenaCombo);
	if(actividadClaveCriterioTipo) {
		$("#cmbCriterio").val("");
		$("#cmbCriterio").focus();
	}
}

function actividadClaveCriterioAgregar() {
	actividadClaveCriterioLimpiar(true);
}

function actividadClaveCriterioListar() {
	ajaxGet(urlApi + "/actividadClaveCriterio", actividadClaveCriterioLlenaTabla);
}

function actividadClaveCriterioEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#cmbCriterio").val($(oFila).find("td")[1].innerHTML);
	$("#cmbActividadClave").val($(oFila).find("td")[2].innerHTML);
}

function actividadClaveCriterioRegistrar() {
	var datos = actividadClaveCriterioSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/actividadClaveCriterio/" + datos.id, datos, actividadClaveCriterioListar);
	} else {
		ajaxPost(urlApi + "/actividadClaveCriterio", datos, actividadClaveCriterioListar);
	}
	actividadClaveCriterioAgregar();
}

function actividadClaveCriterioEliminar() {
	var datos = actividadClaveCriterioSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/actividadClaveCriterio/" + datos.id, actividadClaveCriterioListar);
		actividadClaveCriterioAgregar();
	}
}

function actividadClaveCriterioCargar() {
	$("#divContenido").load("actividadClaveCriterio.html", function() {
		actividadClaveCriterioListar();
		actividadClaveCriterioLimpiar(true);
		$(".titulo").html("Actividad Clave Criterio");
		$("nav.desplegable").css("left", "-200px");
	});
}
