<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Evaluacion;
use AppBundle\Entity\Persona;
use AppBundle\Entity\EvaluacionPersona;

class EvaluacionController extends FOSRestController {

    /**
     * @Rest\Get("/evaluacion")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Evaluacion')->findAll();
        if ($restresult === null) {
            return new View("Ninguna Evaluacion fue Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/evaluacion/{id}")
     */
    public function buscarIdAction($id) {
        $evaluacion = $this->getDoctrine()->getRepository('AppBundle:Evaluacion')->find($id);
        if ($evaluacion === null) {
            return new View("Evaluacion no Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $evaluacion;
    }

    /**
     * @Rest\Get("/evaluacion/codigo/{codigo}")
     */
    public function buscarCodigoAction($codigo) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Evaluacion')->findBy(array('codigo' => $codigo));
        if ($singleresult === null) {
            return new View("evaluacion no Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

/**
  * @Rest\Get("/evaluacion/persona/{personaId}")
  */
   public function listarPorPersonaAction($personaId) {
      $retorno = array();
      $evaluaciones = $this->getDoctrine()->getRepository('AppBundle:EvaluacionPersona')->findBy(array('personaId' => $personaId));
      if ($evaluaciones !== null) {
          foreach ($evaluaciones as $eva) {
              $evaluacion = $this->getDoctrine()->getRepository('AppBundle:Evaluacion')->find($eva->getEvaluacionId());
              if ($evaluacion != null) {
                  array_push($retorno, $evaluacion);
              }
          }
          return $retorno;
      }
   }

    /**
     * @Rest\Post("/evaluacionn")
     */
     public function postAction(Request $request){
        $evaluacion = new Evaluacion;

        $codigo = $request->get('codigo');
        $nombre = $request->get('nombre');
        $fechaInicio = $request->get('fechaInicio');
        $fechaTermino = $request->get('fechaTermino');
        $observacion = $request->get('observacion');

        $evaluacion->setCodigo($codigo);
        $evaluacion->setNombre($nombre);
        $evaluacion->setFechaInicio(date_create($fechaInicio));
        $evaluacion->setFechaTermino(date_create($fechaTermino));
        $evaluacion->setObservacion($observacion);

        $em = $this->getDoctrine()->getManager();
        $em->persist($evaluacion);
        $em->flush();
        return new View("Evaluacion agregada correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/evaluacion")
     */
    public function crearAction(Request $request) {
        $evaluacion = new Evaluacion;

        $codigo = $request->get('codigo');
        $nombre = $request->get('nombre');
        $fechaInicio = $request->get('fechaInicio');
        $fechaTermino = $request->get('fechaTermino');
        $observacion = $request->get('observacion');

        $evaluacion->setCodigo($codigo);
        $evaluacion->setNombre($nombre);
        $evaluacion->setFechaInicio(date_create($fechaInicio));
        $evaluacion->setFechaTermino(date_create($fechaTermino));
        $evaluacion->setObservacion($observacion);

        $em = $this->getDoctrine()->getManager();
        $em->persist($evaluacion);
        $em->flush();

        $id = $evaluacion->getId();

        $evaluacionPersona = new EvaluacionPersona();
        $em = $this->getDoctrine()->getManager();
        $personas = $this->getDoctrine()->getRepository('AppBundle:EvaluacionPersona')->findBy(array('evaluacionId' => $id));
        if (!empty($personas)) {
            foreach ($personas as $persona) {
                $em->remove($persona);
                $em->flush();
            }
        }

        $personas = $request->get('personas');
        foreach ($personas as $persona) {
            $evaluacionPersona = new EvaluacionPersona();
            $em = $this->getDoctrine()->getManager();
            $evaluacionPersona->setPersonaId($persona);
            $evaluacionPersona->setEvaluacionId($id);
            $em->persist($evaluacionPersona);
            $em->flush();
        }
        return new View("Evaluacion creada con sus personas asignadas", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/evaluacion/{id}")
     */
    public function actualizarAction($id, Request $request) {

        $codigo = $request->get('codigo');
        $nombre = $request->get('nombre');
        $fechaInicio = $request->get('fechaInicio');
        $fechaTermino = $request->get('fechaTermino');
        $observacion = $request->get('observacion');

        $em = $this->getDoctrine()->getManager();
        $evaluacion = $this->getDoctrine()->getRepository('AppBundle:Evaluacion')->find($id);
        if (!empty($evaluacion)) {

            $evaluacion->setCodigo($codigo);
            $evaluacion->setNombre($nombre);
            $evaluacion->setFechaInicio(date_create($fechaInicio));
            $evaluacion->setFechaTermino(date_create($fechaTermino));
            $evaluacion->setObservacion($observacion);
            $em->flush();

            $evaluacionPersona = new EvaluacionPersona();
            $em = $this->getDoctrine()->getManager();
            $personas = $this->getDoctrine()->getRepository('AppBundle:EvaluacionPersona')->findBy(array('evaluacionId' => $id));
            if (!empty($personas)) {
                foreach ($personas as $persona) {
                    $em->remove($persona);
                    $em->flush();
                }
            }

            $personas = $request->get('personas');
            foreach ($personas as $persona) {
                $evaluacionPersona = new EvaluacionPersona();

                $em = $this->getDoctrine()->getManager();
                $evaluacionPersona->setPersonaId($persona);
                $evaluacionPersona->setEvaluacionId($id);
                $em->persist($evaluacionPersona);
                $em->flush();
            }
            return new View("Evaluacion modificada con sus personas asignadas", Response::HTTP_OK);

            }

    }

    /**
     * @Rest\Delete("/evaluacion/{id}")
     */
    public function borrarAction($id) {
        $data = new Evaluacion;
        $em = $this->getDoctrine()->getManager();
        $evaluacion = $this->getDoctrine()->getRepository('AppBundle:Evaluacion')->find($id);
        if (empty($evaluacion)) {
            return new View("No se encontro a la evaluacion", Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($evaluacion);
            $em->flush();
        }
        return new View("evaluacion borrada Exitosamente!", Response::HTTP_OK);
    }

}
