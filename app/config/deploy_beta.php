<?php
use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        /** @var \EasyCorp\Bundle\EasyDeployBundle\Configuration\DefaultConfiguration $configBuilder */
        $configBuilder = $this->getConfigBuilder();
        return $configBuilder
            ->server('pollium.ru')
            ->deployDir('/var/deploy/cantharis/vk-bot-test')
            ->repositoryUrl('git@github.com:alexvasilyev/vkBot.git')
            ->repositoryBranch('master')
            ->sharedFilesAndDirs([])
            ->webDir('public')
            ;
    }
};
