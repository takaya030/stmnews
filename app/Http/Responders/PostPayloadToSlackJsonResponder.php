<?php
declare(strict_types=1);

namespace App\Http\Responders;

class PostPlayloadToSlackJsonResponder extends BaseJsonResponder
{
    private $data;

    public function __construct( $data )
    {
        $this->data = $data;
    }

    protected function getData(): mixed
    {
        return $this->data;
    }
}
