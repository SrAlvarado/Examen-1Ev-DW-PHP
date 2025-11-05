<?php

require_once __DIR__ . '/../conf/PersistentManager.php';
require_once __DIR__ . '/../../model/Activity.php';

class ActivityDAO {

    private PDO $conexion;

    public function __construct() {
        $this->conexion = PersistentManager::getConexion();
    }

    public function create(Activity $activity): int{
        $sql = "INSERT INTO activities (type, monitor, place, date) 
                VALUES (?, ?, ?, ?)";

        $sentencia = $this->conexion->prepare($sql);

        $exito = $sentencia->execute([
            $activity->type,
            $activity->monitor,
            $activity->place,
            $activity->date
        ]);

        if ($exito) {
            return (int)$this->conexion->lastInsertId();
        }
        return false;
    }

    public function findAll(?string $filterDate = null): array {
        $sql = "SELECT id, type, monitor, place, date FROM activities";
        $params = [];

        if ($filterDate) {
            $sql .= " WHERE DATE(date) = DATE(?)";
            $params[] = $filterDate;
        }

        $sql .= " ORDER BY date ASC";
        $activities = [];

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute($params);

        while ($row = $sentencia->fetch(PDO::FETCH_ASSOC)) {
            $activities[] = new Activity(
                (int)$row['id'],
                $row['type'],
                $row['monitor'],
                $row['place'],
                $row['date']
            );
        }
        return $activities;
    }

    public function findById(int $id): ?Activity {
        $sql = "SELECT id, type, monitor, place, date FROM activities WHERE id = ?";

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute([$id]);

        //la línea toma la siguiente fila de resultados de la base de datos y la convierte en un array de PHP
        $row = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Activity(
            (int)$row['id'],
            $row['type'],
            $row['monitor'],
            $row['place'],
            $row['date']
        );
    }

    public function update(Activity $activity): bool {
        $sql = "UPDATE activities SET type = ?, monitor = ?, place = ?, date = ? 
                WHERE id = ?";

        $sentencia = $this->conexion->prepare($sql);

        $sentencia->execute([
            $activity->type,
            $activity->monitor,
            $activity->place,
            $activity->date,
            $activity->id
        ]);

        return $sentencia->rowCount() > 0;
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM activities WHERE id = ?";

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute([$id]);

        return $sentencia->rowCount() === 1;
    }
}