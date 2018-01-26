/**
 * Esta es la que obtiene la estructura del formulario del respectivo archivo
 * estructura.js
 */
Formulario = function Formulario(idFormulario, idVersion) {
    this.idFormulario = idFormulario;
    this.idVersion = idVersion;
}

Formulario.prototype.getIdFormulario = function() {
	return this.idFormulario;
}

Formulario.prototype.getVersion = function() {
	return this.idVersion;
}

//Aqui va el agregar hojas y el borrar hoja y el cargar hojas, este ultimo es privado

