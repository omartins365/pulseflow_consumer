<?php
namespace GenioForge\Consumer;
use GenioForge\Consumer\Repository\RepositoryProvider;
class ConsumerServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function has_registered_services()
    {
        $this->assertTrue($this->app->bound('consumer'));

        $this->assertInstanceOf(RepositoryProvider::class, $this->app->make('consumer'));
    }

    /**
     * @test
     */
    public function has_registered_aliases()
    {
        $this->assertTrue($this->app->isAlias(RepositoryProvider::class));
        $this->assertEquals('consumer', $this->app->getAlias(RepositoryProvider::class));
    }

    /**
     * @test
     */
    public function has_registered_package_config()
    {
        $config = $this->app->make('config');

       
    }

    /**
     * @test
     */
    public function does_provide_singleton_instance ()
    {
        $this->assertSame($this->app->make('consumer'), $this->app->make('consumer'));
    }

}
