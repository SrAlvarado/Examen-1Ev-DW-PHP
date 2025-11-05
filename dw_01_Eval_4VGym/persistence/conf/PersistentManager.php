<?php
class PersistentManager {
    private static $conexion = null;
    private const DB_HOST = 'localhost';
    private const DB_NAME = '4vgym';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    private function __construct() {
    }

    /**
     * Devuelve la instancia única de la conexión PDO.
     *
     * @return PDO La conexión activa a la base de datos.
     * @throws PDOException Si la conexión falla.
     */
    public static function getConexion(): PDO {
        $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::DB_CHARSET;

        try {
            $conexion = new PDO($dsn, self::DB_USER, self::DB_PASS);

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

            return $conexion;
        } catch (PDOException $e) {
            die("Error de conexión a la BBDD (4VGym): " . $e->getMessage());
        }
    }

    public static function cerrarConexion(): void {
        self::$conexion = null;
    }
}