<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Indicador;
use AppBundle\Entity\CompetenciaConductualIndicador;

class IndicadorController extends FOSRestController {

    /**
     * @Rest\Get("/indicador")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Indicador')->findAll();
        if ($restresult === null) {
            return new View("no hay nada", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/indicador/{id}")
     */
    public function buscarIdAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Indicador')->find($id);
        if ($singleresult === null) {
            return new View("indicador no encontrado", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/indicador/competenciaConductual/{competenciaConductualId}")
     */
     public function listarPorActividadAction($competenciaConductualId) {
        $retorno = array();
        $indicadores = $this->getDoctrine()->getRepository('AppBundle:CompetenciaConductualIndicador')->findBy(array('competenciaConductualId' => $competenciaConductualId));
        if ($indicadores !== null) {
            foreach ($indicadores as $indica) {
                $indicador = $this->getDoctrine()->getRepository('AppBundle:Indicador')->find($indica->getIndicadorId());
                if ($indicador != null) {
                    array_push($retorno, $indicador);
                }
            }
            return $retorno;
        }
     }
    /**
     * @Rest\Post("/indicador")
     */
    public function crearAction(Request $request) {

        $indicador = new Indicador;
        $competenciaConductualIndicador = new CompetenciaConductualIndicador;

        $competenciaConductualId = $request->get('competenciaConductualId');
        $nombre = $request->get('nombre');

        $indicador->setNombre($nombre);

        $em = $this->getDoctrine()->getManager();
        $em->persist($indicador);
        $em->flush();
        $indicadorId = $indicador->getId();

        $competenciaConductualIndicador->setCompetenciaConductualId($competenciaConductualId);
        $competenciaConductualIndicador->setIndicadorId($indicadorId);

        $em = $this->getDoctrine()->getManager();
        $em->persist($competenciaConductualIndicador);
        $em->flush();

        return new View("indicador creado y asignado a competencia conductual correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/indicador/{id}")
     */
    public function actualizarAction($id, Request $request) {
        $data = new Indicador;
        $nombre = $request->get('nombre');
        $sn = $this->getDoctrine()->getManager();
        $indicador = $this->getDoctrine()->getRepository('AppBundle:Indicador')->find($id);
        if (empty($indicador)) {
            return new View("indicador no encontrado", Response::HTTP_NOT_FOUND);
        } elseif (!empty($nombre)) {
            $indicador->setNombre($nombre);
            $sn->flush();
            return new View("indicador Actualizado Correctamente", Response::HTTP_OK);
        } else
            return new View("el nombre de Indicador no puede estar vacio!", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/indicador/{id}")
     */
    public function borrarAction($id) {
        $data = new Indicador;
        $sn = $this->getDoctrine()->getManager();
        $indicador = $this->getDoctrine()->getRepository('AppBundle:Indicador')->find($id);
        if (empty($indicador)) {
            return new View("No se encontro el indicador", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($indicador);
            $sn->flush();
        }
        return new View("indicador borrado Exitosamente!", Response::HTTP_OK);
    }

}
