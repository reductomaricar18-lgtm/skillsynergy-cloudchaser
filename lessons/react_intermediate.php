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
    <title>React Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
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
          <h2>useEffect and useRef</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p><strong>useEffect</strong> lets you perform side effects in function components. <strong>useRef</strong> gives you a mutable ref object whose <code>.current</code> property persists across renders.</p>
          <div class="code-block">
<span class="code-comment">// useEffect example</span>
import React, { useEffect, useState } from 'react';

function Timer() {
  const [count, setCount] = useState(0);
  useEffect(() => {
    const id = setInterval(() => setCount(c => c + 1), 1000);
    return () => clearInterval(id);
  }, []);
  return <div>Timer: {count}</div>;
}
          </div>
          <div class="code-block">
<span class="code-comment">// useRef example</span>
import React, { useRef } from 'react';

function FocusInput() {
  const inputRef = useRef();
  return (
    <>
      <input ref={inputRef} />
      <button onClick={() => inputRef.current.focus()}>Focus</button>
    </>
  );
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> useEffect runs after render. useRef does not cause re-render when changed.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>useContext and Prop Drilling</h2>
          <p><strong>useContext</strong> provides a way to pass data through the component tree without passing props down manually at every level. <strong>Prop drilling</strong> is when you pass data through many layers of components.</p>
          <div class="code-block">
<span class="code-comment">// Context example</span>
import React, { createContext, useContext } from 'react';

const ThemeContext = createContext('light');

function ThemedButton() {
  const theme = useContext(ThemeContext);
  return <button className={theme}>Theme: {theme}</button>;
}

function App() {
  return (
    <ThemeContext.Provider value="dark">
      <ThemedButton />
    </ThemeContext.Provider>
  );
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use context to avoid prop drilling for global data like theme or user.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Custom Hooks</h2>
          <p>Custom hooks let you extract and reuse stateful logic between components.</p>
          <div class="code-block">
<span class="code-comment">// Custom hook example</span>
import { useState, useEffect } from 'react';

function useWindowWidth() {
  const [width, setWidth] = useState(window.innerWidth);
  useEffect(() => {
    const handleResize = () => setWidth(window.innerWidth);
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);
  return width;
}

function MyComponent() {
  const width = useWindowWidth();
  return <div>Window width: {width}</div>;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Custom hooks must start with <code>use</code> and can use other hooks inside.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Controlled vs. Uncontrolled Components</h2>
          <p>Controlled components get their value from React state. Uncontrolled components use refs to access DOM values directly.</p>
          <div class="code-block">
<span class="code-comment">// Controlled input</span>
function ControlledInput() {
  const [value, setValue] = useState('');
  return <input value={value} onChange={e => setValue(e.target.value)} />;
}

<span class="code-comment">// Uncontrolled input</span>
function UncontrolledInput() {
  const inputRef = useRef();
  function handleSubmit() {
    alert(inputRef.current.value);
  }
  return (
    <>
      <input ref={inputRef} />
      <button onClick={handleSubmit}>Show Value</button>
    </>
  );
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Prefer controlled components for most cases, but uncontrolled can be useful for simple forms or integrating with non-React code.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Lifting State Up</h2>
          <p>Lifting state up means moving state to the closest common ancestor so that multiple components can share and modify it.</p>
          <div class="code-block">
<span class="code-comment">// Lifting state up example</span>
function Parent() {
  const [value, setValue] = useState('');
  return (
    <>
      <Child value={value} onChange={setValue} />
      <p>Value: {value}</p>
    </>
  );
}
function Child({ value, onChange }) {
  return <input value={value} onChange={e => onChange(e.target.value)} />;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Lift state up to avoid duplicate state and keep data in sync.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Component Composition</h2>
          <p>Compose components using children, render props, or higher-order components for flexible UIs.</p>
          <div class="code-block">
<span class="code-comment">// Children composition</span>
function Card({ children }) {
  return <div className="card">{children}</div>;
}

<span class="code-comment">// Render props</span>
function DataFetcher({ render }) {
  const [data, setData] = useState(null);
  useEffect(() => { setData('Loaded!'); }, []);
  return render(data);
}

function App() {
  return (
    <DataFetcher render={data => <div>{data}</div>} />
  );
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Composition is preferred over inheritance in React.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other React Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">useEffect & useRef</button>
          <button class="topic-btn" data-index="1">useContext & Prop Drilling</button>
          <button class="topic-btn" data-index="2">Custom Hooks</button>
          <button class="topic-btn" data-index="3">Controlled vs. Uncontrolled</button>
          <button class="topic-btn" data-index="4">Lifting State Up</button>
          <button class="topic-btn" data-index="5">Component Composition</button>
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