<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\ConductaIndicador;

class ConductaIndicadorController extends FOSRestController {

    /**
     * @Rest\Get("/conductaIndicador")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:ConductaIndicador')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/conductaIndicador/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:ConductaIndicador')->find($id);
        if ($singleresult === null) {
            return new View("ConductaIndicador no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/conductaIndicador")
     */
    public function crearAction(Request $request) {
        $data = new ConductaIndicador;
        $conductaId = $request->get('conductaId');
        $indicadorId = $request->get('indicadorId');
        if (empty($conductaId) || empty($indicadorId)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setConductaID($conductaId);
        $data->setIndicadorId($indicadorId);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("ConductaIndicador Agregada Correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/conductaIndicador/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new ConductaIndicador;
        $conductaId = $request->get('conductaId');
        $indicadorId = $request->get('indicadorId');
        $sn = $this->getDoctrine()->getManager();
        $conductaIndicador = $this->getDoctrine()->getRepository('AppBundle:ConductaIndicador')->find($id);
        if (empty($conductaIndicador)) {
            return new View("conductaIndicador not found", Response::HTTP_NOT_FOUND);
        } elseif (!empty($conductaId) && !empty($indicadorId)) {
            $conductaIndicador->setConductaId($conductaId);
            $conductaIndicador->setIndicadorId($indicadorId);
            $sn->flush();
            return new View("conductaIndicador Actualizado Correctamente", Response::HTTP_OK);
        } else
            return new View("el nombre de la ConductaIndicador no puede estar vacio!", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/conductaIndicador/{id}")
     */
    public function borrarAction($id) {
        $data = new ConductaIndicador;
        $sn = $this->getDoctrine()->getManager();
        $conductaIndicador = $this->getDoctrine()->getRepository('AppBundle:ConductaIndicador')->find($id);
        if (empty($conductaIndicador)) {
            return new View("No se encontro el ConductaIndicador", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($conductaIndicador);
            $sn->flush();
        }
        return new View("ConductaIndicador borrado Exitosamente!", Response::HTTP_OK);
    }

}
