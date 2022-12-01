<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw_forms.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwForms\Controller;

use JWeiland\JwForms\Domain\Model\Form;
use JWeiland\JwForms\Domain\Repository\FormRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class FormController
 */
class FormController extends ActionController
{
    /**
     * @var FormRepository
     */
    protected $formRepository;

    /**
     * @var string
     */
    protected $letters = '0-9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z';

    public function injectFormRepository(FormRepository $formRepository): void
    {
        $this->formRepository = $formRepository;
    }

    public function listAction(): void
    {
        $forms = $this->formRepository->findByStartingLetter('', '', $this->settings);
        $this->view->assign('forms', $forms);
        $this->view->assign('glossar', $this->getGlossary());
        $this->view->assign('searchWord', '');
    }

    public function searchAction(string $letter = '', string $searchWord = ''): void
    {
        $this->view->assignMultiple([
            'forms' => $this->formRepository->findByStartingLetter($letter, $searchWord, $this->settings),
            'glossar' => $this->getGlossary(),
            'searchWord' => $searchWord
        ]);
    }

    public function showAction(Form $form): void
    {
        $this->view->assign('form', $form);
    }

    /**
     * Get an array with letters as keys for the glossar
     */
    public function getGlossary(): array
    {
        $possibleLetters = GeneralUtility::trimExplode(',', $this->letters);

        // remove all letters which are not numbers or letters. Sort them
        $availableLetters = $this->formRepository->getStartingLetters($this->settings['categories']);
        $availableLetters = str_split(preg_replace('~([[:^alnum:]])~', '', $availableLetters['letters']));
        sort($availableLetters);
        $availableLetters = implode('', $availableLetters);

        // if there are numbers inside, replace them with 0-9
        if (preg_match('~^[[:digit:]]+~', $availableLetters)) {
            $availableLetters = preg_replace('~(^[[:digit:]]+)~', '0-9', $availableLetters);
        }

        // mark letter as link (true) or not-linked (false)
        $glossary = [];
        foreach ($possibleLetters as $possibleLetter) {
            $glossary[$possibleLetter] = (strpos($availableLetters, $possibleLetter) !== false) ? true : false;
        }

        return $glossary;
    }
}
