<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Criterio;
use AppBundle\Entity\ActividadClaveCriterio;

class CriterioController extends FOSRestController {

    /**
     * @Rest\Get("/criterio")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Criterio')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/criterio/actividadClave/{actividadClaveId}")
     */
    public function listarAsignadasAction($actividadClaveId) {
        $resultado = $this->getDoctrine()->getRepository('AppBundle:ActividadClaveCriterio')
                ->findBy(array('actividadClaveId' => $actividadClaveId));
        if ($resultado === null) {
            return new View("no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $resultado;
    }

    /**
     * @Rest\Get("/criterio/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Criterio')->find($id);
        if ($singleresult === null) {
            return new View("criterio no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/criterio")
     */
    public function crearAsignarAction(Request $request) {
        $criterio = new Criterio;
        $actividadClaveCriterio = new ActividadClaveCriterio;

        $actividadClaveId = $request->get('actividadClaveId');
        $nombre = $request->get('nombre');

        $criterio->setNombre($nombre);

        $em = $this->getDoctrine()->getManager();
        $em->persist($criterio);
        $em->flush();
        $criterioId = $criterio->getId();

        $actividadClaveCriterio->setActividadClaveId($actividadClaveId);
        $actividadClaveCriterio->setCriterioId($criterioId);

        $em = $this->getDoctrine()->getManager();
        $em->persist($actividadClaveCriterio);
        $em->flush();

        return new View("criterio creado y asignado a actividad clave Correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/criterio/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new Criterio;
        $nombre = $request->get('nombre');
        $sn = $this->getDoctrine()->getManager();
        $criterio = $this->getDoctrine()->getRepository('AppBundle:Criterio')->find($id);
        if (empty($criterio)) {
            return new View("criterio not found", Response::HTTP_NOT_FOUND);
        } elseif (!empty($nombre)) {
            $criterio->setNombre($nombre);
            $sn->flush();
            return new View("criterio Actualizado Correctamente", Response::HTTP_OK);
        } else
            return new View("el nombre de CRITERIO no puede estar vacio!", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/criterio/{id}")
     */
    public function borrarAction($id) {
        $data = new Criterio;
        $sn = $this->getDoctrine()->getManager();
        $criterio = $this->getDoctrine()->getRepository('AppBundle:Criterio')->find($id);

        $sn->remove($criterio);
        $sn->flush();

        return new View("criterio borrado Exitosamente!", Response::HTTP_OK);
    }

}
