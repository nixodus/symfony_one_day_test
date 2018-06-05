<?php

namespace GithubstatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GithubstatsBundle\Form\UsernameType;
use Symfony\Component\HttpFoundation\Request;


class GithubstatsController extends Controller
{
    public function indexAction(Request $request)
    {

          $userData = null;
          $repositoryData = null;
          $error = null;

          $formResult =  $request->get("github_form");
          $form = $this->createForm(UsernameType::class, $formResult);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $userName = $formResult['user_name'];

                try {
                    $retriever = $this->get('githubstats.retriever');
                    $userData = $retriever->getUserData($userName);
                    $repositoryData = $retriever->getRepositoryData($userName);
                } catch (\Exception $e) {

                    $error = $e;
                }
            }
        }

        return $this->render('GithubstatsBundle:Githubstats:index.html.twig', [
            'repositoryData' => $repositoryData,
            'userData' => $userData,
            'error' => $error,
            'form' => $form->createView(),
        ]);

    }
}
