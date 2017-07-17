function uclActividadClaveSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"uclId": $("#cmbUcl").val(),
		"actividadClaveId": $("#cmbActividadClave").val()
	}
	return datos;
}

function uclActividadClaveLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='uclActividadClaveEditar(this)'><td>" + this.id + "</td><td>" + this.ucl_id + "</td><td>" + this.actividad_clave_id + "</td></tr>");
	});
}

function uclActividadClaveLlenaOtroCombo(oData) {
	$("#cmbUcl option").remove();
	$("#cmbUcl").append("<option value=''>:: Seleccione UCL ::</option>");
	$(oData).each(function() {
		$("#cmbUcl").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function uclActividadClaveLlenaCombo(oData) {
	$("#cmbActividadClave option").remove();
	$("#cmbActividadClave").append("<option value=''>:: Seleccione Act.Clave ::</option>");
	$(oData).each(function() {
		$("#cmbActividadClave").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function uclActividadClaveLimpiar(uclActividadClaveTipo) {
	$("#cmbActividadClave").val("");
	$("#txtID").val("");
	ajaxGet(urlApi + "/ucl", uclActividadClaveLlenaOtroCombo);
  ajaxGet(urlApi + "/actividadClave", uclActividadClaveLlenaCombo);
	if(uclActividadClaveTipo) {
		$("#cmbUcl").val("");
		$("#cmbUcl").focus();
	}
}

function uclActividadClaveAgregar() {
	uclActividadClaveLimpiar(true);
}

function uclActividadClaveListar() {
	ajaxGet(urlApi + "/uclActividadClave", uclActividadClaveLlenaTabla);
}

function uclActividadClaveEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#cmbUcl").val($(oFila).find("td")[1].innerHTML);
	$("#cmbActividadClave").val($(oFila).find("td")[2].innerHTML);
}

function uclActividadClaveRegistrar() {
	var datos = uclActividadClaveSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/uclActividadClave/" + datos.id, datos, uclActividadClaveListar);
	} else {
		ajaxPost(urlApi + "/uclActividadClave", datos, uclActividadClaveListar);
	}
	uclActividadClaveAgregar();
}

function uclActividadClaveEliminar() {
	var datos = uclActividadClaveSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/uclActividadClave/" + datos.id, uclActividadClaveListar);
		uclActividadClaveAgregar();
	}
}

function uclActividadClaveCargar() {
	$("#divContenido").load("uclActividadClave.html", function() {
		uclActividadClaveListar();
		uclActividadClaveLimpiar(true);
		$(".titulo").html("UCL Actividad Clave");
		$("nav.desplegable").css("left", "-200px");
	});
}
