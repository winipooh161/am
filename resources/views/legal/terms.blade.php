@extends('layouts.app')

@section('meta_tags')
    <title>Пользовательское соглашение | {{ config('app.name') }}</title>
    <meta name="description" content="Пользовательское соглашение сайта {{ config('app.name') }}. Правила использования сервиса и размещения контента.">
    <link rel="canonical" href="{{ route('legal.terms') }}" />
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-lg-5">
                    <h1 class="mb-4">Пользовательское соглашение</h1>
                    
                    <p class="text-muted">Последнее обновление: {{ date('d.m.Y') }}</p>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">1. Общие положения</h2>
                        <p>Настоящее Пользовательское соглашение (далее — «Соглашение») регулирует отношения между {{ config('app.name') }} (далее — «Администрация») и пользователем сети Интернет (далее — «Пользователь») по использованию сайта {{ config('app.name') }} (далее — «Сайт»).</p>
                        <p>Используя Сайт, Пользователь подтверждает, что ознакомился с условиями настоящего Соглашения и принимает их в полном объеме.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">2. Предмет соглашения</h2>
                        <p>Администрация предоставляет Пользователю доступ к использованию Сайта и его функционала на условиях, предусмотренных настоящим Соглашением.</p>
                        <p>Сайт представляет собой платформу для обмена кулинарными рецептами, где Пользователи могут публиковать, просматривать и комментировать рецепты, а также использовать другие функции, доступные на Сайте.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">3. Регистрация на Сайте</h2>
                        <p>Для получения доступа к некоторым функциям Сайта Пользователю необходимо пройти процедуру регистрации, в результате которой для Пользователя будет создана уникальная учетная запись.</p>
                        <p>При регистрации Пользователь обязуется предоставить достоверную информацию о себе, необходимую для использования Сайта.</p>
                        <p>Пользователь несет ответственность за безопасность своего логина и пароля, а также за все действия, совершенные под его учетной записью.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">4. Правила публикации контента</h2>
                        <p>Пользователь вправе размещать на Сайте рецепты, комментарии, изображения и другой контент при условии соблюдения настоящего Соглашения и действующего законодательства.</p>
                        <p>Размещая контент на Сайте, Пользователь гарантирует, что:</p>
                        <ul>
                            <li>Является автором данного контента или имеет разрешение на его публикацию;</li>
                            <li>Контент не нарушает права третьих лиц;</li>
                            <li>Контент не содержит информации, запрещенной законодательством;</li>
                            <li>Контент не содержит рекламы без согласования с Администрацией;</li>
                            <li>Контент не содержит вредоносных программ или кодов;</li>
                            <li>Контент не нарушает нормы морали и этики.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">5. Права на контент</h2>
                        <p>Пользователь сохраняет все права на размещаемый им на Сайте контент.</p>
                        <p>Публикуя контент на Сайте, Пользователь предоставляет Администрации неисключительное право использовать его следующими способами:</p>
                        <ul>
                            <li>Воспроизводить контент на Сайте;</li>
                            <li>Распространять контент на Сайте;</li>
                            <li>Доводить контент до всеобщего сведения;</li>
                            <li>Редактировать, переводить и модифицировать контент;</li>
                            <li>Использовать контент для продвижения Сайта.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">6. Ограничения и запреты</h2>
                        <p>Пользователю запрещается:</p>
                        <ul>
                            <li>Использовать Сайт способами, не предусмотренными настоящим Соглашением;</li>
                            <li>Собирать данные пользователей и использовать их в коммерческих целях;</li>
                            <li>Распространять спам, вирусы и вредоносные программы;</li>
                            <li>Размещать ложную или недостоверную информацию;</li>
                            <li>Выдавать себя за другого человека или представителя организации;</li>
                            <li>Нарушать работу Сайта и его сервисов;</li>
                            <li>Использовать автоматические скрипты для сбора информации с Сайта;</li>
                            <li>Осуществлять иные действия, которые могут нанести вред Сайту и его пользователям.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">7. Ответственность сторон</h2>
                        <p>Администрация не несет ответственности за:</p>
                        <ul>
                            <li>Действия Пользователей;</li>
                            <li>Контент, размещаемый Пользователями;</li>
                            <li>Потерю данных Пользователя;</li>
                            <li>Технические сбои в работе Сайта;</li>
                            <li>Убытки, возникшие у Пользователя в результате использования Сайта.</li>
                        </ul>
                        <p>Пользователь несет ответственность за:</p>
                        <ul>
                            <li>Соблюдение законодательства;</li>
                            <li>Содержание размещаемого им контента;</li>
                            <li>Сохранность своей учетной записи;</li>
                            <li>Любые действия, совершенные с использованием его учетной записи.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">8. Изменение и расторжение Соглашения</h2>
                        <p>Администрация вправе изменять условия настоящего Соглашения без предварительного уведомления Пользователя. Новая редакция Соглашения вступает в силу с момента ее размещения на Сайте.</p>
                        <p>Пользователь вправе расторгнуть настоящее Соглашение путем удаления своей учетной записи.</p>
                        <p>Администрация вправе заблокировать или удалить учетную запись Пользователя и прекратить доступ к Сайту без объяснения причин в случае нарушения Пользователем условий настоящего Соглашения.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">9. Заключительные положения</h2>
                        <p>Настоящее Соглашение регулируется и толкуется в соответствии с законодательством Российской Федерации.</p>
                        <p>Если по тем или иным причинам одно или несколько положений настоящего Соглашения будут признаны недействительными или не имеющими юридической силы, это не оказывает влияния на действительность или применимость остальных положений Соглашения.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">10. Контактная информация</h2>
                        <p>Для связи с Администрацией по вопросам, связанным с использованием Сайта, пожалуйста, используйте следующие контактные данные:</p>
                        <p>Email: <a href="mailto:w1nishko@yandex.ru">w1nishko@yandex.ru</a><br>
                        Телефон: <a href="tel:+79044482283">+7 904 448-22-83</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
