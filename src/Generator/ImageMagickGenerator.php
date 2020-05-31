<?php

namespace Wuchienkun\Identicon\Generator;

use Exception;
use ImagickDraw;
use ImagickPixel;

/**
 * @author Francis Chuang <francis.chuang@gmail.com>
 */
class ImageMagickGenerator extends BaseGenerator implements GeneratorInterface
{
    /**
     * ImageMagickGenerator constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        if (!extension_loaded('imagick')) {
            throw new Exception('imagick 拓展未启用，请确保安装并启用了 imagick 拓展。');
        }

        try {
            $generator = new \Imagick();
            $generator->newImage(1,1,'#fff','png');
        } catch (Exception $e) {
            throw new Exception('ImageMagick 似乎无法工作，请确保安装了 ImageMagick，或者使用另一种 Generator。');
        }
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return 'image/png';
    }

    /**
     * @return $this
     * @throws \ImagickException
     */
    private function generateImage()
    {
        $this->generatedImage = new \Imagick();
        $rgbBackgroundColor = $this->getBackgroundColor();

        if (null === $rgbBackgroundColor) {
            $background = 'none';
        } else {
            $background = new ImagickPixel("rgb($rgbBackgroundColor[0],$rgbBackgroundColor[1],$rgbBackgroundColor[2])");
        }

        $this->generatedImage->newImage($this->pixelRatio * 5 + $this->getMargin() * 2, $this->pixelRatio * 5 + $this->getMargin() * 2, $background, 'png');

        // prepare color
        $rgbColor = $this->getColor();
        $color = new ImagickPixel("rgb($rgbColor[0],$rgbColor[1],$rgbColor[2])");

        $draw = new ImagickDraw();
        $draw->setFillColor($color);

        // draw the content
        foreach ($this->getArrayOfSquare() as $lineKey => $lineValue) {
            foreach ($lineValue as $colKey => $colValue) {
                if (true === $colValue && 5 > $lineKey) {
                    $draw->rectangle(
                        $this->getMargin() + $colKey * $this->pixelRatio,
                        $this->getMargin() + $lineKey * $this->pixelRatio,
                        $this->getMargin() + ($colKey + 1) * $this->pixelRatio - 1,
                        $this->getMargin() + ($lineKey + 1) * $this->pixelRatio - 1
                    );
                }
            }
        }

        $this->generatedImage->drawImage($draw);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getImageBinaryData($string, $size = null, $color = null, $backgroundColor = null, $margin = null)
    {
        ob_start();
        echo $this->getImageResource($string, $size, $color, $backgroundColor, $margin);
        $imageData = ob_get_contents();
        ob_end_clean();

        return $imageData;
    }

    /**
     * {@inheritdoc}
     */
    public function getImageResource($string, $size = null, $color = null, $backgroundColor = null, $margin = null)
    {
        $this
            ->setString($string)
            ->setSize($size)
            ->setColor($color)
            ->setBackgroundColor($backgroundColor)
            ->setMargin($margin)
            ->generateImage();

        return $this->generatedImage;
    }
}
