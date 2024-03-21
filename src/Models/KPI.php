<?php

namespace Iffifan\WinnieClient\Models;

use DateTime;
use DateTimeInterface;

class KPI
{
    protected ?int $userId;
    protected ?string $email;
    protected ?string $externalID;
    protected float $value;
    protected float $weight;
    protected DateTimeInterface $timestamp;
    protected array $meta;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return KPI
     */
    public function setUserId(?int $userId): KPI
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return KPI
     */
    public function setEmail(?string $email): KPI
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalID(): ?string
    {
        return $this->externalID;
    }

    /**
     * @param string|null $externalID
     * @return KPI
     */
    public function setExternalID(?string $externalID): KPI
    {
        $this->externalID = $externalID;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return KPI
     */
    public function setValue(float $value): KPI
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return KPI
     */
    public function setWeight(float $weight): KPI
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @param DateTimeInterface $timestamp
     * @return KPI
     */
    public function setTimestamp(DateTimeInterface $timestamp): KPI
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return KPI
     */
    public function setMeta(array $meta): KPI
    {
        $this->meta = $meta;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'value' => $this->getValue(),
            'weight' => $this->getWeight(),
            'timestamp' => $this->getTimestamp()->format('Y-m-d H:i'),
            'meta' => $this->getMeta(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public static function fromArray(array $data): KPI
    {
        if (!isset($data['user_id']) && !isset($data['email']) && !isset($data['external_id'])) {
            throw new \InvalidArgumentException('Either user_id, email or external_id must be set');
        }
        $kpi = new self();
        $kpi->setUserId($data['userId'] ?? null);
        $kpi->setEmail($data['email'] ?? null);
        $kpi->setExternalID($data['external_id'] ?? null);
        $kpi->setValue($data['value'] ?? 0);
        $kpi->setWeight($data['weight'] ?? 1);
        $kpi->setTimestamp(new DateTime($data['timestamp'] ?? 'now'));
        $kpi->setMeta($data['meta'] ?? []);
        return $kpi;
    }
}
