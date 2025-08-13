<?php

use App\Models\User;
use App\Models\Story;
use App\Models\Banner;
use Nette\Utils\Random;
use App\Enums\LevelEnum;
use App\Enums\MediaTypeEnum;
use App\Constants\ModelPaths;
use App\Constants\MediaCollection;
use Illuminate\Support\Facades\Config;
use App\Services\System\SystemSettingService;

// if (!function_exists('generateEmail')) {
//     function generateEmail()
//     {
//         do {
//             $year = date('Y');
//             $month = date('m');
//             $randomNumber = random_int(00, 99);
//             $email =  $year . $month . $randomNumber . '@friendApp.com';
//         } while (User::where('email', $email)->exists());

//         return $email;
//     }
// }

if (!function_exists('generatePassword')) {
    function generatePassword()
    {
        $year = date('Y');
        $month = date('m');
        $randomNumber = random_int(00, 99);
        $password = $year . $month . $randomNumber . Random::generate(15);
        return $password;
    }
}

if (!function_exists('customizePaginationData')) {
    function customizePaginationData($data)
    {
        $paginationData = [
            'total'          => $data['total'] ?? null,
            'per_page'       => $data['per_page'] ?? null,
            'first_page_url' => $data['first_page_url'] ?? null,
            'prev_page_url'  => $data['prev_page_url'] ?? null,
            'current_page'   => $data['current_page'] ?? null,
            'next_page_url'  => $data['next_page_url'],
            'last_page_url'  => $data['last_page_url'] ?? null,
            // 'from'           => $data['from'] ?? null,
            // 'last_page'      => $data['last_page'] ?? null,
            // 'links'          => $data['links'],
            // 'path'           => $data['path'],
            // 'to'             => $data['to'] ?? null,
        ];

        return $paginationData;
    }
}

if (!function_exists('selectRandomElement')) {
    function selectRandomElement($values, $weights)
    {

        $weightedValues = array_combine($values, $weights);
        $rand = mt_rand(1, (int) array_sum($weightedValues));

        foreach ($weightedValues as $value => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $value;
            }
        }
    }
}

if (!function_exists('generateRandomNumber')) {
    function generateRandomNumber(int $numberOfDigits): string
    {
        // Ensure that the first digit is not 0 to prevent octal interpretation
        $firstDigit = mt_rand(1, 9);
        $number = (string)$firstDigit;

        // Generate the remaining digits
        for ($i = 1; $i < $numberOfDigits; $i++) {
            $number .= mt_rand(0, 9);
        }

        return $number;
    }
}

if (!function_exists('prepareTranslatableData')) {
    function prepareTranslatableData(array $validatedData): array
    {
        $translatableData = [];
        $supportedLocales = Config::get('app.available_locales', []);

        foreach ($validatedData as $key => $value) {
            if (strpos($key, '_') !== false) {
                [$field, $locale] = explode('_', $key, 2);

                if (in_array($locale, $supportedLocales)) {
                    $translatableData[$field][$locale] = $value;
                } else {
                    $translatableData[$key] = $value;
                }
            } else {
                $translatableData[$key] =  $value;
            }
        }
        return $translatableData;
    }
}

if (!function_exists('decodeStringToArray')) {
    function decodeStringToArray(string $string): array
    {
        $array = [];
        if (is_string($string)) {
            $array = json_decode(str_replace("'", '"', $string), true);
            if (!is_array($array)) $array = [];
        }
        return $array;
    }
}

if (!function_exists('dlrToSyp')) {
    function dlrToSyp(float $price, $dlrPrice = null): float
    {
        return $price * ($dlrPrice ?? (new SystemSettingService)->index()[0]["value"]);
    }
}

if (!function_exists('sypToDlr')) {
    function sypToDlr(float $price, $dlrPrice = null): float
    {
        return $price / ($dlrPrice ?? (new SystemSettingService)->index()[0]["value"]);
    }
}

if (!function_exists('getMediaType')) {
    function getMediaType($mime_type)
    {
        if ((strpos($mime_type, 'image') !== false)) {
            return MediaTypeEnum::IMAGE;
        } elseif ((strpos($mime_type, 'video') !== false)) {
            return MediaTypeEnum::VIDEO;
        } else {
            return MediaTypeEnum::FILE;
        }
    }
}

