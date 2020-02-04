<?php


namespace App\Entity;


class PerformanceSearch
{
    /**
     * @var string | null
     */
    private $searchText;

    /**
     * @var City | null
     */
    private $city;

    /**
     * @return string|null
     */
    public function getSearchText(): ?string
    {
        return $this->searchText;
    }

    /**
     * @param string|null $searchText
     */
    public function setSearchText(?string $searchText): void
    {
        $this->searchText = $searchText;
    }

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     */
    public function setCity(?City $city): void
    {
        $this->city = $city;
    }
}