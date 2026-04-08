<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrawlRoutesCommand extends Command
{
    protected $signature = 'route:crawl';
    protected $description = 'Crawls all GET routes with different roles and reports 500 errors';

    public function handle()
    {
        $this->info("Starting Route Crawler...");

        // Setup dummy data
        $user = \App\Models\User::first();
        $admin = \App\Models\Admin::first() ?? \App\Models\User::first();
        $agent = \App\Models\Agent::first() ?? \App\Models\User::first();

        // Ensure we have models for route bindings
        $task = \App\Models\Task::first();
        $submission = \App\Models\TaskSubmission::first();
        $deposit = \App\Models\Deposit::first();
        $withdrawal = \App\Models\Withdrawal::first();

        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return in_array('GET', $route->methods()) && str_starts_with($route->uri(), 'user') || str_starts_with($route->uri(), 'admin') || str_starts_with($route->uri(), 'agent');
        });

        $app = app();

        foreach ($routes as $route) {
            $uri = $route->uri();

            // Replace bindings with actual IDs for testing
            if (Str::contains($uri, '{task}') && $task) {
                $uri = str_replace('{task}', $task->id, $uri);
            }
            if (Str::contains($uri, '{submission}') && $submission) {
                $uri = str_replace('{submission}', $submission->id, $uri);
            }
            if (Str::contains($uri, '{deposit}') && $deposit) {
                $uri = str_replace('{deposit}', $deposit->id, $uri);
            }
            if (Str::contains($uri, '{withdrawal}') && $withdrawal) {
                $uri = str_replace('{withdrawal}', $withdrawal->id, $uri);
            }
            if (Str::contains($uri, '{user}') && $user) {
                $uri = str_replace('{user}', $user->id, $uri);
            }
            if (Str::contains($uri, '{agent}') && $agent) {
                $uri = str_replace('{agent}', $agent->id, $uri);
            }
            
            // Skip any remaining bound variables we can't fill easily
            if (Str::contains($uri, '{')) {
                continue;
            }

            // Determine guard
            $guard = 'web';
            $actingAs = $user;
            if (Str::startsWith($uri, 'admin')) {
                $guard = 'admin';
                $actingAs = $admin;
            } elseif (Str::startsWith($uri, 'agent')) {
                $guard = 'agent';
                $actingAs = $agent;
            }

            if (!$actingAs) {
                $this->warn("Skipping $uri (No user role available)");
                continue;
            }

            // Dispatch Request
            Auth::guard($guard)->login($actingAs);
            
            try {
                $request = Request::create($uri, 'GET');
                $response = $app->handle($request);
                
                if ($response->getStatusCode() == 500) {
                    $this->error("500 ERROR: $uri");
                    // get the exception if possible
                    if (isset($response->exception)) {
                        $this->error($response->exception->getMessage());
                    }
                } elseif ($response->getStatusCode() >= 400 && $response->getStatusCode() != 401 && $response->getStatusCode() != 403 && $response->getStatusCode() != 404) {
                    $this->warn("HTTP {$response->getStatusCode()}: $uri");
                } else {
                    $this->info("OK: $uri");
                }
            } catch (\Exception $e) {
                $this->error("CRASH: $uri -> " . $e->getMessage());
            }
            
            Auth::guard($guard)->logout();
        }

        $this->info("Crawl complete.");
    }
}
