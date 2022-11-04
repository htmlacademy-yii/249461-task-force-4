<?php

namespace app\services;

class UserServices
{
    /**
     * @param $rating float Значение рейтинга
     * @param $starsSize string Строка для класса small|big Отвечает за размер звезд
     * @return void
     */
    public function renderStarRating(float $rating, string $starsSize = 'small') :void
    {
        $max_rating = 5;
        $emptyStars = $max_rating - floor($rating);

        $showStars = '';

        for ($i = 1; $i <= $rating; $i++) {
            $showStars .= '<span class="fill-star">&nbsp;</span>';
        }

        if ($emptyStars > 0) {
            for ($i = 1; $i <= $emptyStars; $i++) {
                $showStars .= '<span>&nbsp;</span>';
            }
        }

        echo "<div class='stars-rating $starsSize'>$showStars</div>";
    }
}