if (!function_exists('getOrPaginate')) {
    function getOrPaginate($items, $data)
    {
        $items = (isset($data['per_page']))
            ? $items->paginate($data['per_page'])
            : $items->get();

        return $items;
    }
}


if (!function_exists('mediaCollectionByContxt')) {
    function mediaCollectionByContxt($model_path)
    {
        $data = [
            // LevelEnum::SPECIALIZATION => MediaCollection::SPECIALIZATION_COLLECTION,
            // LevelEnum::COURSE         => MediaCollection::COURSE_COLLECTION,
            // LevelEnum::UNIT           => MediaCollection::UNIT_COLLECTION,
            // LevelEnum::SUB_UNIT       => MediaCollection::SUB_UNIT_COLLECTION,
            // LevelEnum::LESSON         => MediaCollection::LESSON_COLLECTION,
            // LevelEnum::FILE           => MediaCollection::FILE_COLLECTION,
            // LevelEnum::QUIZ           => MediaCollection::QUIZ_COLLECTION,
            // LevelEnum::QUESTION       => MediaCollection::QUESTION_COLLECTION,
            // LevelEnum::ANSWER         => MediaCollection::ANSWER_COLLECTION,
            // LevelEnum::TEACHER        => MediaCollection::TEACHER_COLLECTION,
            LevelEnum::STORY          => MediaCollection::STORY_COLLECTION,
            LevelEnum::BANNER         => MediaCollection::BANNER_COLLECTION,
            LevelEnum::USER         => MediaCollection::USER_COLLECTION,
        ];

        return $data[$model_path] ?? null;
    }
}


if (!function_exists('getModel')) {
    function getModel($model_path)
    {
        $data = [
            // LevelEnum::SPECIALIZATION => Specialization::class,
            // LevelEnum::COURSE         => Course::class,
            // LevelEnum::UNIT           => Unit::class,
            // LevelEnum::SUB_UNIT       => SubUnit::class,
            // LevelEnum::LESSON         => Lesson::class,
            // LevelEnum::FILE           => File::class,
            // LevelEnum::QUIZ           => Quiz::class,
            // LevelEnum::QUESTION       => Question::class,
            // LevelEnum::ANSWER         => Answer::class,
            // LevelEnum::TEACHER        => Teacher::class,
            LevelEnum::STORY                => Story::class,
            LevelEnum::BANNER               => Banner::class,
            LevelEnum::USER               => User::class,
        ];

        return $data[$model_path] ?? null;
    }
}


if (!function_exists('getModelName')) {
    function getModelName($model_path)
    {
        $data = [
            // ModelPaths::Specialization => LevelEnum::SPECIALIZATION,
            // ModelPaths::Course         => LevelEnum::COURSE,
            // ModelPaths::Unit           => LevelEnum::UNIT,
            // ModelPaths::SubUnit        => LevelEnum::SUB_UNIT,
            // ModelPaths::Lesson         => LevelEnum::LESSON,
            // ModelPaths::File           => LevelEnum::FILE,
            // ModelPaths::Quiz           => LevelEnum::QUIZ,
            // ModelPaths::Question       => LevelEnum::QUESTION,
            // ModelPaths::Answer         => LevelEnum::ANSWER,
            // ModelPaths::Teacher        => LevelEnum::TEACHER,
            ModelPaths::Story          => LevelEnum::STORY,
            ModelPaths::Banner         => LevelEnum::BANNER,
            ModelPaths::User         => LevelEnum::USER,
        ];

        return $data[$model_path] ?? null;
    }
}

if (!function_exists('getModelByPath')) {
    function getModelByPath($model_path)
    {
        $data = [
            ModelPaths::Story          => Story::class,
            ModelPaths::Banner         => Banner::class,
            ModelPaths::User         => User::class,
        ];

        return $data[$model_path] ?? null;
    }
}

if (!function_exists('maskString')) {
    function maskString($string, $visibleCharsLength = 5, $maskChar = '*')
    {
        $visiblePart = substr($string, 0, $visibleCharsLength);
        $maskedPart = str_repeat($maskChar, strlen($string) - $visibleCharsLength);
        return "$visiblePart$maskedPart";
    }
}
