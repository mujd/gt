function competenciaConductualIndicadorSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"competenciaConductualId": $("#cmbCompetenciaConductual").val(),
		"indicadorId": $("#cmbIndicador").val()
	}
	return datos;
}

function competenciaConductualIndicadorLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='competenciaConductualIndicadorEditar(this)'><td>" + this.id + "</td><td>" + this.competencia_conductual_id + "</td><td>" + this.indicador_id + "</td></tr>");
	});
}

function competenciaConductualIndicadorLlenaOtroCombo(oData) {
	$("#cmbCompetenciaConductual option").remove();
	$("#cmbCompetenciaConductual").append("<option value=''>:: Seleccione C.Conductual ::</option>");
	$(oData).each(function() {
		$("#cmbCompetenciaConductual").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function competenciaConductualIndicadorLlenaCombo(oData) {
	$("#cmbIndicador option").remove();
	$("#cmbIndicador").append("<option value=''>:: Seleccione Indicador ::</option>");
	$(oData).each(function() {
		$("#cmbIndicador").append("<option value='" + this.id + "'>" + this.nombre + "</option>");
	});
}

function competenciaConductualIndicadorLimpiar(competenciaConductualIndicadorTipo) {
	$("#cmbIndicador").val("");
	$("#txtID").val("");
	ajaxGet(urlApi + "/competenciaConductual", competenciaConductualIndicadorLlenaOtroCombo);
  ajaxGet(urlApi + "/indicador", competenciaConductualIndicadorLlenaCombo);
	if(competenciaConductualIndicadorTipo) {
		$("#cmbCompetenciaConductual").val("");
		$("#cmbCompetenciaConductual").focus();
	}
}

function competenciaConductualIndicadorAgregar() {
	competenciaConductualIndicadorLimpiar(true);
}

function competenciaConductualIndicadorListar() {
	ajaxGet(urlApi + "/competenciaConductualIndicador", competenciaConductualIndicadorLlenaTabla);
}

function competenciaConductualIndicadorEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#cmbCompetenciaConductual").val($(oFila).find("td")[1].innerHTML);
	$("#cmbIndicador").val($(oFila).find("td")[2].innerHTML);
}

function competenciaConductualIndicadorRegistrar() {
	var datos = competenciaConductualIndicadorSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/competenciaConductualIndicador/" + datos.id, datos, competenciaConductualIndicadorListar);
	} else {
		ajaxPost(urlApi + "/competenciaConductualIndicador", datos, competenciaConductualIndicadorListar);
	}
	competenciaConductualIndicadorAgregar();
}

function competenciaConductualIndicadorEliminar() {
	var datos = competenciaConductualIndicadorSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/competenciaConductualIndicador/" + datos.id, competenciaConductualIndicadorListar);
		competenciaConductualIndicadorAgregar();
	}
}

function competenciaConductualIndicadorCargar() {
	$("#divContenido").load("competenciaConductualIndicador.html", function() {
		competenciaConductualIndicadorListar();
		competenciaConductualIndicadorLimpiar(true);
		$(".titulo").html("Actividad Conductual Indicador");
		$("nav.desplegable").css("left", "-200px");
	});
}
