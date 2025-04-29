<?php

namespace SamuelTerra22\LaravelEvolutionClient\Models;

class FetchProfile extends Profile
{
    /**
     * Create a new FetchProfile instance.
     *
     * @param string $number
     */
    public function __construct(string $number)
    {
        parent::__construct(['number' => $number]);
    }
}
