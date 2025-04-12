<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Models\FetchProfile;
use SamuelTerra22\EvolutionLaravelClient\Models\ProfileName;
use SamuelTerra22\EvolutionLaravelClient\Models\ProfileStatus;
use SamuelTerra22\EvolutionLaravelClient\Models\ProfilePicture;
use SamuelTerra22\EvolutionLaravelClient\Models\PrivacySettings;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Profile
{
    /**
     * @var EvolutionService The Evolution service
     */
    protected EvolutionService $service;

    /**
     * @var string The instance name
     */
    protected string $instanceName;

    /**
     * Create a new Profile resource instance.
     *
     * @param EvolutionService $service
     * @param string $instanceName
     */
    public function __construct(EvolutionService $service, string $instanceName)
    {
        $this->service = $service;
        $this->instanceName = $instanceName;
    }

    /**
     * Set the instance name.
     *
     * @param string $instanceName
     * @return void
     */
    public function setInstanceName(string $instanceName): void
    {
        $this->instanceName = $instanceName;
    }

    /**
     * Fetch business profile.
     *
     * @param string $number
     * @return array
     * @throws EvolutionApiException
     */
    public function fetchBusinessProfile(string $number): array
    {
        $profile = new FetchProfile($number);

        return $this->service->post("/chat/fetchBusinessProfile/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Fetch profile.
     *
     * @param string $number
     * @return array
     * @throws EvolutionApiException
     */
    public function fetchProfile(string $number): array
    {
        $profile = new FetchProfile($number);

        return $this->service->post("/chat/fetchProfile/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Update profile name.
     *
     * @param string $name
     * @return array
     * @throws EvolutionApiException
     */
    public function updateProfileName(string $name): array
    {
        $profile = new ProfileName($name);

        return $this->service->post("/chat/updateProfileName/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Update profile status.
     *
     * @param string $status
     * @return array
     * @throws EvolutionApiException
     */
    public function updateProfileStatus(string $status): array
    {
        $profile = new ProfileStatus($status);

        return $this->service->post("/chat/updateProfileStatus/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Update profile picture.
     *
     * @param string $picture
     * @return array
     * @throws EvolutionApiException
     */
    public function updateProfilePicture(string $picture): array
    {
        $profile = new ProfilePicture($picture);

        return $this->service->post("/chat/updateProfilePicture/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Remove profile picture.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function removeProfilePicture(): array
    {
        return $this->service->delete("/chat/removeProfilePicture/{$this->instanceName}");
    }

    /**
     * Fetch privacy settings.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function fetchPrivacySettings(): array
    {
        return $this->service->get("/chat/fetchPrivacySettings/{$this->instanceName}");
    }

    /**
     * Update privacy settings.
     *
     * @param string $readreceipts
     * @param string $profile
     * @param string $status
     * @param string $online
     * @param string $last
     * @param string $groupadd
     * @return array
     * @throws EvolutionApiException
     */
    public function updatePrivacySettings(
        string $readreceipts,
        string $profile,
        string $status,
        string $online,
        string $last,
        string $groupadd
    ): array {
        $privacy = new PrivacySettings($readreceipts, $profile, $status, $online, $last, $groupadd);

        return $this->service->post("/chat/updatePrivacySettings/{$this->instanceName}", $privacy->toArray());
    }
}
