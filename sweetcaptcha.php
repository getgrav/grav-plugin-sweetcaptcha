<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class SweetCaptchaPlugin
 * @package Grav\Plugin
 */
class SweetCaptchaPlugin extends Plugin
{
    protected $sweetcaptcha;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onFormProcessed' => ['onFormProcessed', 0],
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
//        // Don't proceed if we are in the admin plugin
//        if ($this->isAdmin()) {
//            return;
//        }

        require_once(__DIR__ . '/classes/sweetcaptcha.php');

        $this->sweetcaptcha = new SweetCaptcha($this->config['plugins.sweetcaptcha']);
        
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Make form accessible from twig.
     */
    public function onTwigSiteVariables()
    {
        $this->grav['twig']->twig_vars['sweetcaptcha'] = $this->sweetcaptcha;
    }

    /**
     * Process the sweetcaptcha logic
     *
     * @param Event $event
     */
    public function onFormProcessed(Event $event)
    {
        $form = $event['form'];
        $action = $event['action'];

        switch ($action) {
            case 'sweetcaptcha':
                // make sure we have the details
                $key = $form->getValue('sckey');
                $value = $form->getValue('scvalue');
                if ($key && $value && $this->sweetcaptcha->check(array('sckey' => $key, 'scvalue' => $value)) == "true") {
                    // do nothing captcha passed successfully
                } else {
                    $this->grav->fireEvent('onFormValidationError', new Event([
                        'form'    => $form,
                        'message' => $this->grav['language']->translate('PLUGIN_FORM.ERROR_VALIDATING_CAPTCHA')
                    ]));
                    $event->stopPropagation();
                }

                break;
        }
    }
}
