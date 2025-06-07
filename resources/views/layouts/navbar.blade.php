
<div class="page__nav nav">
    <div class="nav__container">
        <div data-simplebar class="nav__body">
            <ul class="nav__list">
                <!--Активному пукнту додати клас _active-->
                <li>
                    <a href="{{ route('home') }}">
                        Головна
                    </a>
                </li>
                <li>
                    <a href="{{ route('tournaments') }}">
                        Турніри
                    </a>
                </li>
                <li>
                    <a href="{{ route('tables') }}">
                        Таблиці
                    </a>
                </li>
                <li>
                    <a href="{{ route('stadiums') }}">
                        Стадіони
                    </a>
                </li>
                <li>
                    <a href="{{ asset(route('article.show', '1')) }}">
                        Регламент
                    </a>
                </li>
                <li>
                    <a href="{{ route('archive') }}">
                        Архів
                    </a>
                </li>
                <li>
                    <a href="{{ asset(route('contacts')) }}">
                        Контакти
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>