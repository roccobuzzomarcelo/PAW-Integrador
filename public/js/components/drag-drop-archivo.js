class DragAndDropArchivo {
	constructor(dropzoneSelector, inputSelector, previewSelector) {
		this.dropzone = document.querySelector(dropzoneSelector);
		this.fileInput = document.querySelector(inputSelector);
		this.preview = document.querySelector(previewSelector);
		this.botonSeleccionar = document.querySelector("#botonSeleccionarArchivo");

		if (this.dropzone && this.fileInput && this.preview) {
			this.init();
		}
	}

	init() {
		this.dropzone.addEventListener("dragover", (e) => {
			e.preventDefault();
			this.dropzone.classList.add("dragover");
		});

		this.dropzone.addEventListener("dragleave", () => {
			this.dropzone.classList.remove("dragover");
		});

		this.dropzone.addEventListener("drop", (e) => {
			e.preventDefault();
			this.dropzone.classList.remove("dragover");

			const file = e.dataTransfer.files[0];
			if (file) {
				this.fileInput.files = e.dataTransfer.files;
				this.mostrarInfoArchivo(file);
			}
		});

		this.dropzone.addEventListener("click", () => {
			this.fileInput.click();
		});

		this.botonSeleccionar?.addEventListener("click", () => {
			this.fileInput.click();
		});

		this.fileInput.addEventListener("change", () => {
			if (this.fileInput.files.length > 0) {
				const file = this.fileInput.files[0];
				this.mostrarInfoArchivo(file);
			}
		});
	}

	mostrarInfoArchivo(file) {
		const icono = this.obtenerIcono(file.type);
		this.preview.innerHTML = `${icono} <strong>${file.name}</strong>`;
	}

	obtenerIcono(type) {
		if (type.startsWith("image/")) return "🖼️";
		if (type.startsWith("audio/")) return "🎵";
		if (type === "application/pdf") return "📄";
		return "📁";
	}
}

document.addEventListener("DOMContentLoaded", () => {
	new DragAndDropArchivo("#dropzone", "#archivo", "#preview");
});
