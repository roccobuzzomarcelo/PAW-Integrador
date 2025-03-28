# TP Integrador - Entrega 1

## Programación en Ambiente Web

### **Integrantes**

- Buzzo Marcelo, Rocco   |   Legajo: 190292
- Cardona, Eliana        |   Legajo: 118441
- Pereyra Buch, Bautista |   Legajo 193177

## Plataforma de Aprendizaje Dinámico

### **Descripción General**

La plataforma permitirá a los usuarios acceder a cursos estructurados en módulos, con ejercicios diseñados y evaluados en función de respuestas previamente cargadas por el instructor. La administración de los cursos será manual, asegurando calidad en el contenido y la evaluación.

La inteligencia artificial se empleará como herramienta de apoyo, sugiriendo materiales adicionales al finalizar un módulo. Estos recursos pueden incluir enlaces a páginas web, videos explicativos y, eventualmente, ejercicios adicionales.

### **Características principales**

1. **Estructura modular de cursos:**
    - Se ofrecerán 3 o 4 cursos, cada uno con 5 o 6 módulos.
    - Cada módulo contendrá teoría y ejercicios prácticos.
2. **Evaluación de ejercicios:**
    - Corrección automática en base a respuestas predefinidas por el instructor.
3. **Sugerencias de contenido con IA:**
    - Recomendaciones automáticas de videos, artículos y recursos tras completar un módulo.
4. **Foro de discusión (en evaluación):**
    - Posible implementación de un espacio de interacción entre usuarios.
5. **Tecnologías a utilizar:**
    - Frontend: HTML5, CSS, JavaScript.
    - Backend: PHP.
    - Base de datos: A definir (posiblemente MySQL o PostgreSQL).

---

## **Presupuesto Funcional**

### **1. Autenticación y Gestión de Usuarios**

- Registro de usuarios (estudiantes e instructores).
- Inicio de sesión y recuperación de contraseña.  
- Gestión de perfil y roles de usuario.  

### **2. Gestión de Cursos y Módulos**

- Creación, edición y eliminación de cursos.
- Inscripción y seguimiento del progreso del usuario.  

### **3. Evaluaciones y Corrección de Ejercicios**

- Creación y evaluación automática de ejercicios.
- Registro del desempeño del usuario.  

### **4. IA para Sugerencia de Contenidos**  

- Generación de enlaces a videos y artículos relevantes.  

### **5. Panel de Usuario**

- Visualización de cursos inscritos y progreso.  
- Historial de evaluaciones y resultados.  

### **6. Foro de Discusión (Opcional)**

- Creación y moderación de temas de discusión.  

### **7. Administración del Sistema (Instructores/Admins)**  

- Administración de cursos, usuarios y evaluaciones.  

#### **Infraestructura necesaria**
- **Hosting web:** Un servidor que soporte PHP y la base de datos elegida.
- **Dominio web:** URL para acceder a la plataforma.
- **Almacenamiento y ancho de banda.**.

#### **Recursos humanos**
- **Desarrolladores:** Responsables de frontend y backend.
- **Instructores o administradores:** Encargados de la carga y gestión de contenido.

---

## **Presupuesto Temporal**

| **Fase**                     | **Duración**  | **Tareas Claves**  |
|------------------------------|--------------|--------------------|
| **Análisis y planificación** | 1 semana     | Definir requerimientos, diseñar arquitectura, elegir tecnologías, asignar tareas. |
| **Diseño de la plataforma**  | 2 semanas    | Crear wireframes, diseñar UI/UX, definir estructura de la base de datos. |
| **Desarrollo del backend**   | 3 semanas    | Implementar autenticación, gestión de cursos y módulos, evaluación de ejercicios. |
| **Desarrollo del frontend**  | 3 semanas    | Implementar interfaz de usuario y vistas de cursos. |
| **Integración de IA**        | 1 semana     | Implementar sugerencias de contenido con IA. |
| **Pruebas y correcciones**   | 1 semana     | Realizar pruebas funcionales y corregir errores. |
| **Despliegue y documentación** | 1 semana  | Configurar servidor, desplegar la plataforma, documentar el sistema. |

---

## **Sitemap**

![alt text](../sitemap.png)
