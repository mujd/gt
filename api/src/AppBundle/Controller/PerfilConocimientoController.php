<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\PerfilConocimiento;

class PerfilConocimientoController extends FOSRestController {

    /**
     * @Rest\Get("/perfilConocimiento")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:PerfilConocimiento')->findAll();
        if ($restresult === null) {
            return new View("no hay registros de perfilConocimiento", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/perfilConocimiento/{id}")
     */
    public function buscarIddAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:PerfilConocimiento')->find($id);
        if ($singleresult === null) {
            return new View("PerfilConocimiento no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/perfilConocimiento")
     */
    public function crearAction(Request $request) {
        $data = new PerfilConocimiento;
        $perfilId = $request->get('perfilId');
        $conocimientoId = $request->get('conocimientoId');
        if (empty($perfilId) || empty($conocimientoId)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setPerfilId($perfilId);
        $data->setConocimientoId($conocimientoId);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("PerfilConocimiento agregado correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/perfilConocimiento/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new PerfilConocimiento;
        $perfilId = $request->get('perfilId');
        $conocimientoId = $request->get('conocimientoId');
        $sn = $this->getDoctrine()->getManager();
        $perfilConocimiento = $this->getDoctrine()->getRepository('AppBundle:PerfilConocimiento')->find($id);
        if (empty($perfilConocimiento)) {
            return new View("PerfilConocimiento no encontrado", Response::HTTP_NOT_FOUND);
        } elseif (!empty($perfilId) && !empty($conocimientoId)) {
            $perfilConocimiento->setPerfilId($perfilId);
            $perfilConocimiento->setConocimientoId($conocimientoId);
            $sn->flush();
            return new View("PerfilConocimiento actualizado correctamente", Response::HTTP_OK);
        } else
            return new View("perfilId o conocimientoId no pueden estar vacios!", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/perfilConocimiento/{id}")
     */
    public function borrarAction($id) {
        $data = new PerfilConocimiento;
        $sn = $this->getDoctrine()->getManager();
        $perfilConocimiento = $this->getDoctrine()->getRepository('AppBundle:PerfilConocimiento')->find($id);
        if (empty($perfilConocimiento)) {
            return new View("perfilConocimiento no encontrado", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($perfilConocimiento);
            $sn->flush();
        }
        return new View("borrado exitosamente", Response::HTTP_OK);
    }

}
