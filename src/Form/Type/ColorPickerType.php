<?php

namespace Wandi\ColorPickerBundle\Form\Type;

use Wandi\ColorPickerBundle\PickerOptions\Color;
use Wandi\ColorPickerBundle\PickerOptions\Position;
use Wandi\ColorPickerBundle\PickerOptions\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ColorPickerType extends AbstractType
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * ColorType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // erase default color if update && valid color
        if (!empty($view->vars['value']) && 1 === preg_match("/^#([0-9a-fA-F]{8}|[0-9a-fA-F]{6}|[0-9a-fA-F]{4}|[0-9a-fA-F]{3})$/", $view->vars['value'])){
            $options['pickr_options']['default'] = $view->vars['value'];
        }
        // if developer overrode class, append ours
        if (!empty($view->vars['attr']['class'])){
            $view->vars['attr']['class'] .= ' color-picker ' . $view->vars['id'];
        }
        $options['pickr_options']['el'] = '.' . $view->vars['id'];
        $view->vars['pickr_options'] = $options['pickr_options'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'ColorPickerBundle',
            'attr' => [
                'class' => 'color-picker',
            ],
            'pickr_options' => [
                // Selector or element which will be replaced with the actual color-picker.
                // Can be a HTMLElement.
                'el' => '.color-picker',

                // Where the pickr-app should be added as child.
                'container' => 'body',

                // Which theme you want to use. Can be 'classic', 'monolith' or 'nano'
                'theme' => Theme::CLASSIC,

                // Nested scrolling is currently not supported and as this would be really sophisticated to add this
                // it's easier to set this to true which will hide pickr if the user scrolls the area behind it.
                'closeOnScroll' => false,

                // Custom class which gets added to the pcr-app. Can be used to apply custom styles.
                'appClass' => 'custom-class',

                // Don't replace 'el' Element with the pickr-button, instead use 'el' as a button.
                // If true, appendToBody will also be automatically true.
                'useAsButton' => true,

                // If true pickr won't be floating, and instead will append after the in el resolved element.
                // Setting this to true will also set showAlways to true. It's possible to hide it via .hide() anyway.
                'inline' => false,

                // If true, pickr will be repositioned automatically on page scroll or window resize.
                // Can be set to false to make custom positioning easier.
                'autoReposition' => true,

                // Defines the direction in which the knobs of hue and opacity can be moved.
                // 'v' => opacity- and hue-slider can both only moved vertically.
                // 'hv' => opacity-slider can be moved horizontally and hue-slider vertically.
                // Can be used to apply custom layouts
                'sliders' => 'v',

                // Start state. If true 'disabled' will be added to the button's classlist.
                'disabled' => false,

                // If true, the user won't be able to adjust any opacity.
                // Opacity will be locked at 1 and the opacity slider will be removed.
                // The HSVaColor object also doesn't contain an alpha, so the toString() methods just
                // print HSV, HSL, RGB, HEX, etc.
                'lockOpacity' => false,

                // Precision of output string (only effective if components.interaction.input is true)
                'outputPrecision' => 0,

                // If set to false it would directly apply the selected color on the button and preview.
                'comparison' => true,

                // Default color
                'default' => '#42445a',

                // Optional color swatches. When null, swatches are disabled.
                // Types are all those which can be produced by pickr e.g. hex(a), hsv(a), hsl(a), rgb(a), cmyk, and also CSS color names like 'magenta'.
                // Example' => swatches' => ['#F44336', '#E91E63', '#9C27B0', '#673AB7'],
                'swatches' => [
                    Color::NAVY,
                    Color::BLUE,
                    Color::AQUA,
                    Color::TEAL,
                    Color::OLIVE,
                    Color::GREEN,
                    Color::LIME,
                    Color::YELLOW,
                    Color::ORANGE,
                    Color::RED,
                    Color::MAROON,
                    Color::FUCHSIA,
                    Color::PURPLE,
                    Color::BLACK,
                    Color::GRAY,
                ],

                // Default color representation of the input/output textbox.
                // Valid options are `HEX`, `RGBA`, `HSVA`, `HSLA` and `CMYK`.
                'defaultRepresentation' => 'HEX',

                // Option to keep the color picker always visible.
                // You can still hide / show it via 'pickr.hide()' and 'pickr.show()'.
                // The save button keeps its functionality, so still fires the onSave event when clicked.
                'showAlways' => false,

                // Close pickr with a keypress.
                // Default is 'Escape'. Can be the event key or code.
                // (see' => https' =>//developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key)
                'closeWithKey' => 'Escape',

                // Defines the position of the color-picker.
                // Any combinations of top, left, bottom or right with one of these optional modifiers' => start, middle, end
                // Examples' => top-start / right-end
                // If clipping occurs, the color picker will automatically choose its position.
                'position' => Position::BOTTOM . '-' . Position::MIDDLE,

                // Enables the ability to change numbers in an input field with the scroll-wheel.
                // To use it set the cursor on a position where a number is and scroll, use ctrl to make steps of five
                'adjustableNumbers' => true,

                // Show or hide specific components.
                // By default only the palette (and the save button) is visible.
                'components' => [

                    // Defines if the palette itself should be visible.
                    // Will be overwritten with true if preview, opacity or hue are true
                    'palette' => true,

                    'preview' => true, // Display comparison between previous state and new color
                    'opacity' => true, // Display opacity slider
                    'hue' => true,     // Display hue slider

                    // show or hide components on the bottom interaction bar.
                    'interaction' => [
                        'hex' => true,  // Display 'input/output format as hex' button  (hexadecimal representation of the rgba value)
                        'rgba' => true, // Display 'input/output format as rgba' button (red green blue and alpha)
                        'hsla' => true, // Display 'input/output format as hsla' button (hue saturation lightness and alpha)
                        'hsva' => false, // Display 'input/output format as hsva' button (hue saturation value and alpha)
                        'cmyk' => false, // Display 'input/output format as cmyk' button (cyan mangenta yellow key )
                        'input' => true, // Display input/output textbox which shows the selected color value.
                        // the format of the input is determined by defaultRepresentation,
                        // and can be changed by the user with the buttons set by hex, rgba, hsla, etc (above).
                        'cancel' => false, // Display Cancel Button, resets the color to the previous state
                        'clear' => false, // Display Clear Button; same as cancel, but keeps the window open
                        'save' => true,  // Display Save Button,
                    ],
                ],

                // Button strings, brings the possibility to use a language other than English.
                'strings' => [
                    'save' => $this->translator->trans('buttons.save', [], 'ColorPickerBundle'),  // Default for save button
                    'clear' => $this->translator->trans('buttons.clear', [], 'ColorPickerBundle'), // Default for clear button
                    'cancel' => $this->translator->trans('buttons.cancel', [], 'ColorPickerBundle'), // Default for cancel button
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'wandi_color_picker';
    }

}