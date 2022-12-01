<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwForms\Controller;

use JWeiland\JwForms\Domain\Model\Form;
use JWeiland\JwForms\Domain\Repository\FormRepository;
use JWeiland\JwForms\Event\PostProcessFluidVariablesEvent;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller for form records. It contains actions for list, search and show
 */
class FormController extends ActionController
{
    /**
     * @var FormRepository
     */
    protected $formRepository;

    public function injectFormRepository(FormRepository $formRepository): void
    {
        $this->formRepository = $formRepository;
    }

    public function listAction(): void
    {
        $this->postProcessAndAssignFluidVariables([
            'forms' => $this->formRepository->findByStartingLetter('', '', $this->settings),
            'searchWord' => ''
        ]);
    }

    public function searchAction(string $letter = '', string $searchWord = ''): void
    {
        $this->postProcessAndAssignFluidVariables([
            'forms' => $this->formRepository->findByStartingLetter($letter, $searchWord, $this->settings),
            'searchWord' => $searchWord
        ]);
    }

    protected function postProcessAndAssignFluidVariables(array $variables = []): void
    {
        /** @var PostProcessFluidVariablesEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new PostProcessFluidVariablesEvent(
                $this->request,
                $this->settings,
                $variables
            )
        );

        $this->view->assignMultiple($event->getFluidVariables());
    }
}
