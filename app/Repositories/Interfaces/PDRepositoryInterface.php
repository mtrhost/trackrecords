<?php

namespace App\Repositories\Interfaces;

interface PDRepositoryInterface
{
    public function parseProfileData(string $profileLink);

    public function parseProfileAvatar(string $profileLink);

    public function parseVotes(string $gameLink = '');
}