<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwForms\EventListener;

use JWeiland\Glossary2\Service\GlossaryService;
use JWeiland\JwForms\Domain\Repository\FormRepository;
use JWeiland\JwForms\Event\PostProcessFluidVariablesEvent;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class AddGlossaryEventListener extends AbstractControllerEventListener
{
    /**
     * @var GlossaryService
     */
    protected $glossaryService;

    /**
     * @var FormRepository
     */
    protected $formRepository;

    protected $allowedControllerActions = [
        'Form' => [
            'list',
            'search'
        ]
    ];

    public function __construct(GlossaryService $glossaryService, FormRepository $formRepository)
    {
        $this->glossaryService = $glossaryService;
        $this->formRepository = $formRepository;
    }

    public function __invoke(PostProcessFluidVariablesEvent $event): void
    {
        if ($this->isValidRequest($event)) {
            $event->addFluidVariable(
                'glossar',
                $this->glossaryService->buildGlossary(
                    $this->formRepository->getQueryBuilderToFindAllEntries(),
                    $this->getOptions($event)
                )
            );
        }
    }

    protected function getOptions(PostProcessFluidVariablesEvent $event): array
    {
        $options = [
            'extensionName' => 'JwForms',
            'pluginName' => 'Forms',
            'controllerName' => 'Form',
            'column' => 'title',
            'settings' => $event->getSettings()
        ];

        if (
            isset($event->getSettings()['glossary'])
            && is_array($event->getSettings()['glossary'])
        ) {
            ArrayUtility::mergeRecursiveWithOverrule($options, $event->getSettings()['glossary']);
        }

        return $options;
    }
}
