<?php

namespace SamuelTerra22\LaravelEvolutionClient\Models;

class ProfileName extends Profile
{
    /**
     * Create a new ProfileName instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(['name' => $name]);
    }
}
