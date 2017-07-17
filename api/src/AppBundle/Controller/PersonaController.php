<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Persona;
use AppBundle\Entity\Empresa;
use AppBundle\Entity\PersonaEmpresa;

class PersonaController extends FOSRestController {

    /**
     * @Rest\Get("/persona")
     */
    public function listarAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Persona')->findAll();
        if ($restresult === null) {
            return new View("Ninguna Persona fue Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/persona/{id}")
     */
    public function buscarIdAction($id) {
        $persona = $this->getDoctrine()->getRepository('AppBundle:Persona')->find($id);
        if ($persona === null) {
            return new View("Persona no Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $persona;
    }

    /**
     * @Rest\Get("/persona/rut/{rut}")
     */
    public function buscarRutAction($rut) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Persona')->findBy(array('rut' => $rut));
        if ($singleresult === null) {
            return new View("Persona no Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/persona/nombre/{nombre}")
     */
    public function buscarNombreAction($nombre) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Persona')->findBy(array('nombre' => $nombre));
        if ($singleresult === null) {
            return new View("Persona no Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/persona/apellidoPaterno/{apellidoPaterno}")
     */
    public function buscarPaternoAction($apellidoPaterno) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Persona')->findBy(array('apellidoPaterno' => $apellidoPaterno));
        if ($singleresult === null) {
            return new View("Persona no Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/persona/apellidoMaterno/{apellidoMaterno}")
     */
    public function buscarMaternoAction($apellidoMaterno) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Persona')->findBy(array('apellidoMaterno' => $apellidoMaterno));
        if ($singleresult === null) {
            return new View("Persona no Encontrada!", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

/**
  * @Rest\Get("/persona/evaluacion/{evaluacionId}")
  */
   public function listarPorEvaluacionAction($evaluacionId) {
      $retorno = array();
      $personas = $this->getDoctrine()->getRepository('AppBundle:EvaluacionPersona')->findBy(array('evaluacionId' => $evaluacionId));
      if ($personas !== null) {
          foreach ($personas as $perso) {
              $persona = $this->getDoctrine()->getRepository('AppBundle:Persona')->find($perso->getPersonaId());
              if ($persona != null) {
                  array_push($retorno, $persona);
              }
          }
          return $retorno;
      }
   }

    /**
     * @Rest\Post("/persona")
     */
    public function crearAction(Request $request) {
        $persona = new Persona;

        $rut = $request->get('rut');
        $nombre = $request->get('nombre');
        $apellidoPaterno = $request->get('apellidoPaterno');
        $apellidoMaterno = $request->get('apellidoMaterno');
        $correo = $request->get('correo');
        $perfil = $request->get('perfilId');
        $area = $request->get('areaId');
        $centroDistribucion = $request->get('centroDistribucionId');

        $persona->setRut($rut);
        $persona->setNombre($nombre);
        $persona->setApellidoPaterno($apellidoPaterno);
        $persona->setApellidoMaterno($apellidoMaterno);
        $persona->setCorreo($correo);
        $persona->setPerfilId($perfil);
        $persona->setAreaId($area);
        $persona->setCentroDistribucionId($centroDistribucion);

        $em = $this->getDoctrine()->getManager();
        $em->persist($persona);
        $em->flush();

        $id = $persona->getId();

        $personaEmpresa = new PersonaEmpresa();
        $em = $this->getDoctrine()->getManager();
        $empresas = $this->getDoctrine()->getRepository('AppBundle:PersonaEmpresa')->findBy(array('personaId' => $id));
        if (!empty($empresas)) {
            foreach ($empresas as $empresa) {
                $em->remove($empresa);
                $em->flush();
            }
        }

        $empresas = $request->get('empresas');
        foreach ($empresas as $empresa) {
            $personaEmpresa = new PersonaEmpresa();
            $em = $this->getDoctrine()->getManager();
            $personaEmpresa->setEmpresaId($empresa);
            $personaEmpresa->setPersonaId($id);
            $em->persist($personaEmpresa);
            $em->flush();
        }
        return new View("Persona Agregada y asignada correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/persona/{id}")
     */
    public function actualizarAction($id, Request $request) {

        $rut = $request->get('rut');
        $nombre = $request->get('nombre');
        $apellidoPaterno = $request->get('apellidoPaterno');
        $apellidoMaterno = $request->get('apellidoMaterno');
        $correo = $request->get('correo');
        $perfil = $request->get('perfilId');
        $area = $request->get('areaId');
        $centroDistribucion = $request->get('centroDistribucionId');

        $em = $this->getDoctrine()->getManager();
        $persona = $this->getDoctrine()->getRepository('AppBundle:Persona')->find($id);
        if (!empty($persona)) {

            $persona->setRut($rut);
            $persona->setNombre($nombre);
            $persona->setCorreo($correo);
            $persona->setApellidoPAterno($apellidoPaterno);
            $persona->setApellidoMaterno($apellidoMaterno);
            $persona->setPerfilId($perfil);
            $persona->setAreaId($area);
            $persona->setCentroDistribucionId($centroDistribucion);
            $em->flush();

            $personaEmpresa = new PersonaEmpresa();
            $em = $this->getDoctrine()->getManager();
            $empresas = $this->getDoctrine()->getRepository('AppBundle:PersonaEmpresa')->findBy(array('personaId' => $id));
            if (!empty($empresas)) {
                foreach ($empresas as $empresa) {
                    $em->remove($empresa);
                    $em->flush();
                }
            }

            $empresas = $request->get('empresas');
            foreach ($empresas as $empresa) {
                $personaEmpresa = new PersonaEmpresa();
                $em = $this->getDoctrine()->getManager();
                $personaEmpresa->setEmpresaId($empresa);
                $personaEmpresa->setPersonaId($id);
                $em->persist($personaEmpresa);
                $em->flush();
            }
            return new View("Persona Actualizada Correctamente", Response::HTTP_OK);

        } else {
            return new View("Persona no Encontrada!", Response::HTTP_NOT_FOUND);
        }

    }

    /**
     * @Rest\Delete("/persona/{id}")
     */
    public function borrarAction($id) {
        $data = new Persona;
        $sn = $this->getDoctrine()->getManager();
        $persona = $this->getDoctrine()->getRepository('AppBundle:Persona')->find($id);
        if (empty($persona)) {
            return new View("No se encontro a la Persona", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($persona);
            $sn->flush();
        }
        return new View("Persona borrada Exitosamente!", Response::HTTP_OK);
    }

}
