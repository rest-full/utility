<?php

namespace Restfull\Utility;

use Restfull\Filesystem\File;
use Restfull\Http\Request;
use Restfull\Error\Exceptions;
use Restfull\Container\Instances;

class Icons
{

    /**
     * @var array
     */
    private $sizes = [
        'android' => [
            '196x196', // Android Chrome (M31+)
            '96x96', // GoogleTV icon
            '32x32', // New tab page in IE, taskbar button in Win 7+, Safari Reading List sidebar
            '16x16', // The interweb standard for (almost) every browser
            '128x128' // Chrome Web Store app icon &amp; Android icon (lo-res)
        ],
        'ios' => [
            '57x57', // Standard iOS home screen (iPod Touch, iPhone first generation to 3G)
            '114x114', // iPhone retina touch icon (iOS6 or prior)
            '72x72', // iPad touch icon (non-retina - iOS6 or prior)
            '144x144', // iPad retina (iOS6 or prior)
            '60x60', // iPhone touch icon (non-retina - iOS7)
            '120x120', // iPhone retina touch icon (iOS7)
            '76x76', // iPad touch icon (non-retina - iOS7)
            '152x152' // iPad retina touch icon (iOS7)
        ],
        'msdos' => [
            '70x70', // Win 8.1 Metro tile image (small)
            '150x150', // Win 8.1 Metro tile image (square)
            '310x310'
        ]
    ];

    /**
     * @var array
     */
    private $imageMime = ['image/jpeg' => 'jpg', 'image/jpeg' => 'jpeg', 'image/png' => 'png'];

    /**
     * @var array
     */
    private $images = [];

    /**
     * @param string $icon
     * @throws Exceptions
     */
    public function __construct(string $icon)
    {
        $icon = ROOT_PATH . $icon;
        $exists = [];
        $instance = new Instances();
        foreach ($this->sizes as $key => $sizes) {
            for ($a = 0; $a < count($sizes); $a++) {
                $file = $instance->resolveClass(
                        $instance->assemblyClassOrPath(
                                '%s' . DS_REVERSE . 'Filesystem' . DS_REVERSE . 'Image',
                                [ROOT_NAMESPACE]
                        ),
                        ['file' => $icon]
                );
                $this->images[$key][] = $file;
            }
        }
        return $this;
    }

    public function addIco(): array
    {
        foreach ($this->images as $key => $image) {
            for ($a = 0; $a < count($image); $a++) {
                $file = pathinfo($image[$a]->pathinfo());
                $file['basename'] = substr(
                                $file['basename'],
                                0,
                                stripos($file['basename'], '.')
                        ) . '_' . $this->sizes[$key][$a] . '.' . $file['extension'];
                if (!$image[$a]->exists($file['dirname'] . DS . $file['basename'])) {
                    $image[$a]->createDifferentSizes($this->sizes[$key][$a]);
                }
                $names[] = $file['dirname'] . DS . $file['basename'];
            }
        }
        return $names;
    }

    /**
     * @param array $files
     * @return array
     */
    public function typeOptions(string $file)
    {
        $options = [];
        $size = substr(
                substr($file, stripos($file, '_') + 1),
                0,
                stripos(substr($file, stripos($file, '_') + 1), '.')
        );
        if (in_array(substr($size, 0, stripos($size, 'x')), ['144', '150', '310', '70']) !== false) {
            $name = ['square70x70logo', 'square150x150logo', 'square310x310logo'];
            $options = [
                'name' => 'msapplication-' . $name[array_search($size, $this->sizes['msdos'])],
                'content' => $file
            ];
        } else {
            if (in_array(
                            substr($size, 0, stripos($size, 'x')),
                            ['196', '96', '32', '16', '64', '128']
                    ) !== false) {
                $options = [
                    'rel' => 'icon',
                    'size' => $size,
                    'type' => array_search(pathinfo($file)['extension'], $this->imageMime),
                    'url' => $file
                ];
            } else {
                $options = ['rel' => 'apple-touch-icon', 'size' => $size, 'url' => $file];
            }
        }
        return $options;
    }

}
