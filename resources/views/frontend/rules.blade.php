@extends('layouts.frontend')

@section('title', $title)
@section('description', '')
@section('keywords', '')


@section('css')

@endsection

@section('content')

    <h1>{{ $title }}</h1>


    <p>Правила каталога сайтов. Перед добавлением сайта в каталог и в процессе работы необходимо руководствоваться
        настоящими правилами, описанными ниже.</p>

    <ol>
        <li>Общие положения
            <ol>
                <li>sssv.ru обязуется предоставлять возможность добавления сайтов в каталог.</li>
                <li>sssv.ru имеет право исключать из каталога сайты, а также изменять данные сайтов с целью
                    предотвращения случаев предоставления пользователями недостоверной информации о сайте или наличия
                    недостоверного или нарушающего действующее законодательство Российской Федерации, прав и законных
                    интересов третьих лиц, а также нормы морали и нравственности содержания сайта.
                </li>
                <li>sssv.ru оставляет за собой право в одностороннем порядке прекратить предоставление услуг без
                    указания причин.
                </li>
                <li>sssv.ru не несет ответственности:
                    <ol>
                        <li>за соответствие содержания сайтов действующему законодательству, нормам морали и
                            нравственности, а также сетевым аппаратно-программным стандартам и протоколам;
                        </li>
                        <li>за содержание и достоверность сайтов;</li>
                        <li>за работоспособность сайтов;</li>
                        <li>за временные сбои и перерывы в работе каталога;</li>
                        <li>за неисполнение обязательств в силу форс-мажорных обстоятельств. Под форс-мажорными
                            обстоятельствами понимаются обстоятельства непреодолимой силы, неподконтрольные каталогу
                            сайтов sssv.ru, в том числе: война, забастовка, восстание, любые другие социальные конфликты
                            и волнения; действия органов государственной власти, неблагоприятные погодные условия,
                            пожар, наводнение, землетрясение и прочие стихийные бедствия; отсутствие электроэнергии,
                            неполадки в электросети, действие вредоносных программ, а также искажение информации на
                            сайте в результате деятельности третьих лиц и любые иные подобные события, наступление
                            которых нельзя предвидеть и предотвратить разумными мерами;
                        </li>
                        <li>за возможное несоответствие любой информации, размещенной в каталоге сайтов, общепринятым
                            стандартам или ожиданиям Пользователя;
                        </li>
                        <li>за любой прямой или косвенный ущерб, упущенную выгоду, даже если это стало результатом
                            использования или невозможности использования sssv.ru;
                        </li>
                        <li>за способы и последствия использования третьими лицами информации, размещенной в sssv.ru;
                        </li>
                        <li>за надежность, качество и скорость работы sssv.ru и сохранность размещенной информации.</li>
                    </ol>
                </li>
                <li>sssv.ru не производит проверку правосубъектности лиц, зарегистрировавших сайты в каталоге.</li>
                <li>sssv.ru имеет право осуществлять рекламную деятельность.</li>
                <li>sssv.ru имеет право направлять по электронной почте пользователям информацию, имеющую отношение к
                    сайту.
                </li>
            </ol>
        </li>
        <li>Права и обязанности пользователей каталога сайтов
            <ol>
                <li>Пользователь несет ответственность за достоверность и соответствие законодательству Российской
                    Федерации содержания сайта, а также за нарушение прав и законных интересов третьих лиц, связанное с
                    содержанием сайтов.
                </li>
                <li>Пользователь имеет право предложить администрации sssv.ru варианты их изменения, отправив заявку в
                    администрацию sssv.ru.
                </li>
                <li>Пользователь имеет право удалить сайт из каталога.</li>
                <li>Пользователь имеет право запросить разъяснения от администрации, касающиеся условий предоставления
                    услуг каталога
                </li>
            </ol>
        </li>
        <li>Добавление сайта
            <ol>
                <li>Любой пользователь может добавить сайт в каталог.</li>
                <li>Для добавления сайта в каталог пользователь должен заполнить поля &ldquo;Адрес сайта&rdquo;, &ldquo;Название&rdquo;,
                    выбрать &ldquo;Категорию&rdquo;, указать электронную почту &rdquo;Email&rdquo; и добавить &ldquo;Описание.
                </li>
                <li>Пользователь не может самостоятельно изменять указанные данные.</li>
            </ol>
        </li>
        <li>Отказ в предоставлении услуг Сервиса
            <ol>
                <li>Отказ в предоставлении услуг осуществляется по любой из следующих причин.</li>
                <li>Содержание сайта нарушает законодательство Российской Федерации, права и законные интересы третьих
                    лиц.
                </li>
                <li>Содержание сайта наносит вред здоровью, нравственному и духовному развитию несовершеннолетних либо
                    нарушает общепринятые нормы морали.
                </li>
                <li>На страницах сайта отображаются порнографические материалы. Под порнографией понимается
                    детализированное, натуралистическое изображение, словесное описание или демонстрация полового акта,
                    половых органов, имеющие целью сексуальное возбуждение.
                </li>
                <li>Сайт дублирует содержание других сайтов. При этом дублированием считается не только прямое и
                    буквальное совпадение представленной информации, но также и общее совпадение по смыслу, без наличия
                    на дублирующем сайте принципиально новой информации.
                </li>
                <li>Более 70% площади, занятой содержанием, на любой из страниц сайта занимает реклама или ссылки на
                    сайты, не относящиеся к сайту или предоставляемым на сайте сервисам.
                </li>
                <li>Ресурс не соответствует сетевым аппаратно-программным стандартам и протоколам, либо его просмотр
                    ведёт к нарушению работоспособности программного обеспечения сайта или пользователей.
                </li>
                <li>Пользователь нарушает действующее законодательство РФ, права или законные интересы третьих лиц,
                    условия настоящего Регламента.
                </li>
            </ol>
        </li>
        <li>Поддержка Пользователей каталога сайтов sssv.ru
            <ol>
                <li>По всем вопросам, связанным с sssv.ru, информационная поддержка производится с помощью связи с
                    администрацией.
                </li>
                <li>Претензии, связанные с использованием sssv.ru, принимаются по электронной почте через форму обратной
                    связи с администрацией.
                </li>
                <li>Не рассматриваются анонимные или не конкретные претензии.</li>
            </ol>
        </li>
    </ol>


@endsection

@section('js')



@endsection