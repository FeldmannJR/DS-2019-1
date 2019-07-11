<?php

namespace App\Http\Controllers;

use App\Presentation\PresentationService;
use App\Slide;
use Illuminate\Http\Request;

class PresentationController extends Controller
{


    private $presentation;

    /**
     * PresentationController constructor.
     * @param PresentationService $service
     */
    public function __construct(PresentationService $service)
    {
        $this->presentation = $service;
    }

    public function getTemplates()
    {
        return $this->presentation->getTemplates();
    }

    public function getSlides()
    {
        return Slide::all()->all();
    }

    public function createSlide()
    {
        //template
    }

    public function deleteSlide()
    {
        //slide_id
    }

    public function setIndicators()
    {
        // slide_id
        // slot qual slot do slide
        // array com os ids
    }

    public function setOrder()
    {
        // array ordenada com os ids dos slides
    }


}
