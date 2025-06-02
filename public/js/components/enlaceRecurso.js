class EnlaceRecurso {
	constructor(checkboxSelector, zonaEnlaceSelector, dropzoneSelector, botonSelector, inputArchivoSelector, inputEnlaceSelector, previewSelector) {
		this.checkbox = document.querySelector(checkboxSelector);
		this.zonaEnlace = document.querySelector(zonaEnlaceSelector);
		this.dropzone = document.querySelector(dropzoneSelector);
		this.botonSeleccionar = document.querySelector(botonSelector);
		this.inputArchivo = document.querySelector(inputArchivoSelector);
		this.inputEnlace = document.querySelector(inputEnlaceSelector);
		this.preview = document.querySelector(previewSelector);

		if (this.checkbox) {
			this.init();
		}
	}

	init() {
		this.checkbox.addEventListener("change", () => {
			const usandoEnlace = this.checkbox.checked;

			// Mostrar/ocultar zona de enlace
			if (this.zonaEnlace) this.zonaEnlace.style.display = usandoEnlace ? "block" : "none";

			// Mostrar/ocultar zona de archivo
			if (this.dropzone) this.dropzone.style.display = usandoEnlace ? "none" : "block";
			if (this.botonSeleccionar) this.botonSeleccionar.style.display = usandoEnlace ? "none" : "inline-block";
			if (this.preview) this.preview.style.display = usandoEnlace ? "none" : "block";

			// Limpiar campos
			if (this.inputArchivo && usandoEnlace) this.inputArchivo.value = "";
			if (this.inputEnlace && !usandoEnlace) this.inputEnlace.value = "";
		});
	}
}

document.addEventListener("DOMContentLoaded", () => {
  new EnlaceRecurso(
    "#usarEnlace","#zonaEnlace", "#dropzone", "#botonSeleccionarArchivo", "#recursoArchivo", 
    "#recursoLink", "#preview"
  );
});