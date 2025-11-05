<?php
class SessionHelper
{
    private const LAST_PAGE_KEY = 'last_viewed_page';

    private const DEFAULT_PAGE = 'listActivities.php';

    public const LIST_PAGE = 'listActivities.php';

    public const CREATE_PAGE = 'createActivity.php';

    public static function startSession(): void
    {
        // Verifica si el estado de la sesión es PHP_SESSION_NONE (no iniciada)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setLastViewedPage(string $pagePath): void
    {
        self::startSession();
        // Guardamos el nombre de la página
        $_SESSION[self::LAST_PAGE_KEY] = $pagePath;
    }

    public static function getRedirectPage(): string
    {
        self::startSession();

       // Si la sesión no tiene una página guardada o está vacía, redirige a la página por defecto
        if (isset($_SESSION[self::LAST_PAGE_KEY]) && !empty($_SESSION[self::LAST_PAGE_KEY])) {
            return $_SESSION[self::LAST_PAGE_KEY];
        } else {
            return self::DEFAULT_PAGE;
        }
    }
}