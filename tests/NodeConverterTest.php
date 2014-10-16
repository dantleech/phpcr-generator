<?php

namespace DTL\PHPCR\Generator\Tests;

use Jackalope\RepositoryFactoryFilesystem;
use PHPCR\SimpleCredentials;
use DTL\PHPCR\Generator\NodeConverter;
use DTL\PHPCR\Generator\NodeBuilder;
use Symfony\Component\Filesystem\Filesystem;

class NodeConverterTest extends \PHPUnit_Framework_TestCase
{
    private $nodeConverter;
    private $builder;
    private $session;

    public function setUp()
    {
        $dir = __DIR__ . '/../temp-data';
        $fs = new Filesystem();
        $fs->remove($dir);

        $factory = new RepositoryFactoryFilesystem();
        $repository = $factory->getRepository(array(
            'path' => $dir,
            'search_enabled' => false,
        ));
        $credentials = new SimpleCredentials('admin', 'admin');
        $this->session = $repository->login($credentials);

        $this->converter = new NodeConverter($this->session);
        $this->builder = new NodeBuilder('cmf', 'nt:unstructured');
    }

    public function testGenerator()
    {
        $this->builder
            ->node('content', 'nt:unstructured')
                ->node('article1')
                    ->property('title', 'My first article')
                    ->property('body', 'My first article body')
                ->end()
                ->node('article2')
                    ->property('title', 'My first article')
                    ->property('body', 'My first article body')
                ->end()
            ->end();

        $this->converter->convert($this->builder);
    }

    public function testGeneratorWithRange()
    {
        $this->builder
            ->node('content-[1-5]', 'nt:unstructured')
                ->node('article[50-60]')
                    ->property('title', 'My first article')
                    ->property('body', 'My first article body')
                ->end()
            ->end();

        $this->converter->convert($this->builder);
        $this->assertEquals(61, $this->converter->getCount());
        $this->session->save();
    }
}
