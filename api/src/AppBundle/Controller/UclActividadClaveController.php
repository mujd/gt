<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\UclActividadClave;

class UclActividadClaveController extends FOSRestController {

    /**
     * @Rest\Get("/uclActividadClave")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:UclActividadClave')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/uclActividadClave/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:UclActividadClave')->find($id);
        if ($singleresult === null) {
            return new View("UclActividadClave no encontrada", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/uclActividadClave")
     */
    public function crearAction(Request $request) {
        $data = new UclActividadClave;
        $uclId = $request->get('uclId');
        $actividadClaveId = $request->get('actividadClaveId');
        if (empty($uclId) || empty($actividadClaveId)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setUclId($uclId);
        $data->setActividadClaveId($actividadClaveId);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("UclActividadClave Agregada Correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/uclActividadClave/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new UclActividadClave;
        $uclId = $request->get('uclId');
        $actividadClaveId = $request->get('actividadClaveId');
        $sn = $this->getDoctrine()->getManager();
        $uclActividadClave = $this->getDoctrine()->getRepository('AppBundle:UclActividadClave')->find($id);
        if (empty($uclActividadClave)) {
            return new View("uclActividadClave no encontrada", Response::HTTP_NOT_FOUND);
        } elseif (!empty($uclId) && !empty($actividadClaveId)) {
            $uclActividadClave->setUclId($uclId);
            $uclActividadClave->setActividadClaveId($actividadClaveId);
            $sn->flush();
            return new View("uclActividadClave Actualizada Correctamente", Response::HTTP_OK);
        } else
            return new View("el nombre de la UclActividadClave no puede estar vacio!", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/uclActividadClave/{id}")
     */
    public function borrarAction($id) {
        $data = new UclActividadClave;
        $sn = $this->getDoctrine()->getManager();
        $uclActividadClave = $this->getDoctrine()->getRepository('AppBundle:UclActividadClave')->find($id);
        if (empty($uclActividadClave)) {
            return new View("No se encontro el UclActividadClave", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($uclActividadClave);
            $sn->flush();
        }
        return new View("UclActividadClave borrada Exitosamente!", Response::HTTP_OK);
    }

}
