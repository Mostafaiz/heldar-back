<?php

use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Morilog\Jalali\Jalalian;

if (!function_exists('jalali')) {
    function jalali($date, $format = 'Y/m/d H:i')
    {
        if (empty($date)) {
            return null;
        }

        try {
            $carbon = $date instanceof \DateTimeInterface
                ? Carbon::instance($date)
                : (is_numeric($date)
                    ? Carbon::createFromTimestamp($date)
                    : Carbon::parse($date));

            return Jalalian::fromCarbon($carbon)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('convert_numbers_to_english')) {
    function convert_numbers_to_english(?string $input): string
    {
        if ($input == '' || $input === null)
            return '';

        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace(
            $persian,
            $english,
            str_replace($arabic, $english, $input)
        );
    }
}

if (!function_exists('card_number_format')) {
    /**
     * Format a card number by inserting a space every 4 digits.
     *
     * @param string $number The card number (can have spaces or not)
     * @return string Formatted card number, e.g., 1234 5678 1234 5678
     */
    function card_number_format(string $number, string $seperator = ' '): string
    {
        return trim(chunk_split($number, 4, $seperator));
    }
}

if (!function_exists('highlight')) {
    function highlight(string $text, string $search): HtmlString
    {
        // اگر چیزی برای جستجو نیست
        if (trim($search) === '') {
            return new HtmlString(e($text));
        }

        // اول کل متن را escape می‌کنیم
        $escapedText = e($text);

        // کلمات جستجو
        $words = array_filter(preg_split('/\s+/', $search));

        foreach ($words as $word) {
            $escapedWord = preg_quote(e($word), '/');

            $escapedText = preg_replace(
                '/(' . $escapedWord . ')/iu',
                '<mark class="bg-yellow-200 rounded-md">$1</mark>',
                $escapedText
            );
        }

        return new HtmlString($escapedText);
    }
}

if (!function_exists('priceToWordsFa')) {

    function priceToWordsFa($number): string
    {
        $digit1 = [
            0 => 'صفر',
            1 => 'یک',
            2 => 'دو',
            3 => 'سه',
            4 => 'چهار',
            5 => 'پنج',
            6 => 'شش',
            7 => 'هفت',
            8 => 'هشت',
            9 => 'نه',
        ];

        $digit1_5 = [
            1 => 'یازده',
            2 => 'دوازده',
            3 => 'سیزده',
            4 => 'چهارده',
            5 => 'پانزده',
            6 => 'شانزده',
            7 => 'هفده',
            8 => 'هجده',
            9 => 'نوزده',
        ];

        $digit2 = [
            1 => 'ده',
            2 => 'بیست',
            3 => 'سی',
            4 => 'چهل',
            5 => 'پنجاه',
            6 => 'شصت',
            7 => 'هفتاد',
            8 => 'هشتاد',
            9 => 'نود',
        ];

        $digit3 = [
            1 => 'صد',
            2 => 'دویست',
            3 => 'سیصد',
            4 => 'چهارصد',
            5 => 'پانصد',
            6 => 'ششصد',
            7 => 'هفتصد',
            8 => 'هشتصد',
            9 => 'نهصد',
        ];

        $steps = [
            1 => 'هزار',
            2 => 'میلیون',
            3 => 'بیلیون',
            4 => 'تریلیون',
            5 => 'کادریلیون',
        ];

        $and = 'و';

        $number = number_format($number, 0, '.', ',');
        $groups = explode(',', $number);
        $stepsCount = count($groups);

        $parts = [];

        foreach ($groups as $index => $group) {
            $group = (int) $group;

            if ($group === 0) {
                continue;
            }

            $d3 = intdiv($group, 100);
            $d2 = intdiv($group % 100, 10);
            $d1 = $group % 10;

            $groupWords = [];

            if ($d3 > 0) {
                $groupWords[] = $digit3[$d3];
            }

            if ($d2 === 1 && $d1 > 0) {
                $groupWords[] = $digit1_5[$d1];
            } else {
                if ($d2 > 0) {
                    $groupWords[] = $digit2[$d2];
                }
                if ($d1 > 0) {
                    $groupWords[] = $digit1[$d1];
                }
            }

            $text = implode(" $and ", $groupWords);

            $stepIndex = $stepsCount - $index - 1;
            if ($stepIndex > 0 && isset($steps[$stepIndex])) {
                $text .= ' ' . $steps[$stepIndex];
            }

            $parts[] = $text;
        }

        return implode(" $and ", $parts);
    }
}


// if (!function_exists('priceToWordsFa')) {
//     function priceToWordsFa(int $number): string
//     {
//         if ($number === 0) {
//             return 'صفر تومان';
//         }

//         $ones = [
//             '',
//             'یک',
//             'دو',
//             'سه',
//             'چهار',
//             'پنج',
//             'شش',
//             'هفت',
//             'هشت',
//             'نه',
//             'ده',
//             'یازده',
//             'دوازده',
//             'سیزده',
//             'چهارده',
//             'پانزده',
//             'شانزده',
//             'هفده',
//             'هجده',
//             'نوزده'
//         ];

//         $tens = [
//             '',
//             '',
//             'بیست',
//             'سی',
//             'چهل',
//             'پنجاه',
//             'شصت',
//             'هفتاد',
//             'هشتاد',
//             'نود'
//         ];

//         $hundreds = [
//             '',
//             'صد',
//             'دویست',
//             'سیصد',
//             'چهارصد',
//             'پانصد',
//             'ششصد',
//             'هفتصد',
//             'هشتصد',
//             'نهصد'
//         ];

//         $units = [
//             1000000000 => 'میلیارد',
//             1000000    => 'میلیون',
//             1000       => 'هزار',
//             1          => ''
//         ];

//         $result = [];

//         foreach ($units as $value => $unitName) {
//             if ($number >= $value) {
//                 $count = intdiv($number, $value);
//                 $number %= $value;

//                 if ($count > 0) {
//                     if ($count < 20) {
//                         $result[] = trim($ones[$count] . ' ' . $unitName);
//                     } elseif ($count < 100) {
//                         $result[] = trim(
//                             $tens[intdiv($count, 10)] .
//                                 ($count % 10 ? ' و ' . $ones[$count % 10] : '') .
//                                 ' ' . $unitName
//                         );
//                     } else {
//                         $result[] = trim(
//                             $hundreds[intdiv($count, 100)] .
//                                 ($count % 100 ? ' و ' . priceToWordsFa($count % 100) : '') .
//                                 ' ' . $unitName
//                         );
//                     }
//                 }
//             }
//         }

//         return implode(' و ', array_filter($result)) . ' تومان';
//     }
// }
