<?php
namespace LaravelPWA\Services;

class ManifestService
{
    public function generate()
    {
        $basicManifest =  [
            'name' => config('laravelpwa.manifest.name'),
            'short_name' => config('laravelpwa.manifest.short_name'),
            'start_url' => asset(config('laravelpwa.manifest.start_url')),
            'display' => config('laravelpwa.manifest.display'),
            'theme_color' => config('laravelpwa.manifest.theme_color'),
            'background_color' => config('laravelpwa.manifest.background_color'),
            'orientation' =>  config('laravelpwa.manifest.orientation'),
            'status_bar' =>  config('laravelpwa.manifest.status_bar'),
            'splash' =>  config('laravelpwa.manifest.splash')
        ];

        foreach (config('laravelpwa.manifest.icons') as $size => $file) {
            $fileInfo = pathinfo($file['path']);
            $icon = [
                'src' => $file['path'],
                'type' => 'image/' . $fileInfo['extension'],
                'sizes' => (isset($file['sizes']))?$file['sizes']:$size,
            ];

            if (isset($file['purpose'])) {
                $icon['purpose'] = $file['purpose'];
            }

            $basicManifest['icons'][] = $icon;
        }

        if (config('laravelpwa.manifest.shortcuts')) {
            foreach (config('laravelpwa.manifest.shortcuts') as $shortcut) {

                if (array_key_exists("icons", $shortcut)) {
                    $fileInfo = pathinfo($shortcut['icons']['src']);
                    $icon = [
                        'src' => $shortcut['icons']['src'],
                        'type' => 'image/' . $fileInfo['extension'],
                        'sizes' => (isset($file['sizes']))?$file['sizes']:$size,
                    ];

                    if(isset($shortcut['icons']['sizes'])) {
                        $icon["sizes"] = $shortcut['icons']['sizes'];
                    }

                    if (isset($shortcut['icons']['purpose'])) {
                        $icon['purpose'] = $shortcut['icons']['purpose'];
                    }
                } else {
                    $icon = [];
                }

                $basicManifest['shortcuts'][] = [
                    'name' => trans($shortcut['name']),
                    'description' => trans($shortcut['description']),
                    'url' => $shortcut['url'],
                    'icons' => [
                        $icon
                    ]
                ];
            }
        }

        foreach (config('laravelpwa.manifest.custom') as $tag => $value) {
             $basicManifest[$tag] = $value;
        }
        return $basicManifest;
    }

}
