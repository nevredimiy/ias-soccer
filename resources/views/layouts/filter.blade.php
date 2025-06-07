<div class="filter__container">
    <!-- Вставьте это в нужное место на странице -->
    <form class="filter__form" action="#" method="POST">
        @csrf
        <select id="city_select">
            <option value="">Виберіть місто</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }}</option>
            @endforeach
        </select>

        <select id="district_select" disabled>
            <option value="">Виберіть район</option>
        </select>

        <select id="location_select" disabled>
            <option value="">Виберіть локацію</option>
        </select>

        <select id="league_select" disabled>
            <option value="">Виберіть лігу</option>
        </select>
    </form>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            // Слушаем изменение в селекте городов
            $('#city_select').change(function () {
                var city_id = $(this).val();
                if (city_id) {
                    // Делаем селект района доступным
                    $('#district_select').prop('disabled', false);
                    // Отправляем запрос для получения районов
                    $.get('/districts/' + city_id, function (data) {
                        $('#district_select').empty();
                        $('#district_select').append('<option value="">Выберите район</option>');
                        data.forEach(function (district) {
                            $('#district_select').append('<option value="' + district.id + '">' + district.name + '</option>');
                        });
                    });
                } else {
                    $('#district_select').prop('disabled', true).empty();
                    $('#location_select').prop('disabled', true).empty();
                    $('#league_select').prop('disabled', true).empty();
                }
            });

            // Слушаем изменение в селекте районов
            $('#district_select').change(function () {
                var district_id = $(this).val();
                if (district_id) {
                    $('#location_select').prop('disabled', false);
                    $.get('/locations/' + district_id, function (data) {
                        $('#location_select').empty();
                        $('#location_select').append('<option value="">Выберите локацию</option>');
                        data.forEach(function (location) {
                            $('#location_select').append('<option value="' + location.id + '">' + location.name + '</option>');
                        });
                    });
                } else {
                    $('#location_select').prop('disabled', true).empty();
                    $('#league_select').prop('disabled', true).empty();
                }
            });

            // Слушаем изменение в селекте локаций
            $('#location_select').change(function () {
                var location_id = $(this).val();
                if (location_id) {
                    $('#league_select').prop('disabled', false);
                    $.get('/leagues/' + location_id, function (data) {
                        $('#league_select').empty();
                        $('#league_select').append('<option value="">Выберите лигу</option>');
                        data.forEach(function (league) {
                            $('#league_select').append('<option value="' + league.id + '">' + league.name + '</option>');
                        });
                    });
                } else {
                    $('#league_select').prop('disabled', true).empty();
                }
            });
        });
    </script> --}}


</div>
