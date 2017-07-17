function uclSerializar() {
    var datos = {
        "id": $("#txtID").val(),
        "nombre": $("#txtNombre").val(),
        "definicion": $("#txtDefinicion").val(),
        "actividadesClave": []
    }
    $("#ulActividadClave li").each(function () {
        datos.actividadesClave.push($(this).html());
    });
    return datos;
}

function uclLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='uclEditar(this)'><td>" + this.id + "</td><td>" + this.nombre + "</td><td>" + this.definicion + "</td></tr>");
	});
}

function uclLimpiar(uclTipo) {
	$("#txtDefinicion").val("");
	$("#txtID").val("");
    $("#ulActividadClave li").remove();
    $("#txtActividadID").val("");
    $("#txtActividad").val("");
    $("#ulCriterio li").remove();
    $("#txtCriterioID").val("");
    $("#txtCriterio").val("");
	if(uclTipo) {
		$("#txtNombre").val("");
		$("#txtNombre").focus();
	}
}

function uclAgregar() {
	uclLimpiar(true);
}

function uclListar() {
	ajaxGet(urlApi + "/ucl", uclLlenaTabla);
}

function uclEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtNombre").val($(oFila).find("td")[1].innerHTML);
	$("#txtDefinicion").val($(oFila).find("td")[2].innerHTML);
	uclActividadClaveMostrar();
}

function uclRegistrar() {
	var datos = uclSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/ucl/" + datos.id, datos, uclListar);
	} else {
		ajaxPost(urlApi + "/ucl", datos, uclListar);
	}
	uclAgregar();
}

function uclEliminar() {
	var datos = uclSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/ucl/" + datos.id, uclListar);
		uclAgregar();
	}
}

function uclCargar() {
    $("#divContenido").load("ucl.html", function() {

        uclListar();
		uclLimpiar(true);
		$(".titulo").html("UCL");
		$("nav.desplegable").css("left", "-250px");

        $("#btnActividadRegistra").click(function(){ uclAgregarActividadClave() });
        $("#btnActividadElimina").click(function(){ uclEliminarActividadClave() });

        $("#btnCriterioRegistra").click(function(){ uclAgregarCriterio() });
        $("#btnCriterioElimina").click(function(){ uclEliminarCriterio() });

	});
}

/* Actividad Clave */

function uclAgregarActividadClave() {
    if($("#txtActividad").val() != "") {
        uclActividadClaveDesplegarItem($("#txtActividadID").val(), $("#txtActividad").val());
        uclActividadClaveClick();
    }
}

function uclEliminarActividadClave() {
    $("#ulActividadClave li").each(function() {
        if($(this).find("input")[0].value == $("#txtActividadID").val()) {
            $(this).remove();
        }
    });
    uclActividadClaveClick();
}

function uclActividadClaveMostrar() { // Solicita a la API las actividades clave (todos)
	ajaxGet(urlApi + "/actividadClave/ucl/" + $("#txtID").val(), uclActividadClaveDesplegar);
}

function uclActividadClaveDesplegar(oData) { // Despliega las actividades clave solicitadas a la API
    var nCount = 1;
    $("#ulActividadClave li").remove();
	$(oData).each(function() {
        uclActividadClaveDesplegarItem(nCount, this.nombre);
        nCount++;
    });
}

function uclActividadClaveDesplegarItem(item, nombre) {
    var bEnc = false;
    if(item == "") {
        item = parseInt($("#ulActividadClave li").length) + 1;
    }
    $("#ulActividadClave li").each(function(){
        if($(this).find("input")[0].value == item) {
            $(this).html("<input type='hidden' value='" + item + "'>" + nombre + " </li>");
            bEnc = true;
        }
    });
    if(!bEnc) {
        $("#ulActividadClave").append("<li><input type='hidden' value='" + item + "'>" + nombre + " </li>");
    }
    uclActividadClaveClick();
}

function uclActividadClaveClick() {
    $("#ulActividadClave li").unbind("click");
    $("#ulActividadClave li").click(function() {
        uclActividadClaveMostrarItem($(this));
    });
    $("#txtActividadID").val("");
    $("#txtActividad").val("");
    $("#txtActividad").focus();
}

function uclActividadClaveMostrarItem(obj) {
    $("#txtActividadID").val(obj.find("input")[0].value);
    $("#txtActividad").val(obj.text());
}

/* Criterio */

function uclAgregarCriterio() {
    if($("#txtCriterio").val() != "") {
        uclCriterioDesplegarItem($("#txtCriterioID").val(), $("#txtCriterio").val());
        uclCriterioClick();
    }
}

function uclEliminarCriterio() {
    $("#ulCriterio li").each(function() {
        if($(this).find("input")[0].value == $("#txtCriterioID").val()) {
            $(this).remove();
        }
    });
    uclCriterioClick();
}

function uclCriterioMostrar() { // Solicita a la API los criterios (todos)
	ajaxGet(urlApi + "/criterio/ucl/" + $("#txtID").val(), uclCriterioDesplegar);
}

function uclCriterioDesplegar(oData) { // Despliega los criterios solicitados a la API
    var nCount = 1;
    $("#ulCriterio li").remove();
	$(oData).each(function() {
        uclCriterioDesplegarItem(nCount, this.nombre);
        nCount++;
    });
}

function uclCriterioDesplegarItem(item, nombre) {
    var bEnc = false;
    if(item == "") {
        item = parseInt($("#ulCriterio li").length) + 1;
    }
    $("#ulCriterio li").each(function(){
        if($(this).find("input")[0].value == item) {
            $(this).html("<input type='hidden' value='" + item + "'>" + nombre + " </li>");
            bEnc = true;
        }
    });
    if(!bEnc) {
        $("#ulCriterio").append("<li><input type='hidden' value='" + item + "'>" + nombre + " </li>");
    }
    uclCriterioClick();
}

function uclCriterioClick() {
    $("#ulCriterio li").unbind("click");
    $("#ulCriterio li").click(function() {
        uclCriterioMostrarItem($(this));
    });
    $("#txtCriterioID").val("");
    $("#txtCriterio").val("");
    $("#txtCriterio").focus();
}

function uclCriterioMostrarItem(obj) {
    $("#txtCriterioID").val(obj.find("input")[0].value);
    $("#txtCriterio").val(obj.text());
}
