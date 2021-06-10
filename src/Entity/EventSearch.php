<?php

namespace App\Entity;

use App\Repository\EventSearchRepository;
use DateTime;


class EventSearch
{

    /**
     * @var int/null
     */
    private $minPrice;

    /**
     * @var int/null
     */
    private $maxPrice;

    /**
     * @var datetime/null
     */
    private $date_start;

    /**
     * @return int|null
     */
    public function getMinPrice():? int
    {
        return $this->minPrice;
    }

    /**
     * @param int $minPrice
     */
    public function setMinPrice(int $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * @return int|null
     */
    public function getMaxPrice():? int
    {
        return $this->maxPrice;
    }

    /**
     * @param int $maxPrice
     */
    public function setMaxPrice(int $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * @return datetime|null
     */
    public function getDateStart():? datetime
    {
        return $this->date_start;
    }

    /**
     * @param datetime $date_start
     */
    public function setDateStart(?datetime $date_start): void
    {
        $this->date_start = $date_start;
    }


}
