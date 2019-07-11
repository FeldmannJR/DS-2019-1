<?php

namespace App\Http\Controllers;

use App\Presentation\PresentationService;
use App\Presentation\Slide;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PresentationController extends Controller
{



    /** @var PresentationService */
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
        return Slide::orderBy('order', 'asc')->get();
    }

    public function createSlide(Request $request)
    {
        $data = $request->validate([
                'template' => [
                    'required',
                    Rule::in($this->presentation->getKeys())
                ]
            ]
        );
        $template = $this->presentation->getTemplates()[$data['template']];
        return $this->presentation->createSlide($template);
        //template
    }

    public function deleteSlide(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:slides,id'
        ]);
        $success = Slide::find($data['id'])->delete();
        return response()->json(['success' => $success], $success ? 200 : 403);
    }

    public function setIndicators(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:slides,id',
            'slot' => 'required|integer',
            'indicators' => 'required|array',
            'indicators.*' => 'integer|exists:indicators,id'
        ]);
        $indicators = $data['indicators'];

        $slide = Slide::find($data['id']);
        $slots = $slide->getTemplate()->getSlots();

        if (!array_key_exists($data['slot'], $slots)) {
            throw  \Illuminate\Validation\ValidationException::withMessages([
                'slot' => ['Slot inválido para o slide recebido!']
            ]);
        }
        $slot = $slots[$data['slot']];
        $received_size = count($indicators);
        if ($slot->getSize() < $received_size) {
            throw  \Illuminate\Validation\ValidationException::withMessages([
                'indicators' => ['Recebido tamanho maior do que esperado!']
            ]);
        }
        $success = $slide->setSlot($data['slot'], $indicators);
        return response()->json(['success' => $success], $success ? 200 : 403);

    }

    public function setOrder(Request $request)
    {
        $data = $request->validate([
            'slide' => 'required|array',
            'slide.*' => 'integer|exists:slides,id'
        ]);
        // Ordena na ordem que recebida
        $slides = $data['slide'];
        $order = 1;
        foreach ($slides as $id) {
            $slide = Slide::find($id);
            $slide->order = $order;
            $slide->save();
            $order++;
        }
        // Agora bota os que não foram especificados no final
        foreach (Slide::all() as $slide) {
            if (!in_array($slide->id, $slides)) {
                $slide->order = $order;
                $slide->save();
                $order++;
            }
        }
        return response()->json(['success' => true]);
    }


}
