<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\PerfilUcl;

class PerfilUclController extends FOSRestController {

    /**
     * @Rest\Get("/perfilUcl")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/perfilUcl/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->find($id);
        if ($singleresult === null) {
            return new View("PerfilUcl no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/perfilUcl")
     */
    public function crearAction(Request $request) {
        $data = new PerfilUcl;
        $uclId = $request->get('uclId');
        $perfilId = $request->get('perfilId');
        if (empty($uclId) || empty($perfilId)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setUclId($uclId);
        $data->setPerfilId($perfilId);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("PerfilUcl Agregado Correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/perfilUcl/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new PerfilUcl;
        $uclId = $request->get('uclId');
        $perfilId = $request->get('perfilId');
        $sn = $this->getDoctrine()->getManager();
        $perfilUcl = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->find($id);
        if (empty($perfilUcl)) {
            return new View("perfilUcl no encontrado", Response::HTTP_NOT_FOUND);
        } elseif (!empty($uclId) && !empty($perfilId)) {
            $perfilUcl->setUclId($uclId);
            $perfilUcl->setPerfilId($perfilId);
            $sn->flush();
            return new View("perfilUcl Actualizado Correctamente", Response::HTTP_OK);
        } else
            return new View("el nombre del PerfilUcl no puede estar vacio!", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/perfilUcl/{id}")
     */
    public function borrarAction($id) {
        $data = new PerfilUcl;
        $sn = $this->getDoctrine()->getManager();
        $perfilUcl = $this->getDoctrine()->getRepository('AppBundle:PerfilUcl')->find($id);
        if (empty($perfilUcl)) {
            return new View("No se encontro el PerfilUcl", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($perfilUcl);
            $sn->flush();
        }
        return new View("PerfilUcl borrado Exitosamente!", Response::HTTP_OK);
    }

}
