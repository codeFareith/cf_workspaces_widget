<?php declare(strict_types=1);

namespace CodeFareith\CfWorkspacesWidget\Widgets;

use CodeFareith\CfWorkspacesWidget\Service\WorkspacesWidgetService;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Settings\SettingDefinition;
use TYPO3\CMS\Core\SysLog\Action\Cache;
use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\RequestAwareWidgetInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetContext;
use TYPO3\CMS\Dashboard\Widgets\WidgetRendererInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetResult;

class WorkspacesWidget implements WidgetRendererInterface, RequestAwareWidgetInterface
{
    private ServerRequestInterface $request;

    public function __construct(
        private WidgetConfigurationInterface $configuration,
        private Cache $cache,
        private BackendViewFactory $backendViewFactory,
        private WorkspacesWidgetService $workspacesWidgetService,
        ?ButtonProviderInterface $buttonProvider = null,
        array $options = []
    ) {}

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getSettingsDefinitions(): array
    {
        $workspaces = $this->workspacesWidgetService->getAvailableWorkspaces();
        $firstWorkspace = array_key_first($workspaces);

        $titleSettings = new SettingDefinition(
            'title',
            'string',
            'Default Workspace',
            'LLL:EXT:cf_workspaces_widget/Resources/Private/Language/locallang.xlf:workspaces.widget.settings.title.label',
            'LLL:EXT:cf_workspaces_widget/Resources/Private/Language/locallang.xlf:workspaces.widget.settings.title.description',
        );

        $workspaceSettings = new SettingDefinition(
            'workspace',
            'string',
            (string)$firstWorkspace,
            'LLL:EXT:cf_workspaces_widget/Resources/Private/Language/locallang.xlf:workspaces.widget.settings.workspace.label',
            'LLL:EXT:cf_workspaces_widget/Resources/Private/Language/locallang.xlf:workspaces.widget.settings.workspace.description',
            false,
            $workspaces
        );

        return [$titleSettings, $workspaceSettings];
    }

    /**
     * @param WidgetContext $context
     *
     * @return WidgetResult
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function renderWidget(WidgetContext $context): WidgetResult
    {
        $view = $this->backendViewFactory->create($this->request);

        $settings = $context->settings;

        $workspaceUid = (int)$settings->get('workspace');
        $content = $this->workspacesWidgetService->getDataByWorkspaceUid($workspaceUid);

        $label = $settings->get('title') ?? '';
        $refreshable = true;

        return new WidgetResult($content, $label, $refreshable);
    }
}