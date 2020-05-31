<?php

namespace Wuchienkun\Identicon;

use Wuchienkun\Identicon\Generator\GdGenerator;
use Wuchienkun\Identicon\Generator\GeneratorInterface;
use Exception;

/**
 * @author Benjamin Laugueux <benjamin@yzalis.com>
 */
class Identicon
{
    /**
     * @var GeneratorInterface
     */
    private $generator;

    /**
     * Identicon constructor.
     *
     * @param GeneratorInterface $generator
     * @throws Exception
     */
    public function __construct(GeneratorInterface $generator = null)
    {
        if ($generator === null)
            $this->generator = new GdGenerator();
        else
            $this->generator = $generator;
    }

    /**
     * Set the image generator.
     *
     * @param GeneratorInterface $generator
     *
     * @return $this
     */
    public function setGenerator(GeneratorInterface $generator)
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * Display an Identicon image.
     *
     * @param string       $string
     * @param int          $size
     * @param string|array $color
     * @param string       $backgroundColor
     * @param int          $margin
     */
    public function displayImage($string, $size = 64, $color = null, $backgroundColor = null, $margin = 0)
    {
        header('Content-Type: '.$this->generator->getMimeType());
        echo $this->getImageData($string, $size, $color, $backgroundColor, $margin);
    }

    /**
     * Get an Identicon PNG image data.
     *
     * @param string       $string
     * @param int          $size
     * @param string|array $color
     * @param string       $backgroundColor
     * @param int          $margin
     *
     * @return string
     */
    public function getImageData($string, $size = 64, $color = null, $backgroundColor = null, $margin = 0)
    {
        return $this->generator->getImageBinaryData($string, $size, $color, $backgroundColor, $margin);
    }

    /**
     * Get an Identicon PNG image resource.
     *
     * @param string       $string
     * @param int          $size
     * @param string|array $color
     * @param string       $backgroundColor
     * @param int          $margin
     *
     * @return string
     */
    public function getImageResource($string, $size = 64, $color = null, $backgroundColor = null, $margin = 0)
    {
        return $this->generator->getImageResource($string, $size, $color, $backgroundColor, $margin);
    }

    /**
     * Get an Identicon PNG image data as base 64 encoded.
     *
     * @param string       $string
     * @param int          $size
     * @param string|array $color
     * @param string       $backgroundColor
     * @param int          $margin
     *
     * @return string
     */
    public function getImageDataUri($string, $size = 64, $color = null, $backgroundColor = null, $margin = 0)
    {
        return sprintf('data:%s;base64,%s', $this->generator->getMimeType(), base64_encode($this->getImageData($string, $size, $color, $backgroundColor, $margin)));
    }

	/**
	 * Get the color of the Identicon
     *
     * Returns an array with RGB values of the Identicon's color. Colors may be NULL if no image has been generated
     * so far (e.g., when calling the method on a new Identicon()).
	 *
	 * @return array
	 */
	public function getColor()
    {
		$colors = $this->generator->getColor();

        return [
            "r" => $colors[0],
            "g" => $colors[1],
            "b" => $colors[2]
        ];
	}
}
