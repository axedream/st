<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Страница 1', 'icon' => 'flag', 'url' => ['/page/1']],
                    ['label' => 'Страница 2', 'icon' => 'user', 'url' => ['/page/2']],
                    ['label' => 'Страница 3', 'icon' => 'asterisk', 'url' => ['/page/3']],
                ]
            ]
        )?>

    </section>

</aside>
