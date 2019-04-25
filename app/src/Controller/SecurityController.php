<?php
/**
 * Security controller.
 */

namespace App\Controller;

use App\Entity\Security;
use App\Repository\SecurityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SecurityType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController.
 *
 * @Route("/security")
 */
class SecurityController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\SecurityRepository        $repository Security repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="security_index",
     * )
     */
    public function index(Request $request, SecurityRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Security::NUMBER_OF_ITEMS
        );

        return $this->render(
            'security/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Security $security Security entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="security_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Security $security): Response
    {
        return $this->render(
            'security/view.html.twig',
            ['security' => $security]
        );
    }
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\SecurityRepository        $repository Security repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="security_new",
     * )
     */
    public function new(Request $request, SecurityRepository $repository): Response
    {
        $security = new Security();
        $form = $this->createForm(SecurityType::class, $security);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($security);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('security_index');
        }

        return $this->render(
            'security/new.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Security                      $security   Security entity
     * @param \App\Repository\SecurityRepository        $repository Security repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="security_edit",
     * )
     */
    public function edit(Request $request, Security $security, SecurityRepository $repository): Response
    {
        $form = $this->createForm(SecurityType::class, $security, ['method' => 'put']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $security->setUpdatedAt(new \DateTime());
            $repository->save($security);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('security_index');
        }

        return $this->render(
            'security/edit.html.twig',
            [
                'form' => $form->createView(),
                'security' => $security,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Security                      $security   Security entity
     * @param \App\Repository\SecurityRepository        $repository Security repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="security_delete",
     * )
     */
    public function delete(Request $request, Security $security, SecurityRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $security, ['method' => 'delete']);
        $form->handleRequest($request);

        if ($request->isMethod('delete')) {
            $form->submit($request->request->get($form->getName()));
            $repository->delete($security);

            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('security_index');
        }

        return $this->render(
            'security/delete.html.twig',
            [
                'form' => $form->createView(),
                'security' => $security,
            ]
        );
    }
}