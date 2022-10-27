<?php
/**
 * @author <Akartis>
 */
namespace App\Services\Application;

class AppService
{
    private string $searchTerm = "";

    /**
     * @return string
     */
    public function getSearchTerm(): string
    {
        return $this->searchTerm;
    }

    /**
     * @param string $searchTerm
     * @return AppService
     */
    public function setSearchTerm(string $searchTerm): AppService
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }
}
