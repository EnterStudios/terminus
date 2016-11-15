<?php


namespace Pantheon\Terminus\UnitTests\Commands\Env;

use Pantheon\Terminus\Commands\Env\ClearCacheCommand;
use Pantheon\Terminus\Models\Workflow;

class ClearCacheCommandTest extends EnvCommandTest
{
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->command = new ClearCacheCommand($this->getConfig());
        $this->command->setSites($this->sites);
        $this->command->setLogger($this->logger);
    }

    public function testGetClearCache()
    {
        $workflow = $this->getMockBuilder(Workflow::class)
            ->disableOriginalConstructor()
            ->getMock();
        $site_name = 'site_name';
        $this->env->id = 'site_id';
        $this->env->expects($this->once())
            ->method('clearCache')
            ->with()
            ->willReturn($workflow);
        $workflow->expects($this->once())
            ->method('checkProgress')
            ->with()
            ->willReturn(true);
        $this->site->expects($this->once())
            ->method('get')
            ->with($this->equalTo('name'))
            ->willReturn($site_name);
        $this->logger->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo('notice'),
                $this->equalTo('Caches cleared on {site}.{env}.'),
                $this->equalTo(['site' => $site_name, 'env' => $this->env->id,])
            );

        $out = $this->command->clearCache("$site_name.{$this->env->id}");
        $this->assertNull($out);
    }
}
