Serialize Doctrine Proxies
==========================

[![Latest Stable Version](https://poser.pugx.org/alcalyn/serializer-doctrine-proxies/v/stable)](https://packagist.org/packages/alcalyn/serializer-doctrine-proxies)
[![Total Downloads](https://poser.pugx.org/alcalyn/serializer-doctrine-proxies/downloads)](https://packagist.org/packages/alcalyn/serializer-doctrine-proxies)
[![License](https://poser.pugx.org/alcalyn/serializer-doctrine-proxies/license)](https://packagist.org/packages/alcalyn/serializer-doctrine-proxies)

Provides Doctrine proxies handler for JMS Serializer to disable lazy-loading and recursion during serialization.

This library solves problems discussed on Stack Overflow,
in [this thread](http://stackoverflow.com/questions/11575345/disable-doctrine-2-lazy-loading-when-using-jms-serializer),
or [this one](http://stackoverflow.com/questions/11851197/avoiding-recursion-with-doctrine-entities-and-jmsserializer),
and is inspired by [this gist](https://gist.github.com/Jaap-van-Hengstum/0d400ea4f986d8f8a044).


## Installation

Using [Composer](https://packagist.org/packages/alcalyn/serializer-doctrine-proxies):

``` js
{
    "require": {
        "alcalyn/serializer-doctrine-proxies": "1.x"
    }
}
```


## Usage

You have to add a handler and a listener to your Serializer instance. Here using SerializerBuilder:

``` php
use Alcalyn\SerializerDoctrineProxies\DoctrineProxyHandler;
use Alcalyn\SerializerDoctrineProxies\DoctrineProxySubscriber;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;

$serializer = SerializerBuilder::create()
    ->addDefaultHandlers() // This line to avoid to default handlers to be overrided by the new one.
    ->configureHandlers(function (HandlerRegistryInterface $handlerRegistry) {
        $handlerRegistry->registerSubscribingHandler(new DoctrineProxyHandler());
    })
    ->configureListeners(function (EventDispatcher $dispatcher) {
        $dispatcher->addSubscriber(new DoctrineProxySubscriber(false)); // false to disable lazy loading.
    })
    ->build()
;

$serializer->serialize($myEntityWithABunchOfRelationsIDontWantToLazyLoadDuringSerialization);
```


## License

This project is under [MIT](LICENSE).
