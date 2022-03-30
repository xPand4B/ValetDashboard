<?php
    class ValetDashboard
    {
        const VALET_PORT = '';
        const VALET_XDG_HOME = '/.config/valet';
        CONST VALET_OLD_HOME = '/.valet';
        const VALET_CONFIG_FILE = '/config.json';

        const IGNORED_DIRECTORIES = [
            '00-Helper',
            'valet',
        ];

        /**
         * @var string
         */
        public $tld;

        /**
         * @var int
         */
        public $port;

        /**
         * @var array
         */
        public $paths;

        /**
         * @var int
         */
        public $totalSites;

        public function __construct()
        {
            $valetHomePath = $this->getValetHomePath();
            $valetConfig = $this->getValetConfigFileContent($valetHomePath);

            $this->tld = $this->getValetTld($valetConfig);
            $this->port = self::VALET_PORT;
            $this->paths = $this->getSitesFromPaths($valetConfig->paths);
        }

        private function getValetHomePath(): string
        {
            $valet_xdg_home = getenv('HOME') . self::VALET_XDG_HOME;
            $valet_old_home = getenv('HOME') . self::VALET_OLD_HOME;

            return is_dir($valet_xdg_home) ? $valet_xdg_home : $valet_old_home;
        }

        /**
         * @return mixed
         */
        private function getValetConfigFileContent(string $homePath)
        {
            return json_decode(
                file_get_contents($homePath . self::VALET_CONFIG_FILE)
            );
        }

        private function getValetTld($valetConfig): string
        {
            return $valetConfig->tld ?? $valetConfig->domain;
        }

        private function getSitesFromPaths(array $paths): array
        {
            $result = [];

            foreach ($paths as $path) {
                $trimmedPath = str_replace(getenv('HOME'), '~', $path);
                $result[$trimmedPath] = [];

                foreach (scandir($path) as $key => $site) {
                    if ($site == basename(__DIR__)) {
                        continue;
                    }
                    if (!(is_dir("$path/$site") || is_link("$path/$site"))) {
                        continue;
                    }
                    // Skip . directories
                    if ($site[0] === '.') {
                        continue;
                    }
                    // Skip ignored directories
                    if (in_array($site, self::IGNORED_DIRECTORIES)) {
                        continue;
                    }

                    $this->totalSites++;

                    $url = 'http://' . $site . '.' . $this->tld . self::VALET_PORT;
                    $isShopware = strpos(strtolower($site), 'shopware') !== false;
                    $result[$trimmedPath][$site] = compact('url', 'isShopware');
                }
                array_multisort($result[$trimmedPath]);
            }

            return $result;
        }
    }

    $valetDashboard = new ValetDashboard();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Eric Heinzl"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    <link rel="icon" type="image/png" sizes="16x16" href="https://laravel.com/img/favicon/favicon-16x16.png'"/>
    <link rel="icon" type="image/png" sizes="32x32" href="https://laravel.com/img/favicon/favicon-32x32.png"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://laravel.com/img/favicon/favicon.ico"/>

    <link rel="stylesheet" href="tailwind.min.css">

    <!-- Custom styles -->
    <style>
        /* Backgrounds */
        .bg-discord-primary { background-color: #36393f; }
        .bg-discord-secondary { background-color: #2f3136; }
        .bg-discord-tertiary { background-color: #202225; }

        .bg-fab-primary {
            background-color: #5865F2;
            fill: white;
        }
        .bg-fab-primary:hover {
            background-color: white;
            fill: #5865F2;
        }

        .bg-fab-default {
            background-color: #36393f;
            fill: hsl(139,calc(1*47.3%),43.9%);
        }
        .bg-fab-default:hover {
            background-color: hsl(139,calc(1*47.3%),43.9%);
            fill: white;
        }

        .bg-fab-github {
            background-color: black;
            fill: white;
        }
        .bg-fab-github:hover {
            background-color: white;
            fill: black;
        }

        .bg-fab-laravel {
            background-color: #ff2d20;
            fill: white;
        }
        .bg-fab-laravel:hover {
            background-color: white;
            fill: #ff2d20;
        }

        /* Components */
        .card-discord {
            background-color: #2f3136;
            border-left: 4px solid #43A7FF;
        }

        .item-discord {
            background-color: #4f545c;
            color: #f6f6f7;
        }
        .item-discord:hover {
            background-color: #72767d;
            cursor: pointer;
        }

        /* Text */
        .text-discord-header-secondary { color: #b9bbbe; }
        .text-discord { color: #bcc9dc; }
    </style>

    <title>Valet Dashboard</title>
</head>
<body class="bg-discord-primary min-h-screen antialiased">
    <!-- Layout -->
    <div class="flex flex-row">
        <!-- Sidebar -->
        <div class="bg-discord-tertiary min-h-screen w-20 shadow-lg fixed">
            <!-- FABs -->
            <div class="flex flex-col items-center mt-5 space-y-5">
                <!-- Reload window -->
                <div class="justify-start">
                    <button
                        class="p-2 bg-fab-primary text-sm font-bold tracking-wide rounded-lg focus:outline-none cursor-pointer shadow-2xl transition duration-200"
                        onclick="location.reload()"
                        title="Reload window"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                            <path d="M9 12l-4.463 4.969-4.537-4.969h3c0-4.97 4.03-9 9-9 2.395 0 4.565.942 6.179 2.468l-2.004 2.231c-1.081-1.05-2.553-1.699-4.175-1.699-3.309 0-6 2.691-6 6h3zm10.463-4.969l-4.463 4.969h3c0 3.309-2.691 6-6 6-1.623 0-3.094-.65-4.175-1.699l-2.004 2.231c1.613 1.526 3.784 2.468 6.179 2.468 4.97 0 9-4.03 9-9h3l-4.537-4.969z"/>
                        </svg>
                    </button>
                </div>

                <!-- Github Repo -->
                <div>
                    <button
                        class="p-2 bg-fab-github text-white text-sm font-bold tracking-wide rounded-full focus:outline-none cursor-pointer shadow-2xl transition duration-200"
                        onclick="window.open('https://github.com/xPand4B/ValetDashboard')"
                        title="Github Repository"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </button>
                </div>

                <!-- Laravel Valet -->
                <div>
                    <button
                        class="p-2 bg-fab-laravel text-white text-sm font-bold tracking-wide rounded-full focus:outline-none cursor-pointer shadow-2xl transition duration-200"
                        onclick="window.open('https://laravel.com/docs/valet')"
                        title="Laravel Valet"
                    >
                        <svg viewBox="0 -.11376601 49.74245785 51.31690859" width="40" height="40" xmlns="http://www.w3.org/2000/svg">
                            <path d="m49.626 11.564a.809.809 0 0 1 .028.209v10.972a.8.8 0 0 1 -.402.694l-9.209 5.302v10.509c0 .286-.152.55-.4.694l-19.223 11.066c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 0 1 -.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054l-19.219-11.066a.801.801 0 0 1 -.402-.694v-32.916c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809 0 0 1 .028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801 0 0 1 .8 0l9.61 5.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124l8.01-4.611zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216zm-36.84-31.068v31.068l17.618 10.143v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-21.483l-4.645-2.676-3.363-1.934zm8.81-5.994-8.007 4.609 8.005 4.609 8.006-4.61-8.006-4.608zm4.164 28.764 4.645-2.674v-20.096l-3.363 1.936-4.646 2.675v20.096zm24.667-23.325-8.006 4.609 8.006 4.609 8.005-4.61zm-.801 10.605-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937zm-18.422 20.561 11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833z"/>
                        </svg>
                    </button>
                </div>

                <!-- Request Feature -->
                <div class="justify-start">
                    <button
                        class="p-2 bg-fab-default text-sm font-bold tracking-wide rounded-full focus:outline-none cursor-pointer shadow-2xl transition duration-200"
                        onclick="window.open('https://github.com/xPand4B/ValetDashboard/issues/new?assignees=xPand4B&labels=enhancement&template=feature_request.yml&title=%5BFeature%5D%3A+')"
                        title="Request feature"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                            <path d="M22 8.51v1.372h-2.538c.02-.223.038-.448.038-.681 0-.237-.017-.464-.035-.69h2.535zm-10.648-6.553v-1.957h1.371v1.964c-.242-.022-.484-.035-.726-.035-.215 0-.43.01-.645.028zm5.521 1.544l1.57-1.743 1.019.918-1.603 1.777c-.25-.297-.593-.672-.986-.952zm-10.738.952l-1.603-1.777 1.019-.918 1.57 1.743c-.392.28-.736.655-.986.952zm-1.597 5.429h-2.538v-1.372h2.535c-.018.226-.035.454-.035.691 0 .233.018.458.038.681zm9.462 9.118h-4c-.276 0-.5.224-.5.5s.224.5.5.5h4c.276 0 .5-.224.5-.5s-.224-.5-.5-.5zm0 2h-4c-.276 0-.5.224-.5.5s.224.5.5.5h4c.276 0 .5-.224.5-.5s-.224-.5-.5-.5zm.25 2h-4.5l1.188.782c.154.138.38.218.615.218h.895c.234 0 .461-.08.615-.218l1.187-.782zm3.75-13.799c0 3.569-3.214 5.983-3.214 8.799h-1.989c-.003-1.858.87-3.389 1.721-4.867.761-1.325 1.482-2.577 1.482-3.932 0-2.592-2.075-3.772-4.003-3.772-1.925 0-3.997 1.18-3.997 3.772 0 1.355.721 2.607 1.482 3.932.851 1.478 1.725 3.009 1.72 4.867h-1.988c0-2.816-3.214-5.23-3.214-8.799 0-3.723 2.998-5.772 5.997-5.772 3.001 0 6.003 2.051 6.003 5.772z"/>
                        </svg>
                    </button>
                </div>

                <!-- Report Bug -->
                <div class="justify-start">
                    <button
                        class="p-2 bg-fab-default text-sm font-bold tracking-wide rounded-full focus:outline-none cursor-pointer shadow-2xl transition duration-200"
                        onclick="window.open('https://github.com/xPand4B/ValetDashboard/issues/new?assignees=xPand4B&labels=bug&template=bug_report.yml&title=%5BBug%5D%3A+')"
                        title="Report Bug"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                            <path d="M7.074 1.408c0-.778.641-1.408 1.431-1.408.942 0 1.626.883 1.38 1.776-.093.336-.042.695.138.995.401.664 1.084 1.073 1.977 1.078.88-.004 1.572-.408 1.977-1.078.181-.299.231-.658.138-.995-.246-.892.436-1.776 1.38-1.776.79 0 1.431.63 1.431 1.408 0 .675-.482 1.234-1.118 1.375-.322.071-.6.269-.769.548-.613 1.017.193 1.917.93 2.823-1.21.562-2.524.846-3.969.846-1.468 0-2.771-.277-3.975-.84.748-.92 1.555-1.803.935-2.83-.168-.279-.446-.477-.768-.548-.636-.14-1.118-.699-1.118-1.374zm13.485 14.044h2.387c.583 0 1.054-.464 1.054-1.037s-.472-1.037-1.054-1.037h-2.402c-.575 0-1.137-.393-1.227-1.052-.092-.677.286-1.147.765-1.333l2.231-.866c.541-.21.807-.813.594-1.346-.214-.533-.826-.795-1.367-.584l-2.294.891c-.329.127-.734.036-.926-.401-.185-.423-.396-.816-.62-1.188-1.714.991-3.62 1.501-5.7 1.501-2.113 0-3.995-.498-5.703-1.496-.217.359-.421.738-.601 1.146-.227.514-.646.552-.941.437l-2.295-.89c-.542-.21-1.153.051-1.367.584-.213.533.053 1.136.594 1.346l2.231.866c.496.192.854.694.773 1.274-.106.758-.683 1.111-1.235 1.111h-2.402c-.582 0-1.054.464-1.054 1.037s.472 1.037 1.054 1.037h2.387c.573 0 1.159.372 1.265 1.057.112.728-.228 1.229-.751 1.462l-2.42 1.078c-.53.236-.766.851-.526 1.373s.865.753 1.395.518l2.561-1.14c.307-.137.688-.106.901.259 1.043 1.795 3.143 3.608 6.134 3.941 2.933-.327 5.008-2.076 6.073-3.837.261-.432.628-.514.963-.364l2.561 1.14c.529.236 1.154.005 1.395-.518.24-.522.004-1.137-.526-1.373l-2.42-1.078c-.495-.221-.867-.738-.763-1.383.128-.803.717-1.135 1.276-1.135z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="w-full ml-20">
            <!-- Topbar -->
            <div class="bg-discord-primary py-3 pl-8 shadow-lg flex flex-row fixed w-screen select-none">
                <h3 class="text-white font-bold">Valet Dashboard</h3>
                <div class="text-discord-header-secondary px-4">|</div>
                <div class="text-discord-header-secondary font-medium italic">PHP: <?=phpversion();?></div>
                <div class="text-discord-header-secondary px-4">|</div>
                <div class="text-discord-header-secondary font-medium italic">Sites: <?=$valetDashboard->totalSites?></div>
            </div>

            <!-- Sites -->
            <div class="bg-discord-primary font-sans mx-auto">
                <div class="flex flex-wrap justify-evenly mt-10">
                    <?php foreach($valetDashboard->paths as $path => $sites): ?>
                        <div class="flex flex-col card-discord p-5 rounded mt-10 shadow">
                            <div
                                id="path-<?=array_search($path, array_keys($valetDashboard->paths), true)?>"
                                class="pb-4 font-mono font-bold text-white"
                            >
                                <?=$path?>
                            </div>

                            <!-- Sites found -->
                            <?php if(count($sites) !== 0): ?>
                                <div class="flex flex-col select-none">
                                    <?php foreach($sites as $site => $info): ?>
                                        <div class="flex flex-row items-center mb-2">
                                            <!-- Site Link -->
                                            <div
                                                class="py-2 px-5 flex-auto text-center font-normal item-discord transition duration-150 rounded-l <?=$info['isShopware'] ? null : 'rounded-r' ?>"
                                                onclick="window.open('<?=$info['url']?>')"
                                            >
                                                <?=$site?>
                                            </div>

                                            <!-- Admin Button -->
                                            <?php if($info['isShopware']): ?>
                                                <div
                                                    class="p-2 flex w-16 justify-center text-discord bg-indigo-800 hover:bg-indigo-900 italic rounded-r font-semibold cursor-pointer transition duration-150"
                                                    onclick="window.open('<?=$info['url']?>/admin')"
                                                >
                                                    Admin
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- No Sites Found -->
                            <?php else: ?>
                                <div
                                    class="flex items-center bg-indigo-700 text-white text-sm font-bold p-2 rounded italic"
                                    role="alert"
                                >
                                    <svg
                                        class="fill-current w-4 h-4 mr-2"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
                                    </svg>
                                    <p>No Sites Found.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
