<?php

namespace Drest\Mapping\Annotation;

use Drest\Mapping\Annotation\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Resource implements Annotation
{
    /** @var string */
    public $name;
	/** @var object */
    public $route;
    /** @var string */
    public $collection_route;
    /** @var array */
    public $writers = array();
}