<nav class='navigator align-flex-between m-inline-2'>
    <div>
        <x-fragments.item-list ulClass='nav_list text-fs-100' liClass='nav_item hover' :list="[
            ['element' => 'Home',           'url' => '#'],
            ['element' => 'Products',       'url' => '#'],
            ['element' => 'Appointment',    'url' => '#'],
            ['element' => 'Guides',         'url' => '#'],
            ['element' => 'Contacts',       'url' => '#'],
        ]">
        </x-fragments.item-list>
    </div>
    <div>
        <x-fragments.item-list ulClass='ico_list m-inline-2' liClass='ico_item' :list="[
            ['element' => '<i class=\'fa-solid fa-magnifying-glass\'></i>', 'url' => '#'],
            ['element' => '<i class=\'fa-regular fa-heart\'></i>',          'url' => '#'],
            ['element' => '<i class=\'fa-regular fa-user\'></i>',           'url' => '#'],
        ]">
        </x-fragments.item-list>
    </div>
</nav>
