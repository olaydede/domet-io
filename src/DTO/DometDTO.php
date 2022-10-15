<?php
namespace App\DTO;

use DateTime;

class DometDTO
{
    public string $dometID;
    public string $userID;
    public string $taskID;
    public string $type;
    public string $status;
    public int $duration;
    public int $durationLeft;

    /**
     * @param string $dometID
     * @param string $userID
     * @param string $taskID
     * @param string $type
     * @param string $status
     * @param int $duration
     * @param int $durationLeft
     */
    public function __construct(
        string $dometID,
        string $userID,
        string $taskID,
        string $type,
        string $status,
        int $duration,
        int $durationLeft
    ) {
        $this->dometID = $dometID;
        $this->userID = $userID;
        $this->taskID = $taskID;
        $this->type = $type;
        $this->status = $status;
        $this->duration = $duration;
        $this->durationLeft = $durationLeft;
    }

    /**
     * @return string
     */
    public function getDometID(): string
    {
        return $this->dometID;
    }

    /**
     * @return string
     */
    public function getUserID(): string
    {
        return $this->userID;
    }

    /**
     * @return string
     */
    public function getTaskID(): string
    {
        return $this->taskID;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getDurationLeft(): int
    {
        return $this->durationLeft;
    }
}
