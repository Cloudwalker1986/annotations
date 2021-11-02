<?php
declare(strict_types=1);

namespace RequestTest\Example;

use Request\Attributes\Parameters\RawBodyParameter;
use Request\Attributes\Route;

class ExampleJsonRawRequestParameters
{
    #[Route('/collection-test', Route::HTTP_METHOD_POST)]
    public function myEndpointForCollection(#[RawBodyParameter] RootCollectionObject $object)
    {

    }
    #[Route('/map-test', Route::HTTP_METHOD_POST)]
    public function myEndpointForMap(#[RawBodyParameter] RootMapObject $object)
    {

    }
}
