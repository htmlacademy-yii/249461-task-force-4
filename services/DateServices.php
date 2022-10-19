<?php

namespace app\services;

class DateServices
{

    /**
     * Возвращает корректную форму множественного числа
     * Ограничения: только для целых чисел
     *
     * Пример использования:
     * $remaining_minutes = 5;
     * echo "Я поставил таймер на {$remaining_minutes} " .
     *     get_noun_plural_form(
     *         $remaining_minutes,
     *         'минута',
     *         'минуты',
     *         'минут'
     *     );
     * Результат: "Я поставил таймер на 5 минут"
     *
     * @param int $number Число, по которому вычисляем форму множественного числа
     * @param string $one Форма единственного числа: яблоко, час, минута
     * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
     * @param string $many Форма множественного числа для остальных чисел
     *
     * @return string Рассчитанная форма множественнго числа
     */
    public function get_noun_plural_form(int $number, string $one, string $two, string $many): string
    {
        $number = (int)$number;
        $mod10 = $number % 10;
        $mod100 = $number % 100;

        switch (true) {
            case ($mod100 >= 11 && $mod100 <= 20):
                return $many;

            case ($mod10 > 5):
                return $many;

            case ($mod10 === 1):
                return $one;

            case ($mod10 >= 2 && $mod10 <= 4):
                return $two;

            default:
                return $many;
        }
    }

    /**
     * @param string $date Случайная дата в формате «ГГГГ-ММ-ДД ЧЧ: ММ: СС»
     * @return string Пройденное время к текущему моменту в относительном формате
     */
    public function elapsed_time(string $date): string
    {
        $now_date = date_create('now');
        $post_date = date_create($date);

        $diff = date_diff($now_date, $post_date);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $periods = [
            'y' => ['год', 'года', 'лет'],
            'm' => ['месяц', 'месяца', 'месяцев'],
            'w' => ['неделю', 'недели', 'недель'],
            'd' => ['день', 'дня', 'дней'],
            'h' => ['час', 'часа', 'часов'],
            'i' => ['минута', 'минуты', 'минут'],
            's' => ['секунда', 'секунды', 'секунд']
        ];

        $passed_time = '';

        foreach ($periods as $key => $period) {
            if ($diff->$key) {
                $period = $this->get_noun_plural_form($diff->$key, $period['0'], $period['1'], $period['2']);
                $passed_time = $diff->$key . ' ' . $period . ' назад';

                break;
            }
        }

        return $passed_time;
    }

    public function countUserAge($birthday) {
        $age = date('Y') - date('Y',(strtotime($birthday)));

        if (date('md', strtotime($birthday)) > date('md')) {
            $age--;
        }

        return $age . ' ' . $this->get_noun_plural_form($age,'год', 'года','лет');
    }
}