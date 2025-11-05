<?php

class Activity {
    public ?int $id;
    public string $type;
    public string $monitor;
    public string $place;
    public string $date;

    public function __construct(
        ?int $id,
        string $type,
        string $monitor,
        string $place,
        string $date
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->monitor = $monitor;
        $this->place = $place;
        $this->date = $date;
    }

    /**
     * Convierte la fecha/hora del objeto a un formato legible por el usuario.
     * @return string
     */
    public function getFormattedDate(): string {
        try {
            $dateTime = new DateTime($this->date);
            return $dateTime->format('d M Y H:i');
        } catch (\Exception $e) {
            return $this->date;
        }
    }
}