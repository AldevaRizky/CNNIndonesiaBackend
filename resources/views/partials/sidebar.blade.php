@php
    $menus = [
        [
            'type' => 'menu',
            'name' => 'Dashboard',
            'url' => route('admin.dashboard'),
            'icon' => 'bx bx-home',
            'roles' => ['admin'],
        ],
        [
            'type' => 'header',
            'name' => 'Apps & Pages',
            'roles' => ['admin'],
        ],
        // [
        //     'type' => 'menu',
        //     'name' => 'Layout',
        //     'url' => 'javascript:void(0);',
        //     'icon' => 'bx bx-layout',
        //     'roles' => ['admin'],
        //     'submenu' => [
        //         [
        //             'name' => 'Hero Cover',
        //             'url' => route('admin.heroes.index'),
        //         ],
        //                 [
        //                     'name' => 'Hero Banner',
        //                     'url' => route('admin.hero_banner.index'),
        //                 ],
        //                 [
        //                     'name' => 'Logo',
        //                     'url' => route('admin.logos.index'),
        //                 ],
        //                 [
        //                     'name' => 'Rekap Disparpora',
        //                     'url' => route('admin.rekap_disparpora.index'),
        //                 ],
        //                 [
        //                     'name' => 'Sambutan Kepala Dinas',
        //                     'url' => route('admin.sambutan_kepala_dinas.index'),
        //                 ],
        //                 [
        //                     'name' => 'Visi & Misi',
        //                     'url' => route('admin.visi_misi.index'),
        //                 ],
        //                 [
        //                     'name' => 'Footer',
        //                     'url' => route('admin.footer.index'),
        //                 ],
        //     ],
        // ],
    ];
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo/123.png') }}" alt="Logo" width="40">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2" style="font-size:24px;">DISPARPORA</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menus as $menu)
            @if (in_array(auth()->user()->role, $menu['roles']))
                @if ($menu['type'] === 'header')
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ $menu['name'] }}</span>
                    </li>
                @elseif ($menu['type'] === 'menu')
                    @php
                        $isSubmenuActive =
                            isset($menu['submenu']) &&
                            collect($menu['submenu'])->contains(fn($submenu) => request()->url() === $submenu['url']);
                    @endphp

                    <li
                        class="menu-item {{ request()->url() === $menu['url'] || $isSubmenuActive ? 'active open' : '' }}">
                        <a href="{{ $menu['url'] }}"
                            class="menu-link {{ isset($menu['submenu']) ? 'menu-toggle' : '' }}">
                            <i class="menu-icon tf-icons {{ $menu['icon'] }}"></i>
                            <div class="text-truncate">{{ $menu['name'] }}</div>
                            @isset($menu['submenu'])
                                <span class="badge rounded-pill bg-danger ms-auto">{{ count($menu['submenu']) }}</span>
                            @endisset
                        </a>

                        @isset($menu['submenu'])
                            <ul class="menu-sub">
                                @foreach ($menu['submenu'] as $submenu)
                                    <li class="menu-item {{ request()->url() === $submenu['url'] ? 'active' : '' }}">
                                        <a href="{{ $submenu['url'] }}" class="menu-link">
                                            <div class="text-truncate">{{ $submenu['name'] }}</div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endisset
                    </li>
                @endif
            @endif
        @endforeach
    </ul>
</aside>
