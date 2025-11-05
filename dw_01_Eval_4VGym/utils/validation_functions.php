<?php

function validar_datos_formulario_de_actividad(array $datos_actividad): array {
    $errores = [];
    $tipos_permitidos = ['Spinning', 'BodyPump', 'Pilates'];

    if (empty($datos_actividad['type'])) {
        $errores['type'] = 'El Tipo de actividad es obligatorio.';
    }
    if (empty($datos_actividad['monitor'])) {
        $errores['monitor'] = 'El nombre del Monitor es obligatorio.';
    }
    if (empty($datos_actividad['place'])) {
        $errores['place'] = 'El Lugar es obligatorio.';
    }
    if (empty($datos_actividad['date'])) {
        $errores['date'] = 'La Fecha y hora son obligatorias.';
    }

    if (!empty($errores)) {
        return $errores;
    }

    // 2. Validación de Tipo de Actividad
    if (!in_array($datos_actividad['type'], $tipos_permitidos)) {
        $errores['type'] = 'Tipo de actividad no válido. Debe ser Spinning, BodyPump o Pilates.';
    }

    try {
        // Usamos la hora actual de Pamplona, España (CET) como referencia
        $zona_horaria = new DateTimeZone('Europe/Madrid');
        $fecha_actividad = new DateTime($datos_actividad['date'], $zona_horaria);
        $fecha_actual = new DateTime('now', $zona_horaria);

        if ($fecha_actividad <= $fecha_actual) {
            $errores['date'] = 'La actividad debe ser programada para una fecha y hora FUTURA.';
        }
    } catch (Exception $e) {
        $errores['date'] = 'Formato de fecha y hora inválido.';
    }

    return $errores;
}