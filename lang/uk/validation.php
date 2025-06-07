<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Поле :attribute має бути прийняте.',
    'accepted_if' => 'Поле :attribute має бути прийняте, коли :other дорівнює :value.',
    'active_url' => 'Поле :attribute має бути дійсною URL-адресою.',
    'after' => 'Поле :attribute має містити дату після :date.',
    'after_or_equal' => 'Поле :attribute має містити дату після або рівну :date.',
    'alpha' => 'Поле :attribute може містити лише літери.',
    'alpha_dash' => 'Поле :attribute може містити лише літери, цифри, тире та підкреслення.',
    'alpha_num' => 'Поле :attribute може містити лише літери та цифри.',
    'array' => 'Поле :attribute має бути масивом.',
    'ascii' => 'Поле :attribute може містити лише однобайтові алфавітно-цифрові символи та знаки.',
    'before' => 'Поле :attribute має містити дату до :date.',
    'before_or_equal' => 'Поле :attribute має містити дату до або рівну :date.',
    'between' => [
        'array' => 'Поле :attribute має містити від :min до :max елементів.',
        'file' => 'Поле :attribute має бути від :min до :max кілобайт.',
        'numeric' => 'Поле :attribute має бути між :min та :max.',
        'string' => 'Поле :attribute має містити від :min до :max символів.',
    ],
    'boolean' => 'Поле :attribute має бути true або false.',
    'can' => 'Поле :attribute містить неприпустиме значення.',
    'confirmed' => 'Підтвердження для поля :attribute не співпадає.',
    'contains' => 'Поле :attribute не містить необхідного значення.',
    'current_password' => 'Пароль невірний.',
    'date' => 'Поле :attribute має бути дійсною датою.',
    'date_equals' => 'Поле :attribute має бути датою, рівною :date.',
    'date_format' => 'Поле :attribute має відповідати формату :format.',
    'decimal' => 'Поле :attribute має містити :decimal знаків після коми.',
    'declined' => 'Поле :attribute має бути відхилене.',
    'declined_if' => 'Поле :attribute має бути відхилене, коли :other дорівнює :value.',
    'different' => 'Поля :attribute та :other мають відрізнятися.',
    'digits' => 'Поле :attribute має містити :digits цифр.',
    'digits_between' => 'Поле :attribute має містити від :min до :max цифр.',
    'dimensions' => 'Поле :attribute має недійсні розміри зображення.',
    'distinct' => 'Поле :attribute містить повторюване значення.',
    'doesnt_end_with' => 'Поле :attribute не повинно закінчуватись на одне з наступного: :values.',
    'doesnt_start_with' => 'Поле :attribute не повинно починатися з одного з наступного: :values.',
    'email' => 'Поле :attribute повинно бути дійсною електронною адресою.',
    'ends_with' => 'Поле :attribute має закінчуватися на одне з наступного: :values.',
    'enum' => 'Обране значення для :attribute некоректне.',
    'exists' => 'Обране значення для :attribute некоректне.',
    'extensions' => 'Поле :attribute має мати один із наступних розширень: :values.',
    'file' => 'Поле :attribute має бути файлом.',
    'filled' => 'Поле :attribute обов’язкове для заповнення.',
    'gt' => [
    'array' => 'Поле :attribute має містити більше ніж :value елементів.',
    'file' => 'Розмір файлу у полі :attribute має бути більшим за :value кілобайт.',
    'numeric' => 'Поле :attribute має бути більшим за :value.',
    'string' => 'Поле :attribute має містити більше ніж :value символів.',
    ],
    'gte' => [
        'array' => 'Поле :attribute має містити :value елементів або більше.',
        'file' => 'Розмір файлу у полі :attribute має бути більшим або дорівнювати :value кілобайт.',
        'numeric' => 'Поле :attribute має бути більшим або дорівнювати :value.',
        'string' => 'Поле :attribute має містити не менше ніж :value символів.',
    ],

    'hex_color' => 'Поле :attribute повинно бути допустимим шістнадцятковим кольором.',
    'image' => 'Поле :attribute повинно бути зображенням.',
    'in' => 'Вибране значення для поля :attribute є недійсним.',
    'in_array' => 'Поле :attribute повинно існувати в :other.',
    'integer' => 'Поле :attribute повинно бути цілим числом.',
    'ip' => 'Поле :attribute повинно бути дійсною IP-адресою.',
    'ipv4' => 'Поле :attribute повинно бути дійсною IPv4-адресою.',
    'ipv6' => 'Поле :attribute повинно бути дійсною IPv6-адресою.',
    'json' => 'Поле :attribute повинно бути дійсним JSON-рядком.',
    'list' => 'Поле :attribute повинно бути списком.',
    'lowercase' => 'Поле :attribute повинно бути у нижньому регістрі.',

    'lt' => [
        'array' => 'Поле :attribute має містити менше ніж :value елементів.',
        'file' => 'Розмір файлу у полі :attribute має бути меншим ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має бути менше ніж :value.',
        'string' => 'Поле :attribute має містити менше ніж :value символів.',
    ],
    'lte' => [
        'array' => 'Поле :attribute не повинно містити більше ніж :value елементів.',
        'file' => 'Розмір файлу у полі :attribute має бути меншим або дорівнювати :value кілобайт.',
        'numeric' => 'Поле :attribute має бути меншим або дорівнювати :value.',
        'string' => 'Поле :attribute має містити не більше ніж :value символів.',
    ],
    'mac_address' => 'Поле :attribute має бути дійсною MAC-адресою.',
    'max' => [
        'array' => 'Поле :attribute не повинно містити більше ніж :max елементів.',
        'file' => 'Розмір файлу у полі :attribute не повинен перевищувати :max кілобайт.',
        'numeric' => 'Поле :attribute не повинно бути більшим за :max.',
        'string' => 'Поле :attribute не повинно містити більше ніж :max символів.',
    ],
    'max_digits' => 'Поле :attribute не повинно містити більше ніж :max цифр.',
    'mimes' => 'Поле :attribute має бути файлом одного з типів: :values.',
    'mimetypes' => 'Поле :attribute має бути файлом одного з типів: :values.',
    'min' => [
        'array' => 'Поле :attribute має містити принаймні :min елементів.',
        'file' => 'Розмір файлу у полі :attribute має бути не меншим за :min кілобайт.',
        'numeric' => 'Поле :attribute має бути не меншим за :min.',
        'string' => 'Поле :attribute має бути принаймні :min символів.',
    ],

    'min_digits' => 'Поле :attribute повинно містити щонайменше :min цифр.',
    'missing' => 'Поле :attribute повинно бути відсутнє.',
    'missing_if' => 'Поле :attribute повинно бути відсутнє, якщо :other дорівнює :value.',
    'missing_unless' => 'Поле :attribute повинно бути відсутнє, якщо тільки :other не дорівнює :value.',
    'missing_with' => 'Поле :attribute повинно бути відсутнє, якщо присутнє :values.',
    'missing_with_all' => 'Поле :attribute повинно бути відсутнє, якщо присутні значення :values.',
    'multiple_of' => 'Поле :attribute повинно бути кратним :value.',
    'not_in' => 'Вибране значення для поля :attribute є недійсним.',
    'not_regex' => 'Неправильний формат поля :attribute.',
    'numeric' => 'Поле :attribute повинно бути числом.',
    'password' => [
        'letters' => 'Поле :attribute повинно містити щонайменше одну літеру.',
        'mixed' => 'Поле :attribute повинно містити щонайменше одну велику та одну малу літеру.',
        'numbers' => 'Поле :attribute повинно містити щонайменше одну цифру.',
        'symbols' => 'Поле :attribute повинно містити щонайменше один символ.',
        'uncompromised' => 'Значення :attribute було виявлено в витоку даних. Будь ласка, виберіть інше значення для :attribute.',
    ],

    'present' => 'Поле :attribute має бути присутнім.',
    'present_if' => 'Поле :attribute має бути присутнім, коли :other дорівнює :value.',
    'present_unless' => 'Поле :attribute має бути присутнім, якщо тільки :other не дорівнює :value.',
    'present_with' => 'Поле :attribute має бути присутнім, коли присутні :values.',
    'present_with_all' => 'Поле :attribute має бути присутнім, коли присутні всі :values.',
    'prohibited' => 'Поле :attribute заборонено.',
    'prohibited_if' => 'Поле :attribute заборонено, коли :other дорівнює :value.',
    'prohibited_if_accepted' => 'Поле :attribute заборонено, коли :other прийнято.',
    'prohibited_if_declined' => 'Поле :attribute заборонено, коли :other відхилено.',
    'prohibited_unless' => 'Поле :attribute заборонено, якщо тільки :other не входить до :values.',
    'prohibits' => 'Поле :attribute забороняє присутність :other.',
    'regex' => 'Формат поля :attribute недійсний.',
    'required' => 'Поле :attribute є обов’язковим для заповнення.',
    'required_array_keys' => 'Поле :attribute має містити записи для: :values.',
    'required_if' => 'Поле :attribute є обов’язковим, коли :other дорівнює :value.',
    'required_if_accepted' => 'Поле :attribute є обов’язковим, коли :other прийнято.',
    'required_if_declined' => 'Поле :attribute є обов’язковим, коли :other відхилено.',
    'required_unless' => 'Поле :attribute є обов’язковим, якщо тільки :other не входить до :values.',
    'required_with' => 'Поле :attribute є обов’язковим, коли присутні :values.',
    'required_with_all' => 'Поле :attribute є обов’язковим, коли присутні всі :values.',
    'required_without' => 'Поле :attribute є обов’язковим, коли :values відсутні.',
    'required_without_all' => 'Поле :attribute є обов’язковим, коли жодне з :values не присутнє.',
    'same' => 'Поле :attribute має співпадати з :other.',
    'size' => [
        'array' => 'Поле :attribute має містити :size елементів.',
        'file' => 'Розмір файлу у полі :attribute має бути :size кілобайт.',
        'numeric' => 'Поле :attribute має бути :size.',
        'string' => 'Поле :attribute має містити :size символів.',
    ],
    'starts_with' => 'Поле :attribute має починатися з одного з наступних: :values.',
    'string' => 'Поле :attribute повинно бути рядком.',
    'timezone' => 'Поле :attribute має бути дійсною часовою зоною.',
    'unique' => 'Значення поля :attribute вже зайняте.',
    'uploaded' => 'Завантаження поля :attribute не вдалося.',
    'uppercase' => 'Поле :attribute має бути у верхньому регістрі.',
    'url' => 'Поле :attribute має бути дійсним URL.',
    'ulid' => 'Поле :attribute має бути дійсним ULID.',
    'uuid' => 'Поле :attribute має бути дійсним UUID.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'last_name' => 'Прізвище',
    ],

];
