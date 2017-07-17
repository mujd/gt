<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\ActividadClaveCriterio;

class ActividadClaveCriterioController extends FOSRestController {

    /**
     * @Rest\Get("/actividadClaveCriterio")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:ActividadClaveCriterio')->findAll();
        if ($restresult === null) {
            return new View("no hay registros", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/actividadClaveCriterio/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:ActividadClaveCriterio')->find($id);
        if ($singleresult === null) {
            return new View("ActividadClaveCriterio no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/actividadClaveCriterio")
     */
    public function crearAction(Request $request) {
        $data = new ActividadClaveCriterio;
        $criterioId = $request->get('criterioId');
        $actividadClaveId = $request->get('actividadClaveId');
        if (empty($criterioId) || empty($actividadClaveId)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setCriterioId($criterioId);
        $data->setActividadClaveId($actividadClaveId);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("actividadClaveCriterio agregada correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/actividadClaveCriterio/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new ActividadClaveCriterio;
        $criterioId = $request->get('criterioId');
        $actividadClaveId = $request->get('actividadClaveId');
        $sn = $this->getDoctrine()->getManager();
        $actividadClaveCriterio = $this->getDoctrine()->getRepository('AppBundle:ActividadClaveCriterio')->find($id);
        if (empty($actividadClaveCriterio)) {
            return new View("ActividadClaveCriterio no encontrada", Response::HTTP_NOT_FOUND);
        } elseif (!empty($criterioId) && !empty($actividadClaveId)) {
            $actividadClaveCriterio->setCriterioId($criterioId);
            $actividadClaveCriterio->setActividadClaveId($actividadClaveId);
            $sn->flush();
            return new View("ActividadClaveCriterio actualizada correctamente", Response::HTTP_OK);
        } else
            return new View("ActividadClaveCriterio actividadClaveId or criterioId cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/actividadClaveCriterio/{id}")
     */
    public function borrarAction($id) {
        $data = new ActividadClaveCriterio;
        $sn = $this->getDoctrine()->getManager();
        $actividadClaveCriterio = $this->getDoctrine()->getRepository('AppBundle:ActividadClaveCriterio')->find($id);
        if (empty($actividadClaveCriterio)) {
            return new View("actividadClaveCriterio no encontrada", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($actividadClaveCriterio);
            $sn->flush();
        }
        return new View("borrado correctamente", Response::HTTP_OK);
    }

}
