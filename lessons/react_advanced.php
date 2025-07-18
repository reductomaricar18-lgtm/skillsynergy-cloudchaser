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
    <title>React Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ee9ca7 0%, #ffdde1 100%);
            min-height: 100vh;
        }
        .lesson-card { display: flex; flex-direction: column; height: 100%; min-height: 100px; max-height: 400px; position: relative; }
        .lesson-scrollable { flex: 1 1 auto; overflow-y: auto; background: #f8f9fa; padding: 16px; border-radius: 0 0 0 0; margin-bottom: 0; min-height: 160px; height: auto; position: relative; max-height: 400px; }
        .lesson-section { display: none; }
        .lesson-section.active { display: block; }
        .lesson-nav { display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 5px solid #e2e8f0; padding: 16px 48px 16px 48px; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); z-index: 2; flex: 0 0 auto; box-sizing: border-box; position: relative; bottom: 0; left: 0; right: 0; }
        .lesson-nav button { background: #007bff; color: white; border: none; border-radius: 4px; padding: 8px 18px; font-size: 14px; cursor: pointer; transition: background 0.2s; margin: 0 16px; }
        .lesson-nav button:disabled { background: #b0b0b0; cursor: not-allowed; }
        .lesson-nav button:hover:not(:disabled) { background: #0056b3; }
        #otherTopicsDropdown { display: none; position: absolute; bottom: 48px; right: 0; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); z-index: 10; padding: 6px 0; }
        #otherTopicsDropdown .topic-btn { display: block; width: 100%; border: none; background: none; padding: 10px 20px; text-align: left; font-size: 1rem; color: #333; cursor: pointer; transition: background 0.2s; }
        #otherTopicsDropdown .topic-btn:hover { background: #f0f4ff; color: #4a63ff; }
        .code-block { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .code-comment { color: #68d391; }
        .code-keyword { color: #f6ad55; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .example-box h4 { color: #2c7a7b; margin-top: 0; }
        .tip-box { background: #fef5e7; border: 1px solid #f6ad55; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .tip-box strong { color: #c05621; }
        ul, ol { margin: 15px 0; padding-left: 30px; }
        li { margin: 8px 0; }
        strong { color: #2d3748; }
        .highlight { background: #fef5e7; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Performance Optimization</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Optimize React apps using <code>React.memo</code>, <code>useMemo</code>, and <code>useCallback</code> to avoid unnecessary renders.</p>
          <div class="code-block">
<span class="code-comment">// React.memo</span>
const MyComponent = React.memo(function MyComponent(props) {
  /* ... */
});

<span class="code-comment">// useMemo</span>
const expensiveValue = useMemo(() => computeExpensive(a, b), [a, b]);

<span class="code-comment">// useCallback</span>
const handleClick = useCallback(() => { /* ... */ }, [dep]);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Only optimize when you see performance issues. Premature optimization can make code harder to maintain.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Code Splitting & Lazy Loading</h2>
          <p>Split code into smaller bundles with <code>React.lazy</code> and <code>Suspense</code> for faster load times.</p>
          <div class="code-block">
<span class="code-comment">// Lazy loading a component</span>
const OtherComponent = React.lazy(() => import('./OtherComponent'));

function App() {
  return (
    <React.Suspense fallback={<div>Loading...</div>}>
      <OtherComponent />
    </React.Suspense>
  );
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use code splitting for large apps to improve initial load performance.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Error Boundaries</h2>
          <p>Catch JavaScript errors anywhere in the component tree using error boundaries (class components only).</p>
          <div class="code-block">
<span class="code-comment">// Error boundary example</span>
class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false };
  }
  static getDerivedStateFromError(error) {
    return { hasError: true };
  }
  componentDidCatch(error, info) {
    // log error
  }
  render() {
    if (this.state.hasError) {
      return <h1>Something went wrong.</h1>;
    }
    return this.props.children;
  }
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use error boundaries to prevent the entire app from crashing due to a single error.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Context</h2>
          <p>Use dynamic context values and context selectors for fine-grained updates.</p>
          <div class="code-block">
<span class="code-comment">// Dynamic context value</span>
const LocaleContext = React.createContext('en');

function App() {
  const [locale, setLocale] = useState('en');
  return (
    <LocaleContext.Provider value={locale}>
      <Page />
    </LocaleContext.Provider>
  );
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Context selectors (with libraries) can help avoid unnecessary re-renders.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Testing React Apps</h2>
          <p>Use <code>Jest</code> and <code>React Testing Library</code> for unit and integration testing.</p>
          <div class="code-block">
<span class="code-comment">// Simple test with React Testing Library</span>
import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import App from './App';

test('renders learn react link', () => {
  render(<App />);
  const linkElement = screen.getByText(/learn react/i);
  expect(linkElement).toBeInTheDocument();
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Write tests for critical logic and user flows, not just for coverage.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Advanced Patterns</h2>
          <p>Use render props, higher-order components (HOC), and compound components for flexible APIs.</p>
          <div class="code-block">
<span class="code-comment">// Render props</span>
function Mouse({ render }) {
  const [pos, setPos] = useState({ x: 0, y: 0 });
  return <div onMouseMove={e => setPos({ x: e.clientX, y: e.clientY })}>{render(pos)}</div>;
}

<span class="code-comment">// HOC</span>
function withLogger(Component) {
  return function Wrapped(props) {
    useEffect(() => { console.log('Mounted'); }, []);
    return <Component {...props} />;
  };
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Prefer composition and hooks over HOCs and render props in new code.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other React Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Performance Optimization</button>
          <button class="topic-btn" data-index="1">Code Splitting & Lazy Loading</button>
          <button class="topic-btn" data-index="2">Error Boundaries</button>
          <button class="topic-btn" data-index="3">Advanced Context</button>
          <button class="topic-btn" data-index="4">Testing React Apps</button>
          <button class="topic-btn" data-index="5">Advanced Patterns</button>
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