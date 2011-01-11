<ul id="menu">
    <li> <span>Пользователи и доступы</span>
        <ul>
            <li id="subscribes" class="yes">
                <a href="<?=WEB_PREFIX?>Auth/Users/List/">Пользователи</a>
                <b class="opt">
                    <a href="<?=WEB_PREFIX?>Auth/Users/Add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                </b>
            </li>
        </ul>
    </li>

    <li> <span>Рассылки</span>
        <ul>
            <li id="subscribes" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/List/">Рассылки</a></li>
            <li id="startSubscribe" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/Add/">Создать новую рассылку</a></li>
            <li id="startSubscribe" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/SitesUsers/List/">Мои сайты</a></li>
        </ul>
    </li>
    <li> <span>Сайты</span>
        <ul>
            <li class="yes" id="listSites"><a href="<?=WEB_PREFIX?>AutoSubmitter/Sites/List/">Сконфигурированные</a></li>
            <li class="yes" id="addSites"><a href="<?=WEB_PREFIX?>AutoSubmitter/Sites/Add/">Добавить</a></li>
        </ul>

    </li>

    <li> <span>SE парсинг</span>
        <ul>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Projects/">Проекты</a>
                <b class="opt">
                    <a href="<?=WEB_PREFIX?>SEParsing/Projects/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                </b>
            </li>

            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Sets/">Сеты</a>
                <b class="opt">
                    <a href="<?=WEB_PREFIX?>SEParsing/Keywords/massAdd/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                </b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Thematics/">Тематики</a>
                <b class="opt">
                    <a href="<?=WEB_PREFIX?>SEParsing/Thematics/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                </b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Keywords/">Ключевики</a>
                <b>
                    <a href="<?=WEB_PREFIX?>SEParsing/Keywords/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                    <a href="<?=WEB_PREFIX?>SEParsing/Keywords/massAdd/"><img src="<?=WEB_PREFIX?>Brill/img/mass_add.png" /></a>
                </b>
            </li>
            <li class="sep"></li>

            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/YandexAccesses/">Аккаунты <span class="yandex"><span>Y</span>andex</span></a>
                <b class="opt">
                    <a href="<?=WEB_PREFIX?>SEParsing/YandexAccesses/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                </b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Regions/">Регионы</a>
                <b class="opt">
                    <a href="<?=WEB_PREFIX?>SEParsing/Regions/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                </b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Sets/">Отчет по демонам</a></li>

            <li> <span>Демона</span>
                <ul>
                    <li class="yes">KeywordsYandexXML</li>
                    <li class="yes">KeywordsDotYandexXML</li>
                    <li class="yes">FullPagesYandexXML</li>
                    <li class="yes">KeywordsGoogle</li>
                    <li class="yes">KeywordsRambler</li>
                    <li class="yes">KeywordsWordstat</li>
                </ul>

            </li>
        </ul>
    </li>

    <li> <span>Настройки</span>
        <ul>
            <li class="yes"><a href="<?=WEB_PREFIX?>Settings/Interfaces/">Интерфейсы бота</a>
                <b class="opt">
                    <a href="<?=WEB_PREFIX?>Settings/Interfaces/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                    <a href="<?=WEB_PREFIX?>Settings/Interfaces/massAdd/"><img src="<?=WEB_PREFIX?>Brill/img/mass_add.png" /></a>
                </b>
            </li>
            <li class="no"><a href="<?=WEB_PREFIX?>Settings/StatsToday/">Статистика за сегодня</a></li>
            <li class="yes"><a href="<?=WEB_PREFIX?>Settings/Hosts/">Источники</a></li>
            <li class="yes"><a href="<?=WEB_PREFIX?>Settings/Limits/">Ограничения</a></li>
            <li class="no"><a href="<?=WEB_PREFIX?>Settings/Errors/">Ошибки</a></li>
        </ul>
    </li>
</ul>