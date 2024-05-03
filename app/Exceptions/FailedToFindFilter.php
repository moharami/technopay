<?php

namespace App\Exceptions;

use App\Events\FilterClassNotFoundExceptionOccurred;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class FailedToFindFilter extends Exception
{
    public function report()
    {
        Log::error($this->message());
        Event::dispatch(new FilterClassNotFoundExceptionOccurred($this->message));
    }

    public function render(Request $request)
    {
        return response()->json([
            'message'=> $this->message(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function message()
    {
        return 'failed to find filter for ' . $this->message;
    }
    
    
}
