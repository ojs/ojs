<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;
use Ojs\JournalBundle\Document\JournalSetupProgress;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Journal Setup Wizard controller.
 */
class JournalSetupController extends Controller
{
    /**
     * Admin can create new journal.
     * admin can resume from where he/she left.
     * @return mixed
     */
    public function indexAction()
    {
        if (!$this->isGranted('CREATE', new Journal())) {
            throw new AccessDeniedException();
        }
        $user = $this->getUser();
        $dm = $this->get('doctrine_mongodb')->getManager();
        /** @var JournalSetupProgress $userSetup */
        $userSetup = $dm->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneBy(array('userId' => $user->getId()));

        //if user have an journal setup progress resume journal setup. Else create an journal setup progress
        if ($userSetup) {
            return $this->redirect(
                $this->generateUrl(
                    'admin_journal_setup_resume', [
                        'setupId' => $userSetup->getId(),
                    ]
                ).'#'.
                $userSetup->getCurrentStep()
            );
        } else {
            $em = $this->getDoctrine()->getManager();
            $newJournal = new Journal();
            $newJournal->setTitle('');
            $newJournal->setTitleAbbr('');
            $newJournal->setSetupStatus(false);
            $em->persist($newJournal);
            $em->flush();

            $newSetup = new JournalSetupProgress();
            $newSetup->setUserId($user->getId());
            $newSetup->setCurrentStep(1);
            $newSetup->setJournalId($newJournal->getId());
            $dm->persist($newSetup);
            $dm->flush();

            return $this->redirect(
                $this->generateUrl(
                    'admin_journal_setup_resume', [
                        'setupId' => $newSetup->getId(),
                    ]
                ).'#1'
            );
        }
    }

    /**
     * if admin have not finished journal setup resumes from there.
     * @param $setupId
     * @return Response
     */
    public function resumeAction($setupId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $dm->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournalId());

        $stepsForms = array();
        //for 6 step create update forms
        foreach (range(1, 6) as $stepValue) {
            $stepsForms['step'.$stepValue] = $this->createFormView($journal, $stepValue);
        }
        $yamlParser = new Parser();
        $default_pages = $yamlParser->parse(file_get_contents(
            $this->container->getParameter('kernel.root_dir').
            '/../src/Ojs/JournalBundle/Resources/data/pagetemplates.yml'
        ));

        return $this->render('OjsJournalBundle:JournalSetup:index.html.twig', array(
            'journal' => $journal,
            'steps' => $stepsForms,
            'default_pages' => $default_pages,
        ));
    }

    /**
     * @param $setup
     * @param $stepCount
     * @return FormView
     */
    private function createFormView($setup, $stepCount)
    {
        $stepClassName  = 'Ojs\JournalBundle\Form\JournalSetup\Step'.$stepCount;

        return $this->createForm(new $stepClassName(), $setup, array(
            'method' => 'POST',
        ))->createView();
    }
}
