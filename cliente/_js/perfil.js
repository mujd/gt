function perfilSerializar() {
	var datos = {
		"id": $("#txtID").val(),
		"nombre": $("#txtNombre").val(),
		"objetivo": $("#txtObjetivo").val(),
		"reporta": $("#txtReporta").val(),
		"tareas": $("#txtTareas").val(),
		"ucls": [],
		"competencias": []
	}
	$("#ulUcl").find("input[type='checkbox']:checked").each(function () {
		datos.ucls.push($(this).attr("id"))
	});
	$("#ulCompetencia").find("input[type='checkbox']:checked").each(function () {
		datos.competencias.push($(this).attr("id"))
	});
	return datos;
}

function perfilLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='perfilEditar(this)'><td>" + this.id + "</td><td>" + this.nombre + "</td><td>" + this.objetivo + "</td><td>" + this.reporta + "</td><td>" + this.tareas + "</td></tr>");
	});
}

function perfilLimpiar(tipo) {
	$("#txtObjetivo").val("");
	$("#txtReporta").val("");
	$("#txtTareas").val("");
	$("#txtID").val("");
	if(tipo) {
		perfilUclMostrar();
		perfilCompetenciaConductualMostrar();
		$("#txtNombre").val("");
		$("#txtNombre").focus();
	}
}

function perfilAgregar() {
	perfilLimpiar(true);
}

function perfilListar() {
	ajaxGet(urlApi + "/perfil", perfilLlenaTabla);
}

function perfilEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtNombre").val($(oFila).find("td")[1].innerHTML);
  $("#txtObjetivo").val($(oFila).find("td")[2].innerHTML);
	$("#txtReporta").val($(oFila).find("td")[3].innerHTML);
	$("#txtTareas").val($(oFila).find("td")[4].innerHTML);
	perfilUclMostrar();
	perfilCompetenciaConductualMostrar();
}

function perfilRegistrar() {
	var datos = perfilSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/perfil/" + datos.id, datos, perfilListar);
	} else {
		ajaxPost(urlApi + "/perfil", datos, perfilListar);
	}
	perfilAgregar();
}

function perfilEliminar() {
	var datos = perfilSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/perfil/" + datos.id, perfilListar);
		perfilAgregar();
	}
}

function perfilCargar() {
	$("#divContenido").load("perfil.html", function() {
		perfilListar();
		perfilLimpiar(true);
		$(".titulo").html("Perfil");
		$("nav.desplegable").css("left", "-250px");
	});
}

/* Ucl */

 function perfilUclMostrar() { // Solicita a la API las ucls (todas)
	ajaxGet(urlApi + "/ucl", perfilUclDesplegar);
}

function perfilUclDesplegar(oData) { // Despliega lAs ucls solicitadas a la API
	$("#ulUcl li").remove();
	$(oData).each(function() {
		$("#ulUcl").append("<li><input type='checkbox' id='" + this.id + "'>&nbsp;" + this.nombre + " </li>");
	});
	if($("#txtID").val() != "") {
		ajaxGet(urlApi + "/ucl/perfil/" + $("#txtID").val(), perfilUclDesplegarAsignadas);
	}
}

function perfilUclDesplegarAsignadas(oData) {
	$(oData).each(function() {
		id = $(this)[0].ucl_id;
		$("#ulUcl li").each(function() {
			if($(this).find("input")[0].id == id) {
				$(this).find("input")[0].checked = true;
			}
		});
	});
}

/* Competencia Conductual */

 function perfilCompetenciaConductualMostrar() { // Solicita a la API las competencias conductuales (todas)
	ajaxGet(urlApi + "/competenciaConductual", perfilCompetenciaConductualDesplegar);
}

function perfilCompetenciaConductualDesplegar(oData) { // Despliega lAs competencias conductuales solicitadas a la API
	$("#ulCompetencia li").remove();
	$(oData).each(function() {
		$("#ulCompetencia").append("<li><input type='checkbox' id='" + this.id + "'>&nbsp;" + this.nombre + " </li>");
	});
	if($("#txtID").val() != "") {
		ajaxGet(urlApi + "/competenciaConductual/perfil/" + $("#txtID").val(), perfilCompetenciaConductualDesplegarAsignadas);
	}
}

function perfilCompetenciaConductualDesplegarAsignadas(oData) {
	$(oData).each(function() {
		id = $(this)[0].competencia_conductual_id;
		$("#ulCompetencia li").each(function() {
			if($(this).find("input")[0].id == id) {
				$(this).find("input")[0].checked = true;
			}
		});
	});
}
