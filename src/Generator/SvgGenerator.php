<?php

namespace Wuchienkun\Identicon\Generator;

/**
 * @author Grummfy <grummfy@gmail.com>
 */
class SvgGenerator extends BaseGenerator implements GeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getMimeType()
    {
        return 'image/svg+xml';
    }

    /**
     * {@inheritdoc}
     */
    public function getImageBinaryData($string, $size = null, $color = null, $backgroundColor = null, $margin = null)
    {
        return $this->getImageResource($string, $size, $color, $backgroundColor, $margin);
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
            ->_generateImage();

        return $this->generatedImage;
    }

    /**
     * @return $this
     */
    protected function _generateImage()
    {
        // prepare image
        $w = $this->getPixelRatio() * 5 + $this->getMargin() * 2;
        $h = $this->getPixelRatio() * 5 + $this->getMargin() * 2;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="'.$w.'" height="'.$h.'" viewBox="0 0 '.$w.' '.$h.'">';

        $backgroundColor = '#FFF';
        $rgbBackgroundColor = $this->getBackgroundColor();
        if (!is_null($rgbBackgroundColor)) {
            $backgroundColor = $this->_toUnderstandableColor($rgbBackgroundColor);
        }

        $svg .= '<rect width="'.$w.'" height="'.$h.'" fill="'.$backgroundColor.'" stroke-width="0"/>';

        $rects = [];
        // draw content
        foreach ($this->getArrayOfSquare() as $lineKey => $lineValue) {
            foreach ($lineValue as $colKey => $colValue) {
                if (true === $colValue && 5 > $lineKey) {
                    $rects[] = 'M'.($colKey*$this->getPixelRatio()+$this->getMargin()).','.($lineKey*$this->getPixelRatio()+$this->getMargin()).'h'.$this->getPixelRatio().'v'.$this->getPixelRatio().'h-'.$this->getPixelRatio().'v-'.$this->getPixelRatio();
                }
            }
        }

        $rgbColor = $this->_toUnderstandableColor($this->getColor());
        $svg .= '<path fill="'.$rgbColor.'" stroke-width="0" d="' . implode('', $rects) . '"/>';
        $svg .= '</svg>';

        $this->generatedImage = $svg;

        return $this;
    }

    /**
     * @param array|string $color
     *
     * @return string
     */
    protected function _toUnderstandableColor($color)
    {
        if (is_array($color)) {
            return sprintf('#%02X%02X%02X', $color[0], $color[1], $color[2]);
        }

        return $color;
    }
}
