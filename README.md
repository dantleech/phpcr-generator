PHPCR Node Generator
====================

[![](https://travis-ci.org/dantleech/phpcr-generator.png)](https://travis-ci.org/dantleech/phpcr-generator)


Small library for generating node data for benchmarking, testing, etc.

Example
-------

### Basic

````php
    $converter = new NodeConverter($phpcrSession);
    $builder = new NodeBuilder('node', 'nt:unstructured');

    $builder->node('content', 'nt:unstructured')
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

    $phpcrSession->save();
````

Will result in:

````
node/
    article1/ 
        - title: My First Article
        - body: My First Article body
    article2/
        - title: My First Article
        - body: My First Article body
````

### Ranges

You can also specify ranges in the node name:

````php
        $builder->node('content-[1-5]', 'nt:unstructured')
            ->node('article[1-10]')
                ->property('title', 'My first article')
                ->property('body', 'My first article body')
            ->end()
        ->end();
````

Will result in 50 nodes being created.
