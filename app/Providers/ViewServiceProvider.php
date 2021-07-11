<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->shareValetInfos();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Share valet-infos into view
     */
    private function shareValetInfos(): void
    {
        $valetHomePath = $this->getValetHomePath();
        $valetConfig = $this->getValetConfigFileContent($valetHomePath);
        $tld = $this->getValetTld($valetConfig);

        $valetInfos = [
            'tld' => $tld,
            'port' => self::VALET_PORT,
            'paths' => $this->getSitesFromPaths($valetConfig->paths, $tld)
        ];

        view()->share('valet', $valetInfos);
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

    private function getSitesFromPaths(array $paths, string $tld): array
    {
        $result = [];

        foreach ($paths as $path) {
            $trimmedPath = str_replace(getenv('HOME'), '~', $path);
            $result[$trimmedPath] = [];

            foreach (scandir($path) as $key => $site) {
                if (mb_strtolower($site) === 'valetdashboard') {
                    continue;
                }
                if ($site == basename(__DIR__)) {
                    continue;
                }
                if (!(is_dir("$path/$site") || is_link("$path/$site"))) {
                    continue;
                }
                if ($site[0] === '.') {
                    continue;
                }
                if (in_array($site, self::IGNORED_DIRECTORIES)) {
                    continue;
                }

                if ($site === 'cloud.shopware') {
                    $tld .= '/admin';
                }

                $url = 'http://' . $site . '.' . $tld . self::VALET_PORT;

                if ($site === 'cloud.shopware') {
                    $tld = str_replace('/admin', '', $tld);
                }


                // if ($site === 'swDashboard') {
                //     $url = 'http://shopware.dashboard';
                // }

                $result[$trimmedPath][$site] = $url;
            }

            array_multisort($result[$trimmedPath]);
        }

        return $result;
    }

    private function getValetTld($valetConfig): string
    {
        return $valetConfig->tld ?? $valetConfig->domain;
    }
}
