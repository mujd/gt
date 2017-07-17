<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\PersonaEmpresa;

class PersonaEmpresaController extends FOSRestController {

    /**
     * @Rest\Get("/personaEmpresa")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:PersonaEmpresa')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/personaEmpresa/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:PersonaEmpresa')->find($id);
        if ($singleresult === null) {
            return new View("PersonaEmpresa no encontrada", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/personaEmpresa")
     */
    public function crearAction(Request $request) {
        $data = new PersonaEmpresa;
        $empresaId = $request->get('empresaId');
        $personaId = $request->get('personaId');
        if (empty($empresaId) || empty($personaId)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setEmpresaId($empresaId);
        $data->setPersonaId($personaId);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("PersonaEmpresa Agregada Correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/personaEmpresa/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new PersonaEmpresa;
        $empresaId = $request->get('empresaId');
        $personaId = $request->get('personaId');
        $sn = $this->getDoctrine()->getManager();
        $personaEmpresa = $this->getDoctrine()->getRepository('AppBundle:PersonaEmpresa')->find($id);
        if (empty($personaEmpresa)) {
            return new View("personaEmpresa no encontrada", Response::HTTP_NOT_FOUND);
        } elseif (!empty($empresaId) && !empty($personaId)) {
            $personaEmpresa->setEmpresaId($empresaId);
            $personaEmpresa->setPersonaId($personaId);
            $sn->flush();
            return new View("personaEmpresa Actualizada Correctamente", Response::HTTP_OK);
        } else
            return new View("el nombre de la PersonaEmpresa no puede estar vacio!", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/personaEmpresa/{id}")
     */
    public function borrarAction($id) {
        $data = new PersonaEmpresa;
        $sn = $this->getDoctrine()->getManager();
        $personaEmpresa = $this->getDoctrine()->getRepository('AppBundle:PersonaEmpresa')->find($id);
        if (empty($personaEmpresa)) {
            return new View("No se encontro la PersonaEmpresa", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($personaEmpresa);
            $sn->flush();
        }
        return new View("PersonaEmpresa borrada Exitosamente!", Response::HTTP_OK);
    }

}
