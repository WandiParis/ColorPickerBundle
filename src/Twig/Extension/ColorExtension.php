<?php

namespace Wandi\ColorPickerBundle\Twig\Extension;

use SSNepenthe\ColorUtils\Colors\ColorFactory;
use SSNepenthe\ColorUtils\Colors\Color;
use SSNepenthe\ColorUtils\Colors\Hsl;
use SSNepenthe\ColorUtils\Colors\Rgb;
use Twig\Extension\AbstractExtension;
use SSNepenthe\ColorUtils\Exceptions\InvalidArgumentException as SSInvalidArgumentException;
use Twig\TwigFilter;
use InvalidArgumentException;

class ColorExtension extends AbstractExtension
{
    const COLOR_HEX = 'hex';
    const COLOR_RGB = 'rgb';
    const COLOR_HSL = 'hsl';
    const COLOR_FORMATS = [
        self::COLOR_HEX,
        self::COLOR_RGB,
        self::COLOR_HSL,
    ];
    const BRIGHTNESS_LIGHT = 'light';
    const BRIGHTNESS_DARK = 'dark';

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('wandi_color_picker_convert', [$this, 'convert']),
            new TwigFilter('wandi_color_picker_get_brightness', [$this, 'getBrightness']),
        ];
    }

    /**
     * Convert a color from rgb, rgba, hsl, hsla, hex to rgb, hsl or hex
     *
     * @param string      $color
     * @param string      $format
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function convert(string $color, string $format): string
    {
        if (!in_array($format, self::COLOR_FORMATS)){
            throw new InvalidArgumentException(sprintf(
                "%s must be one of the following formats: %s in %s",
                '%format',
                implode(",", self::COLOR_FORMATS),
                __METHOD__
            ));
        }
        try {
            /** @var Color $colorO */
            $colorO = ColorFactory::fromString($color);
        }
        catch (SSInvalidArgumentException $e){
            throw new InvalidArgumentException(sprintf(
                'String "%s" not supported in %s',
                $color,
                __METHOD__
            ));
        }

        switch ($format){
            case self::COLOR_HEX:
                return strtoupper($colorO->toHexString());
            case self::COLOR_RGB:
                return $colorO->getRepresentation(Rgb::class)->toString();
            case self::COLOR_HSL:
                return $colorO->getRepresentation(Hsl::class)->toString();
            default:
                return $color;
        }
    }

    /**
     * @param string $color
     * @return string
     */
    public function getBrightness(string $color): string
    {
        try {
            /** @var Color $colorO */
            $colorO = ColorFactory::fromString($color);
        }
        catch (SSInvalidArgumentException $e){
            return self::BRIGHTNESS_DARK;
        }

        /** @var Rgb $rgb */
        $rgb = $colorO->getRepresentation(Rgb::class);
        return $rgb->calculateBrightness() <= 127.5 && $rgb->getAlpha() > 0.4 ? self::BRIGHTNESS_DARK : self::BRIGHTNESS_LIGHT;
    }
}
