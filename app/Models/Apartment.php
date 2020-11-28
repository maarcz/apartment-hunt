<?php

namespace App\Models;

class Apartment
{
    private $id;

    private $street;

    private $price;

    /**
     * Apartment constructor.
     * @param $id
     * @param $street
     * @param $price
     */
    public function __construct($id, $street, $price)
    {
        $this->id = $id;
        $this->street = $street;
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function getUrl(): string
    {
        return 'https://www.ss.lv/msg/lv/real-estate/flats/riga/centre/' . $this->id .  '.html';
    }
}
