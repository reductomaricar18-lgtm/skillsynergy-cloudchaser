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
    <title>PostgreSQL Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
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
          <h2>Window Functions</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Window functions perform calculations across rows related to the current row, without collapsing results.</p>
          <div class="code-block">
<span class="code-comment">-- ROW_NUMBER, RANK, SUM OVER</span>
SELECT name, salary,
  ROW_NUMBER() OVER (ORDER BY salary DESC) AS row_num,
  RANK() OVER (ORDER BY salary DESC) AS rank,
  SUM(salary) OVER (PARTITION BY dept_id) AS dept_total
FROM employees;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Window functions are powerful for analytics and reporting.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>CTEs (WITH Queries)</h2>
          <p>Common Table Expressions (CTEs) simplify complex queries and support recursion.</p>
          <div class="code-block">
<span class="code-comment">-- Simple CTE</span>
WITH high_earners AS (
  SELECT * FROM employees WHERE salary > 100000
)
SELECT * FROM high_earners;

<span class="code-comment">-- Recursive CTE</span>
WITH RECURSIVE nums(n) AS (
  SELECT 1
  UNION ALL
  SELECT n+1 FROM nums WHERE n < 5
)
SELECT * FROM nums;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Recursive CTEs are useful for hierarchical data (trees, graphs).
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Triggers</h2>
          <p>Triggers automatically execute functions in response to table events (INSERT, UPDATE, DELETE).</p>
          <div class="code-block">
<span class="code-comment">-- Trigger function</span>
CREATE OR REPLACE FUNCTION log_update() RETURNS trigger AS $$
BEGIN
  INSERT INTO updates_log(table_name, updated_at) VALUES (TG_TABLE_NAME, NOW());
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

<span class="code-comment">-- Create trigger</span>
CREATE TRIGGER after_update
AFTER UPDATE ON employees
FOR EACH ROW EXECUTE FUNCTION log_update();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Triggers are great for auditing, enforcing rules, and automation.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Indexing</h2>
          <p>PostgreSQL supports partial, expression, and covering indexes for performance tuning.</p>
          <div class="code-block">
<span class="code-comment">-- Partial index</span>
CREATE INDEX idx_active_users ON users(email) WHERE active = true;

<span class="code-comment">-- Expression index</span>
CREATE INDEX idx_lower_email ON users(LOWER(email));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use advanced indexes to optimize specific queries and workloads.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Partitioning</h2>
          <p>Partitioning splits large tables into smaller, more manageable pieces for performance and maintenance.</p>
          <div class="code-block">
<span class="code-comment">-- Range partitioning</span>
CREATE TABLE sales (
  id SERIAL PRIMARY KEY,
  sale_date DATE
) PARTITION BY RANGE (sale_date);

CREATE TABLE sales_2023 PARTITION OF sales
  FOR VALUES FROM ('2023-01-01') TO ('2024-01-01');
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Partitioning is essential for very large tables and time-series data.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Performance Tuning</h2>
          <p>Use <code>EXPLAIN</code>, <code>ANALYZE</code>, and <code>VACUUM</code> to optimize queries and maintain database health.</p>
          <div class="code-block">
<span class="code-comment">-- Query plan</span>
EXPLAIN SELECT * FROM employees WHERE salary > 100000;

<span class="code-comment">-- Analyze query</span>
EXPLAIN ANALYZE SELECT * FROM employees WHERE salary > 100000;

<span class="code-comment">-- Vacuum</span>
VACUUM (VERBOSE, ANALYZE);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Regularly analyze and vacuum your database for best performance.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Security</h2>
          <p>Manage access with roles, privileges, and row-level security.</p>
          <div class="code-block">
<span class="code-comment">-- Create role and grant privileges</span>
CREATE ROLE analyst LOGIN PASSWORD 'secret';
GRANT SELECT ON employees TO analyst;

<span class="code-comment">-- Row-level security</span>
ALTER TABLE employees ENABLE ROW LEVEL SECURITY;
CREATE POLICY emp_policy ON employees
  USING (dept_id = current_setting('my.dept_id')::int);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use least privilege and enable row-level security for sensitive data.
          </div>
        </div>
        <div class="lesson-section" data-index="7">
          <h2>PL/pgSQL Advanced Features</h2>
          <p>Use loops, error handling, and triggers in PL/pgSQL for complex logic.</p>
          <div class="code-block">
<span class="code-comment">-- Loop and exception handling</span>
CREATE OR REPLACE FUNCTION process_employees() RETURNS void AS $$
DECLARE
  rec RECORD;
BEGIN
  FOR rec IN SELECT * FROM employees LOOP
    BEGIN
      -- process each employee
      RAISE NOTICE 'Processing %', rec.name;
    EXCEPTION WHEN OTHERS THEN
      RAISE WARNING 'Error processing %', rec.name;
    END;
  END LOOP;
END;
$$ LANGUAGE plpgsql;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> PL/pgSQL enables procedural logic and automation inside PostgreSQL.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other PostgreSQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Window Functions</button>
          <button class="topic-btn" data-index="1">CTEs (WITH Queries)</button>
          <button class="topic-btn" data-index="2">Triggers</button>
          <button class="topic-btn" data-index="3">Advanced Indexing</button>
          <button class="topic-btn" data-index="4">Partitioning</button>
          <button class="topic-btn" data-index="5">Performance Tuning</button>
          <button class="topic-btn" data-index="6">Security</button>
          <button class="topic-btn" data-index="7">PL/pgSQL Advanced</button>
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