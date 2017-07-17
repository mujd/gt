function indicadorSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"nombre": $("#txtNombre").val(),
		"competenciaConductual": []
  }
	$("#ulCompetenciaConductual").find("input[type='checkbox']:checked").each(function () {
		datos.competenciaConductual.push($(this).attr("id"))
	});
	return datos;
}

function indicadorLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='indicadorEditar(this)'><td>" + this.id + "</td><td>" + this.nombre + "</td></tr>");
	});
}

function indicadorLimpiar(indicadorTipo) {
	$("#txtID").val("");
	if(indicadorTipo) {
		indicadorCompetenciaConductualMostrar();
		$("#txtNombre").val("");
		$("#txtNombre").focus();
	}
}

function indicadorAgregar() {
	indicadorLimpiar(true);
}

function indicadorListar() {
	ajaxGet(urlApi + "/indicador", indicadorLlenaTabla);
}

function indicadorEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtNombre").val($(oFila).find("td")[1].innerHTML);
	indicadorCompetenciaConductualMostrar();
}

function indicadorRegistrar() {
	var datos = indicadorSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/indicador/" + datos.id, datos, indicadorListar);
	} else {
		ajaxPost(urlApi + "/indicador", datos, indicadorListar);
	}
	indicadorAgregar();
}

function indicadorEliminar() {
	var datos = indicadorSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/indicador/" + datos.id, indicadorListar);
		indicadorAgregar();
	}
}

function indicadorCargar() {
	$("#divContenido").load("indicador.html", function() {
		indicadorListar();
		indicadorLimpiar(true);
		$(".titulo").html("Indicador");
		$("nav.desplegable").css("left", "-200px");
	});
}

/* CompetenciaConductual */

 function indicadorCompetenciaConductualMostrar() { // Solicita a la API las competenciaConductuals (todas)
	ajaxGet(urlApi + "/competenciaConductual", indicadorCompetenciaConductualDesplegar);
}

function indicadorCompetenciaConductualDesplegar(oData) { // Despliega lAs competenciaConductuals solicitadas a la API
	$("#ulCompetenciaConductual li").remove();
	$(oData).each(function() {
		$("#ulCompetenciaConductual").append("<li><input type='checkbox' id='" + this.id + "'>&nbsp;" + this.nombre + " </li>");
	});
	if($("#txtID").val() != "") {
		ajaxGet(urlApi + "/competenciaConductual/indicador/" + $("#txtID").val(), indicadorCompetenciaConductualDesplegarAsignadas);
	}
}

function indicadorCompetenciaConductualDesplegarAsignadas(oData) {
	$(oData).each(function() {
		id = $(this)[0].competenciaConductual_id;
		$("#ulCompetenciaConductual li").each(function() {
			if($(this).find("input")[0].id == id) {
				$(this).find("input")[0].checked = true;
			}
		});
	});
}
