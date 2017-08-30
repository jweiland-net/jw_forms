<?php
namespace JWeiland\JwForms\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use JWeiland\JwForms\Domain\Model\Form;
use JWeiland\JwForms\Domain\Repository\FormRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class FormController
 *
 * @package JWeiland\JwForms\Controller
 */
class FormController extends ActionController
{
    /**
     * formRepository
     *
     * @var FormRepository
     */
    protected $formRepository;
    
    /**
     * @var string
     */
    protected $letters = '0-9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z';

    /**
     * inject form repository
     *
     * @param FormRepository $formRepository
     *
     * @return void
     */
    public function injectFormRepository(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $forms = $this->formRepository->findByStartingLetter('', '', $this->settings);
        $this->view->assign('forms', $forms);
        $this->view->assign('glossar', $this->getGlossary());
        $this->view->assign('searchWord', '');
    }

    /**
     * action search
     *
     * @param string $letter
     * @param string $searchWord
     *
     * @return void
     */
    public function searchAction($letter = '', $searchWord = '') {
        $forms = $this->formRepository->findByStartingLetter($letter, $searchWord, $this->settings);
        $this->view->assign('forms', $forms);
        $this->view->assign('glossar', $this->getGlossary());
        $this->view->assign('searchWord', $searchWord);
    }

    /**
     * action show
     *
     * @param Form $form
     * @return void
     */
    public function showAction(Form $form)
    {
        $this->view->assign('form', $form);
    }

    /**
     * get an array with letters as keys for the glossar
     *
     * @return array Array with starting letters as keys
     */
    public function getGlossary()
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
        $glossary = array();
        foreach ($possibleLetters as $possibleLetter) {
            $glossary[$possibleLetter] = (strpos($availableLetters, $possibleLetter) !== false) ? true : false;
        }

        return $glossary;
    }
}
