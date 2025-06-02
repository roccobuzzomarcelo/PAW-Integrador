class appPAW {
	constructor() {
		document.addEventListener("DOMContentLoaded", () => {
			PAW.cargarScript("DragDropArchivo", "js/components/drag-drop-archivo.js", () => {
				new DragAndDropArchivo("#dropzone", "#recursoArchivo", "#preview");
			});
			PAW.cargarScript("EnlaceRecurso", "js/components/enlaceRecurso.js", () => {
				 new EnlaceRecurso(
					"#usarEnlace","#zonaEnlace", "#dropzone", "#botonSeleccionarArchivo", "#recursoArchivo", 
					"#recursoLink", "#preview"
				);
			})
		});
	}
}

let app = new appPAW();


