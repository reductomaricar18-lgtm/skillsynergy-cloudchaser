<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Advanced - SkillSynergy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #232526 0%, #ff512f 100%); min-height: 100vh; }
        .lesson-card { display: flex; flex-direction: column; height: 100%; min-height: 100px; max-height: 400px; position: relative; }
        .lesson-scrollable { flex: 1 1 auto; overflow-y: auto; background: #f8f9fa; padding: 16px; border-radius: 0 0 0 0; margin-bottom: 0; min-height: 160px; height: auto; position: relative; max-height: 400px; }
        .lesson-section { display: none; }
        .lesson-section.active { display: block; }
        .lesson-nav { display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 5px solid #e2e8f0; padding: 16px 48px 16px 48px; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); z-index: 2; flex: 0 0 auto; box-sizing: border-box; position: relative; bottom: 0; left: 0; right: 0; }
        .lesson-nav button { background: #ff512f; color: white; border: none; border-radius: 4px; padding: 8px 18px; font-size: 14px; cursor: pointer; transition: background 0.2s; margin: 0 16px; }
        .lesson-nav button:disabled { background: #b0b0b0; cursor: not-allowed; }
        .lesson-nav button:hover:not(:disabled) { background: #b31217; }
        #otherTopicsDropdown { display: none; position: absolute; bottom: 48px; right: 0; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); z-index: 10; padding: 6px 0; }
        #otherTopicsDropdown .topic-btn { display: block; width: 100%; border: none; background: none; padding: 10px 20px; text-align: left; font-size: 1rem; color: #333; cursor: pointer; transition: background 0.2s; }
        #otherTopicsDropdown .topic-btn:hover { background: #ffe0e0; color: #ff512f; }
        .code-block { background: #263238; color: #fffde7; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .code-comment { color: #81c784; }
        .code-keyword { color: #ffd54f; }
        .code-string { color: #ffb74d; }
        .code-number { color: #4fc3f7; }
        .example-box { background: #fffde7; border: 1px solid #ffe082; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .example-box h4 { color: #f44336; margin-top: 0; }
        .tip-box { background: #ffe0e0; border: 1px solid #ffb74d; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .tip-box strong { color: #b31217; }
        ul, ol { margin: 15px 0; padding-left: 30px; }
        li { margin: 8px 0; }
        strong { color: #263238; }
        .highlight { background: #ffe0e0; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Service Providers</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Service providers are the central place of all Laravel application bootstrapping.</p>
          <div class="code-block">
<span class="code-comment">// Create a service provider</span>
php artisan make:provider CustomServiceProvider

<span class="code-comment">// Register in config/app.php</span>
'providers' => [
    // ...
    App\Providers\CustomServiceProvider::class,
],
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use service providers to bind classes into the service container.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Events & Listeners</h2>
          <p>Events allow you to subscribe and listen for various actions in your app.</p>
          <div class="code-block">
<span class="code-comment">// Create event and listener</span>
php artisan make:event UserRegistered
php artisan make:listener SendWelcomeEmail --event=UserRegistered

<span class="code-comment">// Fire event</span>
event(new UserRegistered($user));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Register events and listeners in <code>EventServiceProvider</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Queues & Jobs</h2>
          <p>Queues allow you to defer the processing of a time-consuming task, such as sending an email.</p>
          <div class="code-block">
<span class="code-comment">// Create a job</span>
php artisan make:job ProcessPodcast

<span class="code-comment">// Dispatch a job</span>
ProcessPodcast::dispatch($podcast);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Configure your queue driver in <code>.env</code> and run <code>php artisan queue:work</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Eloquent</h2>
          <p>Use scopes, mutators, and accessors for powerful model logic.</p>
          <div class="code-block">
<span class="code-comment">// Local scope</span>
<span class="code-keyword">public function</span> scopeActive($query) {
    <span class="code-keyword">return</span> $query->where('active', 1);
}

<span class="code-comment">// Accessor</span>
<span class="code-keyword">public function</span> getFullNameAttribute() {
    <span class="code-keyword">return</span> $this->first_name . ' ' . $this->last_name;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use global scopes for default query constraints.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Policies & Gates</h2>
          <p>Control user authorization logic using policies and gates.</p>
          <div class="code-block">
<span class="code-comment">// Create a policy</span>
php artisan make:policy PostPolicy --model=Post

<span class="code-comment">// In controller</span>
$this->authorize('update', $post);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Register policies in <code>AuthServiceProvider</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>API Authentication (Passport & Sanctum)</h2>
          <p>Secure your APIs using Laravel Passport or Sanctum for token-based authentication.</p>
          <div class="code-block">
<span class="code-comment">// Install Sanctum</span>
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

<span class="code-comment">// Use HasApiTokens in User model</span>
use Laravel\Sanctum\HasApiTokens;

<span class="code-comment">// Protect routes</span>
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use Passport for OAuth2 and Sanctum for simple SPA/mobile API auth.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Testing (Feature & Unit)</h2>
          <p>Write tests to ensure your application works as expected.</p>
          <div class="code-block">
<span class="code-comment">// Feature test example</span>
php artisan make:test UserTest

<span class="code-comment">// In test</span>
public function test_user_can_register() {
    $response = $this->post('/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(302);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>php artisan test</code> to run all tests.
          </div>
        </div>
        <div class="lesson-section" data-index="7">
          <h2>Performance Optimization</h2>
          <ul>
            <li>Use caching (routes, views, queries).</li>
            <li>Optimize database queries (eager loading, indexes).</li>
            <li>Queue heavy tasks.</li>
            <li>Profile with Laravel Telescope or Xdebug.</li>
            <li>Use config and route caching in production.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Run <code>php artisan config:cache</code> and <code>php artisan route:cache</code> for best performance.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #ff512f; position: relative; z-index: 11;">Other Laravel Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Service Providers</button>
          <button class="topic-btn" data-index="1">Events & Listeners</button>
          <button class="topic-btn" data-index="2">Queues & Jobs</button>
          <button class="topic-btn" data-index="3">Advanced Eloquent</button>
          <button class="topic-btn" data-index="4">Policies & Gates</button>
          <button class="topic-btn" data-index="5">API Authentication (Passport & Sanctum)</button>
          <button class="topic-btn" data-index="6">Testing (Feature & Unit)</button>
          <button class="topic-btn" data-index="7">Performance Optimization</button>
        </div>
      </div>
    </div>
    <script>
    (function() {
        const sections = document.querySelectorAll('.lesson-section');
        let current = 0;
        const prevBtn = document.getElementById('prevLessonBtn');
        const nextBtn = document.getElementById('nextLessonBtn');
        const pageInfo = document.getElementById('lessonPageInfo');
        function showSection(idx) {
            sections.forEach((sec, i) => {
                sec.classList.toggle('active', i === idx);
            });
            prevBtn.disabled = idx === 0;
            nextBtn.disabled = idx === sections.length - 1;
            pageInfo.textContent = `Section ${idx + 1} of ${sections.length}`;
            current = idx;
        }
        prevBtn.onclick = function() {
            if (current > 0) {
                current--;
                showSection(current);
            }
        };
        nextBtn.onclick = function() {
            if (current < sections.length - 1) {
                current++;
                showSection(current);
            }
        };
        showSection(current);
        document.getElementById('otherTopicsBtn').onclick = function() {
            var dropdown = document.getElementById('otherTopicsDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        };
        document.querySelectorAll('.topic-btn').forEach(function(btn) {
            btn.onclick = function() {
                var idx = parseInt(btn.getAttribute('data-index'));
                showSection(idx);
                document.getElementById('otherTopicsDropdown').style.display = 'none';
            };
        });
        document.addEventListener('click', function(e) {
            var dropdown = document.getElementById('otherTopicsDropdown');
            var btn = document.getElementById('otherTopicsBtn');
            if (!dropdown.contains(e.target) && e.target !== btn) {
                dropdown.style.display = 'none';
            }
        });
    })();
    </script>
</body>
</html> 