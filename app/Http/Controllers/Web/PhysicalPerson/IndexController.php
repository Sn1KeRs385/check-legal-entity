<?php

namespace App\Http\Controllers\Web\PhysicalPerson;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('PhysicalPerson/Index');
    }
}
