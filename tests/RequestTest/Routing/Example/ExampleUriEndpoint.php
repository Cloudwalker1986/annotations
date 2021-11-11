<?php
declare(strict_types=1);

namespace RequestTest\Routing\Example;

use Request\Attributes\Route;
use Request\Response\Rest\ResponseNoContent;

/**
 * @package RequestTest\Routing\Example
 * @author Dennis Munchausen
 */
class ExampleUriEndpoint
{
    #[Route("/product/\d/edit", Route::HTTP_METHOD_PATCH)]
    public function editTheProduct()
    {
        return new ResponseNoContent();
    }
}