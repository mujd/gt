function competenciaConductualSerializar() {
    var datos = {
        "id": $("#txtID").val(),
        "nombre": $("#txtNombre").val(),
        "definicion": $("#txtDefinicion").val(),
        "indicadores": []
    }
    $("#ulIndicador li").each(function () {
        datos.indicadores.push($(this).html());
    });
    return datos;
}

function competenciaConductualLlenaTabla(oData) {
	$("#tabData tr.item").remove();
	$(oData).each(function() {
		$("#tabData").append("<tr class='item' style='cursor:pointer' onclick='competenciaConductualEditar(this)'><td>" + this.id + "</td><td>" + this.nombre + "</td><td>" + this.definicion + "</td></tr>");
	});
}

function competenciaConductualLimpiar(competenciaConductualTipo) {
	$("#txtDefinicion").val("");
	$("#txtID").val("");
    $("#ulIndicador li").remove();
    $("#txtIndicadorID").val("");
    $("#txtIndicador").val("");
	if(competenciaConductualTipo) {
		$("#txtNombre").val("");
		$("#txtNombre").focus();
	}
}

function competenciaConductualAgregar() {
	competenciaConductualLimpiar(true);
}

function competenciaConductualListar() {
	ajaxGet(urlApi + "/competenciaConductual", competenciaConductualLlenaTabla);
}

function competenciaConductualEditar(oFila) {
	$("#txtID").val($(oFila).find("td")[0].innerHTML);
	$("#txtNombre").val($(oFila).find("td")[1].innerHTML);
	$("#txtDefinicion").val($(oFila).find("td")[2].innerHTML);
	competenciaConductualIndicadorMostrar();
}

function competenciaConductualRegistrar() {
	var datos = competenciaConductualSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxPut(urlApi + "/competenciaConductual/" + datos.id, datos, competenciaConductualListar);
	} else {
		ajaxPost(urlApi + "/competenciaConductual", datos, competenciaConductualListar);
	}
	competenciaConductualAgregar();
}

function competenciaConductualEliminar() {
	var datos = competenciaConductualSerializar();
	if(parseInt(datos.id) > 0) {
		ajaxDelete(urlApi + "/competenciaConductual/" + datos.id, competenciaConductualListar);
		competenciaConductualAgregar();
	}
}

function competenciaConductualCargar() {
    $("#divContenido").load("competenciaConductual.html", function() {
      competenciaConductualListar();
		  competenciaConductualLimpiar(true);
		  $(".titulo").html("Competencia Conductual");
		  $("nav.desplegable").css("left", "-250px");
      $("#btnIndicadorRegistra").click(function(){ competenciaConductualAgregarIndicador() });
      $("#btnIndicadorElimina").click(function(){ competenciaConductualEliminarIndicador() });

	});
}

/* Indicador */

function competenciaConductualAgregarIndicador() {
    if($("#txtIndicador").val() != "") {
        competenciaConductualIndicadorDesplegarItem($("#txtIndicadorID").val(), $("#txtIndicador").val());
        competenciaConductualIndicadorClick();
    }
}

function competenciaConductualEliminarIndicador() {
    $("#ulIndicador li").each(function() {
        if($(this).find("input")[0].value == $("#txtIndicadorID").val()) {
            $(this).remove();
        }
    });
    competenciaConductualIndicadorClick();
}

function competenciaConductualIndicadorMostrar() { // Solicita a la API los Indicadores (todos)
	ajaxGet(urlApi + "/indicador/competenciaConductual/" + $("#txtID").val(), competenciaConductualIndicadorDesplegar);
}

function competenciaConductualIndicadorDesplegar(oData) { // Despliega los Indicadores solicitados a la API
    var nCount = 1;
    $("#ulIndicador li").remove();
	$(oData).each(function() {
        competenciaConductualIndicadorDesplegarItem(nCount, this.nombre);
        nCount++;
    });
}

function competenciaConductualIndicadorDesplegarItem(item, nombre) {
    var bEnc = false;
    if(item == "") {
        item = parseInt($("#ulIndicador li").length) + 1;
    }
    $("#ulIndicador li").each(function(){
        if($(this).find("input")[0].value == item) {
            $(this).html("<input type='hidden' value='" + item + "'>" + nombre + " </li>");
            bEnc = true;
        }
    });
    if(!bEnc) {
        $("#ulIndicador").append("<li><input type='hidden' value='" + item + "'>" + nombre + " </li>");
    }
    competenciaConductualIndicadorClick();
}

function competenciaConductualIndicadorClick() {
    $("#ulIndicador li").unbind("click");
    $("#ulIndicador li").click(function() {
        competenciaConductualIndicadorMostrarItem($(this));
    });
    $("#txtIndicadorID").val("");
    $("#txtIndicador").val("");
    $("#txtIndicador").focus();
}

function competenciaConductualIndicadorMostrarItem(obj) {
    $("#txtIndicadorID").val(obj.find("input")[0].value);
    $("#txtIndicador").val(obj.text());
}
