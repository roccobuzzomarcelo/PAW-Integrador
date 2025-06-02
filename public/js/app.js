class appPAW {
	constructor() {
		document.addEventListener("DOMContentLoaded", () => {
			PAW.cargarScript("DragDropArchivo", "js/components/drag-drop-archivo.js", () => {
				new DragAndDropArchivo("#dropzone", "#archivo", "#preview");
			});
		});
	}
}

let app = new appPAW();


