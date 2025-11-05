# 4VGym - Sistema de Gestión de Actividades  
  
Aplicación web para la gestión de actividades de gimnasio desarrollada en PHP con arquitectura MVC.  
  
## Características  
  
- **Gestión de Actividades**: Crear, editar, listar y eliminar actividades de gimnasio  
- **Tipos de Actividades**: Spinning, BodyPump y Pilates  
- **Validación de Datos**: Validación de formularios con mensajes de error específicos  
- **Filtrado por Fecha**: Búsqueda de actividades por fecha  
- **Interfaz Responsive**: Diseño con Bootstrap 5  
  
## Requisitos del Sistema  
  
- PHP 7.4 o superior  
- MySQL/MariaDB  
- Servidor web (Apache/Nginx)  
- Extensión PDO de PHP  
  
## Instalación  
  
1. **Clonar el repositorio**  
   ```bash  
   git clone https://github.com/SrAlvarado/Examen-1Ev-DW-PHP.git  
   cd Examen-1Ev-DW-PHP/dw_01_Eval_4VGym
Configurar la base de datos

Crear una base de datos llamada 4vgym
Importar el esquema SQL (si está disponible)
Configurar credenciales en persistence/conf/PersistentManager.php
Configuración de la base de datos

DB_HOST = 'localhost'  
DB_NAME = '4vgym'  
DB_USER = 'root'  
DB_PASS = ''
Iniciar el servidor

php -S localhost:8000
Acceder a la aplicación

Abrir navegador en http://localhost:8000/listActivities.php
Estructura del Proyecto
dw_01_Eval_4VGym/  
├── app/  
│   ├── header.php          # Cabecera común  
│   └── footer.php          # Pie de página común  
├── assets/  
│   └── img/                # Imágenes de actividades  
├── model/  
│   └── Activity.php        # Modelo de datos  
├── persistence/  
│   ├── conf/  
│   │   └── PersistentManager.php  # Gestión de conexión PDO  
│   └── DAO/  
│       └── ActivityDAO.php # Capa de acceso a datos  
├── utils/  
│   ├── SessionHelper.php   # Gestión de sesiones  
│   └── validation_functions.php  # Validaciones  
├── createActivity.php      # Crear actividad  
├── editActivity.php        # Editar actividad  
└── listActivities.php      # Listar actividades  
# Resumen Funcional y Arquitectónico del Proyecto 4VGym

## 1. Funcionalidades Principales

### Crear Actividad (`createActivity.php`)
* Formulario con **validación de campos obligatorios**.
* **Validación de fecha futura** (respecto a la hora actual en `Europe/Madrid`).
* Tipos permitidos: `Spinning`, `BodyPump`, `Pilates`.

### Editar Actividad (`editActivity.php`)
* **Carga de datos existentes** mediante ID (`ActivityDAO::findById()`).
* **Validación de ID de actividad** para evitar accesos no autorizados/errores.
* **Preservación de datos** en el formulario en caso de error de validación.

### Listar Actividades (`listActivities.php`)
* Vista en **tarjetas** con *grid* responsivo de **Bootstrap 5**.
* **Filtro por fecha** (`YYYY-MM-DD`).
* **Confirmación JavaScript** para la eliminación de registros.
* Implementación del patrón **Post-Redirect-Get (PRG)**.

---

## 2. Esquema de Validaciones y Seguridad

El sistema implementa estrictas validaciones a nivel de servidor:

### Validaciones de Negocio y Estructura
* **Campos obligatorios:** Tipo, Monitor, Lugar, Fecha.
* **Tipo de actividad:** Solo valores permitidos (`Spinning`, `BodyPump`, `Pilates`).
* **Fecha:** Debe ser **futura** respecto a la hora actual en `Europe/Madrid` (Lógica de negocio).
* **Formato de fecha:** Validación interna mediante la clase `DateTime`.

### Seguridad (Anti-Inyección y XSS)
| Mecanismo | Función / Técnica | Propósito |
| :--- | :--- | :--- |
| **Prevención SQLi** | **PDO** con **prepared statements** (`?` marcadores) | Separa datos de comandos SQL. |
| **Sanitización de Entrada** | `trim()` | Limpieza de espacios en blanco al recibir `$_POST`. |
| **Escape de Salida** | `htmlspecialchars()` | Previene **XSS** al escapar datos antes de renderizar HTML. |
| **Control de Dominio** | Validación contra *whitelist* | Asegura que el `type` sea un valor conocido. |

### Manejo de Errores y UX
* **Errores de validación:** Se muestran de forma **inline** a nivel de campo.
* **Mensajes generales:** Para fallos de base de datos (`ActivityDAO` devuelve `false`).
* **Preservación de datos:** Los valores del formulario se mantienen (`$form_data`) en caso de error.
* **Redirecciones:** Uso de *query parameters* para comunicar el estado de éxito/error después del PRG.

---

## 3. Tecnologías y Arquitectura

* **Backend:** PHP 7.4+
* **Base de datos:** MySQL con capa de acceso a datos **PDO**.
* **Frontend:** HTML5, Bootstrap 5 (CDN).
* **Arquitectura:** **MVC** con enfoque en el **patrón DAO** (Data Access Object).

## 1. Autor
SrAlvarado

  
# Para Mas Información sobre el Proyecto:
[![Ask DeepWiki](https://deepwiki.com/badge.svg)](https://deepwiki.com/SrAlvarado/Examen-1Ev-DW-PHP)

