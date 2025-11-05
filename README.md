[![Ask DeepWiki](https://deepwiki.com/badge.svg)](https://deepwiki.com/SrAlvarado/Examen-1Ev-DW-PHP)
# 🏋️‍♂️ 4VGym: Gestión de Actividades (Examen 1ª Evaluación DW)

Este proyecto implementa una aplicación web básica para la gestión de actividades de un gimnasio (**4VGym**), cumpliendo con los requisitos de arquitectura de capas, patrón DAO y seguridad (PDO) establecidos para el examen práctico de **Desarrollo Web en Entorno Servidor (2DAM)**.

**Autor:** Markel Alvarado
**Materia:** DWES (Desarrollo Web en Entorno Servidor)
**Arquitectura:** PHP Nativo con Patrón DAO (Data Access Object)

---

## 🚀 1. Funcionalidades Implementadas

El proyecto cubre todos los puntos funcionales requeridos por el examen, implementando las operaciones CRUD y la lógica de negocio:

* **Listado (Punto 3):** Muestra todas las actividades programadas.
* **Filtro por Fecha (Punto 7):** Permite filtrar las actividades por un día específico (sin la hora).
* **Creación (Punto 4):** Formulario para añadir nuevas actividades con validaciones estrictas (campos obligatorios, tipos válidos, fecha futura).
* **Edición (Punto 6):** Formulario para modificar una actividad existente, reutilizando la lógica de validación de la creación.
* **Borrado (Punto 5):** Permite eliminar una actividad desde el listado, validando la existencia previa del ID.
* **Redirección/Sesión (Punto 2):** El `index.php` redirige al usuario a la última página visitada (`listActivities.php` o `createActivity.php`) usando el `SessionHelper`.

---

## 🏗️ 2. Arquitectura de Proyecto (Capas)

El proyecto sigue una arquitectura de tres capas bien definidas, utilizando el patrón DAO para la capa de persistencia:

dw_01_Eval_4VGym/ ├── app/ <-- Vistas parciales (Header/Footer) ├── assets/ <-- Archivos estáticos (CSS, JS, Imágenes de tipos de actividad) ├── model/ │ └── Activity.php <-- [DTO/Entidad] Objeto de transferencia de datos. ├── persistence/ <-- CAPA DE PERSISTENCIA │ ├── conf/ │ │ └── PersistentManager.php <-- Conexión PDO (Versión mínima requerida). │ ├── DAO/ │ │ └── ActivityDAO.php <-- [DAO] Contiene toda la lógica SQL (CRUD + Filtro). │ └── scripts/ │ └── bbdd_actividades.sql <-- Script de creación de la BBDD. ├── utils/ │ ├── SessionHelper.php <-- Utilidad para la gestión de la sesión y redirección (Punto 2). │ └── validation_functions.php <-- Funciones de validación de formulario (Clean Code, Punto 4/6). ├── createActivity.php <-- [Front-Controller] Maneja POST/GET y validación de creación. ├── editActivity.php <-- [Front-Controller] Maneja GET/POST y validación de edición. ├── index.php <-- [Front-Controller] Punto de entrada y gestión de redirección. └── listActivities.php <-- [Front-Controller] Listado, Filtro y Lógica de Borrado.


---

## 🛠️ 3. Configuración e Instalación

Para levantar y probar la aplicación, sigue los siguientes pasos:

### 3.1. Base de Datos (BBDD)

1.  Abre tu cliente de MySQL/MariaDB (ej. phpMyAdmin, HeidiSQL, o la consola).
2.  Ejecuta el script SQL ubicado en `persistence/scripts/bbdd_actividades.sql` para crear la base de datos `4vgym` y la tabla `activities` con datos iniciales.

### 3.2. Configuración de Conexión

1.  Abre el archivo `persistence/conf/PersistentManager.php`.
2.  Asegúrate de que las constantes `DB_USER` y `DB_PASS` coincidan con tus credenciales locales de MySQL (por defecto, `root` y contraseña vacía).

```php
private const DB_HOST = 'localhost';
private const DB_NAME = '4vgym';
private const DB_USER = 'root'; // CAMBIAR si es necesario
private const DB_PASS = '';     // CAMBIAR si es necesario
3.3. Ejecución
Copia la carpeta principal del proyecto (dw_01_Eval_4VGym) en tu directorio de servidor web (ej. C:\xampp\htdocs\ o var/www/html).

Accede a la aplicación a través de tu navegador: http://localhost/[ruta_a_tu_proyecto]/dw_01_Eval_4VGym/index.php

🛡️ 4. Principios de Código Clave
Seguridad: Uso estricto de Sentencias Preparadas (PDO) en todo el ActivityDAO para prevenir ataques de Inyección SQL.

Clean Code: Extracción de la lógica de validación a funciones dedicadas (validation_functions.php) para evitar la duplicación de código en los Front-Controllers de creación y edición.
