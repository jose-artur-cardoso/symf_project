<?php
// src/Twig/AppExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('french_phone_format', [$this, 'frenchPhoneFormat']),
        ];
    }

    public function frenchPhoneFormat($number)
    {
        // Remove non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Format the number as a string with a dot every 2 digits
        $formattedNumber = implode('.', str_split($number, 2));

        return $formattedNumber;
    }
}