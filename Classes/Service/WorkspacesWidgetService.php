<?php

namespace CodeFareith\CfWorkspacesWidget\Service;

use TYPO3\CMS\Workspaces\Domain\Repository\WorkspaceRepository;
use TYPO3\CMS\Workspaces\Domain\Repository\WorkspaceStageRepository;
use TYPO3\CMS\Workspaces\Service\GridDataService;
use TYPO3\CMS\Workspaces\Service\WorkspaceService;

class WorkspacesWidgetService {
    public function __construct(
        private WorkspaceService $workspaceService,
        private GridDataService $gridDataService,
        private WorkspaceRepository $workspaceRepository,
        private WorkspaceStageRepository $workspaceStageRepository,
    ) {}

    public function getAvailableWorkspaces(): array
    {
        $workspaces = $this->workspaceService->getAvailableWorkspaces();
        // remove LIVE workspace
        unset($workspaces[0]);

        return $workspaces;
    }

    public function getDataByWorkspaceUid(int $workspaceUid): string
    {
        $result = '';

        $selectedWorkspace = $this->workspaceRepository->findByUid($workspaceUid);
        $stages = $this->workspaceStageRepository->findAllStagesByWorkspace($GLOBALS['BE_USER'], $selectedWorkspace);
        $versions = $this->workspaceService->selectVersionsInWorkspace(
            $workspaceUid
        );

        $gridList = $this->gridDataService->generateGridListFromVersions($stages, $versions, new \StdClass());

        foreach ($gridList['data'] as $key => $value) {
            $result .= '[' . $key . ']' . ' ' . $value['label_Workspace'] . ' - ' . $value['state_Workspace'] . ' - '  . $value['label_Stage'] . '<br>';
        }

        return $result;
    }
}