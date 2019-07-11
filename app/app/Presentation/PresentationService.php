<?php


namespace App\Presentation;


use App\Presentation\templates\BarPlusPercentage;
use App\Presentation\templates\Full;
use App\Presentation\templates\Template;
use App\Presentation\Slide;

class PresentationService
{


    private $templates;

    private $defaultScreentime = 20;

    /**
     * SlideService constructor.
     */
    public function __construct()
    {
        Slot::registerSlots();
        $this->registerTemplates();
    }

    private function registerTemplates()
    {
        $this->templates = [];
        $this->templates[0] = new Full();
        $this->templates[1] = new BarPlusPercentage();
    }

    public function getTemplates()
    {
        return $this->templates;
    }

    public function getKeys()
    {
        $keys = [];
        foreach ($this->templates as $k => $template) {
            $keys[] = $k;
        }
        return $keys;

    }


    public function createSlide(Template $template)
    {
        if (!in_array($template, $this->templates)) {
            return null;
        }
        $key = array_search($template, $this->templates);
        return Slide::create(['template' => $key, 'screen_time' => $this->defaultScreentime]);
    }

}