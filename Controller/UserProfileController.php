<?php

namespace Websolutio\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Websolutio\DemoBundle\Entity\UserProfile;
use Websolutio\DemoBundle\Form\UserProfileType;

use Symfony\Component\Security\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * UserProfile controller. User profile CRU(D). If user has no profile yet (e.g. logs in first time) new profile will be created. 
 *
 */
class UserProfileController extends Controller
{

    /**
     * Default page which displays when user go to user profile. method checks if profile exist already and redirects to profile if so 
     * or redirect to createuserprofile if profile has not been create before.
     * 
     */
    public function indexAction() {
		if (!$this->get('security.context')->isGranted('ROLE_SUBCRIBERUSER')) {
			throw new AccessDeniedException();
        }
		$userId = $this->get('security.context')->getToken()->getUser()->getId();
		
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WebsolutioDemoBundle:UserProfile')->findOneByUser($userId);       

		if (!$entity) {
			return $this->redirect($this->generateUrl('userprofile_create_profile'));
		} else {
			return $this->redirect($this->generateUrl('userprofile_show', array('id' => $userId)));
        	
		}
    }

    /**
     * Finds and displays a UserProfile entity.
     */
    public function showAction()
    {
		if (!$this->get('security.context')->isGranted('ROLE_SUBCRIBERUSER')) {
          throw new AccessDeniedException();
        }

        // get loged in user Id	
		$userId = $this->get('security.context')->getToken()->getUser()->getId();
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WebsolutioDemoBundle:UserProfile')->findOneByUser($userId);

        if (!$entity) {
			return $this->redirect($this->generateUrl('userprofile_create_profile'));
		} else {
			return $this->redirect($this->generateUrl('userprofile_show', array('id' => $userId)));
        	
		}

        return $this->render('WebsolutioDemoBundle:UserProfile:index.html.twig', array(
            'entity'      => $entity        
            ));
    }

    public function editAction($id)
    {
		if (!$this->get('security.context')->isGranted('ROLE_SUBCRIBERUSER')) {
          throw new AccessDeniedException();
        }
        // get loged in user Id	
		$userId = $this->get('security.context')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WebsolutioDemoBundle:UserProfile')->findOneByUser($userId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserProfile entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('WebsolutioDemoBundle:UserProfile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    } 
    
    public function updateAction(Request $request, $id)
    {
		if (!$this->get('security.context')->isGranted('ROLE_SUBCRIBERUSER')) {
          throw new AccessDeniedException();
        }
        // get loged in user Id	
		$userId = $this->get('security.context')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WebsolutioDemoBundle:UserProfile')->findOneByUser($userId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserProfile entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('userprofile_show', array('id' => $id)));
        }

        return $this->render('WebsolutioDemoBundle:UserProfile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }   
    
    private function createEditForm(UserProfile $entity)
    {
        $form = $this->createForm(new UserProfileType(), $entity, array(
            'action' => $this->generateUrl('userprofile_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));

        return $form;
    }
 
    /*
     * Create user profile if user has no profile yet and redirect to profiel view
     */	
    public function createuserprofileAction(Request $request)
    {
		if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
			  throw new AccessDeniedException();
		}
		// get loged in user Id	
		$userId = $this->get('security.context')->getToken()->getUser()->getId();
				
		$em = $this->getDoctrine()->getManager();
		if ($this->get('security.context')->isGranted('ROLE_SUBCRIBERUSER')) {
			$entity = $em->getRepository('WebsolutioDemoBundle:UserProfile')->findOneByUser($userId);
		}
        
		if (!$entity) {
			$em = $this->getDoctrine()->getManager();
			$entity = new UserProfile('UTF-8');
			$entity->setUser($this->getDoctrine()->getManager()->getReference('WebsolutioDemoBundle:User',$userId));
							
			$em->persist($entity);
			$em->flush();
			$em->clear();
		}
		
		if ($this->get('security.context')->isGranted('ROLE_SUBCRIBERUSER')) {
			return $this->redirect($this->generateUrl('userprofile_show', array('id' => $userId)));
		}	
	}


}
